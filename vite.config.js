import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        hmr: {
            host: '10.1.16.209', // your ip
            overlay: false,
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/offcanvas-navbar.css',
                'resources/js/*.js',
                'resources/js/app.js',
                'resources/scss/style.scss'
            ],
            refresh: true,
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),




        {
            name:'blade',
handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            }
        },
    ],

    // optimizeDeps: {
    //     include: [
    //         vue,
    //     ]
    // },
    build: {
        manifest: true,
        outDir: 'public/javascript',
        emptyOutDir: true,
        rollupOptions: {
            input: 'resources/js/app.js',
        },
    },
    resolve: {
        alias: {
            // '@/resources/svg': path.resolve(__dirname, '/resources/assets/svg'),
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
        optimizeDeps: {
            include: [
                // '/resources/assets/svg/logo.svg',
            ],
        },

    },
});
