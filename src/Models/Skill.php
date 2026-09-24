<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use App\Core\Model;

final class Skill extends Model
{
    protected static string $table = 'skills';
    protected static array $jsonFields = ['grp', 'note'];

    /** Skills grouped by their (JSON) group label, preserving order. */
    public static function grouped(): array
    {
        $groups = [];
        foreach (self::all('sort_order') as $skill) {
            $key = is_array($skill['grp']) ? ($skill['grp']['en'] ?? 'misc') : (string) $skill['grp'];
            $groups[$key][] = $skill;
        }
        return $groups;
    }
}
