<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\EntityTujuanSurat;
use App\Models\Master\Organization;
use App\Models\Master\TujuanSurat;
use App\Models\Reference\DerajatSurat;
use App\Models\Reference\JenisSurat;
use App\Models\Reference\KlasifikasiSurat;
use App\Models\Transaction\SuratKeluar;
use Carbon\Carbon;
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
                    }

                    return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$data->statusSurat->description."'>" .$data->statusSurat->name. "</span>";
                })
                ->editColumn('action', function($data){
                    return self::renderAction($data);
                })
                ->rawColumns(['no_draft_surat','status','action'])
                ->make(true);
    }

    public function mintaNoSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        
        // Update Status Surat
        SuratKeluar::where('tx_number', $txNo)->update([
            'status_surat' => 2
        ]);

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
                        ->whereHas('penandatangan', function($user){
                            $user->where('tujuan_surat_id', Auth::user()->organization);
                        })
                        ->get();
        dd($dataSurat);

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
        // dd($request->all());
        DB::beginTransaction();
        try {
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
                'no_agenda' => '-',
                'no_surat' => '-',
                'jenis_surat' => $request->jenis_surat,
                'tgl_surat' => $request->tanggal_surat,
                'perihal' => $request->perihal,
                'lampiran' => $request->lampiran,
                'lampiran_type' => $request->lampiran_type,
                'jml_lampiran' => $request->jumlah_lampiran,
                'konseptor' => $request->konseptor,
                'unit_kerja' => $request->unit_kerja_pemohon,
                'penandatangan_surat' => $request->penandatangan_surat,
                'catatan' => $request->catatan,
                'created_by' => Auth::user()->id,
                'status_surat' => 1,
                'file_path' => $file_path,
                'tujuan_surat' => $tujuanSurat->id,
                'asal_surat' => $asalSurat->id,
                'entity_tujuan_surat' => $entityTujuanSurat->id,
                'entity_tujuan_surat_detail' => $request->entity_tujuan_surat_detail,
            ];

            SuratKeluar::create($insertedData);

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

    public function renderAction($data)
    {
        $html = '';
        if($data->status_surat == 1 && Auth::user()->hasPermissionTo('print-blanko'))
        {
            $html = '<button class="btn btn-primary btn-sm rounded-pill" onclick="actionMintaNomorSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Minta Nomor Surat" > <span class="mdi mdi-file-download-outline"></span> </button>';
        }

        return $html;
    }
}
