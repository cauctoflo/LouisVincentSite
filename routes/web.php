<?php

use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Http\Controllers\WelcomePageController;
use App\Modules\WebTv\Controllers\WebTvController;
use App\Http\Controllers\Admin\View\Sidebar\JsonReadController;
use App\Http\Controllers\PagesIndexController;

















//
// ROUTE LIVESHARE
//

Route::get('/liveshare', function () {
    return 'https://prod.liveshare.vsengsaas.visualstudio.com/join?8F3B61891510448E9CE4D1CAB42B6DB0A44E';
})->name('test');


// Route pour l'index des pages


require __DIR__.'/public/routes.php';
require __DIR__.'/admin/routes.php';