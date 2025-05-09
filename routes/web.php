<?php

use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Http\Controllers\WelcomePageController;

Route::get('/', [WelcomePageController::class, 'index'])->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});




Route::get('/timer', function () {
    return view('Theme.Default.Assets.timer.index');
});

Route::get('/abc', function () {
    return view('Theme.Default.landing.index');
});












//
// ROUTE LIVESHARE
//

Route::get('/test', function () {
    return 'https://prod.liveshare.vsengsaas.visualstudio.com/join?F819405A9EC99B4E5C38AA1526F90B0FC146';
})->name('test');