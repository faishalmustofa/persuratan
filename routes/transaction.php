<?php

use App\Http\Controllers\Transaction\DisposisiController;
use App\Http\Controllers\Transaction\SuratMasukController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    Route::prefix('transaction')->group(function(){
        Route::prefix('surat-masuk')->group(function(){
            Route::get('/', [SuratMasukController::class, 'index']);
            Route::post('/store', [SuratMasukController::class, 'store']);
            Route::post('/data', [SuratMasukController::class, 'data']);
        });
    
        Route::get('buku-agenda', [SuratMasukController::class, 'bukuAgenda']);
    
        Route::prefix('disposisi')->group(function(){
            Route::get('/', [DisposisiController::class, 'index']);
            Route::post('/store', [DisposisiController::class, 'store']);
        });
    }); 
});

?>
