<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\EntityTujuanSurat;
use App\Models\Master\Organization;
use App\Models\Master\StatusSurat;
use App\Models\Master\TujuanSurat;
use App\Models\Reference\DerajatSurat;
use App\Models\Reference\JenisSurat;
use App\Models\Reference\KlasifikasiSurat;
use App\Models\Transaction\LogSuratKeluar;
use App\Models\Transaction\SuratKeluar;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\Facades\DataTables;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($txNo = '')
    {
        $user = Auth::getUser();
        $organization = Organization::orderBy('id')->get();
        $asalSurat = $organization->where('id',$user->organization)->first();
        $unitKerjaPemohon = $asalSurat;

        $listPenandatanganSurat = Helpers::getAllParentOrg($asalSurat);;
        $listChildUser = Helpers::getAllChildOrg($organization,$asalSurat);

        $data = [
            'tujuanSurat' => TujuanSurat::all(),
            'entityTujuan' => EntityTujuanSurat::get(),
            'organization' => $organization,
            'jenis_surat' => JenisSurat::orderBy('id')->get(),
            'asalSurat' => $asalSurat,
            'konseptor' => $user,
            'unitKerjaPemohon' => $unitKerjaPemohon,
            'penandatanganSurat' => $listPenandatanganSurat,
            'childUser' => $listChildUser,
        ];

        if($txNo != ''){
            $txNo = base64_decode($txNo);
            $data['suratKeluar'] = SuratKeluar::where('tx_number', $txNo)->first();
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

    public function streamFile($txNo){
        $txNo = base64_decode($txNo);
        $surat = SuratKeluar::find($txNo);
        $filePath = public_path().'/document/surat-keluar/'.$surat->file_path;
        return response()->download($filePath)->deleteFileAfterSend(false);
    }

    public function downloadFile($txNo){
        $txNo = base64_decode($txNo);
        $surat = SuratKeluar::find($txNo);
        $filePath = public_path().'/document/surat-keluar/'.$surat->file_path;
        return response()->file($filePath);
    }

    public function mintaNoSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $posisi = $user_org->parent_id;

        if (($posisi == 1) && ($user_org->id != 2)) {
            $posisi = 2;
        } elseif (($posisi == 1) && ($user_org->id == 2)) {
            $posisi = 13;
        }

        if ($dataSurat->penandatangan_surat != $user->organization) {
            $status_surat = 14;
        } else {
            $status_surat = 15;
        }
        
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

        // PermintaanNoSurat::create([
        //     'tx_number' => $dataSurat->tx_number,
        //     'created_by' => Auth::getUser()->id,
        //     'catatan' => $dataSurat->catatan,
        //     'penandatangan' => $dataSurat->penandatangan_surat,
        // ]);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Melakukan Permintaan Nomor Surat',
            'no_tx' => $dataSurat,
        ]);
    }

    public function permintaanNoSurat()
    {
        $user = Auth::getUser();
        $organization = Organization::orderBy('id')->get();
        $permintaanSurat = SuratKeluar::where('penandatangan_surat',$user->organization)->get();

        $data = [
            'organization' => $organization,
            'konseptor' => $user,
            'permintaanSurat' => $permintaanSurat
        ];

        return view('content.surat-keluar.permintaan-nomor',$data);
    }

    public function dataMintaNoSurat()
    {
        $dataSurat = SuratKeluar::orderBy('created_at', 'DESC')
                        ->With('statusSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->where('posisi_surat',Auth::user()->organization)
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
                    }

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$data->statusSurat->description."'>" .$data->statusSurat->name. "</span>";
                })
                ->editColumn('action', function($data){
                    return self::renderAction($data);
                })
                ->rawColumns(['no_draft_surat','status','action'])
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
            $user = Auth::user();
            $entityTujuanSurat = EntityAsalSurat::find($request->tujuan_surat);
            $tujuanSurat = AsalSurat::find($entityTujuanSurat->asal_surat_id);

            // $org = Organization::where('id', $request->tujuan_surat)->first();
            $user = Auth::getUser();
            $asalSurat = Organization::where('id',$user->organization)->first();

            $txNumber = Helpers::generateTxNumber('keluar');
            // $noAgenda = Helpers::generateNoAgenda($org->suffix_agenda,'keluar');

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
                'no_agenda' => null,
                'no_surat' => '-',
                'posisi_surat' => $asalSurat->id,
                'jenis_surat' => $request->jenis_surat,
                'tgl_surat' => $request->tanggal_surat,
                'perihal' => $request->perihal,
                'lampiran' => $request->lampiran,
                'lampiran_type' => $request->lampiran_type,
                'jml_lampiran' => $request->jumlah_lampiran,
                'konseptor' => $request->konseptor,
                'unit_kerja' => $request->unit_kerja_pemohon ?? $user->organization,
                'penandatangan_surat' => $request->penandatangan_surat,
                'catatan' => $request->catatan,
                'created_by' => $user->id,
                'status_surat' => 12,
                'file_path' => $file_path,
                'tujuan_surat' => $tujuanSurat->id,
                'asal_surat' => $asalSurat->id,
                'entity_tujuan_surat' => $entityTujuanSurat->id,
                'entity_tujuan_surat_detail' => $request->entity_tujuan_surat_detail,
            ];
            SuratKeluar::create($insertedData);

            LogSuratKeluar::create([
                'tx_number' => $insertedData['tx_number'],
                'process_date' => Carbon::now(),
                'status' => $insertedData['status_surat'],
                'updated_by' => $user->id,
                'posisi_surat' => $insertedData['posisi_surat'],
                'catatan' => $insertedData['catatan'],
            ]);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Buat Draft Surat',
                'txNumber' => $txNumber,
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
                if ( ($user->organization == $surat->penandatangan_surat) || $user->organization == 13 ) {
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionTTDSurat(`'.$surat->tx_number.'`)">TTD SURAT</button>';
                } else {
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionMintaNomorSurat(`'.$surat->tx_number.'`)">Teruskan</button>';
                }
                
                if ($surat->statusSurat->id == 17) { 
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionAgendakanSurat(`'.$surat->tx_number.'`)">Agendakan</button>';
                }
                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'catatan' => $surat->catatan,
                    'status_surat' => $status_surat->description.' oleh ',
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    // 'btn_teruskan' => $btn_teruskan,
                    'btn_action' => $btn_action,
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

    public function getTimelineSurat($txNo)
    {
        try {
            $user = Auth::getUser();
            $txNo = base64_decode($txNo);
            // $surat = SuratKeluar::where('tx_number', $txNo)
            // ->with('statusSurat')
            // ->with('tujuanSurat')
            // ->with('createdUser')->get();

            $log_surat = LogSuratKeluar::where('tx_number',$txNo)->get();
            dd($log_surat);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'OK',
                'data' => $log_surat,
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

    public function renderAction($data)
    {
        $html = '';
        if($data->status_surat == 12 && Auth::user()->hasPermissionTo('print-blanko'))
        {
            $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionMintaNomorSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Minta Nomor Surat" > <span class="mdi mdi-file-download-outline"></span> </button>';
        }
        
        if ($data->status_surat == 16 && Auth::user()->hasPermissionTo('edit-surat')) {
            # code...
            $html = '<button class="btn btn-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" ><span class="mdi mdi-briefcase-eye-outline"></span></button>';
            // $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="actionMintaNomorSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="View Data" > <span class="mdi mdi-eye-outline"></span> </button>
            // <button class="btn btn-warning btn-sm rounded-pill" onclick="actionMintaNomorSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" > <span class="mdi mdi-file-edit-outline"></span> </button>';
        }

        return $html;
    }
}
