@extends('layouts/layoutMaster')

@section('title', 'Surat Masuk - Form Surat Masuk')

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
  <span class="text-muted fw-light">Surat Masuk /</span> Form Agenda Surat Masuk
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
  <!--/ Card Border Shadow -->

<div class="row mb-4">
    <!-- Bootstrap Validation -->
    <div class="col-md">
      <div class="card">
        <h5 class="card-header">Form Agenda Surat Masuk</h5>
        <div class="card-body">
          <form action="javascript:void(0)" id="form-surat-masuk" class="needs-validation" novalidate>
            @csrf
            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Surat" name="nomor_agenda" value="" disabled />
              <label for="nomor_agenda">Nomor Agenda</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="nomor_surat" placeholder="Nomor Surat" name="nomor_surat" required />
              <label for="nomor_surat">Nomor Surat</label>
              <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon masukan nomor surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select id="klasifikasi" name="klasifikasi" class="form-select select2" data-allow-clear="true">
                <option value="">Pilih Klasifikasi Surat</option>
                <option value="1">Biasa</option>
                <option value="2">Rahasia</option>
              </select>
              {{-- <label for="klasifikasi">Klasifikasi Surat</label> --}}
              <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon pilih klasifikasi surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select id="derajat" name="derajat" class="form-select select2" data-allow-clear="true">
                <option value="">Pilih Derajat Surat</option>
                <option value="1">Biasa</option>
                <option value="2">Segera</option>
                <option value="3">Kilat</option>
              </select>
              {{-- <label for="derajat">Derajat Surat</label> --}}
              <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon pilih derajat surat. </div>
            </div>

            <div class="mb-4 form-floating form-floating-outline">
              <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-surat" name="tanggal_surat" required readonly/>
              <label for="tanggal_surat">Pilih Tanggal Surat</label>
              <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon pilih tanggal surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select id="asal_surat" name="asal_surat" class="select2 form-select">
                <option value="">Pilih Asal Surat</option>
                <optgroup label="Internal">
                  <option value="divpropam">Divpropam</option>
                  <option value="mabes_polri" >Mabes Polri</option>
                  <option value="polda" >Polda</option>
                </optgroup>
                <optgroup label="External">
                  <option value="kl">kementrian / Lembaga Pemerintahan</option>
                  <option value="non_gov">Lembaga Non-Pemerintahan</option>
                  <option value="individu">Perseorangan / Pribadi / Lain-lain</option>
                  <option value="dumas">Aduan Masyarakat (Dumas)</option>
                </optgroup>
              </select>
              <div class="valid-feedback"> OK! </div>
              <div class="invalid-feedback"> Mohon pilih asal surat. </div>
              {{-- <label for="asal_surat">Pilih Asal Surat</label> --}}
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" id="entity_asal_surat" placeholder="Asal Surat" name="entity_asal_surat" required />
                <label for="entity_asal_surat">Asal Surat</label>
                <div class="valid-feedback"> OK! </div>
                <div class="invalid-feedback"> Mohon masukan entity asal surat. </div>
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
                <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-diterima" name="tanggal_diterima" required readonly/>
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

            <div class="card mb-4">
              <label for="formFile" class="form-label">Upload Dokumen Surat</label>
              <input class="form-control" type="file" id="file-surat" name="file_surat" required>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <textarea class="form-control h-px-75" id="catatan" name="catatan" rows="3" placeholder="Catatan" required></textarea>
              <label for="catatan">Catatan</label>
            </div>
            
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

@endsection
