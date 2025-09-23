import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/layout.scss',
                'resources/css/main.scss',
                'resources/css/quiz.scss',
                'resources/css/survey-results.scss',
                'resources/css/start-survey.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
