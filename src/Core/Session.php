<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Hardened session wrapper: HttpOnly + SameSite=Lax cookies, Secure in prod,
 * two-bucket flash data (new = written this request, old = readable this request).
 */
final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_name('ZHSESS');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => env('APP_ENV') === 'production',
        ]);
        session_start();

        // Age flash data: last request's "new" becomes this request's "old".
        $_SESSION['_flash_old'] = $_SESSION['_flash_new'] ?? [];
        $_SESSION['_flash_new'] = [];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /** Write flash data — readable on the NEXT request only. */
    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash_new'][$key] = $value;
    }

    /** Read flash data written by the previous request. */
    public static function flashGet(string $key, mixed $default = null): mixed
    {
        return $_SESSION['_flash_old'][$key] ?? $default;
    }

    public static function flashInput(array $input): void
    {
        $_SESSION['_flash_new']['_old_input'] = $input;
    }
}
