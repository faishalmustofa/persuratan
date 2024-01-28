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
        await ajaxPostJson('/transaction/disposisi/store', form, 'input_success_', 'input_error')
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
