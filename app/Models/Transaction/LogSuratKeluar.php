<?php

namespace App\Models\Transaction;

use App\Models\Master\EntityTujuanSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'log_surat_keluar';
    protected $guarded = [];

    function statusSurat(){
        return $this->hasOne(StatusSurat::class, 'id', 'status');
    }
    
    function suratKeluar(){
        return $this->hasOne(SuratKeluar::class, 'tx_number', 'tx_number');
    }
    
    function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    function posisiSurat(){
        return $this->hasOne(Organization::class, 'id', 'posisi_surat');
    }

    function tujuanSurat(){
        return $this->hasOne(EntityTujuanSurat::class, 'id', 'entity_tujuan_surat');
    }
}
