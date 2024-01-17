@extends('layouts/layoutMaster')

@section('title', 'Master Data - Organization')

@section('page-script')
    <script src="{{asset('assets/js/master-data/organization.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Master Data /</span> Organization
</h4>

<div class="row mt-4" id="container-data">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-12 col-md-6 order-md-2 order-last">
                        Data Organization
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <div class="float-start float-lg-end">
                            <a href="{{route('organization.create')}}" class="btn btn-outline-primary">Tambah Data</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatables-basic table table-bordered" id="table-list">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Leader Alias</th>
                                <th>Parent Organization</th>
                                <th>Description</th>
                                <th>Template Blanko Disposisi</th>
                                <th>Suffix No. Agenda</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
