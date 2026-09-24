<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Settings;
use App\Core\Validator;
use App\Models\SeoMeta;

final class SystemController
{
    /** Only these settings keys are writable through the API. */
    private const WRITABLE_SETTINGS = [
        'site.name', 'site.role', 'site.tagline', 'site.email', 'site.location',
        'site.availability', 'theme.default_mode', 'locales.enabled', 'locales.default',
        'seo.og_default', 'footer.note',
    ];

    public function seoIndex(Request $req): Response
    {
        return Response::json(['data' => SeoMeta::all('id')]);
    }

    public function seoUpdate(Request $req): Response
    {
        $items = $req->body['items'] ?? [];
        if (!is_array($items)) {
            return Response::json(['error' => 'items must be an array'], 422);
        }
        DB::transaction(function () use ($items): void {
            foreach ($items as $item) {
                $page = (string) ($item['page'] ?? '');
                if ($page === '' || !preg_match('/^[a-z0-9:_-]+$/', $page)) {
                    continue;
                }
                DB::query(
                    'INSERT INTO seo_meta (page, title, description, og_image) VALUES (?, ?, ?, ?)
                     ON CONFLICT(page) DO UPDATE SET title = excluded.title, description = excluded.description, og_image = excluded.og_image',
                    [
                        $page,
                        json_encode($item['title'] ?? new \stdClass(), JSON_UNESCAPED_UNICODE),
                        json_encode($item['description'] ?? new \stdClass(), JSON_UNESCAPED_UNICODE),
                        (string) ($item['og_image'] ?? ''),
                    ]
                );
            }
        });
        return Response::json(['data' => SeoMeta::all('id')]);
    }

    public function settings(Request $req): Response
    {
        $out = [];
        foreach (self::WRITABLE_SETTINGS as $key) {
            $out[$key] = Settings::get($key);
        }
        return Response::json(['data' => $out]);
    }

    public function updateSettings(Request $req): Response
    {
        $pairs = $req->body['settings'] ?? $req->body;
        if (!is_array($pairs)) {
            return Response::json(['error' => 'settings must be a map'], 422);
        }
        foreach ($pairs as $key => $value) {
            if (!in_array($key, self::WRITABLE_SETTINGS, true)) {
                continue;
            }
            if ($key === 'locales.enabled' || $key === 'locales.default') {
                if ($key === 'locales.enabled') {
                    $value = array_values(array_filter(
                        (array) $value,
                        fn ($l) => is_string($l) && preg_match('/^[a-z]{2}$/', $l)
                    ));
                    if ($value === []) {
                        $value = ['en'];
                    }
                } else {
                    $value = in_array($value, (array) ($pairs['locales.enabled'] ?? Settings::get('locales.enabled', ['en'])), true)
                        ? $value : 'en';
                }
            }
            Settings::set($key, $value);
        }
        return Response::json(['data' => $this->collect()]);
    }

    private function collect(): array
    {
        $out = [];
        foreach (self::WRITABLE_SETTINGS as $key) {
            $out[$key] = Settings::get($key);
        }
        return $out;
    }

    public function updatePassword(Request $req): Response
    {
        $validator = Validator::make($req->body, [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:10|max:200',
        ]);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }
        $user = \App\Core\Auth::user();
        $row = DB::fetch('SELECT password_hash FROM users WHERE id = ?', [$user['id']]);
        if ($row === null || !password_verify((string) $req->body['current_password'], (string) $row['password_hash'])) {
            return Response::json(['errors' => ['current_password' => ['invalid']]], 422);
        }
        DB::update('users', [
            'password_hash' => password_hash((string) $req->body['new_password'], PASSWORD_ARGON2ID),
        ], 'id = ?', [$user['id']]);
        Session::regenerate();
        return Response::json(['ok' => true]);
    }
}
