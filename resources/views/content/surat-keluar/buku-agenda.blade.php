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
<style>
  /* Chrome, Safari, Edge, Opera */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
</style>
@endsection

@section('page-script')
  <script src="{{asset('assets/js/transaction/buku-agenda-keluar.js')}}"></script>
  <script>
    $(function () {
    });
  </script>
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

<div class="row mb-4">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                Daftar Agenda Surat Keluar
            </div>
            <div class="card-body">
                @include('content.surat-keluar.data-list')
            </div>
        </div>
    </div>
</div>

<!-- Daftar pengiriman surat -->
<div class="row mb-4">
  <div class="col-md">
      <div class="card">
          <div class="card-header">
              Log Data Agenda Surat Keluar
          </div>
          <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
              <table class="datatables-basic table table-bordered" id="table-log-agenda-surat">
                <thead>
                  <tr>
                      <th></th>
                      <th>#</th>
                      <th>No. Surat</th>
                      <th>Tanggal Surat</th>
                      <th>Tujuan Surat</th>
                      <th>Perihal</th>
                      <th>Tanggal Diperbarui</th>
                      <th>Posisi Surat</th>
                      <th>Status</th>
                  </tr>
                </thead>
              </table>
            </div>
          
          </div>
      </div>
  </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Detail Agenda Nomor Surat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card text-white bg-primary rounded-pills mb-4">
                <div class="card-body">
                    <h5 class="card-title text-white">Informasi Agenda Surat Keluar</h5>
                    <div class="row justify-content-center align-items-center" id="header-data">
                        {{-- <div class="col-md-4 col-12 mb-2">No. Surat</div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span class='badge rounded-pill bg-label-info' id="no_surat"></span>
                        </div> --}}
                        <div class="col-md-4 col-12 mb-2">File Surat</div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="file_surat"></span>
                        </div>

                        <div class="col-md-4 col-12 mb-2">Konseptor</div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <b id="konseptor"></b>
                        </div>

                        <div class="col-md-4 col-12 mb-2">
                            Tanggal Surat
                        </div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="tgl_surat"></span>
                        </div>
                        
                        <div class="col-md-4 col-12 mb-2">
                            Tanggal Agenda Surat
                        </div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="tgl_agenda_surat"></span>
                        </div>
                        
                        <div class="col-md-4 col-12 mb-2"> Penandatangan Surat </div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="penandatangan"></span>
                        </div>

                        <div class="col-md-4 col-12 mb-2"> Perihal </div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="perihal"></span>
                        </div>
                        
                        <div class="col-md-4 col-12 mb-2"> Tujuan Surat </div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="tujuan_surat"></span>
                        </div>
                        
                        <div class="col-md-4 col-12 mb-2"> Status Surat </div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="status_surat"></span>
                        </div>
                        
                        <div class="col-md-4 col-12 mb-2"> Catatan Surat </div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span id="catatan_surat"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="card text-whiterounded-pills">
              <div class="card-body" id="detail-data">
                <div class="card mb-2">
                  <div class="form-floating form-floating-outline">
                    <textarea class="form-control h-px-75" id="catatan" name="catatan" rows="3" placeholder="Catatan" disabled></textarea>
                    <label for="catatan">Catatan</label>
                    <div class="invalid-feedback"> Mohon masukan catatan surat.</div>
                  </div>
                 </div>
              </div>
            </div> --}}
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-outline-warning" id="edit-surat">Edit Surat</button> --}}
          <div id="section-action"></div>
        </div>
      </div>
  </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modal-form-kirim-surat" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Form Kirim Surat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="javascript:void(0)" id="form-kirim-surat" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="txNo" value="" id="txNo">
            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" id="nomor_surat" placeholder="Nomor Surat" name="nomor_surat" value="" disabled/>
                <label for="nomor_surat">Nomor Surat</label>
            </div>
            
            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Agenda" name="nomor_agenda" value="" disabled/>
                <label for="nomor_agenda">Nomor Agenda</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="jenis_pengiriman" placeholder="Jenis Pengiriman" name="jenis_pengiriman" required />
              <label for="jenis_pengiriman">Jenis Pengiriman</label>
              <div class="invalid-feedback"> Mohon masukan jenis pengiriman. </div>
            </div>
            
            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="no_resi" placeholder="Jenis Pengiriman" name="no_resi" required />
              <label for="no_resi">No. Resi</label>
              <div class="invalid-feedback"> Mohon masukan no. resi. </div>
            </div>

            <div class="mb-4 form-floating form-floating-outline">
                <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal_pengiriman" name="tanggal_pengiriman" required/>
                <label for="tanggal_pengiriman">Pilih Tanggal Pengiriman</label>
                <div class="invalid-feedback"> Mohon pilih tanggal pengiriman. </div>
            </div>

            <div class="row">
              <div class="col-12">
                  <button type="submit" id="btn-save" class="btn btn-primary" style="width: 100%">Kirim Surat</button>
                </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-outline-warning" id="edit-surat">Edit Surat</button> --}}
          <div id="section-action"></div>
        </div>
      </div>
  </div>
</div>

@endsection
