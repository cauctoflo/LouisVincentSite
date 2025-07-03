<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Theme\ThemeController;
use App\Modules\Agenda\Models\AgendaEvent;

class ViewDispatchController extends Controller
{
    public function getPath($view)
    {
        $themeController = new ThemeController();
        $theme = $themeController->getTheme();
        if ($view === 'landing.index') {
            $evenements = AgendaEvent::all();
            $evenements_json = $evenements->map(function($e) {
                return [
                    'id' => $e->id,
                    'titre' => $e->titre,
                    'date' => $e->date,
                    'heure_debut' => $e->heure_debut,
                    'heure_fin' => $e->heure_fin,
                    'description' => $e->description,
                    'couleur' => $e->couleur,
                    'lieu' => $e->lieu,
                ];
            });
            return view('Theme.' . $theme . '.' . $view, [
                'evenements' => $evenements,
                'evenements_json' => $evenements_json,
            ]);
        }
        return view('Theme.' . $theme . '.' . $view);
    }

    public function index()
    {
        return $this->getPath('landing.index');
    }

}
