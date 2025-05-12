<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\ViewDispatchController;


Route::get('/', [ViewDispatchController::class, 'index']);

