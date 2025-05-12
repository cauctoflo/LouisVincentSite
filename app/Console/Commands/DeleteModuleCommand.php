<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;

class DeleteModuleCommand extends Command
{
    protected $signature = 'module:delete {name : The name of the module to delete}
                            {--force : Force deletion without confirmation}
                            {--keep-tables : Keep database tables}';
    
    protected $description = 'Delete a module and its database tables';

    public function handle()
    {
        $moduleName = $this->argument('name');
        $modulePath = app_path("Modules/{$moduleName}");
        
        // Check if module exists
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist!");
            return 1;
        }
        
        // Confirmation
        if (!$this->option('force') && !$this->confirm("Are you sure you want to delete the module '{$moduleName}'? This cannot be undone!", false)) {
            $this->info('Operation cancelled.');
            return 0;
        }
        
        // Get module migrations to know which tables to drop
        $migrationsPath = "{$modulePath}/Migrations";
        $tableNames = [];
        
        if (File::exists($migrationsPath)) {
            $migrationFiles = File::glob("{$migrationsPath}/*.php");
            
            foreach ($migrationFiles as $migrationFile) {
                $content = File::get($migrationFile);
                
                // Check for create table statements
                if (preg_match("/Schema::create\('(.*?)'/", $content, $matches)) {
                    $tableNames[] = $matches[1];
                }
            }
        }
        
        // Drop tables if requested
        if (!$this->option('keep-tables') && !empty($tableNames)) {
            $this->info("The following tables will be dropped:");
            foreach ($tableNames as $tableName) {
                $this->line("- {$tableName}");
            }
            
            if ($this->option('force') || $this->confirm("Do you want to drop these tables?", false)) {
                $this->dropTables($tableNames);
            } else {
                $this->info("Tables will be kept.");
            }
        }
        
        // Rollback migrations
        if (!$this->option('keep-tables') && File::exists($migrationsPath)) {
            $this->info("Rolling back migrations for module {$moduleName}...");
            $this->rollbackMigrations($moduleName);
        }
        
        // Delete module directory
        File::deleteDirectory($modulePath);
        $this->info("Module {$moduleName} deleted successfully!");
        
        return 0;
    }
    
    protected function dropTables(array $tableNames)
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        foreach ($tableNames as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::dropIfExists($tableName);
                $this->info("Dropped table: {$tableName}");
            } else {
                $this->warn("Table {$tableName} does not exist, skipping.");
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->info("All tables dropped successfully!");
    }
    
    protected function rollbackMigrations(string $moduleName)
    {
        // We need to manually attempt to find migrations related to this module in the migrations table
        $migrations = DB::table('migrations')
            ->where('migration', 'like', "%{$moduleName}%")
            ->orWhere('batch', function($query) use ($moduleName) {
                // Try to find migrations through their content
                $migrationFiles = File::glob(app_path("Modules/{$moduleName}/Migrations/*.php"));
                $filenames = [];
                
                foreach ($migrationFiles as $file) {
                    $filenames[] = pathinfo($file, PATHINFO_FILENAME);
                }
                
                if (!empty($filenames)) {
                    $query->whereIn('migration', $filenames);
                }
            })
            ->get();
            
        if ($migrations->count() > 0) {
            $this->info("Found {$migrations->count()} migrations to roll back.");
            
            // Delete migration records directly from the migrations table
            foreach ($migrations as $migration) {
                DB::table('migrations')->where('id', $migration->id)->delete();
                $this->info("Rolled back migration: {$migration->migration}");
            }
        } else {
            $this->warn("No migrations found for this module in the migrations table.");
        }
    }
} 