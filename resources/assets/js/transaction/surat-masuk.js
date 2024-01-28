
'use strict';

const tanggalSurat = $('#tanggal-surat'),
    tanggalDiterima = $('#tanggal-diterima'),
    klasifikasi = $('#klasifikasi'),
    derajat = $('#derajat'),
    asal_surat = $('#asal_surat');
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

    $('#form-surat-masuk').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postForm()
            $(this).addClass('was-validated');
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

    $('#nomor_surat').on('keypress',function( e ) {
        if(e.which === 32)
            return false;
    })

    $('.form-control').each((k, el) => {
        $(el).css('text-transform', 'uppercase')
    })

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

function postForm() {
    let form =  new FormData($("#form-surat-masuk")[0])
    ajaxPostFile('/transaction/surat-masuk/store', form, 'input_success', 'input_error')

    var currentUrl = window.location.href
    var newURL = currentUrl.split('/');
    if(newURL.length > 5){
        newURL = currentUrl.replace('/'+newURL[newURL.length-1], '')
        history.pushState({}, null, newURL)
    }
}

function actionPrintBlanko(txNumber){
    ajaxGetJson(`/transaction/surat-masuk/print-blanko/${txNumber}`, 'printBlanko', 'input_error')
}

function printBlanko(data){
    // var tempDownload = document.createElement("a");
    // tempDownload.style.display = 'none';

    // document.body.appendChild( tempDownload );

    // var download = data.file;
    // tempDownload.setAttribute( 'href', `/transaction/surat-masuk/download-blanko/${download}` );
    // tempDownload.setAttribute( 'download', download );

    // tempDownload.click();
    window.open(data.filePath, '_blank')
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
            ajaxGetJson(`/transaction/surat-masuk/pindah-berkas/${txNo}`, 'success_pindah', 'input_error')
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
            ajaxGetJson(`/transaction/surat-masuk/terima-berkas/${txNo}`, 'success_pindah', 'input_error')
        } else {
            return false
        }
    });
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
        await ajaxPostJson('/transaction/disposisi/store', form, 'input_success_', 'input_error')
        $('#modal-disposisi').modal('toggle')
    }
}

function replaceSpace(el){
    var val = $(el).val()
    const newVal = val.replace(' ', '')
    $(el).val(newVal)
}

function input_success_(data){
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"]("Berhasil Update Disposisi", "Berhasil Simpan Data")

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

    table.ajax.reload()
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

    if(data.printBlanko == '1'){
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
    }

    table.ajax.reload()
    $('#form-surat-masuk').removeClass('was-validated')
    $('#form-surat-masuk').find('.form-control').val('')
    $('#form-surat-masuk').find('textarea').val('')
    $('#form-surat-masuk').find('select').val('').trigger('change')
}

function success_pindah(data){
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

function input_error(err) {
    Swal.close()
    var text = err.responseJSON?.message == undefined ? "Terjadi Kesalahan Pada Sistem!" : err.responseJSON.message
    Command: toastr["error"](text, "Gagal Memproses Data")

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
