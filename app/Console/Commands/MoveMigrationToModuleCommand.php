<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MoveMigrationToModuleCommand extends Command
{
    protected $signature = 'module:move-migration {migration : The migration filename (e.g. 2023_01_01_create_users_table)} {module : The target module}';
    protected $description = 'Move an existing migration to a specific module';

    public function handle()
    {
        $migrationName = $this->argument('migration');
        $moduleName = $this->argument('module');
        
        // If the migration doesn't have a .php extension, add it
        if (!str_ends_with($migrationName, '.php')) {
            $migrationName .= '.php';
        }
        
        // Find the migration file
        $migrationFiles = File::glob(database_path("migrations/*{$migrationName}"));
        
        if (empty($migrationFiles)) {
            $this->error("Migration {$migrationName} does not exist!");
            return 1;
        }
        
        $originalPath = $migrationFiles[0];
        $migrationFileName = basename($originalPath);
        
        // Define paths
        $modulePath = app_path("Modules/{$moduleName}");
        $targetPath = "{$modulePath}/Migrations/{$migrationFileName}";
        
        // Check if the module exists
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Check if the target migration already exists
        if (File::exists($targetPath)) {
            $this->error("A migration with the same name already exists in the module!");
            return 1;
        }
        
        // Read the migration file
        $content = File::get($originalPath);
        
        // Copy the migration to the module
        File::put($targetPath, $content);
        
        // Optionally, remove the original migration
        if ($this->confirm('Do you want to remove the original migration?', true)) {
            File::delete($originalPath);
            $this->info("Original migration deleted.");
        }
        
        $this->info("Migration {$migrationFileName} moved to module {$moduleName} successfully!");
        return 0;
    }
} 