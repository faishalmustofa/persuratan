/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
    let borderColor, bodyBg, headingColor;

    if (isDarkStyle) {
        borderColor = config.colors_dark.borderColor;
        bodyBg = config.colors_dark.bodyBg;
        headingColor = config.colors_dark.headingColor;
    } else {
        borderColor = config.colors.borderColor;
        bodyBg = config.colors.bodyBg;
        headingColor = config.colors.headingColor;
    }

    // Variable declaration for table
    var dt_user_table = $('.datatables-users'),
        select2AddUser = $('#organization'),
        select2EditUser = $('#edit-organization'),
        userView = baseUrl + 'app/user/view/account',
        statusObj = {
            1: { title: 'Pending', class: 'bg-label-warning' },
            2: { title: 'Active', class: 'bg-label-success' },
            3: { title: 'Inactive', class: 'bg-label-secondary' }
        };

    if (select2AddUser.length) {
        var $this = select2AddUser;
        select2Focus($this);
        $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select Organization',
        dropdownParent: $this.parent()
        });
    }
    
    if (select2EditUser.length) {
        var $this = select2EditUser;
        select2Focus($this);
        $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select Organization',
        dropdownParent: $this.parent()
        });
    }

    // Users datatable
    if (dt_user_table.length) {
        var dt_user = dt_user_table.DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            "pageLength": 10,
            ajax: {
                url: "/user/data",
                method: "post",
                data: function (data) {
                    data._token = $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: '' },
                { data: 'name' },
                { data: 'username' },
                { data: 'organization' },
                { data: 'jabatan' },
                { data: 'action' }
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    // User full name and email
                    targets: 1,
                    responsivePriority: 4,
                    render: function (data, type, full, meta) {
                        var $name = full['name'],
                        $email = full['email'],
                        $image = full['avatar'];
                        if ($image) {
                            // For Avatar image
                            var $output = '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
                        } else {
                            // For Avatar badge
                            var stateNum = Math.floor(Math.random() * 6);
                            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                            var $state = states[stateNum],
                                $name = full['name'],
                                $initials = $name.match(/\b\w/g) || [];
                            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                            $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
                        }
                        // Creates full output for row
                        var $row_output =
                            '<div class="d-flex justify-content-start align-items-center user-name">' +
                            '<div class="avatar-wrapper">' +
                            '<div class="avatar avatar-sm me-3">' +
                            $output +
                            '</div>' +
                            '</div>' +
                            '<div class="d-flex flex-column">' +
                            '<a href="' +
                            userView +
                            '" class="text-truncate"><span class="fw-medium text-heading">' +
                            $name +
                            '</span></a>' +
                            '<small>' +
                            $email +
                            '</small>' +
                            '</div>' +
                            '</div>';
                        return $row_output;
                    }
                },
                {
                    // Username
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $username = full['username'];

                        return '<span class="text-heading">' + $username + '</span>';
                    }
                },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var $id = full['id'];
                        return (
                            '<div class="d-inline-block text-nowrap">' +
                            '<button class="btn btn-sm btn-icon btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical mdi-20px"></i></button>' +
                            '<div class="dropdown-menu dropdown-menu-end m-0">' +
                            '<a href="javascript:void(0)" class="dropdown-item" id="viewAccount"><i class="mdi mdi-eye-outline me-2"></i><span>View</span></a>' +
                            '<a href="javascript:void(0)" class="dropdown-item" onclick="editAccount('+$id+')"><i class="mdi mdi-pencil-outline me-2"></i><span>Edit</span></a>' +
                            '<a href="javascript:void(0)" class="dropdown-item delete-record" onclick="deleteAccount('+$id+')"><i class="mdi mdi-delete-outline me-2"></i><span>Delete</span></a>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
            ],
            order: [[2, 'desc']],
            dom:
                '<"row mx-2"' +
                '<"col-md-2"<"me-3"l>>' +
                '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0 gap-3"fB>>' +
                '>t' +
                '<"row mx-2"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: '',
                searchPlaceholder: 'Search..'
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-secondary dropdown-toggle me-3 waves-effect waves-light',
                    text: '<i class="mdi mdi-export-variant me-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                        extend: 'print',
                        text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                            // prevent avatar to be print
                            format: {
                            body: function (inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                    result = result + item.textContent;
                                } else result = result + item.innerText;
                                });
                                return result;
                            }
                            }
                        },
                        customize: function (win) {
                            //customize print view for dark
                            $(win.document.body)
                            .css('color', headingColor)
                            .css('border-color', borderColor)
                            .css('background-color', bodyBg);
                            $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                        }
                        },
                        {
                        extend: 'csv',
                        text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                            // prevent avatar to be display
                            format: {
                            body: function (inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                    result = result + item.textContent;
                                } else result = result + item.innerText;
                                });
                                return result;
                            }
                            }
                        }
                        },
                        {
                        extend: 'excel',
                        text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                            // prevent avatar to be display
                            format: {
                            body: function (inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                    result = result + item.textContent;
                                } else result = result + item.innerText;
                                });
                                return result;
                            }
                            }
                        }
                        },
                        {
                        extend: 'pdf',
                        text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                            // prevent avatar to be display
                            format: {
                            body: function (inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                    result = result + item.textContent;
                                } else result = result + item.innerText;
                                });
                                return result;
                            }
                            }
                        }
                        },
                        {
                        extend: 'copy',
                        text: '<i class="mdi mdi-content-copy me-1"></i>Copy',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5],
                            // prevent avatar to be display
                            format: {
                            body: function (inner, coldex, rowdex) {
                                if (inner.length <= 0) return inner;
                                var el = $.parseHTML(inner);
                                var result = '';
                                $.each(el, function (index, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                    result = result + item.textContent;
                                } else result = result + item.innerText;
                                });
                                return result;
                            }
                            }
                        }
                        }
                    ]
                },
                {
                text: '<i class="mdi mdi-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Add User</span>',
                className: 'add-new btn btn-primary waves-effect waves-light',
                attr: {
                    'data-bs-toggle': 'offcanvas',
                    'data-bs-target': '#offcanvasAddUser'
                }
                }
            ],
            // For responsive popup
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                        var data = row.data();
                        return 'Details of ' + data['full_name'];
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
            },
            
            initComplete: function () {
                // Adding role filter once table initialized
                this.api().columns(3).every(function () {
                    var column = this;
                    var select = $(
                        '<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Email </option></select>'
                    ).appendTo('.user_role') .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });
                });
                // Adding plan filter once table initialized
                this.api().columns(4).every(function () {
                    var column = this;
                    var select = $(
                    '<select id="UserPlan" class="form-select text-capitalize"><option value=""> Select Role </option></select>'
                    ).appendTo('.user_plan').on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });
                });

                // Adding status filter once table initialized
                // this.api().columns(6).every(function () {
                //     var column = this;
                //     var select = $(
                //     '<select id="FilterTransaction" class="form-select text-capitalize"><option value=""> Select Status </option></select>'
                //     ).appendTo('.user_status').on('change', function () {
                //         var val = $.fn.dataTable.util.escapeRegex($(this).val());
                //         column.search(val ? '^' + val + '$' : '', true, false).draw();
                //     });

                //     column.data().unique().sort().each(function (d, j) {
                //         select.append(
                //         '<option value="' +
                //             statusObj[d].title +
                //             '" class="text-capitalize">' +
                //             statusObj[d].title +
                //             '</option>'
                //         );
                //     });
                // });
            }
        });
    }

    // Delete Record
    $('.datatables-users tbody').on('click', '.delete-record', function () {
        dt_user.row($(this).parents('tr')).remove().draw();
    });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);

    $('#form-tambah-user').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            postForm()
            $(this).addClass('was-validated');
        }
    });
    
    $('#form-edit-user').on('submit', function (e) {
        if (this.checkValidity()) {
            e.preventDefault();
            updateForm($('#idUser').val())
            $(this).addClass('was-validated');
        }
    });
});

function postForm() {
    let form =  $("#form-tambah-user").serialize()
    ajaxPostJson('/user/store', form, 'input_success', 'input_error')
}

function updateForm(id) {
    let form =  $("#form-edit-user").serialize()
    id = btoa(id)
    ajaxPostJson('/user/update/'+id, form, 'input_success', 'input_error')
}

function editAccount(id) {
    id = btoa(id)
    ajaxGetJson(`/user/edit/${id}`, 'returnDataEdit', 'error_get')
}

function deleteAccount(id) {
    id = btoa(id)
    ajaxGetJson(`/user/destroy/${id}`, 'input_success', 'error_get')
}

function returnDataEdit(res){
    if (res.success) {
        $('#offcanvasEditUser').offcanvas('show')

        $('#idUser').attr('value',res.user.id)
        $('#edit-user-fullname').attr('value',res.user.name)
        $('#edit-username').attr('value',res.user.username)
        $('#edit-user-email').attr('value',res.user.email)
        $('#edit-organization').val(res.user.organization).trigger('change');
        $('#edit-jabatan').attr('value',res.user.jabatan)
    } else {
        error_notif()
    }
}

function input_success(data) {
    Swal.close()

    if (data.status != 200) {
        var text = data.message
        error_notif(text)
        return false
    }

    Command: toastr["success"](data.message, data.title)

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
        icon: 'success',
        showDenyButton: false,
        title: "Success",
        showCancelButton: false,
        showConfirmButton: false,
        timer: 1000
    });

    location.reload()
    $('#form-tambah-user').removeClass('was-validated')
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
    Command: toastr["error"](text, "Gagal ambil Data")

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