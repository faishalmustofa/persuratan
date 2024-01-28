<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Transaction\SuratKeluar;
use App\Models\Transaction\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BukuAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['asalSurat'] = AsalSurat::all();
        $data['entityAsal'] = EntityAsalSurat::get();
        $data['organization'] = Organization::orderBy('id')->get();

        return view('content.surat_masuk.buku-agenda', $data);
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
                        ->with('createdUser');



        $loggedInOrg = User::with('org')->find(Auth::user()->id);

        if(strtolower($loggedInOrg->org->nama) == 'taud' || strtolower($loggedInOrg->org->nama) == 'spri'){
            $suratMasuk = $suratMasuk->whereHas('tujuanSurat', function($user) use ($loggedInOrg){
                $user->where('tujuan_surat', $loggedInOrg->org->parent_id);
            });

            if(strtolower($loggedInOrg->org->nama) == 'spri'){
                $suratMasuk = $suratMasuk->whereIn('status_surat', [3,6,7,8]);
            }

        } else {
            $suratMasuk = $suratMasuk->whereHas('createdUser', function($user){
                $user->where('organization', Auth::user()->organization);
            });
        }

        if($request->tgl_surat != null){
            $rangeDate = explode('to', $request->tgl_surat);
            $tglAwal = trim($rangeDate[0], ' ');
            $tglAkhir = trim($rangeDate[1], ' ');
            $suratMasuk = $suratMasuk->whereBetween('tgl_surat', [$tglAwal, $tglAkhir]);
        } else if ($request->nomor_agenda != null) {
            $suratMasuk = $suratMasuk->where('no_agenda', 'like', '%' .$request->nomor_agenda. '%');
        } else if ($request->nomor_surat != null){
            $suratMasuk = $suratMasuk->where('no_surat', 'like', '%' .$request->nomor_surat. '%');
        } else if ($request->asal_surat != null) {
            $suratMasuk = $suratMasuk->where('entity_asal_surat', $request->asal_surat);
        } else if ($request->perihal != null) {
            $suratMasuk = $suratMasuk->Where('perihal', 'like', '%' . $request->perihal . '%');
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
                    return "<a href='".route('showPDF',$txNo)."' class='badge rounded-pill bg-label-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Lihat Berkas Surat'>" .$data->no_surat. "</a>";
                })
                ->editColumn('action', function($data){
                    return (new SuratMasukController)->renderAction($data);
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.surat-keluar.buku-agenda');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
