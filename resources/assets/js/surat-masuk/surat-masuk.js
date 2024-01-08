
'use strict';

$(function () {
    const tanggalSurat = document.querySelector('#tanggal-surat'),
        tanggalDiterima = document.querySelector('#tanggal-diterima'),
        dropzoneBasic = document.querySelector('#dropzone-basic'),
        klasifikasi = $('#klasifikasi'),
        derajat = $('#derajat'),
        asal_surat = $('#asal_surat');
        
    // console.log('dropzone : ',dropzoneBasic);

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
                console.log('test')
                form.classList.add('was-validated')
            }, false)
        })

    $('#form-surat-masuk').on('submit', function (e) {
        if (this.checkValidity()) {
            console.log('test')
            e.preventDefault();
            postForm()
        }
        $(this).addClass('was-validated');
    });

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

    // console.log(previewTemplate)

    // Basic Dropzone
    // --------------------------------------------------------------------

    if (dropzoneBasic) {
        const myDropzone = new Dropzone(dropzoneBasic, {
            previewTemplate: previewTemplate,
            url: '/surat-masuk/store',
            parallelUploads: 1,
            maxFilesize: 5,
            addRemoveLinks: true,
            autoProcessQueue: false,
            maxFiles: 1
        });
    }

    // custom template to render icons
    function renderIcons(option) {
        if (!option.id) {
            return option.text;
        }
        var $icon = "<i class='" + $(option.element).data("icon") + " me-2'></i>" + option.text;
        return $icon
    }
    
    // Init select2
    $("#klasifikasi").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: asal_surat.parent(),
        templateResult: renderIcons,
        templateSelection: renderIcons,
        escapeMarkup: function(es) {
        return es;
        }
    });
    $("#derajat").wrap('<div class="position-relative"></div>').select2({
        dropdownParent: asal_surat.parent(),
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
});

function postForm() {
    let form =  new FormData($("#form-surat-masuk")[0])
    // let form = $('#form-surat-masuk').serialize()    
    ajaxPostFile('/surat-masuk/store', form, 'input_success', 'input_error')
}

function input_success(data) {
    if (data.status != 200) {
        optionerror.text = data.message
        Snackbar.show(optionerror);
        return false
    }
    Swal.fire(
        'Success!',
        data.message,
        'success'
    )

    location.href = '/surat-masuk';
}

function input_error(err) {
    console.log(err)
}