import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    // Railway serves the app from the domain root, so generated asset URLs
    // must be root-relative (e.g. /build/assets/app-xxxx.css).
    base: '/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/theme.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // Keep the manifest where @vite() expects it: public/build/manifest.json.
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: 'manifest.json',
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
