<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Media;

final class MediaController
{
    private const ALLOWED_MIME = [
        'image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/gif', 'image/svg+xml',
        'application/pdf',
    ];

    public function show(Request $req): Response
    {
        $media = Media::find((int) $req->param('id'));
        if ($media === null) {
            return Response::html('Not found', 404);
        }
        // Filenames are server-generated randoms; still enforce basename + mime whitelist.
        $filename = basename((string) $media['filename']);
        $path = storage_path('uploads/' . $filename);
        if (!is_file($path) || !in_array($media['mime'], self::ALLOWED_MIME, true)) {
            return Response::html('Not found', 404);
        }
        $response = Response::download($path, (string) $media['orig_name'], (string) $media['mime']);
        $response->headers['Cache-Control'] = 'public, max-age=31536000, immutable';
        return $response;
    }
}
