<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\ViewDispatchController;
use App\Http\Controllers\Admin\Settings\SettingsController;


Route::prefix('/admin')->group(function () {
    Route::prefix("/settings")->group(function () {
        Route::get("/", [SettingsController::class, 'index'])->name("index");
        Route::get("/{setting}", [SettingsController::class, 'edit'])->name("edit");
        Route::post("/{setting}", [SettingsController::class, 'store'])->name("store");
    })->name("settings.");
})->name("admin.");

