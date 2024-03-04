
'use strict';

const tanggalPengiriman = $('#tanggal_pengiriman');
var table

$(function () {

    tanggalPengiriman.flatpickr({
        altInput: true,
        altFormat: 'F j, Y',
        dateFormat: 'Y-m-d'
    });
    // Init custom option check
    window.Helpers.initCustomOptionCheck();

    // Bootstrap validation example
    //------------------------------------------------------------------------------------------
    // const flatPickrEL = $('.flatpickr-validation');
    const flatPickrList = [].slice.call(document.querySelectorAll('.flatpickr-validation')),
    selectPicker = $('.selectpicker');

    // Flat pickr
    if (flatPickrList) {
        flatPickrList.forEach(flatPickr => {
        flatPickr.flatpickr({
            allowInput: true,
            monthSelectorType: 'static'
        });
        });
    }

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

    $('#form-kirim-surat').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postForm()
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

    getDataSuratMasuk()
    getLogAgendaSurat()
});

function getDataSuratMasuk(){
    table = $('#table-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/buku-agenda-surat-keluar/get-data",
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

function getLogAgendaSurat(){
    table_log = $('#table-log-agenda-surat').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/buku-agenda-surat-keluar/log-data",
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
    let form = $("#form-kirim-surat").serialize()
    ajaxPostJson('/transaction/pengiriman-surat-keluar/store', form, 'input_success', 'input_error')
}

function input_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"]("Surat Berhasil Dikirimkan", "Berhasil Kirim Surat")

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

    // Swal.fire({
    //     icon: 'question',
    //     showDenyButton: false,
    //     title: "Kirim draft surat?",
    //     showCancelButton: true,
    //     confirmButtonText: "Ya, kirim draft surat!"
    //   }).then((result) => {
    //     /* Read more about isConfirmed, isDenied below */
    //     if(result.isConfirmed){
    //         ajaxGetJson(`/transaction/surat-masuk/print-blanko/${data.txNumber}`, 'printBlanko', 'input_error')
    //     } else {
    //         return false
    //     }
    // });

    table.ajax.reload()
    table_log.ajax.reload()
    // $('#form-surat-keluar').removeClass('was-validated')
    // $('#form-surat-keluar').find('input').val('')
    // $('#form-surat-keluar').find('textarea').val('')
    // $('#form-surat-keluar').find('select').val('').trigger('change')
}

function detailSurat(txNo) {
    txNo = btoa(txNo)
    ajaxGetJson(`/transaction/buku-agenda-surat-keluar/detail/${txNo}`, 'showModalDetail', 'error_get')
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
    // $('#detail-data').find('#penandatangan').html(header.penandatangan)
    // $('#detail-data').find('#perihal').html(header.perihal)

}

function getFormKirimSurat(txNumber){
    txNumber = btoa(txNumber)
    ajaxGetJson(`/transaction/buku-agenda-surat-keluar/get-form-kirim-surat/${txNumber}`, 'showFormKirimSurat', 'input_error')
}

function showFormKirimSurat(res){
    Swal.close()

    if (res.status != 200) {
        var text = res.message
        error_notif(text)
        return false
    }

    var data = res.data
    // var detail = res.data.detail

    // Object.keys(header).map((key) => {
    //     $('#header-data').find(`#${key}`).html(header[key])
    // })

    $('#modal-form-kirim-surat').modal('toggle')

    // let button_teruskan = `<button onclick="actionMintaNomorSurat(`+detail.tx_number+`)" type="button" class="btn btn-info btn-sm rounded-pill px-2">Teruskan</button>`
    // $('#section-action').html(header.btn_action)
    $('#nomor_surat').val(data.nomor_surat)
    $('#nomor_agenda').val(data.nomor_agenda)
    $('#txNo').val(data.txNo)
    // $('#detail-data').find('#penandatangan').html(header.penandatangan)
    // $('#detail-data').find('#perihal').html(header.perihal)

}

function get_form_success(data) {
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

    $('#modal-detail').modal('toggle')
    table.ajax.reload()
    table_log.ajax.reload()
    
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
