
'use strict';

const tanggalSurat = document.querySelector('#tanggal-surat'),
    tanggalDiterima = document.querySelector('#tanggal-diterima'),
    klasifikasi = $('#klasifikasi'),
    derajat = $('#derajat'),
    asal_surat = $('#asal_surat');
var table

$(function () {

    // console.log('dropzone : ',dropzoneBasic);
    const dropzoneBasic = document.querySelector('#dropzone-basic');

    tanggalSurat.flatpickr({
        altInput: true,
        altFormat: 'F j, Y',
        dateFormat: 'Y-m-d'
    });
    tanggalDiterima.flatpickr({
        altInput: true,
        altFormat: 'F j, Y',
        dateFormat: 'Y-m-d'
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

    $('#form-surat-masuk').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postForm()
            $(this).addClass('was-validated');
        }
        // postForm()
    });

    $('#form-disposisi').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postDisposisi()
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

    const previewTemplate = `<div class="dz-preview dz-file-preview">
        <div class="dz-details">
        <div class="dz-thumbnail">
            <img data-dz-thumbnail>
            <span class="dz-nopreview">No preview</span>
            <div class="dz-success-mark"></div>
            <div class="dz-error-mark"></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div>
            <div class="progress">
            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
            </div>
        </div>
        <div class="dz-filename" data-dz-name></div>
        <div class="dz-size" data-dz-size></div>
        </div>
        </div>`;

    // const myDropzone = new Dropzone(dropzoneBasic, {
    //     previewTemplate: previewTemplate,
    //     parallelUploads: 1,
    //     url: '/transaction/surat-masuk/store' ,
    //     maxFilesize: 5,
    //     addRemoveLinks: true,
    //     maxFiles: 1
    // });

    // Init select2
    $("#klasifikasi").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: klasifikasi.parent(),
        templateResult: renderIcons,
        templateSelection: renderIcons,
        escapeMarkup: function(es) {
            return es;
        }
    });

    $("#derajat").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: derajat.parent(),
        templateResult: renderIcons,
        templateSelection: renderIcons,
        escapeMarkup: function(es) {
            return es;
        }
    });

    $("#asal_surat").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: asal_surat.parent(),
        templateResult: renderIcons,
        templateSelection: renderIcons,
        escapeMarkup: function(es) {
            return es;
        }
    });

    $("#tujuan_surat").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: asal_surat.parent(),
        templateResult: renderIcons,
        templateSelection: renderIcons,
        escapeMarkup: function(es) {
            return es;
        }
    });

    getDataSuratMasuk()
});

function getDataSuratMasuk(){
    table = $('#table-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/surat-masuk/data",
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
                data: 'no_agenda',
                name: 'no_agenda',
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
                data: 'tgl_diterima',
                name: 'tgl_diterima',
            },
            {
                data: 'asal_surat.name',
                name: 'asal_surat.name',
            },
            {
                data: 'tujuan_surat.nama',
                name: 'tujuan_surat.nama',
            },
            {
                data: 'perihal',
                name: 'perihal',
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
}

function postForm() {
    let form =  new FormData($("#form-surat-masuk")[0])
    // let form = $('#form-surat-masuk').serialize()
    ajaxPostFile('/transaction/surat-masuk/store', form, 'input_success', 'input_error')
}

// function postDisposisi() {
//     let form = $('#form-disposisi').serialize()
//     ajaxPostJson('/transaction/disposisi/store', form, 'disposisi_succes', 'input_error')
// }

// function disposisi_succes(data) {
//     window.location.href = `/print/${data.file}`
//     setTimeout(() => {
//         window.location.reload()
//     }, 2000);
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
        title: "Apakah anda ingin langsung melakukan mencetak blanko disposisi?",
        showCancelButton: true,
        confirmButtonText: "Ya, print blanko disposisi!"
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if(result.isConfirmed){
            ajaxGetJson(`/transaction/surat-masuk/print-blanko/${data.txNumber}`, 'printBlanko', 'input_error')
        } else {
            return false
        }
    });

    table.ajax.reload()
    $('#form-surat-masuk').removeClass('was-validated')
    $('#form-surat-masuk').find('input').val('')
    $('#form-surat-masuk').find('textarea').val('')
    $('#form-surat-masuk').find('select').empty().trigger('change')
}

function actionPrintBlanko(txNumber){
    ajaxGetJson(`/transaction/surat-masuk/print-blanko/${txNumber}`, 'printBlanko', 'input_error')
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
