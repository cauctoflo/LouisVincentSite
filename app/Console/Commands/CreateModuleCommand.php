<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateModuleCommand extends Command
{
    protected $signature = 'module:create {name : The name of the module}';
    protected $description = 'Create a new module with its basic structure';

    public function handle()
    {
        $moduleName = $this->argument('name');
        $modulePath = app_path('Modules/' . $moduleName);

        if (File::exists($modulePath)) {
            $this->error("Module {$moduleName} already exists!");
            return 1;
        }

        // Create module directory
        File::makeDirectory($modulePath, 0755, true);

        // Create subdirectories
        $directories = [
            'Controllers',
            'Models',
            'Routes',
            'Migrations',
            'Views',
            'Services',
            'Providers',
        ];

        foreach ($directories as $directory) {
            File::makeDirectory("{$modulePath}/{$directory}", 0755, true);
        }

        // Create service provider for the module
        $this->createServiceProvider($moduleName, $modulePath);
        
        // Create route file
        $this->createRouteFile($moduleName, $modulePath);
        
        // Create base controller
        $this->createBaseController($moduleName, $modulePath);

        $this->info("Module {$moduleName} created successfully!");
        return 0;
    }

    protected function createServiceProvider($moduleName, $modulePath)
    {
        $providerContent = "<?php

namespace App\\Modules\\{$moduleName}\\Providers;

use Illuminate\\Support\\ServiceProvider;
use Illuminate\\Support\\Facades\\Route;

class {$moduleName}ServiceProvider extends ServiceProvider
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
        \$this->loadRoutes();
        
        // Load views
        \$this->loadViewsFrom(__DIR__ . '/../Views', '{$moduleName}');
        
        // Load migrations
        \$this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
    
    /**
     * Load routes for this module
     */
    protected function loadRoutes(): void
    {
        Route::middleware('web')
            ->namespace('App\\\\Modules\\\\{$moduleName}\\\\Controllers')
            ->group(__DIR__ . '/../Routes/web.php');
            
        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\\\\Modules\\\\{$moduleName}\\\\Controllers')
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
";
        
        File::put("{$modulePath}/Providers/{$moduleName}ServiceProvider.php", $providerContent);
    }
    
    protected function createRouteFile($moduleName, $modulePath)
    {
        $webRouteContent = "<?php

use Illuminate\\Support\\Facades\\Route;

/*
|--------------------------------------------------------------------------
| {$moduleName} Module Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('" . strtolower($moduleName) . "')->group(function () {
    // Define your web routes here
});
";
        
        $apiRouteContent = "<?php

use Illuminate\\Support\\Facades\\Route;

/*
|--------------------------------------------------------------------------
| {$moduleName} Module API Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('" . strtolower($moduleName) . "')->group(function () {
    // Define your API routes here
});
";
        
        File::put("{$modulePath}/Routes/web.php", $webRouteContent);
        File::put("{$modulePath}/Routes/api.php", $apiRouteContent);
    }
    
    protected function createBaseController($moduleName, $modulePath)
    {
        $controllerContent = "<?php

namespace App\\Modules\\{$moduleName}\\Controllers;

use App\\Http\\Controllers\\Controller;

class {$moduleName}Controller extends Controller
{
    //
}
";
        
        File::put("{$modulePath}/Controllers/{$moduleName}Controller.php", $controllerContent);
    }
} 