@extends('layouts/layoutMaster')

@section('title',isset($jenis_surat) ? 'Jenis Surat - Edit Data' : 'Jenis Surat - Tambah Data')

@section('page-script')
    <script src="{{asset('assets/js/master-data/jenis-surat.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Master Data / Jenis Surat / </span> {{ isset($jenis_surat) ? 'Edit' : 'Tambah' }} Data
</h4>

<div class="row mt-4">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                {{ isset($jenis_surat) ? 'Edit' : 'Tambah' }} Jenis Surat
            </div>
            <div class="card-body">
                @if (isset($jenis_surat))
                <form action="javascript:void(0)" id="form-update" class="needs-validation" novalidate>
                @else
                <form action="javascript:void(0)" id="form-add" class="needs-validation" novalidate>
                @endif
                    @csrf
                    @if (isset($jenis_surat))
                        <input type="hidden" name="id" value="{{ $jenis_surat->id }}">
                    @endif
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="nama" placeholder="Nama Surat" name="nama" value="{{ isset($jenis_surat) ? $jenis_surat->nama : '' }}" required />
                                <label for="nama">Nama Surat</label>
                                <div class="invalid-feedback"> Mohon masukan Nama Surat. </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="format" placeholder="Format Surat" name="format" value="{{ isset($jenis_surat) ? $jenis_surat->format : '' }}" required />
                                <label for="format">Format Surat</label>
                                <div class="invalid-feedback"> Mohon masukan Format Surat. </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control h-px-75" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi">{{ isset($jenis_surat) ? $jenis_surat->deskripsi : '' }}</textarea>
                                <label for="deskripsi">Deskripsi</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            @if (isset($jenis_surat))
                                <button type="submit" id="btn-save" class="btn btn-outline-warning"> Update Data</button>
                            @else
                                <button type="submit" id="btn-save" class="btn btn-outline-primary"> Simpan Data</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
