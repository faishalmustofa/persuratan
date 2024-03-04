
'use strict';

const tanggalSurat = $('#tanggal-surat'),
    tanggalDiterima = $('#tanggal-diterima');
var table
var table_log

$(function () {
    var forms = document.querySelectorAll('.needs-validation')

    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })

    $('#form-surat-belum-sesuai').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postForm()
            $(this).addClass('was-validated');
        }
    });
    
    var form_penomoran_surat = document.querySelectorAll('.needs-validation')

    Array.prototype.slice.call(form_penomoran_surat).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })

    $('#form-penomoran-surat').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postFormPenomoranSurat()
            $(this).addClass('was-validated');
        }
    });

    // custom template to render icons
    function renderIcons(option) {
        if (!option.id) {
            return option.text;
        }
        var $icon = "<i class='" + $(option.element).data("icon") + " me-2'></i>" + option.text;
        return $icon
    }

    getDataPermintaanSurat()
    getLogPermintaanSurat()
});

function getDataPermintaanSurat(){
    table = $('#table-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/permintaan-no-surat/data",
            method: "post",
            data: function (data) {
                data._token = $('meta[name="csrf-token"]').attr('content')
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
                data: 'no_draft_surat',
                name: 'no_draft_surat',
            },
            {
                data: 'no_surat',
                name: 'no_surat',
            },
            {
                data: 'tgl_surat',
                name: 'tgl_surat',
            },
            {
                data: 'tujuan_surat.entity_name',
                name: 'tujuan_surat.entity_name',
            },
            {
                data: 'perihal',
                name: 'perihal',
                responsivePriority: 0
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                orderable: false,
                responsivePriority: 0
            },
            {
                data: 'posisi_surat',
                name: 'posisi_surat',
                orderable: false,
                responsivePriority: 0
            },
            {
                data: 'status',
                name: 'status',
                responsivePriority: 0
            },
            {
                data: 'action',
                name: 'action',
                responsivePriority: 0
            }
        ],
        order: [[3, 'asc']],
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
}

function getLogPermintaanSurat(){
    table_log = $('#table-log-permintaan-surat').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/permintaan-no-surat/log-data",
            method: "post",
            data: function (data) {
                data._token = $('meta[name="csrf-token"]').attr('content')
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
                data: 'no_draft_surat',
                name: 'no_draft_surat',
            },
            {
                data: 'tgl_surat',
                name: 'tgl_surat',
            },
            {
                data: 'tujuan_surat',
                name: 'tujuan_surat',
            },
            {
                data: 'perihal',
                name: 'perihal',
                responsivePriority: 0
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                orderable: false,
                responsivePriority: 0
            },
            {
                data: 'posisi_surat',
                name: 'posisi_surat',
                orderable: false,
                responsivePriority: 0
            },
            {
                data: 'status',
                name: 'status',
                responsivePriority: 0
            },
            // {
            //     data: 'action',
            //     name: 'action',
            //     responsivePriority: 0
            // }
        ],
        order: [[3, 'asc']],
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
}

function postForm() {
    let form = $("#form-surat-belum-sesuai").serialize()
    ajaxPostJson('/transaction/permintaan-no-surat/tindak-surat', form, 'tindak_surat_success', 'input_error')
}

function postFormPenomoranSurat() {
    let form =  new FormData($("#form-penomoran-surat")[0])
    ajaxPostFile('/transaction/buku-agenda-surat-keluar/buat-agenda-surat', form, 'penomoran_surat_success', 'input_error')
}

// function postFormPenomoranSurat() {
//     let form = $("#form-penomoran-surat").serialize()
//     ajaxPostJson('/transaction/buku-agenda-surat-keluar/buat-agenda-surat', form, 'penomoran_surat_success', 'input_error')
// }

function input_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"]("Data Surat Masuk Berhasil Disimpan", "Berhasil Simpan Data")

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

    Swal.fire({
        icon: 'question',
        showDenyButton: false,
        title: "Kirim draft surat?",
        showCancelButton: true,
        confirmButtonText: "Ya, kirim draft surat!"
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if(result.isConfirmed){
            ajaxGetJson(`/transaction/surat-masuk/print-blanko/${data.txNumber}`, 'printBlanko', 'input_error')
        } else {
            return false
        }
    });

    table.ajax.reload()
    table_log.ajax.reload()
    $('#form-surat-keluar').removeClass('was-validated')
    $('#form-surat-keluar').find('input').val('')
    $('#form-surat-keluar').find('textarea').val('')
    $('#form-surat-keluar').find('select').val('').trigger('change')
}

function modalPenomoranSurat(txNo) {
    txNo = btoa(txNo)
    ajaxGetJson(`/transaction/permintaan-no-surat/get-form-penomoran-surat/${txNo}`, 'showModalPenomoranSurat', 'error_get')
}

function showModalPenomoranSurat(res){
    Swal.close()

    if (res.status != 200) {
        var text = res.message
        error_notif(text)
        return false
    }
    var detail = res.data.detail
    
    $('#modal-detail').modal('hide')
    $('#modal-penomoran-surat').modal('toggle')
    $('#detail-data-penomoran').find('#tx_number_penomoran').val(detail.tx_number)

}

function detailSurat(txNo) {
    txNo = btoa(txNo)
    ajaxGetJson(`/transaction/permintaan-no-surat/detail/${txNo}`, 'showModalDetail', 'error_get')
}

function showModalDetail(res){
    Swal.close()

    if (res.status != 200) {
        var text = res.message
        error_notif(text)
        return false
    }

    var header = res.data.header
    var detail = res.data.detail

    Object.keys(header).map((key) => {
        $('#header-data').find(`#${key}`).html(header[key])
    })

    $('#modal-detail').modal('toggle')

    // let button_teruskan = `<button onclick="actionMintaNomorSurat(`+detail.tx_number+`)" type="button" class="btn btn-info btn-sm rounded-pill px-2">Teruskan</button>`
    $('#section-action').html(header.btn_action)
    $('#detail-data').find('#tx_number').val(detail.tx_number)
    $('#detail-data').find('#catatan').val(detail.catatan)

    if (detail['status_surat'] == '209' & detail['user'].organization == 2) {
        $('#btn-belum-sesuai').attr("hidden",true)
    }
    // $('#detail-data').find('#penandatangan').html(header.penandatangan)
    // $('#detail-data').find('#perihal').html(header.perihal)

}

function actionAgendakanSurat(txNumber){
    txNumber = btoa(txNumber)
    ajaxGetJson(`/transaction/buku-agenda-surat-keluar/buat-agenda-surat/${txNumber}`, 'agendakan_surat_success', 'input_error')
}

function actionMintaNomorSurat(txNumber){
    console.log(txNumber)
    ajaxGetJson(`/transaction/permintaan-no-surat/minta-no-surat/${txNumber}`, 'minta_surat_success', 'input_error')
}

function actionTindakSurat(txNumber){
    ajaxGetJson(`/transaction/permintaan-no-surat/tindak-surat/${txNumber}`, 'tindak_surat_success', 'input_error')
}

function actionTerimaSurat(txNumber){
    ajaxGetJson(`/transaction/permintaan-no-surat/terima-surat/${txNumber}`, 'terima_surat_success', 'input_error')
}

function actionTTDSurat(txNumber){
    ajaxGetJson(`/transaction/permintaan-no-surat/ttd-surat/${txNumber}`, 'ttd_surat_success', 'input_error')
}

function agendakan_surat_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Surat berhasil diagendakan!',
        type: 'success',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
    })

    $('#modal-detail').modal('hide')
    table.ajax.reload()
    table_log.ajax.reload()
    
}

function penomoran_surat_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Surat berhasil diberi nomor dan diagendakan!',
        type: 'success',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
    })

    table.ajax.reload()
    table_log.ajax.reload()
}

function ttd_surat_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Surat berhasil ditandatangani!',
        type: 'success',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
    })

    table.ajax.reload()
    table_log.ajax.reload()
    $('#modal-detail').modal('hide')
}

function tindak_surat_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Surat berhasil dikembalikan!',
        type: 'success',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
    })

    table.ajax.reload()
    table_log.ajax.reload()
    $('#modal-detail').modal('hide')
}

function terima_surat_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Surat berhasil diterima!',
        type: 'success',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
    })

    table.ajax.reload()
    table_log.ajax.reload()
}

function minta_surat_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Surat berhasil diminta!',
        type: 'success',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false
    })

    table.ajax.reload()
    table_log.ajax.reload()
    $('#modal-detail').modal('hide')
}

function input_error(err) {
    Swal.close()
    console.log(err)
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

function error_notif(text){
    Command: toastr["error"](text, "Gagal Simpan Data")

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
