<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Internat\Controllers\InternatController;


/*
|--------------------------------------------------------------------------
| Internat Module Routes
|--------------------------------------------------------------------------
|
*/
// Routes administratives
Route::prefix('personnels/internat')->middleware(['web', 'auth'])->name('personnels.Internat.')->group(function () {
    Route::get('/settings', [InternatController::class, 'settings'])->name('settings');
    Route::post('/settings', [InternatController::class, 'saveSettings'])->name('settings.save');
});

