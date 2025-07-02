<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Pages\Controllers\Admin\SectionController;
use App\Modules\Pages\Controllers\Admin\FolderController;
use App\Modules\Pages\Controllers\Admin\PageController;

/*
|--------------------------------------------------------------------------
| Pages Admin Routes
|--------------------------------------------------------------------------
|
| Routes pour l'administration du module Pages
|
*/

Route::prefix('personnels')->name('personnels.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    // Route principale du gestionnaire de pages
    Route::get('pages', [\App\Modules\Pages\Controllers\Admin\PagesManagerController::class, 'index'])->name('pages.manager')->middleware('permission:pages.view');
    Route::get('pages/hierarchy', [\App\Modules\Pages\Controllers\Admin\PagesManagerController::class, 'hierarchy'])->name('pages.hierarchy')->middleware('permission:pages.view');
    Route::get('pages/search', [\App\Modules\Pages\Controllers\Admin\PagesManagerController::class, 'search'])->name('pages.search')->middleware('permission:pages.view');
    
    // Routes pour les sections
    Route::prefix('pages/sections')->name('pages.sections.')->middleware('permission:pages.view')->group(function () {
        Route::get('/', [SectionController::class, 'index'])->name('index');
        Route::get('/create', [SectionController::class, 'create'])->name('create')->middleware('permission:pages.create');
        Route::post('/', [SectionController::class, 'store'])->name('store')->middleware('permission:pages.create');
        Route::get('/{section}', [SectionController::class, 'show'])->name('show');
        Route::get('/{section}/edit', [SectionController::class, 'edit'])->name('edit')->middleware('permission:pages.edit');
        Route::put('/{section}', [SectionController::class, 'update'])->name('update')->middleware('permission:pages.edit');
        Route::delete('/{section}', [SectionController::class, 'destroy'])->name('destroy')->middleware('permission:pages.delete');
        
        // Routes pour gérer les responsables de section
        Route::post('/{section}/responsibles', [SectionController::class, 'addResponsible'])->name('add-responsible')->middleware('permission:pages.edit');
        Route::delete('/{section}/responsibles/{user}', [SectionController::class, 'removeResponsible'])->name('remove-responsible')->middleware('permission:pages.edit');
    });
    
    // Routes pour les dossiers
    Route::prefix('pages/folders')->name('pages.folders.')->middleware('permission:pages.view')->group(function () {
        Route::get('/', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'index'])->name('index');
        Route::get('/create', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'create'])->name('create')->middleware('permission:pages.create');
        Route::post('/', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'store'])->name('store')->middleware('permission:pages.create');
        Route::get('/{folder}', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'show'])->name('show');
        Route::get('/{folder}/edit', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'edit'])->name('edit')->middleware('permission:pages.edit');
        Route::put('/{folder}', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'update'])->name('update')->middleware('permission:pages.edit');
        Route::delete('/{folder}', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'destroy'])->name('destroy')->middleware('permission:pages.delete');
    });
    
    // Routes pour les pages
    Route::prefix('pages/list')->name('pages.pages.')->middleware('permission:pages.view')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/create', [PageController::class, 'create'])->name('create')->middleware('permission:pages.create');
        Route::post('/', [PageController::class, 'store'])->name('store')->middleware('permission:pages.create');
        Route::get('/{page}', [PageController::class, 'show'])->name('show');
        Route::get('/{page}/edit', [PageController::class, 'edit'])->name('edit')->middleware('permission:pages.edit');
        Route::put('/{page}', [PageController::class, 'update'])->name('update')->middleware('permission:pages.edit');
        Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy')->middleware('permission:pages.delete');
        
        // Routes spéciales pour la publication
        Route::post('/{page}/publish', [PageController::class, 'publish'])->name('publish')->middleware('permission:pages.publish');
        Route::post('/{page}/unpublish', [PageController::class, 'unpublish'])->name('unpublish')->middleware('permission:pages.publish');
        
        // Routes pour les brouillons
        Route::get('/drafts/list', [PageController::class, 'drafts'])->name('drafts')->middleware('permission:pages.view_drafts');
        
        // Routes pour l'auto-save
        Route::post('/{page}/autosave', [PageController::class, 'autosave'])->name('autosave')->middleware('permission:pages.edit');
        
        // Routes pour la prévisualisation
        Route::get('/{page}/preview', [PageController::class, 'preview'])->name('preview')->middleware('permission:pages.edit');
        
        // Routes pour l'historique des révisions
        Route::get('/{page}/revisions', [PageController::class, 'revisions'])->name('revisions')->middleware('permission:pages.view');
        Route::post('/{page}/revisions/{revision}/restore', [PageController::class, 'restoreRevision'])->name('restore-revision')->middleware('permission:pages.edit');
        
        // Routes AJAX
        Route::get('/folders/list', [PageController::class, 'getFolders'])->name('folders');
        Route::get('/search', [PageController::class, 'search'])->name('search');
    });
    
    // Routes API pour AJAX
    Route::prefix('pages/api')->name('pages.api.')->group(function () {
        Route::get('/folders', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'getFolders'])->name('folders');
        Route::get('/sections/{section}/folders', [\App\Modules\Pages\Controllers\Admin\SimpleFolderController::class, 'getSectionFolders'])->name('section-folders');
    });
    
});
