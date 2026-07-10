import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { exec } from 'child_process';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig } from 'vite';

const execOnChange = (name, command, filesToWatch) => ({
    name,
    configureServer(server) {
        const log = (msg: any) => console.log('\x1b[35m%s\x1b[0m', `[${name}]`, msg);

        const generate = () => {
            exec(
                command,
                (error, stdout, stderr) => {
                    if (error) console.error(error);
                    if (stderr) console.error(stderr);
                    log(stdout);
                },
            );
        };

        generate();

        server.watcher.add(filesToWatch);
        server.watcher.on('change', (path) => {
            const matches = filesToWatch.some((file) => {
                if (file.includes('**')) {
                    const prefix = file.split('**')[0];
                    return path.startsWith(prefix);
                }

                return path === file;
            });

            if (matches) {
                log(`Detected change to ${path}`);
                generate();
            }
        });
    },
});

export default defineConfig({
    server: {
        watch: {
            ignored: [
                '**/.junie/**',
                '**/.github/**',
                '**/.vscode/**',
                '**/.cursor/**',
                '**/.claude/**',
            ],
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        inertia(),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),

        // Custom plugin automatically keeps ziggy in sync with changes to routes
        execOnChange('wayfinder:generate', 'php artisan wayfinder:generate', [
            'routes/**/*.php',
            'modules/**/routes/**/*.php',
        ])
    ],
});
