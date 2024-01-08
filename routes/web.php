<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\Transaction\SuratMasukController;

// Main Page Route
Route::get('/', [DashboardController::class, 'index'])->name('dashboards');
// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// surat masuk
Route::get('surat-masuk', [SuratMasukController::class, 'index']);
Route::post('surat-masuk/store', [SuratMasukController::class, 'store']);

Route::get('buku-agenda', [SuratMasukController::class, 'bukuAgenda']);

Route::get('disposisi', [SuratMasukController::class, 'disposisi']);