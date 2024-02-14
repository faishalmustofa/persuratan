<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanSuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'pengiriman_surat_keluar';
    protected $guarded = [];

    function suratKeluar(){
        return $this->hasOne(SuratKeluar::class, 'tx_number', 'tx_number');
    }
}
