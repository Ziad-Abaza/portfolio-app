<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Thin active-record-ish base. Rows are plain arrays — views stay honest
 * about what they render. JSON columns are declared in $jsonFields and
 * decoded on read, encoded on write.
 */
abstract class Model
{
    protected static string $table = '';

    /** @var array<int,string> columns holding JSON payloads */
    protected static array $jsonFields = [];

    public static function all(string $orderBy = 'id', string $dir = 'ASC'): array
    {
        $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        return array_map(self::decode(...), DB::fetchAll("SELECT * FROM `" . static::$table . "` ORDER BY `{$orderBy}` {$dir}"));
    }

    public static function find(int $id): ?array
    {
        $row = DB::fetch('SELECT * FROM `' . static::$table . '` WHERE id = ? LIMIT 1', [$id]);
        return $row === null ? null : self::decode($row);
    }

    public static function where(string $column, mixed $value, string $orderBy = 'id'): array
    {
        return array_map(
            self::decode(...),
            DB::fetchAll('SELECT * FROM `' . static::$table . '` WHERE `' . $column . '` = ? ORDER BY `' . $orderBy . '`', [$value])
        );
    }

    public static function firstWhere(string $column, mixed $value): ?array
    {
        $row = DB::fetch('SELECT * FROM `' . static::$table . '` WHERE `' . $column . '` = ? LIMIT 1', [$value]);
        return $row === null ? null : self::decode($row);
    }

    public static function create(array $data): int
    {
        return DB::insert(static::$table, self::encode($data));
    }

    public static function update(int $id, array $data): int
    {
        return DB::update(static::$table, self::encode($data), 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return DB::delete(static::$table, 'id = ?', [$id]);
    }

    public static function count(): int
    {
        return (int) DB::fetchColumn('SELECT COUNT(*) FROM `' . static::$table . '`');
    }

    protected static function decode(array $row): array
    {
        foreach (static::$jsonFields as $field) {
            if (isset($row[$field]) && is_string($row[$field])) {
                $row[$field] = json_decode($row[$field], true);
            }
        }
        return $row;
    }

    protected static function encode(array $data): array
    {
        foreach (static::$jsonFields as $field) {
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field], JSON_UNESCAPED_UNICODE);
            }
        }
        return $data;
    }
}
