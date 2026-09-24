import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'node:path';

export default defineConfig({
    plugins: [vue(), tailwindcss()],
    build: {
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true,
        rollupOptions: {
            input: {
                site: resolve(__dirname, 'resources/js/site.ts'),
                admin: resolve(__dirname, 'resources/js/admin/main.ts'),
            },
            output: {
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash][extname]',
            },
        },
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
