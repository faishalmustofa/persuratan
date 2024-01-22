<div>
    <!-- When there is no desire, all things are at peace. - Laozi -->
</div>
@extends('layouts/layoutMaster')

@section('title', 'Surat Keluar - Data Permintaan Surat Keluar')

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
  <script src="{{asset('assets/js/transaction/permintaan-no-surat.js')}}"></script>
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
  <span class="text-muted fw-light">Surat Keluar /</span> Permintaan Nomor Surat
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
                Data Permintaan Surat Keluar
            </div>
            <div class="card-body">
              <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered" id="table-list">
                  <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>No. Draft Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Tujuan Surat</th>
                        <th>Perihal</th>
                        <th>Status</th>
                        <th>Action</th>
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
              <h5 class="modal-title" id="modalCenterTitle">Detail Disposisi</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="card text-white bg-primary rounded-pills mb-4">
                  <div class="card-body">
                      <h5 class="card-title text-white">Informasi Data Surat</h5>
                      <div class="row justify-content-center align-items-center" id="header-data">
                          <div class="col-md-4 col-12 mb-2">No. Surat</div>
                          <div class="col-md-1 col-12 mb-2"> : </div>
                          <div class="col-md-7 col-12 mb-2">
                              <span class='badge rounded-pill bg-label-info' id="no_surat"></span>
                          </div>

                          <div class="col-md-4 col-12 mb-2">No. Agenda</div>
                          <div class="col-md-1 col-12 mb-2"> : </div>
                          <div class="col-md-7 col-12 mb-2">
                              <b id="no_agenda"></b>
                          </div>

                          <div class="col-md-4 col-12 mb-2">
                              Tanggal Surat
                          </div>
                          <div class="col-md-1 col-12 mb-2"> : </div>
                          <div class="col-md-7 col-12 mb-2">
                              <span id="tgl_surat"></span>
                          </div>

                          <div class="col-md-4 col-12 mb-2">
                              Tanggal Diterima
                          </div>
                          <div class="col-md-1 col-12 mb-2"> : </div>
                          <div class="col-md-7 col-12 mb-2">
                              <span id="tgl_diterima"></span>
                          </div>

                          <div class="col-md-4 col-12 mb-2"> Tujuan Surat </div>
                          <div class="col-md-1 col-12 mb-2"> : </div>
                          <div class="col-md-7 col-12 mb-2">
                              <span id="tujuan_surat"></span>
                          </div>

                          <div class="col-md-4 col-12 mb-2"> Perihal </div>
                          <div class="col-md-1 col-12 mb-2"> : </div>
                          <div class="col-md-7 col-12 mb-2">
                              <span id="perihal"></span>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="card text-white bg-info rounded-pills">
                  <div class="card-body" id="detail-data">
                      <div class="card text-secondary bg-white mb-2">
                          <div class="card-header">Tujuan Disposisi</div>
                          <div class="card-body">
                              <div class="d-flex flex-wrap" id="tujuan_disposisi"></div>
                          </div>
                      </div>

                      <div class="card text-secondary bg-white">
                          <div class="card-header">Isi Disposisi</div>
                          <div class="card-body">
                              <p id="isi_disposisi"></p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
              </div>
      </div>
  </div>
</div>

@endsection
