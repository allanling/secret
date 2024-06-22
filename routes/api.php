<?php

use App\Http\Controllers\ObjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureTokenIsValid;


Route::controller(ObjectController::class)
    ->prefix('object')->group(function () {
        Route::get('/get_all_records', 'index');
        Route::get('/{key}', 'show');
        Route::post('/', 'store');
    });


Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::controller(UserController::class)->prefix('auth')->group(function () {
        Route::post('/close', 'close');
        Route::patch('/users/{user_id}', 'update');
        Route::get('/users/{user_id}', 'show');
        Route::post('/signup', 'signup')->withoutMiddleware([EnsureTokenIsValid::class]);
    });
});
