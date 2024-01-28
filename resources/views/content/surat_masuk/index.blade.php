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

@section('page-script')
    <script src="{{asset('assets/js/transaction/surat-masuk.js')}}"></script>
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
  <span class="text-muted fw-light">Surat Masuk /</span> Agenda Surat Masuk
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

@can('create-surat')
    <div class="row mb-4">
        <!-- Bootstrap Validation -->
        <div class="col-md">
        <div class="card">
            <h5 class="card-header">Form Agenda Surat Masuk</h5>
            <div class="card-body">
            <form action="javascript:void(0)" id="form-surat-masuk" class="needs-validation" novalidate>
                @csrf
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 {{isset($suratMasuk) ? 'col-md-4' : 'col-md-6'}}">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Agenda" name="nomor_agenda" value="" disabled />
                            <label for="nomor_agenda">Nomor Agenda (Auto Generated)</label>
                        </div>
                    </div>
                    <div class="col-12 {{isset($suratMasuk) ? 'col-md-4' : 'col-md-5'}}">
                        <div class="form-floating form-floating-outline mb-4">
                            @if (isset($suratMasuk))
                                <input type="text" class="form-control" id="nomor_surat_asal" placeholder="Nomor Surat" name="nomor_surat_asal" onkeydown="replaceSpace(this)" required {{isset($suratMasuk) ? ($suratMasuk != null ? 'readonly' : '') : ''}} value="{{isset($suratMasuk) ? ($suratMasuk != null ? $suratMasuk->no_surat : '') : ''}}" />
                                <label for="nomor_surat_asal">Nomor Surat Asal</label>
                                <div class="invalid-feedback"> Mohon masukan nomor surat. </div>
                            @else
                                <input type="text" class="form-control" id="nomor_surat" placeholder="Nomor Surat" name="nomor_surat" onkeydown="replaceSpace(this)" required />
                                <label for="nomor_surat">Nomor Surat</label>
                                <div class="invalid-feedback"> Mohon masukan nomor surat. </div>
                                <div id="usedNoSurat" class="text-warning" style="display: none"> Nomor Surat ini sudah pernah digunakan, status surat terakhir <b id="lastStatus"></b>. </div>
                            @endif
                        </div>
                    </div>
                    @if (isset($suratMasuk))
                        <div class="col-12 col-md-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="nomor_surat" placeholder="Nomor Surat" name="nomor_surat" onkeydown="replaceSpace(this)" required value="{{$suratMasuk->tujuanDisposisi[0]->no_agenda}}" />
                                <label for="nomor_surat">Nomor Surat</label>
                                <div class="invalid-feedback"> Mohon masukan nomor surat</div>
                            </div>
                        </div>
                    @endif

                    @if (!isset($suratMasuk))
                        <div class="col-12 col-md-1 mb-4">
                            <button type="button" class="btn btn-sm btn-info" onclick="cekNoSurat()">Cek</button>
                        </div>
                    @endif

                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="asal_surat" name="asal_surat" class="select2 form-select" required>
                                <option value="">Pilih Asal Surat</option>
                                @foreach ($asalSurat as $header)
                                    @if(isset($suratMasuk))
                                        @if($header->id == '1')
                                            <optgroup label="{{$header->name}}">
                                                @foreach ($entityAsal as $detail)
                                                    @if ($detail->asal_surat_id == $header->id)
                                                        <option value="{{$detail->id}}">{{$detail->entity_name}}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @else
                                        <optgroup label="{{$header->name}}">
                                            @foreach ($entityAsal as $detail)
                                                @if ($detail->asal_surat_id == $header->id)
                                                    <option value="{{$detail->id}}">{{$detail->entity_name}}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            <div class="invalid-feedback"> Mohon pilih asal surat. </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="entity_asal_surat_detail" placeholder="Detail Entity Asal Surat" name="entity_asal_surat_detail" required />
                            <label for="entity_asal_surat_detail">Detail Entity Asal Surat</label>
                            <div class="invalid-feedback"> Mohon masukan detail entity asal surat. </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-4 form-floating form-floating-outline">
                            <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-surat" name="tanggal_surat" required {{isset($suratMasuk) ? ($suratMasuk != null ? 'readonly' : '') : ''}} value="{{isset($suratMasuk) ? ($suratMasuk != null ? $suratMasuk->tgl_surat : '') : ''}}"/>
                            <label for="tanggal_surat">Pilih Tanggal Surat</label>
                            <div class="invalid-feedback"> Mohon pilih tanggal surat. </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-4 form-floating form-floating-outline">
                            <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-diterima" name="tanggal_diterima" required/>
                            <label for="tanggal_diterima">Pilih Tanggal Diterima</label>
                            <div class="invalid-feedback"> Mohon pilih tanggal diterima. </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="klasifikasi" name="klasifikasi" class="form-select select2" data-allow-clear="true" required>
                                <option>Pilih Klasifikasi Surat</option>
                                @foreach ($klasifikasi as $item)
                                    <option value="{{$item->id}}" {{isset($suratMasuk) ? ($suratMasuk->klasifikasi == $item->id ? 'selected' : '') : ''}} >{{$item->nama}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"> Mohon pilih klasifikasi surat. </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="derajat" name="derajat" class="form-select select2" data-allow-clear="true" required>
                                <option>Pilih Derajat Surat</option>
                                @foreach ($derajat as $item)
                                    <option value="{{$item->id}}" {{isset($suratMasuk) ? ($suratMasuk->derajat == $item->id ? 'selected' : '') : ''}} >{{$item->nama}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"> Mohon pilih derajat surat. </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="tujuan_surat" name="tujuan_surat" class="select2 form-select" required>
                                <option value="">Pilih Tujuan Surat</option>
                                @foreach ($organization as $org)
                                    <option value="{{$org->id}}" {{isset($suratMasuk) ? ($suratMasuk->tujuanDisposisi[0]->tujuan_disposisi == $org->id ? 'selected' : '') : ''}} >{{$org->nama}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"> Mohon pilih tujuan surat. </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center">
                    <div class="col-12 col-md-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control h-px-75" id="perihal" name="perihal" rows="3" placeholder="Perihal" required {{isset($suratMasuk) ? ($suratMasuk != null ? 'readonly' : '') : ''}}>{{isset($suratMasuk) ? ($suratMasuk != null ? $suratMasuk->perihal : '') : ''}}</textarea>
                            <label for="perihal">Perihal</label>
                            <div class="invalid-feedback"> Mohon masukan perihal surat. </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="judul_lampiran" placeholder="Judul Lampiran" name="judul_lampiran" {{isset($suratMasuk) ? ($suratMasuk != null ? 'readonly' : '') : ''}} value="{{isset($suratMasuk) ? ($suratMasuk != null ? $suratMasuk->lampiran : '') : ''}}"/>
                            <label for="judul_lampiran">Judul Lampiran</label>
                            <div class="invalid-feedback"> Mohon masukan judul lampiran. </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="jumlah_lampiran" placeholder="Jumlah Lampiran" name="jumlah_lampiran" value="{{isset($suratMasuk) ? ($suratMasuk != null ? $suratMasuk->jml_lampiran : '') : ''}}"/>
                            <label for="jumlah_lampiran">Jumlah Lampiran</label>
                            <div class="invalid-feedback"> Mohon masukan jumlah lampiran. </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-floating form-floating-outline mb-4">
                            <select name="lampiran_type" class="form-select select2" id="lampiran_type">
                                <option value="">Pilih Tipe Lampiran</option>
                                <option value="bundle" {{isset($suratMasuk) ? ($suratMasuk->lampiran_type == 'bundle' ? 'selected' : '') : ''}}>Bundle</option>
                                <option value="lembar" {{isset($suratMasuk) ? ($suratMasuk->lampiran_type == 'lembar' ? 'selected' : '') : ''}}>Lembar</option>
                            </select>
                            <div class="invalid-feedback"> Mohon masukan tipe lampiran. </div>
                        </div>
                    </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <textarea class="form-control h-px-75" id="catatan" name="catatan" rows="3" placeholder="Catatan">{{isset($suratMasuk) ? ($suratMasuk != null ? $suratMasuk->catatan : '') : ''}}</textarea>
                    <label for="catatan">Catatan</label>
                </div>

                @if (!isset($suratMasuk))
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
                <div class="row justify-content-between">
                    <div class="col-12 col-md-6 order-md-2 order-last">
                        Data Agenda Surat Masuk
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        {{-- <div class="float-start float-lg-end">
                            <button class="btn btn-outline-primary" onclick="kirimBerkasBundle()">Kirim Berkas Ke TAUD (Bundle)</button>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('content.surat_masuk.data-list')
            </div>
        </div>
    </div>
</div>

    <!-- Modal Disposisi -->
    <div class="modal fade" id="modal-disposisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Form Disposisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="javascript:void(0)" id="form-disposisi" class="needs-validation" novalidate>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="tx_number" class="form-control">
                        <div class="form-floating form-floating-outline mb-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Surat" name="nomor_agenda" value="" readonly />
                                <label for="nomor_agenda">Nomor Agenda</label>
                            </div>
                        </div>

                        <div class="form-floating form-floating-outline mb-4">
                            <select id="tujuan_disposisi" name="tujuan_disposisi[]" class="select2 form-select" multiple>
                            </select>
                            <label for="tujuan_disposisi">Tujuan Disposisi</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control h-px-75" id="isi_disposisi" name="isi_disposisi" rows="3" placeholder="Isi Disposisi"></textarea>
                            <label for="isi_disposisi">Isi Disposisi</label>
                            <div class="invalid-feedback"> Mohon masukan Isi Disposisi. </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Disposisikan Surat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Rubah Tanggal -->
    <div class="modal fade" id="modal-edit-tgl" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Rubah Tanggal Diterima</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="javascript:void(0)" id="form-edit-tgl" class="needs-validation" novalidate>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="tx_number" class="form-control">
                        <div class="form-floating form-floating-outline mb-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" min="" id="tanggal-diterima" name="tanggal_diterima" required/>
                                <label for="tanggal_diterima">Pilih Tanggal Diterima</label>
                                <div class="invalid-feedback"> Mohon pilih tanggal diterima. </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Rubah Tanggal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Revisi Surat -->
    <div class="modal fade" id="modal-reject" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Revisi Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="javascript:void(0)" id="form-revisi" class="needs-validation" novalidate enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="tx_number" class="form-control">
                        <div class="form-group mb-4">
                            <label for="formFile" class="form-label">Upload Foto Revisi</label>
                            <input class="form-control" type="file" id="image" name="image[]" multiple accept=".jpg,.jpeg,.png">
                        </div>

                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control h-px-75" id="notes" name="notes" rows="3" placeholder="Catatan Revisi" required></textarea>
                            <label for="notes">Catatan Revisi</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Revisi Berkas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Revisi -->
    <div class="modal fade" id="modal-reject-detail" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Detail Revisi Surat</h5>
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
                            </div>
                        </div>
                    </div>

                    <div class="card text-white bg-info rounded-pills mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-white">Informasi Revisi</h5>
                            <div class="row justify-content-center align-items-center" id="detail-data">
                                <div class="col-md-4 col-12 mb-2">
                                    Tanggal Direvisi
                                </div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <span id="tgl_revisi"></span>
                                </div>

                                <div class="col-md-4 col-12 mb-2">
                                    Direvisi Oleh
                                </div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <span id="revisi_by"></span>
                                </div>

                                <div class="col-md-4 col-12 mb-2"> Catatan Revisi </div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <span id="notes"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card text-black bg-white rounded-pills mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-dark">List Gambar</h5>
                            <div class="row justify-content-center align-items-center" id="image-data">

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
