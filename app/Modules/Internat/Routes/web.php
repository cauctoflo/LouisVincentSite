<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Internat Module Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('internat')->group(function () {
    Route::get('/', function () {
        return 'test';
    });
});
