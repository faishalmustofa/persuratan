var table
$( function(){
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

    $('#form-add').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            insertData()
        }
    });

    $('#form-update').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            updateData()
        }
    });

    getData()
})

function getData(){
    table = $('#table-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/master-data/jenis-surat/data",
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
                data: 'nama',
                name: 'nama',
            },
            {
                data: 'format',
                name: 'format',
            },
            {
                data: 'deskripsi',
                name: 'deskripsi',
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

function insertData(){
    let form =  $("#form-add").serialize()
    ajaxPostJson('/master-data/jenis-surat/store', form, 'input_success', 'input_error');

    window.location.href = '/master-data/jenis-surat'
}

function updateData(){
    let form =  $("#form-update").serialize()
    const id = $('#form-update').find('input[name="id"]').val()
    ajaxPostJson(`/master-data/jenis-surat/update/${id}`, form, 'update_success', 'input_error');

    // window.location.href = '/master-data/jenis-surat'
}

function deleteData(id){
    Swal.fire({
        title: 'Apakah anda yakin ingin menghapus jenis surat ini?',
        text: "Data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            ajaxGetJson(`/master-data/jenis-surat/destroy/${id}`, 'delete_success', 'input_error');
        }
    })
}

function delete_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"]("Data Berhasil Dihapus", "Berhasil Hapus Data")

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
        title: 'Berhasil!',
        text: 'Hapus Data Berhasil!',
        type: 'success',
        timer: 1500,
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false,
        showConfirmButton: false,
    })

    // table.ajax.reload()
    window.location.replace(data.redirect)
}

function update_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"]("Data Berhasil Diupdate", "Berhasil Update Data")

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
        title: 'Berhasil!',
        text: 'Update Data Berhasil!',
        type: 'success',
        timer: 1500,
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false,
        showConfirmButton: false,
    })

    // table.ajax.reload()
    window.location.replace(data.redirect)
}

function input_success(data) {
    Swal.close()
    table.ajax.reload()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"](data.message, "Berhasil Memproses Data")

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
    console.log(err)
    Command: toastr["error"]("Harap coba lagi beberapa saat lagi", "Gagal Input Data")

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