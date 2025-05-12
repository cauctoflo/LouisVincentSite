<?php

use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Http\Controllers\WelcomePageController;


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

Route::get('/te', function () {
    return view('Theme.Default.landing.index');
});














//
// ROUTE LIVESHARE
//

Route::get('/test', function () {
    return 'https://prod.liveshare.vsengsaas.visualstudio.com/join?0717518A0A34C63184F25893553E3BAF430C';
})->name('test');


require __DIR__.'/public/routes.php';
require __DIR__.'/admin/routes.php';