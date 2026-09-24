<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use App\Core\Model;

final class ContactMessage extends Model
{
    protected static string $table = 'contact_messages';

    public static function unreadCount(): int
    {
        return (int) DB::fetchColumn('SELECT COUNT(*) FROM contact_messages WHERE read_at IS NULL');
    }

    public static function markRead(int $id): void
    {
        DB::query("UPDATE contact_messages SET read_at = datetime('now') WHERE id = ?", [$id]);
    }
}
