<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\Reference\DerajatSurat;
use App\Models\Reference\KlasifikasiSurat;
use App\Models\Transaction\DisposisiSuratMasuk;
use App\Models\Transaction\SuratMasuk;
use App\Models\Transaction\SuratMasukRejected;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class SuratMasukController extends Controller
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

        if($txNo != ''){
            $txNo = base64_decode($txNo);
            $data['suratMasuk'] = SuratMasuk::with('tujuanDisposisi')->where('tx_number', $txNo)->first();
        }

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
                        ->with('createdUser');


        $loggedInOrg = User::with('org')->find(Auth::user()->id);

        if(strtolower($loggedInOrg->org->nama) == 'taud' || strtolower($loggedInOrg->org->nama) == 'spri'){
            $dataSurat = $dataSurat->whereHas('tujuanSurat', function($user) use ($loggedInOrg){
                $user->where('tujuan_surat', $loggedInOrg->org->parent_id);
            });

            if(strtolower($loggedInOrg->org->nama) == 'spri'){
                $dataSurat = $dataSurat->whereIn('status_surat', [3,6,7,8]);
            }

            $dataSurat = $dataSurat->get();
        } else {
            $dataSurat = $dataSurat->whereHas('createdUser', function($user){
                $user->where('organization', Auth::user()->organization);
            })->get();
        }

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
                        if($data->status_surat == 6 || $data->status_surat == 8){
                            return $tgl.'<button class="btn btn-warning btn-sm rounded-pill ms-2" onclick="editTglDiterima(`'.$data->tx_number.'`, `'.$data->tgl_diterima.'`)"> <span class="mdi mdi-square-edit-outline"></span> </button>';
                        } else {
                            return $tgl;
                        }
                    } else {
                        return $tgl;
                    }
                    // return $tgl;// . '<button type="button" class="edit btn btn-warning btn-sm rounded-pill ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Rubah Tanggal Diterima" onclick="editTglDiterima(`'.$data->tx_number.'`, event)"><span class="mdi mdi-square-edit-outline"></span></button>';
                })
                ->editColumn('surat_dari', function($data){
                    return "<span> ". $data->entityAsalSurat->entity_name ." (" . $data->entity_asal_surat_detail . ")  </span>";
                })
                ->rawColumns(['status', 'action', 'noSurat', 'tgl_surat', 'tgl_diterima', 'surat_dari'])
                ->make(true);
    }

    public function renderAction($data)
    {
        $loggedInOrg = User::with('org')->find(Auth::user()->id);
        $status = StatusSurat::where('id', $data->status_surat)->first();

        $html = '';
        if(strtolower($loggedInOrg->org->nama) != 'taud' && strtolower($loggedInOrg->org->nama) != 'spri'){
            if($data->status_surat == 1 && Auth::user()->hasPermissionTo('print-blanko')){
                $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionPrintBlanko(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Blanko" > <span class="mdi mdi-file-download-outline"></span> </button>';
            } else if($data->status_surat == 9){
                if (Auth::user()->hasPermissionTo('kirim-disposisi')){
                    $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="kirimDisposisi(`'.$data->tx_number.'`, `'.$data->no_surat.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </button>';
                }
                $html .= '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
            } else if ($data->status_surat == 4) {
                $html = '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
            } else if($data->status_surat == 2){
                $html = '<button class="btn btn-success btn-sm rounded-pill" onclick="updateDisposisi(`'.$data->tx_number.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Disposisi"> <span class="mdi mdi-note-edit-outline"></span> </button>';
            }
        }  else if(strtolower($loggedInOrg->org->nama) == 'taud'){
            if($data->status_surat == 10) {
                if($data->tujuanSurat->need_disposisi == 1){
                    // ACTION PRINT BLANKO DISPOSISI TAUD
                    $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionPrintBlanko(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Blanko" > <span class="mdi mdi-file-download-outline"></span> </button>';
                } else {
                    // ACTION KIRIM SURAT LANGSUNG TAUD
                    $disposisi = DisposisiSuratMasuk::where('tx_number', $data->tx_number)->get();

                    if(count($disposisi) != 0){
                        $noAgenda = base64_encode($data->no_agenda);
                        $html = '<a href="'.url('transaction/disposisi/'.$noAgenda).'" class="btn btn-info btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </a>';
                    }
                }
            } else if($data->status_surat == 11){
                // ACTION PINDAH BERKAS TAUD KE SPRI
                $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="pindahBerkas(`'.$data->tx_number.'`, `'.$status->name.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Pindah Berkas ke SPRI" > <span class="mdi mdi-file-move"></span> </button>';
            } else if($data->status_surat == 9 && Auth::user()->hasPermissionTo('kirim-disposisi')){
                // ACTION KIRIM SURAT SETELAH DISPOSISI
                $disposisi = DisposisiSuratMasuk::where('tx_number', $data->tx_number)->get();

                if(count($disposisi) != 0){
                    $noAgenda = base64_encode($data->no_agenda);
                    $html = '<a href="'.url('transaction/disposisi/'.$noAgenda).'" class="btn btn-info btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </a>';
                }
            } else if($data->status_surat == 12){
                $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="viewDetailRejected(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail revisi berkas" > <span class="mdi mdi-folder-eye"></span> </button>';
            }
        } else if(strtolower($loggedInOrg->org->nama) == 'spri'){
            if($data->status_surat == 6){
                $html = '<button class="btn btn-warning btn-sm rounded-pill" onclick="terimaBerkas(`'.$data->tx_number.'`, `'.$status->name.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Terima Berkas" > <span class="mdi mdi-file-document-check"></span> </button>';
                $html .= '<button class="btn btn-danger btn-sm rounded-pill mt-2" onclick="revisiBerkas(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Revisi Berkas" > <span class="mdi mdi-book-cancel"></span> </button>';
            } else if($data->status_surat == 8){
                $html = '<button class="btn btn-success btn-sm rounded-pill" onclick="updateDisposisi(`'.$data->tx_number.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Disposisi"> <span class="mdi mdi-note-edit-outline"></span> </button>';
            } else if($data->status_surat == 3) {
                $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="pindahBerkas(`'.$data->tx_number.'`, `'.$status->name.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Pindah Berkas ke TAUD" > <span class="mdi mdi-file-move"></span> </button>';
            }
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
        $this->validate($request, [
            'nomor_surat' => 'unique:surat_masuk,no_surat'
        ],[
            'nomor_surat' => 'Nomor Surat '.$request->nomor_surat.' sudah pernah dibuatkan agenda surat masuk'
        ]);
        try {
            $entityAsalSurat = EntityAsalSurat::find($request->asal_surat);
            $asalSurat = AsalSurat::find($entityAsalSurat->asal_surat_id);
            $org = Organization::where('id', $request->tujuan_surat)->first();

            $txNumber = Helpers::generateTxNumber();
            $noAgenda = Helpers::generateNoAgenda($org->suffix_agenda);

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

            $statusNext = 0;
            $loggedInOrg = User::with('org')->find(Auth::user()->id);

            if(strtolower($org->nama) == 'kadiv propam' && strtolower($loggedInOrg->org->nama) == 'taud'){
                $statusNext = 10;
            } else {
                $statusNext = 1;
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
                'status_surat' => $statusNext,
                'entity_asal_surat' => $entityAsalSurat->id,
                'entity_asal_surat_detail' => $request->entity_asal_surat_detail,
                'lampiran_type' => $request->lampiran_type,
                'jml_lampiran' => $request->jumlah_lampiran,
                'catatan' => $request->catatan,
                'klasifikasi' => $request->klasifikasi,
                'derajat' => $request->derajat,
                'created_by' => Auth::user()->id,
                'file_path' => $file_path,
                'no_surat_asal' => $request->nomor_surat_asal
            ];

            if($request->nomor_surat_asal != null){
                SuratMasuk::where('no_surat', $request->nomor_surat_asal)->update(['status_surat' => 1]);
            }

            SuratMasuk::create($insertedData);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Buat Agenda Surat',
                'txNumber' => $txNumber,
                'noAgenda' => $noAgenda,
                'printBlanko' => $org->need_disposisi
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => [
                    'msg' => $th->getMessage() != '' ? $th->getMessage() : 'Err',
                    'code' => $th->getCode() != '' ? $th->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ],
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Hapus Data, Harap Coba lagi!'
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
                'tgl_surat' => Carbon::parse($dataSurat->tgl_surat)->translatedFormat('d F Y'),
                'tgl_diterima' => Carbon::parse($dataSurat->tgl_diterima)->translatedFormat('d F Y'),
                'perihal' => $dataSurat->perihal,
                'catatan' => $dataSurat->catatan,
                'lampiran' => $dataSurat->lampiran != null ? $dataSurat->lampiran : 'TANPA LAMPIRAN',
                'jml_lampiran' => $jml_lampiran,
            ));

            $filename = 'Blanko Disposisi - '. $dataSurat->tx_number;
            $path = public_path().'/document/'.$filename .'.docx';

            if(file_exists($path)){
                unlink($path);
            }

            $template_document->saveAs($path);

            $pdfPath = public_path().'/document/';
            $convert='"C:/Program Files/LibreOffice/program/soffice" --headless --convert-to pdf "'.$path.'" --outdir "'.$pdfPath.'"';
            if(!exec($convert)){
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Gagal Memproses file blanko disposisi, harap coba lagi',
                ]);
            }
            $pdfFile = $filename.'.pdf';
            unlink($path);

            $statusNext = 0;
            if($dataSurat->status_surat == 10){
                $statusNext = 11;
            } else if ($dataSurat->status_surat == 1) {
                $statusNext = 2;
            }

            // Update Status Surat
            SuratMasuk::where('tx_number', $txNo)->update([
                'status_surat' => $statusNext
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Cetak Blanko Disposisi',
                'file' => $filename,
                'filePath' => asset('document/'.$pdfFile)
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

    public function pindahBerkas($txNo)
    {
        $suratMasuk = SuratMasuk::where('tx_number', $txNo);
        $loggedInOrg = User::with('org')->find(Auth::user()->id);

        if($suratMasuk->first()->status_surat == 11 && strtolower($loggedInOrg->org->nama) == 'taud'){
            $suratMasuk->update([
                'status_surat' => 6
            ]);
        } else if($suratMasuk->first()->status_surat == 3 && strtolower($loggedInOrg->org->nama) == 'spri') {
            $suratMasuk->update([
                'status_surat' => 9
            ]);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Melakukan Perpindahan Berkas',
        ]);
    }

    public function terimaBerkas($txNo)
    {
        $suratMasuk = SuratMasuk::where('tx_number', $txNo);
        $loggedInOrg = User::with('org')->find(Auth::user()->id);

        if($suratMasuk->first()->status_surat == 11 && strtolower($loggedInOrg->org->nama) == 'taud'){
            $suratMasuk->update([
                'status_surat' => 6
            ]);
        } else if($suratMasuk->first()->status_surat == 6 && strtolower($loggedInOrg->org->nama) == 'spri') {
            $suratMasuk->update([
                'status_surat' => 8
            ]);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Melakukan Penerimaan Berkas',
        ]);
    }

    public function editTgl(Request $request)
    {
        try {
            $suratMasuk = SuratMasuk::where('tx_number', $request->tx_number);

            $suratMasuk->update([
                'tgl_diterima' => $request->tanggal_diterima
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil update tanggal diterima surat',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'message' => 'Gagal update tanggal diterima surat',
                'detail' => $th
            ]);
        }
    }

    public function revisiBerkas(Request $request)
    {
        try {
            // Check Directory
            $path = public_path().'/upload/images/rejected/'.$request->tx_number;
            if(!file_exists($path)){
                File::makeDirectory($path, 0777, true);
            }

            $images = [];
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $imageFile) {
                    $filename = date('y-m-d') . '_' . $imageFile->getClientOriginalName();
                    $imageFile->move($path, $filename);
                    $images[] = $filename;
                }
            }

            $data = [
                'tx_number' => $request->tx_number,
                'notes' => $request->notes,
                'rejected_by' => Auth::user()->name,
                'rejected_at' => Carbon::now(),
            ];

            if (!empty($images)) {
                $data['image'] = implode(',', $images);
            } else {
                $data['image'] = null;
            }

            $insert = SuratMasukRejected::create($data);

            // Update Status Surat
            SuratMasuk::where('tx_number', $request->tx_number)->update([
                'status_surat' => 12
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil submir revisi berkas',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'message' => 'Gagal Submit revisi berkas',
                'detail' => $th
            ]);
        }
    }

    public function viewReject($txNumber)
    {
        $txNumber = base64_decode($txNumber);
        $data['reject'] = SuratMasukRejected::where('tx_number', $txNumber)->first();
        $data['surat'] = SuratMasuk::select('no_surat', 'no_agenda')->where('tx_number', $txNumber)->first();

        $data['reject']->rejected_at = Carbon::parse($data['reject']->rejected_at)->translatedFormat('d F Y H:i').' WIB';

        $image = [];
        if($data['reject']->image != null){
            $image = explode(',', $data['reject']->image);
            for ($i=0; $i < count($image); $i++) {
                $image[$i] = asset('upload/images/rejected/'.$data['reject']->tx_number.'/'.$image[$i]);
            }
        }
        $data['reject']->image = $image;

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'OK',
            'data' => $data,
        ]);
    }

    public function cekSurat($noSurat){
        $noSurat = base64_decode($noSurat);
        $data = SuratMasuk::where('no_surat', $noSurat)->With('statusSurat')->first();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'OK',
            'data' => $data,
        ]);
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
