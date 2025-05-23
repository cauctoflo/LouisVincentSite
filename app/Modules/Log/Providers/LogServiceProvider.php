<?php

namespace App\Modules\Log\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Modules\Log\Services\LogService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enregistrer le service de logs comme singleton
        $this->app->singleton(LogService::class, function ($app) {
            return new LogService();
        });

        // Fusionner la configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/log.php', 'log'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerViews();
        $this->publishConfig();
        $this->loadCustomConfig();
        
        // Écouter les événements de connexion/déconnexion
        Event::listen(Login::class, function ($event) {
            app(LogService::class)->logLogin($event->user);
        });

        Event::listen(Logout::class, function ($event) {
            app(LogService::class)->logLogout($event->user);
        });
    }
    
    /**
     * Load routes for this module
     */
    protected function registerRoutes(): void
    {
        Route::middleware('web')
            ->prefix('personnels/log')
            ->name('personnels.Log.')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
            });

        // Routes API
        Route::middleware('api')
            ->prefix('api/logs')
            ->name('api.logs.')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
            });
    }

    /**
     * Enregistrement des vues du module
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'Log');
    }

    /**
     * Publication de la configuration du module
     *
     * @return void
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config/log.php' => config_path('log.php'),
        ], 'log-config');
    }

    /**
     * Charge une configuration personnalisée depuis un fichier JSON
     *
     * @return void
     */
    protected function loadCustomConfig(): void
    {
        $configPath = storage_path('app/modules/log/config.json');
        
        if (File::exists($configPath)) {
            $customConfig = json_decode(File::get($configPath), true);
            
            if (is_array($customConfig)) {
                // Fusionner avec la configuration existante
                $currentConfig = Config::get('log', []);
                Config::set('log', array_merge($currentConfig, $customConfig));
            }
        }
    }
}
