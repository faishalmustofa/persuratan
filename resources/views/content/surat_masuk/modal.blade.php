<!-- Modal Disposisi -->
<div class="modal fade" id="modal-disposisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Form Disposisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0)" id="form-disposisi" class="needs-validation" novalidate>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="tx_number" class="form-control">
                    <div class="form-floating form-floating-outline mb-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Surat" name="nomor_agenda" value="" readonly />
                            <label for="nomor_agenda">Nomor Agenda</label>
                        </div>
                    </div>

                    <div class="form-floating form-floating-outline mb-4">
                        <select id="tujuan_disposisi" name="tujuan_disposisi[]" class="select2 form-select" multiple>
                        </select>
                        <label for="tujuan_disposisi">Tujuan Disposisi</label>
                    </div>

                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control h-px-75" id="isi_disposisi" name="isi_disposisi" rows="3" placeholder="Isi Disposisi"></textarea>
                        <label for="isi_disposisi">Isi Disposisi</label>
                        <div class="invalid-feedback"> Mohon masukan Isi Disposisi. </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Disposisikan Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rubah Tanggal -->
<div class="modal fade" id="modal-edit-tgl" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Rubah Tanggal Diterima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0)" id="form-edit-tgl" class="needs-validation" novalidate>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="tx_number" class="form-control">
                    <div class="form-floating form-floating-outline mb-4">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control flatpickr-validation" placeholder="YYYY-MM-DD" min="" id="tanggal-diterima" name="tanggal_diterima" required/>
                            <label for="tanggal_diterima">Pilih Tanggal Diterima</label>
                            <div class="invalid-feedback"> Mohon pilih tanggal diterima. </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Rubah Tanggal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Revisi Surat -->
<div class="modal fade" id="modal-reject" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Revisi Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0)" id="form-revisi" class="needs-validation" novalidate enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="tx_number" class="form-control">
                    <div class="form-group mb-4">
                        <label for="formFile" class="form-label">Upload Foto Revisi</label>
                        <input class="form-control" type="file" id="image" name="image[]" multiple accept=".jpg,.jpeg,.png">
                    </div>

                    <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control h-px-75" id="notes" name="notes" rows="3" placeholder="Catatan Revisi" required></textarea>
                        <label for="notes">Catatan Revisi</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Revisi Berkas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Revisi -->
<div class="modal fade" id="modal-reject-detail" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Detail Revisi Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card text-white bg-primary rounded-pills mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-white">Informasi Data Surat</h5>
                        <div class="row justify-content-center align-items-center" id="header-data">
                            <div class="col-md-4 col-12 mb-2">No. Surat</div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span class='badge rounded-pill bg-label-info' id="no_surat"></span>
                            </div>

                            <div class="col-md-4 col-12 mb-2">No. Agenda</div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <b id="no_agenda"></b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card text-white bg-info rounded-pills mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-white">Informasi Revisi</h5>
                        <div class="row justify-content-center align-items-center" id="detail-data">
                            <div class="col-md-4 col-12 mb-2">
                                Tanggal Direvisi
                            </div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span id="tgl_revisi"></span>
                            </div>

                            <div class="col-md-4 col-12 mb-2">
                                Direvisi Oleh
                            </div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span id="revisi_by"></span>
                            </div>

                            <div class="col-md-4 col-12 mb-2"> Catatan Revisi </div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span id="notes"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card text-black bg-white rounded-pills mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-dark">List Gambar</h5>
                        <div class="row justify-content-center align-items-center" id="image-data">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bulking Surat -->
<div class="modal fade" id="modal-bulking" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Bulking Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javacript:void(0)" id="form-bulking">
                @csrf
                <div class="modal-body" id="modal-body-bulking">
                    <div class="row justify-content-center align-items-center" id="select-tujuan-cont">
                        <div class="col-4 col-md-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <select id="tujuan_surat_bulking" name="tujuan_surat" class="select2 form-select" data-dropdown-parent="#modal-body-bulking">
                                    <option value="">Pilih Tujuan Surat</option>
                                    @foreach ($organization as $org)
                                        <option value="{{$org->id}}" {{isset($suratMasuk) ? ($suratMasuk->tujuanDisposisi[0]->tujuan_disposisi == $org->id ? 'selected' : '') : ''}} >{{$org->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            &nbsp;
                        </div>
                        <div class="col-4 col-md-4">
                            &nbsp;
                        </div>
                    </div>
                    <div class="card-datatable table-responsive pt-0" id="data-container" style="display:none">
                        <table class="datatables-basic table table-bordered" id="bulking-list">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">No. Agenda</th>
                                    <th scope="col">No. Surat</th>
                                    <th scope="col">Tanggal Surat</th>
                                    <th scope="col">Tanggal Diterima</th>
                                    <th scope="col">Surat Dari</th>
                                    <th scope="col">Perihal</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="sendBulking()">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Detail Disposisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card text-white bg-primary rounded-pills mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-white">Informasi Data Surat</h5>
                        <div class="row justify-content-center align-items-center" id="header-data">
                            <div class="col-md-4 col-12 mb-2">No. Surat</div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span class='badge rounded-pill bg-label-info' id="no_surat"></span>
                            </div>

                            <div class="col-md-4 col-12 mb-2">No. Agenda</div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <b id="no_agenda"></b>
                            </div>

                            <div class="col-md-4 col-12 mb-2">
                                Tanggal Surat
                            </div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span id="tgl_surat"></span>
                            </div>

                            <div class="col-md-4 col-12 mb-2">
                                Tanggal Diterima
                            </div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span id="tgl_diterima"></span>
                            </div>

                            <div class="col-md-4 col-12 mb-2"> Tujuan Surat </div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span id="tujuan_surat"></span>
                            </div>

                            <div class="col-md-4 col-12 mb-2"> Perihal </div>
                            <div class="col-md-1 col-12 mb-2"> : </div>
                            <div class="col-md-7 col-12 mb-2">
                                <span id="perihal"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card text-white bg-info rounded-pills">
                    <div class="card-body" id="detail-data">
                        <div class="card text-secondary bg-white mb-2">
                            <div class="card-header">Tujuan Disposisi</div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap" id="tujuan_disposisi"></div>
                            </div>
                        </div>

                        <div class="card text-secondary bg-white">
                            <div class="card-header">Isi Disposisi</div>
                            <div class="card-body">
                                <p id="isi_disposisi"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>

<!-- Modal Kirim Surat -->
<div class="modal fade" id="modal-kirim" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Form Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0)" id="form-pengiriman" class="needs-validation" novalidate>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="tx_number" class="form-control">
                    <input type="hidden" name="type_kirim" value="single">
                    <div class="row justify-content-center" id="data-surat">
                        <div class="col-md-6 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Agenda" name="nomor_agenda" value="" readonly />
                                <label for="nomor_agenda">Nomor Agenda</label>
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="no_surat" placeholder="Nomor Surat" name="no_surat" value="" readonly />
                                <label for="no_surat">Nomor Surat</label>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-4 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="jenis_pengiriman" placeholder="Jenis Pengiriman" name="jenis_pengiriman" value="" required />
                                <label for="jenis_pengiriman">Jenis Pengiriman</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="expedisi" placeholder="Expedisi" name="expedisi" value="" />
                                <label for="expedisi">Expedisi</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="no_resi" placeholder="no_resi" name="no_resi" value="" />
                                <label for="no_resi">No. Resi</label>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-6 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="nama_pengirim" placeholder="nama_pengirim" name="nama_pengirim" value="" required />
                                <label for="nama_pengirim">Nama Pengirim</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" placeholder="YYYY-MM-DD HH:MM" name="tgl_kirim" id="tgl_kirim" required />
                                <label for="tgl_kirim">Tanggal Pengiriman</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Input Pengiriman</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Diterima -->
<div class="modal fade" id="modal-terima" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Terima Berkas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0)" id="form-terima" class="needs-validation" novalidate>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="tx_number" class="form-control">
                    <div class="row justify-content-center" id="data-surat">
                        <div class="col-md-6 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="nomor_agenda" placeholder="Nomor Agenda" name="nomor_agenda" value="" readonly />
                                <label for="nomor_agenda">Nomor Agenda</label>
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="no_surat" placeholder="Nomor Surat" name="no_surat" value="" readonly />
                                <label for="no_surat">Nomor Surat</label>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-3 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="pangkat_penerima" placeholder="pangkat penerima" name="pangkat_penerima" required />
                                <label for="pangkat_penerima">Pangkat Penerima</label>
                            </div>
                        </div>
                        <div class="col-md-5 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="nama_penerima" placeholder="nama penerima" name="nama_penerima" required />
                                <label for="nama_penerima">Nama Penerima</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="jabatan_penerima" placeholder="jabatan penerima" name="jabatan_penerima" required />
                                <label for="jabatan_penerima">Jabatan Penerima</label>
                            </div>
                        </div>
                        <div class="col-md-12 col-12 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" placeholder="YYYY-MM-DD HH:MM" name="tgl_diterima" id="tgl_terima" required />
                                <label for="tgl_terima">Tanggal Diterima</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Terima Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>
