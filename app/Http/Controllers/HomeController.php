<?php

namespace App\Http\Controllers;

use App\Modules\WebTv\Controllers\WebTvController;
use App\Modules\Internat\Controllers\InternatController;
use App\Modules\Agenda\Models\AgendaEvent;

class HomeController extends Controller
{
    protected $webTvController;
    protected $internatController;

    public function __construct(WebTvController $webTvController, InternatController $internatController)
    {
        $this->webTvController = $webTvController;
        $this->internatController = $internatController;
    }

    public function index()
    {
        $evenements = AgendaEvent::all();
        $data = [
            'liveId' => $this->webTvController->getLive(),
            'evenements' => $evenements,
            'evenements_json' => $evenements->map(function($e) {
                return [
                    'id' => $e->id,
                    'titre' => $e->titre,
                    'date' => $e->date,
                    'heure_debut' => $e->heure_debut,
                    'heure_fin' => $e->heure_fin,
                    'description' => $e->description,
                ];
            }),
        ];

        return view('Theme.Default.landing.index', $data);
    }
} 