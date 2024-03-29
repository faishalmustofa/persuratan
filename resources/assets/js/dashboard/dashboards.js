/**
 *  Logistics Dashboard
 */

'use strict';

$(function () {
  let labelColor, headingColor, currentTheme, bodyColor;

  if (isDarkStyle) {
    labelColor = config.colors_dark.textMuted;
    headingColor = config.colors_dark.headingColor;
    bodyColor = config.colors_dark.bodyColor;
    currentTheme = 'dark';
  } else {
    labelColor = config.colors.textMuted;
    headingColor = config.colors.headingColor;
    bodyColor = config.colors.bodyColor;
    currentTheme = 'light';
  }

  // Chart Colors
  const chartColors = {
    donut: {
      series1: config.colors.success,
      series2: '#43ff64e6',
      series3: '#43ff6473',
      series4: '#43ff6433'
    },
    line: {
      series1: config.colors.warning,
      series2: config.colors.primary,
      series3: '#7367f029'
    }
  };

  const chartMingguanMasuk = document.querySelector('#chartMingguanMasuk'),
    chartMingguanMasukConfig = {
      series: [
        {
          name: 'Surat Masuk',
          type: 'column',
          data: []
        },
        {
          name: 'Diarsipkan',
          type: 'line',
          data: []
        }
      ],
      chart: {
        id: 'chartMingguanMasuk',
        height: 270,
        type: 'line',
        stacked: false,
        parentHeightOffset: 0,
        toolbar: {
          show: false
        },
        zoom: {
          enabled: false
        }
      },
      markers: {
        size: 4,
        colors: [config.colors.white],
        strokeColors: chartColors.line.series2,
        hover: {
          size: 6
        },
        borderRadius: 4
      },
      stroke: {
        curve: 'smooth',
        width: [0, 3],
        lineCap: 'round'
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          width: 8,
          height: 8,
          offsetX: -3
        },
        height: 40,
        offsetY: 10,
        itemMargin: {
          horizontal: 10,
          vertical: 0
        },
        fontSize: '15px',
        fontFamily: 'Inter',
        fontWeight: 400,
        labels: {
          colors: headingColor,
          useSeriesColors: false
        },
        offsetY: 10
      },
      grid: {
        strokeDashArray: 8
      },
      colors: [chartColors.line.series1, chartColors.line.series2],
      fill: {
        opacity: [1, 1]
      },
      plotOptions: {
        bar: {
          columnWidth: '30%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: false
      },
      xaxis: {
        tickAmount: 7,
        categories: [],
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          }
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        tickAmount: 4,
        min: 0,
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          },
          formatter: function (val) {
            return val;
          }
        }
      },
      responsive: [
        {
          breakpoint: 1400,
          options: {
            chart: {
              height: 270
            },
            xaxis: {
              labels: {
                style: {
                  fontSize: '10px'
                }
              }
            },
            legend: {
              itemMargin: {
                vertical: 0,
                horizontal: 10
              },
              fontSize: '13px',
              offsetY: 12
            }
          }
        },
        {
          breakpoint: 1399,
          options: {
            chart: {
              height: 415
            },
            plotOptions: {
              bar: {
                columnWidth: '50%'
              }
            }
          }
        },
        {
          breakpoint: 982,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '30%'
              }
            }
          }
        },
        {
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            },
            legend: {
              offsetY: 7
            }
          }
        }
      ]
    };
  if (typeof chartMingguanMasuk !== undefined && chartMingguanMasuk !== null) {
    const mingguan = new ApexCharts(chartMingguanMasuk, chartMingguanMasukConfig);
    mingguan.render();
  }

  const chartBulananMasuk = document.querySelector('#chartBulananMasuk'),
    chartBulananMasukConfig = {
      series: [
        {
          name: 'Surat Masuk',
          type: 'column',
          data: []
        },
        {
          name: 'Diarsipkan',
          type: 'line',
          data: []
        }
      ],
      chart: {
        id: 'chartBulananMasuk',
        height: 270,
        type: 'line',
        stacked: false,
        parentHeightOffset: 0,
        toolbar: {
          show: false
        },
        zoom: {
          enabled: false
        }
      },
      markers: {
        size: 4,
        colors: [config.colors.white],
        strokeColors: chartColors.line.series2,
        hover: {
          size: 6
        },
        borderRadius: 4
      },
      stroke: {
        curve: 'smooth',
        width: [0, 3],
        lineCap: 'round'
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          width: 8,
          height: 8,
          offsetX: -3
        },
        height: 40,
        offsetY: 10,
        itemMargin: {
          horizontal: 10,
          vertical: 0
        },
        fontSize: '15px',
        fontFamily: 'Inter',
        fontWeight: 400,
        labels: {
          colors: headingColor,
          useSeriesColors: false
        },
        offsetY: 10
      },
      grid: {
        strokeDashArray: 8
      },
      colors: [chartColors.line.series1, chartColors.line.series2],
      fill: {
        opacity: [1, 1]
      },
      plotOptions: {
        bar: {
          columnWidth: '30%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: false
      },
      xaxis: {
        tickAmount: 10,
        categories: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          }
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        tickAmount: 4,
        min: 0,
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          },
          formatter: function (val) {
            return val;
          }
        }
      },
      responsive: [
        {
          breakpoint: 1400,
          options: {
            chart: {
              height: 270
            },
            xaxis: {
              labels: {
                style: {
                  fontSize: '10px'
                }
              }
            },
            legend: {
              itemMargin: {
                vertical: 0,
                horizontal: 10
              },
              fontSize: '13px',
              offsetY: 12
            }
          }
        },
        {
          breakpoint: 1399,
          options: {
            chart: {
              height: 415
            },
            plotOptions: {
              bar: {
                columnWidth: '50%'
              }
            }
          }
        },
        {
          breakpoint: 982,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '30%'
              }
            }
          }
        },
        {
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            },
            legend: {
              offsetY: 7
            }
          }
        }
      ]
    };
  if (typeof chartBulananMasuk !== undefined && chartBulananMasuk !== null) {
    const bulanan = new ApexCharts(chartBulananMasuk, chartBulananMasukConfig);
    bulanan.render();
  }

  const chartMingguanKeluar = document.querySelector('#chartMingguanKeluar'),
    chartMingguanConfig = {
      series: [
        {
          name: 'Surat Keluar',
          type: 'column',
          data: []
        },
        {
          name: 'Dikirim',
          type: 'line',
          data: []
        }
      ],
      chart: {
        id: 'chartMingguanKeluar',
        height: 270,
        type: 'line',
        stacked: false,
        parentHeightOffset: 0,
        toolbar: {
          show: false
        },
        zoom: {
          enabled: false
        }
      },
      markers: {
        size: 4,
        colors: [config.colors.white],
        strokeColors: chartColors.line.series2,
        hover: {
          size: 6
        },
        borderRadius: 4
      },
      stroke: {
        curve: 'smooth',
        width: [0, 3],
        lineCap: 'round'
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          width: 8,
          height: 8,
          offsetX: -3
        },
        height: 40,
        offsetY: 10,
        itemMargin: {
          horizontal: 10,
          vertical: 0
        },
        fontSize: '15px',
        fontFamily: 'Inter',
        fontWeight: 400,
        labels: {
          colors: headingColor,
          useSeriesColors: false
        },
        offsetY: 10
      },
      grid: {
        strokeDashArray: 8
      },
      colors: [chartColors.line.series1, chartColors.line.series2],
      fill: {
        opacity: [1, 1]
      },
      plotOptions: {
        bar: {
          columnWidth: '30%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: false
      },
      xaxis: {
        tickAmount: 7,
        categories: [],
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          }
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        tickAmount: 4,
        min: 0,
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          },
          formatter: function (val) {
            return val;
          }
        }
      },
      responsive: [
        {
          breakpoint: 1400,
          options: {
            chart: {
              height: 270
            },
            xaxis: {
              labels: {
                style: {
                  fontSize: '10px'
                }
              }
            },
            legend: {
              itemMargin: {
                vertical: 0,
                horizontal: 10
              },
              fontSize: '13px',
              offsetY: 12
            }
          }
        },
        {
          breakpoint: 1399,
          options: {
            chart: {
              height: 415
            },
            plotOptions: {
              bar: {
                columnWidth: '50%'
              }
            }
          }
        },
        {
          breakpoint: 982,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '30%'
              }
            }
          }
        },
        {
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            },
            legend: {
              offsetY: 7
            }
          }
        }
      ]
    };
  if (typeof chartMingguanKeluar !== undefined && chartMingguanKeluar !== null) {
    const mingguan = new ApexCharts(chartMingguanKeluar, chartMingguanConfig);
    mingguan.render();
  }

  const chartBulananKeluar = document.querySelector('#chartBulananKeluar'),
    chartBulananConfig = {
      series: [
        {
          name: 'Surat Keluar',
          type: 'column',
          data: []
        },
        {
          name: 'Dikirim',
          type: 'line',
          data: []
        }
      ],
      chart: {
        id: 'chartBulananKeluar',
        height: 270,
        type: 'line',
        stacked: false,
        parentHeightOffset: 0,
        toolbar: {
          show: false
        },
        zoom: {
          enabled: false
        }
      },
      markers: {
        size: 4,
        colors: [config.colors.white],
        strokeColors: chartColors.line.series2,
        hover: {
          size: 6
        },
        borderRadius: 4
      },
      stroke: {
        curve: 'smooth',
        width: [0, 3],
        lineCap: 'round'
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          width: 8,
          height: 8,
          offsetX: -3
        },
        height: 40,
        offsetY: 10,
        itemMargin: {
          horizontal: 10,
          vertical: 0
        },
        fontSize: '15px',
        fontFamily: 'Inter',
        fontWeight: 400,
        labels: {
          colors: headingColor,
          useSeriesColors: false
        },
        offsetY: 10
      },
      grid: {
        strokeDashArray: 8
      },
      colors: [chartColors.line.series1, chartColors.line.series2],
      fill: {
        opacity: [1, 1]
      },
      plotOptions: {
        bar: {
          columnWidth: '30%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4
        }
      },
      dataLabels: {
        enabled: false
      },
      xaxis: {
        tickAmount: 10,
        categories: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          }
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        tickAmount: 4,
        min: 0,
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: 'Inter',
            fontWeight: 400
          },
          formatter: function (val) {
            return val;
          }
        }
      },
      responsive: [
        {
          breakpoint: 1400,
          options: {
            chart: {
              height: 270
            },
            xaxis: {
              labels: {
                style: {
                  fontSize: '10px'
                }
              }
            },
            legend: {
              itemMargin: {
                vertical: 0,
                horizontal: 10
              },
              fontSize: '13px',
              offsetY: 12
            }
          }
        },
        {
          breakpoint: 1399,
          options: {
            chart: {
              height: 415
            },
            plotOptions: {
              bar: {
                columnWidth: '50%'
              }
            }
          }
        },
        {
          breakpoint: 982,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '30%'
              }
            }
          }
        },
        {
          breakpoint: 480,
          options: {
            chart: {
              height: 250
            },
            legend: {
              offsetY: 7
            }
          }
        }
      ]
    };
  if (typeof chartBulananKeluar !== undefined && chartBulananKeluar !== null) {
    const bulanan = new ApexCharts(chartBulananKeluar, chartBulananConfig);
    bulanan.render();
  }

  getDataHarianMasuk('1');
  getDataMingguanMasuk('01');
  getDataHarianKeluar('1');
  getDataMingguanKeluar('01');
});

function getDataHarianMasuk(waktu) {
  ajaxGetJson(`/data-harian-masuk/${waktu}`, 'showDataHarianMasuk', 'error_notif');
}

function getDataMingguanMasuk(waktu) {
  ajaxGetJson(`/data-mingguan-masuk/${waktu}`, 'showDataMingguanMasuk', 'error_notif');
}

function getDataHarianKeluar(waktu) {
  ajaxGetJson(`/data-harian-keluar/${waktu}`, 'showDataHarianKeluar', 'error_notif');
}

function getDataMingguanKeluar(waktu) {
  ajaxGetJson(`/data-mingguan-keluar/${waktu}`, 'showDataMingguanKeluar', 'error_notif');
}

function showDataHarianMasuk(res) {
  if (res.status != 200) {
    var text = res.message;
    error_notif(text);
    return false;
  }

  console.log(res.data);
  $('#buttonMingguanMasuk').text(res.data.time);
  ApexCharts.exec('chartMingguanMasuk', 'updateOptions', {
    series: [
      {
        name: 'Surat Masuk',
        type: 'column',
        data: res.data.suratmasuk
      },
      {
        name: 'Diarsipkan',
        type: 'line',
        data: res.data.diarsipkan
      }
    ],
    xaxis: {
      tickAmount: 7,
      categories: res.data.tanggal
    }
  });
}

function showDataMingguanMasuk(res) {
  if (res.status != 200) {
    var text = res.message;
    error_notif(text);
    return false;
  }

  console.log(res.data);
  $('#buttonBulananMasuk').text(res.data.time);
  ApexCharts.exec(
    'chartBulananMasuk',
    'updateSeries',
    [
      {
        name: 'Surat Masuk',
        type: 'column',
        data: res.data.suratmasuk
      },
      {
        name: 'Diarsipkan',
        type: 'line',
        data: res.data.diarsipkan
      }
    ],
    true
  );
}

function showDataHarianKeluar(res) {
  if (res.status != 200) {
    var text = res.message;
    error_notif(text);
    return false;
  }

  console.log(res.data);
  $('#buttonMingguanKeluar').text(res.data.time);
  ApexCharts.exec('chartMingguanKeluar', 'updateOptions', {
    series: [
      {
        name: 'Surat Keluar',
        type: 'column',
        data: res.data.suratkeluar
      },
      {
        name: 'Dikirim',
        type: 'line',
        data: res.data.diarsipkan
      }
    ],
    xaxis: {
      tickAmount: 7,
      categories: res.data.tanggal
    }
  });
}

function showDataMingguanKeluar(res) {
  if (res.status != 200) {
    var text = res.message;
    error_notif(text);
    return false;
  }
  $('#buttonBulananKeluar').text(res.data.time);

  ApexCharts.exec(
    'chartBulananKeluar',
    'updateSeries',
    [
      {
        name: 'Surat Keluar',
        type: 'column',
        data: res.data.suratkeluar
      },
      {
        name: 'Dikirim',
        type: 'line',
        data: res.data.diarsipkan
      }
    ],
    true
  );
}

function error_notif(text) {
  Command: toastr['error'](text, 'Gagal Menampilkan Data');

  toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: false,
    progressBar: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    onclick: null,
    showDuration: '300',
    hideDuration: '1000',
    timeOut: '5000',
    extendedTimeOut: '1000',
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
  };
}

// DataTable (jquery)
// --------------------------------------------------------------------
// $(function () {
//   // Variable declaration for table
//   var dt_dashboard_table = $('.dt-route-vehicles');

//   // On route vehicles DataTable
//   if (dt_dashboard_table.length) {
//     var dt_dashboard = dt_dashboard_table.DataTable({
//       ajax: assetsPath + 'json/logistics-dashboard.json',
//       columns: [
//         { data: 'id' },
//         { data: 'id' },
//         { data: 'location' },
//         { data: 'start_city' },
//         { data: 'end_city' },
//         { data: 'warnings' },
//         { data: 'progress' }
//       ],
//       columnDefs: [
//         {
//           // For Responsive
//           className: 'control',
//           orderable: false,
//           searchable: false,
//           responsivePriority: 2,
//           targets: 0,
//           render: function (data, type, full, meta) {
//             return '';
//           }
//         },
//         {
//           // For Checkboxes
//           targets: 1,
//           orderable: false,
//           searchable: false,
//           checkboxes: true,
//           responsivePriority: 3,
//           render: function () {
//             return '<input type="checkbox" class="dt-checkboxes form-check-input">';
//           },
//           checkboxes: {
//             selectAllRender: '<input type="checkbox" class="form-check-input">'
//           }
//         },
//         {
//           // Icon and location
//           targets: 2,
//           responsivePriority: 1,
//           render: function (data, type, full, meta) {
//             var $location = full['location'];
//             // Creates full output for row
//             var $row_output =
//               '<div class="d-flex justify-content-start align-items-center user-name">' +
//               '<div class="avatar-wrapper">' +
//               '<div class="avatar me-2">' +
//               '<span class="avatar-initial rounded-circle bg-label-secondary"><i class="mdi mdi-bus"></i></span>' +
//               '</div>' +
//               '</div>' +
//               '<div class="d-flex flex-column">' +
//               '<a class="text-heading fw-medium" href="' +
//               baseUrl +
//               'app/logistics/fleet">VOL-' +
//               $location +
//               '</a>' +
//               '</div>' +
//               '</div>';
//             return $row_output;
//           }
//         },
//         {
//           // starting route
//           targets: 3,
//           render: function (data, type, full, meta) {
//             var $start_city = full['start_city'],
//               $start_country = full['start_country'];
//             var $row_output = '<div class="text-body">' + $start_city + ', ' + $start_country + '</div >';
//             return $row_output;
//           }
//         },
//         {
//           // ending route
//           targets: 4,
//           render: function (data, type, full, meta) {
//             var $end_city = full['end_city'],
//               $end_country = full['end_country'];
//             var $row_output = '<div class="text-body">' + $end_city + ', ' + $end_country + '</div >';
//             return $row_output;
//           }
//         },
//         {
//           // warnings
//           targets: -2,
//           render: function (data, type, full, meta) {
//             var $status_number = full['warnings'];
//             var $status = {
//               1: { title: 'No Warnings', class: 'bg-label-success' },
//               2: {
//                 title: 'Temperature Not Optimal',
//                 class: 'bg-label-warning'
//               },
//               3: { title: 'Ecu Not Responding', class: 'bg-label-danger' },
//               4: { title: 'Oil Leakage', class: 'bg-label-info' },
//               5: { title: 'fuel problems', class: 'bg-label-primary' }
//             };
//             if (typeof $status[$status_number] === 'undefined') {
//               return data;
//             }
//             return (
//               '<span class="badge rounded-pill ' +
//               $status[$status_number].class +
//               '">' +
//               $status[$status_number].title +
//               '</span>'
//             );
//           }
//         },
//         {
//           // progress
//           targets: -1,
//           render: function (data, type, full, meta) {
//             var $progress = full['progress'];
//             var $progress_output =
//               '<div class="d-flex align-items-center">' +
//               '<div div class="progress w-100 rounded" style="height: 8px;">' +
//               '<div class="progress-bar" role="progressbar" style="width:' +
//               $progress +
//               '%;" aria-valuenow="' +
//               $progress +
//               '" aria-valuemin="0" aria-valuemax="100"></div>' +
//               '</div>' +
//               '<div class="text-body ms-3">' +
//               $progress +
//               '%</div>' +
//               '</div>';
//             return $progress_output;
//           }
//         }
//       ],
//       order: [2, 'asc'],
//       dom: '<"table-responsive"t><"row d-flex align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
//       displayLength: 5,
//       responsive: {
//         details: {
//           display: $.fn.dataTable.Responsive.display.modal({
//             header: function (row) {
//               var data = row.data();
//               return 'Details of ' + data['location'];
//             }
//           }),
//           type: 'column',
//           renderer: function (api, rowIdx, columns) {
//             var data = $.map(columns, function (col, i) {
//               return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
//                 ? '<tr data-dt-row="' +
//                     col.rowIndex +
//                     '" data-dt-column="' +
//                     col.columnIndex +
//                     '">' +
//                     '<td>' +
//                     col.title +
//                     ':' +
//                     '</td> ' +
//                     '<td>' +
//                     col.data +
//                     '</td>' +
//                     '</tr>'
//                 : '';
//             }).join('');

//             return data ? $('<table class="table"/><tbody />').append(data) : false;
//           }
//         }
//       }
//     });
//     $('.dataTables_info').addClass('pt-0');
//   }
// });
