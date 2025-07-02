<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\EditorJsRenderer;

class EditorJsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Add a blade directive for rendering Editor.js content
        Blade::directive('editorjs', function ($expression) {
            return "<?php echo \App\Helpers\EditorJsRenderer::render($expression); ?>";
        });
    }
}
