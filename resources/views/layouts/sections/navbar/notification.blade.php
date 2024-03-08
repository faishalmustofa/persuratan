<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
    <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
      <i class="mdi mdi-bell-outline mdi-24px"></i>
      @if ($totalNewNotif > 0)
          <span class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
      @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end py-0">
      <li class="dropdown-menu-header border-bottom">
        <div class="dropdown-header d-flex align-items-center py-3">
          <h6 class="mb-0 me-auto">Notifikasi</h6>
          <span class="badge rounded-pill bg-label-primary">
              {{$totalNewNotif}} surat masuk menunggu tindak lanjut
          </span>
          <span class="badge rounded-pill bg-label-info ms-2">
            0 surat keluar menunggu tindak lanjut
        </span>
        </div>
      </li>

      <div class="nav-align-top">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-sm" aria-controls="navs-top-sm" aria-selected="true">Surat Masuk</button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-sk" aria-controls="navs-top-sk" aria-selected="false">Surat Keluar</button>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="navs-top-sm" role="tabpanel">
            <li class="dropdown-notifications-list scrollable-container">
                <ul class="list-group list-group-flush">
                    @forelse ($notifikasi as $nt)
                    <a href="{{url('transaction/buku-agenda/'.base64_encode($nt->tx_number))}}">
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar me-1">
                                        <span class="avatar-initial rounded-circle bg-label-success">
                                            <i class="mdi mdi-information-outline"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                    <h6 class="mb-1 text-truncate">Surat Masuk Baru !!</h6>
                                    <small class="text-truncate text-body">No. Surat : {{$nt->suratMasuk->no_surat}}</small>
                                    <small class="text-truncate text-body">Perihal : {{$nt->suratMasuk->perihal}} | Klasifikasi/Derajat : {{$nt->suratMasuk->klasifikasiSurat->nama}}/{{$nt->suratMasuk->derajatSurat->nama}}</small>
                                </div>
                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                    <small class="text-muted">{{App\Helpers\Helpers::time_elapsed_string($nt->created_at)}}</small>
                                </div>
                            </div>
                        </li>
                    </a>
                    @empty
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                            <center>
                                Tidak ada Notifikasi baru
                            </center>
                        </li>
                    @endforelse
                </ul>
            </li>

            <li class="dropdown-menu-footer border-top p-2 mt-3">
                <a href="javascript:void(0);" class="btn btn-primary d-flex justify-content-center">
                Baca semua notifikasi surat masuk
                </a>
            </li>
          </div>
          <div class="tab-pane fade" id="navs-top-sk" role="tabpanel">
            <p>
              Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice cream. Gummies
              halvah
              tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream cheesecake fruitcake.
            </p>
            <p class="mb-0">
              Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah cotton candy
              liquorice caramels.
            </p>
          </div>
        </div>
      </div>
    </ul>
  </li>
