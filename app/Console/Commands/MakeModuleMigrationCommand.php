<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleMigrationCommand extends Command
{
    protected $signature = 'module:make-migration {name : The name of the migration} 
                            {module : The module name}
                            {--create= : The table to be created}
                            {--table= : The table to be updated}';
    
    protected $description = 'Create a new migration within a module';

    public function handle()
    {
        $migrationName = $this->argument('name');
        $moduleName = $this->argument('module');
        
        // Check if module exists
        $modulePath = app_path("Modules/{$moduleName}");
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Create migrations directory if it doesn't exist
        $migrationsPath = "{$modulePath}/Migrations";
        if (!File::exists($migrationsPath)) {
            File::makeDirectory($migrationsPath, 0755, true);
        }
        
        // Create migration file
        $table = $this->option('create') ?: $this->option('table');
        $create = $this->option('create') ? true : false;
        
        $this->createMigration($migrationName, $moduleName, $migrationsPath, $table, $create);
        
        return 0;
    }
    
    protected function createMigration($name, $moduleName, $path, $table, $create)
    {
        // Generate a date prefix for the migration
        $prefix = date('Y_m_d_His');
        $filename = "{$prefix}_{$name}.php";
        
        // Full path to the migration file
        $migrationPath = "{$path}/{$filename}";
        
        // Generate migration content
        if ($create && $table) {
            $stub = $this->getCreateTableStub($table);
        } else if (!$create && $table) {
            $stub = $this->getUpdateTableStub($table);
        } else {
            $stub = $this->getPlainStub();
        }
        
        File::put($migrationPath, $stub);
        $this->info("Migration {$filename} created successfully in module {$moduleName}.");
    }
    
    protected function getCreateTableStub($table)
    {
        $tableClass = Str::studly($table);
        
        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
            // Add your columns here
            \$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
EOT;
    }
    
    protected function getUpdateTableStub($table)
    {
        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('{$table}', function (Blueprint \$table) {
            // Add your columns here
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('{$table}', function (Blueprint \$table) {
            // Reverse the changes
        });
    }
};
EOT;
    }
    
    protected function getPlainStub()
    {
        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Implement your migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the migration
    }
};
EOT;
    }
} 