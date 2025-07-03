<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Agenda\Controllers\AgendaController;

/*
|--------------------------------------------------------------------------
| Agenda Module Routes
|--------------------------------------------------------------------------
|
*/

// Routes administratives
Route::prefix('personnels/agenda')->middleware(['web', 'auth'])->name('personnels.Agenda.')->group(function () {
    Route::get('/settings', [AgendaController::class, 'settings'])->name('settings');
    Route::post('/settings', [AgendaController::class, 'saveSettings'])->name('settings.save');
    Route::post('/evenements/ajouter', [AgendaController::class, 'addEvent'])->name('evenements.ajouter');
    Route::post('/evenements/supprimer/{id}', [AgendaController::class, 'deleteEvent'])->name('evenements.supprimer');
    Route::post('/evenements/modifier/{id}', [AgendaController::class, 'updateEvent'])->name('evenements.modifier');
});

