<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use App\Core\Model;

final class SocialLink extends Model
{
    protected static string $table = 'social_links';

    public static function visible(): array
    {
        return DB::fetchAll('SELECT * FROM social_links WHERE visible = 1 ORDER BY sort_order');
    }
}
