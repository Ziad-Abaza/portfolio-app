/**
 * The System Field — a living architecture map rendered on canvas.
 *
 * Nodes are typed (edge/core/data/agent/tenant) and drawn as glyphs.
 * Edges are rebuilt periodically via a spatial hash. "Packets" travel
 * edges like requests through a system; the pointer acts as a load
 * generator and section focus energizes the matching cluster.
 *
 * Performance contract: O(n·k) via spatial hash, rAF-driven, pauses when
 * the tab is hidden, DPR-capped, static render under reduced-motion.
 */

export type NodeType = 'edge' | 'core' | 'data' | 'agent' | 'tenant' | 'endpoint';

interface Node {
    x: number; y: number; vx: number; vy: number;
    type: NodeType; cluster: string; energy: number; size: number;
}

interface Packet { a: number; b: number; t: number; speed: number }

export interface FieldConfig {
    enabled: boolean; density: number; intensity: number;
}

import { edgePairs, nodeCount, withAlpha } from './topology';

const TYPES: NodeType[] = ['edge', 'core', 'data', 'agent', 'tenant', 'endpoint'];
const CLUSTERS = ['core', 'services', 'data', 'apps', 'metrics', 'edge', 'security', 'agents', 'perf', 'ingress'];

/** Cluster anchor regions (viewport fractions). */
const ANCHORS: Record<string, [number, number]> = {
    core: [0.5, 0.42], services: [0.28, 0.4], data: [0.68, 0.55], apps: [0.5, 0.5],
    metrics: [0.5, 0.35], edge: [0.3, 0.6], security: [0.7, 0.4], agents: [0.62, 0.38],
    perf: [0.4, 0.55], ingress: [0.5, 0.5],
};

export class SystemField {
    private canvas: HTMLCanvasElement;
    private ctx: CanvasRenderingContext2D;
    private nodes: Node[] = [];
    private edges: [number, number][] = [];
    private packets: Packet[] = [];
    private raf = 0;
    private running = false;
    private lastEdgeBuild = 0;
    private lastFrame = 0;
    private dpr = 1;
    private pointer = { x: -9999, y: -9999, active: false };
    private focusCluster = 'core';
    private focusWeight = 0;
    private scrollEnergy = 0;
    private lastScrollY = 0;
    private colors = { accent: '#FF5D1F', soft: '#FF8A4C', dim: '#A89F92', border: '#2A251F', bg: '#0B0A08' };
    private config: FieldConfig;
    private reduced = false;

    constructor(canvas: HTMLCanvasElement, config: FieldConfig) {
        this.canvas = canvas;
        this.config = config;
        const ctx = canvas.getContext('2d', { alpha: true });
        if (!ctx) throw new Error('2d context unavailable');
        this.ctx = ctx;
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    start(): void {
        this.refreshTheme();
        this.resize();
        this.seed();
        this.buildEdges();

        window.addEventListener('resize', this.onResize, { passive: true });
        window.addEventListener('pointermove', this.onPointer, { passive: true });
        window.addEventListener('pointerleave', this.onPointerOut, { passive: true });
        window.addEventListener('scroll', this.onScroll, { passive: true });
        document.addEventListener('visibilitychange', this.onVisibility);
        window.addEventListener('zh:theme', this.onTheme);

        this.running = true;
        if (this.reduced || !this.config.enabled) {
            this.renderStatic();
            return;
        }
        this.lastFrame = performance.now();
        this.raf = requestAnimationFrame(this.tick);
    }

    destroy(): void {
        this.running = false;
        cancelAnimationFrame(this.raf);
        window.removeEventListener('resize', this.onResize);
        window.removeEventListener('pointermove', this.onPointer);
        window.removeEventListener('scroll', this.onScroll);
        document.removeEventListener('visibilitychange', this.onVisibility);
        window.removeEventListener('zh:theme', this.onTheme);
    }

    setFocus(cluster: string): void {
        this.focusCluster = cluster;
    }

    refreshTheme(): void {
        const s = getComputedStyle(document.documentElement);
        this.colors.accent = s.getPropertyValue('--accent').trim() || this.colors.accent;
        this.colors.soft = s.getPropertyValue('--accent-soft').trim() || this.colors.soft;
        this.colors.dim = s.getPropertyValue('--text-dim').trim() || this.colors.dim;
        this.colors.border = s.getPropertyValue('--border').trim() || this.colors.border;
        this.colors.bg = s.getPropertyValue('--bg').trim() || this.colors.bg;
    }

    /** One-shot ignition pulse used by the boot sequence. */
    ignite(): void {
        this.scrollEnergy = Math.min(this.scrollEnergy + 3, 4);
    }

    /* ── internals ─────────────────────────────────────────────────── */

    private onResize = (): void => { this.resize(); this.buildEdges(); };
    private onPointer = (e: PointerEvent): void => {
        this.pointer.x = e.clientX; this.pointer.y = e.clientY; this.pointer.active = true;
    };
    private onPointerOut = (): void => { this.pointer.active = false; this.pointer.x = -9999; };
    private onScroll = (): void => {
        const dy = Math.abs(window.scrollY - this.lastScrollY);
        this.lastScrollY = window.scrollY;
        this.scrollEnergy = Math.min(this.scrollEnergy + dy / 600, 3);
    };
    private onVisibility = (): void => {
        if (document.hidden) {
            this.running = false;
            cancelAnimationFrame(this.raf);
        } else if (!this.reduced && this.config.enabled) {
            this.running = true;
            this.lastFrame = performance.now();
            this.raf = requestAnimationFrame(this.tick);
        }
    };
    private onTheme = (): void => {
        this.refreshTheme();
        if (this.reduced || !this.config.enabled) this.renderStatic();
    };

    private resize(): void {
        this.dpr = Math.min(window.devicePixelRatio || 1, 2);
        this.canvas.width = Math.floor(window.innerWidth * this.dpr);
        this.canvas.height = Math.floor(window.innerHeight * this.dpr);
        this.canvas.style.width = '100%';
        this.canvas.style.height = '100%';
        this.ctx.setTransform(this.dpr, 0, 0, this.dpr, 0, 0);
    }

    private seed(): void {
        const count = nodeCount(window.innerWidth, window.innerHeight, this.config.density);
        this.nodes = [];
        for (let i = 0; i < count; i++) {
            this.nodes.push({
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                vx: (Math.random() - 0.5) * 0.14,
                vy: (Math.random() - 0.5) * 0.14,
                type: TYPES[i % TYPES.length]!,
                cluster: CLUSTERS[i % CLUSTERS.length]!,
                energy: 0,
                size: 1.6 + Math.random() * 1.8,
            });
        }
    }

    /** Spatial-hash edge rebuild — links nodes within radius. */
    private buildEdges(): void {
        const radius = Math.min(130, Math.max(80, window.innerWidth / 12));
        this.edges = edgePairs(this.nodes, radius);
    }

    private tick = (now: number): void => {
        if (!this.running) return;
        const dt = Math.min((now - this.lastFrame) / 16.67, 3); // normalized to 60fps steps
        this.lastFrame = now;

        this.step(dt);
        this.draw();

        this.raf = requestAnimationFrame(this.tick);
    };

    private step(dt: number): void {
        const { innerWidth: W, innerHeight: H } = window;
        const intensity = this.config.intensity;

        // Focus weight eases toward the active cluster
        this.focusWeight = Math.min(this.focusWeight + 0.02 * dt, 1);

        // Pointer as load generator
        const px = this.pointer.x, py = this.pointer.y;
        const pointerR = 190;

        for (const n of this.nodes) {
            n.x += n.vx * dt;
            n.y += n.vy * dt;
            // soft bounds
            if (n.x < -20) n.x = W + 20; else if (n.x > W + 20) n.x = -20;
            if (n.y < -20) n.y = H + 20; else if (n.y > H + 20) n.y = -20;

            // gentle drift randomness
            n.vx += (Math.random() - 0.5) * 0.012 * dt;
            n.vy += (Math.random() - 0.5) * 0.012 * dt;
            const vmax = 0.22;
            n.vx = Math.max(-vmax, Math.min(vmax, n.vx));
            n.vy = Math.max(-vmax, Math.min(vmax, n.vy));

            // cluster gravity when its section owns the viewport
            if (n.cluster === this.focusCluster) {
                const [ax, ay] = ANCHORS[this.focusCluster] ?? [0.5, 0.5];
                n.vx += (ax * W - n.x) * 0.000018 * dt * this.focusWeight;
                n.vy += (ay * H - n.y) * 0.000018 * dt * this.focusWeight;
                n.energy = Math.min(n.energy + 0.012 * dt * intensity, 1);
            } else {
                n.energy = Math.max(n.energy - 0.01 * dt, 0);
            }

            // pointer energy + faint attraction
            if (this.pointer.active) {
                const dx = px - n.x, dy = py - n.y;
                const d2 = dx * dx + dy * dy;
                if (d2 < pointerR * pointerR) {
                    const d = Math.sqrt(d2) || 1;
                    const f = (1 - d / pointerR);
                    n.energy = Math.min(n.energy + f * 0.05 * dt * intensity, 1);
                    n.vx += (dx / d) * f * 0.014 * dt;
                    n.vy += (dy / d) * f * 0.014 * dt;
                }
            }
        }

        // edges rebuilt periodically (nodes drift slowly)
        if (performance.now() - this.lastEdgeBuild > 350) {
            this.buildEdges();
            this.lastEdgeBuild = performance.now();
        }

        // packets: spawn scaled by intensity + scroll energy, hop along edges
        this.scrollEnergy = Math.max(this.scrollEnergy - 0.008 * dt, 0);
        const targetPackets = Math.round((6 + this.scrollEnergy * 14) * intensity);
        while (this.packets.length < targetPackets && this.edges.length > 0) {
            const e = this.edges[Math.floor(Math.random() * this.edges.length)]!;
            this.packets.push({ a: e[0], b: e[1], t: 0, speed: 0.008 + Math.random() * 0.02 });
        }
        this.packets = this.packets.filter((p) => {
            p.t += p.speed * dt;
            if (p.t >= 1) {
                const dst = this.nodes[p.b];
                if (dst) dst.energy = Math.min(dst.energy + 0.4, 1);
                return false;
            }
            return true;
        });
    }

    private draw(): void {
        const { ctx } = this;
        ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);

        // edges — faint topology lines
        ctx.lineWidth = 1;
        for (const [ai, bi] of this.edges) {
            const a = this.nodes[ai]!, b = this.nodes[bi]!;
            const energy = Math.max(a.energy, b.energy);
            ctx.strokeStyle = this.withAlpha(this.colors.border, 0.35 + energy * 0.5);
            ctx.beginPath();
            ctx.moveTo(a.x, a.y);
            ctx.lineTo(b.x, b.y);
            ctx.stroke();
        }

        // packets — request traffic
        for (const p of this.packets) {
            const a = this.nodes[p.a], b = this.nodes[p.b];
            if (!a || !b) continue;
            const x = a.x + (b.x - a.x) * p.t;
            const y = a.y + (b.y - a.y) * p.t;
            ctx.fillStyle = this.colors.accent;
            ctx.beginPath();
            ctx.arc(x, y, 1.6, 0, Math.PI * 2);
            ctx.fill();
        }

        // nodes — typed glyphs, energized glow
        for (const n of this.nodes) {
            const e = n.energy;
            if (e > 0.05) {
                ctx.fillStyle = this.withAlpha(this.colors.accent, 0.10 * e);
                ctx.beginPath();
                ctx.arc(n.x, n.y, n.size * (4 + e * 5), 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.fillStyle = e > 0.4 ? this.colors.soft : this.withAlpha(this.colors.dim, 0.55 + e * 0.45);
            this.glyph(n);
        }
    }

    private glyph(n: Node): void {
        const { ctx } = this;
        const s = n.size;
        ctx.beginPath();
        switch (n.type) {
            case 'core': // diamond
                ctx.moveTo(n.x, n.y - s * 1.4); ctx.lineTo(n.x + s * 1.4, n.y);
                ctx.lineTo(n.x, n.y + s * 1.4); ctx.lineTo(n.x - s * 1.4, n.y);
                ctx.closePath();
                break;
            case 'data': // square
                ctx.rect(n.x - s, n.y - s, s * 2, s * 2);
                break;
            case 'agent': // hexagon
                for (let i = 0; i < 6; i++) {
                    const a = (Math.PI / 3) * i - Math.PI / 6;
                    const gx = n.x + Math.cos(a) * s * 1.3, gy = n.y + Math.sin(a) * s * 1.3;
                    i === 0 ? ctx.moveTo(gx, gy) : ctx.lineTo(gx, gy);
                }
                ctx.closePath();
                break;
            case 'tenant': // triangle
                ctx.moveTo(n.x, n.y - s * 1.3); ctx.lineTo(n.x + s * 1.2, n.y + s);
                ctx.lineTo(n.x - s * 1.2, n.y + s); ctx.closePath();
                break;
            default: // edge / endpoint — circle
                ctx.arc(n.x, n.y, s, 0, Math.PI * 2);
        }
        ctx.fill();
    }

    private renderStatic(): void {
        this.step(0);
        this.draw();
    }

    private withAlpha(color: string, alpha: number): string {
        return withAlpha(color, alpha);
    }
}
