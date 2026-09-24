<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Active theme resolution. A theme row holds tokens:
 * {"shared":{font-*,radius-*}, "dark":{bg,surface,...}, "light":{...}}
 * Emitted as a nonce'd <style> block of CSS custom properties.
 */
final class Theme
{
    public static function active(): ?array
    {
        $row = DB::fetch('SELECT * FROM themes WHERE is_active = 1 ORDER BY id LIMIT 1');
        if ($row === null) {
            $row = DB::fetch("SELECT * FROM themes WHERE slug = 'ember' LIMIT 1");
        }
        if ($row !== null && isset($row['tokens']) && is_string($row['tokens'])) {
            $row['tokens'] = json_decode($row['tokens'], true) ?: [];
        }
        return $row;
    }

    public static function tokens(): array
    {
        $theme = self::active();
        return $theme['tokens'] ?? [];
    }

    /** "[data-mode='dark']{--bg:...;...} [data-mode='light']{...} :root{...}" */
    public static function styleBlock(): string
    {
        $tokens = self::tokens();
        $css = '';
        foreach (['dark', 'light'] as $mode) {
            if (empty($tokens[$mode]) || !is_array($tokens[$mode])) {
                continue;
            }
            $css .= "[data-mode='{$mode}']{" . self::vars($tokens[$mode]) . '}';
        }
        if (!empty($tokens['shared']) && is_array($tokens['shared'])) {
            $css .= ':root{' . self::vars($tokens['shared']) . '}';
        }
        return $css;
    }

    private static function vars(array $map): string
    {
        $out = '';
        foreach ($map as $key => $value) {
            // Token names are controlled by our admin editor; still, sanitize.
            $key = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $key);
            $value = str_replace(['<', '>', ';', '{', '}'], '', (string) $value);
            $out .= "--{$key}:{$value};";
        }
        return $out;
    }

    /** Effects config handed to the frontend boot script. */
    public static function effectsConfig(): array
    {
        $defaults = [
            'field' => ['enabled' => true, 'density' => 1.0, 'intensity' => 1.0],
            'magnetic' => true,
            'parallax' => true,
            'transitions' => true,
            'cursor' => true,
            'boot' => true,
        ];
        $saved = Settings::get('effects', []);
        return array_replace_recursive($defaults, is_array($saved) ? $saved : []);
    }
}
