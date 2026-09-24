# Ziad Hassan — The Living System

A portfolio that behaves like the systems it describes: an interactive canvas renders a
living node-graph (typed nodes, traveling request-packets, section-aware clusters), while a
hand-built PHP kernel and a custom Vue admin console run everything underneath.

**No framework, no template** — the site is itself the portfolio piece.

## Stack

| Layer | Choice |
|---|---|
| Backend | PHP 8.4, custom kernel (Router, Request/Response, View, DB, Session, CSRF, Auth, Validator, RateLimiter, I18n, SecurityHeaders, Theme) |
| DB | SQLite (dev) — PDO, portable to MySQL/Postgres |
| Public frontend | Server-rendered PHP + vanilla TS — **9.8 KB gzip JS** |
| Admin | Vue 3 SPA + Pinia + vue-router, JSON API |
| Effects | Custom canvas engine + Lenis — magnetic, parallax, cursor reticle, signal-sweep transitions |
| Build | Vite 7 · Tailwind 4 · TypeScript strict |
| Fonts | Self-hosted IBM Plex (Sans/Arabic/Mono) + Clash Display |

## Setup

```bash
composer install && npm install
cp .env.example .env        # set APP_KEY
php bin/migrate.php --seed  # creates database/portfolio.sqlite
php bin/fonts.php           # optional — fonts already vendored
npx vite build
php -S localhost:8000 -t public
```

Admin: `/admin` — seeded `admin@ziadhassan.dev` / `change-me-now` (**change immediately**).

## Develop

```bash
npm run dev          # vite dev server (HMR) + php -S localhost:8000 -t public
composer test        # phpunit
npm run test:js      # vitest
npx playwright test  # e2e + axe + responsive matrix (needs server running)
```

## Structure

```
public/index.php      front controller (single entry)
src/Core/             kernel — routing, security, i18n, themes
src/Controllers/      public pages + admin + JSON API
src/Models/           thin ActiveRecord-style models
resources/views/      PHP templates (sections/, partials/, admin/)
resources/js/engine/  SystemField canvas + motion layer
resources/js/admin/   Vue SPA
database/             migrations + bilingual seed
tests/                phpunit (Unit, Feature) + vitest + playwright
docs/                 strategy, architecture, audit report
```

## Docs

- `docs/01-competitive-analysis.md` — what Awwwards-level actually requires
- `docs/02-design-strategy.md` — the Living System concept + design system
- `docs/03-architecture.md` — kernel design, security model, data model
- `docs/04-audit.md` — **measured results: 96/100/100/100 Lighthouse, axe clean, 60fps**
