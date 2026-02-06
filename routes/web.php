<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\LanguageController;

// Public Portfolio Pages
Route::get('/', [HomeController::class, 'show'])->name('portfolio.home');
Route::get('/about', [AboutController::class, 'show'])->name('portfolio.about');
Route::get('/skills', [SkillsController::class, 'show'])->name('portfolio.skills');
Route::get('/projects', [ProjectsController::class, 'show'])->name('portfolio.projects');

// Contact
Route::get('/contact', [ContactController::class, 'showForm'])->name('portfolio.contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('portfolio.contact.submit');

// Service Detail
Route::get('/services/{slug}', [ServiceDetailController::class, 'show'])->name('portfolio.service.detail');

// Language
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';
