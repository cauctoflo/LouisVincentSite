<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\WebTv\Controllers\WebTvController;
use App\Modules\Pages\Models\Folder;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API pour les sections et dossiers
Route::get('/sections/{section}/folders', function ($sectionId) {
    try {
        // Récupérer tous les dossiers d'une section
        $folders = Folder::where('section_id', $sectionId)
                        ->where('is_active', 1) // Corrigé: status -> is_active
                        ->orderBy('name')
                        ->get(['id', 'name']);
                        
        return response()->json($folders);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

