<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Security headers + CSP with a per-request nonce.
 * - script-src 'self' + nonce: no inline JS without our nonce.
 * - style-src keeps 'unsafe-inline' for style attributes (JS-driven effects
 *   use CSSOM which CSP does not govern anyway).
 */
final class SecurityHeaders
{
    private static ?string $nonce = null;

    public static function nonce(): string
    {
        return self::$nonce ??= bin2hex(random_bytes(16));
    }

    public static function apply(Response $response): void
    {
        $nonce = self::nonce();
        $response->headers += [
            'Content-Security-Policy' => implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'nonce-{$nonce}'",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data:",
                "font-src 'self'",
                "connect-src 'self'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'self'",
            ]),
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), interest-cohort=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
        ];
    }
}
