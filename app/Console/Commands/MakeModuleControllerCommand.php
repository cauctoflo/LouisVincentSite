<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleControllerCommand extends Command
{
    protected $signature = 'module:make-controller {name : The name of the controller} 
                            {module : The module name}
                            {--model= : Generate a resource controller for the specified model}
                            {--r|resource : Generate a resource controller class}
                            {--api : Generate an API controller class}';
    
    protected $description = 'Create a new controller within a module';

    public function handle()
    {
        $controllerName = $this->argument('name');
        $moduleName = $this->argument('module');
        
        // Ensure the controller has Controller suffix
        if (!Str::endsWith($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }
        
        // Check if module exists
        $modulePath = app_path("Modules/{$moduleName}");
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Create controller directory if it doesn't exist
        $controllersPath = "{$modulePath}/Controllers";
        if (!File::exists($controllersPath)) {
            File::makeDirectory($controllersPath, 0755, true);
        }
        
        // Create controller file
        $modelName = $this->option('model');
        
        if ($this->option('resource') || $modelName) {
            if ($modelName) {
                $this->createResourceControllerWithModel($controllerName, $moduleName, $controllersPath, $modelName);
            } else {
                $this->createResourceController($controllerName, $moduleName, $controllersPath);
            }
        } else if ($this->option('api')) {
            $this->createApiController($controllerName, $moduleName, $controllersPath);
        } else {
            $this->createPlainController($controllerName, $moduleName, $controllersPath);
        }
        
        return 0;
    }
    
    protected function createPlainController($name, $moduleName, $path)
    {
        $controllerPath = "{$path}/{$name}.php";
        
        // Check if controller already exists
        if (File::exists($controllerPath)) {
            $this->error("Controller {$name} already exists in module {$moduleName}!");
            return;
        }
        
        $stub = <<<EOT
<?php

namespace App\\Modules\\{$moduleName}\\Controllers;

use App\\Http\\Controllers\\Controller;
use Illuminate\\Http\\Request;

class {$name} extends Controller
{
    //
}
EOT;
        
        File::put($controllerPath, $stub);
        $this->info("Controller {$name} created successfully in module {$moduleName}.");
    }
    
    protected function createResourceController($name, $moduleName, $path)
    {
        $controllerPath = "{$path}/{$name}.php";
        
        // Check if controller already exists
        if (File::exists($controllerPath)) {
            $this->error("Controller {$name} already exists in module {$moduleName}!");
            return;
        }
        
        $stub = <<<EOT
<?php

namespace App\\Modules\\{$moduleName}\\Controllers;

use App\\Http\\Controllers\\Controller;
use Illuminate\\Http\\Request;

class {$name} extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request \$request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string \$id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string \$id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request \$request, string \$id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string \$id)
    {
        //
    }
}
EOT;
        
        File::put($controllerPath, $stub);
        $this->info("Resource Controller {$name} created successfully in module {$moduleName}.");
    }
    
    protected function createResourceControllerWithModel($name, $moduleName, $path, $modelName)
    {
        $controllerPath = "{$path}/{$name}.php";
        
        // Check if controller already exists
        if (File::exists($controllerPath)) {
            $this->error("Controller {$name} already exists in module {$moduleName}!");
            return;
        }
        
        // Format the model name
        $modelClass = Str::studly($modelName);
        $modelVariable = Str::camel($modelName);
        
        $stub = <<<EOT
<?php

namespace App\\Modules\\{$moduleName}\\Controllers;

use App\\Http\\Controllers\\Controller;
use App\\Modules\\{$moduleName}\\Models\\{$modelClass};
use Illuminate\\Http\\Request;

class {$name} extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \${$modelVariable}s = {$modelClass}::all();
        return view('{$moduleName}::{$modelVariable}.index', compact('{$modelVariable}s'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('{$moduleName}::{$modelVariable}.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request \$request)
    {
        \$validated = \$request->validate([
            // Define validation rules here
        ]);

        {$modelClass}::create(\$validated);

        return redirect()->route('{$moduleName}.{$modelVariable}.index')
                         ->with('success', '{$modelClass} created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show({$modelClass} \${$modelVariable})
    {
        return view('{$moduleName}::{$modelVariable}.show', compact('{$modelVariable}'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit({$modelClass} \${$modelVariable})
    {
        return view('{$moduleName}::{$modelVariable}.edit', compact('{$modelVariable}'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request \$request, {$modelClass} \${$modelVariable})
    {
        \$validated = \$request->validate([
            // Define validation rules here
        ]);

        \${$modelVariable}->update(\$validated);

        return redirect()->route('{$moduleName}.{$modelVariable}.index')
                         ->with('success', '{$modelClass} updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy({$modelClass} \${$modelVariable})
    {
        \${$modelVariable}->delete();

        return redirect()->route('{$moduleName}.{$modelVariable}.index')
                         ->with('success', '{$modelClass} deleted successfully');
    }
}
EOT;
        
        File::put($controllerPath, $stub);
        $this->info("Resource Controller {$name} for model {$modelClass} created successfully in module {$moduleName}.");
    }
    
    protected function createApiController($name, $moduleName, $path)
    {
        $controllerPath = "{$path}/{$name}.php";
        
        // Check if controller already exists
        if (File::exists($controllerPath)) {
            $this->error("Controller {$name} already exists in module {$moduleName}!");
            return;
        }
        
        $stub = <<<EOT
<?php

namespace App\\Modules\\{$moduleName}\\Controllers;

use App\\Http\\Controllers\\Controller;
use Illuminate\\Http\\Request;

class {$name} extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request \$request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string \$id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request \$request, string \$id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string \$id)
    {
        //
    }
}
EOT;
        
        File::put($controllerPath, $stub);
        $this->info("API Controller {$name} created successfully in module {$moduleName}.");
    }
} 