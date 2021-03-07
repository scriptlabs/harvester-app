/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/pages/db_dark.js ***!
  \***************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/*
 *  Document   : db_dark.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Dark Dashboard Page
 */
// Chart.js Charts, for more examples you can check out http://www.chartjs.org/docs
var DbDark = /*#__PURE__*/function () {
  function DbDark() {
    _classCallCheck(this, DbDark);
  }

  _createClass(DbDark, null, [{
    key: "initDarkChartJS",
    value:
    /*
     * Init Charts
     *
     */
    function initDarkChartJS() {
      // Set Global Chart.js configuration
      Chart.defaults.global.defaultFontColor = '#000';
      Chart.defaults.scale.gridLines.color = '#000';
      Chart.defaults.scale.gridLines.zeroLineColor = '#000';
      Chart.defaults.scale.display = false;
      Chart.defaults.scale.ticks.beginAtZero = true;
      Chart.defaults.global.elements.line.borderWidth = 2;
      Chart.defaults.global.elements.point.radius = 3;
      Chart.defaults.global.elements.point.hoverRadius = 5;
      Chart.defaults.global.tooltips.cornerRadius = 3;
      Chart.defaults.global.legend.display = false; // Chart Containers

      var chartDarkLinesCon = jQuery('.js-chartjs-dark-lines');
      console.log('chartDarkLinesCon:', chartDarkLinesCon);
      var chartDarkLinesCon2 = jQuery('.js-chartjs-dark-lines2');
      console.log('chartDarkLinesCon2:', chartDarkLinesCon2); // Chart Variables

      var chartDarkLines, chartDarkLines2; // Lines Charts Data

      var chartDarkLinesData = {
        labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
        datasets: [{
          label: 'This Week',
          fill: true,
          backgroundColor: 'rgba( 0, 0, 0,.1)',
          borderColor: 'rgba( 0, 0, 0,.4)',
          pointBackgroundColor: 'rgba( 0, 0, 0,.4)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba( 0, 0, 0,.4)',
          data: [39, 15, 25, 32, 38, 10, 45]
        }]
      };
      var chartDarkLinesOptions = {
        scales: {
          yAxes: [{
            ticks: {
              suggestedMax: 50
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: function label(tooltipItems, data) {
              return ' ' + tooltipItems.yLabel + ' Sales';
            }
          }
        }
      };
      var chartDarkLinesData2 = {
        labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
        datasets: [{
          label: 'This Week',
          fill: true,
          backgroundColor: 'rgba( 0, 0, 0,.1)',
          borderColor: 'rgba( 0, 0, 0,.4)',
          pointBackgroundColor: 'rgba( 0, 0, 0,.4)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba( 0, 0, 0,.4)',
          data: [345, 190, 220, 290, 380, 230, 455]
        }]
      };
      var chartDarkLinesOptions2 = {
        scales: {
          yAxes: [{
            ticks: {
              suggestedMax: 480
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: function label(tooltipItems, data) {
              return ' $ ' + tooltipItems.yLabel;
            }
          }
        }
      }; // Init Charts

      if (chartDarkLinesCon.length) {
        chartDarkLines = new Chart(chartDarkLinesCon, {
          type: 'line',
          data: chartDarkLinesData,
          options: chartDarkLinesOptions
        });
      }

      if (chartDarkLinesCon2.length) {
        chartDarkLines2 = new Chart(chartDarkLinesCon2, {
          type: 'line',
          data: chartDarkLinesData2,
          options: chartDarkLinesOptions2
        });
      }
    }
    /*
     * Init functionality
     *
     */

  }, {
    key: "init",
    value: function init() {
      this.initDarkChartJS();
    }
  }]);

  return DbDark;
}(); // Initialize when page loads


jQuery(function () {
  DbDark.init();
});
/******/ })()
;