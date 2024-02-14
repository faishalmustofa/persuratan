@extends('layouts/layoutMaster')
@section('title', 'Surat Masuk - Disposisi')

@section('page-script')
    <script src="{{ asset('assets/js/transaction/disposisi.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Surat Masuk /</span> Disposisi
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

    <div class="card" style="display: none">
        <h5 class="card-header">Pencarian Berdasarkan Nomor Agenda</h5>
        <div class="card-body">
            <form action="javascript:void(0)" id="form-pencarian" class="needs-validation" novalidate>
                @csrf

                <div class="row mb-4 align-items-center">
                    <div class="col-md-4 col-12 mb-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="nomor_agenda"
                                placeholder="Nomor Agenda" />
                            <label for="nomor_agenda">Nomor Agenda</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" id="btn-save" class="btn btn-primary"><span
                                class="mdi mdi-book-search-outline"></span> Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-4" id="container-data">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-6 order-md-2 order-last">
                            Data Agenda Surat Masuk
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <div class="float-start float-lg-end">
                                <button class="btn btn-sm btn-outline-primary" onclick="bulkingSurat()">Kirim Berkas (Bundle)</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="nomor_agenda" name="noAgenda" value="{{$noAgenda}}">
                    @include('content.surat_masuk.data-list')
                </div>
            </div>
        </div>
    </div>

    @include('content.surat_masuk.modal')
@endsection
