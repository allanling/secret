<?php

use App\Http\Controllers\ObjectController;
use Illuminate\Support\Facades\Route;

Route::controller(ObjectController::class)
    ->prefix('object')->group(function () {
        Route::get('/get_all_records', 'index');
        Route::get('/{key}', 'show');
        Route::post('/', 'store');
    });
