<?php

use App\Http\Controllers\BoostController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index']);


Route::get('/boosts', [BoostController::class, 'index']);
Route::post('/boosts', [BoostController::class, 'store']);
Route::post('/boosts/{id}/use', [BoostController::class, 'use']);
