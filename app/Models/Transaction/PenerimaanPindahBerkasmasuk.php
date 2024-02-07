<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanPindahBerkasmasuk extends Model
{
    use HasFactory;
    protected $table = 'penerimaan_pindah_berkas_surat_masuk';
    protected $guarded = [];
}
