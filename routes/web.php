<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\Transaction\SuratMasukController;

// Main Page Route
Route::get('/', [DashboardController::class, 'index'])->name('dashboards');
// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::get('print/{file}', function($file){
    $path = public_path().'/document/disposisi/'.$file;
    return response()->download($path)->deleteFileAfterSend(true);
});
