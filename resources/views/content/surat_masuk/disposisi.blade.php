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
                    Data Agenda Surat Masuk
                </div>
                <div class="card-body">
                    <input type="hidden" id="nomor_agenda" name="noAgenda" value="{{$noAgenda}}">
                    @include('content.surat_masuk.data-list')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Disposisi -->
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

    <!-- Modal Kirim Surat -->
    <div class="modal fade" id="modal-kirim" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Form Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="javascript:void(0)" id="form-pengiriman" class="needs-validation" novalidate>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="tx_number" class="form-control">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Agenda" name="nomor_agenda" value="" readonly />
                                    <label for="nomor_agenda">Nomor Agenda</label>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="no_surat" placeholder="Nomor Surat" name="no_surat" value="" readonly />
                                    <label for="no_surat">Nomor Surat</label>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-4 col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="jenis_pengiriman" placeholder="Jenis Pengiriman" name="jenis_pengiriman" value="" required />
                                    <label for="jenis_pengiriman">Jenis Pengiriman</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="expedisi" placeholder="Expedisi" name="expedisi" value="" />
                                    <label for="expedisi">Expedisi</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="no_resi" placeholder="no_resi" name="no_resi" value="" />
                                    <label for="no_resi">No. Resi</label>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-6 col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="nama_pengirim" placeholder="nama_pengirim" name="nama_pengirim" value="" required />
                                    <label for="nama_pengirim">Nama Pengirim</label>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" placeholder="YYYY-MM-DD HH:MM" name="tgl_kirim" id="tgl_kirim" required />
                                    <label for="tgl_kirim">Tanggal Pengiriman</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Input Pengiriman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
