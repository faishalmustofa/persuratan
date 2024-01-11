
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
            { data: '', name: '' },
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
                data: 'tujuan_surat',
                name: 'tujuan_surat',
            },
            {
                data: 'perihal',
                name: 'perihal',
            },
            {
                data: 'status_surat.name',
                name: 'status_surat.name',
            }
        ],
        columnDefs: [
            {
                // For Responsive
                className: 'control',
                orderable: false,
                responsivePriority: 2,
                searchable: false,
                targets: 0,
                render: function (data, type, full, meta) {
                    return '';
                }
            },
        ],
        order: [[4, 'desc']],
        responsive: {
            details: {
              display: $.fn.dataTable.Responsive.display.modal({
                header: function (row) {
                  var data = row.data();
                  return 'Detail Data';
                }
              }),
              type: 'column',
              renderer: function (api, rowIdx, columns) {
                var data = $.map(columns, function (col, i) {
                  return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                    ? '<tr data-dt-row="' +
                        col.rowIndex +
                        '" data-dt-column="' +
                        col.columnIndex +
                        '">' +
                        '<td>' +
                        col.title +
                        ':' +
                        '</td> ' +
                        '<td>' +
                        col.data +
                        '</td>' +
                        '</tr>'
                    : '';
                }).join('');

                return data ? $('<table class="table"/><tbody />').append(data) : false;
              }
            }
        }
    });
}

function postForm() {
    let form =  new FormData($("#form-surat-masuk")[0])
    // let form = $('#form-surat-masuk').serialize()
    ajaxPostFile('/transaction/surat-masuk/store', form, 'input_success', 'input_error')
}

function postDisposisi() {
    let form = $('#form-disposisi').serialize()

    ajaxPostJson('/transaction/disposisi/store', form, 'disposisi_succes', 'input_error')
}

function disposisi_succes(data) {
    window.location.href = `/print/${data.file}`
    setTimeout(() => {
        window.location.reload()
    }, 2000);
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

    table.ajax.reload()

    Swal.fire({
        title: "Apakah anda ingin langsung melakukan disposisi?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Ya, Lakukan Disposisi",
        denyButtonText: `Tidak, Nanti Saja`,
        showCancelButton: false,
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $('#modal-disposisi').modal('toggle')
            $('#form-disposisi').find('input[name="tx_number"]').val(data.txNumber)
            $('#form-disposisi').find('input[name="nomor_agenda"]').val(data.noAgenda)
            $("#tujuan_disposisi").select2({
                dropdownParent : $('#modal-disposisi .modal-content')
            });
        } else {
            $('#modal-disposisi').modal('toggle')
            $('#form-disposisi').find('input[name="tx_number"]').val(data.txNumber)
            $('#form-disposisi').find('input[name="nomor_agenda"]').val(data.noAgenda)
            $("#tujuan_disposisi").select2({
                dropdownParent : $('#modal-disposisi .modal-content')
            });
        }
      });
}

function input_error(err) {
    Swal.close()
    console.log(err)
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
