
'use strict';

const tanggalSurat = $('#tanggal-surat'),
    tanggalDiterima = $('#tanggal-diterima'),
    jenis_surat = $('#jenis_surat'),
    tujuan_surat = $('#tujuan_surat'),
    unit_kerja_pemohon = $('#unit_kerja_pemohon');
var table

$(function () {
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

    // Init custom option check
  window.Helpers.initCustomOptionCheck();

  // Bootstrap validation example
  //------------------------------------------------------------------------------------------
  // const flatPickrEL = $('.flatpickr-validation');
  const flatPickrList = [].slice.call(document.querySelectorAll('.flatpickr-validation')),
    selectPicker = $('.selectpicker');

  // Bootstrap Select
  // --------------------------------------------------------------------
  if (selectPicker.length) {
    selectPicker.selectpicker();
    handleBootstrapSelectEvents();
  }

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

    $('#form-surat-keluar').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postForm()
            $(this).addClass('was-validated');
        }
        // postForm()
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


    // Init select2

    $("#jenis_surat").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: jenis_surat.parent(),
        templateResult: renderIcons,
        templateSelection: renderIcons,
        escapeMarkup: function(es) {
            return es;
        }
    });

    $("#tujuan_surat").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: tujuan_surat.parent(),
        templateResult: renderIcons,
        templateSelection: renderIcons,
        escapeMarkup: function(es) {
            return es;
        }
    });
    
    $("#unit_kerja_pemohon").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: unit_kerja_pemohon.parent(),
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
            url: "/transaction/surat-keluar/data",
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
                data: 'noSurat',
                name: 'noSurat',
            },
            {
                data: 'tgl_surat',
                name: 'tgl_surat',
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
            // {
            //     data: 'action',
            //     name: 'action',
            //     responsivePriority: 0
            // }
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
    let form =  new FormData($("#form-surat-keluar")[0])
    ajaxPostFile('/transaction/surat-keluar/store', form, 'input_success', 'input_error')
}

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
    $('#form-surat-keluar').removeClass('was-validated')
    $('#form-surat-keluar').find('input').val('')
    $('#form-surat-keluar').find('textarea').val('')
    $('#form-surat-keluar').find('select').val('').trigger('change')
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
