<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Models\ContactMessage;

final class SpaController
{
    public function index(Request $req): Response
    {
        return Response::view('admin/app', [
            'boot' => [
                'csrf' => Csrf::token(),
                'user' => Auth::user(),
                'unread' => ContactMessage::unreadCount(),
            ],
        ], 200, layout: null);
    }
}
