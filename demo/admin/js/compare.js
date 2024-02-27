$(function () {
   //$('.select2bs4').select2(); 
   $('#users').select2({
    placeholder: "All Claims Specialist",
    allowClear: true
  });
    $('#clients').select2({
    placeholder: "All Clients",
    allowClear: true
  });
  $(".load-bar").hide();
  
});
let valid = false;
let graph1=[];
$(document).on('change','.allc', function(){
  const f = naly();
  f.then((value)=>{
    //getPredict(value),
 
    $(".load-bar").hide();
  });
});
$(document).on('change','.select2bs4',function(){
  naly();
});

$(document).on('change','input[name="r1"]',function(){
   const f = naly();
  f.then((value)=>{   
  });
});
$(document).on('change','input[name="r3"]',function(){
   const f = naly();
  f.then((value)=>{  
  });
});
$(document).on('change','input[name="emerg"]',function(){
 mlTScoring(getVals()); 
});


$(document).on('click','#predictBtn41',function(){
  getPredict(getVals());
});
$(document).on('click','#predictBtn42',function(){
  getDays(getVals());
});
$(document).on('click','#predictBtn43',function(){
  mlTScoring(getVals());
});
$(document).on('click','#predictBtn44',function(){
  mlTariff(getVals());
});

const naly = async () => {
   $(".graph").hide();
   $(".graph1").show();
  return new Promise((resolve,revoke)=>{
    $("#info").empty();
$(".cs").html("<div id='cs_trend' style='min-height: 250px;'></div>");
$(".clients").html("<div id='apex_2' style='min-height: 250px;'></div>");
  const obj=getVals();
validateObj(obj);
if(!valid)
{
  $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:obj,
      async: true,
       beforeSend:function(){
                    $(".load-bar").show();
                },
      success:function (data) { 
    var json2= JSON.parse(data);
    const compare1=json2["compare1"].reverse();
    const compare2=json2["compare2"].reverse();
    graph1=compare1;    
    getTrend1Graph(compare1);
    getTrend1Graph(compare2,"apex_2");    
    resolve(obj);
      },
      error:function (jqXHR, exception) {
        revoke(jqXHR.responseText);
      },
                complete: function (result, status) {
                    $(".load-bar").hide();
                    $(".btn-danger").prop('disabled', false);
                               }
    });
}
else
{
  revoke(0);
}
});
};
const getPredict= async (obj)=>{
  $(".41p1").html("<div class='w-100' id='chart411' style='height:250px'></div>");
$(".41p2").html("<div class='w-100' id='chart412' style='height:250px'></div>");
$(".graph2").show();
return new Promise((resolve,revoke)=>{
obj.identityNum=41;
   $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:obj,
      async: true,
       beforeSend:function(){   
       $(".load-bar").show();                 
                },
      success:function (data) { 
    var json2= JSON.parse(data);   
  claimvalSav41(json2["compare1"]);
  claimvalSav41(json2["compare2"],"chart412");
    resolve(1);
      },
      error:function (jqXHR, exception) {
        revoke(jqXHR.responseText);
      },
                complete: function (result, status) {
                    $(".load-bar").hide();
                               }
    });
 }); 
}
//
const getDays= async (obj)=>{
   $(".42p1").html("<div class='w-100' id='chart421' style='height:250px'></div>");
$(".42p2").html("<div class='w-100' id='chart422' style='height:250px'></div>");
$(".graph3").show();
return new Promise((resolve,revoke)=>{
obj.identityNum=42;
   $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:obj,
      async: true,
       beforeSend:function(){   
       $(".load-bar").show();                 
                },
      success:function (data) { 
    var json2= JSON.parse(data);  
  weekDaysML(json2["compare1"]);
  weekDaysML(json2["compare2"],"chart422");
    resolve(1);
      },
      error:function (jqXHR, exception) {
        revoke(jqXHR.responseText);
      },
                complete: function (result, status) {
                    $(".load-bar").hide();
                               }
    });
 }); 
}
//
const mlTariff= async (obj)=>{
  $(".graph5").show();
return new Promise((resolve,revoke)=>{
obj.identityNum=43;
   $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:obj,
      async: true,
       beforeSend:function(){   
       $(".load-bar").show();                 
                },
      success:function (data) { 
    var json2= JSON.parse(data);   
  mlTariffTable(json2["compare1"]);
  mlTariffTable(json2["compare2"],"44p2");
    resolve(1);
      },
      error:function (jqXHR, exception) {
        revoke(jqXHR.responseText);
      },
                complete: function (result, status) {
                    $(".load-bar").hide();
                               }
    }); 
 }); 
}
const mlTScoring= async (obj)=>{
  $(".graph4").show();
return new Promise((resolve,revoke)=>{
obj.identityNum=44;
   $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:obj,
      async: true,
       beforeSend:function(){    
       $(".load-bar").show();                
                },
      success:function (data) {     
    var json2= JSON.parse(data);   
    mlTTables(json2["compare1"],json2["compare2"]) 
    resolve(1);
      },
      error:function (jqXHR, exception) {
        revoke(jqXHR.responseText);
      },
                complete: function (result, status) {
                    $(".load-bar").hide();
                               }
    }); 
 }); 
}
//
const getVals = () =>
{
  let year1=$("#val1").val();
  let year2=$("#val2").val();
  let from_month=$("#from_month").val();
  let to_month=$("#to_month").val();
  let type=$('input[name="r1"]:checked').val();
  let emergency=$('input[name="emerg"]:checked').val();
  let r3=$('input[name="r3"]:checked').val();
  let users=$("#users").val();
  let clients=$("#clients").val();
    
  identityNum=40;
  const obj = {
year1,year2,from_month,to_month,type,identityNum,emergency,users,clients,r3
  };
  return obj;
}

const  validateObj = (data) =>{
  valid=false;
  for(var key in data) {
        if(data[key] === "") {
          valid=true;
           $("#info").append("<p><b>"+key + "</b> is blank. Please select something.</p>");           
        }
    }
}  

const mlTariffTable=(arr,sec="44p1")=>{
  let txt="<div class='white_card_body QA_section' style='width:100% !important'><div class='QA_table'><div id='DataTables_Table_1_wrapper' class='dataTables_wrapper no-footer'><table id='"+sec+"' class='table table-striped' cellspacing='0' width='100%'><thead>"+
  "<tr><th>Tariff Code</th><th>Claims</th><th>Scheme(%)</th><th>Discount(%)</th><th>PMB(%)</th><th>Scoring(%)</th></tr></thead><tbody>";

for(let key in arr)
    {    
    let emerg=arr[key]["emerg"]!==null?"*":"";

      let tariff_code=arr[key]["tariff_code"]+emerg;
      let total_claims=parseFloat(arr[key]["claims"]);
      let no_sav=arr[key]["no_sav"];
      let non_pmbx=parseFloat(arr[key]["non_pmbx"]);
      let pmbx=parseFloat(arr[key]["pmbx"]);
      let disc_perc=arr[key]["disc_perc"]+"%";
      let sch_perc=arr[key]["sch_perc"]+"%";
      let total=arr[key]["total"];
      let with_disc_sav=arr[key]["with_disc_sav"];
      let with_sav=arr[key]["with_sav"];      
      let perc=arr[key]["perc"]+"%";
      pmbx=calcperc(pmbx,total);
      non_pmbx=calcperc(non_pmbx,total);
txt+="<tr><td>"+tariff_code+"</td><td><span class='badge bg-danger'>"+total_claims+"</span></td><td><span class='badge bg-info'>"+sch_perc+
"</span></td><td><span class='badge bg-info'>"+disc_perc+"</span></td><td><span class='badge bg-info'>"+pmbx+"</span> | <span class='badge bg-info'>"+non_pmbx+"</span></td><td><span class='badge bg-success'>"+perc+"</span></td></tr>";
    }
     txt+="</tbody></table></div></div></div>";
    $("."+sec).html(txt);;
    $('#'+sec).dataTable( {
    "order": [5, 'desc']
} );
};

const calcperc=(val1,val2)=>{
return Math.round((val1/val2)*100)+"%";
};
const mlTTables = (arr,arr1) =>{
  let mainArr=[]
  let txt="<div class='white_card_body QA_section' style='width:100% !important'><div class='QA_table'><div id='DataTables_Table_1_wrapper' class='dataTables_wrapper no-footer'>"+
  "<table class='table table-striped' cellspacing='0' width='100%'><thead><tr><th>Month</th><th>Total</th><th>With Savings</th><th>Without Savings</th><th>PMB</th><th>NON-PMB</th><th>Scheme</th><th>Discount</th><th>Savings Scoring(%)</th></tr></thead><tbody>";
for(let key in arr)
    {        
     let inarr=[{'date1':arr[key]["claim_date"],'date2':arr1[key]["claim_date"],'arr1':arr[key]["cont"],'arr2':arr1[key]["cont"]}];      
     mainArr.push(inarr);
    }

    for (let key in mainArr) {
      let dat1=mainArr[key][0]["date1"];
      let dat2=mainArr[key][0]["date2"];  
      let total_claims=parseFloat(mainArr[key][0]["arr1"][0]["total_claims"]);
      let with_savings=parseFloat(mainArr[key][0]["arr1"][0]["with_savings"]);
      let non_savings=parseFloat(mainArr[key][0]["arr1"][0]["non_savings"]);
      let pmb=parseFloat(mainArr[key][0]["arr1"][0]["pmb"]);
      let non_pmb=parseFloat(mainArr[key][0]["arr1"][0]["non_pmb"]);
      let scheme_savings=parseFloat(mainArr[key][0]["arr1"][0]["scheme_savings"]);
      let discount_savings=parseFloat(mainArr[key][0]["arr1"][0]["discount_savings"]);
      let perc_scoring=calcperc(with_savings,total_claims);
      let perc_pmb=calcperc(pmb,total_claims);
      let perc_non_pmb=calcperc(non_pmb,total_claims);
      let perc_scheme=calcperc(scheme_savings,total_claims);
      let perc_discount=calcperc(discount_savings,total_claims);

      let total_claims1=parseFloat(mainArr[key][0]["arr2"][0]["total_claims"]);
      let with_savings1=parseFloat(mainArr[key][0]["arr2"][0]["with_savings"]);
      let non_savings1=parseFloat(mainArr[key][0]["arr2"][0]["non_savings"]);
      let pmb1=parseFloat(mainArr[key][0]["arr2"][0]["pmb"]);
      let non_pmb1=parseFloat(mainArr[key][0]["arr2"][0]["non_pmb"]);
      let scheme_savings1=parseFloat(mainArr[key][0]["arr2"][0]["scheme_savings"]);
      let discount_savings1=parseFloat(mainArr[key][0]["arr2"][0]["discount_savings"]);
      let perc_scoring1=calcperc(with_savings1,total_claims1);
      let perc_pmb1=calcperc(pmb1,total_claims1);
      let perc_non_pmb1=calcperc(non_pmb1,total_claims1);
      let perc_scheme1=calcperc(scheme_savings1,total_claims1);
      let perc_discount1=calcperc(discount_savings1,total_claims1);

txt+="<tr><td>"+dat1+"<hr>"+dat2+"</td><td><span class='badge bg-danger'>"+total_claims+"</span><hr><span class='badge bg-danger'>"+total_claims1+"</span></td><th>"+with_savings+"<hr>"+with_savings1+
"</td><th>"+non_savings+"<hr>"+non_savings1+"</td><th>"+pmb+" | <span class='badge bg-info'>"+perc_pmb+"</span><hr>"+pmb1+
" | <span class='badge bg-info'>"+perc_pmb1+"</span></td><td>"+non_pmb+" | <span class='badge bg-info'>"+perc_non_pmb+"</span><hr>"+non_pmb+
" | <span class='badge bg-info'>"+perc_non_pmb1+"</span></td><th>"+scheme_savings+" | <span class='badge bg-info'>"+perc_scheme+"</span><hr>"+scheme_savings1+
" | <span class='badge bg-info'>"+perc_scheme1+"</span></td><th>"+discount_savings+" | <span class='badge bg-info'>"+perc_discount+"</span><hr>"+discount_savings1+
" | <span class='badge bg-info'>"+perc_discount1+"</span></td><th><span class='badge bg-success'>"+perc_scoring+"</span><hr><span class='badge bg-success'>"+perc_scoring1+"</span></td></tr>";

    }
    txt+="</tbody></table></div></div></div>";
    $(".43p1").html(txt);
};
 
  function getTrend1Graph(arr,sec="cs_trend")
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

        var chart = new ApexCharts(document.querySelector("#"+sec), options);             
        chart.render();
         
  }
  
  
  function claimvalSav41(arr,sec="chart411")
  {
    let avg_claim_value = parseFloat(arr["averages"]["avg_cv"]);
    let avg_savings = parseFloat(arr["averages"]["avg_sav"]);
    let percf=Math.round((avg_savings/avg_claim_value)*100)+"%";
    avg_savings=avg_savings.toFixed(2);
    avg_claim_value=avg_claim_value.toFixed(0);
    arr=arr["main"];   
    let arrSavings=[];
    let arrClaimNumber=[];
    let arrPerc=[];
    let arrCategory=[];
    let arrClaimValue=[];
  
  for(key in arr)    
  {
    let claim_number=parseFloat(arr[key]["vals"]["total_claims"]);
    let savings=parseFloat(arr[key]["vals"]["total_savings"]);
    let claim_value=parseFloat(arr[key]["vals"]["claim_value"]);
    let category=arr[key]["category"].replace('BETWEEN ','');
    let perc=Math.round((savings/claim_value)*100);
    perc=parseFloat(perc);
    category=category.replace('AND','-<');
    let spl=category.split('-');
    category=spl[spl.length-1];
    
    arrSavings.push(savings); 
    arrClaimValue.push(claim_value); 
    arrClaimNumber.push(claim_number); 
    arrPerc.push(perc);   
    arrCategory.push(category.replace('AND','-'));
  }

  var options = {
          series: [{
          name: 'Savings | Avg : '+avg_savings,
          type: 'column',
          data: arrSavings
        },
        {
          name: 'Percentage | Avg : '+percf,
          type: 'line',
          data: arrPerc
        }, {
          name: 'Number of Claims',
          type: 'line',
          data: arrClaimNumber
        }],
          chart: {
          height: 350,
          type: 'line',
          stacked: false
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: [1, 1, 4]
        },
        title: {
          text: 'Savings Vs Claim Value | Avg : '+avg_claim_value,
          align: 'left',
          offsetX: 110
        },
        xaxis: {
          categories: arrCategory,
        },
        yaxis: [
          {
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#008FFB'
            },
            labels: {
              style: {
                colors: '#008FFB',
              }
            },
            title: {
              text: "Savings",
              style: {
                color: '#008FFB',
              }
            },
            tooltip: {
              enabled: true
            }
          },
        
          {
            seriesName: 'Percentage',
            opposite: true,
            yaxis: {
    max: 100,
  },
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#00E396'
            },
            labels: {
              style: {
                colors: '#00E396',
              }
            },
            title: {
              text: "Percentage",
              style: {
                color: '#00E396',
              }
            },
          },
          {
            seriesName: 'Number of Claims',
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#FEB019'
            },
            labels: {
              style: {
                colors: '#FEB019',
              },
            },
            title: {
              text: "Number of Claims",
              style: {
                color: '#FEB019',
              }
            }
          },
        ],
        tooltip: {
          fixed: {
            enabled: true,
            position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
            offsetY: 30,
            offsetX: 60
          },
        },
        legend: {
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#"+sec), options);
        chart.render();
        
      
  }
  function weekDaysML(arr,sec="chart421")
  {
     const cs_arr=arr.reverse();    
    let monday=[];
    let tuesday=[]; 
    let wednesday=[];
    let thursday=[];
    let friday=[];
    let satarday=[];
    let sunday=[];
    let monthArr=[];  
     for(let key in cs_arr)
    {
      let month=cs_arr[key]["claim_date"];
      for(let y in cs_arr[key]["cont"])
      {
      let day=arr[key]["cont"][y]["day_name"];  
      let total=parseFloat(arr[key]["cont"][y]["total"]);   
      if(day=="Monday")
      {
        monday.push(total);               
      }
      else if(day=="Tuesday")
      {
        tuesday.push(total);               
      }
        else if(day=="Wednesday")
      {
        wednesday.push(total);               
      }
        else if(day=="Thursday")
      {
        thursday.push(total);               
      }
        else if(day=="Friday")
      {
        friday.push(total);               
      }
        else if(day=="Saturday")
      {
        satarday.push(total);               
      }
        else if(day=="Sunday")
      {
        sunday.push(total);               
      }
      }     
     
        monthArr.push(month);  
    }
    

 
     var options = {
          series: [{
          name: 'Monday',
          data: monday
        }, {
          name: 'Tuesday',
          data: tuesday
        }, {
          name: 'Wednesday',
          data: wednesday
        }, {
          name: 'Thursaday',
          data: thursday
        }, {
          name: 'Friday',
          data: friday
        }, {
          name: 'Saturday',
          data: satarday
        }, {
          name: 'Sunday',
          data: sunday
        }],
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              total: {
                enabled: true,
                offsetX: 0,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
          text: 'Weekly'
        },
        xaxis: {
          categories: monthArr,
          labels: {
            formatter: function (val) {
              return val
            }
          }
        },
        yaxis: {
          title: {
            text: undefined
          },
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#"+sec), options);
        chart.render();
      
  }
  
    
  function mycolor(){
    let arr= ["#33b2df","#546E7A","#d4526e","#13d8aa","#A5978B","#2b908f","#f9a3a4","#90ee7e","#f48024","#69d2e7","#991AFF","#809980","#FF4D4D","#E6B333","#6666FF"];
    return arr;
  }
  function toNum(num)
  {
    return parseFloat(num);
  }
