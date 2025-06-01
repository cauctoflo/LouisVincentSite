<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Personnels\Controllers\PersonnelsController;
use App\Modules\Personnels\Controllers\RoleController;
use App\Modules\Personnels\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Personnels Module Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('personnels')->name('personnels.')->group(function () {
    
    // Routes pour la gestion des personnels
    Route::prefix('personnels')->name('personnels.')->group(function () {
        Route::get('/', [PersonnelsController::class, 'index'])->name('index');
        Route::get('/create', [PersonnelsController::class, 'create'])->name('create');
        Route::post('/', [PersonnelsController::class, 'store'])->name('store');
        Route::get('/{personnel}', [PersonnelsController::class, 'show'])->name('show');

        

        Route::get('/{personnel}/edit', [PersonnelsController::class, 'edit'])->name('edit');
        Route::put('/{personnel}', [PersonnelsController::class, 'update'])->name('update');
        Route::delete('/{personnel}', [PersonnelsController::class, 'destroy'])->name('destroy');
    });
    
    // Routes pour la gestion des rôles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        
        Route::get('/assign', [RoleController::class, 'assignForm'])->name('assign.form');
        Route::post('/assign', [RoleController::class, 'assign'])->name('assign');
        Route::delete('/assign', [RoleController::class, 'removeRole'])->name('remove');
    });
    
    // Routes pour la gestion des permissions
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/{user}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{user}', [PermissionController::class, 'update'])->name('update');
    });
})->middleware(
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',);
