<?php

namespace App\Modules\Pages\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PagesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutes();
        
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'Pages');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
    
    /**
     * Load routes for this module
     */
    protected function loadRoutes(): void
    {
        // Routes web publiques
        Route::middleware('web')
            ->namespace('App\\Modules\\Pages\\Controllers')
            ->group(__DIR__ . '/../Routes/web.php');
            
        // Routes d'administration
        Route::middleware('web')
            ->namespace('App\\Modules\\Pages\\Controllers')
            ->group(__DIR__ . '/../Routes/admin.php');
    }
}
