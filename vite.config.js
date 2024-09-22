import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/custom/customCreate.js',
                'resources/js/custom/customUpdate.js',
                'resources/js/custom/customHome.js',
            ],
            refresh: true,
        }),
    ],
});
