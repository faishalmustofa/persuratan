<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DisposisiMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.surat_masuk.disposisi-masuk');
    }

    public function getData(Request $request)
    {
        $suratMasuk = SuratMasuk::orderBy('tgl_diterima', 'asc')
                        ->with('asalSurat')
                        ->with('entityAsalSurat')
                        ->With('statusSurat')
                        ->with('klasifikasiSurat')
                        ->with('derajatSurat')
                        ->with('tujuanSurat')
                        ->with('createdUser')
                        ->with('disposisi')
                        ->whereHas('disposisi', function($dispo){
                            $dispo->where('tujuan_disposisi', Auth::user()->organization);
                        })
                        ->where('status_surat', '4');


        if($request->tgl_surat != null){
            $rangeDate = explode('to', $request->tgl_surat);
            $tglAwal = trim($rangeDate[0], ' ');
            $tglAkhir = trim($rangeDate[1], ' ');
            $suratMasuk = $suratMasuk->whereBetween('tgl_surat', [$tglAwal, $tglAkhir]);
        } else if ($request->nomor_agenda != null) {
            $suratMasuk = $suratMasuk->where('no_agenda', 'like', '%' .$request->nomor_agenda. '%');
        } else if ($request->nomor_surat != null){
            $suratMasuk = $suratMasuk->where('no_surat', 'like', '%' .$request->nomor_surat. '%');
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

                    $totalSurat = SuratMasuk::where('no_surat', $data->no_surat)->count();
                    // if($totalSurat != 1){
                    //     $description = 'Surat ini sudah dibuat agenda ulang';
                    //     $statusName = 'Dibuatkan agenda baru';
                    //     $bg = 'bg-label-success';

                    //     return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$description."'>" .$statusName. "</span>";
                    // } else {
                        return "<span class='badge rounded-pill $bg' data-bs-toggle='tooltip' data-bs-placement='top' title='".$data->statusSurat->description."'>" .$data->statusSurat->name. "</span>";
                    // }
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
        $totalSurat = SuratMasuk::where('no_surat', $data->no_surat)->count();

        // if($totalSurat == 1){
            if($data->status_surat == 4 && Auth::user()->hasPermissionTo('create-surat'))
            {
                $txNo = base64_encode($data->tx_number);
                $html = '<a href="'.route('create-bukuagenda', $txNo).'" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Buat Agenda Surat Masuk" > <span class="mdi mdi-note-plus"></span> </button>';
            }
        // }

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
