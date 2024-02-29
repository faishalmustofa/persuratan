var table, valTujuan
$( function(){
    var forms = document.querySelectorAll('.needs-validation')
    var flatpickrMulti = document.querySelector("#tgl_kirim");

    flatpickrMulti.flatpickr({
        enableTime: true,
        dateFormat: "d-F-Y H:i",
        "locale": "id",
        defaultDate: new Date(),
    });

    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })

    $('#form-disposisi').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postDisposisi()
            $(this).addClass('was-validated');
        }
    });

    $('#form-pengiriman').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            var type = $(this).find('input[name="type_kirim"]').val()
            postPengiriman(type)
            $(this).addClass('was-validated');
        }
    });

    $('#form-bulking').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            sendBulking()
        }
    });

    $("#tujuan_surat_bulking").select2();

    $('#form-pencarian').on('submit', function (e) {
        searchData()
    });

    searchData()
})

function searchData(){
    $('#container-data').slideUp()
    $('#table-list').DataTable().destroy();

    const nomor_agenda = $('#nomor_agenda').val()

    table = $('#table-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/disposisi/get-data",
            method: "post",
            data: function(d){
                d._token = $('meta[name="csrf-token"]').attr('content')
                d.nomor_agenda = nomor_agenda
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

function kirimDisposisi(txNo, noSurat, noAgenda, type = 'single') {
    $('#form-pengiriman').find('input[name="type_kirim"]').val(type)

    if(type == 'bulking'){
        $('#form-pengiriman').find('#data-surat').fadeOut()
        $('#form-pengiriman').find('input[name="_token"]').remove()
    } else {
        if($('#form-pengiriman').find('input[name="_token"]').length == 0){
            $('#form-pengiriman').append(`<input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">`)
        }

        $('#form-pengiriman').find('#data-surat').fadeIn()
        $('#form-pengiriman').find('input[name="tx_number"]').val(txNo)
        $('#form-pengiriman').find('#nomor_agenda').val(noAgenda)
        $('#form-pengiriman').find('#no_surat').val(noSurat)
    }

    $('#modal-kirim').modal('toggle')
}

async function postPengiriman(type){
    if(type == 'single'){
        $('#nomor_agenda').val('')
        let form = $('#form-pengiriman').serialize()
        await ajaxPostJson('/transaction/disposisi/pengiriman-surat', form, 'input_success', 'input_error')
        $('#modal-kirim').modal('toggle')
    } else if (type == 'bulking'){
        await postDataBulking()
        $('#modal-kirim').modal('toggle')
    }

    var currentUrl = window.location.href
    var newURL = currentUrl.split('/');
    if(newURL.length > 5){
        newURL = currentUrl.replace('/'+newURL[newURL.length-1], '')
        history.pushState({}, null, newURL)
    }
}

function detailDisposisi(txNo) {
    txNo = btoa(txNo)
    ajaxGetJson(`/transaction/disposisi/detail/${txNo}`, 'showModalDetail', 'error_get')
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
        $('#modal-detail').find('#header-data').find(`#${key}`).html(header[key])
    })

    let elTujuan = '';
    for (let i = 0; i < detail.tujuan_disposisi.length; i++) {
        elTujuan += `<h5 class="me-2"><span class="badge rounded-pill bg-label-primary">${detail.tujuan_disposisi[i]}</span></h5>`
    }

    $('#modal-detail').modal('toggle')

    $('#modal-detail').find('#detail-data').find('#tujuan_disposisi').html(elTujuan)
    $('#modal-detail').find('#detail-data').find('#isi_disposisi').html(detail.isi_disposisi)

}

function actionPrintBlanko(txNumber){
    ajaxGetJson(`/transaction/surat-masuk/print-blanko/${txNumber}`, 'printBlanko', 'input_error')
}

function printBlanko(data){
    window.open(data.filePath, '_blank')
    table.ajax.reload()
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
            url: "/transaction/disposisi/get-data",
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
                    return `
                        <input type="checkbox" name="txNumber[]" class="dt-checkboxes form-check-input" value="${data.tx_number}">
                    `;
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
            kirimDisposisi('', '', '', 'bulking')
            // postData()
        } else {
            return false
        }
    });

}

async function postDataBulking(){
    let form = $('#form-bulking').serialize()
    form += '&'+$('#form-pengiriman').serialize()

    await ajaxPostJson('/transaction/disposisi/kirim-bundling', form, 'input_success', 'input_error')
    tableBulking.ajax.reload()

    $('#modal-bulking').modal('toggle')
}

function revisiDisposisi(txNo){
    Swal.fire({
        title: "Anda yakin ingin melakukan revisi disposisi berkas ini?",
        text: "Jika anda melakukan revisi disposisi, maka proses surat harus diulang dari awal (print blanko disposisi)",
        icon: 'warning',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Ya, Revisi Disposisi",
        denyButtonText: `Tidak`,
        showCancelButton: false,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            txNo = btoa(txNo)
            ajaxGetJson(`/transaction/disposisi/revisi/${txNo}`, 'input_success', 'input_error')
        } else {
            return false
        }
    });
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


function input_success(data) {
    Swal.close()
    table.destroy()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    if(data.do_next == 'print_blanko'){
        // actionPrintBlanko(data.txNo)
        window.location.href = '/transaction/surat-masuk'
        // pindahBerkas(data.txNo, 'TAUD')
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

    searchData()
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

function error_get(err) {
    console.log(err)
    Swal.close()
    Command: toastr["error"]("Harap coba lagi beberapa saat lagi", "Terjadi Kesalahan Saat Mengambil Data")

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
