/*
 * Dashboard bootstrap script:
 * sidebar toggle, swiper, ApexCharts, jsVectorMap.
 */
(function () {
  'use strict';

  /* ------------------------------------------------------
   * Sidebar toggle (mobile)
   * ---------------------------------------------------- */
  document.querySelectorAll('[data-sidebar-toggle]').forEach(function (el) {
    el.addEventListener('click', function () {
      document.body.classList.toggle('sidebar-show');
    });
  });

  var backdrop = document.querySelector('.sidebar-backdrop');
  if (backdrop) {
    backdrop.addEventListener('click', function () {
      document.body.classList.remove('sidebar-show');
    });
  }

  /* ------------------------------------------------------
   * Swiper: "Ideas for You"
   * ---------------------------------------------------- */
  var swiperEl = document.getElementById('swiper-1');
  if (swiperEl && window.Swiper) {
    new Swiper(swiperEl, {
      speed: 900,
      spaceBetween: 24,
      slidesPerView: 1,
      watchOverflow: true,
      navigation: {
        prevEl: document.querySelector('.swiper-button-prev'),
        nextEl: document.querySelector('.swiper-button-next'),
      },
    });
  }

  if (!window.Apex) {
    return;
  }

  var COLORS = {
    primary: '#0d6efd',
    warning: '#ffc107',
    info: '#0dcaf0',
    danger: '#dc3545',
    success: '#198754',
    grid: '#e9ecef',
    text: '#6c757d',
  };

  var FONT = "'Plus Jakarta Sans', system-ui, sans-serif";

  function areaChartOptions(seriesName, data, color) {
    return {
      chart: {
        type: 'area',
        height: 300,
        toolbar: { show: false },
        fontFamily: FONT,
        zoom: { enabled: false },
      },
      series: [{ name: seriesName, data: data }],
      colors: [color],
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 3 },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.35,
          opacityTo: 0.05,
          stops: [0, 90, 100],
        },
      },
      xaxis: {
        categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: { style: { colors: COLORS.text, fontSize: '12px' } },
      },
      yaxis: {
        labels: {
          style: { colors: COLORS.text, fontSize: '12px' },
          formatter: function (val) {
            return '$' + val + 'k';
          },
        },
      },
      grid: { borderColor: COLORS.grid, strokeDashArray: 4 },
      legend: { show: false },
      tooltip: { theme: 'light' },
    };
  }

  /* ------------------------------------------------------
   * Revenue: Total Income (tab aktif pertama)
   * ---------------------------------------------------- */
  var incomeChart = new ApexCharts(
    document.querySelector('#totalIncomeChart'),
    areaChartOptions('Total Income', [31, 40, 28, 51, 42, 109, 100], COLORS.primary)
  );
  incomeChart.render();

  /* ------------------------------------------------------
   * Revenue: Total Expenses (dirender saat tab dibuka,
   * karena container-nya tersembunyi saat load)
   * ---------------------------------------------------- */
  var expensesChart = null;
  var pastWeekTab = document.getElementById('past-week-tab');
  if (pastWeekTab) {
    pastWeekTab.addEventListener('shown.bs.tab', function () {
      if (!expensesChart) {
        expensesChart = new ApexCharts(
          document.querySelector('#totalExpensesChart'),
          areaChartOptions('Total Expenses', [45, 52, 38, 64, 55, 80, 72], COLORS.warning)
        );
        expensesChart.render();
      } else {
        expensesChart.windowResize();
      }
    });
  }

  /* ------------------------------------------------------
   * Product Sales (donut)
   * ---------------------------------------------------- */
  var saleEl = document.querySelector('#totalSale');
  if (saleEl) {
    var salesChart = new ApexCharts(saleEl, {
      chart: { type: 'donut', height: 240, fontFamily: FONT },
      series: [38.1, 28.6, 23.8, 9.5],
      labels: ['Smartphones', 'Laptops', 'Headphones', 'Cameras'],
      colors: [COLORS.primary, COLORS.warning, COLORS.info, COLORS.danger],
      stroke: { width: 0 },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return Math.round(val) + '%';
        },
        style: { fontSize: '12px', fontWeight: 600 },
        dropShadow: { enabled: false },
      },
      legend: { show: false },
      plotOptions: {
        pie: {
          donut: {
            size: '72%',
            labels: {
              show: true,
              name: { show: true, fontSize: '13px', color: COLORS.text },
              value: {
                show: true,
                fontSize: '20px',
                fontWeight: 700,
                formatter: function () {
                  return '$27,850';
                },
              },
              total: {
                show: true,
                label: 'Total Sales',
                fontSize: '13px',
                color: COLORS.text,
                formatter: function () {
                  return '900 units';
                },
              },
            },
          },
        },
      },
      tooltip: { theme: 'light' },
    });
    salesChart.render();
  }

  /* ------------------------------------------------------
   * Sales by gender (donut)
   * ---------------------------------------------------- */
  var genderEl = document.querySelector('#salesBygender');
  if (genderEl) {
    var genderChart = new ApexCharts(genderEl, {
      chart: { type: 'donut', height: 220, fontFamily: FONT },
      series: [45, 35, 20],
      labels: ['Mens', 'Womens', 'Kids'],
      colors: [COLORS.primary, COLORS.warning, COLORS.danger],
      stroke: { width: 0 },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return Math.round(val) + '%';
        },
        style: { fontSize: '12px', fontWeight: 600 },
        dropShadow: { enabled: false },
      },
      legend: { show: false },
      plotOptions: {
        pie: { donut: { size: '68%' } },
      },
      tooltip: { theme: 'light' },
    });
    genderChart.render();
  }

  /* ------------------------------------------------------
   * Revenue by Location (jsVectorMap)
   * ---------------------------------------------------- */
  var mapEl = document.getElementById('map-world');
  var MapLib = window.JsVectorMap || window.jsVectorMap;
  if (mapEl && MapLib) {
    try {
      new MapLib({
        selector: '#map-world',
        map: 'world',
      backgroundColor: 'transparent',
      zoomButtons: false,
      zoomOnScroll: false,
      regionStyle: {
        initial: { fill: '#e9ecef', stroke: 'none' },
        hover: { fill: '#ced4da' },
      },
      markerStyle: {
        initial: { r: 6, fillColor: COLORS.primary, fillOpacity: 0.9, stroke: '#fff', strokeWidth: 1.5 },
      },
      markers: [
        { name: 'United States', coords: [38.89, -77.03] },
        { name: 'India', coords: [28.61, 77.2], style: { fillColor: COLORS.success } },
        { name: 'United Kingdom', coords: [51.5, -0.12], style: { fillColor: COLORS.info } },
        { name: 'Sweden', coords: [59.33, 18.06], style: { fillColor: COLORS.warning } },
      ],
      });
    } catch (err) {
      console.warn('jsVectorMap gagal dirender:', err);
    }
  }
})();
