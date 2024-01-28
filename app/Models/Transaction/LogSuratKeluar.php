<?php

namespace App\Models\Transaction;

use App\Models\Master\StatusSurat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'log_surat_keluar';
    protected $guarded = [];

    function statusSurat(){
        return $this->hasOne(StatusSurat::class, 'id', 'status');
    }
    
    function suratKeluar(){
        return $this->hasOne(SuratKeluar::class, 'tx_number', 'tx_number');
    }
}
