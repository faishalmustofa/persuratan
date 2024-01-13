<?php

use App\Http\Controllers\Transaction\BukuAgendaController;
use App\Http\Controllers\Transaction\DisposisiController;
use App\Http\Controllers\Transaction\SuratMasukController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('transaction')->group(function(){
    /** MENU SURAT MASUK */
    Route::prefix('surat-masuk')->group(function(){
        Route::get('/', [SuratMasukController::class, 'index']);
        Route::post('/store', [SuratMasukController::class, 'store']);
        Route::post('/data', [SuratMasukController::class, 'data']);
        Route::get('/print-blanko/{txNo}', [SuratMasukController::class, 'printBlanko']);
        Route::get('/download-blanko/{file}', [SuratMasukController::class, 'downloadBlanko']);
        Route::get('/show-pdf/{txNumber}', [SuratMasukController::class, 'showPdf'])->name('showPDF');
    });

    /** MENU BUKU AGENDA */
    Route::prefix('buku-agenda')->group(function(){
        Route::get('/', [BukuAgendaController::class, 'index']);
        Route::post('/get-data', [BukuAgendaController::class, 'getData']);
    });

    /** MENU DISPOSISI KELUAR */
    Route::prefix('disposisi')->group(function(){
        Route::get('/', [DisposisiController::class, 'index']);
        Route::post('/store', [DisposisiController::class, 'store']);
        Route::post('/get-data', [DisposisiController::class, 'getData']);
        Route::get('/get-tujuan/{txNumber}', [DisposisiController::class, 'getTujuanDisposisi']);
        Route::get('/detail/{txNo}', [DisposisiController::class, 'show']);
    });

    /** MENU DISPOSISI MASUK */
});

?>
