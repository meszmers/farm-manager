<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Farm\AnimalController;
use App\Http\Controllers\Farm\FarmController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'welcome'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::group(['prefix' => 'farms', 'as' => 'farms.'], function() {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::post('/', [FarmController::class, 'create'])->name('create');

        Route::get('/{id}', [DashboardController::class, 'show']);
        Route::post('/{id}/animals', [AnimalController::class, 'create']);
        Route::delete('/{id}/animals/{animalId}/delete', [AnimalController::class, 'delete']);
        Route::post('/{id}/animals/{animalId}/transfer', [AnimalController::class, 'transfer']);
    });

    Route::get('/create-farm', [DashboardController::class, 'createFarm']);
    Route::get('/farms-with-space', [FarmController::class, 'getFarmsWithSpace']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
