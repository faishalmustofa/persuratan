var table
const tglSurat = document.querySelector('#tgl-surat')
const asal_surat = $('#asal_surat');
const tujuan_surat = $('#tujuan_surat');

$( function(){
    tglSurat.flatpickr({
        mode: "range"
    });

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

    $('#container-data').slideDown()
    // ajaxPostJson('/transaction/buku-agenda/get-data', form, 'renderListData', 'error_insert')
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

function renderListData(data){
    console.log(data)
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
