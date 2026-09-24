<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use App\Core\Model;

final class Project extends Model
{
    protected static string $table = 'projects';
    protected static array $jsonFields = ['title', 'summary', 'body', 'role', 'domain', 'stack', 'links', 'cover', 'metrics'];

    public static function published(bool $featuredOnly = false): array
    {
        $sql = "SELECT * FROM projects WHERE status = 'published'";
        if ($featuredOnly) {
            $sql .= ' AND featured = 1';
        }
        $sql .= ' ORDER BY featured DESC, sort_order ASC, published_at DESC';
        return array_map(self::decode(...), DB::fetchAll($sql));
    }

    public static function findBySlug(string $slug): ?array
    {
        $row = DB::fetch("SELECT * FROM projects WHERE slug = ? AND status = 'published' LIMIT 1", [$slug]);
        return $row === null ? null : self::decode($row);
    }

    public static function blocks(int $projectId): array
    {
        return array_map(
            fn (array $r) => [...$r, 'content' => json_decode((string) $r['content'], true)],
            DB::fetchAll('SELECT * FROM case_study_blocks WHERE project_id = ? ORDER BY sort_order', [$projectId])
        );
    }
}
