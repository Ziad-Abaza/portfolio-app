<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Synchronizer-token CSRF protection. Token lives server-side in the session;
 * compared with hash_equals to resist timing attacks.
 */
final class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        $token = Session::get(self::KEY);
        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::set(self::KEY, $token);
        }
        return $token;
    }

    public static function validate(Request $request): bool
    {
        $provided = $request->body['_token'] ?? $request->header('x-csrf-token') ?? '';
        $expected = Session::get(self::KEY, '');
        return is_string($provided)
            && is_string($expected)
            && $expected !== ''
            && hash_equals($expected, $provided);
    }
}
