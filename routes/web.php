<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\dashboard\DashboardController;

// Main Page Route
Route::get('/', [DashboardController::class, 'index'])->name('dashboards');
// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
