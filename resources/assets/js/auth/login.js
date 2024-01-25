'use strict';
$(function(){
    var forms = document.querySelectorAll('.needs-validation')
    console.log(forms)
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
    $('#formAuthentication').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            // console.log('masuk')
            login_process()
        }

        $(this).addClass('was-validated');

    });
})

async function login_process() {
    var form = $('#formAuthentication').serialize()
    ajaxPostJson('login-process', form, 'success_login', 'error_login')
}

function success_login(data) {
    if (data.status != 200) {
        Swal.fire({
            title: 'Gagal!',
            text: 'Gagal login!',
            icon: 'error',
        });
        return
    }
    console.log(data)
    Swal.fire({
        title: 'Berhasil!',
        text: 'Login berhasil!',
        type: 'success',
        customClass: {
          confirmButton: 'btn btn-primary waves-effect waves-light'
        },
        buttonsStyling: false,
        confirmButton: false,
      })
    location.href = '/';
}

function error_login(err) {
    console.log(err);
}
