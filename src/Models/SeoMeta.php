<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class SeoMeta extends Model
{
    protected static string $table = 'seo_meta';
    protected static array $jsonFields = ['title', 'description'];

    public static function forPage(string $page): ?array
    {
        return self::firstWhere('page', $page);
    }
}
