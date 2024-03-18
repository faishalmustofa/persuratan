<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\EntityTujuanSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\Transaction\LogSuratKeluar;
use App\Models\Transaction\PermintaanNoSurat;
use App\Models\Transaction\SuratKeluar;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Sudo;
use Yajra\DataTables\Facades\DataTables;

class PermintaanNoSuratController extends Controller
{
    public function tandaTanganSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->with('jenisSurat')->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $status_surat = Helpers::getStatusSurat('205')->id;
        $posisi = $dataSurat->penandatangan_surat == 1 ? 2 : $user_org->id;

        // Update Status Surat
        $dataSurat->update([
            'status_surat' => $status_surat,
            'posisi_surat' => $posisi,
        ]);

        LogSuratKeluar::create([
            'tx_number' => $dataSurat->tx_number,
            'process_date' => Carbon::now(),
            'status' => $status_surat,
            'updated_by' => $user->id,
            'posisi_surat' => $posisi,
            'konseptor' => $dataSurat->konseptor,
            'penandatangan' => $dataSurat->penandatangan_surat,
            'catatan' => $dataSurat->catatan,
        ]);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Melakukan Permintaan Nomor Surat',
            'no_tx' => $dataSurat,
        ]);
    }

    public function terimaSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $staus_surat = Helpers::getStatusSurat('203')->id;
        
        // Update Status Surat
        $dataSurat->update([
            'status_surat' => $staus_surat,
            'posisi_surat' => $user_org->id,
        ]);

        LogSuratKeluar::create([
            'tx_number' => $dataSurat->tx_number,
            'process_date' => Carbon::now(),
            'status' => $dataSurat->status_surat,
            'updated_by' => $user->id,
            'posisi_surat' => $dataSurat->posisi_surat,
            'konseptor' => $dataSurat->konseptor,
            'penandatangan' => $dataSurat->penandatangan_surat,
            'catatan' => $dataSurat->catatan,
        ]);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Surat Diterima oleh '. $user->name,
            'no_tx' => $dataSurat,
        ]);
    }

    public function mintaNoSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $posisi = $user_org->parent_id;
        $currentStatus = $dataSurat->statusSurat;
        
        if ($user->organization == $dataSurat->konseptorSurat->organization) {
            # code...
            $log_status_surat = StatusSurat::where('kode_status','209')->first();
            $log_surat = LogSuratKeluar::where('tx_number',$dataSurat->tx_number)
            ->where('status',$log_status_surat->id)
            ->where('updated_by','<>',$dataSurat->konseptor)
            ->latest('created_at')
            ->first();
            $posisi = $currentStatus->kode_status == '201' ? $user_org->parent_id : $log_surat->updatedBy->organization;
            $status_surat = $currentStatus->kode_status == '201' ? Helpers::getStatusSurat('202')->id : Helpers::getStatusSurat('211')->id;
        }  elseif ($user->organization == $posisi) {
            # code...
            $posisi = 13;
            $status_surat = Helpers::getStatusSurat('204')->id;
        } else {
            if ($user_org->id != 2) {
                $posisi = 2;
            } elseif ($user_org->id == 2) {
                $posisi = 13;
            }
            $status_surat = Helpers::getStatusSurat('204')->id;
        }
        
        // Update Status Surat
        $dataSurat->update([
            'status_surat' => $status_surat,
            'posisi_surat' => $posisi,
        ]);

        LogSuratKeluar::create([
            'tx_number' => $dataSurat->tx_number,
            'process_date' => Carbon::now(),
            'status' => $status_surat,
            'updated_by' => $user->id,
            'posisi_surat' => $posisi,
            'konseptor' => $dataSurat->konseptor,
            'penandatangan' => $dataSurat->penandatangan_surat,
            'catatan' => $dataSurat->catatan,
        ]);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Melakukan Permintaan Nomor Surat',
            'no_tx' => $dataSurat,
        ]);
    }
    
    public function tindakSurat(Request $request)
    {
        $dataSurat = SuratKeluar::find($request->txNo);
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $posisi = $dataSurat->unit_kerja;
        $status_surat = Helpers::getStatusSurat('209')->id;
        
        if ($user_org->id == 13) {
            $posisi = 2;
        }

        // Update Status Surat
        $dataSurat->update([
            'status_surat' => $status_surat,
            'posisi_surat' => $posisi,
            'catatan' => $request->catatan,
        ]);

        LogSuratKeluar::create([
            'tx_number' => $dataSurat->tx_number,
            'process_date' => Carbon::now(),
            'status' => $status_surat,
            'updated_by' => $user->id,
            'posisi_surat' => $posisi,
            'konseptor' => $dataSurat->konseptor,
            'penandatangan' => $dataSurat->penandatangan_surat,
            'catatan' => $request->catatan,
        ]);

        // PermintaanNoSurat::create([
        //     'tx_number' => $dataSurat->tx_number,
        //     'created_by' => Auth::getUser()->id,
        //     'catatan' => $dataSurat->catatan,
        //     'penandatangan' => $dataSurat->penandatangan_surat,
        // ]);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil update surat!',
            'no_tx' => $dataSurat,
        ]);
    }
    
    public function permintaanNoSurat()
    {
        $user = Auth::getUser();
        $organization = Organization::orderBy('id')->get();
        $permintaanSurat = SuratKeluar::where('penandatangan_surat', $user->organization)->get();

        $data = [
            'organization' => $organization,
            'user' => $user,
            'permintaanSurat' => $permintaanSurat
        ];

        return view('content.surat-keluar.permintaan-nomor',$data);
    }

    public function dataMintaNoSurat()
    {
        $dataSurat = SuratKeluar::orderBy('created_at', 'DESC')
                        ->With('statusSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->with('posisiSurat')
                        ->whereHas('posisiSurat',function($surat){
                            $surat->where('posisi_surat',Auth::user()->organization)
                            ->where('asal_surat','<>',Auth::user()->organization);
                        })
                        ->get();
                        

        return DataTables::of($dataSurat)
                ->addIndexColumn()
                ->editColumn('no_draft_surat', function($data){
                    return $data->tx_number;
                })
                ->editColumn('updated_at', function($data){
                    $tgl_diperbarui = Carbon::parse($data->updated_at)->translatedFormat('d F Y H:i T');
                    return $tgl_diperbarui;
                })
                ->editColumn('posisi_surat', function($data){
                    $status_surat = StatusSurat::find($data->status_surat)->kode_status;
                    $posisi = $status_surat == '208' ? $data->entity_tujuan_surat_detail :  $data->posisiSurat->leader_alias;
                    return $posisi;
                })
                ->editColumn('status', function($data){
                    $bg = '';
                    if ($data->status_surat == '1') {
                        $bg = 'bg-label-warning';
                    } else if($data->status_surat == '2') {
                        $bg = 'bg-label-success';
                    } else if($data->status_surat == '3') {
                        $bg = 'bg-label-primary';
                    } else if($data->status_surat == '4') {
                        $bg = 'bg-label-info';
                    } else {
                        $bg = 'bg-label-info';
                    }
                    // $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->latest('created_at')->first();

                    if ($data->status_surat == Helpers::getStatusSurat('209')->id) {
                        $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->where('status',$data->status_surat)->oldest('created_at')->first();
                        $desc = $log_surat->statusSurat->description." oleh ".$log_surat->updatedBy->name;
                    } elseif ($data->status_surat == Helpers::getStatusSurat('205')->id || $data->status_surat == Helpers::getStatusSurat('208')->id) {
                        $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->where('status',$data->status_surat)->latest('created_at')->first();
                        $desc = $log_surat->statusSurat->description;
                    } else {
                        $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->where('status',$data->status_surat)->latest('created_at')->first();
                        $desc = $log_surat->statusSurat->description." oleh ".$log_surat->updatedBy->name;
                    }

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$desc."'>" .$desc. "</span>";
                })
                ->editColumn('action', function($data){
                    return self::renderAction($data);
                })
                ->rawColumns(['no_draft_surat','posisi_surat','status','action'])
                ->make(true);
    }
    
    public function logPermintaanSurat()
    {
        $dataSurat = LogSuratKeluar::with('statusSurat')
            ->with('tujuanSurat')
            ->with('posisiSurat')
            ->with('suratKeluar')
            ->distinct('tx_number')
            ->whereHas('posisiSurat',function($surat){
                $user = Auth::getUser();
                $surat->where('posisi_surat',$user->organization);
            })
            ->get();


        return DataTables::of($dataSurat)
                ->addIndexColumn()
                ->editColumn('no_draft_surat', function($data){
                    return $data->tx_number;
                })
                ->editColumn('tgl_surat', function($data){
                    return $data->suratKeluar->tgl_surat;
                })
                ->editColumn('perihal', function($data){
                    return $data->suratKeluar->perihal;
                })
                ->editColumn('tujuan_surat', function($data){
                    return $data->suratKeluar->tujuanSurat->entity_name;
                })
                ->editColumn('updated_at', function($data){
                    $tgl_diperbarui = Carbon::parse($data->suratKeluar->updated_at)->translatedFormat('d F Y H:i T');
                    return $tgl_diperbarui;
                })
                ->editColumn('posisi_surat', function($data){
                    $status_surat = StatusSurat::find($data->suratKeluar->status_surat)->kode_status;
                    $posisi = $status_surat == '208' ? $data->suratKeluar->entity_tujuan_surat_detail :  $data->posisiSurat->leader_alias;
                    return $posisi;
                })
                ->editColumn('status', function($data){
                    $data = $data->suratKeluar;
                    $bg = '';
                    if ($data->status_surat == '1') {
                        $bg = 'bg-label-warning';
                    } else if($data->status_surat == '2') {
                        $bg = 'bg-label-success';
                    } else if($data->status_surat == '3') {
                        $bg = 'bg-label-primary';
                    } else if($data->status_surat == '4') {
                        $bg = 'bg-label-info';
                    } else {
                        $bg = 'bg-label-info';
                    }

                    if ($data->status_surat == Helpers::getStatusSurat('209')->id) {
                        $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->where('status',$data->status_surat)->oldest('created_at')->first();
                        $desc = $log_surat->statusSurat->description." oleh ".$log_surat->updatedBy->name;
                    } elseif ($data->status_surat == Helpers::getStatusSurat('205')->id || $data->status_surat == Helpers::getStatusSurat('208')->id) {
                        $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->where('status',$data->status_surat)->latest('created_at')->first();
                        $desc = $log_surat->statusSurat->description;
                    } else {
                        $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->where('status',$data->status_surat)->latest('created_at')->first();
                        $desc = $log_surat->statusSurat->description." oleh ".$log_surat->updatedBy->name;
                    }

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$desc."'>" .$desc. "</span>";
                })
                ->rawColumns(['no_draft_surat','tgl_surat','perihal','posisi_surat','status'])
                ->make(true);
    }
    
    public function detailPermintaanNoSurat($txNo)
    {
        try {
            $user = Auth::getUser();
            $txNo = base64_decode($txNo);
            $surat = SuratKeluar::where('tx_number', $txNo)
            ->with('statusSurat')
            ->with('tujuanSurat')
            ->with('createdUser')
            ->first();
            $entity_tujuan_surat = EntityTujuanSurat::find($surat->entity_tujuan_surat);
            $penandatangan = Organization::find($surat->penandatangan_surat);
            $konseptor = User::find($surat->konseptor);
            $status_surat = StatusSurat::find($surat->statusSurat->id);
            $log_surat = LogSuratKeluar::where('tx_number',$surat->tx_number)->latest('created_at')->first();
            $log_surat_ttd = LogSuratKeluar::where('tx_number',$surat->tx_number)->where('status',Helpers::getStatusSurat('205')->id)->latest('created_at')->first();

            if (!$surat){
                throw new Exception('Data Surat tidak ditemukan atau operator belum melakukan update surat', 404);
            } else {
                if ( ($user->organization == $surat->penandatangan_surat) || $user->organization == 13 ) {
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionTTDSurat(`'.$surat->tx_number.'`)">TTD SURAT</button>';
                } elseif ($user->organization == 2 && $surat->status_surat == Helpers::getStatusSurat('209')->id) {
                    // $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionTindakSurat(`'.$surat->tx_number.'`)">KEMBALIKAN KE KONSEPTOR</button>';
                    $btn_action = '<button type="submit" class="btn btn-outline-warning">KEMBALIKAN KE KONSEPTOR</button>';
                } else {
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionMintaNomorSurat(`'.$surat->tx_number.'`)">TERUSKAN PERMINTAAN SURAT</button>';
                }
                
                if ($log_surat_ttd) { 
                    // $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionAgendakanSurat(`'.$surat->tx_number.'`)">Beri nomor dan Agendakan</button>';
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="modalPenomoranSurat(`'.$surat->tx_number.'`)">BERI NOMOR DAN AGENDAKAN</button>';
                }
                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'status_surat' => $status_surat->description.' oleh '. $log_surat->updatedBy->name,
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    // 'btn_teruskan' => $btn_teruskan,
                    'btn_action' => $btn_action,
                    'file_surat' => '<a href="'.route('download-surat-keluar',['txNo'=> base64_encode($surat->tx_number)]).'" target="_blank" type="button" class="badge rounded-pill bg-label-info" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="downloadFile('.$surat->tx_number.')" title="Lihat Dokumen">'.$surat->tx_number.'</a>',
                ];
                $detail = [
                    'tx_number' => $surat->tx_number,
                    'catatan' => $surat->catatan,
                    'status_surat' => Helpers::getStatusSurat($status_surat->kode_status)->kode_status,
                    'user' => $user
                ];
            }

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'OK',
                'data' => [
                    'header' => $header,
                    'detail' => $detail,
                ]
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' =>  $th->getCode() != '' ? $th->getCode() : 500,
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Input Data, Harap Coba lagi!'
            ]);
        }     
    }
    
    public function detailPenomoranSurat($txNo)
    {
        try {
            $user = Auth::getUser();
            $txNo = base64_decode($txNo);
            $surat = SuratKeluar::where('tx_number', $txNo)
            ->with('statusSurat')
            ->with('tujuanSurat')
            ->with('createdUser')->first();
            $entity_tujuan_surat = EntityTujuanSurat::find($surat->entity_tujuan_surat);
            $penandatangan = Organization::find($surat->penandatangan_surat);
            $konseptor = User::find($surat->konseptor);
            $status_surat = StatusSurat::find($surat->statusSurat->id);
            $log_surat = LogSuratKeluar::where('tx_number',$surat->tx_number)->latest('created_at')->first();

            if (!$surat){
                throw new Exception('Data Surat tidak ditemukan atau operator belum melakukan update surat', 404);
            } else {

                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'status_surat' => $status_surat->description.' oleh '. $log_surat->updatedBy->name,
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    // 'btn_teruskan' => $btn_teruskan,
                    // 'btn_action' => $btn_action,
                    'file_surat' => '<a href="'.route('download-surat-keluar',['txNo'=> base64_encode($surat->tx_number)]).'" target="_blank" type="button" class="badge rounded-pill bg-label-info" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="downloadFile('.$surat->tx_number.')" title="Lihat Dokumen">'.$surat->tx_number.'</a>',
                ];
                $detail = [
                    'tx_number' => $surat->tx_number
                ];
            }

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'OK',
                'data' => [
                    'header' => $header,
                    'detail' => $detail,
                ]
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' =>  $th->getCode() != '' ? $th->getCode() : 500,
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Input Data, Harap Coba lagi!'
            ]);
        }     
    }

    public function renderAction($data)
    {
        $html = '';
        
        if ( $data->status_surat == Helpers::getStatusSurat('202')->id || 
            $data->status_surat == Helpers::getStatusSurat('204')->id || 
            $data->status_surat == Helpers::getStatusSurat('205')->id || 
            $data->status_surat == Helpers::getStatusSurat('211')->id
            ) {
            $html = '<button class="btn btn-success btn-sm rounded-pill px-2" onclick="actionTerimaSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Terima Surat" ><span class="mdi mdi-file-plus-outline"></span></button>';
        } 
        
        if ($data->status_surat == Helpers::getStatusSurat('203')->id || $data->status_surat == Helpers::getStatusSurat('209')->id) {
            $html = '<button class="btn btn-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" ><span class="mdi mdi-briefcase-eye-outline"></span></button>';
        }
        
        
        return $html;
    }
}
