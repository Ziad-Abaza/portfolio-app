# Architecture — Information, Technical, Component

## Information Architecture

### Public site
```
/  → redirect to /{locale} by Accept-Language (default en)
/{locale}
├── Home (/) — scroll-storytelling single experience
│     01 Hero            — identity + System Field ignition + availability status
│     02 Expertise       — capability clusters (lights field nodes)
│     03 Architecture    — field resolves into labelled system diagram
│     04 Featured Work   — 3–5 projects, cinematic cards → case study
│     05 Metrics         — tabular numbers, impact data
│     06 Timeline        — career/system evolution
│     07 Technical Depth — stack, practices (security, perf, SEO, scale)
│     08 AI Projects     — AI integration work
│     09 Performance     — this site's own Lighthouse vitals (meta-proof)
│     10 Contact         — form + socials
├── /work           — project index (filterable by domain/stack)
├── /work/{slug}    — case study: problem → architecture → outcome → metrics
├── /about          — story + philosophy + timeline detail (optional phase 2)
└── /contact        — standalone contact page (SEO target)
```

URLs are locale-prefixed (`/en`, `/ar`) with `hreflang` alternates + `x-default`. Slugs per-locale where natural.

### Admin (`/admin`, auth-protected, utilitarian design)
```
/admin
├── Dashboard         — content health, recent messages, quick stats
├── Content
│   ├── Sections      — reorder (drag), toggle visibility, per-section props
│   ├── Projects      — CRUD + featured flag + ordering + media
│   ├── Case Studies  — structured blocks: problem/stack/architecture/outcome
│   ├── Skills        — grouped, proficiency, ordering
│   ├── Timeline      — CRUD entries
│   ├── Metrics       — key/value/label CRUD
│   └── Messages      — contact submissions inbox
├── Appearance
│   ├── Themes        — token editor + presets + live preview
│   ├── Effects       — field density/intensity, magnetic, parallax, transitions
│   └── Background    — field config, fallback gradients
├── System
│   ├── SEO           — meta/OG per page, JSON-LD, sitemap, robots
│   ├── Social Links  — CRUD
│   ├── Languages     — enable/disable locales, translation coverage
│   ├── Media Library — spatie medialibrary
│   └── Settings      — identity, availability status, contact email
```

## Technical Architecture

### Stack (revised — native PHP, no framework)
| Layer | Choice |
|---|---|
| Backend | **Native PHP 8.4** — custom lightweight kernel: front controller, router, DI-free services, PDO (prepared statements only), session auth, CSRF, rate limiting, security headers. Building the framework itself is the architecture flex. |
| Public site | **Server-rendered PHP templates** + vanilla TS enhancement (GSAP/Lenis/canvas). Best SEO + fastest FCP; no hydration cost. |
| Admin | **Vue 3.5 SPA** (TypeScript strict, Pinia, vue-router) consuming a session-authed JSON API — showcases Vue/TS skills in the right place. |
| Styling | Tailwind CSS 4 + custom token layer (CSS vars) |
| Motion | GSAP + ScrollTrigger, Lenis, custom canvas engine |
| DB | SQLite (PDO) → MySQL/PostgreSQL via DSN swap |
| i18n | JSON locale columns + PHP translation maps + vue-i18n in admin |
| Settings/Media/SEO | custom lightweight implementations (typed settings store, validated media uploads, server-rendered meta + JSON-LD + sitemap) |
| Testing | Pest/PHPUnit (PHP), Vitest (TS), Playwright (E2E/a11y/responsive), Lighthouse, axe-core |

### Security architecture (explicitly designed, not incidental)
- **SQL injection**: PDO prepared statements exclusively; query builder emits bound params only.
- **XSS**: `e()` escaping helper (`htmlspecialchars` ENT_QUOTES/UTF-8) mandatory in views; strict CSP (`default-src 'self'`, nonce'd inline scripts); rich content sanitized.
- **CSRF**: synchronizer tokens on every mutating route + `X-CSRF-Token` header for API; `hash_equals` compare.
- **Auth**: Argon2id hashing, `session_regenerate_id` on login, HttpOnly+SameSite=Lax cookies, single-admin (no public registration), login rate-limited (5/min per IP+user) with exponential backoff.
- **Uploads**: finfo MIME sniff, extension whitelist, size caps, random filenames, stored outside webroot, served via read-only controller.
- **Headers**: CSP, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, frame-ancestors, HSTS-ready.
- **Abuse**: contact honeypot + throttle; error display off in prod, structured logging to `storage/logs`.

### Backend structure (lightweight domains)
```
app/
├── Domain/
│   ├── Content/     # Section, Project, CaseStudy, Skill, TimelineEntry, Metric
│   ├── Appearance/  # Theme, EffectSetting
│   ├── Seo/         # SeoMeta, SitemapGenerator
│   └── Contact/     # ContactMessage
├── Http/
│   ├── Controllers/{Public,Admin}/
│   ├── Requests/    # validation + authorization
│   └── Resources/   # Inertia prop shaping (or spatie/laravel-data)
├── Services/        # ThemeResolver, LocaleManager, SectionRenderer
└── Models/
```
Controllers thin → Services hold logic → FormRequests validate. Settings cached; content cached per-locale with tag invalidation on admin writes.

### Data model (key entities)
- `sections` — key, order, visible, locale-independent props JSON
- `projects` — slug, JSON {en,ar}: title/summary/body, stack JSON, links, featured, order, published_at
- `case_studies` — project_id FK, structured blocks JSON (problem/architecture/outcome/metrics), bilingual
- `skills` — group, JSON name, level, order
- `timeline_entries`, `metrics`, `social_links`, `contact_messages`
- `themes` — name, tokens JSON, is_active
- `settings` — key/value via spatie
- `seo_meta` — morphTo page, JSON {en,ar} title/desc/og
- `media` — spatie polymorphic

### Component architecture (frontend)
```
resources/js/
├── app.ts, ssr? (no — CSR + meta via blade), types/
├── layouts/         SiteLayout.vue, AdminLayout.vue
├── components/
│   ├── ui/          Button, Link, Card, Tag, Input, Modal, Toast…
│   ├── effects/     SystemField.vue, Magnetic.vue, CursorReticle.vue,
│   │                ParallaxLayer.vue, RevealOnScroll.vue, SignalSweep.vue
│   ├── sections/    HeroSection, ExpertiseSection, ArchitectureSection,
│   │                FeaturedWork, MetricsSection, TimelineSection,
│   │                TechDepthSection, AiProjectsSection, PerfSection, ContactSection
│   └── admin/       DataTable, FormField, TokenEditor, LivePreview…
├── composables/     useTheme, useLocale, usePointer, useReducedMotion,
│                    useSystemField, useMagnetic, useSectionProgress
├── stores/          theme.ts, locale.ts, effects.ts, ui.ts
├── pages/           Home.vue, Work/{Index,Show}.vue, Contact.vue, admin/…
└── engine/          field/ (nodes, edges, packets, spatial hash, renderer)
```

### Rendering strategy
- Inertia CSR + `inertia-head` for SPA feel; SEO-critical meta rendered server-side in Blade from `seo_meta` (Inertia shares it; blade reads page props on first load) → crawlers get full meta without SSR complexity. JSON-LD Person/WebSite injected server-side.
- OG images: generated per-project (media conversion or `spatie/browsershot` optional).
- `sitemap.xml` + `robots.txt` generated; per-locale alternates.

### Security
- Admin auth: Laravel Fortify-style (login only, no public registration), rate-limited login, optional 2FA.
- All admin routes behind `auth` + `verified` middleware; CSRF default; media uploads validated (mime/size), stored outside public, served via conversions.
- Contact form: honeypot + throttle + validation; stored, no external email dependency required.
- Content output escaped (Vue default); rich text sanitized (HTMLPurifier) where allowed.

## Build phases
0. **Scaffold** — Laravel 12 + Inertia Vue TS + Tailwind 4 + tooling (Pint, Larastan, Vitest, Pest)
1. **Foundation** — tokens, fonts (self-host), theme engine + presets, i18n pipeline, SiteLayout, base UI kit
2. **System Field + motion core** — canvas engine, Lenis+GSAP wiring, cursor, magnetic, transitions, reduced-motion paths
3. **Public sections** — all 10 sections + Work index/detail + Contact page, DB-driven
4. **Admin panel** — auth, all CRUD modules, theme/effects editors with live preview, media library, SEO manager
5. **Content & polish** — real bilingual content (EN+AR), seed real projects, micro-interaction pass, edge cases
6. **QA & hardening** — Pest suite, Vitest, Playwright E2E, axe a11y, Lighthouse audits (mobile+desktop), responsive matrix, cross-browser, perf budget enforcement, fixes

## Verification plan (Definition of Done)
- Lighthouse (prod build): Perf ≥90, A11y ≥95, Best Practices ≥95, SEO ≥95 — documented scores
- axe-core: 0 critical/serious violations on all public pages, both themes, both locales
- Responsive matrix: 320/375/768/1024/1440/1920px, LTR+RTL — no horizontal scroll, targets ≥44px
- Cross-browser: Chrome, Firefox, Safari(ish via WebKit engine), Edge — Playwright suite green
- `prefers-reduced-motion` + forced-colors verified manually
- Pest: ≥ feature coverage on admin CRUD, public routes, locale resolution, contact flow
- No console errors/warnings in prod build; bundle budget documented
