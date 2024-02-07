@extends('layouts/layoutMaster')
@section('title', 'Log Surat Masuk - Timeline')

@section('page-script')
    <script src="{{ asset('assets/js/transaction/log-surat-masuk.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Log Surat Masuk /</span> Timeline
    </h4>

    <div class="row mt-4">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <div class="card text-white bg-primary rounded-pills">
                        <div class="card-body">
                            <h5 class="card-title text-white">Informasi Data Surat</h5>
                            <hr style="border: none; height: 2px; width: 20%; color: #fff; background-color: #fff; margin-top:-0.5rem">

                            <div class="row justify-content-center align-items-center" id="header-data">
                                <div class="col-md-4 col-12 mb-2">No. Surat</div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <a href=' {{route('showPDF', base64_encode($suratMasuk->tx_number))}}' target='_blank' class='badge rounded-pill bg-label-info'>
                                        {{$suratMasuk->no_surat}}
                                    </a>
                                </div>

                                <div class="col-md-4 col-12 mb-2">No. Agenda</div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <b id="no_agenda">
                                        {{$suratMasuk->no_agenda}}
                                    </b>
                                </div>

                                <div class="col-md-4 col-12 mb-2">
                                    Tanggal Surat
                                </div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <span id="tgl_surat">
                                        {{\Carbon\Carbon::parse($suratMasuk->tgl_surat)->translatedFormat('d F Y')}}
                                    </span>
                                </div>

                                <div class="col-md-4 col-12 mb-2">
                                    Tanggal Diterima
                                </div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <span id="tgl_diterima">
                                        {{\Carbon\Carbon::parse($suratMasuk->tgl_diterima)->translatedFormat('d F Y')}}
                                    </span>
                                </div>

                                <div class="col-md-4 col-12 mb-2"> Tujuan Surat </div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <span id="tujuan_surat">
                                        {{$suratMasuk->tujuanSurat->nama}}
                                    </span>
                                </div>

                                <div class="col-md-4 col-12 mb-2"> Perihal </div>
                                <div class="col-md-1 col-12 mb-2"> : </div>
                                <div class="col-md-7 col-12 mb-2">
                                    <span id="perihal">
                                        {{$suratMasuk->perihal}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card bg-white rounded-pills mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Timeline Surat</h5>
                            <hr style="border: none; height: 2px; width: 20%; color: #666CFF; background-color: #666CFF; margin-top:-0.5rem">
                            <ul class="timeline timeline-center mt-5">
                                @foreach ($timeline as $tml)
                                    <li class="timeline-item">
                                        <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                                            <i class="mdi mdi-book-information-variant"></i>
                                        </span>
                                        <div class="timeline-event card p-0" data-aos="fade-right">
                                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="card-title mb-0">{{$tml->status}}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                    <div>
                                                        <p class="text-muted mb-2">User yang Melakukan</p>
                                                        <ul class="list-unstyled users-list d-flex align-items-center avatar-group">
                                                            <span>{{$tml->process_user}}</span>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <p class="text-muted mb-2">Tanggal dilakukan proses</p>
                                                        <ul class="list-unstyled users-list d-flex align-items-center avatar-group">
                                                            <span>{{\Carbon\Carbon::parse($tml->created_at)->translatedFormat('d F Y H:i:s')}}</span>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-event-time ml-5 mr-5">
                                                {{\Carbon\Carbon::parse($tml->process_date)->translatedFormat('d M Y')}}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
