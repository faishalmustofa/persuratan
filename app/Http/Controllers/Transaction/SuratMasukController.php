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
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;
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
                        ->with('tujuanSurat')
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
                ->editColumn('action', function($data){
                    return self::renderAction($data);
                })
                ->rawColumns(['status', 'action', 'noSurat'])
                ->make(true);
    }

    public function renderAction($data)
    {
        $html = '';
        if($data->status_surat == 1 && Auth::user()->hasPermissionTo('print-blanko'))
        {
            $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionPrintBlanko(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Blanko" > <span class="mdi mdi-file-download-outline"></span> </button>';
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
                $documentFile->move(public_path().'/document/surat-masuk/', $path);
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
                'created_by' => Auth::user()->id,
                'file_path' => $file_path
            ];

            SuratMasuk::create($insertedData);

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

    public function printBlanko($txNo)
    {
        $dataSurat = SuratMasuk::where('tx_number', $txNo)->first();
        $org = Organization::find($dataSurat->tujuan_surat);
        $filePath = public_path().'/document/blanko_disposisi/'.$org->blanko_path;


        if (file_exists($filePath)){
            $template_document = new TemplateProcessor($filePath);

            // Set Isi Blanko Disposisi
            $jml_lampiran = $dataSurat->lampiran == null ? '' : $dataSurat->lampiran_type . ' ' . $dataSurat->jml_lampiran;
            $template_document->setValues(array(
                'klasifikasi' => $dataSurat->klasifikasiSurat->nama,
                'derajat' => $dataSurat->derajatSurat->nama,
                'no_agenda' => $dataSurat->no_agenda,
                'asal_surat' => $dataSurat->entity_asal_surat_detail,
                'no_surat' => $dataSurat->no_surat,
                'tgl_surat' => $dataSurat->tgl_surat,
                'perihal' => $dataSurat->perihal,
                'catatan' => $dataSurat->catatan,
                'lampiran' => $dataSurat->lampiran != null ? $dataSurat->lampiran : 'Tidak Ada Lampiran',
                'jml_lampiran' => $jml_lampiran,
            ));

            $filename = 'Blanko Disposisi - '. $dataSurat->tx_number .'.docx';
            $path = public_path().'/document/'.$filename;
            $template_document->saveAs($path);

            // Update Status Surat
            SuratMasuk::where('tx_number', $txNo)->update([
                'status_surat' => 2
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Cetak Blanko Disposisi',
                'file' => $filename,
            ]);
        } else {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'message' => 'File Blanko Tidak Ditemukan',
            ]);
        }
    }

    public function downloadBlanko($file){
        $filePath = public_path().'/document/'.$file;
        return response()->download($filePath)->deleteFileAfterSend(true);
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

    public function showPdf($txNumber)
    {
        $txNo = base64_decode($txNumber);
        $filePath = public_path().'/document/surat-masuk/'.$txNo.'.pdf';
        $filename= $txNo.".pdf";

        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="'.$filename.'"');
        header('content-Transfer-Encoding:binary');
        header('Accept-Ranges:bytes');
        @ readfile($filePath);
    }
}
