<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Models\Media;

final class MediaApiController
{
    private const MAX_BYTES = 4 * 1024 * 1024; // 4 MB
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/avif' => 'avif',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg',
        'application/pdf' => 'pdf',
    ];

    public function index(Request $req): Response
    {
        return Response::json(['data' => Media::all('id', 'DESC')]);
    }

    public function store(Request $req): Response
    {
        $file = $req->files['file'] ?? null;
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return Response::json(['error' => 'No file uploaded'], 422);
        }
        if (($file['size'] ?? 0) > self::MAX_BYTES) {
            return Response::json(['error' => 'File exceeds 4 MB'], 422);
        }

        // Real MIME via finfo — never trust the client-supplied type.
        $mime = (string) (new \finfo(FILEINFO_MIME_TYPE))->file((string) $file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            return Response::json(['error' => 'File type not allowed'], 422);
        }

        // SVG can carry scripts — sanitize by rejecting script-bearing markup.
        if ($mime === 'image/svg+xml') {
            $svg = (string) file_get_contents((string) $file['tmp_name']);
            if (preg_match('/<(script|foreignObject)|on\w+\s*=|javascript:/i', $svg)) {
                return Response::json(['error' => 'SVG contains active content'], 422);
            }
        }

        $filename = bin2hex(random_bytes(16)) . '.' . self::ALLOWED[$mime];
        $dest = storage_path('uploads/' . $filename);
        if (!move_uploaded_file((string) $file['tmp_name'], $dest)) {
            return Response::json(['error' => 'Upload failed'], 500);
        }

        $id = Media::create([
            'filename' => $filename,
            'orig_name' => basename((string) ($file['name'] ?? $filename)),
            'mime' => $mime,
            'size' => (int) $file['size'],
            'alt' => is_array($req->body['alt'] ?? null) ? $req->body['alt'] : [],
        ]);

        return Response::json(['data' => Media::find($id)], 201);
    }

    public function destroy(Request $req): Response
    {
        $media = Media::find((int) $req->param('id'));
        if ($media !== null) {
            $path = storage_path('uploads/' . basename((string) $media['filename']));
            if (is_file($path)) {
                unlink($path);
            }
            Media::delete((int) $media['id']);
        }
        return Response::json(['ok' => true]);
    }
}
