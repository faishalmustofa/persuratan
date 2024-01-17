<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\Organization;
use App\Models\Transaction\DisposisiSuratMasuk;
use App\Models\Transaction\Pengiriman;
use App\Models\Transaction\SuratMasuk;
use App\Models\User;
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
    public function index()
    {
        return view('content.surat_masuk.disposisi');
    }

    public function getData(Request $request)
    {
        $search = '';
        $suratMasuk = SuratMasuk::orderBy('tgl_diterima', 'asc')
                        ->with('asalSurat')
                        ->with('entityAsalSurat')
                        ->With('statusSurat')
                        ->with('klasifikasiSurat')
                        ->with('derajatSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->whereHas('createdUser', function($user){
                            $user->where('organization', Auth::user()->organization);
                        });

        if ($request->nomor_agenda != null) {
            $suratMasuk = $suratMasuk->where('no_agenda', 'like', '%' .$request->nomor_agenda. '%');
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
                ->rawColumns(['status', 'action', 'noSurat'])
                ->make(true);
    }

    public function renderAction($data)
    {
        $html = '';
        if($data->status_surat == 1 && Auth::user()->hasPermissionTo('print-blanko')){
            $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionPrintBlanko(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Blanko" > <span class="mdi mdi-file-download-outline"></span> </button>';
        } else if(($data->status_surat == 2 || $data->status_surat == 9) && Auth::user()->hasPermissionTo('update-disposisi')){
            $html = '<button class="btn btn-success btn-sm rounded-pill" onclick="updateDisposisi(`'.$data->tx_number.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Disposisi"> <span class="mdi mdi-note-edit-outline"></span> </button>';
        } else if($data->status_surat == 3){
            if (Auth::user()->hasPermissionTo('kirim-disposisi')){
                $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="kirimDisposisi(`'.$data->tx_number.'`, `'.$data->no_surat.'`, `'.$data->no_agenda.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Disposisi"> <span class="mdi mdi-file-send"></span> </button>';
            }
            $html .= '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
        } else if ($data->status_surat == 4) {
            $html = '<button class="btn btn-secondary btn-sm rounded-pill mt-2" onclick="detailDisposisi(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail Disposisi"> <span class="mdi mdi-book-information-variant"></span> </button>';
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
        $tujuan = $request->tujuan_disposisi;
        $dataSurat = SuratMasuk::where('tx_number', $request->tx_number)
                        ->with('klasifikasiSurat')
                        ->with('derajatSurat')
                        ->first();

        if($tujuan == null){
            SuratMasuk::where('tx_number', $request->tx_number)->update([
                'status_surat' => 5
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Surat Berhasil Diarsipkan',
            ]);
        } else {
            if(in_array('2', $tujuan)){
                SuratMasuk::where('tx_number', $request->tx_number)->update([
                    'status_surat' => 5
                ]);

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
                    'status_surat' => 3
                ]);

                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'message' => 'Berhasil Update Isi Disposisi',
                ]);
            }

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
                            'tgl_surat' => $dd->suratMasuk->tgl_surat,
                            'tgl_diterima' => $dd->suratMasuk->tgl_diterima,
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
                'tgl_kirim' => $request->tgl_kirim,
            ];

            $data = Pengiriman::create($insertedData);

            SuratMasuk::where('tx_number', $request->tx_number)->update([
                'status_surat' => 4
            ]);

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
}
