<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction\SuratKeluar;
use App\Models\Transaction\SuratMasuk;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('content.dashboard.dashboards');
    }
    public function dashboardSuratMasuk()
    {
        return view('content.dashboard.dashboard-surat-masuk');
    }
    public function dashboardSuratKeluar()
    {
        return view('content.dashboard.dashboard-surat-keluar');
    }

    // Controller Surat Masuk
    public function suratMasukperDay(string $time)
    {
        $now = Carbon::now();
        $startDate;
        $endDate;
        switch ($time) {
            case '1':
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-01");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-07");
                break;
            case '2':
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-08");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-14");
                break;
            case '3':
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-15");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-21");
                break;
            default:
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-22");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-31");
        }
        
        $suratMasuk = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();
        
        $dataMasuk = array();
        foreach ($suratMasuk as $x) {
            array_push($dataMasuk, $x->total);
        }

        $suratDiarsip = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))->where('status_surat', '18')->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();

        $dataDiarsip = array();
        foreach ($suratDiarsip as $x) {
            array_push($dataDiarsip, $x->total);
        }

        $data = array($dataMasuk, $dataDiarsip);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil mengambil data surat masuk',
            'data' => $data,
        ]);
    }

    public function suratMasukperWeek(string $month)
    {
        $now = Carbon::now();
        $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-01");
        $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-31");

        $suratMasuk = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();

        $suratDiarsip = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))->where('status_surat', '18')->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();

        $q1 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-07");
        $q2 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-14");
        $q3 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-21");
        $dataMasuk = array(0,0,0,0);
        $dataDiarsip = array(0,0,0,0);

        foreach ($suratMasuk as $x) {
            if ($x->tgl_surat <= $q1 ) {
                $dataMasuk[0] = $dataMasuk[0] + 1;
            } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2 ) {
                $dataMasuk[1] = $dataMasuk[1] + 1;
            } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3 ) {
                $dataMasuk[2] = $dataMasuk[2] + 1;
            } else {
                $dataMasuk[3] = $dataMasuk[3] + 1;;
            }
        }

        foreach ($dataDiarsip as $x) {
            if ($x->tgl_surat <= $q1 ) {
                $dataDiarsip[0] += 1;
            } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2 ) {
                $dataDiarsip[1] += 1;
            } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3 ) {
                $dataDiarsip[2] += 1;
            } else {
                $dataDiarsip[3] += 1;
            }
        }

        $data = array($dataKeluar, $dataDikirim);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil mengambil data surat masuk',
            'data' => $data,
        ]);
    }

    // Controller Surat Keluar
    public function suratKeluarperDay(string $time)
    {
        $now = Carbon::now();
        $startDate;
        $endDate;
        switch ($time) {
            case '1':
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-01");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-07");
                break;
            case '2':
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-08");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-14");
                break;
            case '3':
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-15");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-21");
                break;
            default:
                $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-22");
                $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$now->month}-31");
        }
        
        $suratKeluar = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();
        
        $dataKeluar = array();
        foreach ($suratKeluar as $x) {
            array_push($dataKeluar, $x->total);
        }

        $suratKeluarDikirim = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))->where('status_surat', '18')->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();

        $dataDikirim = array();
        foreach ($suratKeluarDikirim as $x) {
            array_push($dataDikirim, $x->total);
        }

        $data = array($dataKeluar, $dataDikirim);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil mengambil data surat keluar',
            'data' => $data,
        ]);
    }

    public function suratKeluarperWeek(string $month)
    {
        $now = Carbon::now();
        $startDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-01");
        $endDate = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-31");

        $suratKeluar = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();

        $suratKeluarDikirim = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))->where('status_surat', '18')->whereDate('tgl_surat', '>=', $startDate)->whereDate('tgl_surat', '<=', $endDate)->groupBy('tgl_surat')->get();

        $q1 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-07");
        $q2 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-14");
        $q3 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-21");
        $dataKeluar = array(0,0,0,0);
        $dataDikirim = array(0,0,0,0);

        foreach ($suratKeluar as $x) {
            if ($x->tgl_surat <= $q1 ) {
                $dataKeluar[0] = $dataKeluar[0] + 1;
            } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2 ) {
                $dataKeluar[1] = $dataKeluar[1] + 1;
            } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3 ) {
                $dataKeluar[2] = $dataKeluar[2] + 1;
            } else {
                $dataKeluar[3] = $dataKeluar[3] + 1;;
            }
        }

        foreach ($suratKeluarDikirim as $x) {
            if ($x->tgl_surat <= $q1 ) {
                $dataDikirim[0] += 1;
            } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2 ) {
                $dataDikirim[1] += 1;
            } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3 ) {
                $dataDikirim[2] += 1;
            } else {
                $dataDikirim[3] += 1;
            }
        }

        $data = array($dataKeluar, $dataDikirim);

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Berhasil mengambil data surat keluar',
            'data' => $data,
        ]);
    }
}
