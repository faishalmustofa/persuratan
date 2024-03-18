<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
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

class LaporanPenerimaanSuratController extends Controller
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
                $surat->where('status_surat',Helpers::getStatusSurat('209')->id);
            })
            // ->whereHas('posisiSurat',function($surat){
            //     $surat->where('posisi_surat',Auth::user()->organization)
            //     ->where('asal_surat','<>',Auth::user()->organization);
            // })
            ->get();

        $data = [
            'surat' => $surat,
        ];

        return view('content.surat-keluar.laporan-penerimaan', $data);
    }

    public function getData()
    {
        $dataSurat = SuratKeluar::orderBy('created_at', 'DESC')
                        ->With('statusSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->with('posisiSurat')
                        ->whereHas('statusSurat', function($surat){
                            $user = Auth::getUser();
                            $surat->where('status_surat', Helpers::getStatusSurat('208')->id);
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

    public function renderAction($data)
    {
        $html = '
                <button class="btn btn-outline-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-eye"></span> </button>
                <button class="btn btn-outline-primary btn-sm rounded-pill px-2" onclick="showTimeline()" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat timeline surat" > <span class="mdi mdi-timeline-text-outline"></span> </button>
                ';
        return $html;
    }
    
    public function buatPenerimaansurat($txNo)
    {
        $txNo = base64_decode($txNo);
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();

        $status_surat = Helpers::getStatusSurat('208')->id;
        $posisi = $dataSurat->posisi_surat;
        
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
            'message' => 'Berhasil Membuat Laporan Penerimaan Surat',
            'no_tx' => $dataSurat,
        ]);
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
                $log_surat = LogSuratKeluar::where('status',Helpers::getStatusSurat('208')->id)->where('tx_number',$surat->tx_number)->first();
                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y H:i T'),
                    'tgl_penerimaan_surat' => Carbon::parse($log_surat->created_at)->translatedFormat('d F Y H:i T'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'catatan_surat' => $surat->catatan ?? 'TIDAK ADA',
                    'status_surat' => $status_surat->description.' oleh '.$log_surat->updatedBy->name,
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    'file_surat' => '<a href="'.route('download-surat-keluar',['txNo'=> base64_encode($surat->tx_number)]).'" target="_blank" type="button" class="badge rounded-pill bg-label-info" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="downloadFile('.$surat->no_surat.')" title="Download File">'.$surat->no_surat.'</a>',
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
