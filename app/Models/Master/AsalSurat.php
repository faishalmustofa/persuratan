<?php

namespace App\Models\Master;

use App\Models\Transaction\SuratMasuk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsalSurat extends Model
{
    use HasFactory;
    protected $table = 'm_asal_surat';
    protected $guarded = [];

    function asalSurat(){
        return $this->hasMany(SuratMasuk::class, 'asal_surat', 'id');
    }
}
