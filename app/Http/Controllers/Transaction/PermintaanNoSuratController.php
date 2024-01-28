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
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();

        if ($dataSurat->penandatangan_surat == 1) { 
            $posisi = 2;
            $status_surat = 17;
        } else {
            $posisi = $user->organization;
            $status_surat = 17;
        }

        $nomor_surat = 'belum dibuat'; //input data
        
        // Update Status Surat
        $dataSurat->update([
            'no_surat' => $nomor_surat,
            'status_surat' => $status_surat,
            'posisi_surat' => $posisi,
        ]);

        LogSuratKeluar::create([
            'tx_number' => $dataSurat->tx_number,
            'process_date' => Carbon::now(),
            'status' => $status_surat,
            'updated_by' => $user->id,
            'posisi_surat' => $posisi,
            'catatan' => $dataSurat->catatan,
        ]);

        // PermintaanNoSurat::create([
        //     'tx_number' => $dataSurat->tx_number,
        //     'created_by' => Auth::getUser()->id,
        //     'catatan' => $dataSurat->catatan,
        //     'penandatangan' => $dataSurat->penandatangan_surat,
        // ]);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Melakukan Permintaan Nomor Surat',
            'no_tx' => $dataSurat,
        ]);
    }

    public function mintaNoSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $posisi = $user_org->parent_id;

        if (($posisi == 1) && ($user_org->id != 2)) {
            $posisi = 2;
        } elseif (($posisi == 1) && ($user_org->id == 2)) {
            $posisi = 13;
        }

        if ($dataSurat->penandatangan_surat != $user->organization) {
            $status_surat = 14;
        } else {
            $status_surat = 15;
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
            'catatan' => $dataSurat->catatan,
        ]);

        // PermintaanNoSurat::create([
        //     'tx_number' => $dataSurat->tx_number,
        //     'created_by' => Auth::getUser()->id,
        //     'catatan' => $dataSurat->catatan,
        //     'penandatangan' => $dataSurat->penandatangan_surat,
        // ]);

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
        
        $status_surat = 16;
        
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
            'konseptor' => $user,
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
                        ->whereHas('statusSurat', function($surat){
                            $surat->where('status_surat','<',19);
                        })
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

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$data->statusSurat->description."'>" .$data->statusSurat->name. "</span>";
                })
                ->editColumn('action', function($data){
                    return self::renderAction($data);
                })
                ->rawColumns(['no_draft_surat','status','action'])
                ->make(true);
    }
    
    public function logPermintaanSurat()
    {
        $user = Auth::getUser();
        $dataSurat = LogSuratKeluar::where('posisi_surat',$user->organization)
            ->distinct('tx_number')
            ->with('statusSurat')
            ->with('suratKeluar')
            ->get();

        return DataTables::of($dataSurat)
                ->addIndexColumn()
                ->editColumn('no_draft_surat', function($data){
                    return $data->tx_number;
                })
                ->editColumn('tujuan_surat', function($data){
                    $tujuan_surat = $data->suratKeluar->tujuanSurat;
                    return $tujuan_surat->entity_name;
                })
                ->editColumn('status', function($data){
                    $surat = $data->suratKeluar;
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

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$surat->statusSurat->description."'>" .$surat->statusSurat->name. "</span>";
                })
                ->rawColumns(['no_draft_surat','tujuan_surat','status'])
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
            ->with('createdUser')->first();
            $entity_tujuan_surat = EntityTujuanSurat::find($surat->entity_tujuan_surat);
            $penandatangan = Organization::find($surat->penandatangan_surat);
            $konseptor = User::find($surat->konseptor);
            $status_surat = StatusSurat::find($surat->statusSurat->id);

            // dd($surat->penandatangan_surat);

            if (!$surat){
                throw new Exception('Data Disposisi tidak ditemukan atau operator belum melakukan update disposisi', 404);
            } else {
                if ( ($user->organization == $surat->penandatangan_surat) || $user->organization == 13 ) {
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionTTDSurat(`'.$surat->tx_number.'`)">TTD SURAT</button>';
                } else {
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionMintaNomorSurat(`'.$surat->tx_number.'`)">Teruskan</button>';
                }
                
                if ($surat->statusSurat->id == 17) { 
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionAgendakanSurat(`'.$surat->tx_number.'`)">Agendakan</button>';
                }
                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'status_surat' => $status_surat->description.' oleh ',
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    // 'btn_teruskan' => $btn_teruskan,
                    'btn_action' => $btn_action,
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
        if ( ((int)$data->penandatangan_surat == 13) || ((int)$data->penandatangan_surat == 1) ) {
            $button_cek = true;
        } else {
            $button_cek = false;
        }
        
        if ($button_cek) {
            // $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-briefcase-eye-outline"></span> </button>
            // <button class="btn btn-warning btn-sm rounded-pill" onclick="actionTindakSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Tindak Lanjut" > <span class="mdi mdi-file-download-outline"></span> </button>';
            $html = '<button class="btn btn-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-briefcase-eye-outline"></span> </button>';
        } else {
            $html = '<button class="btn btn-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" ><span class="mdi mdi-briefcase-eye-outline"></span></button>';
        }

        return $html;
    }
}
