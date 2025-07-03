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
        // Récupérer tous les dossiers d'une section en utilisant le scope 'active'
        $folders = Folder::active()
                        ->where('section_id', $sectionId)
                        ->orderBy('name')
                        ->get(['id', 'name']);
                        
        return response()->json($folders);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

