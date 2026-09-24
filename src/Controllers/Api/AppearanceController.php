<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Settings;
use App\Core\Theme;
use App\Models\ThemePreset;

final class AppearanceController
{
    public function themes(Request $req): Response
    {
        return Response::json(['data' => ThemePreset::all('id')]);
    }

    public function storeTheme(Request $req): Response
    {
        $name = trim((string) ($req->body['name'] ?? ''));
        $tokens = $req->body['tokens'] ?? null;
        if ($name === '' || !is_array($tokens)) {
            return Response::json(['errors' => ['name' => ['required'], 'tokens' => ['required']]], 422);
        }
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
        $id = ThemePreset::create([
            'name' => $name,
            'slug' => trim($slug, '-') . '-' . substr(bin2hex(random_bytes(3)), 0, 6),
            'tokens' => $tokens,
            'is_active' => 0,
            'is_builtin' => 0,
        ]);
        return Response::json(['data' => ThemePreset::find($id)], 201);
    }

    public function updateTheme(Request $req): Response
    {
        $id = (int) $req->param('id');
        if (ThemePreset::find($id) === null) {
            return Response::json(['error' => 'Not found'], 404);
        }
        $data = [];
        if (isset($req->body['name'])) {
            $data['name'] = trim((string) $req->body['name']);
        }
        if (isset($req->body['tokens']) && is_array($req->body['tokens'])) {
            $data['tokens'] = $req->body['tokens'];
        }
        if ($data !== []) {
            ThemePreset::update($id, $data);
        }
        return Response::json(['data' => ThemePreset::find($id)]);
    }

    public function activate(Request $req): Response
    {
        $id = (int) $req->param('id');
        if (ThemePreset::find($id) === null) {
            return Response::json(['error' => 'Not found'], 404);
        }
        ThemePreset::activate($id);
        return Response::json(['ok' => true]);
    }

    public function destroyTheme(Request $req): Response
    {
        $theme = ThemePreset::find((int) $req->param('id'));
        if ($theme === null) {
            return Response::json(['error' => 'Not found'], 404);
        }
        if ((int) $theme['is_builtin'] === 1 || (int) $theme['is_active'] === 1) {
            return Response::json(['error' => 'Cannot delete a builtin or active theme'], 422);
        }
        ThemePreset::delete((int) $theme['id']);
        return Response::json(['ok' => true]);
    }

    public function effects(Request $req): Response
    {
        return Response::json(['data' => Theme::effectsConfig()]);
    }

    public function updateEffects(Request $req): Response
    {
        $input = is_array($req->body['effects'] ?? null) ? $req->body['effects'] : $req->body;
        // Whitelist the controllable shape — booleans + bounded numbers only.
        $config = Theme::effectsConfig();
        $config['field']['enabled'] = (bool) ($input['field']['enabled'] ?? $config['field']['enabled']);
        $config['field']['density'] = max(0.2, min(2.0, (float) ($input['field']['density'] ?? $config['field']['density'])));
        $config['field']['intensity'] = max(0.0, min(2.0, (float) ($input['field']['intensity'] ?? $config['field']['intensity'])));
        foreach (['magnetic', 'parallax', 'transitions', 'cursor', 'boot'] as $flag) {
            if (array_key_exists($flag, $input)) {
                $config[$flag] = (bool) $input[$flag];
            }
        }
        Settings::set('effects', $config);
        return Response::json(['data' => $config]);
    }
}
