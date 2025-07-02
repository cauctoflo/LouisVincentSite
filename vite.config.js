import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/editor-form.js',
                'resources/js/tags-handler.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',  
        port: 5173,        
        strictPort: true,  
        hmr: {
            host: '193.160.130.79',  // Remplace par ton IP locale (voir ci-dessous)
        }
    }
});
