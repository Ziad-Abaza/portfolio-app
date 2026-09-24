<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Fixed-window rate limiter backed by the rate_limits table.
 * Used for login attempts and the contact form.
 */
final class RateLimiter
{
    public static function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        $row = DB::fetch('SELECT attempts, reset_at FROM rate_limits WHERE `key` = ?', [$key]);
        if ($row === null) {
            return false;
        }
        if ((int) $row['reset_at'] <= time()) {
            self::clear($key);
            return false;
        }
        return (int) $row['attempts'] >= $maxAttempts;
    }

    public static function hit(string $key, int $decaySeconds = 60): int
    {
        $now = time();
        $row = DB::fetch('SELECT attempts, reset_at FROM rate_limits WHERE `key` = ?', [$key]);
        if ($row === null || (int) $row['reset_at'] <= $now) {
            DB::query(
                'INSERT INTO rate_limits (`key`, attempts, reset_at) VALUES (?, 1, ?)
                 ON CONFLICT(`key`) DO UPDATE SET attempts = 1, reset_at = excluded.reset_at',
                [$key, $now + $decaySeconds]
            );
            return 1;
        }
        DB::query('UPDATE rate_limits SET attempts = attempts + 1 WHERE `key` = ?', [$key]);
        return (int) $row['attempts'] + 1;
    }

    public static function clear(string $key): void
    {
        DB::query('DELETE FROM rate_limits WHERE `key` = ?', [$key]);
    }

    /** Seconds until the window resets (for Retry-After / user messaging). */
    public static function availableIn(string $key): int
    {
        $row = DB::fetch('SELECT reset_at FROM rate_limits WHERE `key` = ?', [$key]);
        return $row === null ? 0 : max(0, (int) $row['reset_at'] - time());
    }
}
