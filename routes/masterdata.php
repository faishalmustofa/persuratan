<?php

use App\Http\Controllers\MasterData\AsalSuratController;
use App\Http\Controllers\MasterData\OrganizationController;
use App\Http\Controllers\MasterData\StatusDisposisiController;
use App\Http\Controllers\MasterData\StatusSuratController;
use Illuminate\Support\Facades\Route;

Route::prefix('master-data')->group(function () {
    /** Asal Surat */
    Route::prefix('asal-surat')->group(function () {
        Route::get('/', [AsalSuratController::class, 'index'])->name('masterdata.asal-surat');
    });

    /** Organization */
    Route::prefix('organization')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('masterdata.organization');
    });

    /** Status Disposisi */
    Route::prefix('status-disposisi')->group(function () {
        Route::get('/', [StatusDisposisiController::class, 'index'])->name('masterdata.status-disposisi');
    });

    /** Status Surat */
    Route::prefix('status-surat')->group(function () {
        Route::get('/', [StatusSuratController::class, 'index'])->name('masterdata.status-surat');
    });
});
?>

