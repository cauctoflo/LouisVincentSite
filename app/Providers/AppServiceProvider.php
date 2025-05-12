<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register module service providers
        $this->registerModules();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
    
    /**
     * Register all module service providers
     */
    protected function registerModules(): void
    {
        $modulesPath = app_path('Modules');
        
        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);
            
            foreach ($modules as $module) {
                $moduleName = basename($module);
                $providerPath = "{$module}/Providers/{$moduleName}ServiceProvider.php";
                
                if (File::exists($providerPath)) {
                    $this->app->register("App\\Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider");
                }
            }
        }
    }
}
