@extends('layouts/layoutMaster')

@section('title', 'Organization - Rubah Data')

@section('page-script')
    <script src="{{asset('assets/js/master-data/organization.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Master Data / Organization / </span> Rubah Data
</h4>

<div class="row mt-4">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                Rubah Data Organization
            </div>
            <div class="card-body">
                <form action="javascript:void(0)" id="form-update" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" value="{{$org->id}}">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="nama" placeholder="Nama Satker" name="nama" required value="{{$org->nama}}" />
                                <label for="nama">Nama Satker</label>
                                <div class="invalid-feedback"> Mohon masukan Nama Satker. </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="leader_alias" placeholder="Nama Alias Pimpinan" name="leader_alias" required value="{{$org->leader_alias}}" />
                                <label for="leader_alias">Nama Alias Pimpinan</label>
                                <div class="invalid-feedback"> Mohon masukan Nama Alias Pimpinan. </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <select id="parent_id" name="parent_id" class="form-select select2" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach ($orgAll as $item)
                                        <option value="{{$item->id}}" {{$org->parent_id == $item->id ? 'selected' : ''}} >{{$item->nama}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"> Mohon pilih Parent Satker. </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="suffix_agenda" placeholder="Suffix Nomor Agenda" name="suffix_agenda" required value="{{$org->suffix_agenda}}" />
                                <label for="suffix_agenda">Suffix Nomor Agenda</label>
                                <div class="invalid-feedback"> Mohon masukan Suffix Nomor Agenda. </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control h-px-75" id="description" name="description" rows="3" placeholder="Deskripsi">{{$org->description}}</textarea>
                                <label for="description">Deskripsi</label>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            @if ($org->blanko_path == null)
                                <div class="form-group mb-4">
                                    <label class="form-label">Upload Template Blanko Disposisi</label>
                                    <input class="form-control" type="file" id="blanko_path" name="blanko_path" accept=".docx">
                                </div>
                            @endif
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
