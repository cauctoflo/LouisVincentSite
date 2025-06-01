<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class ModulesController extends Controller
{
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
                return redirect()->route('personnels.modules.index')
                    ->with('error', "Le module $module n'a pas de page de paramètres.");
        }
    }
} 