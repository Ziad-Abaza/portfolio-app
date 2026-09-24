/**
 * Pure spatial functions for the System Field — extracted so they're
 * unit-testable without a canvas.
 */

export interface Pt { x: number; y: number }

/**
 * Spatial-hash edge detection: returns index pairs of points within `radius`.
 * O(n·k) instead of O(n²) — each point only checks its 3×3 cell neighborhood.
 */
export function edgePairs(pts: Pt[], radius: number): [number, number][] {
    const cell = radius;
    const grid = new Map<string, number[]>();
    pts.forEach((p, i) => {
        const key = `${Math.floor(p.x / cell)},${Math.floor(p.y / cell)}`;
        const bucket = grid.get(key);
        if (bucket) bucket.push(i); else grid.set(key, [i]);
    });
    const pairs: [number, number][] = [];
    const r2 = radius * radius;
    pts.forEach((p, i) => {
        const cx = Math.floor(p.x / cell), cy = Math.floor(p.y / cell);
        for (let dx = -1; dx <= 1; dx++) {
            for (let dy = -1; dy <= 1; dy++) {
                const bucket = grid.get(`${cx + dx},${cy + dy}`);
                if (!bucket) continue;
                for (const j of bucket) {
                    if (j <= i) continue;
                    const o = pts[j]!;
                    if ((p.x - o.x) ** 2 + (p.y - o.y) ** 2 < r2) pairs.push([i, j]);
                }
            }
        }
    });
    return pairs;
}

/** Hex/rgba color → rgba string with the given alpha. */
export function withAlpha(color: string, alpha: number): string {
    if (color.startsWith('rgba')) return color;
    const hex = color.replace('#', '');
    if (hex.length !== 6) return color;
    const r = parseInt(hex.slice(0, 2), 16);
    const g = parseInt(hex.slice(2, 4), 16);
    const b = parseInt(hex.slice(4, 6), 16);
    return `rgba(${r},${g},${b},${alpha})`;
}

/** Deterministic-ish node count from viewport area and density setting. */
export function nodeCount(width: number, height: number, density: number): number {
    return Math.round(Math.min(340, Math.max(90, ((width * height) / 9000) * density)));
}
