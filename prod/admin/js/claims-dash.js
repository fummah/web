$(function () {
  let f1=getClaimsSummary();  
  f1.then(function(value){
let f2=getMainGraph();
f2.then(function(value){
  let f3=getChartGraph();
  f3.then(function(value){
    let f4 = getClaimsSummary1();
    f4.then(function(value){
let f5=pmbPerc();
f5.then(function(value){
  let f6=tariffView();
  f6.then(function(value){
let f7=getTrend1();
f7.then(function(value){
  let f8=getTrend2();
  f8.then(function(value){
let f9=icd10View();
f9.then(function(value){
let f10=schemesView();
f10.then(function(value){
let f11=productivityView();
f11.then(function(value){
})})})})})})})})})})})  
 $('.select2bs4').select2();  
  //naly()
  $(".hid").hide();
  $(".ccval").click(function () {
    naly();
  });
  $(".select2bs4").change(function () {
    naly();
  });
  $(".cc").click(function () {
    naly();
  });
});
$(document).on('click','#analyse',function(e){ 
$("#modal-default").modal("show");
naly();
});
//Buttons
$(document).on('click','.claims_modal',function(e) {
  $("#info41").empty();
   let data=$(this).attr("data");
if(data=="snap")
{
    $("#mysnap").show();
   $("#myaverage").hide();
   $(".mymodal").removeClass("allc");
}
else
{
    $("#mysnap").hide();
   $("#myaverage").show();
   $(".mymodal").addClass("allc");
   getAverage();
}
 $('#average-modal').modal('show');
  });
//Graph
$(document).on('click','.g1',function(e) {
  $(".g1").removeClass("active");
  $(this).addClass("active");
   $("#claims_graph").remove();
    $("#mychart").append("<canvas id='claims_graph' width='913' height='313' style='display: block; height: 251px; width: 731px;' class='chartjs-render-monitor'></canvas>")
    
   let data=$(this).attr("data");
 getMainGraph(data);
  });
//Pie
$(document).on('click','.g2',function(e) {
  console.log("clicked");
  $(".g2").removeClass("active");
  $(this).addClass("active");
   let data=$(this).attr("data");
getChartGraph(data);
  });
//Average
$(document).on('change','.allc',function(e) {
  console.log("Testing...");
  $("#info41").html("Loading...");
 getAverage();
 console.log("Testing...222");
  });
//Functions
 async function getClaimsSummary()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:24
          },
      async: true,
      success:function (data) {    
  
const json = JSON.parse(data);
$("#open_claims").text(json["open_claims"]);  
$("#closed_claims").text(json["closed_claims"]); 
$("#new_claims").text(json["new_claims"]); 
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  async function getClaimsSummary1()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:25
          },
      async: true,
      success:function (data) {   
   
const json = JSON.parse(data);
$("#average").text(json["average"]); 
$(".c_perc").text(json["claims_per"]+"%"); 
$("#up1").text(json["this_perc"]); 
$("#up2").text(json["last_perc"]);
$("#arrow1").addClass(json["arrow1"]); 
$("#arrow2").addClass(json["arrow2"]);
$("#claims1").text(json["claims1"]); 
$("#claims2").text(json["claims2"]);
const reopened=json["reopened"];
getReopened(reopened);
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  async function productivityView()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:26
          },
      async: true,
      success:function (data) {     
        $("#mydata").empty();
$("#mydata").html(data);
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
   async function schemesView()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:27
          },
      async: true,
      success:function (data) {     
        $("#myschemes").empty();
$("#myschemes").html(data);
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
   async function tariffView()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:28
          },
      async: true,
      success:function (data) {     
        $("#mytariff").empty();
$("#mytariff").html(data);
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
   async function icd10View()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:29
          },
      async: true,
      success:function (data) {     
        $("#myicd10").empty();
$("#myicd10").html(data);
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  async function pmbPerc()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:30
          },
      async: true,
      success:function (data) { 
      $("#pmb").html(data);
       resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  async function getTrend1()
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:5
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
        identityNum:4
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
  function getReopened(arr)
  {
    let month_arr=[];
    let total_arr=[];
    for(let key in arr)
    {
      let month= arr[key]["month"];
      let total= parseFloat(arr[key]["total"]);
      month_arr.push(month);
      total_arr.push(total);
    }
 $("#current_reopened").text(total_arr[0]);  
        var r=$("#page_views").get(0).getContext("2d");
        new Chart(r,{
          type:"bar",
          data:{
            labels:month_arr,
            datasets:[{
              backgroundColor:["#E4E9EC","#E4E9EC","#E4E9EC"," #64C5B1","#E4E9EC","#E4E9EC","#E4E9EC"],
              data:total_arr}]
            ,},
            options:{
              responsive:true,
              maintainAspectRatio:false,
              title:{display:!1},
              tooltips:{intersect:!1,mode:"nearest"},
              legend:{display:!1},
              responsive:!0,
              maintainAspectRatio:!1,
              barRadius:2,
              scales:{
                xAxes:[{barThickness:5,display:!1,gridLines:!1,ticks:{beginAtZero:!0}}],
                yAxes:[{display:!1,gridLines:!1,ticks:{beginAtZero:!0}}]},
                layout:{
                  padding:{
                    left:0,right:0,top:0,bottom:0
                  }},},});
    
   
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
          height: 350
        },
        colors: mycolor(),
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
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
            text: 'Number of Claims'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "" + val + " claims"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#apex_2"), options);
        chart.render();

       
  }
    async function getMainGraph(t="monthly")
  {
    return new Promise((resolve,reject)=>{
    $("#info").show();
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:3,
        type:t
          },
      async: true,
      success:function (data) { 
        const json=JSON.parse(data);
      getClaimsGraph(json);
      $("#info").hide();
       resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }

function getClaimsGraph(arr)
  {
   $("#info").show();
   let month_arr=[];
    let total_arr=[];
    for(let key in arr)
    {
      let month= arr[key]["claim_date"];
      let total= parseFloat(arr[key]["total"]);
      month_arr.push(month);
      total_arr.push(total);
    }

  month_arr.reverse();
  total_arr.reverse();
  var claims_graph=$("#claims_graph").get(0).getContext("2d");
              claims_graph.height=20;
              let myChart = new Chart(claims_graph,{
                type:"bar",
                data:{
                  labels:month_arr,
                    datasets:[
                      {
                        label:"Claims received - ",backgroundColor:"#1ccab8",borderColor:"transparent",borderWidth:2,categoryPercentage:0.5,hoverBackgroundColor:"#00d8c2",
                          hoverBorderColor:"transparent",
                          data:total_arr,
                      },
                   ],},
                options:{
                  responsive:true,maintainAspectRatio:false,
                  legend:{
                    display:!1,
                        labels:{
                          fontColor:"#50649c"
                        }
                    },
                    tooltips:{
                      enabled:!0,
                      callbacks:{
                        label:function(e,a){
                          return a.datasets[e.datasetIndex].label+""+e.yLabel+"";
                        },
                      },
                    },
    scales:{
      xAxes:[
        {
          barPercentage:0.35,categoryPercentage:0.4,display:!0,
          gridLines:{
            color:"transparent",borderDash:[0],zeroLineColor:"transparent",
      zeroLineBorderDash:[2],zeroLineBorderDashOffset:[2]},
      ticks:{fontColor:"#a4abc5",beginAtZero:!0,padding:12},},],
    yAxes:[{
      gridLines:{color:"#8997bd29",borderDash:[3],drawBorder:!1,drawTicks:!1,zeroLineColor:"#8997bd29",zeroLineBorderDash:[2],
      zeroLineBorderDashOffset:[2]},ticks:{fontColor:"#a4abc5",beginAtZero:!0,padding:12,
      callback:function(e){if(!(e%10))return ""+e+"";},},},],},},});


      }
async function getChartGraph(t="cs")
  {
    return new Promise((resolve,reject)=>{
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:1,
        type:t
          },
      async: true,
      success:function (data) { 
        const json=JSON.parse(data);
      getClaimsChart(json);
       resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }

function getClaimsChart(arr)
  {
    let in_arr=[];
    let mainarr=[];    
    for(let key in arr)
    {
      let username= arr[key]["username"];
      let total= parseFloat(arr[key]["total"]);
      let obj={value:total,name:username};
      in_arr.push(obj);
      mainarr.push(username);
    }

  var o=window.AdminoxAdmin||{};        
          echarts.init(document.getElementById("platform_type_dates_donut")).setOption({
            timeline:{show:!1,data:["06-16","05-16","04-16"],
          label:{formatter:function(e){
            return e?e.slice(0,5):null;},},x:10,y:null,x2:10,y2:0,width:10,height:50,backgroundColor:"rgba(0,0,0)",
          borderColor:"#eaeaea",borderWidth:0,padding:5,controlPosition:"left",autoPlay:!0,loop:!1,playInterval:2e3,
          lineStyle:{width:1,color:"#000",type:""},},
          options:[{color:["#14256A","#64c5b1","#414b4f","#ee4b82","#45bbe0"],
            title:{text:"",subtext:""},tooltip:{trigger:"item",
            formatter:"{a} <br/>{b} : {c} ({d}%)"},legend:{show:!1,x:"left",
            orient:"vertical",padding:0,data:mainarr},toolbox:{show:!0,
            color:["#bdbdbd","#bdbdbd","#bdbdbd","#bdbdbd"],feature:{mark:{show:!1},dataView:{show:!1,readOnly:!0},
            magicType:{show:!0,type:["pie","funnel"],option:{funnel:{x:"10%",width:"80%",funnelAlign:"center",max:50},pie:{roseType:"none"}}},
            restore:{show:!1},saveAsImage:{show:0},},},series:[{name:"06-16",type:"pie",radius:[20,"80%"],roseType:"none",center:["50%","45%"],
              width:"40%",itemStyle:{normal:{label:{show:!1},labelLine:{show:!0}},emphasis:{label:{show:!1},labelLine:{show:!1}}},
              data:in_arr
              ,},],},{
              series:[
                {
                  name:"Open Claims",type:"pie",
                  data:in_arr,
                },],
            },
                {
                  series:[
                    {name:"Open Claims",type:"pie",
                data:in_arr,
              },],
              },],
        });
       }

       async function getAverage() {
        $("#info41").html("Please wait ...");
        return new Promise((resolve,reject)=>{
    var from_client=$("#from_client").val();
    var to_client=$("#to_client").val();
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{identityNum:15,from_client:from_client,to_client:to_client},
      async: true,
      success:function (data) {
        $("#info41").html(data);
resolve(1);
      },
      error:function (jqXHR, exception) {
        reject(jqXHR.responseText);
      }
    });
  });
  }
  
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
    $(".mychart").empty();
    $(".mychart").append("<canvas id=\"stackedBarChart1\" style=\"min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;\"></canvas>");
    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:11,
        start_date:start_date,
        end_date:end_date,
        val:val,
        clients:clients,
        users:users,
        val1:val1
      },
      async: true,
      success:function (data) {
//console.log(data);
        var json2= JSON.parse(data);
        $.each(json2, function(key, value){
          var client=json2[key].client_name;
          var total=json2[key].total;
          sum+=parseInt(total);
          analy_array.push(client);
          analy_array1.push(total);

        });


      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });


    var areaChartData = {
      labels  : analy_array,
      datasets: [
        {
          label               : '('+sum+') Claims ',
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
  function mycolor(){
    let arr= ["#33b2df","#546E7A","#d4526e","#13d8aa","#A5978B","#2b908f","#f9a3a4","#90ee7e","#f48024","#69d2e7","#991AFF","#809980","#FF4D4D","#E6B333","#6666FF"];
    return arr;
  }
