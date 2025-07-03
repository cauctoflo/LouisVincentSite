<?php

namespace App\Modules\Agenda\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaEvent extends Model
{
    protected $table = 'agenda_events';
    protected $fillable = [
        'titre',
        'date',
        'heure_debut',
        'heure_fin',
        'description',
        'couleur',
        'lieu',
    ];
} 