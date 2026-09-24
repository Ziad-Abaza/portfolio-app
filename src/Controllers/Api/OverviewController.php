<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Models\ContactMessage;
use App\Models\Metric;
use App\Models\Project;
use App\Models\Section;
use App\Models\Skill;

final class OverviewController
{
    public function index(Request $req): Response
    {
        return Response::json([
            'stats' => [
                'projects' => Project::count(),
                'published' => (int) DB::fetchColumn("SELECT COUNT(*) FROM projects WHERE status = 'published'"),
                'sections_visible' => (int) DB::fetchColumn('SELECT COUNT(*) FROM sections WHERE visible = 1'),
                'skills' => Skill::count(),
                'metrics' => Metric::count(),
                'unread_messages' => ContactMessage::unreadCount(),
            ],
            'recent_messages' => DB::fetchAll('SELECT id, name, email, locale, created_at, read_at FROM contact_messages ORDER BY id DESC LIMIT 5'),
        ]);
    }
}
