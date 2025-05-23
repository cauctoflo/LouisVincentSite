<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Log\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| Log Module Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('personnels/log')->name('personnels.Log.')->group(function () {

        Route::get('/settings', [LogController::class, 'settings'])->name('settings');
        Route::post('/settings', [LogController::class, 'saveSettings'])->name('settings.save');


        Route::get('/', [LogController::class, 'index'])->name('index');
        Route::get('/export', [LogController::class, 'export'])->name('export');
        Route::get('/{log}', [LogController::class, 'show'])->name('show');
        Route::get('/user/{user}', [LogController::class, 'userLogs'])->name('user');
        Route::delete('/{log}', [LogController::class, 'destroy'])->name('destroy');
        Route::post('/clear', [LogController::class, 'clear'])->name('clear');


    

    });
});
