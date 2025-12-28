<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Portfolio Routes
Route::get('/', [PortfolioController::class, 'home'])->name('portfolio.home');
Route::get('/about', [PortfolioController::class, 'about'])->name('portfolio.about');
Route::get('/skills', [PortfolioController::class, 'skills'])->name('portfolio.skills');
Route::get('/projects', [PortfolioController::class, 'projects'])->name('portfolio.projects');
Route::get('/contact', [PortfolioController::class, 'contact'])->name('portfolio.contact');
Route::post('/contact', [PortfolioController::class, 'submitContact'])->name('portfolio.contact.submit');

// Service Detail Route
Route::get('/services/{slug}', [ServiceController::class, 'showPublic'])->name('portfolio.service.detail');

// Language Switching
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// API Routes for Services and Projects
Route::prefix('api')->group(function () {
    // Public API endpoints
    Route::get('/services', [ServiceController::class, 'public'])->name('api.services.public');
    Route::get('/projects', [ProjectController::class, 'public'])->name('api.projects.public');
    Route::get('/projects/featured', [ProjectController::class, 'getFeatured'])->name('api.projects.featured');
    Route::get('/projects/category/{category}', [ProjectController::class, 'byCategory'])->name('api.projects.by-category');
});

// Admin Resource Routes (protected by auth middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Service Resource Routes
    Route::resource('services', ServiceController::class)->except(['create', 'edit']);
    
    // Project Resource Routes
    Route::resource('projects', ProjectController::class)->except(['create', 'edit']);
    
    // Additional Service Routes
    Route::post('/services/{service}/toggle', [ServiceController::class, 'toggle'])->name('services.toggle');
    Route::post('/services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');
    Route::delete('/services/{service}/force', [ServiceController::class, 'forceDelete'])->name('services.force-delete');
    
    // Additional Project Routes
    Route::post('/projects/{project}/toggle', [ProjectController::class, 'toggle'])->name('projects.toggle');
    Route::post('/projects/{project}/featured', [ProjectController::class, 'featured'])->name('projects.featured');
    Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('/projects/{project}/force', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
