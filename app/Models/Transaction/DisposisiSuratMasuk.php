<?php

namespace App\Models\Transaction;

use App\Models\Master\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposisiSuratMasuk extends Model
{
    use HasFactory;
    protected $table = 'disposisi';
    protected $guarded = [];

    function organization()
    {
        return $this->hasOne(Organization::class, 'id', 'tujuan_disposisi');
    }

    function suratMasuk()
    {
        return $this->hasOne(SuratMasuk::class, 'tx_number', 'tx_number');
    }
}
