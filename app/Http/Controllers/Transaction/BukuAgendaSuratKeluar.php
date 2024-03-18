<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\EntityTujuanSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\Transaction\LogSuratKeluar;
use App\Models\Transaction\SuratKeluar;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BukuAgendaSuratKeluar extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surat = SuratKeluar::orderBy('created_at', 'DESC')
            ->with('statusSurat')
            ->with('tujuanSurat')
            ->with('createdUser')
            ->with('posisiSurat')
            ->whereHas('statusSurat', function($surat){
                $surat->where('status_surat',Helpers::getStatusSurat('206')->id);
            })
            ->whereHas('posisiSurat',function($surat){
                $surat->where('posisi_surat',Auth::user()->organization)
                ->where('asal_surat','<>',Auth::user()->organization);
            })
            ->get();

        $data = [
            'surat' => $surat,
        ];

        return view('content.surat-keluar.buku-agenda', $data);
    }

    public function buatAgenda(Request $request)
    {
        $txNo = $request->txNo;
        $user = Auth::getUser();
        $surat = SuratKeluar::find($txNo);
        $org = Organization::where('id', $surat->konseptor)->first();

        $noAgenda = Helpers::generateNoAgenda($org->suffix_agenda,'keluar');
        $status_surat = Helpers::getStatusSurat('206')->id;
        $posisi = $surat->konseptorSurat->organization;
        $bulan_romawi = Helpers::getBulanRomawi(Carbon::now()->format('m'));
        $nomor_surat = $request->nomor_surat; //input data
        
        $file = '';
        if ($request->hasFile('file_surat')) {
            $documentFile = $request->file('file_surat');
            $filename = $documentFile->getClientOriginalName();
            $path = time().'_surat_keluar.pdf';
            $documentFile->move(public_path().'/document/surat-keluar/', $path);
            $file = $path;
        }

        $surat->update([
            'no_surat' => $nomor_surat,
            'no_agenda' => $noAgenda,
            'status_surat' => $status_surat,
            'posisi_surat' => $posisi,
            'file_path' => $file
        ]);

        LogSuratKeluar::create([
            'tx_number' => $surat->tx_number,
            'process_date' => Carbon::now(),
            'status' => $status_surat,
            'updated_by' => $user->id,
            'posisi_surat' => $posisi,
            'konseptor' => $surat->konseptor,
            'penandatangan' => $surat->penandatangan_surat,
            'catatan' => $surat->catatan,
        ]);

        $data = [
            'surat' => $surat,
            'no_agenda' => $noAgenda,
        ];
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'OK',
            'data' => $data,
        ]);
    }

    public function getData()
    {
        $user = Auth::getUser(); 
        $dataSurat = SuratKeluar::orderBy('created_at', 'DESC')
                        ->With('statusSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->with('posisiSurat')
                        ->with('konseptorSurat')
                        ->whereHas('statusSurat', function($surat){
                            $surat->where('status_surat', Helpers::getStatusSurat('206')->id);
                        })
                        ->whereHas('posisiSurat', function($surat){
                            $user = Auth::getUser();
                            $surat->where('posisi_surat', $user->organization);
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

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$data->statusSurat->description."'>" .$data->statusSurat->name. "</span>";
                })
                ->editColumn('action', function($data){
                    return self::renderAction($data);
                })
                ->rawColumns(['no_draft_surat','posisi_surat','status','action'])
                ->make(true);
    }

    public function logAgenda()
    {
        $dataSurat = LogSuratKeluar::with('statusSurat')
            ->with('tujuanSurat')
            ->with('posisiSurat')
            ->with('suratKeluar')
            ->with('updatedBy')
            ->distinct('tx_number')
            ->whereHas('posisiSurat',function($surat){
                $user = Auth::getUser();
                $surat->orWhere('posisi_surat',$user->organization);
            })
            ->get();

        // dd($dataSurat);


        return DataTables::of($dataSurat)
                ->addIndexColumn()
                ->editColumn('no_draft_surat', function($data){
                    $status_surat = StatusSurat::find($data->suratKeluar->status_surat)->kode_status;
                    $nomor = ($status_surat == '206' || $status_surat == '207' || $status_surat == '208') ? $data->suratKeluar->no_surat : $data->tx_number;
                    return $nomor;
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

    public function renderAction($data)
    {
        $html = '
            <button class="btn btn-outline-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-eye"></span> </button>
            <button class="btn btn-outline-warning btn-sm rounded-pill px-2" onclick="getFormKirimSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Surat" > <span class="mdi mdi-email-fast-outline"></span> </button>
            ';

        return $html;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($txNo)
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

            if (!$surat){
                throw new Exception('Data Disposisi tidak ditemukan atau operator belum melakukan update disposisi', 404);
            } else {
                $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="kirimSurat(`'.$surat->tx_number.'`)">KIRIM SURAT</button>';
                $agenda_surat = LogSuratKeluar::where('status',Helpers::getStatusSurat('206')->id)->where('tx_number',$surat->tx_number)->first();
                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y H:i T'),
                    'tgl_agenda_surat' => Carbon::parse($agenda_surat->created_at)->translatedFormat('d F Y H:i T'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'catatan_surat' => $surat->catatan ?? 'TIDAK ADA',
                    'status_surat' => $status_surat->description.' oleh '.$agenda_surat->updatedBy->name,
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    // 'updated_by' => $agenda_surat->updated_by,
                    'file_surat' => '<a href="'.route('download-surat-keluar',['txNo'=> base64_encode($surat->tx_number)]).'" type="button" class="badge rounded-pill bg-label-info" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="downloadFile('.$surat->no_surat.')" title="Download File">'.$surat->no_surat.'</a>',
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
    
    public function getFormKirimSurat($txNo)
    {
        try {
            $txNo = base64_decode($txNo);
            $surat = SuratKeluar::where('tx_number', $txNo)
            ->with('statusSurat')
            ->with('tujuanSurat')
            ->with('createdUser')->first();

            $data = [
                'txNo' => $surat->tx_number,
                'nomor_surat' => $surat->no_surat,
                'nomor_agenda' => $surat->no_agenda,
            ];

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'OK',
                'data' => $data
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
