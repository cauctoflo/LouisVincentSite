<?php

namespace App\Modules\WebTv\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class WebTvAdminController extends Controller
{
    /**
     * Affiche la page des paramètres généraux du module WebTv
     */
    public function settings()
    {
        // Charger la configuration existante si elle existe
        $configPath = storage_path('app/modules/webtv/config.json');
        $config = [];
        
        if (File::exists($configPath)) {
            $config = json_decode(File::get($configPath), true) ?? [];
        }

        return view('WebTv::admin.settings', compact('config'));
    }

    /**
     * Enregistre les paramètres du module WebTv
     */
    public function saveSettings(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            // Paramètres généraux
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            
            // Paramètres de la chaîne
            'streaming_service' => 'required|string|in:youtube,vimeo,dailymotion,custom',
            'channel_id' => 'required|string|max:255',
            'api_key' => 'nullable|string|max:255',
            
            // Paramètres d'affichage
            'autoplay' => 'nullable|boolean',
            'max_videos' => 'nullable|integer|min:1|max:50',
            'default_resolution' => 'nullable|string|in:360p,480p,720p,1080p',
            'show_controls' => 'nullable|boolean',
            'show_related' => 'nullable|boolean',
            'show_title' => 'nullable|boolean',
            
            // Paramètres avancés
            'cache_duration' => 'nullable|integer|min:0|max:1440',
            'custom_css' => 'nullable|string|max:5000',
        ]);

        // Convertir les valeurs de checkbox en booléens
        $checkboxFields = ['autoplay', 'show_controls', 'show_related', 'show_title'];
        foreach ($checkboxFields as $field) {
            $validated[$field] = isset($request[$field]) ? true : false;
        }

        // Définir des valeurs par défaut pour les champs non renseignés
        $defaults = [
            'max_videos' => 10,
            'default_resolution' => '720p',
            'show_controls' => true,
            'show_title' => true,
            'cache_duration' => 60,
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($validated[$key])) {
                $validated[$key] = $value;
            }
        }

        // Enregistrement des paramètres dans un fichier config
        $configPath = storage_path('app/modules/webtv/config.json');
        
        // Créer le répertoire s'il n'existe pas
        if (!File::exists(dirname($configPath))) {
            File::makeDirectory(dirname($configPath), 0755, true);
        }
        
        // Enregistrer la configuration
        File::put($configPath, json_encode($validated, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Paramètres de la WebTV enregistrés avec succès.');
    }
} 