<?php

use Illuminate\Support\Facades\Route;
use App\Modules\ImageAPI\Controllers\ImageAPIController;

/*
|--------------------------------------------------------------------------
| ImageAPI Module Routes
|--------------------------------------------------------------------------
|
*/


// Sécurisé 
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::prefix('personnels')->middleware(['web', 'auth'])->group(function () {

        Route::prefix('images')->group(function () {
            // Route temporaire de redirection vers l'index en attendant l'implémentation des paramètres
            Route::get('/settings', function() {
                return redirect()->route('personnels.ImageAPI.index')->with('info', 'Les paramètres sont en cours de développement. Vous avez été redirigé vers la page principale.');
            })->name('personnels.ImageAPI.settings');
            
            // Route::post('/settings', [ImageAPIController::class, 'saveSettings'])->name('personnels.ImageAPI.settings.save');  // Commentée car méthode non présente
        
            // Routes Web
            Route::name('personnels.ImageAPI.')->group(function () {
                Route::get('/', [ImageAPIController::class, 'index'])->name('index');
                Route::post('/store', [ImageAPIController::class, 'store'])->name('store');
                Route::put('/update', [ImageAPIController::class, 'update'])->name('update');
                Route::delete('/destroy', [ImageAPIController::class, 'destroy'])->name('destroy');
                Route::post('/folder/store', [ImageAPIController::class, 'createFolder'])->name('folder.store');
                Route::put('/folder/update', [ImageAPIController::class, 'updateFolder'])->name('folder.update');
                Route::delete('/folder/destroy', [ImageAPIController::class, 'destroyFolder'])->name('folder.destroy');
                Route::get('/folder/{id}/images-count', [ImageAPIController::class, 'getFolderImagesCount'])->name('folder.images-count');
            });
        });

    });

});

// Route API non sécurisée
Route::get('/images/token/{token}', [ImageAPIController::class, 'getImagebyToken'])
    ->name('images.token');

