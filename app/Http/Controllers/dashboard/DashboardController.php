<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
