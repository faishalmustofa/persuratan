<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction\SuratKeluar;
use App\Models\Transaction\SuratMasuk;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;

setlocale(LC_ALL, 'IND');

class DashboardController extends Controller
{
  public function index()
  {
    $totalSuratMasuk = SuratMasuk::select(SuratMasuk::raw('COUNT(*) as total'))->get();
    $totalSuratKeluar = SuratKeluar::select(SuratKeluar::raw('COUNT(*) as total'))->get();
    $data = [
      'totalSuratMasuk' => $totalSuratMasuk[0]->total,
      'totalSuratKeluar' => $totalSuratKeluar[0]->total,
    ];
    return view('content.dashboard.dashboards', $data);
  }
  public function dashboardSuratMasuk()
  {
    $totalSuratMasuk = SuratMasuk::select(SuratMasuk::raw('COUNT(*) as total'))->get();
    $totalSuratDiarsipkan = SuratMasuk::select(SuratMasuk::raw('COUNT(*) as total'))
      ->where('status_surat', '5')
      ->get();
    $totalSuratDiterima = SuratMasuk::select(SuratMasuk::raw('COUNT(*) as total'))
      ->where('status_surat', '1')
      ->get();
    $totalSuratDidisposisi = SuratMasuk::select(SuratMasuk::raw('COUNT(*) as total'))
      ->where('status_surat', '3')
      ->get();

    $data = [
      'totalSuratMasuk' => $totalSuratMasuk[0]->total,
      'totalSuratDiarsipkan' => $totalSuratDiarsipkan[0]->total,
      'totalSuratDiterima' => $totalSuratDiterima[0]->total,
      'totalSuratDidisposisi' => $totalSuratDidisposisi[0]->total,
    ];

    return view('content.dashboard.dashboard-surat-masuk', $data);
  }
  public function dashboardSuratKeluar()
  {
    $totalSuratKeluar = SuratKeluar::select(SuratKeluar::raw('COUNT(*) as total'))->get();
    $totalSuratTerkirim = SuratKeluar::select(SuratKeluar::raw('COUNT(*) as total'))
      ->where('status_surat', '18')
      ->get();

    $data = [
      'jumlahSuratKeluar' => $totalSuratKeluar[0]->total,
      'totalSuratTerkirim' => $totalSuratTerkirim[0]->total,
    ];
    return view('content.dashboard.dashboard-surat-keluar', $data);
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
        $endDate = Carbon::now()->endOfMonth();
    }

    $suratMasuk = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $suratDiarsip = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))
      ->where('status_surat', '18')
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $interval = $startDate->diff($endDate);
    $days = $interval->format('%a');

    $dataMasuk = [];
    $dataDiarsip = [];

    for ($x = 0; $x <= $days; $x++) {
      array_push($dataMasuk, 0);
      array_push($dataDiarsip, 0);
    }

    foreach ($suratMasuk as $x) {
      $test = (int) date('d', strtotime($x->tgl_surat));
      if ($test % 7 == 0) {
        $dataMasuk[6] = $x->total;
      } elseif ($test < 29 && $test % 7 != 0) {
        $dataMasuk[($test % 7) - 1] = $x->total;
      } else {
        $dataMasuk[($test % 8) + 6] = $x->total;
      }
    }

    foreach ($suratDiarsip as $x) {
      $test = (int) date('d', strtotime($x->tgl_surat));
      if ($test % 7 == 0) {
        $dataDiarsip[6] = $x->total;
      } elseif ($test < 29 && $test % 7 != 0) {
        $dataDiarsip[($test % 7) - 1] = $x->total;
      } else {
        $dataDiarsip[($test % 8) + 6] = $x->total;
      }
    }

    $period = CarbonPeriod::create($startDate, $endDate);
    $datess = [];
    foreach ($period as $date) {
      $i = Carbon::parse($date)->locale('id');
      $i->settings(['formatFunction' => 'translatedFormat']);
      array_push($datess, $i->format('j F'));
    }

    $data = [
      'suratmasuk' => $dataMasuk,
      'diarsipkan' => $dataDiarsip,
      'time' => 'Minggu ' . $time,
      'tanggal' => $datess,
    ];

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

    $suratMasuk = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $suratDiarsip = SuratMasuk::select('tgl_surat', SuratMasuk::raw('COUNT(*) as total'))
      ->where('status_surat', '5')
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $q1 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-07");
    $q2 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-14");
    $q3 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-21");
    $dataMasuk = [0, 0, 0, 0];
    $dataDiarsip = [0, 0, 0, 0];

    foreach ($suratMasuk as $x) {
      if ($x->tgl_surat <= $q1) {
        $dataMasuk[0] = $dataMasuk[0] + 1;
      } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2) {
        $dataMasuk[1] = $dataMasuk[1] + 1;
      } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3) {
        $dataMasuk[2] = $dataMasuk[2] + 1;
      } else {
        $dataMasuk[3] = $dataMasuk[3] + 1;
      }
    }

    foreach ($suratDiarsip as $x) {
      if ($x->tgl_surat <= $q1) {
        $dataDiarsip[0] += 1;
      } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2) {
        $dataDiarsip[1] += 1;
      } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3) {
        $dataDiarsip[2] += 1;
      } else {
        $dataDiarsip[3] += 1;
      }
    }

    $dates = Carbon::create()
      ->month($month)
      ->formatLocalized('%B');

    $data = [
      'suratmasuk' => $dataMasuk,
      'diarsipkan' => $dataDiarsip,
      'time' => $dates,
    ];

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
        $endDate = Carbon::now()->endOfMonth();
    }

    $suratKeluar = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $suratKeluarDikirim = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))
      ->where('status_surat', '18')
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $interval = $startDate->diff($endDate);
    $days = $interval->format('%a');

    $dataKeluar = [];
    $dataDikirim = [];

    for ($x = 0; $x <= $days; $x++) {
      array_push($dataKeluar, 0);
      array_push($dataDikirim, 0);
    }

    foreach ($suratKeluar as $x) {
      $test = (int) date('d', strtotime($x->tgl_surat));
      if ($test % 7 == 0) {
        $dataKeluar[6] = $x->total;
      } elseif ($test < 29 && $test % 7 != 0) {
        $dataKeluar[($test % 7) - 1] = $x->total;
      } else {
        $dataKeluar[($test % 8) + 6] = $x->total;
      }
    }

    foreach ($suratKeluarDikirim as $x) {
      $test = (int) date('d', strtotime($x->tgl_surat));
      if ($test % 7 == 0) {
        $dataDikirim[6] = $x->total;
      } elseif ($test < 29 && $test % 7 != 0) {
        $dataDikirim[($test % 7) - 1] = $x->total;
      } else {
        $dataDikirim[($test % 8) + 6] = $x->total;
      }
    }

    $period = CarbonPeriod::create($startDate, $endDate);
    $datess = [];
    foreach ($period as $date) {
      $i = Carbon::parse($date)->locale('id');
      $i->settings(['formatFunction' => 'translatedFormat']);
      array_push($datess, $i->format('j F'));
    }

    // Convert the period to an array of dates
    // $datess = $period->toArray();

    $data = [
      'suratkeluar' => $dataKeluar,
      'diarsipkan' => $dataDikirim,
      'time' => 'Minggu ' . $time,
      'tanggal' => $datess,
    ];

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

    $suratKeluar = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $suratKeluarDikirim = SuratKeluar::select('tgl_surat', SuratKeluar::raw('COUNT(*) as total'))
      ->where('status_surat', '18')
      ->whereDate('tgl_surat', '>=', $startDate)
      ->whereDate('tgl_surat', '<=', $endDate)
      ->groupBy('tgl_surat')
      ->get();

    $q1 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-07");
    $q2 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-14");
    $q3 = Carbon::createFromFormat('Y-m-d', "{$now->year}-{$month}-21");
    $dataKeluar = [0, 0, 0, 0];
    $dataDikirim = [0, 0, 0, 0];

    foreach ($suratKeluar as $x) {
      if ($x->tgl_surat <= $q1) {
        $dataKeluar[0] = $dataKeluar[0] + $x->total;
      } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2) {
        $dataKeluar[1] = $dataKeluar[1] + $x->total;
      } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3) {
        $dataKeluar[2] = $dataKeluar[2] + $x->total;
      } else {
        $dataKeluar[3] = $dataKeluar[3] + $x->total;
      }
    }

    foreach ($suratKeluarDikirim as $x) {
      if ($x->tgl_surat <= $q1) {
        $dataDikirim[0] += $x->total;
      } elseif ($x->tgl_surat > $q1 && $x->tgl_surat <= $q2) {
        $dataDikirim[1] += $x->total;
      } elseif ($x->tgl_surat > $q2 && $x->tgl_surat <= $q3) {
        $dataDikirim[2] += $x->total;
      } else {
        $dataDikirim[3] += $x->total;
      }
    }

    $dates = Carbon::create()
      ->month($month)
      ->formatLocalized('%B');

    $data = [
      'suratkeluar' => $dataKeluar,
      'diarsipkan' => $dataDikirim,
      'time' => $dates,
    ];

    return response()->json([
      'status' => JsonResponse::HTTP_OK,
      'message' => 'Berhasil mengambil data surat keluar',
      'data' => $data,
    ]);
  }
}
