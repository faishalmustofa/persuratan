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

class PengirimanSuratKeluarController extends Controller
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
                $surat->where('status_surat',20);
            })
            // ->whereHas('posisiSurat',function($surat){
            //     $surat->where('posisi_surat',Auth::user()->organization)
            //     ->where('asal_surat','<>',Auth::user()->organization);
            // })
            ->get();

        $data = [
            'surat' => $surat,
        ];

        return view('content.surat-keluar.pengiriman-surat', $data);
    }

    public function getData()
    {
        $dataSurat = SuratKeluar::orderBy('created_at', 'DESC')
                        ->With('statusSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->with('posisiSurat')
                        ->whereHas('statusSurat', function($surat){
                            $surat->where('status_surat', 18);
                        })
                        ->whereHas('statusSurat', function($surat){
                            $user = Auth::getUser();
                            $surat->where('posisi_surat', $user->organization);
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

    public function renderAction($data)
    {
        if ($data->status_surat != 19) {
            $html = '
                <button class="btn btn-outline-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-eye"></span> </button>
                <button class="btn btn-outline-success btn-sm rounded-pill px-2" onclick="penerimaanSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Buat Laporan Penerimaan Surat" > <span class="mdi mdi-playlist-plus"></span> </button>
                <button class="btn btn-outline-primary btn-sm rounded-pill px-2" onclick="showTimeline()" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat timeline surat" > <span class="mdi mdi-timeline-text-outline"></span> </button>
                ';
        } else {
            $html = '
                <button class="btn btn-outline-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-eye"></span> </button>
                <button class="btn btn-outline-primary btn-sm rounded-pill px-2" onclick="showTimeline()" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat timeline surat" > <span class="mdi mdi-timeline-text-outline"></span> </button>
                ';
        }
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
        $user = Auth::getUser();
        $surat = SuratKeluar::find($request->txNo);
        $org = Organization::where('id', $surat->konseptor)->first();

        $noAgenda = Helpers::generateNoAgenda($org->suffix_agenda,'keluar');
        $status_surat = 18;
        $posisi = $user->organization;

        $surat->update([
            'no_agenda' => $noAgenda,
            'status_surat' => $status_surat
        ]);

        LogSuratKeluar::create([
            'tx_number' => $surat->tx_number,
            'process_date' => Carbon::now(),
            'status' => $status_surat,
            'updated_by' => $user->id,
            'posisi_surat' => $posisi,
            'catatan' => $surat->catatan,
        ]);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'OK',
            // 'data' => $data,
        ]);
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
                
                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'catatan_surat' => $surat->catatan ?? 'TIDAK ADA',
                    'status_surat' => $status_surat->description.' oleh ',
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    'file_surat' => '<a href="'.route('download-surat-keluar',['txNo'=> base64_encode($surat->tx_number)]).'" type="button" class="badge rounded-pill bg-label-info" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="downloadFile('.$surat->tx_number.')" title="Download File">'.$surat->tx_number.'</a>',
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