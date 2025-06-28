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
            Route::get('/settings', [ImageAPIController::class, 'settings'])->name('personnels.ImageAPI.settings');
            Route::post('/settings', [ImageAPIController::class, 'saveSettings'])->name('personnels.ImageAPI.settings.save');
        
            // Routes Web
            Route::name('personnels.ImageAPI.')->group(function () {
                Route::get('/', [ImageAPIController::class, 'index'])->name('index');
                Route::post('/store', [ImageAPIController::class, 'store'])->name('store');
                Route::put('/update', [ImageAPIController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [ImageAPIController::class, 'destroy'])->name('destroy');
                Route::get('/edit', [ImageAPIController::class, 'edit'])->name('edit');
            });
        });

    });

});

// Route API non sécurisée
Route::get('/images/token/{token}', [ImageAPIController::class, 'getImagebyToken'])
    ->name('images.token');

