@extends('layouts/layoutMaster')

@section('title', 'Status Surat - Tambah Data')

@section('page-script')
    <script src="{{asset('assets/js/master-data/status-surat.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Master Data / Status Surat / </span> Tambah Data
</h4>

<div class="row mt-4">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                Tambah Data Status Surat
            </div>
            <div class="card-body">
                <form action="javascript:void(0)" id="form-add" class="needs-validation" novalidate>
                    @csrf
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="name" placeholder="Nama Status" name="name" required />
                                <label for="name">Nama Status</label>
                                <div class="invalid-feedback"> Mohon masukan Nama Status. </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control h-px-75" id="description" name="description" rows="3" placeholder="Deskripsi"></textarea>
                                <label for="description">Deskripsi</label>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" id="btn-save" class="btn btn-outline-primary"> Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
