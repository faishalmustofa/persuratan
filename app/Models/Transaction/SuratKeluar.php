<?php

namespace App\Models\Transaction;

use App\Models\Master\EntityTujuanSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\Reference\JenisSurat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'surat_keluar';
    protected $guarded = [];
    public $primaryKey = 'tx_number';
    public $incrementing = false;
    public $keyType = 'string';

    function statusSurat(){
        return $this->hasOne(StatusSurat::class, 'id', 'status_surat');
    }

    function tujuanSurat(){
        return $this->hasOne(EntityTujuanSurat::class, 'id', 'entity_tujuan_surat');
    }
    
    function asalSurat(){
        return $this->hasOne(EntityTujuanSurat::class, 'id', 'entity_tujuan_surat');
    }

    function createdUser(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    
    function posisiSurat(){
        return $this->hasOne(Organization::class, 'id', 'posisi_surat');
    }

    function konseptorSurat(){
        return $this->hasOne(User::class, 'id', 'konseptor');
    }
    
    function jenisSurat(){
        return $this->hasOne(JenisSurat::class, 'id', 'jenis_surat');
    }


}
