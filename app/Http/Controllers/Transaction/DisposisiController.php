<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\Organization;
use App\Models\Transaction\DisposisiSuratMasuk;
use App\Models\Transaction\Pengiriman;
use App\Models\Transaction\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\DataTables;

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($noAgenda = '')
    {
        $data['noAgenda'] = $noAgenda;
        $data['organization'] = Organization::orderBy('id')->get();
        return view('content.surat_masuk.disposisi', $data);
    }

    public function getData(Request $request)
    {
        $search = '';

        $loggedInOrg = User::with('org')->find(Auth::user()->id);
        $suratMasuk = SuratMasuk::orderBy('tgl_diterima', 'asc')
                            ->with('asalSurat')
                            ->with('entityAsalSurat')
                            ->With('statusSurat')
                            ->with('klasifikasiSurat')
                            ->with('derajatSurat')
                            ->with('tujuanSurat')
                            ->with('createdUser');

        if(strtolower($loggedInOrg->org->nama) != 'spri'){
            $suratMasuk = $suratMasuk->whereHas('createdUser', function($user){
                                $user->where('organization', Auth::user()->organization);
                            });

            if ($request->nomor_agenda != null) {
                $noAgenda = base64_decode($request->nomor_agenda);
                $suratMasuk = $suratMasuk->where('no_agenda', 'like', '%' .$noAgenda. '%');
            }
        } else {
            $suratMasuk = $suratMasuk->whereIn('status_surat', ['110', '004', '999']) ;
        }

        if(isset($request->from) && $request->from == 'bulking'){
            $suratMasuk = $suratMasuk
                            ->where(function($tujuan) use ($request){
                                $tujuan->where('tujuan_surat', $request->tujuan_surat)->where('status_surat', '111');
                            })->orWhere(function($tujuan) use ($request){
                                $tujuan->whereHas('disposisi', function($disposisi) use ($request){
                                    $disposisi->where('tujuan_disposisi', $request->tujuan_surat);
                                })->where('status_surat', '110');
                            });
        }

        $suratMasuk = $suratMasuk->get();

        return DataTables::of($suratMasuk)
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
                    return $tgl;
                })
                ->editColumn('surat_dari', function($data){
                    return "<span> ". $data->entityAsalSurat->entity_name ." (" . $data->entity_asal_surat_detail . ")  </span>";
                })
                ->rawColumns(['status', 'action', 'noSurat', 'tgl_surat', 'tgl_diterima', 'surat_dari'])
                ->make(true);
    }

    public function renderAction($data)
    {
        $html = '';
        if(($data->status_surat == '001') && Auth::user()->hasPermissionTo('print-blanko')){
            $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionPrintBlanko(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Blanko" > <span class="mdi mdi-file-download-outline"></span> </button>';
        } else if($data->status_surat == '111' && $data->tujuanSurat->need_disposisi != 1) {
            $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="kirimDisposisi(`'.$data->tx_number.'`, `'.$data->no_surat.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </button>';
        } else if($data->status_surat == '110'){
            if (Auth::user()->hasPermissionTo('kirim-disposisi')){
                $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="kirimDisposisi(`'.$data->tx_number.'`, `'.$data->no_surat.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </button>';
            }
            $html .= '<button class="btn btn-danger btn-sm rounded-pill mt-2" onclick="revisiDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Revisi Disposisi" > <span class="mdi mdi-book-cancel"></span> </button>';
            $html .= '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
        } else if ($data->status_surat == '004') {
            $html = '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
        } else if($data->status_surat == '002'){
            $html = '<button class="btn btn-success btn-sm rounded-pill" onclick="updateDisposisi(`'.$data->tx_number.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Disposisi"> <span class="mdi mdi-note-edit-outline"></span> </button>';
        }

        return $html;
    }

    public function getTujuanDisposisi($txNumber)
    {
        $suratMasuk = SuratMasuk::where('tx_number', $txNumber)->first();
        $parentOrg = Organization::where('id', $suratMasuk->tujuan_surat)->first();
        $childOrg = Organization::where('parent_id', $parentOrg->id)->get();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'OK',
            'data' => $childOrg
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
        DB::beginTransaction();
        try {
            $tujuan = $request->tujuan_disposisi;
            $dataSurat = SuratMasuk::where('tx_number', $request->tx_number)
                            ->with('klasifikasiSurat')
                            ->with('derajatSurat')
                            ->first();

            if($tujuan == null){
                SuratMasuk::where('tx_number', $request->tx_number)->update([
                    'status_surat' => '005'
                ]);

                $logData = [
                    'txNumber' => $request->tx_number,
                    'status' => 'Surat '.$dataSurat->no_surat.' dilakukan pengarsipan oleh SPRI karena tidak ada tujuan disposisi yang dipilih',
                    'user' => Auth::user()->name,
                ];

                $log = (new LogSuratMasukController)->store($logData);
                $statusLog = $log->getData()->status->code;
                if($statusLog != 200){
                    throw new Exception('Gagal input Log Surat', $statusLog);
                }

                DB::commit();
                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Surat Berhasil Diarsipkan',
                ]);
            } else {
                if(in_array('2', $tujuan)){
                    SuratMasuk::where('tx_number', $request->tx_number)->update([
                        'status_surat' => '005'
                    ]);

                    $logData = [
                        'txNumber' => $request->tx_number,
                        'status' => 'Surat '.$dataSurat->no_surat.' dilakukan pengarsipan oleh SPRI karena tujuan disposisi adalah TAUD',
                        'user' => Auth::user()->name,
                    ];

                    $log = (new LogSuratMasukController)->store($logData);
                    $statusLog = $log->getData()->status->code;
                    if($statusLog != 200){
                        throw new Exception('Gagal input Log Surat', $statusLog);
                    }

                    DB::commit();
                    return response()->json([
                        'status' => JsonResponse::HTTP_OK,
                        'message' => 'Surat Berhasil Diarsipkan',
                    ]);
                } else {
                    for ($i=0; $i < count($tujuan); $i++) {
                        DisposisiSuratMasuk::create([
                            'tx_number' => $request->tx_number,
                            'no_agenda' => $dataSurat->no_agenda,
                            'tujuan_disposisi' => $tujuan[$i],
                            'isi_disposisi' => $request->isi_disposisi,
                        ]);
                    }

                    SuratMasuk::where('tx_number', $request->tx_number)->update([
                        'status_surat' => '003'
                    ]);

                    $logData = [
                        'txNumber' => $request->tx_number,
                        'status' => 'Surat '.$dataSurat->no_surat.' dilakukan update isi disposisi oleh SPRI',
                        'user' => Auth::user()->name,
                    ];

                    $log = (new LogSuratMasukController)->store($logData);
                    $statusLog = $log->getData()->status->code;
                    if($statusLog != 200){
                        throw new Exception('Gagal input Log Surat', $statusLog);
                    }

                    DB::commit();
                    return response()->json([
                        'status' => JsonResponse::HTTP_OK,
                        'message' => 'Berhasil Update Isi Disposisi',
                    ]);
                }

            }
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => [
                    'msg' => $th->getMessage() != '' ? $th->getMessage() : 'Err',
                    'code' => $th->getCode() != '' ? $th->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ],
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Update Isi Disposisi, Harap Coba lagi!'
            ]);
        }


        /** JANGAN DIHAPUS, JAGA-JAGA BUAT NANTI */
        // $jml_lampiran = $dataSurat->lampiran == null ? '' : $dataSurat->lampiran_type . ' ' . $dataSurat->jml_lampiran;
        // $template_document = new TemplateProcessor(public_path().'/document/blanko_disposisi.docx');
        // $template_document->cloneBlock('tujuan_section', count($tujuan),true,true);
        // for ($i=0; $i < count($tujuan); $i++) {
        //     $dt_tujuan = Organization::where('id', $tujuan[$i])->first();
        //     $template_document->setValues(array(
        //         "tujuan#".$i+1 => $dt_tujuan->leader_alias == 'null' ? strtoupper($dt_tujuan->nama) : strtoupper($dt_tujuan->leader_alias),
        //     ));

        //     DisposisiSuratMasuk::create([
        //         'tx_number' => $request->tx_number,
        //         'no_agenda' => $dataSurat->no_agenda,
        //         'tujuan_disposisi' => $tujuan[$i],
        //     ]);
        // }

        // $template_document->setValues(array(
        //     'klasifikasi' => $dataSurat->klasifikasiSurat->nama,
        //     'derajat' => $dataSurat->derajatSurat->nama,
        //     'no_agenda' => $dataSurat->no_agenda,
        //     'asal_surat' => $dataSurat->entity_asal_surat_detail,
        //     'no_surat' => $dataSurat->no_surat,
        //     'tgl_surat' => $dataSurat->tgl_surat,
        //     'perihal' => $dataSurat->perihal,
        //     'catatan' => $dataSurat->catatan,
        //     'lampiran' => $dataSurat->lampiran != null ? $dataSurat->lampiran : 'Tidak Ada Lampiran',
        //     'jml_lampiran' => $jml_lampiran,
        // ));

        // $filename = 'Blanko Disposisi - '. $request->tx_number .'.docx';
        // $path = public_path().'/document/disposisi/'.$filename;
        // $template_document->saveAs($path);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $id = base64_decode($id);
            $dataDisposisi = DisposisiSuratMasuk::where('tx_number', $id)->with('organization')->with('suratMasuk')->get();
            $tujuanDisposisi = [];
            $isiDisposisi = '';

            if (count($dataDisposisi) > 0){
                foreach ($dataDisposisi as $dd) {
                    // Header
                    if($dd->suratMasuk != null){
                        $header = [
                            'no_surat' => $dd->suratMasuk->no_surat,
                            'no_agenda' => $dd->suratMasuk->no_agenda,
                            'tgl_surat' => Carbon::parse($dd->suratMasuk->tgl_surat)->translatedFormat('d F Y'),
                            'tgl_diterima' => Carbon::parse($dd->suratMasuk->tgl_diterima)->translatedFormat('d F Y H:i').' WIB',
                            'tujuan_surat' => $dd->suratMasuk->tujuanSurat->nama,
                            'perihal' => $dd->suratMasuk->perihal,
                        ];
                    } else {
                        throw new Exception('Data Surat Masuk tidak ditemukan', 404);
                    }

                    // Detail
                    array_push($tujuanDisposisi, $dd->organization->nama);
                    $isiDisposisi = $dd->isi_disposisi;
                }
            } else {
                throw new Exception('Data Disposisi tidak ditemukan atau operator belum melakukan update disposisi', 404);
            }

            $detail = [
                'tujuan_disposisi' => $tujuanDisposisi,
                'isi_disposisi' => $isiDisposisi,
            ];

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'OK',
                'data' => [
                    'header' => $header,
                    'detail' => $detail
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

    public function pengirimanSurat(Request $request)
    {
        DB::beginTransaction();
        try {
            $insertedData = [
                'no_agenda' => $request->nomor_agenda,
                'jenis_pengiriman' => $request->jenis_pengiriman,
                'expedisi' => $request->expedisi,
                'no_resi' => $request->no_resi,
                'nama_pengirim' => $request->nama_pengirim,
                'tgl_kirim' => Carbon::parseFromLocale($request->tgl_kirim, 'id')->format('Y-m-d H:i'),
            ];

            $data = Pengiriman::create($insertedData);

            SuratMasuk::where('tx_number', $request->tx_number)->update([
                'status_surat' => '004'
            ]);

            $logData = [
                'txNumber' => $request->tx_number,
                'status' => 'Surat '.$request->no_surat.' dilakukan pengiriman ke tujuan disposisi',
                'user' => $request->nama_pengirim,
            ];

            $log = (new LogSuratMasukController)->store($logData);
            $statusLog = $log->getData()->status->code;
            if($statusLog != 200){
                throw new Exception('Gagal input Log Surat', $statusLog);
            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Input Data Pengiriman',
                'data' => $data
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' =>  $th->getCode() != '' ? $th->getCode() : 500,
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Input Data, Harap Coba lagi!'
            ]);
        }
    }

    public function kirimBulking(Request $request)
    {
        DB::beginTransaction();
        try {
            for ($i=0; $i < count($request->txNumber); $i++) {
                $suratMasuk = SuratMasuk::where('tx_number', $request->txNumber[$i]);
                $insertedData = [
                    'no_agenda' => $suratMasuk->first()->no_agenda,
                    'jenis_pengiriman' => $request->jenis_pengiriman,
                    'expedisi' => $request->expedisi,
                    'no_resi' => $request->no_resi,
                    'nama_pengirim' => $request->nama_pengirim,
                    'tgl_kirim' => Carbon::parseFromLocale($request->tgl_kirim, 'id')->format('Y-m-d H:i'),
                ];

                $logData = [
                    'txNumber' => $suratMasuk->first()->tx_number,
                    'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan pengiriman ke tujuan disposisi',
                    'user' => $request->nama_pengirim,
                ];

                $log = (new LogSuratMasukController)->store($logData);
                $statusLog = $log->getData()->status->code;
                if($statusLog != 200){
                    throw new Exception('Gagal input Log Surat', $statusLog);
                }

                $data = Pengiriman::create($insertedData);

                $suratMasuk->update([
                    'status_surat' => '004'
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Input Data Pengiriman',
                'data' => $data
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' =>  $th->getCode() != '' ? $th->getCode() : 500,
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Terjadi Kesalahan Saat Input Data, Harap Coba lagi!'
            ]);
        }
    }

    public function revisiDisposisi($txNo)
    {
        $txNo = base64_decode($txNo);
        DB::beginTransaction();
        try {
            $suratMasuk = SuratMasuk::where('tx_number', $txNo);
            $suratMasuk->update([
                'status_surat' => '111',
            ]);

            $logData = [
                'txNumber' => $suratMasuk->first()->tx_number,
                'status' => 'Surat '.$suratMasuk->first()->no_surat.' dilakukan revisi disposisi oleh TAUD',
                'user' => Auth::user()->name,
            ];

            $log = (new LogSuratMasukController)->store($logData);
            $statusLog = $log->getData()->status->code;
            if($statusLog != 200){
                throw new Exception('Gagal input Log Surat', $statusLog);
            }

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Revisi Disposisi',
                'txNo' => $txNo,
                'do_next' => 'print_blanko'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => [
                    'msg' => $th->getMessage() != '' ? $th->getMessage() : 'Err',
                    'code' => $th->getCode() != '' ? $th->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ],
                'data' => null,
                'err_detail' => $th,
                'message' => $th->getMessage() != '' ? $th->getMessage() : 'Gagal Submit Revisi Disposisi'
            ], 500);
        }
    }
}
