<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Reference\DerajatSurat;
use App\Models\Reference\KlasifikasiSurat;
use App\Models\Transaction\SuratMasuk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['derajat'] = DerajatSurat::orderBy('id')->get();
        $data['klasifikasi'] = KlasifikasiSurat::orderBy('id')->get();
        $data['asalSurat'] = AsalSurat::all();
        $data['entityAsal'] = EntityAsalSurat::get();
        $data['organization'] = Organization::orderBy('id')->get();

        return view('content.surat_masuk.index', $data);
    }

    public function data()
    {
        $dataSurat = SuratMasuk::orderBy('tgl_diterima', 'DESC')
                        ->with('asalSurat')
                        ->with('entityAsalSurat')
                        ->With('statusSurat')
                        ->with('klasifikasiSurat')
                        ->with('derajatSurat')
                        ->get();

        return DataTables::of($dataSurat)->make(true);
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
            $entityAsalSurat = EntityAsalSurat::find($request->asal_surat);
            $asalSurat = AsalSurat::find($entityAsalSurat->asal_surat_id);
            $txNumber = Helpers::generateTxNumber();
            $noAgenda = Helpers::generateNoAgenda();

            $file = '';
            if ($request->hasFile('file_surat')) {
                $documentFile = $request->file('file_surat');
                $filename = $documentFile->getClientOriginalName();
                $path = $txNumber.'.pdf';
                $documentFile->move(public_path('document/surat-masuk'), $path);
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
                'no_surat' => $request->nomor_surat,
                'tgl_surat' => $request->tanggal_surat,
                'asal_surat' => $asalSurat->id,
                'tujuan_surat' => $request->tujuan_surat,
                'perihal' => $request->perihal,
                'tgl_diterima' => $request->tanggal_diterima,
                'lampiran' => $request->judul_lampiran,
                'tembusan' => $request->tembusan,
                'status_surat' => 1,
                'entity_asal_surat' => $entityAsalSurat->id,
                'entity_asal_surat_detail' => $request->entity_asal_surat_detail,
                'lampiran_type' => $request->lampiran_type,
                'jml_lampiran' => $request->jumlah_lampiran,
                'catatan' => $request->catatan,
                'klasifikasi' => $request->klasifikasi,
                'derajat' => $request->derajat,
                'created_by' => 3,
                'file_path' => $file_path
            ];

            SuratMasuk::create($insertedData);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Buat Agenda Surat',
                'txNumber' => $txNumber,
                'noAgenda' => $noAgenda
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

    public function bukuAgenda()
    {
        return view('content.surat_masuk.buku-agenda');
    }

    public function disposisi()
    {
        return view('content.surat_masuk.disposisi');
    }
}
