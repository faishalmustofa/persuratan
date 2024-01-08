@extends('layouts/layoutMaster')

@section('title', 'Surat Masuk - Laporan Surat Masuk')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/pickr/pickr-themes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
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
@endsection

@section('page-script')
{{-- <script src="{{asset('assets/js/master-data/AsalSurat.js')}}"></script> --}}
<script src="{{asset('assets/js/form-validation.js')}}"></script>
<script src="{{asset('assets/js/forms-file-upload.js')}}"></script>
<script src="{{asset('assets/js/surat-masuk.js')}}"></script>
{{-- <script src="{{asset('assets/js/forms-pickers.js')}}"></script> --}}
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Surat Masuk /</span> Laporan Surat Masuk
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
            <span class="me-1">+18.2%</span>
            <small class="text-muted">than last week</small>
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
            <span class="me-1">-8.7%</span>
            <small class="text-muted">than last week</small>
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
            <span class="me-1">+4.3%</span>
            <small class="text-muted">than last week</small>
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
  <!--/ Card Border Shadow -->

<div class="row mb-4">
    <!-- Bootstrap Validation -->
    <div class="col-md">
      <div class="card">
        <h5 class="card-header">Form Surat Masuk</h5>
        <div class="card-body">
          <form action="/surat-masuk/store" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="nomor_surat" placeholder="Nomor Surat" name="nomor_surat" required />
              <label for="nomor_surat">Nomor Surat</label>
              <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon masukan nomor surat. </div>
            </div>

            <div class="mb-4 form-floating form-floating-outline">
                <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-surat" name="tanggal_surat" required />
                <label for="tanggal_surat">Pilih Tanggal Surat</label>
                <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon pilih tanggal surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" id="asal_surat" placeholder="Asal Surat" name="asal_surat" required />
                <label for="asal_surat">Asal Surat</label>
                <div class="valid-feedback"> OK! </div>
                <div class="invalid-feedback"> Mohon masukan asal surat. </div>
            </div>
            
            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" id="tujuan_surat" placeholder="Tujuan Surat" name="tujuan_surat" required />
                <label for="tujuan_surat">Tujuan Surat</label>
                <div class="valid-feedback"> OK! </div>
                <div class="invalid-feedback"> Mohon masukan tujuan surat. </div>
            </div>
            
            <div class="form-floating form-floating-outline mb-4">
                <textarea class="form-control h-px-75" id="perihal" name="perihal" rows="3" placeholder="Perihal" required></textarea>
                <label for="perihal">Perihal</label>
            </div>

            <div class="mb-4 form-floating form-floating-outline">
                <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-diterima" name="tanggal_diterima" required/>
                <label for="tanggal_diterima">Pilih Tanggal Diterima</label>
                <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon pilih tanggal diterima. </div>
            </div>

            <div class="row">
                <div class="col-10">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="judul_lampiran" placeholder="Judul Lampiran" name="judul_lampiran" required />
                        <label for="judul_lampiran">Judul Lampiran</label>
                        <div class="valid-feedback"> OK! </div>
                        <div class="invalid-feedback"> Mohon masukan judul lampiran. </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="jumlah_lampiran" placeholder="Jumlah Lampiran" name="jumlah_lampiran" required />
                        <label for="jumlah_lampiran">Jumlah Lampiran</label>
                        <div class="valid-feedback"> OK! </div>
                        <div class="invalid-feedback"> Mohon masukan jumlah lampiran. </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 mt-4">
                <h5 class="card-header">Upload Dokumen Surat</h5>
                <div class="card-body">
                    <div action="/upload" class="dropzone needsclick" id="dropzone-basic">
                        <div class="dz-message needsclick">
                            Drop files here or click to upload
                            <span class="note needsclick">(This is just a demo dropzone. Selected files are <span class="fw-medium">not</span> actually uploaded.)</span>
                        </div>
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </div>
                </div>
              </div>
            
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Bootstrap Validation -->
  </div>

@endsection
