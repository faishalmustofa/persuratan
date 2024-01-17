<?php

use App\Http\Controllers\MasterData\AsalSuratController;
use App\Http\Controllers\MasterData\OrganizationController;
use App\Http\Controllers\MasterData\StatusDisposisiController;
use App\Http\Controllers\MasterData\StatusSuratController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    Route::prefix('master-data')->group(function () {
        /** Asal Surat */
        Route::prefix('asal-surat')->group(function () {
            Route::get('/', [AsalSuratController::class, 'index'])->name('masterdata.asal-surat');
        });

        /** Organization */
        Route::prefix('organization')->group(function () {
            Route::get('/', [OrganizationController::class, 'index'])->name('masterdata.organization');
            Route::post('/data', [OrganizationController::class, 'data']);
            Route::get('/edit/{id}', [OrganizationController::class, 'edit'])->name('organization.edit');
            Route::post('/update/{id}', [OrganizationController::class, 'update']);
            Route::get('/add', [OrganizationController::class, 'create'])->name('organization.create');
            Route::post('/add/store', [OrganizationController::class, 'store']);
            Route::get('/delete/{id}', [OrganizationController::class, 'destroy']);
        });

        /** Status Disposisi */
        Route::prefix('status-disposisi')->group(function () {
            Route::get('/', [StatusDisposisiController::class, 'index'])->name('masterdata.status-disposisi');
        });

        /** Status Surat */
        Route::prefix('status-surat')->group(function () {
            Route::get('/', [StatusSuratController::class, 'index'])->name('masterdata.status-surat');
            Route::post('/data', [StatusSuratController::class, 'data']);
            Route::get('/edit/{id}', [StatusSuratController::class, 'edit'])->name('status-surat.edit');
            Route::post('/update/{id}', [StatusSuratController::class, 'update']);
            Route::get('/add', [StatusSuratController::class, 'create'])->name('status-surat.create');
            Route::post('/add/store', [StatusSuratController::class, 'store']);
            Route::get('/delete/{id}', [StatusSuratController::class, 'destroy']);
        });
    });
});


?>

