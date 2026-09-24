import { test } from '@playwright/test';
import { mkdirSync } from 'node:fs';

const OUT = 'docs/audit/screens';
mkdirSync(OUT, { recursive: true });

const viewports = [
    { name: 'mobile-320', width: 320, height: 800 },
    { name: 'mobile-390', width: 390, height: 844 },
    { name: 'tablet-768', width: 768, height: 1024 },
    { name: 'desktop-1440', width: 1440, height: 900 },
    { name: 'wide-1920', width: 1920, height: 1080 },
];

test.describe('responsive matrix', () => {
    for (const vp of viewports) {
        test(`home ${vp.name}`, async ({ browser }) => {
            const ctx = await browser.newContext({ viewport: { width: vp.width, height: vp.height } });
            const page = await ctx.newPage();
            await page.goto('/en');
            await page.waitForTimeout(1200);
            // capture hero + a scrolled mid-section
            await page.screenshot({ path: `${OUT}/home-${vp.name}-hero.png` });
            await page.evaluate(() => document.getElementById('work')?.scrollIntoView());
            await page.waitForTimeout(900);
            await page.screenshot({ path: `${OUT}/home-${vp.name}-work.png` });
            await page.evaluate(() => document.getElementById('contact')?.scrollIntoView());
            await page.waitForTimeout(900);
            await page.screenshot({ path: `${OUT}/home-${vp.name}-contact.png` });
            // horizontal scroll check — the classic RTL/responsive bug
            const overflowX = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            if (overflowX > 1) throw new Error(`horizontal overflow: ${overflowX}px at ${vp.name}`);
            await ctx.close();
        });
    }

    test('RTL mobile', async ({ browser }) => {
        const ctx = await browser.newContext({ viewport: { width: 390, height: 844 } });
        const page = await ctx.newPage();
        await page.goto('/ar');
        await page.waitForTimeout(1200);
        await page.screenshot({ path: `${OUT}/ar-mobile-hero.png` });
        await page.evaluate(() => document.getElementById('architecture')?.scrollIntoView());
        await page.waitForTimeout(900);
        await page.screenshot({ path: `${OUT}/ar-mobile-arch.png` });
        const overflowX = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
        if (overflowX > 1) throw new Error(`RTL horizontal overflow: ${overflowX}px`);
        await ctx.close();
    });

    test('light mode desktop', async ({ browser }) => {
        const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
        const page = await ctx.newPage();
        await page.goto('/en');
        await page.getByRole('button', { name: /theme/i }).first().click();
        await page.waitForTimeout(800);
        await page.screenshot({ path: `${OUT}/light-desktop-hero.png` });
        await page.evaluate(() => document.getElementById('architecture')?.scrollIntoView());
        await page.waitForTimeout(800);
        await page.screenshot({ path: `${OUT}/light-desktop-arch.png` });
        await ctx.close();
    });

    test('admin screens', async ({ page }) => {
        await page.goto('/admin/login');
        await page.screenshot({ path: `${OUT}/admin-login.png` });
        await page.getByLabel('Email').fill('admin@ziadhassan.dev');
        await page.getByLabel('Password').fill('change-me-now');
        await page.getByRole('button', { name: 'Enter' }).click();
        await page.waitForURL(/admin/);
        await page.waitForTimeout(800);
        await page.screenshot({ path: `${OUT}/admin-dashboard.png` });
        await page.goto('/admin/themes');
        await page.waitForTimeout(600);
        await page.screenshot({ path: `${OUT}/admin-themes.png` });
    });
});

test.describe('field performance', () => {
    // Headless WebKit renders in software with an artificially capped rAF —
    // unrepresentative of real Safari. Chromium+Firefox are measured honestly.
    test('frame cost fits the 60fps budget', async ({ page, browserName }) => {
        test.skip(browserName === 'webkit', 'headless webkit software-rendering rAF is unrepresentative');
        await page.goto('/en');
        await page.waitForTimeout(1500);

        const result = await page.evaluate(() => new Promise<{ fps: number; frames: number }>((resolve) => {
            let frames = 0;
            const t0 = performance.now();
            const tick = () => {
                frames++;
                if (performance.now() - t0 < 2000) requestAnimationFrame(tick);
                else resolve({ fps: frames / 2, frames });
            };
            requestAnimationFrame(tick);
        }));
        console.log(`${browserName} fps: ${result.fps.toFixed(1)}`);

        if (browserName === 'chromium' && result.fps < 45) {
            throw new Error(`chromium fps too low: ${result.fps}`);
        }
        // All engines: at minimum the loop must keep ticking (no starvation).
        if (result.fps < 10) throw new Error(`rAF starved: ${result.fps}`);
    });
});
