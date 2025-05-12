<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleModelCommand extends Command
{
    protected $signature = 'module:make-model {name : The name of the model} 
                            {module : The module name} 
                            {--m|migration : Create a migration for the model}
                            {--c|controller : Create a controller for the model}
                            {--r|resource : Create a resource controller for the model}';
    
    protected $description = 'Create a new model within a module';

    public function handle()
    {
        $modelName = $this->argument('name');
        $moduleName = $this->argument('module');
        
        // Check if module exists
        $modulePath = app_path("Modules/{$moduleName}");
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Create model file
        $this->createModel($modelName, $moduleName, $modulePath);
        
        // Create migration if requested
        if ($this->option('migration')) {
            $this->call('module:make-migration', [
                'name' => "create_" . Str::snake(Str::pluralStudly($modelName)) . "_table",
                'module' => $moduleName,
                '--create' => Str::snake(Str::pluralStudly($modelName))
            ]);
        }
        
        // Create controller if requested
        if ($this->option('controller') || $this->option('resource')) {
            $this->call('module:make-controller', [
                'name' => "{$modelName}Controller",
                'module' => $moduleName,
                '--model' => $modelName,
                '--resource' => $this->option('resource')
            ]);
        }
        
        return 0;
    }
    
    protected function createModel($modelName, $moduleName, $modulePath)
    {
        $modelPath = "{$modulePath}/Models/{$modelName}.php";
        
        // Check if model already exists
        if (File::exists($modelPath)) {
            $this->error("Model {$modelName} already exists in module {$moduleName}!");
            return;
        }
        
        $stub = <<<EOT
<?php

namespace App\\Modules\\{$moduleName}\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\Model;

class {$modelName} extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected \$fillable = [
        // Add your fillable attributes here
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected \$casts = [
        // Add your castable attributes here
    ];
}
EOT;
        
        File::put($modelPath, $stub);
        $this->info("Model {$modelName} created successfully in module {$moduleName}.");
    }
} 