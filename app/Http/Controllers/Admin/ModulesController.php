<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Modules\Log\Services\LogService;
use App\Core\Module\ModuleRegistry;

class ModulesController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        // Récupérer tous les modules enregistrés
        $registeredModules = ModuleRegistry::getAllModules();
        
        $modules = $registeredModules->map(function ($module) {
            $configPath = storage_path("app/modules/" . strtolower($module->getName()) . "/config.json");
            $config = [];
            
            if (File::exists($configPath)) {
                $config = json_decode(File::get($configPath), true) ?? [];
            }
            
            $status = $config['status'] ?? 'inactive';
            
            return [
                'name' => $module->getName(),
                'displayName' => $module->getDisplayName(),
                'description' => $module->getDescription(),
                'icon' => $module->getIcon(),
                'version' => $module->getVersion(),
                'order' => $module->getOrder(),
                'permissions' => $module->getPermissions(),
                'routes' => $module->getRoutes(),
                'status' => $status,
                'isActive' => $module->isActive(),
                'category' => $this->getModuleCategory($module->getName())
            ];
        })->sortBy('order');

        return view('Admin.modules.index', compact('modules'));
    }

    public function active($moduleName)
    {
        $module = ModuleRegistry::getModule($moduleName);
        
        if (!$module) {
            $this->logService->log('module_activation_failed', null, [
                'module' => $moduleName,
                'reason' => 'Module not found in registry'
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Le module $moduleName n'existe pas dans le registre.");
        }

        try {
            $this->updateModuleStatus($moduleName, 'active');
            
            $this->logService->log('module_activated', null, [
                'module' => $moduleName,
                'display_name' => $module->getDisplayName()
            ]);
            
            return redirect()->route('personnels.modules.index')
                ->with('success', "Le module {$module->getDisplayName()} a été activé avec succès.");
        } catch (\Exception $e) {
            $this->logService->log('module_activation_failed', null, [
                'module' => $moduleName,
                'reason' => $e->getMessage()
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Impossible d'activer le module {$module->getDisplayName()}.");
        }
    }

    public function inactive($moduleName)
    {
        $module = ModuleRegistry::getModule($moduleName);
        
        if (!$module) {
            $this->logService->log('module_deactivation_failed', null, [
                'module' => $moduleName,
                'reason' => 'Module not found in registry'
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Le module $moduleName n'existe pas dans le registre.");
        }

        // Empêcher la désactivation des modules core
        if (in_array($moduleName, ['Core', 'Personnels', 'Settings'])) {
            return redirect()->route('personnels.modules.index')
                ->with('error', "Le module {$module->getDisplayName()} est essentiel et ne peut pas être désactivé.");
        }

        try {
            $this->updateModuleStatus($moduleName, 'inactive');
            
            $this->logService->log('module_deactivated', null, [
                'module' => $moduleName,
                'display_name' => $module->getDisplayName()
            ]);
            
            return redirect()->route('personnels.modules.index')
                ->with('success', "Le module {$module->getDisplayName()} a été désactivé avec succès.");
        } catch (\Exception $e) {
            $this->logService->log('module_deactivation_failed', null, [
                'module' => $moduleName,
                'reason' => $e->getMessage()
            ]);
            return redirect()->route('personnels.modules.index')
                ->with('error', "Impossible de désactiver le module {$module->getDisplayName()}.");
        }
    }

    public function toggleStatus($moduleName)
    {
        $module = ModuleRegistry::getModule($moduleName);
        
        if (!$module) {
            return response()->json(['success' => false, 'message' => 'Module non trouvé']);
        }

        try {
            $currentStatus = $module->isActive() ? 'active' : 'inactive';
            $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';
            
            // Empêcher la désactivation des modules core
            if ($newStatus === 'inactive' && in_array($moduleName, ['Core', 'Personnels', 'Settings'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Ce module est essentiel et ne peut pas être désactivé'
                ]);
            }
            
            $this->updateModuleStatus($moduleName, $newStatus);
            
            $this->logService->log('module_status_changed', null, [
                'module' => $moduleName,
                'display_name' => $module->getDisplayName(),
                'old_status' => $currentStatus,
                'new_status' => $newStatus
            ]);
            
            return response()->json([
                'success' => true, 
                'status' => $newStatus,
                'message' => $newStatus === 'active' ? 'Module activé' : 'Module désactivé'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors du changement de statut']);
        }
    }

    public function getModuleInfo($moduleName)
    {
        $module = ModuleRegistry::getModule($moduleName);
        
        if (!$module) {
            return response()->json(['success' => false, 'message' => 'Module non trouvé']);
        }

        return response()->json([
            'success' => true,
            'module' => [
                'name' => $module->getName(),
                'displayName' => $module->getDisplayName(),
                'description' => $module->getDescription(),
                'version' => $module->getVersion(),
                'permissions' => $module->getPermissions(),
                'routes' => $module->getRoutes(),
                'isActive' => $module->isActive(),
                'category' => $this->getModuleCategory($module->getName())
            ]
        ]);
    }

    private function getConfigPath($moduleName)
    {
        return storage_path("app/modules/" . strtolower($moduleName) . "/config.json");
    }

    private function ensureConfigExists($moduleName)
    {
        $configPath = $this->getConfigPath($moduleName);
        
        if (!File::exists(dirname($configPath))) {
            try {
                File::makeDirectory(dirname($configPath), 0755, true);
            } catch (\Exception $e) {
                $this->logService->log('module_dir_creation_failed', null, [
                    'module' => $moduleName,
                    'path' => dirname($configPath),
                    'reason' => $e->getMessage()
                ]);
                throw $e;
            }
        }
        
        if (!File::exists($configPath)) {
            try {
                File::put($configPath, json_encode(['status' => 'inactive'], JSON_PRETTY_PRINT));
            } catch (\Exception $e) {
                $this->logService->log('module_config_creation_failed', null, [
                    'module' => $moduleName,
                    'path' => $configPath,
                    'reason' => $e->getMessage()
                ]);
                throw $e;
            }
        }

        return $configPath;
    }

    private function getModuleConfig($moduleName)
    {
        $configPath = $this->ensureConfigExists($moduleName);
        
        return File::exists($configPath) 
            ? (json_decode(File::get($configPath), true) ?? []) 
            : [];
    }

    private function updateModuleStatus($moduleName, $status)
    {
        try {
            $config = $this->getModuleConfig($moduleName);
            $config['status'] = $status;
            $config['updated_at'] = now()->toDateTimeString();
            
            File::put($this->getConfigPath($moduleName), json_encode($config, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->logService->log('module_status_update_failed', null, [
                'module' => $moduleName,
                'status' => $status,
                'reason' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function getModuleCategory($moduleName)
    {
        $categories = [
            'Core' => 'Système',
            'Personnels' => 'Gestion',
            'Settings' => 'Administration',
            'Pages' => 'Contenu',
            'ImageAPI' => 'Médias',
            'Log' => 'Système',
            'Agenda' => 'Organisation',
            'Internat' => 'Gestion'
        ];

        return $categories[$moduleName] ?? 'Autre';
    }
}