<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use App\Core\Model;

final class ThemePreset extends Model
{
    protected static string $table = 'themes';
    protected static array $jsonFields = ['tokens'];

    public static function activate(int $id): void
    {
        DB::transaction(function () use ($id): void {
            DB::query('UPDATE themes SET is_active = 0');
            DB::query('UPDATE themes SET is_active = 1 WHERE id = ?', [$id]);
        });
    }
}
