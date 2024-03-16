<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\Reference\DerajatSurat;
use App\Models\Reference\JenisSuratMasuk;
use App\Models\Reference\KlasifikasiSurat;
use App\Models\Transaction\DisposisiSuratMasuk;
use App\Models\Transaction\PenerimaanPindahBerkasmasuk;
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
use Spatie\Permission\Contracts\Role;

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
        $data['jenis_surat'] = JenisSuratMasuk::get();
        $data['user'] = User::with('org')->find(Auth::user()->id);

        if($txNo != ''){
            $txNo = base64_decode($txNo);
            $data['suratMasuk'] = SuratMasuk::with('tujuanDisposisi')->where('tx_number', $txNo)->first();
        }

        return view('content.surat_masuk.index', $data);
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
                        ->with('createdUser');


        $loggedInOrg = User::with('org')->find(Auth::user()->id);

        if(strtolower($loggedInOrg->org->nama) == 'spri'){
            $dataSurat = $dataSurat->whereHas('tujuanSurat', function($user) use ($loggedInOrg){
                $user->where('tujuan_surat', $loggedInOrg->org->parent_id);
            })->whereIn('status_surat', ['003','100','101','102']);

        } else if (strtolower($loggedInOrg->org->nama) != 'taud') {
            $dataSurat = $dataSurat->whereHas('tujuanSurat', function($user){
                $user->Where('tujuan_surat', Auth::user()->organization);
            })->whereIn('status_surat', ['001', '002', '003']);
        }

        if(isset($request->from) && $request->from == 'bulking'){
            $dataSurat = $dataSurat->where('tujuan_surat', $request->tujuan_surat)->where('status_surat', '112');
        }

        $dataSurat = $dataSurat->get();

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
        $status = StatusSurat::where('kode_status', $data->status_surat)->first();

        $html = '';
        if(strtolower($loggedInOrg->org->nama) != 'taud' && strtolower($loggedInOrg->org->nama) != 'spri'){
            if($data->status_surat == '001')// && Auth::user()->hasPermissionTo('print-blanko'))
            {
                $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionPrintBlanko(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Blanko" > <span class="mdi mdi-file-download-outline"></span> </button>';
            } else if($data->status_surat == '003'){
                // if (Auth::user()->hasPermissionTo('kirim-disposisi')){
                    $noAgenda = base64_encode($data->no_agenda);
                    $html .= '<a href="'.url('transaction/disposisi/'.$noAgenda).'" class="btn btn-info btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </a>';
                    // $html .= '<button class="btn btn-info btn-sm rounded-pill" onclick="kirimDisposisi(`'.$data->tx_number.'`, `'.$data->no_surat.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </button>';
                // }
                $html .= '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
            } else if ($data->status_surat == '004') {
                $disposisi = DisposisiSuratMasuk::where('tx_number', $data->tx_number)->count();

                if($disposisi > 0){
                    // berkas yang di disposisi
                    $html = '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
                } else {
                    // Berkas langsung
                    $html = '<button class="btn btn-warning btn-sm rounded-pill" onclick="terimaBerkas(`'.$data->tx_number.'`, `'.$status->name.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Terima Berkas" > <span class="mdi mdi-file-document-check"></span> </button>';
                }
            } else if($data->status_surat == '002'){
                $html = '<button class="btn btn-success btn-sm rounded-pill" onclick="updateDisposisi(`'.$data->tx_number.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Disposisi"> <span class="mdi mdi-note-edit-outline"></span> </button>';
            }
        }  else if(strtolower($loggedInOrg->org->nama) == 'taud'){
            if($data->status_surat == '111') {
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
            } else if($data->status_surat == '112'){
                // ACTION PINDAH BERKAS TAUD KE SPRI
                $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="pindahBerkas(`'.$data->tx_number.'`, `'.$status->name.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Pindah Berkas ke SPRI" > <span class="mdi mdi-file-move"></span> </button>';
            } else if($data->status_surat == '110' && Auth::user()->hasPermissionTo('kirim-disposisi')){
                // ACTION KIRIM SURAT SETELAH DISPOSISI
                $disposisi = DisposisiSuratMasuk::where('tx_number', $data->tx_number)->get();

                if(count($disposisi) != 0){
                    $noAgenda = base64_encode($data->no_agenda);
                    $html = '<a href="'.url('transaction/disposisi/'.$noAgenda).'" class="btn btn-info btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </a>';
                }
            } else if($data->status_surat == '999'){
                $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="viewDetailRejected(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail revisi berkas" > <span class="mdi mdi-folder-eye"></span> </button>';
            }
        } else if(strtolower($loggedInOrg->org->nama) == 'spri'){
            if($data->status_surat == '100'){
                $html = '<button class="btn btn-warning btn-sm rounded-pill" onclick="terimaBerkas(`'.$data->tx_number.'`, `'.$data->no_surat.'`, `'.$data->no_agenda.'`, `'.$status->name.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Terima Berkas" > <span class="mdi mdi-file-document-check"></span> </button>';
                $html .= '<button class="btn btn-danger btn-sm rounded-pill mt-2" onclick="revisiBerkas(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Revisi Berkas" > <span class="mdi mdi-book-cancel"></span> </button>';
            } else if($data->status_surat == '102'){
                $html = '<button class="btn btn-success btn-sm rounded-pill" onclick="updateDisposisi(`'.$data->tx_number.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Disposisi"> <span class="mdi mdi-note-edit-outline"></span> </button>';
            } else if($data->status_surat == '003') {
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

            $statusNext = '111';
            $loggedInOrg = User::with('org')->find(Auth::user()->id);

            if(strtolower($org->nama) == 'kadiv propam' && strtolower($loggedInOrg->org->nama) == 'taud'){
                $statusNext = '111';
            } else {
                $statusNext = '001';
            }

            $insertedData = [
                'tx_number' => $txNumber,
                'no_agenda' => Str::upper($noAgenda),
                'no_surat' => Str::upper($request->nomor_surat),
                'tgl_surat' => Carbon::parseFromLocale($request->tanggal_surat, 'id')->format('Y-m-d'),
                'asal_surat' => $asalSurat->id,
                'tujuan_surat' => (int)$request->tujuan_surat,
                'perihal' => Str::upper($request->perihal),
                'tgl_diterima' => Carbon::parseFromLocale($request->tanggal_diterima, 'id')->format('Y-m-d H:i:s'),
                'lampiran' => Str::upper($request->judul_lampiran),
                'tembusan' => Str::upper($request->tembusan),
                'status_surat' => $statusNext,
                'entity_asal_surat' => $entityAsalSurat->id,
                'entity_asal_surat_detail' => Str::upper($request->entity_asal_surat_detail),
                'lampiran_type' => $request->lampiran_type != null ? Str::upper($request->lampiran_type) : null,
                'jml_lampiran' => $request->jumlah_lampiran,
                'catatan' => Str::upper($request->catatan),
                'klasifikasi' => (int)$request->klasifikasi,
                'derajat' => (int)$request->derajat,
                'created_by' => Auth::user()->id,
                'file_path' => $file_path,
                'no_surat_asal' => Str::upper($request->nomor_surat_asal),
                'jenis_surat' => (int)$request->jenis_surat
            ];

            // if($request->nomor_surat_asal != null){
            //     SuratMasuk::where('no_surat', $request->nomor_surat_asal)->update(['status_surat' => '001']);
            // }

            SuratMasuk::create($insertedData);

            $logData = [
                'txNumber' => $txNumber,
                'status' => 'Input Data Surat Masuk dengan nomor surat '.$request->no_surat,
                'user' => Auth::user()->name,
            ];

            $log = (new LogSuratMasukController)->store($logData);
            $statusLog = $log->getData()->status->code;
            $msgLog = isset($log->getData()->detail) ? $log->getData()->detail : 'Gagal insert data Log Surat Masuk' ;
            if($statusLog != 200){
                throw new Exception($msgLog, $statusLog);
            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Buat Agenda Surat',
                'txNumber' => $txNumber,
                'noAgenda' => $noAgenda,
                'printBlanko' => $org->need_disposisi,
                'notif' => [
                    'send' => true,
                    'act' => 'new_surat',
                    'user_except' => Auth::user()->id
                ]
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
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Simpan Data, Harap Coba lagi!'
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
                'klasifikasi' => strtoupper($dataSurat->klasifikasiSurat->nama),
                'derajat' => strtoupper($dataSurat->derajatSurat->nama),
                'no_agenda' => $dataSurat->no_agenda,
                'asal_surat' => strtoupper($dataSurat->entity_asal_surat_detail),
                'no_surat' => strtoupper($dataSurat->no_surat),
                'tgl_surat' => Carbon::parse($dataSurat->tgl_surat)->translatedFormat('d F Y'),
                'tgl_diterima' => Carbon::parse($dataSurat->tgl_diterima)->translatedFormat('d F Y'),
                'perihal' => strtoupper($dataSurat->perihal),
                'catatan' => strtoupper($dataSurat->catatan),
                'lampiran' => $dataSurat->lampiran != null ? $dataSurat->lampiran : 'TANPA LAMPIRAN',
                'jml_lampiran' => $jml_lampiran,
                'user' => Auth::user()->name,
                'tgl_cetak' => Carbon::now()->translatedFormat('m/d/Y H:i:s'),
            ));

            $filename = 'Blanko Disposisi - '. $dataSurat->tx_number;
            $path = public_path().'/document/'.$filename .'.docx';

            if(file_exists($path)){
                unlink($path);
            }

            $template_document->saveAs($path);

            $pdfPath = public_path().'/document/';
            $convert='"C:/LibreOfficePortablePrevious/App/libreoffice/program/soffice" --headless --convert-to pdf "'.$path.'" --outdir "'.$pdfPath.'"';
            // $convert='"C:/Program Files/LibreOffice/program/soffice" --headless --convert-to pdf "'.$path.'" --outdir "'.$pdfPath.'"';
            if(!exec($convert)){
                return response()->json([
                    'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Gagal Memproses file blanko disposisi, harap coba lagi',
                ]);
            }

            $pdfFile = $filename.'.pdf';
            unlink($path);

            $statusNext = 0;
            if($dataSurat->status_surat == '111' || $dataSurat->status_surat == '000'){
                $statusNext = '112';
            } else if ($dataSurat->status_surat == '001') {
                $statusNext = '002';
            }

            // Update Status Surat
            SuratMasuk::where('tx_number', $txNo)->update([
                'status_surat' => $statusNext
            ]);

            $logData = [
                'txNumber' => $txNo,
                'status' => 'Surat '.$dataSurat->no_surat.' dilakukan print blanko',
                'user' => Auth::user()->name,
            ];

            $log = (new LogSuratMasukController)->store($logData);
            $statusLog = $log->getData()->status->code;
            $msgLog = isset($log->getData()->detail) ? $log->getData()->detail : 'Gagal insert data Log Surat Masuk' ;

            if($statusLog != 200){
                return response()->json([
                    'status' => $statusLog,
                    'message' => $msgLog,
                ]);
            }

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
        DB::beginTransaction();
        try {
            $suratMasuk = SuratMasuk::where('tx_number', $txNo);
            $loggedInOrg = User::with('org')->find(Auth::user()->id);
            if($suratMasuk->first()->status_surat == '112' && strtolower($loggedInOrg->org->nama) == 'taud'){
                $suratMasuk->update([
                    'status_surat' => '100'
                ]);

                $logData = [
                    'txNumber' => $txNo,
                    'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan pemindahan berkas dari TAUD ke SPRI',
                    'user' => Auth::user()->name,
                ];

                $log = (new LogSuratMasukController)->store($logData);
                $statusLog = $log->getData()->status->code;
                if($statusLog != 200){
                    throw new Exception('Gagal input logo surat', $statusLog);
                }

            } else if($suratMasuk->first()->status_surat == '003' && strtolower($loggedInOrg->org->nama) == 'spri') {
                $suratMasuk->update([
                    'status_surat' => '110'
                ]);

                $logData = [
                    'txNumber' => $txNo,
                    'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan pemindahan berkas dari SPRI ke TAUD',
                    'user' => Auth::user()->name,
                ];

                $log = (new LogSuratMasukController)->store($logData);
                $statusLog = $log->getData()->status->code;
                if($statusLog != 200){
                    throw new Exception('Gagal input logo surat', $statusLog);
                }

            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Melakukan Perpindahan Berkas',
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
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Kirim Berkas, Harap Coba lagi!'
            ]);
        }
    }

    public function terimaBerkas(Request $request)
    {
        DB::beginTransaction();
        try {
            $txNo = $request->tx_number;

            $suratMasuk = SuratMasuk::where('tx_number', $txNo);
            $loggedInOrg = User::with('org')->find(Auth::user()->id);

            if($suratMasuk->first()->status_surat == '112' && strtolower($loggedInOrg->org->nama) == 'taud'){
                $suratMasuk->update([
                    'status_surat' => '100'
                ]);

                $logData = [
                    'txNumber' => $txNo,
                    'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan penerimaan berkas di TAUD',
                    'user' => strtoupper($request->pangkat_penerima). ' ' . strtoupper($request->nama_penerima) . ' - ' . strtoupper($request->jabatan_penerima) ,
                ];

                $log = (new LogSuratMasukController)->store($logData);
                $statusLog = $log->getData()->status->code;
                if($statusLog != 200){
                    throw new Exception('Gagal input Log Surat', $statusLog);
                }
            } else if($suratMasuk->first()->status_surat == '100' && strtolower($loggedInOrg->org->nama) == 'spri') {
                $suratMasuk->update([
                    'status_surat' => '102'
                ]);

                $logData = [
                    'txNumber' => $txNo,
                    'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan penerimaan berkas di SPRI KADIV',
                    'user' => strtoupper($request->pangkat_penerima). ' ' . strtoupper($request->nama_penerima) . ' - ' . strtoupper($request->jabatan_penerima) ,
                ];

                $log = (new LogSuratMasukController)->store($logData);
                $statusLog = $log->getData()->status->code;
                if($statusLog != 200){
                    throw new Exception('Gagal input Log Surat', $statusLog);
                }
            }

            $dataPenerimaanSurat = [
                'tx_number' => $txNo,
                'pangkat_penerima' => strtoupper($request->pangkat_penerima),
                'nama_penerima' => strtoupper($request->nama_penerima),
                'jabatan_penerima' => strtoupper($request->jabatan_penerima),
                'tgl_diterima' => Carbon::parseFromLocale($request->tgl_diterima, 'id')->format('Y-m-d H:i:s')
            ];

            PenerimaanPindahBerkasmasuk::create($dataPenerimaanSurat);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Melakukan Penerimaan Berkas',
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
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Terima Berkas, Harap Coba lagi!'
            ]);
        }
    }

    public function editTgl(Request $request)
    {
        DB::beginTransaction();
        try {
            $suratMasuk = SuratMasuk::where('tx_number', $request->tx_number);

            $suratMasuk->update([
                'tgl_diterima' => Carbon::parseFromLocale($request->tanggal_diterima, 'id')->translatedFormat('Y-m-d H:i:s')
            ]);

            $logData = [
                'txNumber' => $request->tx_number,
                'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan perubahan tanggal diterima',
                'user' => Auth::user()->name,
            ];

            $log = (new LogSuratMasukController)->store($logData);
            $statusLog = $log->getData()->status->code;
            $msgLog = $log->getData()->status->msg;

            if($statusLog != 200){
                throw new Exception($msgLog, $statusLog);
            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil update tanggal diterima surat',
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
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Merubah Tanggal, Harap Coba lagi!'
            ]);
        }
    }

    public function revisiBerkas(Request $request)
    {
        DB::beginTransaction();
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
            $suratMasuk = SuratMasuk::where('tx_number', $request->tx_number);

            $suratMasuk = $suratMasuk->update([
                'status_surat' => '999'
            ]);

            $logData = [
                'txNumber' => $request->tx_number,
                'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan perubahan tanggal diterima',
                'user' => Auth::user()->name,
            ];

            $log = (new LogSuratMasukController)->store($logData);
            $statusLog = $log->getData()->status->code;

            if($statusLog != 200){
                throw new Exception('Gagal Submit Log Surat Masuk', $statusLog);
            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil submit revisi berkas',
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
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Gagal Submit Revisi Berkas'
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

    public function pindahBerkasBundling(Request $request)
    {
        DB::beginTransaction();
        try {
            for ($i=0; $i < count($request->txNumber); $i++) {
                $suratMasuk = SuratMasuk::where('tx_number', $request->txNumber[$i]);
                $loggedInOrg = User::with('org')->find(Auth::user()->id);

                if($suratMasuk->first()->status_surat == '112' && strtolower($loggedInOrg->org->nama) == 'taud'){
                    $suratMasuk->update([
                        'status_surat' => '100'
                    ]);

                    $logData = [
                        'txNumber' => $request->txNumber[$i],
                        'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan perpindahan berkas dari TAUD ke SPRI',
                        'user' => Auth::user()->name,
                    ];

                    $log = (new LogSuratMasukController)->store($logData);
                    $statusLog = $log->getData()->status->code;

                    if($statusLog != 200){
                        throw new Exception('Gagal submit log surat masuk', $statusLog);
                    }
                } else if($suratMasuk->first()->status_surat == '003' && strtolower($loggedInOrg->org->nama) == 'spri') {
                    $suratMasuk->update([
                        'status_surat' => '110'
                    ]);

                    $logData = [
                        'txNumber' => $request->tx_number,
                        'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan perpindahan berkas dari SPRI ke TAUD',
                        'user' => Auth::user()->name,
                    ];

                    $log = (new LogSuratMasukController)->store($logData);
                    $statusLog = $log->getData()->status->code;

                    if($statusLog != 200){
                        throw new Exception('Gagal submit log surat masuk', $statusLog);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Melakukan Perpindahan Berkas',
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
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Kirim Berkas, Harap Coba lagi!'
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
