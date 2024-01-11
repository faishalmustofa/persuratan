toastAnimationExample = document.querySelector('.toast-ex')
toastAnimationExample.querySelector('i.mdi').classList.add('text-error');
toastAnimationExample.classList.add('animate__tada');
toastAnimation = new bootstrap.Toast(toastAnimationExample);

var optionerror = {
    text: "Terjadi Kesalahan Pada Sistem!",
    pos: 'top-center',
    backgroundColor: '#e7515a'
}

async function ajaxGetJson(url, onsuccess, onerror) {
    $.ajax(url, {
        type: 'get',
        dataType: 'json',
        beforeSend: function () {
            Swal.fire({
                html: "<h5>Please Wait...</h5>",
                customClass: {
                },
                buttonsStyling: false,
                showConfirmButton: false,
                allowOutsideClick: false,
            })
            Swal.showLoading()
        },
        success: function (data, status, xhr) {   // success callback function
            Swal.close()
            window[onsuccess](data);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback
            $('.load_process').css('display', 'none')
            let text = jqXhr.responseJSON?.message == undefined ? "Terjadi Kesalahan Pada Sistem!" : jqXhr.responseJSON.message
            var option = {
                text: text,
                pos: 'top-center',
                backgroundColor: '#e7515a'
            }
            // Snackbar.show(option);
            window[onerror](errorMessage);
        }
    })
}

async function ajaxPostJson(url, form, onsuccess, onerror) {
    $.ajax(url, {
        type: 'post',
        dataType: 'json',
        data: form,
        beforeSend: function () {
            Swal.fire({
                html: "<h5>Please Wait...</h5>",
                customClass: {
                },
                buttonsStyling: false,
                showConfirmButton: false,
                allowOutsideClick: false,
            })
            Swal.showLoading()
        }, success: function (data, status, xhr) {   // success callback function
            Swal.close()
            window[onsuccess](data);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback
            Swal.close()
            // let text = jqXhr.responseJSON?.message == undefined ? "Terjadi Kesalahan Pada Sistem!" : jqXhr.responseJSON.message
            // var option = {
            //     text: text,
            //     pos: 'top-center',
            //     backgroundColor: '#e7515a'
            // }
            // Snackbar.show(option);
            toastAnimation.show();
            // window[onerror](jqXhr);
        }
    })

}

async function ajaxPostFile(url, form, onsuccess, onerror) {
    $.ajax(url, {
        type: 'post',
        data: form,
        processData: false,
		contentType: false,
        beforeSend: function () {
            Swal.fire({
                html: "<h5>Please Wait...</h5>",
                customClass: {
                },
                buttonsStyling: false,
                showConfirmButton: false,
                allowOutsideClick: false,
            })
            Swal.showLoading()
        }, success: function (data, status, xhr) {   // success callback function
            // $('.load_process').css('display', 'none')
            Swal.close()
            window[onsuccess](data);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback
            Swal.close()
            $('.load_process').css('display', 'none')
            // let text = jqXhr.responseJSON?.message == undefined ? "Terjadi Kesalahan Pada Sistem!" : jqXhr.responseJSON.message
            // var option = {
            //     text: text,
            //     pos: 'top-center',
            //     backgroundColor: '#e7515a'
            // }
            // Snackbar.show(option);
            toastAnimation.show();
            // window[onerror](jqXhr);
        }
    })

}
