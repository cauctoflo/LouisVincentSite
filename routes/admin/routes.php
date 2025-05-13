<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\View\ViewDispatchController;
use App\Http\Controllers\Admin\Settings\SettingsController;


Route::prefix('/personnels')->name('personnels.')->group(function () {
    Route::prefix("/settings")->name("settings.")->group(function () {
        Route::get("/", [SettingsController::class, 'index'])->name("index");
        Route::get("/{setting}", [SettingsController::class, 'edit'])->name("edit");
        Route::post("/{setting}", [SettingsController::class, 'store'])->name("store");
    });

    Route::get("/", [ViewDispatchController::class, 'index'])->name("index");



})->middleware(
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',);


