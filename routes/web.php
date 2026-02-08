<?php

use App\Http\Controllers\BoostController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('boosts.index');

Route::get('/boosts/create', [BoostController::class, 'create'])->name('boosts.create');
Route::post('/boosts', [BoostController::class, 'store'])->name('boosts.store');


Route::get('/boosts/{boost}/edit', [BoostController::class, 'edit'])->name('boosts.edit');
Route::put('/boosts/{boost}', [BoostController::class, 'update'])->name('boosts.update');

Route::post('/boosts/{boost}/reset', [BoostController::class, 'reset'])->name('boosts.reset');