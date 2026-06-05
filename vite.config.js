import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // このCSSとJSをVitaで管理する
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],

            // BladeやPHPファイルを変更したらブラウザを自動更新する
            refresh: true,
        }),
    ],
});
