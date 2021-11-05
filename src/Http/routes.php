<?php

use Illuminate\Support\Facades\Route;
use LaravelCentrifugo\Http\Controllers\MainController;

Route::post('/subscribe', [MainController::class, 'subscribe'])->name('laravel_centrigufo_subscribe');
Route::post('/connect', [MainController::class, 'connect'])->name('laravel_centrigufo_connect');
Route::post('/publish', [MainController::class, 'publish'])->name('laravel_centrigufo_publish');