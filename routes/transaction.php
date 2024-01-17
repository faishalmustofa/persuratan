<?php

use App\Http\Controllers\Transaction\BukuAgendaController;
use App\Http\Controllers\Transaction\DisposisiController;
use App\Http\Controllers\Transaction\DisposisiMasukController;
use App\Http\Controllers\Transaction\SuratKeluarController;
use App\Http\Controllers\Transaction\SuratMasukController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('transaction')->group(function(){
    /** MENU SURAT MASUK */
    Route::prefix('surat-masuk')->group(function(){
        Route::get('/{txNo?}', [SuratMasukController::class, 'index'])->name('create-bukuagenda');
        Route::post('/store', [SuratMasukController::class, 'store']);
        Route::post('/data', [SuratMasukController::class, 'data']);
        Route::get('/print-blanko/{txNo}', [SuratMasukController::class, 'printBlanko']);
        Route::get('/download-blanko/{file}', [SuratMasukController::class, 'downloadBlanko']);
        Route::get('/pindah-berkas/{txNo}', [SuratMasukController::class, 'pindahBerkas']);
        Route::get('/terima-berkas/{txNo}', [SuratMasukController::class, 'terimaBerkas']);
        Route::get('/show-pdf/{txNumber}', [SuratMasukController::class, 'showPdf'])->name('showPDF');
    });

    /** MENU BUKU AGENDA SURAT MASUK*/
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
        Route::post('/pengiriman-surat', [DisposisiController::class, 'pengirimanSurat']);
    });

    /** MENU DISPOSISI MASUK */
    Route::prefix('disposisi-masuk')->group(function(){
        Route::get('/', [DisposisiMasukController::class, 'index']);
        Route::post('/get-data', [DisposisiMasukController::class, 'getData']);
    });

    /** MENU SURAT KELUAR */
    Route::prefix('surat-keluar')->group(function(){
        Route::get('/{txNo?}', [SuratKeluarController::class, 'index'])->name('create-bukuagenda-suratkeluar');
        Route::post('/store', [SuratKeluarController::class, 'store']);
        Route::post('/data', [SuratKeluarController::class, 'data']);
        Route::get('/minta-no-surat/{txNo}', [SuratKeluarController::class, 'mintaNoSurat']);
    });
    
    /** MENU SURAT KELUAR */
    Route::prefix('permintaan-no-surat')->group(function(){
        Route::get('/', [SuratKeluarController::class, 'permintaanNoSurat']);
        Route::post('/data', [SuratKeluarController::class, 'dataMintaNoSurat']);
    });

    /** MENU BUKU AGENDA SURAT KELUAR*/
    Route::prefix('buku-agenda-surat-keluar')->group(function(){
        Route::get('/', [BukuAgendaController::class, 'index']);
        Route::post('/get-data', [BukuAgendaController::class, 'getData']);
    });
});

?>
