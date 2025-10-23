import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/admin.css",
                "resources/js/admin.js",
                "resources/css/user.css",
                "resources/css/profile.css",
                "resources/js/user.js",
                "resources/js/profile.js",
            ],
            refresh: true,
        }),
    ],
});
