<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\Organization;
use App\Models\Transaction\SuratKeluar;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PermintaanNoSuratController extends Controller
{
    public function mintaNoSurat($txNo)
    {
        $dataSurat = SuratKeluar::where('tx_number', $txNo)->first();
        $user = Auth::getUser();
        $user_org = Organization::where('id',$user->organization)->first();
        $posisi = $user_org->parent_id;
        
        // Update Status Surat
        SuratKeluar::where('tx_number', $txNo)->update([
            'status_surat' => 2,
            'posisi_surat' => $posisi,
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

    public function renderAction($data)
    {
        $html = '';
        
        if ($data->penandatangan_surat == Auth::user()->organization) {
            $html = '<button class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-file-download-outline"></span> </button>';
            $html += '<button class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-file-download-outline"></span> </button>';
        } else {
            $html = '
                <button class="btn btn-info btn-sm rounded-pill px-2" onclick="detailSurat(`'.$data->tx_number.'`)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-file-download-outline"></span> </button>
                <button class="btn btn-warning btn-sm rounded-pill px-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail" > <span class="mdi mdi-briefcase-eye-outline"></span> </button>
                ';
        }

        return $html;
    }

    public function show(string $id)
    {
        try {
            $id = base64_decode($id);
            // $dataDisposisi = DisposisiSuratMasuk::where('tx_number', $id)->with('organization')->with('suratMasuk')->get();
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
}
