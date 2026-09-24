import { describe, expect, it } from 'vitest';
import { edgePairs, nodeCount, withAlpha } from '@/engine/topology';

describe('edgePairs (spatial hash)', () => {
    it('links points within radius, no duplicates', () => {
        const pts = [
            { x: 0, y: 0 }, { x: 50, y: 0 }, { x: 500, y: 500 },
        ];
        const pairs = edgePairs(pts, 100);
        expect(pairs).toEqual([[0, 1]]);
    });

    it('finds neighbors across cell boundaries', () => {
        // 95 apart horizontally — sits in adjacent cells with radius 100
        const pts = [{ x: 99, y: 0 }, { x: 101, y: 0 }];
        expect(edgePairs(pts, 100)).toEqual([[0, 1]]);
    });

    it('excludes points beyond radius', () => {
        const pts = [{ x: 0, y: 0 }, { x: 150, y: 0 }];
        expect(edgePairs(pts, 100)).toEqual([]);
    });

    it('scales to hundreds of points without blowup', () => {
        const pts = Array.from({ length: 300 }, (_, i) => ({ x: (i * 37) % 1200, y: (i * 91) % 800 }));
        const pairs = edgePairs(pts, 120);
        expect(pairs.length).toBeGreaterThan(0);
        expect(pairs.length).toBeLessThan(300 * 300);
    });
});

describe('withAlpha', () => {
    it('converts hex to rgba', () => {
        expect(withAlpha('#FF5D1F', 0.5)).toBe('rgba(255,93,31,0.5)');
    });
    it('passes rgba through untouched', () => {
        expect(withAlpha('rgba(1,2,3,.4)', 0.9)).toBe('rgba(1,2,3,.4)');
    });
    it('passes unknown formats through', () => {
        expect(withAlpha('var(--x)', 0.5)).toBe('var(--x)');
    });
});

describe('nodeCount', () => {
    it('scales with area and density', () => {
        expect(nodeCount(1920, 1080, 1)).toBeGreaterThan(nodeCount(400, 700, 1));
        expect(nodeCount(1920, 1080, 2)).toBeGreaterThan(nodeCount(1920, 1080, 0.5));
    });
    it('clamps to floor and ceiling', () => {
        expect(nodeCount(100, 100, 1)).toBe(90);
        expect(nodeCount(10000, 10000, 2)).toBe(340);
    });
});
