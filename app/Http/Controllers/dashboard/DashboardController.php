<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dashboard\SuratMasuk;
use App\Models\Dashboard\SuratKeluar;

class DashboardController extends Controller
{
    public function index()
    {
        return view('content.dashboard.dashboards');
    }

    public function suratMasukbyDay(string $month)
    {
        $suratMasuk = SuratMasuk::orderBy('created_at')->whereMonth('fecha', '=', $month)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('Y-m-d');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }

    public function suratMasukbyDayArsip(string $month)
    {
        $suratMasuk = SuratMasuk::orderBy('created_at')->where('status_surat', '=', 'Diarsipkan')->whereMonth('fecha', '=', $month)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('Y-m-d');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }

    public function suratMasukbyWeek(string $year)
    {
        $suratMasuk = SuratMasuk::orderBy('created_at')->whereYear('created_at', $year)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('W');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }

    public function suratMasukbyWeekArsip(string $year)
    {
        $suratMasuk = SuratMasuk::orderBy('created_at')->where('status_surat', '=', 'Diarsipkan')->whereYear('created_at', $year)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('W');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }

    public function suratKeluarbyDay(string $month)
    {
        $suratMasuk = SuratKeluar::orderBy('created_at')->whereMonth('fecha', '=', $month)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('Y-m-d');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }

    public function suratKeluarbyDayDikirim(string $month)
    {
        $suratMasuk = SuratKeluar::orderBy('created_at')->where('status_surat', '=', 'Dikirim')->whereMonth('fecha', '=', $month)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('Y-m-d');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }

    public function suratKeluarbyWeek(string $year)
    {
        $suratMasuk = SuratKeluar::orderBy('created_at')->whereYear('created_at', $year)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('W');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }

    public function suratKeluarbyWeekArsip(string $year)
    {
        $suratMasuk = SuratKeluar::orderBy('created_at')->where('status_surat', '=', 'Dikirim')->whereYear('created_at', $year)->get()->groupBy(function($data) {
            return \Carbon\Carbon::parse($data->created_at)->format('W');
        })
        ->map(function($entries) {
            return $entries->count();
        })
        ->toArray();
    }
}
