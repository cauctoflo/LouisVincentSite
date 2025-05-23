<?php

use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Http\Controllers\WelcomePageController;
use App\Modules\WebTv\Controllers\WebTvController;
use App\Http\Controllers\Admin\View\Sidebar\JsonReadController;
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
















//
// ROUTE LIVESHARE
//

Route::get('/liveshare', function () {
    return 'https://prod.liveshare.vsengsaas.visualstudio.com/join?1ADE156BD7DE8E7E0132A46F4209CCE1CC25';
})->name('test');


require __DIR__.'/public/routes.php';
require __DIR__.'/admin/routes.php';