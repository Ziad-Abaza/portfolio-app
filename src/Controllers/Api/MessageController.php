<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Models\ContactMessage;

final class MessageController
{
    public function index(Request $req): Response
    {
        return Response::json(['data' => ContactMessage::all('id', 'DESC')]);
    }

    public function markRead(Request $req): Response
    {
        ContactMessage::markRead((int) $req->param('id'));
        return Response::json(['ok' => true]);
    }

    public function destroy(Request $req): Response
    {
        ContactMessage::delete((int) $req->param('id'));
        return Response::json(['ok' => true]);
    }
}
