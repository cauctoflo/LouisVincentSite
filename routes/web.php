<?php

use Illuminate\Support\Facades\Route;
use App\Models\Setting;

Route::get('/', function () {
    Setting::create(["key" => "theme", "value" => "default"]);
    return view('welcome');
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/test', function () {
    return 'https://prod.liveshare.vsengsaas.visualstudio.com/join?9E034DAD63B48EB8B59F24F7634F9046988D';
})->name('test');