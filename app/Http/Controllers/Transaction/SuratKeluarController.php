<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Reference\DerajatSurat;
use App\Models\Reference\JenisSurat;
use App\Models\Reference\KlasifikasiSurat;
use App\Models\Transaction\SuratKeluar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($txNo = '')
    {
        $data['derajat'] = DerajatSurat::orderBy('id')->get();
        $data['klasifikasi'] = KlasifikasiSurat::orderBy('id')->get();
        $data['asalSurat'] = AsalSurat::all();
        $data['entityAsal'] = EntityAsalSurat::get();
        $data['organization'] = Organization::orderBy('id')->get();
        $data['jenis_surat'] = JenisSurat::orderBy('id')->get();

        if($txNo != ''){
            $txNo = base64_decode($txNo);
            $data['suratMasuk'] = SuratKeluar::where('tx_number', $txNo)->first();
        }

        return view('content.surat-keluar.index', $data);
    }

    public function data()
    {
        $dataSurat = SuratKeluar::orderBy('created_at', 'DESC')
                        ->With('statusSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->whereHas('createdUser', function($user){
                            $user->where('organization', Auth::user()->organization);
                        })
                        ->get();

        return DataTables::of($dataSurat)
                ->addIndexColumn()
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
                    }

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$data->statusSurat->description."'>" .$data->statusSurat->name. "</span>";
                })
                ->editColumn('noSurat', function($data){
                    $txNo = base64_encode($data->tx_number);
                    return "<a href='".route('showPDF',$txNo)."' target='_blank' class='badge rounded-pill bg-label-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Lihat Berkas Surat'>" .$data->no_surat. "</a>";
                })
                // ->editColumn('action', function($data){
                //     return self::renderAction($data);
                // })
                ->rawColumns(['status', 'noSurat'])
                ->make(true);
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
        DB::beginTransaction();
        try {
            $org = Organization::where('id', $request->tujuan_surat)->first();

            $txNumber = Helpers::generateTxNumber();
            $noAgenda = Helpers::generateNoAgenda($org->suffix_agenda,'keluar');

            $file = '';
            if ($request->hasFile('file_surat')) {
                $documentFile = $request->file('file_surat');
                $filename = $documentFile->getClientOriginalName();
                $path = $txNumber.'.pdf';
                $documentFile->move(public_path().'/document/surat-keluar/', $path);
                $file = $path;
            }

            if ($file != '') {
                $file_path = $file;
            } else {
                $file_path = null;
            }

            $insertedData = [
                'tx_number' => $txNumber,
                'no_agenda' => $noAgenda,
                'jenis_surat' => $request->jenis_surat,
                'no_surat' => $request->nomor_surat,
                'tgl_surat' => $request->tanggal_surat,
                'tujuan_surat' => $request->tujuan_surat,
                'perihal' => $request->perihal,
                'lampiran' => $request->judul_lampiran,
                'status_surat' => 1,
                'lampiran_type' => $request->lampiran_type,
                'jml_lampiran' => $request->jumlah_lampiran,
                'konseptor' => $request->konseptor,
                'unit_kerja' => $request->unit_kerja_pemohon,
                'penandatangan_surat' => $request->penandatangan_surat,
                'catatan' => $request->catatan,
                'created_by' => Auth::user()->id,
                'file_path' => $file_path
            ];

            SuratKeluar::create($insertedData);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Buat Agenda Surat',
                'txNumber' => $txNumber,
                'noAgenda' => $noAgenda,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan Pada Sistem, Harap Coba Lagi',
                'detail' => $e
            ]);
        }
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
