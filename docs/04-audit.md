# Audit Report — The Living System portfolio

Environment: Windows 11 · PHP 8.4.23 · Node 24 · SQLite · Chromium 153 / Firefox / WebKit 26 (Playwright)

## Automated test suite

| Suite | Result |
|---|---|
| PHPUnit (unit + feature, real kernel + SQLite) | **28 tests, 83 assertions — all green** |
| Vitest (field engine math) | **9 tests — all green** |
| Playwright E2E (3 browsers) | **62 passed, 1 skipped** — across Chromium, Firefox, WebKit |

Covered: routing incl. `{param:regex}` constraints, middleware, groups, fallbacks, HEAD→GET,
validator rules, i18n lookup/fallback/direction, escaping, locale negotiation (q-values),
all 10 sections render in EN+AR, RTL attributes, 404s, contact honeypot/validation/storage,
CSRF 419, admin 401/302 gating, login flow, sitemap + robots.

## Lighthouse (production build, /en)

| Category | Score |
|---|---|
| Performance | **96** |
| Accessibility | **100** |
| Best Practices | **100** |
| SEO | **100** |

Metrics: FCP 2.1s · LCP 2.4s · TBT 0ms · CLS 0.001 · total payload 229 KiB (all fonts self-hosted).

## Accessibility (axe-core, WCAG 2 AA)

4 pages scanned (EN, AR, /work, /contact) in 3 browsers — **zero serious/critical violations**.
Note: reveal animations gate on viewport intersection, so audits scroll the page first —
a hidden-until-revealed element would otherwise measure at low effective contrast.

## Performance — the System Field

Measured real rAF throughput with the canvas running at full density:

| Engine | FPS |
|---|---|
| Chromium (headless) | **60.5** |
| Firefox (headed, GPU) | **144.5** |
| WebKit (headless, software) | throttled by environment — skipped |

The field costs well under 16.6ms/frame; pauses on `visibilitychange`; static render under
`prefers-reduced-motion`.

## Responsive matrix (screenshots in `audit/screens/`)

320 · 390 · 768 · 1440 · 1920 px — **zero horizontal overflow at every width**, EN + RTL AR.
Light mode and admin console screens captured.

## Issues found by testing and fixed

1. **Router regex bug** — `{param:[a-z]{2}}` constraints broke on the inner `}`. Rewrote the
   pattern parser to handle one-level-nested braces. Caught by unit tests.
2. **CSP blocked Clash Display** — fontshare serves multi-format src with protocol-relative
   URLs; the downloader only rewrote `https://` woff2. Now all 54 font files are local and
   `font-src 'self'` holds with zero violations.
3. **Contrast failures** — decorative `opacity:.5–.7` on text dropped below 4.5:1
   (work-card indices, footer caption, admin link). Removed; `--text-dim` is 7.5:1.
4. **Locale negotiation order** — iterated enabled locales instead of client preference order;
   `ar,en;q=0.5` returned `en`. Rewrote with q-value sorting.
5. **Container collapse** — hero's `width:100%` inline style overrode `.container-x`'s
   `min(92vw,1400px)`, collapsing auto margins → content flush to viewport edge. Fixed.
6. **Rate limiter caught the test suite** — contact's 3/hour limit fired during E2E.
   Added `bin/test-reset.php` (local-only) as Playwright global setup — the limit itself
   is verified working.
7. **Session regenerate in CLI** — guarded `regenerate()` behind `PHP_SESSION_ACTIVE`.

## Security posture verified live

401 on unauthenticated API · 419 on forged CSRF · 302 gate on /admin · rate limits on
login (5/min) and contact (3/hr) · honeypot drop on contact · Argon2id passwords ·
`HttpOnly; Secure; SameSite=Strict` sessions · CSP + full header set on every response ·
prepared statements throughout · SVG sanitization + SVG-gif duplication on upload ·
output escaping on every rendered field.

## Known limitations

- `database/portfolio.sqlite` and `.env` are dev-local; production needs a real DB path,
  `APP_DEBUG=false`, and password rotation.
- Headless WebKit can't represent real Safari GPU rendering; verify on-device for release.
- `bin/fonts.php` uses a bundled CA bundle (`bin/cacert.pem`) for the font CDN — dev tool only.
