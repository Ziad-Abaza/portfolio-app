# Design Strategy — "The Living System"

## Concept (one sentence)
**A portfolio that behaves like the systems its owner builds: a living architecture map runs beneath every section, and scrolling routes energy through it.**

The owner is a systems engineer (Laravel, SaaS, multi-tenant, architecture, AI integration). Instead of *saying* "I design systems", the site's ambient layer literally is one — a node-graph field of typed nodes (API, DB, Queue, Cache, Service, Agent, Tenant) with request-packets travelling along edges. Each section activates the part of the system it describes. This is the signature element; everything around it stays quiet and disciplined.

## Signature element: The System Field
- Persistent fixed canvas behind all content, ~180–320 nodes (adaptive by viewport/device).
- Nodes are **typed** — glyph shapes per type (diamond=service, square=datastore, circle=endpoint, hex=agent) — forming recognizable architecture clusters, not random dots.
- **Edges are alive**: packets travel along connections like requests through a system; pulses intensify near the cursor (mouse = load generator).
- **Scroll routing**: each section maps to a node cluster; entering a section sends an energy wave through the field and re-clusters it subtly.
- In the "Architecture Showcase" section the field resolves into a legible, labelled architecture diagram — ambient art becomes literal content.
- Light mode: same field, ink-on-paper treatment (nodes as blueprint marks).

Why not generic particles: typed nodes + directional packets + section-coupled clustering makes it a *diagram that breathes*, directly expressing "I think in systems". 2D canvas + spatial hashing keeps it 60fps without a WebGL dependency; WebGL shader upgrade path remains open.

## Design principles
1. **Cinematic but legible** — drama lives in the field, transitions, and type scale; body content stays supremely readable.
2. **Data typography as texture** — mono labels, tabular metrics, timestamps, coordinates: the engineer's aesthetic rendered honestly.
3. **Restraint budget** — one bold thing (the field). Cards, grids, sections: quiet, precise, generous whitespace.
4. **Arabic is first-class, not mirrored** — Arabic gets its own display treatment (Plex Sans Arabic bold, adjusted scale/leading), RTL layout is designed, not just flipped.
5. **Every control does what it says** — admin UI is utilitarian-dense, deliberately contrasting the public site's cinematic tone.

## Design System

### Color (dark-first, warm graphite + signal orange)
| Token | Dark | Light |
|---|---|---|
| `--bg` | `#0B0A08` (basalt) | `#FAF8F5` (paper) |
| `--surface` | `#14110E` | `#FFFFFF` |
| `--elevated` | `#1D1915` | `#F1EDE7` |
| `--border` | `#2A251F` | `#E3DDD4` |
| `--text` | `#EDE7DD` | `#16130F` |
| `--text-dim` | `#A89F92` | `#5C554A` |
| `--accent` | `#FF5D1F` (signal orange) | `#E04A0F` |
| `--accent-soft` | `#FF8A4C` | `#FF6B2C` |
| `--accent-ember` | `#8F3208` | `#B33B00` |
| `--accent-glow` | `rgba(255,93,31,.35)` | `rgba(224,74,15,.22)` |
| `--ok` / `--err` / `--info` | `#4ADE80` / `#F87171` / `#E8B44F` | `#15803D` / `#B91C1C` / `#A16207` |

Accent never paints body text — it marks energy: pulses, focus rings, key glyphs, primary CTAs. Gradients: ember → signal → soft, used only in hero type accents and field glow.

### Typography
| Role | Latin | Arabic |
|---|---|---|
| Display | **Clash Display** 600/700, tight tracking `-0.03em`, expanded hero scale | **IBM Plex Sans Arabic** 700, scale ×0.92, leading +8% |
| Body | **IBM Plex Sans** 400/500 | IBM Plex Sans Arabic 400/500 |
| Data/labels/code | **IBM Plex Mono** 400/500, `tabular-nums`, uppercase eyebrow labels | same mono (Latin glyphs for code) |

Rationale: one engineering superfamily (Plex) spans both scripts + mono = coherent identity; Clash Display supplies display tension. All self-hosted variable fonts, `font-display: swap`, preloaded.

Type scale (fluid `clamp`): display `clamp(2.75rem, 8vw, 7.5rem)`, h2 `clamp(2rem,5vw,4rem)`, h3 `clamp(1.35rem,2.5vw,2rem)`, body `1.0625rem`, label `.8125rem`.

### Layout
- Grid: 12-col fluid, `min(92vw, 1400px)` container; asymmetric editorial compositions (7/5, 8/4 splits), never centered-default.
- Spacing scale on `clamp()` tokens; section rhythm `clamp(6rem,14vh,12rem)`.
- Structural device: **section index bars** — thin rules with mono coordinates (`SEC/03 · AR-CORE`) that encode real taxonomy, not decorative numbering.

### Shape language
- Radius: 2px chips/labels, 10px cards, 16px media frames — angular-precision, not bubbly.
- Borders: 1px `--border`; accent used as 2px signal lines, focus rings, node glyphs.

### Theme engine
- All values are CSS custom properties on `[data-theme]`; presets stored in DB as JSON token maps.
- Admin edits tokens with live iframe preview; presets ship: *Ember* (default dark), *Paper* (light), *Solar* (warm amber-forward), *Graphite* (low-glow).
- `color-scheme` meta + `prefers-color-scheme` fallback; user choice persisted (DB-backed per user / localStorage anonymous).

## Motion System

### Stack
**Lenis** (smooth scroll, reduced-motion aware) + **GSAP + ScrollTrigger** (storytelling, parallax, reveals) + **custom canvas System Field** + **Inertia visit choreography** for transitions. No animation framework bloat; `motion` only where springs needed (magnetic effects — custom spring math, 20 lines).

### Motion language (one dialect)
| Token | Value | Use |
|---|---|---|
| `ease-out-expo` | `cubic-bezier(.16,1,.3,1)` | entrances, reveals |
| `ease-in-out-quint` | `cubic-bezier(.83,0,.17,1)` | page transitions, morphs |
| `spring-snappy` | stiffness 320 / damping 26 | magnetic, cursor |
| `dur-xs/sm/md/lg` | 120/240/420/720ms | micro → cinematic |

### Choreography
- **Boot**: 800ms orchestrated intro — field ignites, type masks up, nav settles. Once per session.
- **Page transitions**: outgoing content shears + fades (240ms), an orange "signal sweep" line traverses (300ms), incoming masks up (360ms). Thematic — like a request hitting the system.
- **Scroll storytelling**: sections reveal in staged beats (eyebrow → title → content, 60ms stagger); parallax layers at 0.3/0.6/1.0 depth ratios; field cluster transitions at section boundaries.
- **Magnetic**: primary CTAs + nav links attract within 48px radius, spring back on leave. Desktop pointer only.
- **Cursor**: crosshair-style engineer cursor — small dot + trailing reticle ring that snaps to interactive elements (scales to their bounds). Hidden on touch/`pointer:coarse`.
- **Micro-interactions**: underline sweep on links, copy-email ripple confirmation, card sheen on hover, focus rings that "charge" along the border.

### Performance contract (60fps)
- Animate only `transform`/`opacity`; canvas draw calls batched; spatial hash for edge detection O(n·k).
- Pointer state written on event, read in rAF — never thrash.
- IntersectionObserver gates all scroll effects; canvas pauses when `document.hidden` or off-screen.
- `devicePixelRatio` capped at 2; node count scales down under 768px / low-power.
- `prefers-reduced-motion`: Lenis off, field → static blueprint render, transitions → instant crossfade, parallax/magnetic disabled.

## Animation guidelines (admin-controllable)
Every effect maps to a DB setting: `effects.field.enabled|density|intensity`, `effects.magnetic`, `effects.parallax`, `effects.transitions`, `effects.cursor`, `effects.boot`. Admin gets sliders/toggles + live preview. Reduced-motion always wins over settings.
