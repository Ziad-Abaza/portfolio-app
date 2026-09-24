<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $user = DB::fetch(
            'SELECT id, email, name, password_hash FROM users WHERE email = ? LIMIT 1',
            [mb_strtolower(trim($email))]
        );
        if ($user === null || !password_verify($password, (string) $user['password_hash'])) {
            return false;
        }
        // Rehash transparently when the algorithm/parameters improved.
        if (password_needs_rehash((string) $user['password_hash'], PASSWORD_ARGON2ID)) {
            DB::update('users', ['password_hash' => password_hash($password, PASSWORD_ARGON2ID)], 'id = ?', [$user['id']]);
        }
        Session::regenerate();
        Session::set('user_id', (int) $user['id']);
        return true;
    }

    public static function check(): bool
    {
        return Session::get('user_id') !== null;
    }

    public static function user(): ?array
    {
        $id = Session::get('user_id');
        if ($id === null) {
            return null;
        }
        return DB::fetch('SELECT id, email, name FROM users WHERE id = ?', [(int) $id]);
    }

    public static function logout(): void
    {
        Session::forget('user_id');
        Session::regenerate();
    }
}
