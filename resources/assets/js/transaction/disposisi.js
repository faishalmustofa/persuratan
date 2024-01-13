var table

$( function(){
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

    $('#form-disposisi').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postDisposisi()
            $(this).addClass('was-validated');
        }
    });

    $('#form-pencarian').on('submit', function (e) {
        searchData()
    });
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
                responsivePriority: 2
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

    function postData(){
        let form = $('#form-disposisi').serialize()
        ajaxPostJson('/transaction/disposisi/store', form, 'input_success', 'input_error')
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
        $('#header-data').find(`#${key}`).html(header[key])
    })

    let elTujuan = '';
    for (let i = 0; i < detail.tujuan_disposisi.length; i++) {
        elTujuan += `<h5 class="me-2"><span class="badge rounded-pill bg-label-primary">${detail.tujuan_disposisi[i]}</span></h5>`
    }

    $('#detail-data').find('#tujuan_disposisi').html(elTujuan)
    $('#detail-data').find('#isi_disposisi').html(detail.isi_disposisi)

    $('#modal-detail').modal('toggle')
}

function input_success(data) {
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

    $('#modal-disposisi').modal('toggle')
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
