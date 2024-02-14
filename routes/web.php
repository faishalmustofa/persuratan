<?php

use App\Http\Controllers\auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\UserRole\PermissionController;
use App\Http\Controllers\UserRole\RoleController;
use App\Http\Controllers\UserRole\UserController;

// Auth
Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login-process', [AuthController::class, 'loginProcess']);

Route::middleware(['auth'])->group(function() {
    // Main Page Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboards');
    Route::get('/dashboard-surat-masuk', [DashboardController::class, 'dashboardSuratMasuk'])->name('dashboard-surat-masuk');
    Route::get('/dashboard-surat-keluar', [DashboardController::class, 'dashboardSuratKeluar'])->name('dashboard-surat-keluar');
    // locale
    Route::get('lang/{locale}', [LanguageController::class, 'swap']);

    Route::get('print/{file}', function($file){
        $path = public_path().'/document/disposisi/'.$file;
        return response()->download($path)->deleteFileAfterSend(true);
    });

    Route::prefix('user')->group(function(){
        Route::get('/list', [UserController::class, 'index']);
        Route::post('/data', [UserController::class, 'data']);
    }); 

    Route::prefix('roles')->group(function(){
        Route::get('/', [RoleController::class, 'index']);
    }); 
    
    Route::prefix('permission')->group(function(){
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/data', [PermissionController::class, 'data']);
    }); 
});