$(function () {
  let f1= getSavingsGraph();
  f1.then(function(value){
    let f2=getSavingsSummary1();
    f2.then(function(value){
      let f3=getReports();
      f3.then(function(value){
        let f4=getTrend3();
        f4.then(function(value){
          let f5=getTrend1();
          f5.then(function(value){
            let f6=getTrend2();
            f6.then(function(value){             
            })
          })
        })
      })
    })
  })
 $('.select2bs4').select2();  
  //naly()
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
 
});
$(document).on('click','#analyse',function(e){  
$("#modal-default").modal("show");
naly();
});
$(document).on('click','.toastsDefaultMaroon',function(e) {
  $('#average-modal-sav').modal('show'); 
  let cclient=$(this).attr("client");
  $("#cclient_name").text(cclient);
    $("#desc").text("loading..");
    var name=$(this).attr('value');   
    var nx=$("#mmnths").val();
    var doc=1;
    var x = document.getElementById("current").checked;
    if(x)
    {
      doc=0;
    }
    var content="No data";    
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:13,
        client:name,
        month:nx,
        current:doc
      },
      async: true,
      success:function (data) {
        content=data;       
$("#desc").html(data);  
      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });
   // $('.toast').toast('show');
   

  });
  function mycolor(){
    let arr= ["#33b2df","#546E7A","#d4526e","#13d8aa","#A5978B","#2b908f","#f9a3a4","#90ee7e","#f48024","#69d2e7","#991AFF","#809980","#FF4D4D","#E6B333","#6666FF"];
    return arr;
  }
function convertToFloat(item) {
  return parseFloat(item);
}
function roundVal(item) {
  let x= item.toFixed(2);
  return parseFloat(x);
}

//Functions
 async function getSavingsGraph(type="")
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:7,
        type:type
          },
      async: true,
      success:function (data) {    
const json = JSON.parse(data);
savingsGraph(json);
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
function savingsGraph(arr)
{
    let month_arr=arr["months"];
    let scheme_savings=arr["scheme"]; 
    let discount_savings=arr["discount"];
    scheme_savings=scheme_savings.map(convertToFloat);
    discount_savings=discount_savings.map(convertToFloat);
    let total_savings=[];
    for(let i=0;i<month_arr.length;i++)
    {
      let sum=parseFloat(scheme_savings[i])+parseFloat(discount_savings[i]);
      total_savings.push(sum);
    }
    total_savings=total_savings.map(roundVal); 
    var options = {
          series: [{
          name: 'Total Savings',
          type: 'column',
          data: total_savings
        }, {
          name: 'Scheme Savings',
          type: 'area',
          data: scheme_savings
        }, {
          name: 'Discount Savings',
          type: 'line',
          data: discount_savings
        }],
          chart: {
          height: 350,
          type: 'line',
          stacked: false,
        },
        stroke: {
          width: [0, 2, 5],
          curve: 'smooth'
        },
        plotOptions: {
          bar: {
            columnWidth: '50%'
          }
        },
        colors:['#84c4dc', 'purple', 'rgb(0, 227, 150)'],
        
        fill: {
          opacity: [0.85, 0.25, 1],
          gradient: {
            inverseColors: false,
            shade: 'light',
            type: "vertical",
            opacityFrom: 0.85,
            opacityTo: 0.55,
            stops: [0, 100, 100, 100]
          }
        },
        labels:month_arr,
        markers: {
          size: 0
        },
        xaxis: {
          type: 'datetime'
        },
        yaxis: {
          title: {
            text: 'Savings',
          },          
           min: 0,
      max: 3000000,
        },
        tooltip: {
          shared: true,
          intersect: false,
          y: {
            formatter: function (y) {
              if (typeof y !== "undefined") {
                return y.toFixed(2) + " savings";
              }
              return y;
        
            }
          }
        }
        };
        var chart = new ApexCharts(document.querySelector("#savings_graph"), options);
        chart.render();
}
   function getTrend1Graph(arr)
  {
    const cs_arr=arr.reverse();
    let main_arr=[];
    let getname=[]; 
    let bottomarr=[];   
     for(let key in arr)
    {
      let month=arr[key]["claim_date"];
      for(let y in arr[key]["cont"])
      {
      let cv=arr[key]["cont"][y]["client_name"];    
      if(getname.indexOf(cv)<0)
      {
        getname.push(cv);               
      }
      }     
     
        bottomarr.push(month);  
    }
    for (let j = 0; j < getname.length; j++) {
  let inside_arr=[];
  let name=getname[j]; 
  for(let key in arr)
    {        
      let myrad =  arr[key]["cont"].filter(function(creature) {
                        return creature.client_name === name;
                    });

      if(myrad.length>0)
      {    
inside_arr.push(parseFloat(myrad[0]["total"]));
      }
      else
      {
inside_arr.push(0);
      }
      
    }
    let inarr={name:name,data:inside_arr};
    main_arr.push(inarr);
}

    var options = {
          series: main_arr,
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
        colors: mycolor(),
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 10,
            dataLabels: {
              total: {
                enabled: true,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        xaxis: {
          type: 'string',
          categories: bottomarr,
        },
        legend: {
          position: 'right',
          offsetY: 40
        },
        fill: {
          opacity: 1
        }
        };

        var chart = new ApexCharts(document.querySelector("#cs_trend"), options);
        chart.render();
       
  }
   async function getTrend1()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:10
          },
      async: true,
      success:function (data) { 
    var json2= JSON.parse(data);   
    getTrend1Graph(json2);
    resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  async function getTrend2()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:9
          },
      async: true,
      success:function (data) { 
    var json2= JSON.parse(data); 
    getTrend2Graph(json2);
    resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  async function getTrend3()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:8
          },
      async: true,
      success:function (data) { 
    var json2= JSON.parse(data); 
    getPerc(json2);
    resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
   async function getReports()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:31
          },
      async: true,
      success:function (data) { 
    $("#report").html(data);
    resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  function getTrend2Graph(arr)
  {
    const cs_arr=arr.reverse();
    let main_arr=[];
    let getname=[]; 
    let bottomarr=[];   
     for(let key in arr)
    {
      let month=arr[key]["claim_date"];
      for(let y in arr[key]["cont"])
      {
      let cv=arr[key]["cont"][y]["client_name"];    
      if(getname.indexOf(cv)<0)
      {
        getname.push(cv);               
      }
      }     
     
        bottomarr.push(month);  
    }
    for (let j = 0; j < getname.length; j++) {
  let inside_arr=[];
  let name=getname[j]; 
  for(let key in arr)
    {        
      let myrad =  arr[key]["cont"].filter(function(creature) {
                        return creature.client_name === name;
                    });

      if(myrad.length>0)
      {    
inside_arr.push(parseFloat(myrad[0]["total"]));
      }
      else
      {
inside_arr.push(0);
      }
      
    }
    let inarr={name:name,data:inside_arr};
    main_arr.push(inarr);
}

 var options = {
          series:main_arr,
          chart: {
          type: 'bar',
          height: 350,
          },
          plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded',
          },
        },        
    colors: mycolor(),
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: bottomarr,
        },
        yaxis: {
          title: {
            text: 'Savings'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "" + val + " savings"
            }
          }
        },
        };

        var chart = new ApexCharts(document.querySelector("#apex_2"), options);
        chart.render();      
  }

  function getPerc(arr)
  {    
    let month_arr=arr["months"].reverse();
    let scheme_savings=arr["scheme"].reverse(); 
    let discount_savings=arr["discount"].reverse();
    scheme_savings=scheme_savings.map(convertToFloat);
    discount_savings=discount_savings.map(convertToFloat);
  
     var options = {
          series: [{
          name: 'Scheme Percentage',
          type: 'area',
          data: scheme_savings
        }, {
          name: 'Discount Percentage',
          type: 'line',
          data: discount_savings
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          curve: 'smooth'
        },
        fill: {
          type:'solid',
          opacity: [0.35, 1],
        },
        labels: month_arr,
        markers: {
          size: 0
        },
        yaxis: [
          {
            title: {
              text: 'Scheme Savings %',
            },
          },
          {
            opposite: true,
            title: {
              text: 'Discount Savings %',
            },
          },
        ],
        tooltip: {
          shared: true,
          intersect: false,
          y: {
            formatter: function (y) {
              if(typeof y !== "undefined") {
                return  y.toFixed(0) + " savings %";
              }
              return y;
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#perc_sav"), options);
        chart.render();

  }
   async function getSavingsSummary1()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:32
          },
      async: true,
      success:function (data) {      
const json = JSON.parse(data);
$(".c_perc").text(json["claims_per"]+"%"); 
$("#up1").text(json["this_perc"]); 
$("#up2").text(json["last_perc"]);
$("#arrow1").addClass(json["arrow1"]); 
$("#arrow2").addClass(json["arrow2"]);
$("#claims1").text("R "+json["claims1"]); 
$("#claims2").text("R "+json["claims2"]);
resolve(1);
      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });
  });
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
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:12,
        start_date:start_date,
        end_date:end_date,
        clients:clients,
        users:users,
        val:val
      },
      async: true,
      success:function (data) {
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

