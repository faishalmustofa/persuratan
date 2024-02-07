<?php

namespace App\Models\Transaction;

use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\Reference\DerajatSurat;
use App\Models\Reference\KlasifikasiSurat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SuratMasuk extends Model
{
    use HasFactory;
    protected $table = 'surat_masuk';
    protected $guarded = [];
    public $primaryKey = 'tx_number';
    public $incrementing = false;
    public $keyType = 'string';

    function asalSurat(){
        return $this->hasOne(AsalSurat::class, 'id', 'asal_surat');
    }

    function entityAsalSurat(){
        return $this->hasOne(EntityAsalSurat::class, 'id', 'entity_asal_surat');
    }

    function statusSurat(){
        return $this->hasOne(StatusSurat::class, 'kode_status', 'status_surat');
    }

    function klasifikasiSurat(){
        return $this->hasOne(KlasifikasiSurat::class, 'id', 'klasifikasi');
    }

    function derajatSurat(){
        return $this->hasOne(DerajatSurat::class, 'id', 'derajat');
    }

    function tujuanSurat(){
        return $this->hasOne(Organization::class, 'id', 'tujuan_surat');
    }

    function createdUser(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    function disposisi(){
        return $this->hasMany(DisposisiSuratMasuk::class, 'tx_number');
    }

    function tujuanDisposisi(){
        return $this->disposisi()->where('tujuan_disposisi', Auth::user()->organization);
    }
}
