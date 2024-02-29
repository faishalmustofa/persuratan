<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSuratMasuk extends Model
{
    use HasFactory;
    protected $table = 'log_surat_masuk';
    protected $guarded = [];
}
