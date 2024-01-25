@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login Basic - Pages')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
<style>
  .authentication-basic {
    /* background: url('../../assets/img/backgrounds/propam.png');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 100vh;
    width: 100%; */
    background-color: #00004d;
  }

  .authentication-inner {
    margin-right: 0;
  }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
{{-- <script src="{{asset('assets/js/pages-auth.js')}}"></script> --}}
<script src="{{asset('assets/js/auth/login.js')}}"></script>
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

<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Login -->
      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="{{url('/')}}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros',["width"=>100,"withbg"=>'var(--bs-primary)'])</span>
            {{-- <span class="app-brand-text demo text-heading fw-bold">E-office</span> --}}
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2">
          <h4 class="mb-2">Selamat datang! 👋</h4>
          <p class="mb-4">Silakan masuk menggunakan akun anda</p>

          <form id="formAuthentication" class="needs-validation" novalidate>
            @csrf
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="email" name="username" placeholder="Masukan username" autofocus>
              <label for="email">Username</label>
            </div>
            <div class="mb-3">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-3 d-flex justify-content-between">
              <a href="{{url('auth/forgot-password-basic')}}" class="float-end mb-1">
                <span>Lupa Password?</span>
              </a>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>

          {{-- <div class="divider my-4">
            <div class="divider-text">or</div>
          </div> --}}
        </div>
      </div>
      <!-- /Login -->
    </div>
  </div>
</div>
@endsection
