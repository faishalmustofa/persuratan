$( function(){
    getDataSuratMasuk()

    AOS.init({
        disable: function () {
          const maxWidth = 1024;
          return window.innerWidth < maxWidth;
        },
        once: true
    });
})
function getDataSuratMasuk(){
    table = $('#table-list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 10,
        ajax: {
            url: "/transaction/log-surat-masuk/data",
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
}
