<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Pages\Controllers\PublicPageController;

/*
|--------------------------------------------------------------------------
| Pages Public Routes
|--------------------------------------------------------------------------
|
| Routes publiques pour l'affichage des pages
|
*/

// Route pour la page d'accueil des pages
Route::get('/pages', [PublicPageController::class, 'index'])->name('pages.index');

// Routes pour l'affichage des sections et pages
Route::prefix('pages')->name('pages.')->group(function () {

    
    // Affichage d'une section avec ses dossiers et pages
    Route::get('/{section}', [PublicPageController::class, 'showSection'])->name('sections.show');
    
    // Affichage d'un dossier dans une section
    Route::get('/{section}/{folder}', [PublicPageController::class, 'showFolder'])->name('folders.show');
    
    // Affichage d'une page dans un dossier d'une section
    Route::get('/{section}/{folder}/{page}', [PublicPageController::class, 'showPage'])->name('show');
    
    // Affichage d'une page directement dans une section (sans dossier)
    Route::get('/{section}/pages/{page}', [PublicPageController::class, 'showPageInSection'])->name('section-pages.show');
    
});

// Route de recherche dans les pages
Route::get('/search/pages', [PublicPageController::class, 'search'])->name('pages.search');
