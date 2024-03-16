<?php

use App\Http\Controllers\Transaction\BukuAgendaController;
use App\Http\Controllers\Transaction\BukuAgendaSuratKeluar;
use App\Http\Controllers\Transaction\DisposisiController;
use App\Http\Controllers\Transaction\DisposisiMasukController;
use App\Http\Controllers\Transaction\LaporanPenerimaanSuratController;
use App\Http\Controllers\Transaction\LogSuratMasukController;
use App\Http\Controllers\Transaction\PengirimanSuratKeluarController;
use App\Http\Controllers\Transaction\PermintaanNoSuratController;
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
        Route::post('/terima-berkas', [SuratMasukController::class, 'terimaBerkas']);
        Route::get('/show-pdf/{txNumber}', [SuratMasukController::class, 'showPdf'])->name('showPDF');
        Route::post('/edit-tgl', [SuratMasukController::class, 'editTgl']);
        Route::post('/revisi-berkas', [SuratMasukController::class, 'revisiBerkas']);
        Route::get('/view-reject/{tx_number}', [SuratMasukController::class, 'viewReject']);
        Route::get('/cek-surat/{noSurat}', [SuratMasukController::class, 'cekSurat']);
        Route::post('/kirim-bundling', [SuratMasukController::class, 'pindahBerkasBundling']);
    });

    /** MENU BUKU AGENDA SURAT MASUK*/
    Route::prefix('buku-agenda')->group(function(){
        Route::get('/{txNo?}', [BukuAgendaController::class, 'index']);
        Route::post('/get-data', [BukuAgendaController::class, 'getData']);
        Route::post('/cetak-laporan', [BukuAgendaController::class, 'printLaporan']);
    });

    /** MENU DISPOSISI KELUAR */
    Route::prefix('disposisi')->group(function(){
        Route::get('/{noAgenda?}', [DisposisiController::class, 'index']);
        Route::post('/store', [DisposisiController::class, 'store']);
        Route::post('/get-data', [DisposisiController::class, 'getData']);
        Route::get('/get-tujuan/{txNumber}', [DisposisiController::class, 'getTujuanDisposisi']);
        Route::get('/detail/{txNo}', [DisposisiController::class, 'show']);
        Route::post('/kirim-bundling', [DisposisiController::class, 'kirimBulking']);
        Route::post('/pengiriman-surat', [DisposisiController::class, 'pengirimanSurat']);
        Route::get('/revisi/{txNo}', [DisposisiController::class, 'revisiDisposisi']);
    });

    /** MENU DISPOSISI MASUK */
    Route::prefix('disposisi-masuk')->group(function(){
        Route::get('/', [DisposisiMasukController::class, 'index']);
        Route::post('/get-data', [DisposisiMasukController::class, 'getData']);
    });

    /** MENU LOG SURAT MASUK */
    Route::prefix('log-surat-masuk')->group(function(){
        Route::get('/', [LogSuratMasukController::class, 'index']);
        Route::post('/data', [LogSuratMasukController::class, 'data']);
        Route::get('/show/{txNo}', [LogSuratMasukController::class, 'show']);
    });

    /** MENU SURAT KELUAR */
    Route::prefix('surat-keluar')->group(function(){
        Route::get('/{txNo?}', [SuratKeluarController::class, 'index'])->name('create-bukuagenda-suratkeluar');
        Route::post('/store', [SuratKeluarController::class, 'store']);
        Route::post('/update/{txNo}', [SuratKeluarController::class, 'update']);
        Route::post('/data', [SuratKeluarController::class, 'data']);
        Route::get('/detail/{txNo}', [SuratKeluarController::class, 'show']);
        Route::get('/minta-no-surat/{txNo}', [SuratKeluarController::class, 'mintaNoSurat']);
        Route::get('/download-file/{txNo}', [SuratKeluarController::class, 'downloadFile'])->name('download-surat-keluar');
        Route::get('/get-timeline-surat/{txNo}', [SuratKeluarController::class, 'getTimelineSurat'])->name('get-timeline-surat');
    });

    /** MENU SURAT KELUAR */
    Route::prefix('permintaan-no-surat')->group(function(){
        Route::get('/', [PermintaanNoSuratController::class, 'permintaanNoSurat']);
        Route::post('/data', [PermintaanNoSuratController::class, 'dataMintaNoSurat']);
        Route::post('/log-data', [PermintaanNoSuratController::class, 'logPermintaanSurat']);
        Route::get('/detail/{txNo}', [PermintaanNoSuratController::class, 'detailPermintaanNoSurat']);
        Route::get('/get-form-penomoran-surat/{txNo}', [PermintaanNoSuratController::class, 'detailPenomoranSurat']);
        Route::get('/terima-surat/{txNo}', [PermintaanNoSuratController::class, 'terimaSurat']);
        Route::get('/minta-no-surat/{txNo}', [PermintaanNoSuratController::class, 'mintaNoSurat']);
        Route::post('/tindak-surat', [PermintaanNoSuratController::class, 'tindakSurat']);
        Route::get('/ttd-surat/{txNo}', [PermintaanNoSuratController::class, 'tandaTanganSurat']);
    });

    /** MENU BUKU AGENDA SURAT KELUAR*/
    Route::prefix('buku-agenda-surat-keluar')->group(function(){
        Route::get('/', [BukuAgendaSuratKeluar::class, 'index'])->name('agenda-surat-keluar');
        Route::post('/buat-agenda-surat', [BukuAgendaSuratKeluar::class, 'buatAgenda']);
        Route::post('/log-data', [BukuAgendaSuratKeluar::class, 'logAgenda']);
        Route::get('/get-form-kirim-surat/{txNo}', [BukuAgendaSuratKeluar::class, 'getFormKirimSurat']);
        Route::post('/get-data', [BukuAgendaSuratKeluar::class, 'getData']);
        Route::get('/detail/{txNo}', [BukuAgendaSuratKeluar::class, 'show']);
    });

    /** MENU PENGIRIMAN SURAT KELUAR*/
    Route::prefix('pengiriman-surat-keluar')->group(function(){
        Route::get('/', [PengirimanSuratKeluarController::class, 'index']);
        Route::post('/log-data', [PengirimanSuratKeluarController::class, 'logPengiriman']);
        Route::post('/store', [PengirimanSuratKeluarController::class, 'store']);
        Route::post('/get-data', [PengirimanSuratKeluarController::class, 'getData']);
        Route::get('/detail/{txNo}', [PengirimanSuratKeluarController::class, 'show']);
    });

    /** MENU PENGIRIMAN SURAT KELUAR*/
    Route::prefix('laporan-penerimaan-surat')->group(function(){
        Route::get('/', [LaporanPenerimaanSuratController::class, 'index']);
        Route::get('/buat-laporan-penerimaan/{txNo}', [LaporanPenerimaanSuratController::class, 'buatPenerimaansurat']);
        Route::post('/get-data', [LaporanPenerimaanSuratController::class, 'getData']);
        Route::get('/detail/{txNo}', [LaporanPenerimaanSuratController::class, 'show']);
    });
});

?>
