<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MoveControllerToModuleCommand extends Command
{
    protected $signature = 'module:move-controller {controller : The controller to move (e.g. UserController)} {module : The target module}';
    protected $description = 'Move an existing controller to a specific module';

    public function handle()
    {
        $controllerName = $this->argument('controller');
        $moduleName = $this->argument('module');
        
        // Ensure the controller name has the "Controller" suffix
        if (!Str::endsWith($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }
        
        // Define paths
        $originalPath = app_path("Http/Controllers/{$controllerName}.php");
        $modulePath = app_path("Modules/{$moduleName}");
        $targetPath = "{$modulePath}/Controllers/{$controllerName}.php";
        
        // Check if the controller exists
        if (!File::exists($originalPath)) {
            $this->error("Controller {$controllerName} does not exist!");
            return 1;
        }
        
        // Check if the module exists
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Check if the target controller already exists
        if (File::exists($targetPath)) {
            $this->error("A controller with the same name already exists in the module!");
            return 1;
        }
        
        // Read the controller file
        $content = File::get($originalPath);
        
        // Update the namespace
        $content = preg_replace(
            '/namespace\s+App\\\\Http\\\\Controllers(\\\\\w*)?;/',
            "namespace App\\Modules\\{$moduleName}\\Controllers;",
            $content
        );
        
        // Update any imports that refer to App\Http\Controllers
        $content = str_replace(
            'use App\Http\Controllers\\',
            'use App\Http\Controllers\\',
            $content
        );
        
        // Create the controller in the module
        File::put($targetPath, $content);
        
        // Optionally, remove the original controller
        if ($this->confirm('Do you want to remove the original controller?', true)) {
            File::delete($originalPath);
            $this->info("Original controller deleted.");
        }
        
        $this->info("Controller {$controllerName} moved to module {$moduleName} successfully!");
        return 0;
    }
} 