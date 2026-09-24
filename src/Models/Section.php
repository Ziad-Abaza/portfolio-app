<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use App\Core\Model;

final class Section extends Model
{
    protected static string $table = 'sections';
    protected static array $jsonFields = ['name', 'props'];

    /** Visible sections keyed by `key`, in display order. */
    public static function visibleMap(): array
    {
        $map = [];
        foreach (DB::fetchAll('SELECT * FROM sections WHERE visible = 1 ORDER BY sort_order') as $row) {
            $map[$row['key']] = self::decode($row);
        }
        return $map;
    }
}
