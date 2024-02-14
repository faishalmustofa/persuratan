@extends('layouts/layoutMaster')

@section('title', 'Dashboard Masuk - Persuratan')

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
<script src="{{asset('assets/js/dashboard/dashboard-surat-masuk.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Persuratan /</span> Dashboard Masuk
</h4>

<!-- Card Border Shadow -->
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-bus-school mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">42</h4>
        </div>
        <p class="mb-0 text-heading">Total Surat Masuk</p>
        <p class="mb-0">
          <span class="me-1">+18.2%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning">
              <i class='mdi mdi-alert mdi-20px'></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">8</h4>
        </div>
        <p class="mb-0 text-heading">Jumlah Surat Masuk</p>
        <p class="mb-0">
          <span class="me-1">-8.7%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class='mdi mdi-source-fork mdi-20px'></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">27</h4>
        </div>
        <p class="mb-0 text-heading">Jumlah Surat Didisposisi</p>
        <p class="mb-0">
          <span class="me-1">+4.3%</span>
          <small class="text-muted">than last week</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class='mdi mdi-timer-outline mdi-20px'></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">13</h4>
        </div>
        <p class="mb-0 text-heading">Jumlah Surat Diarsipkan</p>
        <p class="mb-0">
          <span class="me-1">-2.5%</span>
          <small class="text-muted">than last week</small>
        </p>
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
          <h5 class="m-0 me-2 mb-1">Statistik Mingguan Surat Masuk</h5>
          <p class="text-body mb-0">Total Surat Masuk 23.8k</p>
        </div>
        <div class="dropdown">
          <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Minggu 1</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="javascript:void(0);">Minggu 1</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Minggu 2</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Minggu 3</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Minggu 4</a></li>
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
          <h5 class="m-0 me-2 mb-1">Statistik Bulanan Surat Masuk</h5>
          <p class="text-body mb-0">Total Surat Masuk 23.8k</p>
        </div>
        <div class="dropdown">
          <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Januari</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="javascript:void(0);">Januari</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Februari</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Maret</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">April</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Mei</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Juni</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Juli</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Agustus</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">September</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Oktober</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">November</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Desember</a></li>
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
