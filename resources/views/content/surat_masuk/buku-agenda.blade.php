@extends('layouts/layoutMaster')

@section('title', 'Surat Masuk - Buku Agenda')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />

<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/typeahead-js/typeahead.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/pickr/pickr-themes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-logistics-dashboard.css')}}" />

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>
@endsection

@section('page-script')
{{-- <script src="{{asset('assets/js/form-validation.js')}}"></script> --}}
{{-- <script src="{{asset('assets/js/forms-file-upload.js')}}"></script> --}}
<script src="{{asset('assets/js/surat-masuk/surat-masuk.js')}}"></script>
{{-- <script src="{{asset('assets/js/ui-toasts.js')}}"></script> --}}
@endsection

@section('content')
<!-- Toast with Animation -->
<div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
    <div class="toast-header">
        <i class="mdi mdi-home me-2"></i>
        <div class="me-auto fw-medium">ERROR</div>
        {{-- <small class="text-muted">11 mins ago</small> --}}
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        TERJADI KESALAHAN SISTEM!
    </div>
</div>
<!--/ Toast with Animation -->

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Surat Masuk /</span> Buku Agenda
</h4>

<!-- Card Border Shadow -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-4 mb-4">
      <div class="card card-border-shadow-primary mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="mdi mdi-email-arrow-left mdi-20px"></i></span>
            </div>
            <h4 class="ms-1 mb-0 display-6">42</h4>
          </div>
          <p class="mb-0 text-heading">TOTAL SURAT MASUK</p>
          <p class="mb-0">
            <span class="me-1">Diarsipkan : </span>
            <small class="text-muted">10</small>
          </p>
        </div>
      </div>
      <div class="d-grid gap-2 col-lg-12 mx-auto">
        <a class="btn btn-primary" type="button" style="color: #ffffff">
            <span class="mdi mdi-printer"></span> CETAK LAPORAN
        </a>
      </div>
    </div>
    <div class="col-sm-6 col-lg-4 mb-4">
      <div class="card card-border-shadow-warning mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-warning">
                <i class='mdi mdi-email-arrow-left mdi-20px'></i></span>
            </div>
            <h4 class="ms-1 mb-0 display-6">8</h4>
          </div>
          <p class="mb-0 text-heading">SURAT MASUK JANUARI</p>
          <p class="mb-0">
            <span class="me-1">Diarsipkan : </span>
            <small class="text-muted">10</small>
          </p>
        </div>
      </div>
      <div class="d-grid gap-2 col-lg-12 mx-auto">
        <a class="btn btn-warning" type="button" style="color: #ffffff">
            <span class="mdi mdi-arrow-expand-all"></span> DISPOSISI
        </a>
      </div>
    </div>
    <div class="col-sm-6 col-lg-4 mb-4">
      <div class="card card-border-shadow-danger mb-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-danger">
                <i class='mdi mdi-email-arrow-left mdi-20px'></i>
              </span>
            </div>
            <h4 class="ms-1 mb-0 display-6">27</h4>
          </div>
          <p class="mb-0 text-heading">SURAT MASUK MINGGU KE-2</p>
          <p class="mb-0">
            <span class="me-1">Diarsipkan : </span>
            <small class="text-muted">10</small>
          </p>
        </div>
      </div>
      <div class="d-grid gap-2 col-lg-12 mx-auto">
        <a class="btn btn-danger" type="button" style="color: #ffffff">
            <span class="mdi mdi-book-open-variant"></span> BUKU AGENDA
        </a>
      </div>
    </div>
</div>

<div class="card">
  <h5 class="card-header">Pencarian Surat Masuk</h5>
  <div class="card-body">
    <form action="javascript:void(0)" id="form-surat-masuk" class="needs-validation" novalidate>
      @csrf
      
      <div class="row mb-4">
        <div class="col-3">
          Tanggal Surat Masuk
        </div>
        <div class="col-9">
          <div class="input-group input-daterange" id="bs-datepicker-daterange">
            <input id="dateRangePicker" type="text" placeholder="MM/DD/YYYY" class="form-control" />
            <span class="input-group-text">to</span>
            <input type="text" placeholder="MM/DD/YYYY" class="form-control" />
          </div>
        </div>
      </div>
      
      <div class="row mb-4">
        <div class="col-3">
          Nomor Surat
        </div>
        <div class="col-9">
          <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="nomor_agenda" name="nomor_agenda" placeholder="Nomor Agenda" />
            <label for="nomor_agenda">Nomor Agenda</label>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-3">
          Nomor Surat
        </div>
        <div class="col-9">
          <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" placeholder="Nomor Surat" />
            <label for="nomor_surat">Nomor Surat</label>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-3">
          Asal Surat
        </div>
        <div class="col-9">
          <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="asal_surat" name="asal_surat" placeholder="Asal Surat" />
            <label for="asal_surat">Asal Surat</label>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-3">
          Perihal
        </div>
        <div class="col-9">
          <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Perihal Surat" />
            <label for="perihal">Perihal Surat</label>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-3">
          Tujuan Surat
        </div>
        <div class="col-9">
          <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="tujuan_surat" name="tujuan_surat" placeholder="Tujuan Surat" />
            <label for="tujuan_surat">Tujuan Surat</label>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-12">
          <button type="submit" id="btn-save" class="btn btn-primary"><span class="mdi mdi-book-search-outline"></span> Cari</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
