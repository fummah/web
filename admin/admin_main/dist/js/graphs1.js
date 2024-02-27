$(function () {

  var colors=['#3c8dbc','#f56954','#483D8B', '#00a65a', '#f39c12', '#00c0ef', '#2F4F4F','#CD853','#006400','#ADFF2F','#F0E68C','#FF6347','#8B0000','#FA8072','#0000CD','#d2d6de','#7B68EE','#EE82EE','#F0FFF0'];
  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var open_user_array=[];
  var open_user_total_array=[];
  var open_user_array1=[];
  var open_user_total_array1=[];
  var build_array=[];
  var current_clients=[];

  // Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder         : 'sort-highlight',
    connectWith         : '.connectedSortable',
    handle              : '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex              : 999999
  })
  $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

// jQuery UI sortable for the todo list
  $('.todo-list').sortable({
    placeholder         : 'sort-highlight',
    handle              : '.handle',
    forcePlaceholderSize: true,
    zIndex              : 999999
  })
  //Initialize Select2 Elements
  $('.select2').select2()

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  })
// bootstrap WYSIHTML5 - text editor
  $('.textarea').summernote()

  $('.daterange').daterangepicker({
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
  }, function (start, end) {
    $("#dat1").val(start.format('Y-MM-DD'));
    $("#dat2").val(end.format('YYYY-MM-DD'));
    $("#datetxt").text(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))
    naly();
  });

  /* jQueryKnob */
  $('.knob').knob();
  ////
  $(".ccval").click(function () {
    naly();
  });

  $(".select2bs4").change(function () {
    naly();
  });
  $('.toastsDefaultMaroon').click(function() {
    var name=$(this).attr('value');
    $("#mesg").show();
    var nx=$("#mmnths").val();
    var doc=1;
    var x = document.getElementById("current").checked;
    if(x)
    {
      doc=0;
    }
    var content="No data";
    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{
        identityNum:13,
        client:name,
        month:nx,
        current:doc
      },
      async: false,
      success:function (data) {
        // var json2= JSON.parse(data);
        content=data;
        $("#mesg").hide();

      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });
    $(document).Toasts('create', {
      class: 'bg-default',
      title: name,
      subtitle: 'Report',
      body: content
    });

  });
  //////
  $.ajax({
    url:"../ajaxPhp/reports.php",
    type:"GET",
    data:{identityNum:9},
    async: false,
    success:function (data) {
      var json2= JSON.parse(data);
      console.log(json2);
      stackedf(json2,open_user_array,open_user_total_array);

      open_user_array=trend_main_array4;
      open_user_total_array=trend_main_total_array4;
      /*
          }*/
      //console.log(current_clients);
      //console.log(grouped);
      //console.log(build_array);
    },
    error:function (jqXHR, exception) {
      alert(jqXHR.responseText);
    }
  });
  $.ajax({
    url:"../ajaxPhp/reports.php",
    type:"GET",
    data:{identityNum:10},
    async: false,
    success:function (data) {
      var json2= JSON.parse(data);
      console.log(json2);
      stackedf(json2,open_user_array1,open_user_total_array1);

      open_user_array1=trend_main_array4;
      open_user_total_array1=trend_main_total_array4;
      /*
          }*/
      //console.log(current_clients);
      //console.log(grouped);
      //console.log(build_array);
    },
    error:function (jqXHR, exception) {
      alert(jqXHR.responseText);
    }
  });
  open_user_array.reverse();
  open_user_array1.reverse();
  naly();
  $(".hid").hide();
  var areaChartData = {
    labels  : open_user_array,
    datasets:open_user_total_array
  }

  var areaChartData1 = {
    labels  :open_user_array1,
    datasets:open_user_total_array1
  }
  var areaChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines : {
          display : false,
        }
      }],
      yAxes: [{
        gridLines : {
          display : false,
        }
      }]
    }
  }

// This will get the first returned node in the jQuery collection.


//-------------
//- BAR CHART -
//-------------
  var barChartCanvas = $('#barChart').get(0).getContext('2d')
  var barChartData = jQuery.extend(true, {}, areaChartData)
  var temp0 = areaChartData.datasets[0]
  var temp1 = areaChartData.datasets[1]
  barChartData.datasets[0] = temp1
  barChartData.datasets[1] = temp0

  var barChartOptions = {
    responsive              : true,
    maintainAspectRatio     : false,
    datasetFill             : false
  }

  var barChart = new Chart(barChartCanvas, {
    type: 'bar',
    data: barChartData,
    options: barChartOptions
  })

//-------------

  var barChartData1 = jQuery.extend(true, {}, areaChartData1)
  var temp0 = areaChartData1.datasets[0]
  var temp1 = areaChartData1.datasets[1]
  barChartData1.datasets[0] = temp1
  barChartData1.datasets[1] = temp0
  var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
  var stackedBarChartData = jQuery.extend(true, {}, barChartData1)

  var stackedBarChartOptions = {
    responsive              : true,
    maintainAspectRatio     : false,
    scales: {
      xAxes: [{
        stacked: true,
      }],
      yAxes: [{
        stacked: true
      }]
    }
  }

  var stackedBarChart = new Chart(stackedBarChartCanvas, {
    type: 'bar',
    data: stackedBarChartData,
    options: stackedBarChartOptions
  })

// Sales graph chart
  var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
//$('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'],
    datasets: [
      {
        label               : 'Total Claims',
        fill                : false,
        borderWidth         : 2,
        lineTension         : 0,
        spanGaps : true,
        borderColor         : '#efefef',
        pointRadius         : 3,
        pointHoverRadius    : 7,
        pointColor          : '#efefef',
        pointBackgroundColor: '#efefef',
        data                : [666, 778, 912, 376, 681, 570, 420, 173, 107, 843]
      }
    ]
  }

  var salesGraphChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: false,
    },
    scales: {
      xAxes: [{
        ticks : {
          fontColor: '#efefef',
        },
        gridLines : {
          display : false,
          color: '#efefef',
          drawBorder: false,
        }
      }],
      yAxes: [{
        ticks : {
          stepSize: 5000,
          fontColor: '#efefef',
        },
        gridLines : {
          display : true,
          color: '#efefef',
          drawBorder: false,
        }
      }]
    }
  };

// This will get the first returned node in the jQuery collection.
  var salesGraphChart = new Chart(salesGraphChartCanvas, {
      type: 'line',
      data: salesGraphChartData,
      options: salesGraphChartOptions
    }
  );


  function stackedf(json2,trend_main_array4,trend_main_total_array4) {


    $.each(json2, function(key, value){
      var full_date=json2[key].claim_date;
      var mmnth = full_date.split('-');
      var num = parseInt(mmnth[1] - 1);
      var mm=months[num];
      trend_main_array4.push(mm);
      var insid=json2[key].cont;
      $.each(insid, function(key, value){

        var cclient=insid[key].client_name;
        var vtot=insid[key].total;
        var obj1={"client":cclient,"total":vtot,"month":mm};
        build_array.push(obj1);
        if(current_clients.indexOf(cclient)<0)
        {
          current_clients.push(cclient);
        }

      });

    });

    var  grouped = {};
    build_array.forEach(function (a) {
      grouped[a.client] = grouped[a.client] || [];
      grouped[a.client].push({ total: a.total,month:a.month});
    });

    var coun=current_clients.length;



    for(var i=0;i<coun;i++) {
      var ccl = current_clients[i];
      var arr1 = grouped[ccl];
      var mycolor=colors[i];
      var data1 = [];

      for(var x=0;x<trend_main_array4.length;x++)
      {
        var actval=0;
        $.each(arr1, function (key, value) {
          var tot = arr1[key].total;
          var mnth = arr1[key].month;
          var xmanths=trend_main_array4[x];
          if(xmanths.indexOf(mnth)>-1)
          {
            actval=tot;
          }

        });
        data1.push(actval)
      }

      data1.reverse();
      var main_obj = {
        label: ccl,
        backgroundColor: mycolor,
        borderColor: mycolor,
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: mycolor,
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: data1

      };

      trend_main_total_array4.push(main_obj);

    }
    build_array=[];
    current_clients=[];
  }
///Analise function

  function naly() {
    var analy_array=[];
    var analy_array1=[];
    var val=document.querySelector('input[name="r1"]:checked').value;
    var sum=0;
    var clients=$("#clients").val();
    var users=$("#users").val();
    var start_date=$("#dat1").val();
    var end_date=$("#dat2").val();
    $(".mychart").empty();
    $(".mychart").append("<canvas id=\"stackedBarChart1\" style=\"min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;\"></canvas>");
    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{
        identityNum:12,
        start_date:start_date,
        end_date:end_date,
        clients:clients,
        users:users,
        val:val
      },
      async: false,
      success:function (data) {
//console.log(data);
        var json2= JSON.parse(data);
        $.each(json2, function(key, value){
          var client=json2[key].client_name;
          var total=json2[key].total;
          sum+=parseFloat(total);
          analy_array.push(client);
          analy_array1.push(total);

        });


      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });
    sum=parseFloat(sum).toFixed(2);
    sum=sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    var areaChartData = {
      labels  : analy_array,
      datasets: [
        {
          label               : '('+sum+') Savings ',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : analy_array1
        },
        {
          label               : '',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : []
        },
      ]
    };
//////////////////////////

    var barChartData = jQuery.extend(true, {}, areaChartData);
    var temp0 = areaChartData.datasets[0];
    var temp1 = areaChartData.datasets[1];
    barChartData.datasets[0] = temp1;
    barChartData.datasets[1] = temp0;


    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart1').get(0).getContext('2d');
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    };

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  }
});


