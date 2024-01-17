@extends('layouts/layoutMaster')

@section('title', 'Surat Keluar - Buku Agenda')

@section('page-script')
    <script src="{{asset('assets/js/transaction/buku-agenda.js')}}"></script>
@endsection

@section('content')
<!-- Toast with Animation -->
<div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
    <div class="toast-header">
        <i class="mdi mdi-alert-cicrle-outline me-2"></i>
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
  <span class="text-muted fw-light">Surat Keluar /</span> Buku Agenda
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

<div class="card">
  <h5 class="card-header">Pencarian Surat Keluar</h5>
    <div class="card-body">
        <form action="javascript:void(0)" id="form-pencarian" class="needs-validation" novalidate>
        @csrf

            <div class="row mb-4 align-items-center">
                <div class="col-md-4 col-12 mb-4">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" name="tgl_surat" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="tgl-surat" />
                        <label for="tgl-surat">Tanggal Surat Keluar</label>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-4">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" id="nomor_agenda" name="nomor_agenda" placeholder="Nomor Agenda" />
                        <label for="nomor_agenda">Nomor Agenda</label>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-4">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" placeholder="Nomor Surat" />
                        <label for="nomor_surat">Nomor Surat</label>
                    </div>
                </div>

                <div class="col-md-6 col-12 mb-4">
                    <div class="form-floating form-floating-outline">
                        <select id="asal_surat" name="asal_surat" class="select2 form-select">
                            <option></option>
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
                        <label for="asal_surat">Asal Surat</label>
                    </div>
                </div>

                <div class="col-md-6 col-12 mb-4">
                    <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Perihal Surat" />
                    <label for="perihal">Perihal Surat</label>
                    </div>
                </div>


                {{-- <div class="col-md-4 col-12 mb-4">
                    <div class="form-floating form-floating-outline">
                        <select id="tujuan_surat" name="tujuan_surat" class="select2 form-select" required>
                            <option></option>
                            @foreach ($organization as $org)
                                <option value="{{$org->id}}">{{$org->nama}}</option>
                            @endforeach
                        </select>
                        <label for="tujuan_surat">Tujuan Surat</label>
                    </div>
                </div> --}}

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" id="btn-save" class="btn btn-primary"><span class="mdi mdi-book-search-outline"></span> Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mt-4" id="container-data" style="display: none">
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
