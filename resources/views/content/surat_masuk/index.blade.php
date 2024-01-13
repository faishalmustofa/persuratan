@extends('layouts/layoutMaster')

@section('title', 'Surat Masuk - Form Surat Masuk')

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
              <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Agenda" name="nomor_agenda" value="" disabled />
              <label for="nomor_agenda">Nomor Agenda (Auto Generated)</label>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="nomor_surat" placeholder="Nomor Surat" name="nomor_surat" required />
              <label for="nomor_surat">Nomor Surat</label>
              <div class="invalid-feedback"> Mohon masukan nomor surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select id="klasifikasi" name="klasifikasi" class="form-select select2" data-allow-clear="true" required>
                <option>Pilih Klasifikasi Surat</option>
                @foreach ($klasifikasi as $item)
                    <option value="{{$item->id}}">{{$item->nama}}</option>
                @endforeach
              </select>
              {{-- <label for="klasifikasi">Klasifikasi Surat</label> --}}
              <div class="invalid-feedback"> Mohon pilih klasifikasi surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <select id="derajat" name="derajat" class="form-select select2" data-allow-clear="true" required>
                <option>Pilih Derajat Surat</option>
                @foreach ($derajat as $item)
                    <option value="{{$item->id}}">{{$item->nama}}</option>
                @endforeach
              </select>
              {{-- <label for="derajat">Derajat Surat</label> --}}
              <div class="invalid-feedback"> Mohon pilih derajat surat. </div>
            </div>

            <div class="mb-4 form-floating form-floating-outline">
              <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-surat" name="tanggal_surat" required readonly/>
              <label for="tanggal_surat">Pilih Tanggal Surat</label>
              <div class="invalid-feedback"> Mohon pilih tanggal surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select id="asal_surat" name="asal_surat" class="select2 form-select" required>
                    <option value="">Pilih Asal Surat</option>
                    @foreach ($asalSurat as $header)
                        <optgroup label="{{$header->name}}">
                            @foreach ($entityAsal as $detail)
                                @if ($detail->asal_surat_id == $header->id)
                                    <option value="{{$detail->id}}">{{$detail->entity_name}}</option>
                                @endif
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <div class="invalid-feedback"> Mohon pilih asal surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" id="entity_asal_surat_detail" placeholder="Detail Entity Asal Surat" name="entity_asal_surat_detail" required />
                <label for="entity_asal_surat_detail">Detail Entity Asal Surat</label>
                <div class="invalid-feedback"> Mohon masukan detail entity asal surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select id="tujuan_surat" name="tujuan_surat" class="select2 form-select" required>
                    <option value="">Pilih Tujuan Surat</option>
                    @foreach ($organization as $org)
                        <option value="{{$org->id}}">{{$org->nama}}</option>
                    @endforeach
                </select>
                {{-- <input type="text" class="form-control" id="tujuan_surat" placeholder="Tujuan Surat" name="tujuan_surat" required /> --}}
                {{-- <label for="tujuan_surat">Tujuan Surat</label> --}}
                <div class="invalid-feedback"> Mohon pilih tujuan surat. </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <textarea class="form-control h-px-75" id="perihal" name="perihal" rows="3" placeholder="Perihal" required></textarea>
                <label for="perihal">Perihal</label>
                <div class="invalid-feedback"> Mohon masukan tujuan surat. </div>
            </div>

            <div class="mb-4 form-floating form-floating-outline">
                <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" id="tanggal-diterima" name="tanggal_diterima" required readonly/>
                <label for="tanggal_diterima">Pilih Tanggal Diterima</label>
              <div class="invalid-feedback"> Mohon pilih tanggal diterima. </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="judul_lampiran" placeholder="Judul Lampiran" name="judul_lampiran" />
                        <label for="judul_lampiran">Judul Lampiran</label>
                        <div class="invalid-feedback"> Mohon masukan judul lampiran. </div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="jumlah_lampiran" placeholder="Jumlah Lampiran" name="jumlah_lampiran" />
                        <label for="jumlah_lampiran">Jumlah Lampiran</label>
                        <div class="invalid-feedback"> Mohon masukan jumlah lampiran. </div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-floating form-floating-outline mb-4">
                        <select name="lampiran_type" class="form-select select2" id="lampiran_type">
                            <option value="">Pilih Tipe Lampiran</option>
                            <option value="bundle">Bundle</option>
                            <option value="lembar">Lembar</option>
                        </select>
                        <div class="invalid-feedback"> Mohon masukan tipe lampiran. </div>
                    </div>
                </div>
            </div>

            <div class="form-floating form-floating-outline mb-4">
              <textarea class="form-control h-px-75" id="catatan" name="catatan" rows="3" placeholder="Catatan"></textarea>
              <label for="catatan">Catatan</label>
            </div>

            <div class="form-group mb-4">
              <label for="formFile" class="form-label">Upload Dokumen Surat</label>
              <input class="form-control" type="file" id="file-surat" name="file_surat" required accept=".pdf">
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

<div class="row mb-4">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                Data Agenda Surat Masuk
            </div>
            <div class="card-body">
                @include('content.surat_masuk.data-list')
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-disposisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
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
                        <div class="form-floating form-floating-outline mb-4">
                            <select id="tujuan_disposisi" name="tujuan_disposisi[]" class="select2 form-select" multiple required>
                                <option value="">Pilih Tujuan Disposisi</option>
                                @foreach ($organization as $org)
                                    <option value="{{$org->id}}">{{$org->nama}}</option>
                                @endforeach
                            </select>
                            <label for="tujuan_disposisi">Tujuan Disposisi</label>
                        </div>
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

@endsection
