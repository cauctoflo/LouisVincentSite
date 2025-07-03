<?php

namespace App\Modules\Agenda\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Modules\Agenda\Models\AgendaEvent;

class AgendaController extends Controller
{
    /**
     * Chemin du fichier de configuration
     */
    private string $configPath;

    public function __construct()
    {
        $this->configPath = storage_path('app/modules/agenda/config.json');
    }

    /**
     * Récupère la configuration de l'Agenda
     */
    public function getConfig(): array
    {
        if (File::exists($this->configPath)) {
            return json_decode(File::get($this->configPath), true) ?? [];
        }
        return [];
    }

    /**
     * Affiche la page d'accueil de l'Agenda
     */
    public function index()
    {
        $config = $this->getConfig();
        return view('Agenda::front.index', compact('config'));
    }

    /**
     * Affiche la page des paramètres de l'Agenda
     */
    public function settings()
    {
        $config = $this->getConfig();
        $evenements = AgendaEvent::orderBy('date')->orderBy('heure_debut')->get();
        return view('Agenda::admin.settings', compact('config', 'evenements'));
    }

    /**
     * Enregistre les paramètres de l'Agenda
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

    // --- GESTION DES EVENEMENTS ---
    /**
     * Ajoute un évènement
     */
    public function addEvent(Request $request)
    {
        AgendaEvent::create([
            'titre' => $request->input('titre'),
            'date' => $request->input('date'),
            'heure_debut' => $request->input('heure_debut_h') . ':' . $request->input('heure_debut_m'),
            'heure_fin' => $request->input('heure_fin_h') . ':' . $request->input('heure_fin_m'),
            'description' => $request->input('description'),
            'couleur' => $request->input('couleur') ?? 'blue',
            'lieu' => $request->input('lieu'),
        ]);
        return redirect()->back()->with('success', 'Évènement ajouté avec succès.');
    }

    /**
     * Supprime un évènement
     */
    public function deleteEvent($id)
    {
        AgendaEvent::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Évènement supprimé.');
    }

    /**
     * Modifie un évènement
     */
    public function updateEvent(Request $request, $id)
    {
        $event = AgendaEvent::findOrFail($id);
        $event->update([
            'titre' => $request->input('titre'),
            'date' => $request->input('date'),
            'heure_debut' => $request->input('heure_debut_h') . ':' . $request->input('heure_debut_m'),
            'heure_fin' => $request->input('heure_fin_h') . ':' . $request->input('heure_fin_m'),
            'description' => $request->input('description'),
            'couleur' => $request->input('couleur'),
            'lieu' => $request->input('lieu'),
        ]);
        return redirect()->back()->with('success', 'Évènement modifié.');
    }
}
