@extends('layouts/layoutMaster')

@section('title', 'Pricing - Pages')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-pricing.css')}}" />
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-pricing.js')}}"></script>
@endsection

@section('content')
<div class="card">
  <!-- Pricing Plans -->
  <div class="pb-sm-5 pb-2 rounded-top">
    <div class="container py-5">
      <h2 class="text-center mb-2 mt-0 mt-md-4">Pricing Plans</h2>
      <p class="text-center">All plans include 40+ advanced tools and features to boost your product. Choose the best plan to fit your needs.</p>
      <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 py-5 mb-0 mb-md-4">
        <label class="switch switch-primary ms-sm-5 ps-sm-5 me-0">
          <span class="switch-label">Monthly</span>
          <input type="checkbox" class="switch-input price-duration-toggler" checked />
          <span class="switch-toggle-slider">
            <span class="switch-on"></span>
            <span class="switch-off"></span>
          </span>
          <span class="switch-label">Annual</span>
        </label>
        <div class="mt-n5 ms-n5 ml-2 mb-2 d-none d-sm-flex align-items-center gap-2">
          <i class="mdi mdi-arrow-down-left mdi-24px text-muted scaleX-n1-rtl"></i>
          <span class="badge badge-sm bg-label-primary rounded-pill mb-2 ">Get 2 months free</span>
        </div>
      </div>

      <div class="pricing-plans row mx-0 gy-3 px-lg-5">
        <!-- Basic -->
        <div class="col-lg mb-md-0 mb-4">
          <div class="card border rounded shadow-none">
            <div class="card-body">
              <div class="my-3 pt-2 text-center">
                <img src="{{asset('assets/img/illustrations/pricing-basic.png')}}" alt="Basic Image" height="100">
              </div>
              <h3 class="card-title text-center text-capitalize mb-1">Basic</h3>
              <p class="text-center pb-2">A simple start for everyone</p>
              <div class="text-center">
                <div class="d-flex justify-content-center">
                  <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-body fw-normal">$</sup>
                  <h1 class="display-3 mb-0 text-primary">0</h1>
                  <sub class="h6 pricing-duration mt-auto mb-2 text-body fw-normal">/month</sub>
                </div>
              </div>

              <ul class="list-group ms-4 my-4 pt-2">
                <li class="mb-1">100 responses a month</li>
                <li class="mb-1">Unlimited forms and surveys</li>
                <li class="mb-1">Unlimited fields</li>
                <li class="mb-1">Basic form creation tools</li>
                <li class="mb-0">Up to 2 subdomains</li>
              </ul>

              <a href="{{url('auth/register-basic')}}" class="btn btn-outline-success d-grid w-100">Your Current Plan</a>
            </div>
          </div>
        </div>

        <!-- Standard -->
        <div class="col-lg mb-md-0 mb-4">
          <div class="card border-primary border shadow-none">
            <div class="card-body position-relative">
              <div class="position-absolute end-0 me-4 top-0 mt-4">
                <span class="badge bg-label-primary rounded-pill">Popular</span>
              </div>
              <div class="my-3 pt-2 text-center">
                <img src="{{asset('assets/img/illustrations/pricing-standard.png')}}" alt="Standard Image" height="100">
              </div>
              <h3 class="card-title text-center text-capitalize mb-1">Standard</h3>
              <p class="text-center">For small to medium businesses</p>
              <div class="text-center">
                <div class="d-flex justify-content-center">
                  <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-body fw-normal">$</sup>
                  <h1 class="price-toggle price-yearly display-3 text-primary mb-0">7</h1>
                  <h1 class="price-toggle price-monthly display-3 text-primary mb-0 d-none">9</h1>
                  <sub class="h6 text-body pricing-duration mt-auto mb-2 fw-normal">/month</sub>
                </div>
                <small class="price-yearly price-yearly-toggle text-body">$ 90 / year</small>
              </div>

              <ul class="list-group ms-4 my-4 pt-3">
                <li class="mb-1">Unlimited responses</li>
                <li class="mb-1">Unlimited forms and surveys</li>
                <li class="mb-1">Instagram profile page</li>
                <li class="mb-1">Google Docs integration</li>
                <li class="mb-0">Custom “Thank you” page</li>
              </ul>

              <a href="{{url('auth/register-basic')}}" class="btn btn-primary d-grid w-100">Upgrade</a>
            </div>
          </div>
        </div>

        <!-- Enterprise -->
        <div class="col-lg">
          <div class="card border rounded shadow-none">
            <div class="card-body">

              <div class="my-3 pt-2 text-center">
                <img src="{{asset('assets/img/illustrations/pricing-enterprise.png')}}" alt="Enterprise Image" height="100">
              </div>
              <h3 class="card-title text-center text-capitalize mb-1">Enterprise</h3>
              <p class="text-center">Solution for big organizations</p>

              <div class="text-center">
                <div class="d-flex justify-content-center">
                  <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-body fw-normal">$</sup>
                  <h1 class="price-toggle price-yearly display-3 text-primary mb-0">16</h1>
                  <h1 class="price-toggle price-monthly display-3 text-primary mb-0 d-none">19</h1>
                  <sub class="h6 pricing-duration mt-auto mb-2 fw-normal text-body">/month</sub>
                </div>
                <small class="price-yearly price-yearly-toggle text-body">$ 190 / year</small>
              </div>

              <ul class="list-group ms-4 my-4 pt-3">
                <li class="mb-1">PayPal payments</li>
                <li class="mb-1">Logic Jumps</li>
                <li class="mb-1">File upload with 5GB storage</li>
                <li class="mb-1">Custom domain support</li>
                <li class="mb-0">Stripe integration</li>
              </ul>

              <a href="{{url('auth/register-basic')}}" class="btn btn-outline-primary d-grid w-100">Upgrade</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Pricing Plans -->
</div>
@endsection
