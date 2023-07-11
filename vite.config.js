import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        '@vitejs/plugin-vue',
        laravel({
            input: ['Modules/Auth/Resources/css/app.css', 'Modules/Auth/Resources/assets/js/app.js'],
            refresh: true,
        }),
    ],
});
