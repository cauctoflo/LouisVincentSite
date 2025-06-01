<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Internat\Controllers\InternatController;


/*
|--------------------------------------------------------------------------
| Internat Module Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('internat')->name('personnels.Internat.')->group(function () {
    Route::get('/settings', [InternatController::class, 'settings'])->name('settings');
});
