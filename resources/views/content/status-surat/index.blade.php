@extends('layouts/layoutMaster')

@section('title', 'Master Data - Status Surat')

@section('page-script')
    <script src="{{asset('assets/js/master-data/status-surat.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Master Data /</span> Status Surat
</h4>

<div class="row mt-4" id="container-data">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                Data Organization
            </div>
            <div class="card-body">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatables-basic table table-bordered" id="table-list">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Nama Status</th>
                                <th>Deskripsi</th>
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
