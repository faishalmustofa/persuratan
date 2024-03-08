<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalSurat;
use App\Models\Master\EntityAsalSurat;
use App\Models\Master\Organization;
use App\Models\Reference\JenisSuratMasuk;
use App\Models\Reference\Notification;
use App\Models\Transaction\SuratKeluar;
use App\Models\Transaction\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\DataTables;

class BukuAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($txNo = '')
    {
        $data['asalSurat'] = AsalSurat::all();
        $data['entityAsal'] = EntityAsalSurat::get();
        $data['organization'] = Organization::orderBy('id')->get();
        $data['user'] = User::with('org')->find(Auth::user()->id);
        $data['jenis_surat'] = JenisSuratMasuk::get();
        $data['noAgenda'] = '';

        if($txNo != ''){
            $txNo = base64_decode($txNo);
            $noAgenda = SuratMasuk::select('no_agenda')->where('tx_number', $txNo)->first();
            $data['noAgenda'] = $noAgenda->no_agenda;

            // Update Notification
            Notification::where('tx_number', $txNo)->update([
                'is_read' => 1,
                'read_at' => Carbon::now(),
            ]);
        }

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

        if(strtolower($loggedInOrg->org->nama) == 'spri'){
            $suratMasuk = $suratMasuk->whereHas('tujuanSurat', function($user) use ($loggedInOrg){
                $user->where('tujuan_surat', $loggedInOrg->org->parent_id);
            })->whereIn('status_surat', ['003','100','101','102']);

        } else if (strtolower($loggedInOrg->org->nama) != 'taud') {
            $suratMasuk = $suratMasuk->whereHas('tujuanSurat', function($user){
                $user->Where('tujuan_surat', Auth::user()->organization);
            })->where('status_surat', '004');

            // $suratMasuk = $suratMasuk->whereHas('createdUser', function($user){
            //     $user->where('organization', Auth::user()->organization);
            // });
        }


        if(isset($request->from) && $request->from == 'bulking'){
            $suratMasuk = $suratMasuk->where('tujuan_surat', $request->tujuan_surat)->where('status_surat', '112');
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

    public function printLaporan(Request $request)
    {
        $rangeDate = explode(' - ', $request->tgl_surat);
        $tglAwal = trim($rangeDate[0], ' ');
        $tglAkhir = trim($rangeDate[1], ' ');

        $loggedInOrg = User::with('org')->find(Auth::user()->id);
        $suratMasuk = SuratMasuk::orderBy('tx_number')->whereBetween('tgl_diterima', [$tglAwal, $tglAkhir])->whereIn('jenis_surat', $request->jenis_laporan);

        if(strtolower($loggedInOrg->org->nama) != 'taud'){
            $suratMasuk = $suratMasuk->whereHas('tujuanSurat', function($user){
                $user->Where('tujuan_surat', Auth::user()->organization);
            });
        }

        $template_document = new TemplateProcessor(public_path().'/document/template_laporan_surat_masuk.docx');
        $template_document->setValues(array(
            'tgl_bulan_cetak' => Carbon::now()->translatedFormat('l, d F Y'),
            'waktu_cetak' => Carbon::now()->format('H:i'),
            'org_cetak' => $loggedInOrg->org->nama,
            'total_surat' => $suratMasuk->count(),
            'user' => Auth::user()->name,
            'leader' => $loggedInOrg->org->leader_alias
        ));

        $jenisSurat = JenisSuratMasuk::whereIn('id', $request->jenis_laporan)->get();
        $template_document->cloneBlock('jenis_section', count($jenisSurat),true,true);
        foreach ($jenisSurat as $i => $val) {
            $suratMasukByJenis = SuratMasuk::where('jenis_surat', $val->id)->whereBetween('tgl_diterima', [$tglAwal, $tglAkhir])
                                            ->with('entityAsalSurat')
                                            ->With('statusSurat')
                                            ->with('tujuanSurat')
                                            ->get();

            $new_line = new \PhpOffice\PhpWord\Element\PreserveText('</w:t><w:br/><w:t>');

            // Value Header
            $template_document->setValues(array(
                "jenis_surat#".$i+1 => strtoupper($val->jenis_surat),
                'jenis_code#'.$i+1 => strtoupper($val->kd_jenis),
                "jumlah_jenis#".$i+1 => count($suratMasukByJenis)
            ));

            $template_document->setComplexValue('new_line#'.$i+1, $new_line);

            // Value Isi Data
            $template_document->cloneRow('no_surat#'.$i+1, count($suratMasukByJenis),true,true);
            foreach ($suratMasukByJenis as $x => $val_) {
                $template_document->setValues(array(
                    'no_surat#'. ($i+1) .'#'.$x+1 => $val_->no_surat,
                    'tgl_surat#'. ($i+1) .'#'.$x+1 => Carbon::parse($val_->tgl_surat)->translatedFormat('d F Y'),
                    'asal#'. ($i+1) .'#'.$x+1 => $val_->entityAsalSurat->entity_name ." (" . $val_->entity_asal_surat_detail . ")",
                    'perihal#'. ($i+1) .'#'.$x+1 => $val_->perihal,
                    'tujuan_surat#'. ($i+1) .'#'.$x+1 => $val_->tujuanSurat->nama,
                    'status#'. ($i+1) .'#'.$x+1 => $val_->statusSurat->name,
                ));
            }
        }

        $filename = "Laporan Surat Masuk Periode $tglAwal - $tglAkhir";
        $path = public_path().'/document/surat-masuk/laporan/'. $filename .'.docx';
        $template_document->saveAs($path);

        $pdfPath = public_path().'/document/';
        $convert='"C:/Program Files/LibreOffice/program/soffice" --headless --convert-to pdf "'.$path.'" --outdir "'.$pdfPath.'"';
        if(!exec($convert)){
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Gagal Memproses laporan surat masuk, harap coba lagi',
            ]);
        }

        $pdfFile = $filename.'.pdf';
        unlink($path);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil Cetak Blanko Disposisi',
            'file' => $filename,
            'filePath' => asset('document/'.$pdfFile)
        ]);
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
