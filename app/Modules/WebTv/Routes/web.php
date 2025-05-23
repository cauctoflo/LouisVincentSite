<?php

use Illuminate\Support\Facades\Route;
use App\Modules\WebTv\Controllers\WebTvController;
use App\Modules\WebTv\Controllers\WebTvAdminController;

/*
|--------------------------------------------------------------------------
| WebTv Module Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('webtv')->group(function () {
    Route::get('/video-preview', [WebTvController::class, 'getVideoPreview'])->name('WebTv.video.preview');
});

// Routes d'administration
Route::prefix('personnels/webtv')->middleware(['web', 'auth'])->group(function () {
    Route::get('/settings', [WebTvAdminController::class, 'settings'])->name('personnels.WebTv.settings');
    Route::post('/settings', [WebTvAdminController::class, 'saveSettings'])->name('personnels.WebTv.settings.save');
});
