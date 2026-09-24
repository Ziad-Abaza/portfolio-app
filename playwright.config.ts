import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: 'tests/e2e',
    timeout: 30_000,
    retries: 0,
    globalSetup: './tests/e2e/global-setup.ts',
    use: {
        baseURL: 'http://localhost:8000',
        screenshot: 'only-on-failure',
    },
    projects: [
        { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
        { name: 'firefox', use: { ...devices['Desktop Firefox'] } },
        { name: 'webkit', use: { ...devices['Desktop Safari'] } },
    ],
});
