<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'log_surat_keluar';
    protected $guarded = [];
}
