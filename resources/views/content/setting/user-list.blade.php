@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
<style>
  .light-style table.dataTable thead th,
  .light-style table.dataTable thead td,
  .light-style table.dataTable thead tr, {
    border-bottom: 1px solid;
  }

  .light-style table.dataTable tbody th, .light-style table.dataTable tbody td, .light-style table.dataTable tbody tr {
    border-bottom: 1px solid;
    border-radius: 50px;
  }
</style>

@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
@endsection

@section('page-script')
{{-- <script src="{{asset('assets/js/app-user-list.js')}}"></script> --}}
<script src="{{asset('assets/js/setting/user.js')}}"></script>
@endsection

@section('content')
<!-- Toast with Animation -->
<div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
  <div class="toast-header">
      <i class="mdi mdi-alert-circle-outline me-2"></i>
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
  <span class="text-muted fw-light">Pengaturan /</span> Daftar User
</h4>

{{-- <div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="me-1">
            <p class="text-heading mb-2">Session</p>
            <div class="d-flex align-items-center">
              <h4 class="mb-2 me-1 display-6">{{count($users)}}</h4>
              <p class="text-success mb-2">(+29%)</p>
            </div>
            <p class="mb-0">Total Users</p>
          </div>
          <div class="avatar">
            <div class="avatar-initial bg-label-primary rounded">
              <div class="mdi mdi-account-outline mdi-24px"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="me-1">
            <p class="text-heading mb-2">Paid Users</p>
            <div class="d-flex align-items-center">
              <h4 class="mb-2 me-1 display-6">4,567</h4>
              <p class="text-success mb-2">(+18%)</p>
            </div>
            <p class="mb-0">Last week analytics</p>
          </div>
          <div class="avatar">
            <div class="avatar-initial bg-label-danger rounded">
              <div class="mdi mdi-account-plus-outline mdi-24px scaleX-n1"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="me-1">
            <p class="text-heading mb-2">Active Users</p>
            <div class="d-flex align-items-center">
              <h4 class="mb-2 me-1 display-6">19,860</h4>
              <p class="text-danger mb-2">(-14%)</p>
            </div>
            <p class="mb-0">Last week analytics</p>
          </div>
          <div class="avatar">
            <div class="avatar-initial bg-label-success rounded">
              <div class="mdi mdi-account-check-outline mdi-24px"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="me-1">
            <p class="text-heading mb-2">Pending Users</p>
            <div class="d-flex align-items-center">
              <h4 class="mb-2 me-1 display-6">237</h4>
              <p class="text-success mb-2">(+42%)</p>
            </div>
            <p class="mb-0">Last week analytics</p>
          </div>
          <div class="avatar">
            <div class="avatar-initial bg-label-warning rounded">
              <div class="mdi mdi-account-search mdi-24px"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div> --}}

<!-- Users List Table -->
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
      <div class="col-md-4 user_role"></div>
      <div class="col-md-4 user_plan"></div>
      <div class="col-md-4 user_status"></div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-users table">
      <thead class="table-light">
        <tr>
          <th></th>
          {{-- <th></th> --}}
          {{-- <th>Email</th> --}}
          <th>Nama</th>
          <th>Username</th>
          <th>Organization</th>
          <th>Jabatan</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>

  <!-- Offcanvas to add new user -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 h-100">
      <form class="browser-default-validation" action="javascript:void(0)" id="form-tambah-user">
        @csrf
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-user-fullname" placeholder="Nama" name="name" aria-label="Nama" style="text-transform:uppercase"/>
          <label for="add-user-fullname">Nama</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="username" id="username" class="form-control" placeholder="Username" aria-label="subbagbinfung" name="username" />
          <label for="username">Username</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="email" id="add-user-email" class="form-control" placeholder="test@example.com" aria-label="test@example.com" name="email" />
          <label for="email">Email</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select id="organization" class="select2 form-select" name="organization">
            <option value="">Select</option>
            @foreach ($organization as $org)
                <option value="{{$org->id}}">{{ $org->nama }}</option>
            @endforeach
          </select>
          <label for="organization">Organization</label>
        </div>

        {{-- <div class="form-floating form-floating-outline mb-4">
          <select id="user-role" class="form-select" name="role">
            <option value="">Pilih Role</option>
            @foreach ($roles as $role)
                <option value="{{$role->id}}">{{ $role->name }}</option>
            @endforeach
          </select>
          <label for="user-role">User Role</label>
        </div> --}}

        <div class="form-floating form-floating-outline mb-4">
          <input type="text" id="jabatan" class="form-control" placeholder="Jabatan" aria-label="jdoe1" name="jabatan" style="text-transform:uppercase" />
          <label for="jabatan">Jabatan</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
  
  <!-- Offcanvas to edit new user -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Edit User</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 h-100">
      <form class="browser-default-validation" action="javascript:void(0)" id="form-edit-user">
        @csrf
        <input type="hidden" name="id_user" id="idUser" value="">
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="edit-user-fullname" placeholder="Nama" name="name" aria-label="Nama" style="text-transform:uppercase"/>
          <label for="add-user-fullname">Nama</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="username" id="edit-username" class="form-control" placeholder="Username" aria-label="subbagbinfung" name="username" />
          <label for="username">Username</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="email" id="edit-user-email" class="form-control" placeholder="test@example.com" aria-label="test@example.com" name="email" />
          <label for="email">Email</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select id="edit-organization" class="select2 form-select" name="organization">
            <option value="">Select</option>
            @foreach ($organization as $org)
                <option value="{{$org->id}}">{{ $org->nama }}</option>
            @endforeach
          </select>
          <label for="edit-organization">Organization</label>
        </div>

        {{-- <div class="form-floating form-floating-outline mb-4">
          <select id="user-role" class="form-select" name="role">
            <option value="">Pilih Role</option>
            @foreach ($roles as $role)
                <option value="{{$role->id}}">{{ $role->name }}</option>
            @endforeach
          </select>
          <label for="user-role">User Role</label>
        </div> --}}

        <div class="form-floating form-floating-outline mb-4">
          <input type="text" id="edit-jabatan" class="form-control" placeholder="Jabatan" aria-label="jdoe1" name="jabatan" style="text-transform:uppercase" />
          <label for="jabatan">Jabatan</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>

</div>
@endsection
