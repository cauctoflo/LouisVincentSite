<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleServiceCommand extends Command
{
    protected $signature = 'module:make-service {name : The name of the service} 
                            {module : The module name}';
    
    protected $description = 'Create a new service class within a module';

    public function handle()
    {
        $serviceName = $this->argument('name');
        $moduleName = $this->argument('module');
        
        // Ensure the service has Service suffix
        if (!Str::endsWith($serviceName, 'Service')) {
            $serviceName .= 'Service';
        }
        
        // Check if module exists
        $modulePath = app_path("Modules/{$moduleName}");
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Create services directory if it doesn't exist
        $servicesPath = "{$modulePath}/Services";
        if (!File::exists($servicesPath)) {
            File::makeDirectory($servicesPath, 0755, true);
        }
        
        $this->createService($serviceName, $moduleName, $servicesPath);
        
        return 0;
    }
    
    protected function createService($name, $moduleName, $path)
    {
        $servicePath = "{$path}/{$name}.php";
        
        // Check if service already exists
        if (File::exists($servicePath)) {
            $this->error("Service {$name} already exists in module {$moduleName}!");
            return;
        }
        
        $stub = <<<EOT
<?php

namespace App\\Modules\\{$moduleName}\\Services;

class {$name}
{
    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Execute the service operation.
     */
    public function execute()
    {
        // Implement your service logic here
    }
}
EOT;
        
        File::put($servicePath, $stub);
        $this->info("Service {$name} created successfully in module {$moduleName}.");
    }
} 