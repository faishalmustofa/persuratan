@extends('layouts/layoutMaster')

@section('title', 'Dashboard Keluar - Persuratan')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-logistics-dashboard.css')}}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboard/dashboard-surat-keluar.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Persuratan /</span> Dashboard Keluar
</h4>

<!-- Card Border Shadow -->
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-email-arrow-right mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $jumlahSuratKeluar }}</h4>
        </div>
        <p class="mb-0 text-heading">Total Surat Keluar</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning">
              <i class='mdi mdi-email-outline mdi-20px'></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $jumlahDraftSurat }}</h4>
        </div>
        <p class="mb-0 text-heading">Total Draft Surat</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class='mdi mdi-email-plus-outline mdi-20px'></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $jumlahAgendaSurat }}</h4>
        </div>
        <p class="mb-0 text-heading">Total Agenda Surat Keluar</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class='mdi mdi-email-fast mdi-20px'></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $totalSuratTerkirim }}</h4>
        </div>
        <p class="mb-0 text-heading">Total Surat Terkirim</p>
      </div>
    </div>
  </div>
</div>
<!--/ Card Border Shadow -->

<div class="row">
  
  <!-- Chart Perbulan-->
  <div class="col-lg-12 col-xxl-12 mb-4 order-3 order-xxl-1">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 mb-1">Statistik Mingguan Surat Keluar Bulan Ini</h5>
          <p class="text-body mb-0">Total Surat Keluar {{ $jumlahSuratKeluar }}</p>
        </div>
        <div class="dropdown">
          <button type="button" class="btn btn-outline-primary dropdown-toggle" id="buttonMingguan" data-bs-toggle="dropdown" aria-expanded="false">Minggu 1</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataHarian('1')">Minggu 1</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataHarian('2')">Minggu 2</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataHarian('3')">Minggu 3</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataHarian('4')">Minggu 4</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div id="chartMingguan"></div>
      </div>
    </div>
  </div>
  <!--/ Chart Perbulan -->

  <!-- Chart Perbulan-->
  <div class="col-lg-12 col-xxl-12 mb-4 order-3 order-xxl-1">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 mb-1">Statistik Bulanan Surat Keluar Tahun Ini</h5>
          <p class="text-body mb-0">Total Surat Keluar {{ $jumlahSuratKeluar }}</p>
        </div>
        <div class="dropdown">
          <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" id="buttonBulanan" aria-expanded="false">Januari</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('01')">Januari</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('02')">Februari</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('03')">Maret</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('04')">April</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('05')">Mei</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('06')">Juni</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('07')">Juli</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('08')">Agustus</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('09')">September</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('10')">Oktober</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('11')">November</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);" onclick="getDataMingguan('12')">Desember</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div id="chartBulanan"></div>
      </div>
    </div>
  </div>
  <!--/ Chart Perbulan -->

  <!-- On route vehicles Table -->
  {{-- <div class="col-12 order-5">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">On route vehicles</h5>
        </div>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="routeVehicles" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical mdi-24px"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-datatable table-responsive">
        <table class="dt-route-vehicles table">
          <thead class="table-light">
            <tr>
              <th></th>
              <th></th>
              <th>location</th>
              <th>starting route</th>
              <th>ending route</th>
              <th>warnings</th>
              <th class="w-20">progress</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div> --}}
</div>
<!--/ On route vehicles Table -->
@endsection
