<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MoveModelToModuleCommand extends Command
{
    protected $signature = 'module:move-model {model : The model to move (e.g. User)} {module : The target module}';
    protected $description = 'Move an existing model to a specific module';

    public function handle()
    {
        $modelName = $this->argument('model');
        $moduleName = $this->argument('module');
        
        // Define paths
        $originalPath = app_path("Models/{$modelName}.php");
        $modulePath = app_path("Modules/{$moduleName}");
        $targetPath = "{$modulePath}/Models/{$modelName}.php";
        
        // Check if the model exists
        if (!File::exists($originalPath)) {
            $this->error("Model {$modelName} does not exist!");
            return 1;
        }
        
        // Check if the module exists
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Check if the target model already exists
        if (File::exists($targetPath)) {
            $this->error("A model with the same name already exists in the module!");
            return 1;
        }
        
        // Read the model file
        $content = File::get($originalPath);
        
        // Update the namespace
        $content = preg_replace(
            '/namespace\s+App\\\\Models;/',
            "namespace App\\Modules\\{$moduleName}\\Models;",
            $content
        );
        
        // Update any imports that refer to App\Models
        $content = str_replace(
            'use App\Models\\',
            "use App\\Modules\\{$moduleName}\\Models\\",
            $content
        );
        
        // Create the model in the module
        File::put($targetPath, $content);
        
        // Optionally, remove the original model
        if ($this->confirm('Do you want to remove the original model?', true)) {
            File::delete($originalPath);
            $this->info("Original model deleted.");
        }
        
        $this->info("Model {$modelName} moved to module {$moduleName} successfully!");
        return 0;
    }
} 