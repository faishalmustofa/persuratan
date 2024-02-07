@extends('layouts/layoutMaster')

@section('title', 'Master Data - Jenis Surat')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-logistics-dashboard.css')}}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/master-data/jenis-surat.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Master Data /</span> Jenis Surat
</h4>

<div class="row mt-4" id="container-data">
  <div class="col-md">
      <div class="card">
          <div class="card-header">
              <div class="row justify-content-between">
                  <div class="col-12 col-md-6 order-md-2 order-last">
                      Daftar Jenis Surat
                  </div>
                  <div class="col-12 col-md-6 order-md-2 order-first">
                      <div class="float-start float-lg-end">
                          <a href="{{route('jenis-surat.create')}}" class="btn btn-outline-primary">Tambah Jenis Surat</a>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card-body">
              <div class="card-datatable table-responsive pt-0">
                  <table class="datatables-basic table table-bordered" id="table-list">
                      <thead>
                          <tr>
                              <th></th>
                              <th>#</th>
                              <th>Nama Surat</th>
                              <th>Format</th>
                              <th>Deskripsi</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                  </table>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
