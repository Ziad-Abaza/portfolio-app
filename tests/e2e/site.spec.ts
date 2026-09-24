import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

test.describe('public site', () => {
    test('home renders all sections without console errors', async ({ page }) => {
        const errors: string[] = [];
        page.on('console', (m) => { if (m.type() === 'error') errors.push(m.text()); });
        page.on('pageerror', (e) => errors.push(String(e)));

        await page.goto('/en');
        await expect(page).toHaveTitle(/Ziad Hassan/);
        for (const id of ['hero', 'expertise', 'architecture', 'work', 'metrics', 'timeline', 'depth', 'ai', 'performance', 'contact']) {
            await expect(page.locator(`#${id}`)).toBeAttached();
        }
        await expect(page.locator('#system-field')).toBeAttached();
        expect(errors).toEqual([]);
    });

    test('root negotiates locale', async ({ page }) => {
        await page.goto('/');
        await expect(page).toHaveURL(/\/(en|ar)/);
    });

    test('locale switch produces RTL arabic', async ({ page }) => {
        await page.goto('/en');
        await page.getByRole('link', { name: 'العربية' }).first().click();
        await expect(page).toHaveURL(/\/ar/);
        await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
        await expect(page.locator('html')).toHaveAttribute('lang', 'ar');
    });

    test('theme toggle flips data-mode and persists', async ({ page }) => {
        await page.goto('/en');
        await page.getByRole('button', { name: /theme/i }).first().click();
        await expect(page.locator('html')).toHaveAttribute('data-mode', 'light');
        await page.reload();
        await expect(page.locator('html')).toHaveAttribute('data-mode', 'light');
    });

    test('work index + case study navigation', async ({ page }) => {
        await page.goto('/en/work');
        await page.locator('article a').first().click();
        await expect(page).toHaveURL(/\/en\/work\/[\w-]+/);
        await expect(page.locator('h1')).toBeVisible();
    });

    test('contact form validates and submits', async ({ page }) => {
        await page.goto('/en/contact');
        // empty submit → validation errors redirect back
        await page.getByRole('button', { name: /send/i }).click();
        await expect(page).toHaveURL(/contact/);
        // fill properly
        await page.getByLabel(/name/i).fill('Playwright Tester');
        await page.getByLabel(/email/i).fill('pw@test.dev');
        await page.getByLabel(/project/i).fill('An automated end-to-end test message.');
        await page.getByRole('button', { name: /send/i }).click();
        await expect(page.locator('[role="status"]')).toBeVisible();
    });

    test('reduced motion: no animation, field static', async ({ page, browser }) => {
        const ctx = await browser.newContext({ reducedMotion: 'reduce' });
        const p = await ctx.newPage();
        await p.goto('/en');
        // hero words must be visible immediately (no masked transform)
        const span = p.locator('.hero-word > span').first();
        await expect(span).toBeVisible();
        await ctx.close();
    });
});

test.describe('accessibility', () => {
    for (const path of ['/en', '/ar', '/en/work', '/en/contact']) {
        test(`axe — ${path}`, async ({ page }) => {
            await page.goto(path);
            // Reveal animations gate on viewport intersection — scroll through
            // the page first so axe measures elements at final opacity.
            await page.evaluate(async () => {
                const step = window.innerHeight * 0.8;
                for (let y = 0; y < document.body.scrollHeight; y += step) {
                    window.scrollTo(0, y);
                    await new Promise((r) => setTimeout(r, 60));
                }
                window.scrollTo(0, 0);
            });
            await page.waitForTimeout(700);
            const results = await new AxeBuilder({ page }).analyze();
            const serious = results.violations.filter((v) => ['critical', 'serious'].includes(v.impact ?? ''));
            expect(serious, JSON.stringify(serious.map((v) => ({ id: v.id, nodes: v.nodes.map((n) => n.target) })))).toEqual([]);
        });
    }
});

test.describe('admin', () => {
    test('login gate + dashboard', async ({ page }) => {
        await page.goto('/admin');
        await expect(page).toHaveURL(/admin\/login/);
        await page.getByLabel('Email').fill('admin@ziadhassan.dev');
        await page.getByLabel('Password').fill('change-me-now');
        await page.getByRole('button', { name: 'Enter' }).click();
        await expect(page).toHaveURL(/\/admin\/?$/);
        await expect(page.getByRole('heading', { name: 'Dashboard' })).toBeVisible();
        // SPA navigation
        await page.getByRole('link', { name: 'Projects' }).click();
        await expect(page.getByText('Riyada OS')).toBeVisible();
    });
});
