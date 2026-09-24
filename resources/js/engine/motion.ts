/**
 * Motion layer — Lenis smooth scroll, reveal choreography, magnetic
 * elements, cursor reticle, signal-sweep page transitions, header state,
 * theme toggle, mobile nav, card sheen, metric counters, field focus.
 *
 * Everything degrades: prefers-reduced-motion → instant, static.
 */

import Lenis from 'lenis';
import type { SystemField } from './field';

interface Fx {
    field: { enabled: boolean; density: number; intensity: number };
    magnetic: boolean; parallax: boolean; transitions: boolean;
    cursor: boolean; boot: boolean;
}

const fx: Fx = (window as any).__FX__ ?? {
    field: { enabled: true, density: 1, intensity: 1 },
    magnetic: true, parallax: true, transitions: true, cursor: true, boot: true,
};

const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(pointer: fine)').matches;

export function initMotion(field: SystemField | null): void {
    // ── Smooth scroll ──────────────────────────────────────────────
    if (!reduced) {
        const lenis = new Lenis({ lerp: 0.11, wheelMultiplier: 1 });
        const raf = (t: number) => { lenis.raf(t); requestAnimationFrame(raf); };
        requestAnimationFrame(raf);
        // anchor links through lenis
        document.querySelectorAll<HTMLAnchorElement>('a[href^="#"]').forEach((a) => {
            a.addEventListener('click', (e) => {
                const target = document.querySelector(a.getAttribute('href')!);
                if (target) { e.preventDefault(); lenis.scrollTo(target as HTMLElement, { offset: -20 }); }
            });
        });
    }

    // ── Boot sequence ──────────────────────────────────────────────
    if (fx.boot && !reduced) {
        document.fonts?.ready.then(() => {
            requestAnimationFrame(() => document.documentElement.classList.add('booted'));
            field?.ignite();
        });
        // safety: never leave hero masked if fonts stall
        setTimeout(() => document.documentElement.classList.add('booted'), 1800);
    } else {
        document.documentElement.classList.add('booted');
    }

    // ── Reveal choreography ────────────────────────────────────────
    const io = new IntersectionObserver((entries) => {
        for (const entry of entries) {
            if (!entry.isIntersecting) continue;
            const el = entry.target as HTMLElement;
            el.classList.add('is-in');
            // level bars inside
            el.querySelectorAll<HTMLElement>('[data-level]').forEach((bar) => {
                bar.style.width = `${bar.dataset.level}%`;
            });
            // counters
            el.querySelectorAll<HTMLElement>('[data-count]').forEach(animateCount);
            io.unobserve(el);
        }
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    document.querySelectorAll('[data-reveal]').forEach((el) => io.observe(el));
    document.querySelectorAll<HTMLElement>('[data-reveal-group]').forEach((group) => {
        [...group.children].forEach((child, i) => (child as HTMLElement).style.setProperty('--i', String(i)));
        io.observe(group);
    });
    // section index bars grow when their section enters
    document.querySelectorAll<HTMLElement>('.sec-index').forEach((el) => io.observe(el));

    // standalone level bars / counters outside reveal groups
    document.querySelectorAll<HTMLElement>('[data-level]').forEach((bar) => {
        new IntersectionObserver((ents, o) => {
            if (ents[0]?.isIntersecting) { bar.style.width = `${bar.dataset.level}%`; o.disconnect(); }
        }, { threshold: 0.4 }).observe(bar);
    });
    document.querySelectorAll<HTMLElement>('[data-count]').forEach((el) => {
        new IntersectionObserver((ents, o) => {
            if (ents[0]?.isIntersecting) { animateCount(el); o.disconnect(); }
        }, { threshold: 0.4 }).observe(el);
    });

    // ── Section → field cluster coupling ───────────────────────────
    if (field) {
        const sectionIO = new IntersectionObserver((entries) => {
            for (const e of entries) {
                if (e.isIntersecting) field.setFocus((e.target as HTMLElement).dataset.fieldCluster || 'core');
            }
        }, { threshold: 0.35 });
        document.querySelectorAll('[data-field-cluster]').forEach((s) => sectionIO.observe(s));
    }

    // ── Header state ───────────────────────────────────────────────
    const header = document.getElementById('site-header');
    const onScroll = () => header?.classList.toggle('is-scrolled', window.scrollY > 24);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // ── Theme toggle ───────────────────────────────────────────────
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const next = document.documentElement.dataset.mode === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.mode = next;
            try { localStorage.setItem('zh-mode', next); } catch { /* private mode */ }
            window.dispatchEvent(new Event('zh:theme'));
        });
    });

    // ── Mobile nav ─────────────────────────────────────────────────
    const nav = document.querySelector<HTMLElement>('[data-mobile-nav]');
    const openBtn = document.querySelector<HTMLElement>('[data-menu-open]');
    document.querySelectorAll('[data-menu-close]').forEach((b) =>
        b.addEventListener('click', () => { nav?.classList.remove('is-open'); openBtn?.setAttribute('aria-expanded', 'false'); }));
    openBtn?.addEventListener('click', () => { nav?.classList.add('is-open'); openBtn.setAttribute('aria-expanded', 'true'); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') nav?.classList.remove('is-open'); });

    // ── Magnetic elements ──────────────────────────────────────────
    if (fx.magnetic && finePointer && !reduced) {
        document.querySelectorAll<HTMLElement>('[data-magnetic]').forEach((el) => {
            const strength = 0.32;
            let raf = 0;
            el.addEventListener('pointermove', (e) => {
                const r = el.getBoundingClientRect();
                const dx = e.clientX - (r.left + r.width / 2);
                const dy = e.clientY - (r.top + r.height / 2);
                cancelAnimationFrame(raf);
                raf = requestAnimationFrame(() => {
                    el.style.transform = `translate(${dx * strength}px, ${dy * strength}px)`;
                });
            });
            el.addEventListener('pointerleave', () => {
                cancelAnimationFrame(raf);
                el.style.transition = 'transform .5s cubic-bezier(.16,1,.3,1)';
                el.style.transform = '';
                setTimeout(() => (el.style.transition = ''), 500);
            });
        });
    }

    // ── Cursor reticle ─────────────────────────────────────────────
    if (fx.cursor && finePointer && !reduced) {
        const dot = document.querySelector<HTMLElement>('.cursor-dot');
        const ring = document.querySelector<HTMLElement>('.cursor-ring');
        if (dot && ring) {
            let rx = 0, ry = 0, tx = 0, ty = 0;
            window.addEventListener('pointermove', (e) => {
                tx = e.clientX; ty = e.clientY;
                dot.style.left = `${tx}px`; dot.style.top = `${ty}px`;
            }, { passive: true });
            // elements added later (e.g. filtered lists) still get hover state
            const ringIO = new MutationObserver(() => bindRingTargets());
            const bound = new WeakSet<Element>();
            const bindRingTargets = () => {
                document.querySelectorAll('a, button, [role="button"], input, textarea, .arch-node').forEach((el) => {
                    if (bound.has(el)) return;
                    bound.add(el);
                    el.addEventListener('pointerenter', () => ring.classList.add('is-active'));
                    el.addEventListener('pointerleave', () => ring.classList.remove('is-active'));
                });
            };
            bindRingTargets();
            ringIO.observe(document.body, { childList: true, subtree: true });
            const follow = () => {
                rx += (tx - rx) * 0.16; ry += (ty - ry) * 0.16;
                ring.style.left = `${rx}px`; ring.style.top = `${ry}px`;
                requestAnimationFrame(follow);
            };
            follow();
        }
    }

    // ── Card sheen (pointer-tracked radial highlight) ──────────────
    if (finePointer) {
        document.querySelectorAll<HTMLElement>('.card').forEach((card) => {
            card.addEventListener('pointermove', (e) => {
                const r = card.getBoundingClientRect();
                card.style.setProperty('--mx', `${e.clientX - r.left}px`);
                card.style.setProperty('--my', `${e.clientY - r.top}px`);
            });
        });
    }

    // ── Signal-sweep page transitions ──────────────────────────────
    if (fx.transitions && !reduced) {
        const sweep = document.querySelector<HTMLElement>('.sweep');
        document.querySelectorAll<HTMLAnchorElement>('a[href^="/"]').forEach((a) => {
            const href = a.getAttribute('href')!;
            if (href.startsWith('//') || a.target === '_blank' || a.hasAttribute('download')) return;
            a.addEventListener('click', (e) => {
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                e.preventDefault();
                sweep?.classList.add('is-active');
                setTimeout(() => { window.location.href = href; }, 340);
            });
        });
        // replay on bfcache restore
        window.addEventListener('pageshow', (e) => { if (e.persisted) sweep?.classList.remove('is-active'); });
    }

    // ── Parallax — scroll-depth drift on decorative layers ─────────
    // Only applied to elements that never carry [data-reveal] (inline
    // transform would fight the reveal transition).
    if (fx.parallax && !reduced) {
        const layers: [HTMLElement, number][] = [];
        document.querySelectorAll<HTMLElement>('.sec-index').forEach((el) => layers.push([el, 0.05]));
        document.querySelectorAll<HTMLElement>('.work-glyph').forEach((el) => layers.push([el, 0.12]));
        if (layers.length) {
            let ticking = false;
            const update = () => {
                ticking = false;
                const mid = window.innerHeight / 2;
                for (const [el, speed] of layers) {
                    const r = el.getBoundingClientRect();
                    if (r.bottom < -100 || r.top > window.innerHeight + 100) continue;
                    const offset = (r.top + r.height / 2 - mid) * speed;
                    el.style.transform = `translateY(${(-offset).toFixed(1)}px)`;
                }
            };
            window.addEventListener('scroll', () => {
                if (!ticking) { ticking = true; requestAnimationFrame(update); }
            }, { passive: true });
            update();
        }
    }

    // ── Work filters ───────────────────────────────────────────────
    const filterBtns = document.querySelectorAll<HTMLElement>('[data-filter]');
    if (filterBtns.length) {
        filterBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                filterBtns.forEach((b) => b.classList.remove('is-on'));
                btn.classList.add('is-on');
                const f = btn.dataset.filter!;
                document.querySelectorAll<HTMLElement>('[data-filterable]').forEach((card) => {
                    const show = f === '*' || card.dataset.domain === f;
                    card.style.display = show ? '' : 'none';
                });
            });
        });
    }
}

/** Count-up animation for metric values (parses numeric prefix). */
function animateCount(el: HTMLElement): void {
    if (el.dataset.counted) return;
    el.dataset.counted = '1';
    const target = el.dataset.count ?? '';
    const match = target.match(/^(\d+(?:\.\d+)?)/);
    if (!match) return;
    const end = parseFloat(match[1]!);
    const decimals = match[1]!.includes('.') ? 1 : 0;
    const rest = target.slice(match[1]!.length);
    if (reduced) { el.textContent = target; return; }
    const t0 = performance.now();
    const dur = 1100;
    const step = (now: number) => {
        const p = Math.min((now - t0) / dur, 1);
        const eased = 1 - Math.pow(1 - p, 4);
        el.textContent = (end * eased).toFixed(decimals) + rest;
        if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}
