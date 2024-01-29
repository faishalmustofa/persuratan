<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasukRejected extends Model
{
    use HasFactory;
    protected $table = 'surat_masuk_rejected';
    protected $guarded = [];
}
