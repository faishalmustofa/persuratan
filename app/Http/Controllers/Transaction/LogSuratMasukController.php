<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction\LogSuratMasuk;
use App\Models\Transaction\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LogSuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.log-surat-masuk.index');
    }

    public function data(Request $request)
    {
        $dataSurat = SuratMasuk::orderBy('tgl_diterima', 'DESC')
                        ->with('asalSurat')
                        ->with('entityAsalSurat')
                        ->With('statusSurat')
                        ->with('klasifikasiSurat')
                        ->with('derajatSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->get();


        return DataTables::of($dataSurat)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    $bg = '';
                    $statusSurat = (int)$data->status_surat;
                    if ($statusSurat%2 == 0) {
                        $bg = 'bg-label-primary';
                    } else {
                        $bg = 'bg-label-info';
                    }

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$data->statusSurat->description."'>" .$data->statusSurat->name. "</span>";
                })
                ->editColumn('noSurat', function($data){
                    $txNo = base64_encode($data->tx_number);
                    return "<a href='".route('showPDF',$txNo)."' target='_blank' class='badge rounded-pill bg-label-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Lihat Berkas Surat'>" .$data->no_surat. "</a>";
                })
                ->editColumn('action', function($data){
                    return self::renderAction($data);
                })
                ->editColumn('tgl_surat', function($data) {
                    $tgl = Carbon::parse($data->tgl_surat)->translatedFormat('d F Y');
                    return $tgl;
                })
                ->editColumn('tgl_diterima', function($data) {
                    $tgl = Carbon::parse($data->tgl_diterima)->translatedFormat('d F Y');
                    $loggedInOrg = User::with('org')->find(Auth::user()->id);
                    if(strtolower($loggedInOrg->org->nama) == 'spri'){
                        if($data->status_surat == '100' || $data->status_surat == '102'){
                            return $tgl.'<button class="btn btn-warning btn-sm rounded-pill ms-2" onclick="editTglDiterima(`'.$data->tx_number.'`, `'.$data->tgl_diterima.'`)"> <span class="mdi mdi-square-edit-outline"></span> </button>';
                        } else {
                            return $tgl;
                        }
                    } else {
                        return $tgl;
                    }
                })
                ->editColumn('surat_dari', function($data){
                    return "<span> ". $data->entityAsalSurat->entity_name ." (" . $data->entity_asal_surat_detail . ")  </span>";
                })
                ->rawColumns(['status', 'action', 'noSurat', 'tgl_surat', 'tgl_diterima', 'surat_dari'])
                ->make(true);
    }

    public function renderAction($data)
    {
        $txNo = base64_encode($data->tx_number);
        $html = '<a href="'.url('transaction/log-surat-masuk/show/'.$txNo).'" class="btn btn-info btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Timeline"> <span class="mdi mdi-eye"></span> </a>';

        return $html;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($request)
    {
        DB::beginTransaction();
        try {
            $insertLog = [
                'tx_number' => $request['txNumber'],
                'process_date' => Carbon::now(),
                'status' => $request['status'],
                'process_user' => $request['user']
            ];

            LogSuratMasuk::create($insertLog);
            DB::commit();
            return response()->json([
                'status' => [
                    'code' => 200,
                    'msg' => 'Success Processing Data',
                ],
                'data' => null,
            ], 200);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => [
                    'code' => 500,
                    'msg' => 'Error'
                ],
                'detail' => $th,
                'kasus' => null,
                'document_data' => null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $txNo = base64_decode($id);
        $data['suratMasuk'] = SuratMasuk::where('tx_number', $txNo)
                                ->with('tujuanSurat')
                                ->first();
        $data['timeline'] = LogSuratMasuk::where('tx_number', $txNo)->get();

        return view('content.log-surat-masuk.timeline', $data);
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
