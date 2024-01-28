<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanNoSurat extends Model
{
    use HasFactory;
    protected $table = 'permintaan_no_surat';
    protected $guarded = [];

    function suratKeluar(){
        return $this->hasOne(SuratKeluar::class, 'tx_number', 'tx_number');
    }
}
