<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Modules\Log\Services\LogService;

class ModulesController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Affiche la liste des modules disponibles
     */
    public function index()
    {
        // Récupérer tous les modules disponibles
        $modulesPath = app_path('Modules');
        $modules = collect(File::directories($modulesPath))->map(function ($path) {
            $name = basename($path);
            $configPath = storage_path("app/modules/" . strtolower($name) . "/config.json");
            $config = [];
            
            if (File::exists($configPath)) {
                $config = json_decode(File::get($configPath), true) ?? [];
            }
            
            $status = $config['status'] ?? 'inactive';
            
            return [
                'name' => $name,
                'path' => $path,
                'config' => $config,
                'status' => $status
            ];
        });

        return view('Admin.modules.index', compact('modules'));
    }

    /**
     * Rediriger vers les paramètres spécifiques d'un module
     */
    public function settings($module)
    {
        // Vérifier si le module existe
        $modulePath = app_path("Modules/$module");
        
        if (!File::exists($modulePath)) {
            $this->logService->log('module_settings_failed', null, [
                'module' => $module,
                'reason' => 'Module does not exist'
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Le module $module n'existe pas.");
        }

        $routeExists = false;
        
        switch ($module) {
            case 'WebTv':
                return redirect()->route('WebTv.personnels.settings');
            case 'Log':
                return redirect()->route('WebTv.personnels.settings');
            case 'Internat':
                return redirect()->route('WebTv.personnels.settings');
            default:
                $this->logService->log('module_settings_failed', null, [
                    'module' => $module,
                    'reason' => 'No settings page exists'
                ]);
                return redirect()->route('personnels.modules.index')
                    ->with('error', "Le module $module n'a pas de page de paramètres.");
        }
    }

    public function create_json($module)
    {
        if (!$this->moduleExists($module)) {
            $this->logService->log('module_create_json_failed', null, [
                'module' => $module,
                'reason' => 'Module does not exist'
            ]);
            return $this->moduleNotFoundResponse($module);
        }

        try {
            $this->ensureConfigExists($module);
            return redirect()->route('personnels.modules.index')
                ->with('success', "Le fichier de configuration pour le module $module a été créé avec succès.");
        } catch (\Exception $e) {
            $this->logService->log('module_create_json_failed', null, [
                'module' => $module,
                'reason' => $e->getMessage()
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Impossible de créer le fichier de configuration pour le module $module.");
        }
    }

    /**
     * Activate a module
     */
    public function active($module)
    {
        if (!$this->moduleExists($module)) {
            $this->logService->log('module_activation_failed', null, [
                'module' => $module,
                'reason' => 'Module does not exist'
            ]);
            return $this->moduleNotFoundResponse($module);
        }

        try {
            $this->updateModuleStatus($module, 'active');
            return redirect()->route('personnels.modules.index')
                ->with('success', "Le module $module a été activé avec succès.");
        } catch (\Exception $e) {
            $this->logService->log('module_activation_failed', null, [
                'module' => $module,
                'reason' => $e->getMessage()
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Impossible d'activer le module $module.");
        }
    }

    /**
     * Deactivate a module
     */
    public function inactive($module)
    {
        if (!$this->moduleExists($module)) {
            $this->logService->log('module_deactivation_failed', null, [
                'module' => $module,
                'reason' => 'Module does not exist'
            ]);
            return $this->moduleNotFoundResponse($module);
        }

        try {
            $this->updateModuleStatus($module, 'inactive');
            return redirect()->route('personnels.modules.index')
                ->with('success', "Le module $module a été désactivé avec succès.");
        } catch (\Exception $e) {
            $this->logService->log('module_deactivation_failed', null, [
                'module' => $module,
                'reason' => $e->getMessage()
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Impossible de désactiver le module $module.");
        }
    }

    /**
     * Check if a module exists
     */
    private function moduleExists($module)
    {
        $directories = File::directories(app_path('Modules'));
        $modulePath = app_path("Modules/$module");
        return in_array($modulePath, $directories);
    }

    /**
     * Get the response for a non-existent module
     */
    private function moduleNotFoundResponse($module)
    {
        return redirect()->route('personnels.modules.index')
            ->with('error', "Le module $module n'existe pas.");
    }

    /**
     * Get the configuration path for a module
     */
    private function getConfigPath($module)
    {
        return storage_path("app/modules/" . strtolower($module) . "/config.json");
    }

    /**
     * Ensure the configuration directory and file exist
     */
    private function ensureConfigExists($module)
    {
        $configPath = $this->getConfigPath($module);
        
        // Create directory if it doesn't exist
        if (!File::exists(dirname($configPath))) {
            try {
                File::makeDirectory(dirname($configPath), 0755, true);
            } catch (\Exception $e) {
                $this->logService->log('module_dir_creation_failed', null, [
                    'module' => $module,
                    'path' => dirname($configPath),
                    'reason' => $e->getMessage()
                ]);
                throw $e;
            }
        }
        
        // Create config file if it doesn't exist
        if (!File::exists($configPath)) {
            try {
                File::put($configPath, json_encode(['status' => 'inactive'], JSON_PRETTY_PRINT));
            } catch (\Exception $e) {
                $this->logService->log('module_config_creation_failed', null, [
                    'module' => $module,
                    'path' => $configPath,
                    'reason' => $e->getMessage()
                ]);
                throw $e;
            }
        }

        return $configPath;
    }

    /**
     * Get the current configuration for a module
     */
    private function getModuleConfig($module)
    {
        $configPath = $this->ensureConfigExists($module);
        
        return File::exists($configPath) 
            ? (json_decode(File::get($configPath), true) ?? []) 
            : [];
    }

    /**
     * Update the module status
     */
    private function updateModuleStatus($module, $status)
    {
        try {
            $config = $this->getModuleConfig($module);
            $config['status'] = $status;
            
            File::put($this->getConfigPath($module), json_encode($config, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->logService->log('module_status_update_failed', null, [
                'module' => $module,
                'status' => $status,
                'reason' => $e->getMessage()
            ]);
            throw $e;
        }
    }
} 