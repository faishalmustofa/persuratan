<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\Organization;
use App\Models\Transaction\LogSuratKeluar;
use App\Models\Transaction\SuratKeluar;
use Carbon\Carbon;
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
                ->rawColumns(['no_draft_surat','status','action'])
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
    public function show(string $id)
    {
        //
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
