<?php

use Illuminate\Support\Facades\Route;
use LaravelCentrifugo\Http\Controllers\MainController;

Route::post('/subscribe', [MainController::class, 'subscribe']);
Route::post('/connect', [MainController::class, 'connect']);
Route::post('/refresh', [MainController::class, 'refresh']);
Route::post('/publish', [MainController::class, 'publish']);