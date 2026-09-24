<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Csrf;
use App\Core\I18n;
use App\Core\Session;
use App\Core\View;

if (!function_exists('e')) {
    /** Escape output for HTML context. Mandatory in every view. */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

function base_path(string $path = ''): string
{
    return BASE_PATH . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
}

function storage_path(string $path = ''): string
{
    return base_path('storage' . ($path !== '' ? '/' . ltrim($path, '/') : ''));
}

function env(string $key, mixed $default = null): mixed
{
    return App\Core\Env::get($key, $default);
}

function config(string $key, mixed $default = null): mixed
{
    return Config::get($key, $default);
}

/**
 * Resolve a Vite build entry to its hashed assets.
 * @return array{js:?string,css:array<int,string>}
 */
function vite(string $entry): array
{
    static $manifest = null;
    if ($manifest === null) {
        $file = base_path('public/build/.vite/manifest.json');
        $manifest = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    $item = $manifest[$entry] ?? null;
    if ($item === null) {
        return ['js' => null, 'css' => []];
    }
    $css = array_map(fn (string $f) => '/build/' . $f, $item['css'] ?? []);
    return ['js' => '/build/' . $item['file'], 'css' => $css];
}

function url(string $path = ''): string
{
    $base = rtrim((string) env('APP_URL', ''), '/');
    return $base . '/' . ltrim($path, '/');
}

/** Locale-aware URL: keeps current locale prefix, no trailing slash. */
function lurl(string $path = ''): string
{
    $path = trim($path, '/');
    return '/' . I18n::locale() . ($path !== '' ? '/' . $path : '');
}

function locale(): string
{
    return I18n::locale();
}

function is_rtl(): bool
{
    return I18n::direction() === 'rtl';
}

function t(string $key, array $replace = []): string
{
    return I18n::t($key, $replace);
}

/** Read a bilingual JSON column {"en":..,"ar":..} for the current locale. */
function lf(mixed $value, ?string $locale = null): string
{
    $locale ??= I18n::locale();
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        $value = is_array($decoded) ? $decoded : $value;
    }
    if (!is_array($value)) {
        return (string) ($value ?? '');
    }
    return (string) ($value[$locale] ?? $value['en'] ?? reset($value) ?: '');
}

function csrf_token(): string
{
    return Csrf::token();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(Csrf::token()) . '">';
}

function old(string $key, string $default = ''): string
{
    $input = Session::flashGet('_old_input');
    return is_array($input) ? (string) ($input[$key] ?? $default) : $default;
}

function partial(string $template, array $data = []): string
{
    return View::partial($template, $data);
}

function csp_nonce(): string
{
    return App\Core\SecurityHeaders::nonce();
}

/** Inline <style> body — CSS custom properties from the active DB theme. */
function theme_style(): string
{
    return App\Core\Theme::styleBlock();
}

/** JSON effects config consumed by the site boot script. */
function fx_config(): string
{
    return json_encode(App\Core\Theme::effectsConfig(), JSON_UNESCAPED_SLASHES) ?: '{}';
}

/** A site setting value (JSON-decoded by Settings). */
function setting(string $key, mixed $default = null): mixed
{
    return App\Core\Settings::get($key, $default);
}
