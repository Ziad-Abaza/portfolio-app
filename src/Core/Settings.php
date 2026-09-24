<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Key-value settings store. Values are JSON-encoded so scalars, arrays and
 * maps round-trip transparently. Whole-store cache, invalidated on write.
 */
final class Settings
{
    private static ?array $cache = null;

    public static function all(): array
    {
        if (self::$cache === null) {
            self::$cache = [];
            foreach (DB::fetchAll('SELECT `key`, `value` FROM settings') as $row) {
                $decoded = json_decode((string) $row['value'], true);
                self::$cache[$row['key']] = $decoded ?? $row['value'];
            }
        }
        return self::$cache;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        DB::query(
            'INSERT INTO settings (`key`, `value`) VALUES (?, ?)
             ON CONFLICT(`key`) DO UPDATE SET `value` = excluded.`value`',
            [$key, json_encode($value, JSON_UNESCAPED_UNICODE)]
        );
        self::$cache = null;
    }

    public static function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            self::set($key, $value);
        }
    }

    public static function forget(string $key): void
    {
        DB::query('DELETE FROM settings WHERE `key` = ?', [$key]);
        self::$cache = null;
    }
}
