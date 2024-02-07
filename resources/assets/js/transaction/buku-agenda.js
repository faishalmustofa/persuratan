var table, tableBulking, valTujuan = 0
const tglSurat = document.querySelector('#tgl-surat')
const asal_surat = $('#asal_surat');
const tujuan_surat = $('#tujuan_surat');

$( function(){
    tglSurat.flatpickr({
        mode: "range"
    });

    $("#tujuan_surat_bulking").select2();

    $("#asal_surat").select2({
        placeholder: 'Pilih Asal Surat',
        allowClear: true
    });

    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })

    $('#form-pencarian').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            searchData()
        }
    });

    $('#form-disposisi').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postDisposisi()
            $(this).addClass('was-validated');
        }
    });

    $('#form-edit-tgl').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            PostEditTglDiterima()
            $(this).addClass('was-validated');
        }
    });

    $('#form-revisi').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postRevisiBerkas()
            $(this).addClass('was-validated');
        }
    });

    $('#form-bulking').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            sendBulking()
        }
    });
})

function searchData(){
    $('#container-data').slideUp()
    $('#table-list').DataTable().destroy();

    const tgl_surat = $('#tgl-surat').val()
    const nomor_agenda = $('#nomor_agenda').val()
    const nomor_surat = $('#nomor_surat').val()
    const asal_surat = $('#asal_surat').val()
    const perihal = $('#perihal').val()

    table = $('#table-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/buku-agenda/get-data",
            method: "post",
            data: function(d){
                d._token = $('meta[name="csrf-token"]').attr('content')
                d.tgl_surat = tgl_surat;
                d.nomor_agenda = nomor_agenda
                d.nomor_surat = nomor_surat
                d.asal_surat = asal_surat
                d.perihal = perihal
            }
        },
        columns: [
            { data:null },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                responsivePriority: -1,
            },
            {
                data: 'no_agenda',
                name: 'no_agenda',
                responsivePriority: 0,
                className: 'editable'
            },
            {
                data: 'noSurat',
                name: 'noSurat',
                responsivePriority: 0
            },
            {
                data: 'tgl_surat',
                name: 'tgl_surat',
                responsivePriority: 1
            },
            {
                data: 'tgl_diterima',
                name: 'tgl_diterima',
                responsivePriority: 2,
                className: 'editable'
            },
            {
                data: 'surat_dari',
                name: 'surat_dari',
                responsivePriority: 0
            },
            {
                data: 'tujuan_surat.nama',
                name: 'tujuan_surat.nama',
                responsivePriority: 1
            },
            {
                data: 'perihal',
                name: 'perihal',
                responsivePriority: 0
            },
            {
                data: 'status',
                name: 'status',
                responsivePriority: 2
            },
            {
                data: 'action',
                name: 'action',
                responsivePriority: -1
            }
        ],
        order: [[4, 'asc']],
        fnDrawCallback: () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        },
        columnDefs: [
            {
                className: 'control',
                orderable: false,
                responsivePriority: 2,
                searchable: false,
                targets: 0,
                render: function (data, type, full, meta) {
                  return '';
                }
            }
        ],
    });

    $('#container-data').slideDown()
}

function editTglDiterima(txNumber, tglDiterima){
    $('#modal-edit-tgl').modal('toggle')
    $('#form-edit-tgl').find('input[name="tx_number"]').val(txNumber)
    $('#form-edit-tgl').find('#tanggal-diterima').val(tglDiterima).attr('min')
}

async function PostEditTglDiterima(){
    var form = $('#form-edit-tgl').serialize()
    await ajaxPostJson('/transaction/surat-masuk/edit-tgl', form, 'input_success', 'input_error')
    $('#modal-edit-tgl').modal('toggle')
}

function updateDisposisi(txNumber, no_agenda) {
    $('#modal-disposisi').modal('toggle')
    $('#form-disposisi').find('input[name="tx_number"]').val(txNumber)
    $('#form-disposisi').find('input[name="nomor_agenda"]').val(no_agenda)

    ajaxGetJson(`/transaction/disposisi/get-tujuan/${txNumber}`, 'renderTujuanDisposisi', 'error_get')
}

function renderTujuanDisposisi(data) {
    const org = data.data
    let option = '<option></option>';
    for (let i = 0; i < org.length; i++) {
        option += `<option value="${org[i].id}"> ${org[i].nama} </option>`
    }

    $('#tujuan_disposisi').html(option)
    $("#tujuan_disposisi").select2({
        dropdownParent : $('#modal-disposisi .modal-content'),
        placeholder: 'Harap Pilih Tujuan Disposisi',
        allowClear: true
    });
}

function postDisposisi() {
    var tujuanDisposisi = $('#tujuan_disposisi').val()

    if(tujuanDisposisi.length == 0){
        Swal.fire({
            title: "Tidak ada tujuan Disposisi yang dipilih",
            text: 'Anda tidak memilih tujuan disposisi, maka berkas ini akan diarsipkan. Apakah Anda yakin?',
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Ya, Arsipkan Berkas",
            denyButtonText: `Tidak`,
            showCancelButton: false,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                postData()
            } else {
                return false
            }
        });
    } else {
        postData()
    }

    async function postData(){
        let form = $('#form-disposisi').serialize()
        await ajaxPostJson('/transaction/disposisi/store', form, 'input_success', 'input_error')
        $('#modal-disposisi').modal('toggle')
    }
}

function actionPrintBlanko(txNumber){
    ajaxGetJson(`/transaction/surat-masuk/print-blanko/${txNumber}`, 'printBlanko', 'error_insert')
}

function printBlanko(data){
    var tempDownload = document.createElement("a");
    tempDownload.style.display = 'none';

    document.body.appendChild( tempDownload );

    var download = data.file;
    tempDownload.setAttribute( 'href', `/transaction/surat-masuk/download-blanko/${download}` );
    tempDownload.setAttribute( 'download', download );

    tempDownload.click();

    table.ajax.reload()
}

function pindahBerkas(txNo, status) {
    let text = ''
    if(status.includes("TAUD")){
        text = 'Kirimkan Berkas ke SPRI KADIV?'
    } else {
        text = 'Kirimkan Berkas ke TAUD?'
    }

    Swal.fire({
        icon: 'question',
        showDenyButton: false,
        title: text,
        showCancelButton: true,
        confirmButtonText: "Ya!"
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if(result.isConfirmed){
            ajaxGetJson(`/transaction/surat-masuk/pindah-berkas/${txNo}`, 'input_success', 'input_error')
        } else {
            return false
        }
    });
}

function terimaBerkas(txNo, status){
    let text = ''
    if(status.includes("TAUD")){
        text = 'Terima Berkas dari SPRI KADIV?'
    } else {
        text = 'Terima Berkas dari TAUD?'
    }

    Swal.fire({
        icon: 'question',
        showDenyButton: false,
        title: text,
        showCancelButton: true,
        confirmButtonText: "Ya!"
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if(result.isConfirmed){
            ajaxGetJson(`/transaction/surat-masuk/terima-berkas/${txNo}`, 'input_success', 'input_error')
        } else {
            return false
        }
    });
}

async function postRevisiBerkas(){
    let form =  new FormData($("#form-revisi")[0])
    await ajaxPostFile('/transaction/surat-masuk/revisi-berkas', form, 'input_success', 'input_error')
    $('#modal-reject').modal('toggle')
}

function revisiBerkas(txNumber){
    $('#modal-reject').modal('toggle')
    $('#form-revisi').find('input[name="tx_number"]').val(txNumber)
}


function viewDetailRejected(txNumber){
    txNumber = btoa(txNumber)
    ajaxGetJson(`/transaction/surat-masuk/view-reject/${txNumber}`, 'render_rejected', 'input_error')
}

function render_rejected(res){
    const dataSurat = res.data.surat
    const dataReject = res.data.reject
    var headerCont = $('#modal-reject-detail').find('#header-data')
    var detailCont = $('#modal-reject-detail').find('#detail-data')

    $('#modal-reject-detail').modal('toggle')
    $(headerCont).find('#no_surat').html(dataSurat.no_surat)
    $(headerCont).find('#no_agenda').html(dataSurat.no_agenda)

    $(detailCont).find('#tgl_revisi').html(dataReject.rejected_at)
    $(detailCont).find('#revisi_by').html(dataReject.rejected_by)
    $(detailCont).find('#notes').html(dataReject.notes)

    var imgEl = '';
    if(dataReject.image.length > 0){
        for (let i = 0; i < dataReject.image.length; i++) {
            imgEl += `<div class="col">
                            <img src="${dataReject.image[i]}" alt="Image" class="img-fluid">
                        </div>
                        `

        }

        $('#modal-reject-detail').find('#image-data').html(imgEl)
    } else {
        $('#modal-reject-detail').find('#image-data').html(`
            <b><h6 class="text-danger text-center">Tidak ada gambar yang diupload</h6></b>
        `)
    }

}


function bulkingSurat(){
    $('#modal-bulking').modal('toggle')

    if($('#data-container').css('display') == 'none'){
        $('#data-container').slideDown()
    }

    getDataBulking()
}

$('#tujuan_surat_bulking').on('select2:select', function(){
    valTujuan = $(this).val();
    tableBulking.ajax.reload()
})

function getDataBulking(){
    tableBulking = $('#bulking-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "scrollX":true,
        "destroy":true,
        ajax: {
            url: "/transaction/surat-masuk/data",
            method: "post",
            data: function (data) {
                data._token = $('meta[name="csrf-token"]').attr('content'),
                data.tujuan_surat = valTujuan,
                data.from = 'bulking'
            }
        },
        columns: [
            { data:null },
            {
                data: 'no_agenda',
                name: 'no_agenda',
                responsivePriority: 0,
                className: 'editable'
            },
            {
                data: 'noSurat',
                name: 'noSurat',
                responsivePriority: 0
            },
            {
                data: 'tgl_surat',
                name: 'tgl_surat',
                responsivePriority: 1
            },
            {
                data: 'tgl_diterima',
                name: 'tgl_diterima',
                responsivePriority: 2,
                className: 'editable'
            },
            {
                data: 'surat_dari',
                name: 'surat_dari',
                responsivePriority: 0
            },
            {
                data: 'perihal',
                name: 'perihal',
                responsivePriority: 0
            },
        ],
        order: [[4, 'asc']],
        fnDrawCallback: () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        },
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                responsivePriority: 3,
                searchable: false,
                checkboxes: true,
                render: function (data) {
                    return `<input type="checkbox" name="txNumber[]" class="dt-checkboxes form-check-input" value="${data.tx_number}">`;
                },
                checkboxes: {
                    selectAllRender: '<input type="checkbox" class="form-check-input">'
                }
            },
        ],
    });
}

function sendBulking(){
    Swal.fire({
        title: "Anda yakin mengirim berkas surat ini secara bundling?",
        icon: 'question',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Ya, Kirim Bundling",
        denyButtonText: `Tidak`,
        showCancelButton: false,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            postData()
        } else {
            return false
        }
    });

    async function postData(){
        let form = $('#form-bulking').serialize()

        await ajaxPostJson('/transaction/surat-masuk/kirim-bundling', form, 'input_success', 'input_error')
        tableBulking.ajax.reload()

        $('#modal-bulking').modal('toggle')
    }
}

function input_success(data){
    Swal.close()
    table.ajax.reload()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"](data.message, "Berhasil Simpan Data")

    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
}

function error_insert(err){
    console.log(err)
    Swal.close()
    Command: toastr["error"]("Harap coba lagi beberapa saat lagi", "Terjadi Kesalahan Terhadap Sistem")

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}
