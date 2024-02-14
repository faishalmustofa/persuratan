<?php

namespace App\Models\Master;

use App\Models\Transaction\SuratKeluar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanSurat extends Model
{
    use HasFactory;
    protected $table = 'm_tujuan_surat';
    protected $guarded = [];

    function tujuanSurat(){
        return $this->hasMany(SuratKeluar::class, 'tujuan_surat', 'id');
    }
}
