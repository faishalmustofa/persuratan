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

  <style>
  .light-style table.datatables-basic thead th,
  .light-style table.datatables-basic thead td,
  .light-style table.datatables-basic thead tr, {
    border-bottom: 1px solid;
  }

  .light-style table.datatables-basic tbody th,
  .light-style table.datatables-basic tbody td,
  .light-style table.datatables-basic tbody tr {
    border-bottom: 1px solid;
  }
  
  #table-list th,
  #table-list td,
  #table-list tr {
    border-bottom: 1px solid;
  }

</style>
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>
@endsection

@section('page-script')
  <script src="{{asset('assets/js/transaction/surat-keluar.js')}}"></script>
  <script>
    $(function () {
      // let form =  new FormData($("#form-surat-keluar")[0])
      // console.log(form.getAll)
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
  <span class="text-muted fw-light">SURAT KELUAR /</span> DRAFT SURAT KELUAR
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
            <h5 class="card-header">FORM DRAFT SURAT KELUAR</h5>
            <div class="card-body">
            <form action="javascript:void(0)" id="{{isset($suratKeluar) ? 'form-update-surat' : 'form-surat-keluar'}}" class="browser-default-validation">
                @csrf
                <div class="row mb-4">
                  <div class="col">
                    <div class="form-check form-check-primary">
                      <input name="tipe-draft-surat" class="form-check-input" type="radio" value="1" id="with-document" required {{isset($suratKeluar) ? (!is_null($suratKeluar->file_path) ? 'checked' : '') : ''}} {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}/>
                      <label class="form-check-label" for="with-document"> DENGAN DRAFT DOKUMEN SURAT </label>
                      <div class="invalid-feedback"> MOHON PILIH JENIS DRAFT SURAT. </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check form-check-primary">
                      <input name="tipe-draft-surat" class="form-check-input" type="radio" value="0" id="without-document" required {{isset($suratKeluar) ? (is_null($suratKeluar->file_path) ? 'checked' : '') : ''}} {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}/>
                      <label class="form-check-label" for="without-document"> TANPA DRAFT DOKUMEN SURAT </label>
                      <div class="invalid-feedback"> MOHON PILIH JENIS DRAFT SURAT. </div>
                    </div>
                  </div>
                </div>
                
                <div class="form-floating form-floating-outline mb-4">
                  <input type="text" class="form-control" id="nomor_surat" placeholder="NOMOR SURAT (DINOMORI SETELAH DI TTD)" name="nomor_surat" disabled {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->no_surat : '') : ''}}" {{ isset($is_konseptor) ? ($is_konseptor ? 'disabled' : '') : '' }}/>
                  <label for="nomor_surat">NOMOR SURAT (DINOMORI SETELAH DI TTD)</label>
                  <div class="invalid-feedback"> MOHON MASUKAN NOMOR SURAT. </div>
                </div>
                
                <div class="form-floating form-floating-outline mb-4">
                    <select id="jenis_surat" name="jenis_surat" class="select2 form-select" required>
                        <option value="">PILIH JENIS SURAT</option>
                        @foreach ($jenis_surat as $surat)
                            <option value="{{$surat->id}}" {{isset($suratKeluar) ? ($suratKeluar->jenis_surat == $surat->id ? 'selected' : '') : ''}} {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}>{{$surat->jenis_surat}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"> MOHON PILIH JENIS SURAT. </div>
                </div>

                <div class="mb-4 form-floating form-floating-outline">
                  <input type="text" class="form-control" placeholder="TAHUN-BULAN-TANGGAL JAM:MENIT" id="tanggal-surat" name="tanggal_surat" required {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->tgl_surat : '') : ''}}" {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}/>
                  <label for="tanggal_surat">PILIH TANGGAL SURAT</label>
                  <div class="invalid-feedback"> MOHON PILIH TANGGAL SURAT.</div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-floating form-floating-outline mb-4">
                      <select id="tujuan_surat" name="tujuan_surat" class="select2 form-select" required {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}>
                          <option value="">PILIH TUJUAN SURAT</option>
                          @foreach ($tujuanSurat as $header)
                              <optgroup label="{{ strtoupper($header->name) }}">
                                @foreach ($entityTujuan as $detail)
                                  @if ($detail->tujuan_surat_id == $header->id)
                                    <option value="{{$detail->id}}" {{ isset($suratKeluar) ? ( $detail->id == $suratKeluar->tujuanSurat->id ? 'selected' : '') : '' }}>{{strtoupper($detail->entity_name)}}</option>
                                  @endif
                                @endforeach
                              </optgroup>
                          @endforeach
                      </select>
                      <div class="invalid-feedback"> MOHON PILIH TUJUAN SURAT. </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-floating form-floating-outline mb-4">
                      <input type="text" class="form-control" id="entity_tujuan_surat_detail" placeholder="DETIL ENTITY TUJUAN SURAT" name="entity_tujuan_surat_detail" value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->entity_tujuan_surat_detail : '') : ''}}" required {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }} style="text-transform:uppercase"/>
                      <label for="entity_asal_surat_detail">DETIL ENTITY TUJUAN SURAT</label>
                      <div class="invalid-feedback"> MOHON MASUKAN DETIL ENTITY TUJUAN SURAT. </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-floating form-floating-outline mb-4">
                      <input type="text" class="form-control" id="konseptor" placeholder="Konseptor" name="konseptor-text" {{isset($konseptor) ? ($konseptor != null ? 'readonly' : '') : ''}} value="{{isset($konseptor) ? ($konseptor != null ? strtoupper($konseptor->name) : '') : ''}}"/>
                      <input type="hidden" name="konseptor" value="{{isset($konseptor) ? ($konseptor != null ? $konseptor->id : '') : ''}}"/>
                      <label for="konseptor">KONSEPTOR</label>
                      <div class="invalid-feedback"> MOHON MASUKAN KONSEPTOR. </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-floating form-floating-outline mb-4">
                      <select id="unit_kerja_pemohon" name="unit_kerja_pemohon" class="select2 form-select" {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }} {{ count($childUser) < 1 ? 'disabled' : '' }}>
                          <option value="">PILIH UNIT KERJA PEMOHON</option>
                          @if (count($childUser) < 1)
                            <option value="" selected>KONSEPTOR TIDAK MEMILIKI BAWAHAN.</option>
                          @else
                            @foreach ($childUser as $child)
                                <option value="{{$child->id}}" {{ isset($suratKeluar) ? ( $child->id == $suratKeluar->unit_kerja ? 'selected' : '') : '' }}>{{$child->nama}}</option>
                            @endforeach
                          @endif
                      </select>
                      <div class="invalid-feedback"> MOHO PILIH UNIT KERJA PEMOHON. </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-floating form-floating-outline mb-4">
                      <select id="penandatangan_surat" name="penandatangan_surat" class="select2 form-select" required {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}>
                          <option value="">PILIH PENANDATANGAN SURAT</option>
                          @if (count($penandatanganSurat) < 1)
                            <option value="">USER TIDAK MEMILIKI ATASAN.</option>
                          @else
                            @foreach ($penandatanganSurat as $penandatangan)
                                <option value="{{$penandatangan->id}}" {{ isset($suratKeluar) ? ($penandatangan->id == $suratKeluar->penandatangan_surat ? 'selected' : '') : '' }}>{{$penandatangan->leader_alias}}</option>
                            @endforeach
                          @endif
                      </select>
                      <div class="invalid-feedback"> MOHON PILIH PENANDATANGAN SURAT. </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-8">
                      <div class="form-floating form-floating-outline mb-4">
                          <input type="text" class="form-control" id="judul_lampiran" placeholder="JUDUL LAMPIRAN" name="judul_lampiran" value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->lampiran : '') : ''}}" {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}/>
                          <label for="judul_lampiran">JUDUL LAMPIRAN</label>
                          <div class="invalid-feedback"> MOHON MASUKAN JUDUL LAMPIRAN. </div>
                      </div>
                  </div>

                  <div class="col-2">
                      <div class="form-floating form-floating-outline mb-4">
                          <input type="number" class="form-control" id="jumlah_lampiran" placeholder="JUMLAH LAMPIRAN" name="jumlah_lampiran" value="{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->jml_lampiran : '') : ''}}" {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}/>
                          <label for="jumlah_lampiran">JUMLAH LAMPIRAN</label>
                          <div class="invalid-feedback"> MOHON MASUKAN JUMLAH LAMPIRAN. </div>
                      </div>
                  </div>

                  <div class="col-2">
                      <div class="form-floating form-floating-outline mb-4">
                          <select name="lampiran_type" class="form-select select2" id="lampiran_type" {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}>
                              <option value="">TIPE LAMPIRAN</option>
                              <option value="bundle" {{isset($suratKeluar) ? ($suratKeluar->lampiran_type == 'bundle' ? 'selected' : '') : ''}}>BUNDLE</option>
                              <option value="lembar" {{isset($suratKeluar) ? ($suratKeluar->lampiran_type == 'lembar' ? 'selected' : '') : ''}}>LEMBAR</option>
                          </select>
                          <div class="invalid-feedback"> MOHON MASUKAN TIPE LAMPIRAN. </div>
                      </div>
                  </div>
              </div>

                <div class="form-floating form-floating-outline mb-4">
                  <textarea class="form-control h-px-75" id="perihal" name="perihal" rows="3" placeholder="PERIHAL" required {{isset($suratKeluar) ? ($suratKeluar != null ? 'readonly' : '') : ''}} {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }} style="text-transform:uppercase">{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->perihal : '') : ''}}</textarea>
                  <label for="perihal">PERIHAL</label>
                  <div class="invalid-feedback"> MOHON MASUKAN PERIHAL SURAT. </div>
                </div>

                <div class="form-floating form-floating-outline mb-4">
                    <textarea class="form-control h-px-75" id="catatan" name="catatan" rows="3" placeholder="CATATAN" {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }} style="text-transform:uppercase">{{isset($suratKeluar) ? ($suratKeluar != null ? $suratKeluar->perihal : '') : ''}}</textarea>
                    <label for="catatan">CATATAN</label>
                    <div class="invalid-feedback"> MOHON MASUKAN CATATAN SURAT. </div>
                </div>

                @if (isset($suratKeluar) && !is_null($suratKeluar->file_path))
                    <div class="form-group mb-4">
                        <label for="formFile" class="form-label">UPLOAD DRAFT DOKUMEN SURAT</label>
                        <input class="form-control" type="file" id="file-surat" name="file_surat" value="{{$suratKeluar->file_path}}" accept=".pdf" {{ isset($is_konseptor) ? ($is_konseptor ? '' : 'disabled') : '' }}>
                        <div class="invalid-feedback"> MOHON UPLOAD DRAFT DOKUMEN SURAT. </div>
                        <div class="preview-files mt-3">
                          LIHAT DOKUMEN : <a href="{{ route('download-surat-keluar',['txNo'=>base64_encode($suratKeluar->tx_number)]) }}" target="_blank" class="btn btn-info btn-sm rounded-pill px-2" data-bs-toggle="tooltip" data-bs-placement="top" title="KLIK UNTUK LIHAT SURAT" ><span class="mdi mdi-file-search-outline"></span> {{ $suratKeluar->file_path }}</a>
                        </div>
                    </div>
                @else
                  <div class="form-group mb-4">
                    <label for="formFile" class="form-label">UPLOAD DRAFT DOKUMEN SURAT</label>
                    <input class="form-control" type="file" id="file-surat" name="file_surat" accept=".pdf" required>
                    <div class="invalid-feedback"> MOHON UPLOAD DRAFT DOKUMEN SURAT. </div>
                  </div>
                @endif


                <div class="row">
                    <div class="col-12">
                        @if (isset($suratKeluar))
                          <input type="hidden" value="{{ $suratKeluar->tx_number }}" id="txNo" name="txNo">
                          <button type="submit" id="btn-save" class="btn btn-warning">UPDATE DRAFT SURAT</button>
                        @else
                          <button type="submit" id="btn-save" class="btn btn-primary">BUAT DRAFT SURAT</button>
                        @endif
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
                Data Draft Surat Keluar
            </div>
            <div class="card-body">
                @include('content.surat-keluar.data-list')
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Detail Permintaan Nomor Surat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card text-white bg-primary rounded-pills mb-4">
                <div class="card-body">
                    <h5 class="card-title text-white">Informasi Draft Surat Keluar</h5>
                    <div class="row justify-content-center align-items-center" id="header-data">
                        {{-- <div class="col-md-4 col-12 mb-2">No. Surat</div>
                        <div class="col-md-1 col-12 mb-2"> : </div>
                        <div class="col-md-7 col-12 mb-2">
                            <span class='badge rounded-pill bg-label-info' id="no_surat"></span>
                        </div> --}}
                        <div class="col-md-4 col-12 mb-2">Draft File</div>
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
                    </div>
                </div>
            </div>

            <div class="card text-whiterounded-pills">
              <div class="card-body" id="detail-data">
                <div class="card mb-2">
                  <div class="form-floating form-floating-outline">
                    <textarea class="form-control h-px-75" id="catatan" name="catatan" rows="3" placeholder="Catatan" readonly></textarea>
                    <label for="catatan">Catatan</label>
                    <div class="invalid-feedback"> MOHON MASUKAN catatan surat.</div>
                  </div>
                 </div>
                 
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <div id="section-action"></div>
        </div>
      </div>
  </div>
</div>

@endsection
