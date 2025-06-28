<?php

namespace App\Modules\Internat\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InternatController extends Controller
{
    /**
     * Chemin du fichier de configuration
     */
    private string $configPath;

    public function __construct()
    {
        $this->configPath = storage_path('app/modules/internat/config.json');
    }

    /**
     * Récupère la configuration de l'internat
     */
    public function getConfig(): array
    {
        if (File::exists($this->configPath)) {
            return json_decode(File::get($this->configPath), true) ?? [];
        }
        return [];
    }

    /**
     * Affiche la page d'accueil de l'internat
     */
    public function index()
    {
        $config = $this->getConfig();
        return view('Internat::front.index', compact('config'));
    }

    /**
     * Affiche la page des paramètres de l'internat
     */
    public function settings()
    {
        $config = $this->getConfig();
        return view('Internat::admin.settings', compact('config'));
    }

    /**
     * Enregistre les paramètres de l'internat
     */
    public function saveSettings(Request $request)
    {
        try {
            // On repart d'un tableau vide à chaque sauvegarde
            $config = [
                'status' => 'active',
                'description' => $request->input('description'),
                'informations' => [],
                'video' => [
                    'url' => $request->input('video_url'),
                    'title' => $request->input('video_title'),
                    'description' => $request->input('video_description'),
                ],
                'points_forts' => [],
                'temoignages' => [],
            ];

            // Traitement des informations (toujours exactement 4)
            if ($request->has('informations')) {
                for ($i = 0; $i < 4; $i++) {
                    $information = $request->input('informations.' . $i, []);
                    $config['informations'][] = [
                        'key_number' => $information['key_number'] ?? '',
                        'phrase' => $information['phrase'] ?? '',
                        'icon' => $information['icon'] ?? ''
                    ];
                }
            }

            // Traitement des points forts (uniquement ceux qui sont envoyés)
            if ($request->has('points_forts')) {
                foreach ($request->input('points_forts') as $point_fort) {
                    if (!empty($point_fort['titre'])) {
                        $config['points_forts'][] = [
                            'titre' => $point_fort['titre'],
                            'description' => $point_fort['description'],
                            'icon' => $point_fort['icon']
                        ];
                    }
                }
            }

            // Traitement des témoignages (uniquement ceux qui sont envoyés)
            if ($request->has('temoignages')) {
                foreach ($request->input('temoignages') as $temoignage) {
                    if (!empty($temoignage['nom'])) {
                        $config['temoignages'][] = [
                            'nom' => $temoignage['nom'],
                            'statut' => $temoignage['statut'],
                            'texte' => $temoignage['texte']
                        ];
                    }
                }
            }

            // Créer le répertoire s'il n'existe pas
            $directory = dirname($this->configPath);
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Sauvegarde de la nouvelle configuration (écrase l'ancienne)
            File::put($this->configPath, json_encode($config, JSON_PRETTY_PRINT));

            return redirect()->back()->with('success', 'Les paramètres ont été enregistrés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement des paramètres : ' . $e->getMessage());
        }
    }
}

