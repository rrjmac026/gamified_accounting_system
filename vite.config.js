import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],

    // server: {
    //     host: '0.0.0.0',
    //     hmr: {
    //         host: '192.168.254.122', // imong local IP address gamiton
    //     },
    //     port: 5173,
    // },
});
