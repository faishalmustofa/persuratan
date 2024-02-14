
'use strict';

const tanggalSurat = $('#tanggal-surat'),
    tanggalDiterima = $('#tanggal-diterima'),
    tglTerima = $('#tgl_terima'),
    klasifikasi = $('#klasifikasi'),
    derajat = $('#derajat'),
    asal_surat = $('#asal_surat');
var table, tableBulking, valTujuan = 0

$(function () {
    tanggalSurat.flatpickr({
        dateFormat: 'Y-m-d'
    });
    tanggalDiterima.flatpickr({
        dateFormat: 'Y-m-d'
    });
    tglTerima.flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i"
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

    $('#form-terima').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postTerimaSurat()
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

    $("#tujuan_surat_bulking").select2();

    $("#jenis_surat").wrap('<div class="position-relative"></div>').select2({
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

async function postForm() {
    let form =  new FormData($("#form-surat-masuk")[0])
    await ajaxPostFile('/transaction/surat-masuk/store', form, 'input_success', 'input_error')

    var currentUrl = window.location.href
    var newURL = currentUrl.split('/');
    if(newURL.length > 5){
        newURL = currentUrl.replace('/'+newURL[newURL.length-1], '')
        history.pushState({}, null, newURL)
    }

    $('#form-surat-masuk').removeClass('was-validated')
    $('#form-surat-masuk').find('.form-control').val('')
    $('#form-surat-masuk').find('textarea').val('')
    $('#form-surat-masuk').find('select').val('').trigger('change')
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

function terimaBerkas(txNo, noAgenda, noSurat, status){
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
            $('#modal-terima').modal('toggle')
            $('#form-terima').find('input[name="tx_number"]').val(txNo)
            $('#form-terima').find('input[name="nomor_agenda"]').val(noAgenda)
            $('#form-terima').find('input[name="no_surat"]').val(noSurat)
        } else {
            return false
        }
    });
}

async function postTerimaSurat(){
    let form = $('#form-terima').serialize()
    await ajaxPostJson('/transaction/surat-masuk/terima-berkas', form, 'success_pindah', 'input_error')
    $('#modal-terima').modal('toggle')
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

function cekNoSurat(){
    let noSurat = $('input[name="nomor_surat"]').val()
    if(noSurat == '' || noSurat == null){
        $('input[name="nomor_surat"]').next().next().fadeIn()
        return false
    } else {
        $('input[name="nomor_surat"]').next().next().fadeOut()
        noSurat = btoa(noSurat)
        ajaxGetJson(`/transaction/surat-masuk/cek-surat/${noSurat}`, 'notif_used', 'input_error')
    }

}

function notif_used(res){
    if(res.data != null){
        $('#usedNoSurat').fadeIn()
        $('#lastStatus').html(res.data.status_surat.name)

        if(res.data.status_surat.name != 'Direvisi'){
            $('#form-surat-masuk').find('.form-control').not('#nomor_surat').attr('disabled', 'disabled')
            $('#form-surat-masuk').find('.form-select').attr('disabled', 'disabled')
            $('#form-surat-masuk').find('#btn-save').attr('disabled', 'disabled')
        } else {
            $('#form-surat-masuk').find('.form-control').not('#nomor_agenda').removeAttr('disabled')
            $('#form-surat-masuk').find('.form-select').removeAttr('disabled')
            $('#form-surat-masuk').find('#btn-save').removeAttr('disabled')
        }
    } else {
        $('#usedNoSurat').fadeOut()
        $('#form-surat-masuk').find('.form-control').not('#nomor_agenda').removeAttr('disabled')
        $('#form-surat-masuk').find('.form-select').removeAttr('disabled')
        $('#form-surat-masuk').find('#btn-save').removeAttr('disabled')
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
    } else {
        window.location.href = '/transaction/disposisi'
    }

    table.ajax.reload()
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
