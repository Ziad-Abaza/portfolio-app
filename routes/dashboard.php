<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');

    Route::controller(App\Http\Controllers\Dashboard\ProjectController::class)->prefix('projects')->name('projects.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{project}/edit', 'edit')->name('edit');
        Route::post('/{project}', 'update')->name('update');
        Route::delete('/{project}', 'destroy')->name('destroy');
    });

    Route::controller(App\Http\Controllers\Dashboard\MessageController::class)->prefix('messages')->name('messages.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{message}', 'show')->name('show');
        Route::delete('/{message}', 'destroy')->name('destroy');
    });

    Route::controller(App\Http\Controllers\Dashboard\PortfolioController::class)->prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{portfolio}/edit', 'edit')->name('edit');
        Route::post('/{portfolio}', 'update')->name('update');
        Route::delete('/{portfolio}', 'destroy')->name('destroy');
    });
});
