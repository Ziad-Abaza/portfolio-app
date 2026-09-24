<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Metric extends Model
{
    protected static string $table = 'metrics';
    protected static array $jsonFields = ['label', 'context'];
}
