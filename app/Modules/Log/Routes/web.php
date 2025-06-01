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
    // Routes Web
    Route::prefix('personnels/log')->name('personnels.Log.')->group(function () {
        // Routes avec chemins fixes d'abord
        Route::get('/settings', [LogController::class, 'settings'])->name('settings');
        Route::get('/', [LogController::class, 'index'])->name('index');
        Route::get('/export', [LogController::class, 'export'])->name('export');
        Route::get('/user/{user}', [LogController::class, 'userLogs'])->name('user');
        Route::post('/clear', [LogController::class, 'clear'])->name('clear');
        
        // API routes
        Route::get('/files/list', [LogController::class, 'getLogs'])->name('getLogs');
        Route::get('/files/view', [LogController::class, 'viewLog'])->name('viewLog');
        Route::post('/files/delete', [LogController::class, 'deleteLog'])->name('deleteLog');
        Route::post('/config/save', [LogController::class, 'saveConfig'])->name('saveConfig');
        Route::get('/config/get', [LogController::class, 'getConfig'])->name('getConfig');
        Route::get('/files/export', [LogController::class, 'exportLogs'])->name('exportLogs');
        Route::post('/files/clear-all', [LogController::class, 'clearAllLogs'])->name('clearAllLogs');
        Route::get('/data/db-logs', [LogController::class, 'getDbLogs'])->name('getDbLogs');
        
        // Routes avec paramètres dynamiques en dernier
        Route::get('/{log}', [LogController::class, 'show'])->name('show');
        Route::delete('/{log}', [LogController::class, 'destroy'])->name('destroy');
    });
});
