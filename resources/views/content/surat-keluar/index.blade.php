@extends('layouts/layoutMaster')

@section('title', 'Surat Keluar - Form Surat Keluar')

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

@section('page-script')
    <script src="{{asset('assets/js/transaction/surat-keluar.js')}}"></script>
@endsection

@section('content')
<!-- Toast with Animation -->
<div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
    <div class="toast-header">
        <i class="mdi mdi-alert-circle-outline me-2"></i>
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
  <span class="text-muted fw-light">Surat Keluar /</span> Agenda Surat Keluar
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
          <p class="mb-0 text-heading">TOTAL SURAT KELUAR</p>
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
          <p class="mb-0 text-heading">SURAT KELUAR JANUARI</p>
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
          <p class="mb-0 text-heading">SURAT KELUAR MINGGU KE-2</p>
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
  <!--/ Card Border Shadow -->

@can('create-surat')
    <div class="row mb-4">
        <!-- Bootstrap Validation -->
        <div class="col-md">
        <div class="card">
            <h5 class="card-header">Form Agenda Surat Keluar</h5>
            <div class="card-body">
            <form action="javascript:void(0)" id="form-surat-keluar" class="needs-validation" novalidate>
                @csrf
                <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Agenda" name="nomor_agenda" value="" disabled />
                <label for="nomor_agenda">Nomor Agenda (Auto Generated)</label>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <select id="jenis_surat" name="jenis_surat" class="select2 form-select" required>
                        <option value="">Pilih Jenis Surat</option>
                        @foreach ($jenis_surat as $surat)
                            <option value="{{$surat->id}}" {{isset($suratKeluar) ? ($suratKeluar->jenis_surat == $surat->id ? 'selected' : '') : ''}} >{{$surat->nama}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"> Mohon pilih jenis surat. </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <input type="text" class="form-control" id="nomor_surat" placeholder="Nomor Surat" name="nomor_surat" required {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->no_surat : '') : ''}}" />
                    <label for="nomor_surat">Nomor Surat</label>
                    <div class="invalid-feedback"> Mohon masukan nomor surat. </div>
                </div>

                <div class="mb-4 form-floating form-floating-outline">
                    <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-surat" name="tanggal_surat" required {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->tgl_surat : '') : ''}}"/>
                    <label for="tanggal_surat">Pilih Tanggal Surat</label>
                    <div class="invalid-feedback"> Mohon pilih tanggal surat. </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <textarea class="form-control h-px-75" id="perihal" name="perihal" rows="3" placeholder="Perihal" required {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}}>{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->perihal : '') : ''}}</textarea>
                    <label for="perihal">Perihal</label>
                    <div class="invalid-feedback"> Mohon masukan perihal surat. </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <select id="tujuan_surat" name="tujuan_surat" class="select2 form-select" required>
                        <option value="">Pilih Tujuan Surat</option>
                        @foreach ($organization as $org)
                            <option value="{{$org->id}}" {{isset($suratKeluar) ? ($suratKeluar->tujuanDisposisi[0]->tujuan_disposisi == $org->id ? 'selected' : '') : ''}} >{{$org->nama}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"> Mohon pilih tujuan surat. </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="judul_lampiran" placeholder="Judul Lampiran" name="judul_lampiran" {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->lampiran : '') : ''}}"/>
                            <label for="judul_lampiran">Judul Lampiran</label>
                            <div class="invalid-feedback"> Mohon masukan judul lampiran. </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="jumlah_lampiran" placeholder="Jumlah Lampiran" name="jumlah_lampiran" value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->jml_lampiran : '') : ''}}"/>
                            <label for="jumlah_lampiran">Jumlah Lampiran</label>
                            <div class="invalid-feedback"> Mohon masukan jumlah lampiran. </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-floating form-floating-outline mb-4">
                            <select name="lampiran_type" class="form-select select2" id="lampiran_type">
                                <option value="">Tipe Lampiran</option>
                                <option value="bundle" {{isset($suratKeluar) ? ($suratKeluar->lampiran_type == 'bundle' ? 'selected' : '') : ''}}>Bundle</option>
                                <option value="lembar" {{isset($suratKeluar) ? ($suratKeluar->lampiran_type == 'lembar' ? 'selected' : '') : ''}}>Lembar</option>
                            </select>
                            <div class="invalid-feedback"> Mohon masukan tipe lampiran. </div>
                        </div>
                    </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <input type="text" class="form-control" id="konseptor" placeholder="Konseptor" name="konseptor" {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->lampiran : '') : ''}}"/>
                    <label for="konseptor">Konseptor</label>
                    <div class="invalid-feedback"> Mohon masukan konseptor. </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                  <select id="unit_kerja_pemohon" name="unit_kerja_pemohon" class="select2 form-select">
                      <option value="">Pilih Unit Kerja Pemohon</option>
                      @foreach ($organization as $org)
                          <option value="{{$org->id}}" {{isset($suratKeluar) ? ($suratKeluar->unitKerjaPemohon[0]->tujuan_disposisi == $org->id ? 'selected' : '') : ''}} >{{$org->nama}}</option>
                      @endforeach
                  </select>
                  <div class="invalid-feedback"> Mohon pilih unit kerja pemohon. </div>
                </div>
                
                <div class="form-floating form-floating-outline mb-4">
                    <input type="text" class="form-control" id="penandatangan_surat" placeholder="Penandatangan Surat" name="penandatangan_surat" {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->lampiran : '') : ''}}"/>
                    <label for="penandatangan_surat">Penandatangan Surat</label>
                    <div class="invalid-feedback"> Mohon masukan penandatangan surat. </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <textarea class="form-control h-px-75" id="catatan" name="catatan" rows="3" placeholder="Catatan" {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}}>{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->perihal : '') : ''}}</textarea>
                    <label for="catatan">Catatan</label>
                    <div class="invalid-feedback"> Mohon masukan catatan surat. </div>
                </div>

                @if (!isset($suratKeluar))
                    <div class="form-group mb-4">
                        <label for="formFile" class="form-label">Upload Dokumen Surat</label>
                        <input class="form-control" type="file" id="file-surat" name="file_surat" accept=".pdf">
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <button type="submit" id="btn-save" class="btn btn-primary">Buat Agenda</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
        </div>
        <!-- /Bootstrap Validation -->
    </div>
@endcan

<div class="row mb-4">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                Data Agenda Surat Keluar
            </div>
            <div class="card-body">
                @include('content.surat-keluar.data-list')
            </div>
        </div>
    </div>
</div>

@endsection
