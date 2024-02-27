$(function () {

  var colors=['#f56954','#3c8dbc','#483D8B', '#00a65a', '#f39c12', '#00c0ef', '#2F4F4F','#CD853','#006400','#ADFF2F','#F0E68C','#FF6347','#8B0000','#FA8072','#0000CD','#d2d6de','#7B68EE','#EE82EE','#F0FFF0'];
  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var open_user_array=[];
  var open_user_total_array=[];
//open array for clients
  var open_client_array=[];
  var open_client_total_array=[];
//trend claims
  var trend_claim_array=[];
  var trend_claim_total_array=[];
//trend stacked
  var trend_main_array=[];
  var trend_main_total_array=[];
//
  var trend_main_array1=[];
  var trend_main_total_array1=[];
  var build_array=[];
  var current_clients=[];
//

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

  naly()

  $(".hid").hide();
  $(".ccval").click(function () {
    naly();
  });
  $(".cc").click(function () {
    naly();
  });
  $(".select2bs4").change(function () {
    naly();
  });
  $("#checkboxSuccess1").click(function () {
    naly();
  });

  function naly() {
    var analy_array=[];
    var analy_array1=[];
    var sum=0;
    var val=document.querySelector('input[name="r1"]:checked').value;
    var val1=document.querySelector('input[name="typ"]:checked').value;
    var clients=$("#clients").val();
    var users=$("#users").val();
    var start_date=$("#dat1").val();
    var end_date=$("#dat2").val();
    var tti="QA Claims";


    $(".mychart").empty();
    $(".mychart").append("<canvas id=\"stackedBarChart1\" style=\"min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;\"></canvas>");
    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{
        identityNum:16,
        start_date:start_date,
        end_date:end_date,
        val:val,
        clients:clients,
        users:users,
        val1:val1
      },
      async: false,
      success:function (data) {
console.log(data);
        var json2= JSON.parse(data);
        $.each(json2, function(key, value){
          var client=json2[key].client_name;
          var total=json2[key].total;
          sum+=parseInt(total);
          if (document.getElementById("checkboxSuccess1").checked) {
            var pperc=getPer(val,val1,clients,users,start_date,end_date,client);
            client=client+"("+total+"/"+pperc+")";
            total=Math.round((parseInt(total)/pperc)*100);
            tti="QA Claims % ";
          }
          analy_array.push(client);
          analy_array1.push(total);

        });

      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });

    analy_array.reverse();
    analy_array1.reverse();
    var areaChartData = {
      labels  : analy_array,
      datasets: [
        {
          label               : '('+sum+') '+tti,
          backgroundColor     : '#0c525d',
          borderColor         : '#0c525d',
          pointRadius          : false,
          pointColor          : '#0c525d',
          pointStrokeColor    : '#0c525d',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: '#0c525d',
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

    if(val1==="completed")
    {
      $(".vbn").show();

      mystack(val,val1,clients,users,start_date,end_date);
      getImprovements(val,val1,clients,users,start_date,end_date);
      $(".vvc").show();
      stackTrend(val,val1,clients,users,start_date,end_date);

    }
    else {
      $(".vbn").hide();
      $(".vvc").hide();
    }


  }

  function info4() {
    var from_client=$("#from_client").val();
    var to_client=$("#to_client").val();
    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{identityNum:15,from_client:from_client,to_client:to_client},
      async: false,
      success:function (data) {
console.log(data);
        $("#info41").html(data);

      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });
  }

  function mystack(val,val1,clients,users,start_date,end_date)
  {
    var trend_main_array1=[];
    var trend_main_total_array1=[];
    $(".mychart1").empty();
    $(".mychart1").append("<canvas id=\"stackedBarChart\" style=\"min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;\"></canvas>");
    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{
        identityNum:17,
        start_date:start_date,
        end_date:end_date,
        val:val,
        clients:clients,
        users:users,
        val1:val1
      },
      async: false,
      success:function (data) {
        var json2= JSON.parse(data);
        console.log(json2);
        stackedf(json2,trend_main_array1,trend_main_total_array1);

        trend_main_array1=trend_main_array4;
        trend_main_total_array1=trend_main_total_array4;
      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });

    trend_claim_array.reverse();
    trend_claim_total_array.reverse();
    trend_main_array.reverse();
    trend_main_array1.reverse();

    var areaChartData1 = {
      labels  : trend_main_array1,
      datasets: trend_main_total_array1
    };

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
  }


  function stackedf(json2,trend_main_array4,trend_main_total_array4) {

    $.each(json2, function(key, value){
      var full_date=json2[key].claim_date;

      trend_main_array4.push(full_date);
      var insid=json2[key].cont;
      $.each(insid, function(key, value){

        var cclient=insid[key].client_name;
        var vtot=insid[key].total;
        var obj1={"client":cclient,"total":vtot,"month":full_date};
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
        var ttx=0;
        $.each(arr1, function (key, value) {
          var tot = arr1[key].total;
          var mnth = arr1[key].month;
          ttx+=parseInt(tot);
          var xmanths=trend_main_array4[x];
          if(xmanths.indexOf(mnth)>-1)
          {
            actval=tot;
          }

        });
        data1.push(actval)
      }
      ccl= ccl+"("+ttx+")";
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

  function stackTrend(val,val1,clients,users,start_date,end_date)
  {
    var trend_main_array1=[];
    var trend_main_total_array1=[];
    $(".mychart2").empty();
    $(".mychart2").append("<canvas id=\"stackedBarChart2\" style=\"min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;\"></canvas>");
    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{
        identityNum:18,
        start_date:start_date,
        end_date:end_date,
        val:val,
        clients:clients,
        users:users,
        val1:val1
      },
      async: false,
      success:function (data) {
        var json2= JSON.parse(data);
        stackedTrendSec(json2,trend_main_array1,trend_main_total_array1);

        trend_main_array1=trend_main_array4;
        trend_main_total_array1=trend_main_total_array4;


      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });
    trend_claim_array.reverse();
    trend_claim_total_array.reverse();
    trend_main_array.reverse();
    trend_main_array1.reverse();
    var areaChartData1x = {
      labels  : trend_main_array1,
      datasets: trend_main_total_array1
    };

    var barChartData1x = jQuery.extend(true, {}, areaChartData1x)
    var temp0 = areaChartData1x.datasets[0]
    var temp1 = areaChartData1x.datasets[1]
    barChartData1x.datasets[0] = temp1
    barChartData1x.datasets[1] = temp0
    var stackedBarChartCanvasx = $('#stackedBarChart2').get(0).getContext('2d')
    var stackedBarChartDatax = jQuery.extend(true, {}, barChartData1x)

    var stackedBarChartOptionsx = {
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

    var stackedBarChartx = new Chart(stackedBarChartCanvasx, {
      type: 'bar',
      data: stackedBarChartDatax,
      options: stackedBarChartOptionsx
    })
  }
  function stackedTrendSec(json2,trend_main_array5,trend_main_total_array5) {

    $.each(json2, function(key, value){
      var full_date=json2[key].claim_date;
      var mmnth = full_date.split('-');
      var num = parseInt(mmnth[1] - 1);
      var mm=months[num];
      trend_main_array5.push(mm);
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

      for(var x=0;x<trend_main_array5.length;x++)
      {
        var actval=0;
        $.each(arr1, function (key, value) {
          var tot = arr1[key].total;
          var mnth = arr1[key].month;
          var xmanths=trend_main_array5[x];
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

      trend_main_total_array5.push(main_obj);

    }
    build_array=[];
    current_clients=[];
  }


  function getPer(val,val1,clients,users,start_date,end_date,user) {
    var tt=0;
    $.ajax({
      url: "../ajaxPhp/reports.php",
      type: "GET",
      data: {
        identityNum: 19,
        start_date: start_date,
        end_date: end_date,
        val: val,
        clients: clients,
        users: users,
        val1: val1,
        user:user
      },
      async: false,
      success: function (data) {
        tt=data;
      },
      error: function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });

    return tt;
  }

  function getImprovements(val,val1,clients,users,start_date,end_date,user="1") {
    var table="";
    $.ajax({
      url: "../ajaxPhp/reports.php",
      type: "GET",
      data: {
        identityNum: 20,
        start_date: start_date,
        end_date: end_date,
        val: val,
        clients: clients,
        users: users,
        val1: val1,
        user:user
      },
      async: false,
      success: function (data) {
        console.log(data);
        var xx2=JSON.stringify(data);
        var json2= JSON.parse(xx2);
        var vv = JSON.parse(json2);

        for (let qa in vv) {
          var username=vv[qa]["username"];
          table+="<h3>"+username+"</h3>";
          table+="<table class=\"table table-head-fixed\"><thead><tr><th>Grade</th><th>Description</th></tr></thead><tbody>";
          var dd=vv[qa]["data"];
          for (let mydata in dd) {
            var descr=dd[mydata]["descr"];
            var total=dd[mydata]["total"];
            var clas="";
            if(total>0 && total<2){clas="bg-gray-light"}
            else if(total>=2 && total < 5){clas="bg-warning"}
            else if(total>=5){clas="bg-danger"}

            table+="<tr><td class='"+clas+"'></td><td>"+descr+"</td></tr>";
          }
          table+="</tbody></table>";
        }
        $(".improvents").html(table);

      },
      error: function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });

  }
});





