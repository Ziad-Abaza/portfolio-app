import { defineConfig } from 'vitest/config';
import { resolve } from 'node:path';

export default defineConfig({
    test: {
        environment: 'jsdom',
        include: ['tests/js/**/*.test.ts'],
    },
    resolve: {
        alias: { '@': resolve(__dirname, 'resources/js') },
    },
});
