<!-- Checkout Wizard -->
<div id="wizard-checkout" class="bs-stepper wizard-icons wizard-icons-example mt-2">
  <div class="bs-stepper-header m-auto border-0">
    <div class="step" data-target="#checkout-cart">
      <button type="button" class="step-trigger">
        <span class="bs-stepper-icon">
          <svg viewBox="0 0 58 54">
            <use xlink:href="{{asset('assets/svg/icons/wizard-checkout-cart.svg#wizardCart')}}"></use>
          </svg>
        </span>
        <span class="bs-stepper-label">Cart</span>
      </button>
    </div>
    <div class="line">
      <i class="mdi mdi-chevron-right"></i>
    </div>
    <div class="step" data-target="#checkout-address">
      <button type="button" class="step-trigger">
        <span class="bs-stepper-icon">
          <svg viewBox="0 0 54 54">
            <use xlink:href="{{asset('assets/svg/icons/wizard-checkout-address.svg#wizardCheckoutAddress')}}"></use>
          </svg>
        </span>
        <span class="bs-stepper-label">Address</span>
      </button>
    </div>
    <div class="line">
      <i class="mdi mdi-chevron-right"></i>
    </div>
    <div class="step" data-target="#checkout-payment">
      <button type="button" class="step-trigger">
        <span class="bs-stepper-icon">
          <svg viewBox="0 0 58 54">
            <use xlink:href="{{asset('assets/svg/icons/wizard-checkout-payment.svg#wizardPayment')}}"></use>
          </svg>
        </span>
        <span class="bs-stepper-label">Payment</span>
      </button>
    </div>
    <div class="line">
      <i class="mdi mdi-chevron-right"></i>
    </div>
    <div class="step" data-target="#checkout-confirmation">
      <button type="button" class="step-trigger">
        <span class="bs-stepper-icon">
          <svg viewBox="0 0 58 54">
            <use xlink:href="{{asset('assets/svg/icons/wizard-checkout-confirmation.svg#wizardConfirm')}}"></use>
          </svg>
        </span>
        <span class="bs-stepper-label">Confirmation</span>
      </button>
    </div>
  </div>
  <div class="bs-stepper-content border-top rounded-0">
    <form id="wizard-checkout-form" onSubmit="return false">

      <!-- Cart -->
      <div id="checkout-cart" class="content">
        <div class="row">
          <!-- Cart left -->
          <div class="col-xl-8 mb-3 mb-xl-0">

            <!-- Offer alert -->
            <div class="alert alert-success mb-4" role="alert">
              <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                  <i class="mdi mdi-check-circle-outline mdi-24px"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-medium">Available Offers</div>
                  <ul class="list-unstyled mb-0">
                    <li> - 10% Instant Discount on Bank of America Corp Bank Debit and Credit cards</li>
                    <li> - 25% Cashback Voucher of up to $60 on first ever PayPal transaction. TCA</li>
                  </ul>
                </div>
              </div>
              <button type="button" class="btn-close btn-pinned" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Shopping bag -->
            <h5>My Shopping Bag (2 Items)</h5>
            <ul class="list-group mb-3">
              <li class="list-group-item p-4">
                <div class="d-flex gap-3">
                  <div class="flex-shrink-0">
                    <img src="{{asset('assets/img/products/1.png')}}" alt="google home" class="w-px-100">
                  </div>
                  <div class="flex-grow-1">
                    <div class="row">
                      <div class="col-md-8">
                        <h6 class="me-3"><a href="javascript:void(0)" class="text-heading">Google - Google Home - White</a></h6>
                        <div class="mb-1 d-flex flex-wrap"><span class="me-1">Sold by:</span> <a href="javascript:void(0)" class="me-1">Google</a> <span class="badge bg-label-success rounded-pill">In Stock</span></div>
                        <div class="read-only-ratings mb-2 px-0" data-rateyo-read-only="true"></div>
                        <input type="number" class="form-control form-control-sm w-px-100 mt-4" value="1" min="1" max="5">
                      </div>
                      <div class="col-md-4">
                        <div class="text-md-end">
                          <button type="button" class="btn-close btn-pinned" aria-label="Close"></button>
                          <div class="my-2 mt-md-4 mb-md-5"><span class="text-primary">$299/</span><span class="text-body">$359</span></div>
                          <button type="button" class="btn btn-sm btn-outline-primary mt-3">Move to wishlist</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item p-4">
                <div class="d-flex gap-3">
                  <div class="flex-shrink-0">
                    <img src="{{asset('assets/img/products/2.png')}}" alt="google home" class="w-px-100">
                  </div>
                  <div class="flex-grow-1">
                    <div class="row">
                      <div class="col-md-8">
                        <h6 class="me-3"><a href="javascript:void(0)" class="text-heading">Apple iPhone 11 (64GB, Black)</a></h6>
                        <div class="mb-1 d-flex flex-wrap"><span class="me-1">Sold by:</span> <a href="javascript:void(0)" class="me-1">Apple</a> <span class="badge bg-label-success rounded-pill">In Stock</span></div>
                        <div class="read-only-ratings mb-2 px-0" data-rateyo-read-only="true"></div>
                        <input type="number" class="form-control form-control-sm w-px-100 mt-4" value="1" min="1" max="5">
                      </div>
                      <div class="col-md-4">
                        <div class="text-md-end">
                          <button type="button" class="btn-close btn-pinned" aria-label="Close"></button>
                          <div class="my-2 mt-md-4 mb-md-5"><span class="text-primary">$899/</span><span class="text-body">$999</span></div>
                          <button type="button" class="btn btn-sm btn-outline-primary mt-3">Move to wishlist</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>

            <!-- Wishlist -->
            <div class="list-group">
              <a href="javascript:void(0)" class="list-group-item d-flex justify-content-between">
                <span class="text-primary">Add more products from wishlist</span>
                <i class="mdi mdi-chevron-right lh-sm scaleX-n1-rtl"></i>
              </a>
            </div>
          </div>

          <!-- Cart right -->
          <div class="col-xl-4">
            <div class="border rounded p-3 mb-3">

              <!-- Offer -->
              <h6>Offer</h6>
              <div class="row g-3 mb-3">
                <div class="col-sm-8 col-xxl-8 col-xl-12">
                  <input type="text" class="form-control" placeholder="Enter Promo Code" aria-label="Enter Promo Code">
                </div>
                <div class="col-4 col-xxl-4 col-xl-12">
                  <div class="d-grid">
                    <button type="button" class="btn btn-outline-primary">Apply</button>
                  </div>
                </div>
              </div>

              <!-- Gift wrap -->
              <div class="bg-lighter rounded p-3">
                <h6>Buying gift for a loved one?</h6>
                <p>Gift wrap and personalized message on card, Only for $2.</p>
                <a href="javascript:void(0)" class="fw-medium">Add a gift wrap</a>
              </div>
              <hr class="mx-n3">

              <!-- Price Details -->
              <h6 class="mb-4">Price Details</h6>
              <dl class="row mb-0">
                <dt class="col-6 fw-normal text-heading">Bag Total</dt>
                <dd class="col-6 text-end">$1198.00</dd>

                <dt class="col-6 fw-normal text-heading">Coupon Discount</dt>
                <dd class="col-6 text-primary text-end fw-medium">Apply Coupon</dd>

                <dt class="col-6 fw-normal text-heading">Order Total</dt>
                <dd class="col-6 text-end">$1198.00</dd>

                <dt class="col-6 fw-normal text-heading">Delivery Charges</dt>
                <dd class="col-6 text-end"><s class="text-muted">$5.00</s> <span class="badge bg-label-success rounded-pill">Free</span></dd>

              </dl>
              <hr class="mx-n3 my-3">
              <dl class="row mb-0 h6">
                <dt class="col-6 mb-0">Total</dt>
                <dd class="col-6 text-end mb-0">$1198.00</dd>
              </dl>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary btn-next">Place Order</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Address -->
      <div id="checkout-address" class="content">
        <div class="row">
          <!-- Address left -->
          <div class="col-xl-8 mb-3 mb-xl-0">

            <!-- Select address -->
            <p class="text-heading">Select your preferable address</p>
            <div class="row mb-3">
              <div class="col-md mb-md-0 mb-2">
                <div class="form-check custom-option custom-option-basic checked">
                  <label class="form-check-label custom-option-content" for="customRadioAddress1">
                    <input name="customRadioTemp" class="form-check-input" type="radio" value="" id="customRadioAddress1" checked="">
                    <span class="custom-option-header mb-2">
                      <span class="h6 mb-0">John Doe (Default)</span>
                      <span class="badge bg-label-primary rounded-pill">Home</span>
                    </span>
                    <span class="custom-option-body">
                      <small class="d-xxl-block pe-xxl-5 me-xxl-5">4135 Parkway Street, Los Angeles, CA, 90017.<br /> Mobile : 1234567890 Cash / Card on delivery available</small>
                      <span class="my-2 border-bottom d-block"></span>
                      <span class="d-flex">
                        <a class="me-3" href="javascript:void(0)">Edit</a> <a href="javascript:void(0)">Remove</a>
                      </span>
                    </span>
                  </label>
                </div>
              </div>
              <div class="col-md">
                <div class="form-check custom-option custom-option-basic">
                  <label class="form-check-label custom-option-content" for="customRadioAddress2">
                    <input name="customRadioTemp" class="form-check-input" type="radio" value="" id="customRadioAddress2">
                    <span class="custom-option-header mb-2">
                      <span class="h6 mb-0">ACME Inc.</span>
                      <span class="badge bg-label-success rounded-pill">Office</span>
                    </span>
                    <span class="custom-option-body">
                      <small class="d-xxl-block pe-xxl-5 me-xxl-5">8723 Schoffman Avenue, New York, NY, 10016.<br />Mobile : 1234567890 Cash / Card on delivery available</small>
                      <span class="my-2 border-bottom d-block"></span>
                      <span class="d-flex">
                        <a class="me-3" href="javascript:void(0)">Edit</a> <a href="javascript:void(0)">Remove</a>
                      </span>
                    </span>
                  </label>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-outline-primary mb-4" data-bs-toggle="modal" data-bs-target="#addNewAddress">Add new address</button>

            <!-- Choose Delivery -->
            <p class="mt-2 text-heading">Choose Delivery Speed</p>
            <div class="row">
              <div class="col-md mb-md-0 mb-2">
                <div class="form-check custom-option custom-option-icon position-relative checked">
                  <label class="form-check-label custom-option-content" for="customRadioDelivery1">
                    <span class="custom-option-body">
                      <i class="mdi mdi-account-outline text-body"></i>
                      <span class="custom-option-title mb-2">Standard</span>
                      <span class="badge bg-label-success btn-pinned rounded-pill">FREE</span>
                      <small>Get your product in 1 Week.</small>
                    </span>
                    <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="customRadioDelivery1" checked="">
                  </label>
                </div>
              </div>
              <div class="col-md mb-md-0 mb-2">
                <div class="form-check custom-option custom-option-icon position-relative">
                  <label class="form-check-label custom-option-content" for="customRadioDelivery2">
                    <span class="custom-option-body">
                      <i class="mdi mdi-star-outline text-body"></i>
                      <span class="custom-option-title mb-2">Express</span>
                      <span class="badge bg-label-secondary btn-pinned rounded-pill">$10</span>
                      <small>Get your product in 3-4 days.</small>
                    </span>
                    <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="customRadioDelivery2">
                  </label>
                </div>
              </div>
              <div class="col-md">
                <div class="form-check custom-option custom-option-icon position-relative">
                  <label class="form-check-label custom-option-content" for="customRadioDelivery3">
                    <span class="custom-option-body">
                      <i class="mdi mdi-crown-outline text-body"></i>
                      <span class="custom-option-title mb-2">Overnight</span>
                      <span class="badge bg-label-secondary btn-pinned rounded-pill">$15</span>
                      <small>Get your product in 0-1 days.</small>
                    </span>
                    <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="customRadioDelivery3">
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Address right -->
          <div class="col-xl-4">
            <div class="border rounded p-3 mb-3">

              <!-- Estimated Delivery -->
              <h6 class="mb-4">Estimated Delivery Date</h6>
              <ul class="list-unstyled">
                <li class="d-flex gap-3 mb-2">
                  <div class="flex-shrink-0">
                    <img src="{{asset('assets/img/products/1.png')}}" alt="google home" class="w-px-50">
                  </div>
                  <div class="flex-grow-1">
                    <p class="mb-0"><a class="text-body" href="javascript:void(0)">Google - Google Home - White</a></p>
                    <p class="fw-medium">18th Nov 2021</p>
                  </div>
                </li>
                <li class="d-flex gap-3">
                  <div class="flex-shrink-0">
                    <img src="{{asset('assets/img/products/2.png')}}" alt="google home" class="w-px-50">
                  </div>
                  <div class="flex-grow-1">
                    <p class="mb-0"><a class="text-body" href="javascript:void(0)">Apple iPhone 11 (64GB, Black)</a></p>
                    <p class="fw-medium">20th Nov 2021</p>
                  </div>
                </li>
              </ul>

              <hr class="mx-n3 mt-2">

              <!-- Price Details -->
              <h6>Price Details</h6>
              <dl class="row mb-0">

                <dt class="col-6 fw-normal text-heading">Order Total</dt>
                <dd class="col-6 text-end">$1198.00</dd>

                <dt class="col-6 fw-normal text-heading">Delivery Charges</dt>
                <dd class="col-6 text-end"><s class="text-muted">$5.00</s> <span class="badge bg-label-success rounded-pill">Free</span></dd>

              </dl>
              <hr class="mx-n3">
              <dl class="row h6 mb-0">
                <dt class="col-6 mb-0">Total</dt>
                <dd class="col-6 text-end mb-0">$1198.00</dd>
              </dl>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary btn-next">Place Order</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment -->
      <div id="checkout-payment" class="content">
        <div class="row">
          <!-- Payment left -->
          <div class="col-xl-8 mb-3 mb-xl-0">
            <!-- Offer alert -->
            <div class="alert alert-success" role="alert">
              <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                  <i class="mdi mdi-check-circle-outline mdi-24px"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-medium">Available Offer</div>
                  <ul class="list-unstyled mb-0">
                    <li> - 0% Instant Discount on Bank of America Corp Bank Debit and Credit cards</li>
                    <li> - 50% Cashback Voucher of up to $60 on first ever PayPal transaction. TCA</li>
                  </ul>
                </div>
              </div>
              <button type="button" class="btn-close btn-pinned" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Payment Tabs -->
            <div class="col-xxl-6 col-lg-8">
              <ul class="nav nav-pills mb-3" id="paymentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-cc-tab" data-bs-toggle="pill" data-bs-target="#pills-cc" type="button" role="tab" aria-controls="pills-cc" aria-selected="true">Card</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-cod-tab" data-bs-toggle="pill" data-bs-target="#pills-cod" type="button" role="tab" aria-controls="pills-cod" aria-selected="false">Cash On Delivery</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-gift-card-tab" data-bs-toggle="pill" data-bs-target="#pills-gift-card" type="button" role="tab" aria-controls="pills-gift-card" aria-selected="false">Gift Card</button>
                </li>
              </ul>
              <div class="tab-content px-0" id="paymentTabsContent">
                <!-- Credit card -->
                <div class="tab-pane fade show active" id="pills-cc" role="tabpanel" aria-labelledby="pills-cc-tab">
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                          <input id="paymentCard" name="paymentCard" class="form-control credit-card-mask" type="text" placeholder="1356 3215 6548 7898" aria-describedby="paymentCard2" />
                          <label for="paymentCard">Card Number</label>
                        </div>
                        <span class="input-group-text w-px-75" id="paymentCard2"><span class="card-type"></span></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <div class="form-floating form-floating-outline">
                        <input id="paymentCardName" name="paymentCardName" class="form-control" type="text" placeholder="John Doe" />
                        <label for="paymentCardName">Name</label>
                      </div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="form-floating form-floating-outline">
                        <input id="paymentCardExpiryDate" name="paymentCardExpiryDate" class="form-control expiry-date-mask" type="text" placeholder="MM/YY" />
                        <label for="paymentCardExpiryDate">Expiry Date</label>
                      </div>
                    </div>
                    <div class="col-6 col-md-3">
                      <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                          <input type="text" id="paymentCardCvv" class="form-control cvv-code-mask" maxlength="3" placeholder="654" />
                          <label for="paymentCardCvv">CVV</label>
                        </div>
                        <span class="input-group-text cursor-pointer" id="paymentCardCvv2"><i class="mdi mdi-help-circle-outline text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Card Verification Value"></i></span>
                      </div>
                    </div>
                    <div class="col-12">
                      <label class="switch">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                          <span class="switch-on"></span>
                          <span class="switch-off"></span>
                        </span>
                        <span class="switch-label text-heading">Save card for future billing?</span>
                      </label>
                    </div>
                    <div class="col-12">
                      <button type="button" class="btn btn-primary btn-next me-sm-3 me-1 text-uppercase">Save Changes</button>
                      <button type="reset" class="btn btn-outline-secondary text-uppercase">Reset</button>
                    </div>
                  </div>
                </div>

                <!-- COD -->
                <div class="tab-pane fade" id="pills-cod" role="tabpanel" aria-labelledby="pills-cod-tab">
                  <p>Cash on Delivery is a type of payment method where the recipient make payment for the order at the time of delivery rather than in advance.</p>
                  <button type="button" class="btn btn-primary btn-next">Pay On Delivery</button>
                </div>

                <!-- Gift card -->
                <div class="tab-pane fade" id="pills-gift-card" role="tabpanel" aria-labelledby="pills-gift-card-tab">
                  <h6>Enter Gift Card Details</h6>
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="form-floating form-floating-outline">
                        <input type="number" class="form-control" id="giftCardNumber" placeholder="1234 9879 9898">
                        <label for="giftCardNumber">Gift card number</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating form-floating-outline">
                        <input type="number" class="form-control" id="giftCardPin" placeholder="123456">
                        <label for="giftCardPin">Gift card pin</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button type="button" class="btn btn-primary btn-next">Redeem Gift Card</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <!-- Address right -->
          <div class="col-xl-4">
            <div class="border rounded p-3">

              <!-- Price Details -->
              <h6>Price Details</h6>
              <dl class="row mb-0">

                <dt class="col-6 fw-normal text-heading">Order Total</dt>
                <dd class="col-6 text-end">$1198.00</dd>

                <dt class="col-6 fw-normal text-heading">Delivery Charges</dt>
                <dd class="col-6 text-end"><s class="text-muted me-1">$5.00</s> <span class="badge bg-label-success rounded-pill">Free</span></dd>

              </dl>
              <hr class="mx-n3 mt-1">
              <dl class="row gy-2">
                <dt class="col-6 h6 mb-0">Total</dt>
                <dd class="col-6 text-end mb-0">$1198.00</dd>

                <dt class="col-6 h6 mb-0">Deliver to:</dt>
                <dd class="col-6 fw-medium text-end mb-0"><span class="badge bg-label-primary rounded-pill">Home</span></dd>
              </dl>
              <!-- Address Details -->
              <address>
                <span class="h6 mb-0"> John Doe (Default),</span><br />
                4135 Parkway Street, <br />
                Los Angeles, CA, 90017. <br />
                Mobile : +1 906 568 2332
              </address>
              <a href="javascript:void(0)" class="fw-medium">Change address</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Confirmation -->
      <div id="checkout-confirmation" class="content">
        <div class="row mb-3">
          <div class="col-12 col-lg-8 mx-auto text-center mb-3">
            <h4 class="mt-2">Thank You! 😇</h4>
            <p>Your order <a href="javascript:void(0)" class="h6 mb-0">#1536548131</a> has been placed!</p>
            <p>We sent an email to <a href="mailto:john.doe@example.com" class="h6 mb-0">john.doe@example.com</a> with your order confirmation and receipt. If the email hasn't arrived within two minutes, please check your spam folder to see if the email was routed there.</p>
            <p><span class="fw-medium"><i class="mdi mdi-clock-outline text-heading me-1"></i></span>Time placed: 25/05/2020 13:35pm</p>
          </div>
          <!-- Confirmation details -->
          <div class="col-12">
            <ul class="list-group list-group-horizontal-md">
              <li class="list-group-item flex-fill">
                <h6 class="d-flex align-items-center gap-2"><i class="mdi mdi-map-marker-outline"></i> Shipping</h6>
                <address class="text-body">
                  John Doe <br>
                  4135 Parkway Street,<br>
                  Los Angeles, CA 90017,<br>
                  USA<br><br>
                  <span class="fw-medium">+123456789</span>
                </address>
              </li>
              <li class="list-group-item flex-fill">
                <h6 class="d-flex align-items-center gap-2"><i class="mdi mdi-credit-card-outline"></i> Billing Address</h6>
                <address class="text-body">
                  John Doe <br />
                  4135 Parkway Street,<br />
                  Los Angeles, CA 90017,<br />
                  USA <br /><br />
                  <span class="fw-medium">+123456789</span>
                </address>
              </li>
              <li class="list-group-item flex-fill">
                <h6 class="d-flex align-items-center gap-2"><i class="mdi mdi-ferry"></i> Shipping Method</h6>
                <span class="fw-medium text-body">Preferred Method:</span>
                <p class="text-body mb-0 mt-3">
                  Standard Delivery<br />
                  (Normally 3-4 business days)
                </p>
              </li>
            </ul>
          </div>
        </div>

        <div class="row">
          <!-- Confirmation items -->
          <div class="col-xl-9 mb-3 mb-xl-0">
            <ul class="list-group">
              <li class="list-group-item">
                <div class="d-flex gap-3">
                  <div class="flex-shrink-0">
                    <img src="{{asset('assets/img/products/1.png')}}" alt="google home" class="w-px-75">
                  </div>
                  <div class="flex-grow-1">
                    <div class="row">
                      <div class="col-md-8 pt-2">
                        <a href="javascript:void(0)" class="text-heading mt-1">
                          <h6>Google - Google Home - White</h6>
                        </a>
                        <div class="mb-1 d-flex flex-wrap"><span class="me-1">Sold by:</span> <a href="javascript:void(0)" class="me-1">Google</a> <span class="badge bg-label-success rounded-pill">In Stock</span></div>
                      </div>
                      <div class="col-md-4">
                        <div class="text-md-end">
                          <div class="my-2 my-lg-4"><span class="text-primary">$299/</span><span class="text-body">$359</span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="d-flex gap-3">
                  <div class="flex-shrink-0">
                    <img src="{{asset('assets/img/products/2.png')}}" alt="google home" class="w-px-75">
                  </div>
                  <div class="flex-grow-1">
                    <div class="row">
                      <div class="col-md-8 pt-2">
                        <a href="javascript:void(0)" class="text-heading mt-1">
                          <h6>Apple iPhone 11 (64GB, Black)</h6>
                        </a>
                        <div class="mb-1 d-flex flex-wrap"><span class="me-1">Sold by:</span> <a href="javascript:void(0)" class="me-1">Apple</a> <span class="badge bg-label-success rounded-pill">In Stock</span></div>
                      </div>
                      <div class="col-md-4">
                        <div class="text-md-end">
                          <div class="my-2 my-lg-4"><span class="text-primary">$899/</span><span class="text-body">$999</span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <!-- Confirmation total -->
          <div class="col-xl-3">
            <div class="border rounded p-3">
              <!-- Price Details -->
              <h6>Price Details</h6>
              <dl class="row mb-0">

                <dt class="col-6 fw-normal">Order Total</dt>
                <dd class="col-6 text-end">$1198.00</dd>

                <dt class="col-6 fw-normal">Delivery Charges</dt>
                <dd class="col-6 text-end"><s class="text-muted">$5.00</s> <span class="badge bg-label-success rounded-pill">Free</span></dd>

              </dl>
              <hr class="mx-n3 mt-1">
              <dl class="row mb-0">
                <dt class="col-6 h6 mb-0">Total</dt>
                <dd class="col-6 h6 text-end mb-0">$1198.00</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<!--/ Checkout Wizard -->
