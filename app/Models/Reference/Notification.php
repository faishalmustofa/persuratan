<?php

namespace App\Models\Reference;

use App\Models\Transaction\SuratMasuk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notification';
    protected $guarded = [];
    public $timestamps = false;

    function suratMasuk()
    {
        return $this->hasOne(SuratMasuk::class, 'tx_number', 'tx_number');
    }
}
