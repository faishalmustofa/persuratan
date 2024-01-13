<?php

use App\Http\Controllers\auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRole\RoleController;

// Auth
Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login-process', [AuthController::class, 'loginProcess']);

Route::middleware(['auth'])->group(function() {
    // Main Page Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboards');
    // locale
    Route::get('lang/{locale}', [LanguageController::class, 'swap']);

    Route::get('print/{file}', function($file){
        $path = public_path().'/document/disposisi/'.$file;
        return response()->download($path)->deleteFileAfterSend(true);
    });

    // Route::resources([
    //     'roles' => RoleController::class,
    //     'users' => UserController::class,
    // ]);

    Route::prefix('roles')->group(function(){
        Route::get('/', [RoleController::class, 'index']);
    }); 


});