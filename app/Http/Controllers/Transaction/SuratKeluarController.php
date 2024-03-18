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
            $data['is_konseptor'] = $data['suratKeluar']->konseptor == $user->id ? true : false;
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
                ->editColumn('updated_at', function($data){
                    // $log_surat = LogSuratKeluar::where('tx_number',$data->tx_number)->latest()->first();
                    $tgl_diperbarui = Carbon::parse($data->updated_at)->translatedFormat('d F Y H:i T');
                    return $tgl_diperbarui;
                })
                ->editColumn('posisi_surat', function($data){
                    $status_surat = StatusSurat::find($data->status_surat)->kode_status;
                    $posisi = $status_surat == '208' ? $data->entity_tujuan_surat_detail :  $data->posisiSurat->leader_alias;
                    return $posisi;
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
        if (!$surat->file_path) {
            return back()->with('error','File Dokumen belum diupload!');
        }
        $filePath = public_path().'/document/surat-keluar/'.$surat->file_path;
        return response()->file($filePath);
    }

    public function mintaNoSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $posisi = $user_org->parent_id;
        $currentStatus = $dataSurat->statusSurat;
        
        if ($user->organization == $dataSurat->konseptorSurat->organization) {
            # code...
            $log_status_surat = Helpers::getStatusSurat('209')->id;
            $log_surat = LogSuratKeluar::where('tx_number',$dataSurat->tx_number)
            ->where('status',$log_status_surat)
            ->where('updated_by','<>',$dataSurat->konseptor)
            ->latest('created_at')
            ->first();
            $posisi = $currentStatus->kode_status == '201' ? $user_org->parent_id : $log_surat->updatedBy->organization;
            $status_surat = $currentStatus->kode_status == '201' ? Helpers::getStatusSurat('202')->id : Helpers::getStatusSurat('211')->id;
        }  elseif ($user->organization == $posisi) {
            # code...
            $posisi = 13;
            $status_surat = Helpers::getStatusSurat('204')->id;
        } else {
            if ($user_org->id != 2) {
                $posisi = 2;
            } elseif ($user_org->id == 2) {
                $posisi = 13;
            }
            $status_surat = Helpers::getStatusSurat('204')->id;
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
            'konseptor' => $dataSurat->konseptor,
            'penandatangan' => $dataSurat->penandatangan_surat,
            'catatan' => $dataSurat->catatan,
        ]);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'BERHASIL MELAKUKAN PERMINTAAN NOMOR SURAT',
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

        // $tgl_diperbarui = Carbon::parse($dataSurat[0]->updated_at)->translatedFormat('d F Y H:i');
        // dd($tgl_diperbarui);

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
                ->rawColumns(['no_draft_surat','status','updated_at','action'])
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
            $status_surat = StatusSurat::where('kode_status','201')->first();

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
                'no_surat' => 'BELUM DIBERI NOMOR',
                'posisi_surat' => $asalSurat->id,
                'jenis_surat' => strtoupper($request->jenis_surat),
                'tgl_surat' => Carbon::createFromFormat('Y-m-d H:i',$request->tanggal_surat)->format('Y-m-d H:i:s'),
                'perihal' => strtoupper($request->perihal),
                'lampiran' => strtoupper($request->judul_lampiran),
                'lampiran_type' => strtoupper($request->lampiran_type),
                'jml_lampiran' => $request->jumlah_lampiran,
                'konseptor' => strtoupper($request->konseptor),
                'unit_kerja' => $request->unit_kerja_pemohon ?? $user->organization,
                'penandatangan_surat' => $request->penandatangan_surat,
                'catatan' => strtoupper($request->catatan),
                'created_by' => $user->id,
                'status_surat' =>$status_surat->id,
                'file_path' => $file_path,
                'tujuan_surat' => $tujuanSurat->id,
                'asal_surat' => $asalSurat->id,
                'entity_tujuan_surat' => $entityTujuanSurat->id,
                'entity_tujuan_surat_detail' => strtoupper($request->entity_tujuan_surat_detail),
            ];
            SuratKeluar::create($insertedData);

            LogSuratKeluar::create([
                'tx_number' => $insertedData['tx_number'],
                'process_date' => Carbon::now(),
                'status' => $insertedData['status_surat'],
                'updated_by' => $user->id,
                'posisi_surat' => $insertedData['posisi_surat'],
                'konseptor' => $insertedData['konseptor'],
                'penandatangan' => $insertedData['penandatangan_surat'],
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
            ->with('createdUser')
            ->with('konseptorSurat')
            ->first();
            $entity_tujuan_surat = EntityTujuanSurat::find($surat->entity_tujuan_surat);
            $penandatangan = Organization::find($surat->penandatangan_surat);
            $konseptor = User::find($surat->konseptor);
            $status_surat = StatusSurat::find($surat->statusSurat->id);
            $log_surat = LogSuratKeluar::where('tx_number',$surat->tx_number)->latest('created_at')->first();
            // dd($log_surat);

            if (!$surat){
                throw new Exception('Data Disposisi tidak ditemukan atau operator belum melakukan update disposisi', 404);
            } else {
                if ( ($user->organization == $surat->penandatangan_surat) || $user->organization == 13 ) {
                    $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionTTDSurat(`'.$surat->tx_number.'`)">TTD SURAT</button>';
                } 
                
                $btn_action = '';
                if ($user->organization == $surat->konseptorSurat->organization) {
                    if (($surat->status_surat == Helpers::getStatusSurat('209')->id) || ($surat->status_surat == Helpers::getStatusSurat('210')->id)) {
                        $btn_action .= '<a href="'.route('create-bukuagenda-suratkeluar',['txNo'=>base64_encode($surat->tx_number)]).'" type="button" class="btn btn-outline-warning">Edit Surat</a>
                        <button type="button" class="btn btn-outline-warning" onclick="actionMintaNomorSurat(`'.$surat->tx_number.'`)">Minta Ulang Nomor Surat</button>';
                    } elseif($surat->status_surat == Helpers::getStatusSurat('201')->id) {
                        $btn_action .= '<a href="'.route('create-bukuagenda-suratkeluar',['txNo'=>base64_encode($surat->tx_number)]).'" type="button" class="btn btn-outline-warning mx-2">Edit Surat</a>
                        <button type="button" class="btn btn-outline-warning" onclick="actionMintaNomorSurat(`'.$surat->tx_number.'`)">Minta Nomor Surat</button>';
                    }
                }
                
                if ($surat->statusSurat->id == Helpers::getStatusSurat('206')->id) { 
                    // $btn_action = '<button type="button" class="btn btn-outline-warning" onclick="actionKirimSurat(`'.$surat->tx_number.'`)">Kirim Surat</button>';
                    $btn_action = '<a href="'.route('agenda-surat-keluar').'" type="button" class="btn btn-outline-warning">Kirim Surat</a>';
                }
                $header = [
                    'konseptor' => $konseptor->name,
                    'tgl_surat' => Carbon::parse($surat->tgl_surat)->translatedFormat('d F Y'),
                    'penandatangan' => $penandatangan->leader_alias,
                    'perihal' => $surat->perihal,
                    'status_surat' => $status_surat->description.' oleh '. $log_surat->updatedBy->name,
                    'tujuan_surat' => $entity_tujuan_surat->entity_name.' - '.$surat->entity_tujuan_surat_detail,
                    // 'btn_teruskan' => $btn_teruskan,
                    'btn_action' => $btn_action,
                    'file_surat' => '<a href="'.route('download-surat-keluar',['txNo'=> base64_encode($surat->tx_number)]).'" target="_blank" type="button" class="badge rounded-pill bg-label-info" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="downloadFile('.$surat->tx_number.')" title="Download File">'.$surat->tx_number.'</a>',
                ];
                $detail = [
                    'tx_number' => $surat->tx_number,
                    'catatan' => $surat->catatan,
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
    public function update(Request $request, $txNo)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $txNo = base64_decode($txNo);
            $surat = SuratKeluar::find($txNo);
            $txNumber = $surat->tx_number;
            $status_surat = $surat->status_surat;

            if ( ($surat->status_surat == Helpers::getStatusSurat('209')->id) || ($surat->status_surat == Helpers::getStatusSurat('210')->id) ) {
                $status_surat = Helpers::getStatusSurat('210')->id;
            }

            $entityTujuanSurat = EntityAsalSurat::find($request->tujuan_surat);
            $tujuanSurat = AsalSurat::find($entityTujuanSurat->asal_surat_id);

            // $org = Organization::where('id', $request->tujuan_surat)->first();
            $user = Auth::getUser();
            $asalSurat = Organization::where('id',$user->organization)->first();
            // $noAgenda = Helpers::generateNoAgenda($org->suffix_agenda,'keluar');

            $file = '';
            if ($request->hasFile('file_surat')) {
                $documentFile = $request->file('file_surat');
                $filename = $documentFile->getClientOriginalName();
                $path = $txNumber.'.pdf';
                $documentFile->move(public_path().'/document/surat-keluar/', $path);
                $file = $path;
            } else {
                $file = $surat->file_path;
            }

            $insertedData = [
                'tx_number' => $txNumber,
                'no_agenda' => null,
                'no_surat' => 'BELUM DIBERI NOMOR',
                'posisi_surat' => $asalSurat->id,
                'jenis_surat' => strtoupper($request->jenis_surat),
                'tgl_surat' => $request->tanggal_surat,
                'perihal' => strtoupper($request->perihal),
                'lampiran' => strtoupper($request->judul_lampiran),
                'lampiran_type' => strtoupper($request->lampiran_type),
                'jml_lampiran' => $request->jumlah_lampiran,
                'konseptor' => strtoupper($request->konseptor),
                'unit_kerja' => $request->unit_kerja_pemohon ?? $user->organization,
                'penandatangan_surat' => $request->penandatangan_surat,
                'catatan' => strtoupper($request->catatan),
                'created_by' => $user->id,
                'status_surat' => $status_surat,
                'file_path' => $file,
                'tujuan_surat' => $tujuanSurat->id,
                'asal_surat' => $asalSurat->id,
                'entity_tujuan_surat' => $entityTujuanSurat->id,
                'entity_tujuan_surat_detail' => strtoupper($request->entity_tujuan_surat_detail),
            ];
            

            $surat->update($insertedData);

            LogSuratKeluar::create([
                'tx_number' => $surat->tx_number,
                'process_date' => Carbon::now(),
                'status' => $surat->status_surat,
                'updated_by' => $user->id,
                'posisi_surat' => $surat->posisi_surat,
                'konseptor' => $surat->konseptor,
                'penandatangan' => $surat->penandatangan_surat,
                'catatan' => $surat->catatan,
            ]);

            DB::commit();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Berhasil Update Draft Surat',
                'txNumber' => $txNumber,
                'redirect' => route('create-bukuagenda-suratkeluar')
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function renderAction($data)
    {
        $html = '';
        // if($data->status_surat == 12 && Auth::user()->hasPermissionTo('print-blanko'))
        // {
        //     $html = '<button class="btn btn-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" ><span class="mdi mdi-briefcase-eye-outline"></span></button>';
        //     // $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionMintaNomorSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Minta Nomor Surat" > <span class="mdi mdi-file-download-outline"></span> </button>';
        // }
        
        // if ( ( ($data->status_surat == 20) || ($data->status_surat == 21) ) && Auth::user()->hasPermissionTo('edit-surat')) {
        //     # code...
        //     // $html = '<button class="btn btn-info btn-sm rounded-pill" onclick="actionMintaNomorSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="View Data" > <span class="mdi mdi-eye-outline"></span> </button>
        //     // <button class="btn btn-warning btn-sm rounded-pill" onclick="actionMintaNomorSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data" > <span class="mdi mdi-file-edit-outline"></span> </button>';
        // }
        $html = '
        <button class="btn btn-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" ><span class="mdi mdi-briefcase-eye-outline"></span></button>
        <button class="btn btn-outline-primary btn-sm rounded-pill px-2" onclick="showTimeline()" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat timeline surat" > <span class="mdi mdi-timeline-text-outline"></span> </button>
        ';

        return $html;
    }
}
