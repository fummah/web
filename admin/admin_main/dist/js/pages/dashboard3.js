$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode      = 'index';
  var intersect = true;
  var arr1=[];
  var arr2=[];
  var arr3=[];
  $.ajax({
    url:"../ajaxPhp/reports.php",
    type:"GET",
    data:{identityNum:7},
    async: false,
    success:function (data) {
      var json2= JSON.parse(data);
      arr1=json2.months.reverse();
      arr2=json2.scheme.reverse();
      arr3=json2.discount.reverse();

    },
    error:function (jqXHR, exception) {
      alert(jqXHR.responseText);
    }
  });
  var arr11=[];
  var arr21=[];
  var arr31=[];
  $.ajax({
    url:"../ajaxPhp/reports.php",
    type:"GET",
    data:{identityNum:8},
    async: false,
    success:function (data) {
      var json2= JSON.parse(data);
      arr11=json2.months.reverse();
      arr21=json2.scheme.reverse();
      arr31=json2.discount.reverse();

    },
    error:function (jqXHR, exception) {
      alert(jqXHR.responseText);
    }
  });
  var $salesChart = $('#sales-chart')
  var salesChart  = new Chart($salesChart, {
    type   : 'bar',
    data   : {
      labels  : arr1,
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : arr2
        },
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : arr3
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return '' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })

  var $visitorsChart = $('#visitors-chart')
  var visitorsChart  = new Chart($visitorsChart, {
    data   : {
      labels  :arr11,
      datasets: [{
        type                : 'line',
        data                : arr21,
        backgroundColor     : 'transparent',
        borderColor         : '#007bff',
        pointBorderColor    : '#007bff',
        pointBackgroundColor: '#007bff',
        fill                : false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
        {
          type                : 'line',
          data                :arr31,
          backgroundColor     : 'tansparent',
          borderColor         : '#ced4da',
          pointBorderColor    : '#ced4da',
          pointBackgroundColor: '#ced4da',
          fill                : false
          // pointHoverBackgroundColor: '#ced4da',
          // pointHoverBorderColor    : '#ced4da'
        }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero : true,
            suggestedMax: 100
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })
})
