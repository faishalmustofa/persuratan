@extends('layouts/layoutMaster')

@section('title', 'Status Surat - Ubah Data')

@section('vendor-style')
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
    <script src="{{asset('assets/js/master-data/status-surat.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Master Data / Status Surat / </span> Ubah Data
</h4>

<div class="row mt-4">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                Ubah Data Status SUrat
            </div>
            <div class="card-body">
                <form action="javascript:void(0)" id="form-update" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" value="{{$status->id}}">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 col-md-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="name" placeholder="Nama Status" name="name" required value="{{$status->name}}" />
                                <label for="name">Nama Status</label>
                                <div class="invalid-feedback"> Mohon masukan Nama Status. </div>
                            </div>
                        </div>

                        <div class="form-floating form-floating-outline mb-4">
                            <select id="tipe_surat" name="tipe_surat" class="select2 form-select" required>
                                <option value="">Pilih Tipe Surat</option>
                                <option value="masuk" {{ $status->tipe_surat == 'masuk' ? 'selected' : '' }}>Masuk</option>
                                <option value="keluar"{{ $status->tipe_surat == 'keluar' ? 'selected' : '' }}>Keluar</option>
                            </select>
                            <div class="invalid-feedback"> Mohon pilih tipe surat. </div>
                        </div>
                        
                        <div class="col-12 col-md-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="number" class="form-control" id="kode_status" placeholder="Nama Status" name="kode_status" required />
                                <label for="kode_status">Kode Status</label>
                                <div class="invalid-feedback"> Mohon masukan kode status. </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control h-px-75" id="description" name="description" rows="3" placeholder="Deskripsi">{{$status->description}}</textarea>
                                <label for="description">Deskripsi</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" id="btn-save" class="btn btn-outline-primary"> Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
