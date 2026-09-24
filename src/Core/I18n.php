<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Locale state + translation lookup. UI strings live in resources/lang/{locale}.php
 * (nested arrays, dot notation). Content translations live in DB JSON columns.
 * Enabled locales are admin-manageable via the `locales` setting.
 */
final class I18n
{
    private static string $locale = 'en';
    private static array $lines = [];
    private static ?array $enabled = null;

    public static function enabledLocales(): array
    {
        if (self::$enabled === null) {
            $raw = Settings::get('locales.enabled', ['en', 'ar']);
            self::$enabled = is_array($raw) && $raw !== [] ? $raw : ['en', 'ar'];
        }
        return self::$enabled;
    }

    public static function defaultLocale(): string
    {
        return (string) Settings::get('locales.default', 'en');
    }

    public static function setLocale(string $locale): void
    {
        self::$locale = in_array($locale, self::enabledLocales(), true) ? $locale : self::defaultLocale();
    }

    public static function locale(): string
    {
        return self::$locale;
    }

    public static function direction(?string $locale = null): string
    {
        return ($locale ?? self::$locale) === 'ar' ? 'rtl' : 'ltr';
    }

    public static function t(string $key, array $replace = []): string
    {
        if (self::$lines === []) {
            $file = base_path('resources/lang/' . self::$locale . '.php');
            self::$lines = is_file($file) ? (require $file) : [];
            if (self::$locale !== 'en') {
                $fallback = base_path('resources/lang/en.php');
                if (is_file($fallback)) {
                    self::$lines = array_replace_recursive(require $fallback, self::$lines);
                }
            }
        }
        $value = self::$lines;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $key;
            }
            $value = $value[$segment];
        }
        $value = (string) $value;
        foreach ($replace as $k => $v) {
            $value = str_replace(':' . $k, (string) $v, $value);
        }
        return $value;
    }

    /** Locale negotiation — respects the client's preference ORDER and q-values. */
    public static function negotiate(string $acceptLanguage): string
    {
        if (preg_match_all('/([a-z]{2})(?:-[a-z]{2})?(?:\s*;\s*q=([\d.]+))?/i', $acceptLanguage, $m)) {
            $pairs = array_map(null, $m[1], $m[2]);
            usort($pairs, fn (array $a, array $b) => ((float) ($b[1] ?: 1)) <=> ((float) ($a[1] ?: 1)));
            foreach ($pairs as [$code]) {
                $code = strtolower((string) $code);
                if (in_array($code, self::enabledLocales(), true)) {
                    return $code;
                }
            }
        }
        return self::defaultLocale();
    }
}
