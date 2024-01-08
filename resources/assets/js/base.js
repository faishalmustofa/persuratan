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
            $('.load_process').css('display', 'block')
        },
        success: function (data, status, xhr) {   // success callback function
            $('.load_process').css('display', 'none')
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
            $('.load_process').css('display', 'block')
        }, success: function (data, status, xhr) {   // success callback function
            $('.load_process').css('display', 'none')
            window[onsuccess](data);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback
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

async function ajaxPostFile(url, form, onsuccess, onerror) {
    console.log(form)
    $.ajax(url, {
        type: 'post',
        data: form,
        processData: false,
		contentType: false,
		cache: false,
        beforeSend: function () {
            $('.load_process').css('display', 'block')
        }, success: function (data, status, xhr) {   // success callback function
            $('.load_process').css('display', 'none')
            window[onsuccess](data);
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback
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
