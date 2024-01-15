<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Transaction\SuratMasuk;
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
                        ->with('createdUser')
                        ->whereHas('createdUser', function($user){
                            $user->where('organization', Auth::user()->organization);
                        });

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
                    return "<a href='".route('showPDF',$txNo)."' class='badge rounded-pill bg-label-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Lihat Berkas Surat'>" .$data->no_surat. "</a>";
                })
                ->editColumn('action', function($data){
                    return (new SuratMasukController)->renderAction($data);
                })
                ->rawColumns(['status', 'action', 'noSurat'])
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
