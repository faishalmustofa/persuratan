<div class="row overflow-hidden">
    <div class="col-12">
        <ul class="timeline timeline-center mt-5">
            {{-- @foreach ($surat as $item)
                <li class="timeline-item">
                    <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="mdi mdi-brush"></i>
                    </span>
                    <div class="timeline-event card p-0" data-aos="fade-right">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="card-title mb-0">Designing UI</h6>
                            <div class="meta">
                            <span class="badge rounded-pill bg-label-primary">Design</span>
                            <span class="badge rounded-pill bg-label-success">Meeting</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                            Our main goal is to design a new mobile application for our
                            client. The customer wants a clean & flat design.
                            </p>
                        </div>
                        <div class="timeline-event-time">1st January</div>
                    </div>
                </li>
            @endforeach --}}
            <li class="timeline-item">
            <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                <i class="mdi mdi-brush"></i>
            </span>
            <div class="timeline-event card p-0" data-aos="fade-right">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="card-title mb-0">Designing UI</h6>
                <div class="meta">
                    <span class="badge rounded-pill bg-label-primary">Design</span>
                    <span class="badge rounded-pill bg-label-success">Meeting</span>
                </div>
                </div>
                <div class="card-body">
                <p class="mb-2">
                    Our main goal is to design a new mobile application for our
                    client. The customer wants a clean & flat design.
                </p>
                </div>
                <div class="timeline-event-time">1st January</div>
            </div>
            </li>
            <li class="timeline-item">
            <span class="timeline-indicator timeline-indicator-success" data-aos="zoom-in" data-aos-delay="200">
                <i class="mdi mdi-help"></i>
            </span>
            <div class="timeline-event card p-0" data-aos="fade-left">
                <h6 class="card-header">Survey Report</h6>
                <div class="card-body">
                <div class="d-flex flex-wrap mb-4">
                    <div>
                    <div class="avatar avatar-xs me-2">
                        <img src="{{asset('assets/img/avatars/4.png')}}" alt="Avatar" class="rounded-circle" />
                    </div>
                    </div>
                    <span>assigned this task to <span class="fw-medium">Sarah</span></span>
                </div>
                <ul class="list-unstyled">
                    <li class="d-flex">
                    <div>
                        <div class="avatar avatar-xs me-3">
                        <img src="{{asset('assets/img/avatars/2.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                    </div>
                    <div class="mb-3 w-100">
                        <div class="progress bg-label-danger" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 48.7%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small>Jquery</small>
                    </div>
                    </li>
                    <li class="d-flex">
                    <div>
                        <div class="avatar avatar-xs me-3">
                        <img src="{{asset('assets/img/avatars/3.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                    </div>
                    <div class="mb-3 w-100">
                        <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 31.3%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small>React</small>
                        <small>React</small>
                        <small>React</small>
                        <small>React</small>
                    </div>
                    </li>
                    <li class="d-flex">
                    <div>
                        <div class="avatar avatar-xs me-3">
                        <img src="{{asset('assets/img/avatars/4.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                    </div>
                    <div class="mb-3 w-100">
                        <div class="progress bg-label-warning" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small>Angular</small>
                    </div>
                    </li>
                    <li class="d-flex">
                    <div>
                        <div class="avatar avatar-xs me-3">
                        <img src="{{asset('assets/img/avatars/5.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                    </div>
                    <div class="mb-3 w-100">
                        <div class="progress bg-label-info" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 15%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small>VUE</small>
                    </div>
                    </li>
                    <li class="d-flex">
                    <div>
                        <div class="avatar avatar-xs me-3">
                        <img src="{{asset('assets/img/avatars/6.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                    </div>
                    <div class="w-100">
                        <div class="progress bg-label-success" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 10%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small>Laravel</small>
                    </div>
                    </li>
                </ul>
                </div>
                <div class="timeline-event-time">2nd January</div>
            </div>
            </li>
            <li class="timeline-item">
            <span class="timeline-indicator timeline-indicator-danger" data-aos="zoom-in" data-aos-delay="200">
                <i class="mdi mdi-chart-line"></i>
            </span>
    
            <div class="timeline-event card p-0" data-aos="fade-right">
                <h6 class="card-header">Financial Reports</h6>
                <div class="card-body">
                <p class="mb-2">Click the button below to read financial reports</p>
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Show Report
                </button>
                <div class="collapse" id="collapseExample">
                    <ul class="list-group list-group-flush mt-3">
                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                        <span>Last Years's Profit : $20000</span>
                        <i class="mdi mdi-share-variant-outline cursor-pointer"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                        <span> This Years's Profit : $25000</span>
                        <i class="mdi mdi-share-variant-outline cursor-pointer"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                        <span> Last Years's Commission : $5000</span>
                        <i class="mdi mdi-share-variant-outline cursor-pointer"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                        <span> This Years's Commission : $7000</span>
                        <i class="mdi mdi-share-variant-outline cursor-pointer"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                        <span>
                        This Years's Total Balance : $70000</span>
                        <i class="mdi mdi-share-variant-outline cursor-pointer"></i>
                    </li>
                    </ul>
                </div>
                </div>
                <div class="timeline-event-time">5th January</div>
            </div>
            </li>
            <li class="timeline-item">
            <span class="timeline-indicator timeline-indicator-warning" data-aos="zoom-in" data-aos-delay="200">
                <i class="mdi mdi-chart-donut-variant"></i>
            </span>
            <div class="timeline-event card p-0" data-aos="fade-left">
                <h6 class="card-header">Snacks</h6>
                <div class="card-body">
                <div class="d-flex flex-sm-row flex-column">
                    <img src="{{asset('assets/img/elements/13.jpg')}}" class="rounded me-3 mb-sm-0 mb-2" alt="doughnut" height="64" width="64" />
                    <div>
                    <h6 class="mb-2">
                        A Donut which straight gone to Your Tummy
                    </h6>
                    <p class="mb-2">
                        I gaze longingly at the beautiful, perfect, plump donut. This
                        is a delicately crafted piece of art. The mouthwatering mound
                        of miraculous mush isn't able to escape my sight...<a href="javascript:void(0)">read more</a>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                        <i class="mdi mdi-star text-warning"></i>
                        <i class="mdi mdi-star text-warning"></i>
                        <i class="mdi mdi-star text-warning"></i>
                        <i class="mdi mdi-star text-warning"></i>
                        <i class="mdi mdi-star-outline"></i>
                        </div>
                        <div>
                        <span class="fw-medium">$5.00</span>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <div class="timeline-event-time">10th January</div>
            </div>
            </li>
        </ul>
    </div>
</div>