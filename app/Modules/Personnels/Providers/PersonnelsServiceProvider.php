<?php

namespace App\Modules\Personnels\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PersonnelsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/../Views', 'Personnels');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
    
    /**
     * Load routes for this module
     */
    protected function loadRoutes(): void
    {
        Route::middleware('web')
            ->namespace('App\\Modules\\Personnels\\Controllers')
            ->group(__DIR__ . '/../Routes/web.php');
            
        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\\Modules\\Personnels\\Controllers')
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
