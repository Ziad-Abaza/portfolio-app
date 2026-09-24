<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;
use Throwable;

/**
 * PDO gateway. Prepared statements only — there is no raw-query path that
 * concatenates input. insert()/update() quote identifiers with backticks
 * (valid in both SQLite and MySQL).
 */
final class DB
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $dsn = (string) config('db.dsn');
            if ($dsn === '') {
                $dsn = 'sqlite:' . base_path((string) config('db.path', 'database/portfolio.sqlite'));
            }
            self::$pdo = new PDO(
                $dsn,
                config('db.user'),
                config('db.password'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            if (str_starts_with($dsn, 'sqlite:')) {
                self::$pdo->exec('PRAGMA foreign_keys = ON');
                self::$pdo->exec('PRAGMA journal_mode = WAL');
            }
        }
        return self::$pdo;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchColumn(string $sql, array $params = []): mixed
    {
        return self::query($sql, $params)->fetchColumn();
    }

    public static function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s)',
            $table,
            implode(', ', array_map(fn (string $c) => "`{$c}`", $columns)),
            implode(', ', array_fill(0, count($columns), '?'))
        );
        self::query($sql, array_values($data));
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $set = implode(', ', array_map(fn (string $c) => "`{$c}` = ?", array_keys($data)));
        $stmt = self::query("UPDATE `{$table}` SET {$set} WHERE {$where}", [...array_values($data), ...$params]);
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        return self::query("DELETE FROM `{$table}` WHERE {$where}", $params)->rowCount();
    }

    public static function transaction(callable $callback): mixed
    {
        $pdo = self::pdo();
        $pdo->beginTransaction();
        try {
            $result = $callback();
            $pdo->commit();
            return $result;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
