<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MoveViewToModuleCommand extends Command
{
    protected $signature = 'module:move-view {view : The view path in resources/views (e.g. auth/login or auth)} {module : The target module}';
    protected $description = 'Move existing views to a specific module';

    public function handle()
    {
        $viewPath = $this->argument('view');
        $moduleName = $this->argument('module');
        
        // Define paths
        $originalPath = resource_path("views/{$viewPath}");
        $modulePath = app_path("Modules/{$moduleName}");
        $targetPath = "{$modulePath}/Views/{$viewPath}";
        
        // Check if the view exists
        if (!File::exists($originalPath)) {
            $this->error("View path {$viewPath} does not exist!");
            return 1;
        }
        
        // Check if the module exists
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Check if the target already exists
        if (File::exists($targetPath)) {
            $this->error("A view with the same path already exists in the module!");
            return 1;
        }
        
        // Copy the views to the module
        if (File::isDirectory($originalPath)) {
            // It's a directory, copy all contents
            File::copyDirectory($originalPath, $targetPath);
            $this->info("View directory {$viewPath} copied to module {$moduleName}.");
            
            // Optionally, remove the original views
            if ($this->confirm('Do you want to remove the original view directory?', false)) {
                File::deleteDirectory($originalPath);
                $this->info("Original view directory deleted.");
            }
        } else {
            // It's a single file
            File::copy($originalPath, $targetPath);
            $this->info("View file {$viewPath} copied to module {$moduleName}.");
            
            // Optionally, remove the original view
            if ($this->confirm('Do you want to remove the original view file?', false)) {
                File::delete($originalPath);
                $this->info("Original view file deleted.");
            }
        }
        
        $this->info("Views moved to module {$moduleName} successfully!");
        
        // Reminder to update view references in controllers
        $this->warn("Remember to update any view() references in your controllers to use the new namespace: '{$moduleName}::{$viewPath}'");
        
        return 0;
    }
} 