google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawChart1);
google.charts.setOnLoadCallback(drawChart2);
google.charts.setOnLoadCallback(drawChart3);
google.charts.setOnLoadCallback(drawChart4);
google.charts.setOnLoadCallback(drawChart5);
let arrC=[];
let arrC1=[];
let arrC2=[];
let arrC3=[];
let arrC4=[];
let arrC5=[];
let defaultVal="medical_scheme";
let mainVal=[];
let subVal=[];
let inobj={"ui_name":"None","field_name":"none","status":"checked"};
let secDefault="";
let topDefault="";
let finalDefault="";
$(function () { 
    $('.select2').select2()
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    $('.daterange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
    }, function (start, end) {
        $("#dat1").val(start.format('Y-MM-DD'));
        $("#dat2").val(end.format('YYYY-MM-DD'));
        $("#datetxt").text(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
        reset();
    });
    getMainVal();
    arrangeBlock1();
    arrangeBlock2();
    //Data Proc
    let x1 = getSecSec();
    x1.then((value)=>{

    });
   
});
const getMainVal = ()=>{
  const obj={
    identity_number:1
  };
  $.ajax({
    url:"analysisajax.php",
    type:"POST",
    data:obj,
    async: false,
    success:function (data) {
      let m = JSON.parse(data); 
      mainVal = m["main"];
      subVal = m["sub"];

    },
    error:function (jqXHR, exception) {
      console.log(jqXHR.responseText);
    }
  }); 
}
const getSecSec = async (badge="badge bg-success",inclass="myTable")=>{
  return new Promise((resolve,reject)=>{
  const obj={
    identity_number:2,
    start_date:$("#dat1").val(),
    end_date:$("#dat2").val(),
    open:getRadio("r1"),
    section:defaultVal,
    sec_section:secDefault,
    top_section:topDefault,
    final_section:finalDefault,
    typ:$("#typ").val(),
    checkBoxes:JSON.stringify(getVals())
  }; 
  $.ajax({
    url:"analysisajax.php",
    type:"POST",
    data:obj,
    async: true,
    beforeSend: function() {
      $(".load-bar").show();
  },
    success:function (data) {
      console.log(data);
      let m = JSON.parse(data); 
      secTemplate(m,badge,inclass); 
      resolve(1);    
    },
    error:function (jqXHR, exception) {
      reject(jqXHR.responseText);
    },
    complete: function() {
      $(".load-bar").hide();
    }
  }); 
});
}
//Run Template
const secTemplate=(obj,badge,inclass)=>{
  
  let h = [...new Map(obj.map((m) => [m.claim_id, m])).values()];
  let f1=processCSChart(h);
  f1.then((value)=>{
    arrC=value;    
    drawChart();
  });
  let f2=processClientsChart(h);
  f2.then((value)=>{
    arrC1=value;    
    drawChart1();
  });
  let f3=processGraph3(h);
  f3.then((value)=>{
    arrC2=value;
    drawChart2();
  });
  let f4=processGraph4(h);
  f4.then((value)=>{
    arrC3=value;
    drawChart3();
  });
  let f5=processGraph5(h);
  f5.then((value)=>{
    arrC4=value;    
    drawChart4();
  });
  let f6=processGraph5(h);
  f6.then((value)=>{
    arrC5=value;
    drawChart5();
  });
  let fx3=loadTable(obj);
  fx3.then((value)=>{  
  });  
  obj=groupMainValues(obj);  
  if(inclass!="finalnext")
  {
    obj = Object.entries(obj);
  obj.sort((a, b) => b[1] - a[1]);
  $("#"+inclass).empty();  
  let template="";
  for(let key in obj)
  {
    let total=obj[key][1];
    let heading_arr=obj[key][0].split('~');
    let heading=heading_arr[0];
    let txt =heading_arr[1];
template+="<tr><td><h5 my='"+heading+"' class='"+inclass+" byer_name  f_s_16 f_w_600 color_theme2' style='cursor:pointer'>";
template+="<span class='"+badge+"'>"+total+"</span> "+heading+"<br><span class='intxt'>"+txt+"</span></h5></td></tr>";
  }
  $("#"+inclass).append(template);
}
$("#tot_val").text(obj.length);
}

//Get Radio Button
const getRadio =(val)=>{
  return $('input[name="'+val+'"]:checked').val();
}

//Rearrange
const arrangeBlock1 = () =>{
 for(let key in mainVal)
 {
  let ui_name=mainVal[key]["ui_name"]; 
  let field_name=mainVal[key]["field_name"];
  let status=mainVal[key]["status"];
  $("#ourmain").append("<div class='radio icheck-info'><input type='radio' class='ourmain' id='"+field_name+"' value='"+field_name+"' name='components' "+status+"/><label for='"+field_name+"'>"+ui_name+"</label></div><hr>");
 }
 arrangeBlock3();
}

//Rearrange 2
const arrangeBlock2 = () =>{
   $("#submain").empty();
  let topmain = mainVal.filter(function (creature) {
    return creature.field_name !== defaultVal;
});
topmain.push(inobj);
for(let key in topmain)
{
let ui_name=topmain[key]["ui_name"]; 
let field_name=topmain[key]["field_name"]+"_sub";
let status=topmain[key]["status"];
$("#submain").append(ui_name+" <div class='icheck-carrot d-inline'><input type='radio' class='submain' id='"+field_name+"' value='"+field_name+"' name='sel' "+status+"/><label for='"+field_name+"'></label></div> ");
}
}

//Rearrange 3
const arrangeBlock3 = () =>{ 
for(let key in subVal)
{
let ui_name=subVal[key]["ui_name"]; 
let field_name=subVal[key]["field_name"];
$("#subval").append(ui_name+" <div class='icheck-amethyst d-inline'><input type='checkbox' class='submain mychecked' id='"+field_name+"' value='"+field_name+"' name='mychecked[]' checked/><label for='"+field_name+"'></label></div> ");
}
}

//Group Claims
const groupClaims= (arr) =>{
  return arr.reduce(function (r, o) {
     (r[o.claim_id])? r[o.claim_id] += 1 : r[o.claim_id] = 1;
     return r;
   }, {});
 };
//Group Main
const groupMainValues = (arr) =>{
  return arr.reduce(function (r, o) {
     (r[o.heading])? r[o.heading] += 1 : r[o.heading] = 1;
     return r;
   }, {});
 };

 //Group By CS
 const groupByCS = (arr) =>{
  return arr.reduce(function (r, o) {
     (r[o.username])? r[o.username] += 1 : r[o.username] = 1;
     return r;
   }, {});
 };


 //Group By Clients
 const groupByClients = (arr) =>{
  return arr.reduce(function (r, o) {
     (r[o.client_name])? r[o.client_name] += 1 : r[o.client_name] = 1;
     return r;
   }, {});
 };

 //Group Chart 3
 const groupBy2 = (arr) =>{
  let result = [];
 arr.reduce(function(res, value) {
   if (!res[value.username]) {
     res[value.username] = { username: value.username, total_savings: 0 };
     result.push(res[value.username])
   }
   res[value.username].total_savings += parseFloat(value.total_savings);
   return res;
 }, {});
 return result;
 };

  //Group Chart 4
 const groupBy3 = (arr) =>{
  let result = [];
 arr.reduce(function(res, value) {
   if (!res[value.client_name]) {
     res[value.client_name] = { client_name: value.client_name, total_savings: 0 };
     result.push(res[value.client_name])
   }
   res[value.client_name].total_savings += parseFloat(value.total_savings);
   return res;
 }, {});
 return result;
 };

 //Group Chart 5
 const groupBy4 = (arr) =>{
  let result = [];
 arr.reduce(function(res, value) {
   if (!res[value.username]) {
     res[value.username] = { username: value.username, claim_value: 0 };
     result.push(res[value.username])
   }
   res[value.username].claim_value += parseFloat(value.claim_value);
   return res;
 }, {});
 return result;
 };
 
  //Group Chart 6
 const groupBy5 = (arr) =>{
  let result = [];
 arr.reduce(function(res, value) {
   if (!res[value.client_name]) {
     res[value.client_name] = { client_name: value.client_name, claim_value: 0 };
     result.push(res[value.client_name])
   }
   res[value.client_name].claim_value += parseFloat(value.claim_value);
   return res;
 }, {});
 return result;
 };

 //Process CS Graph Values
 const processCSChart = async (arr)=>{
  return new Promise((resolve,reject)=>{
  let myarr=groupByCS(arr); 
  let inarr=[['CS Name', 'Total']];
  for (const key in myarr) {
  let total=parseFloat(myarr[key]);
  let afarr=[key,total];
  inarr.push(afarr);
  }
  resolve(inarr);
});
  };

   //Process Client Graph Values
  const processClientsChart= async (arr)=>{
    return new Promise((resolve,reject)=>{
  let myarr=groupByClients(arr);
  let inarr=[['Client Name', 'Total']];
  for (const key in myarr) {
  let total=parseFloat(myarr[key]);
  let afarr=[key,total];
  inarr.push(afarr);
  }
  resolve(inarr);
});  
  };

//Process Graph 3
const processGraph3= async (arr)=>{
  return new Promise((resolve,reject)=>{
  let myarr=groupBy2(arr); 
  let inarr=[['CS', 'Total']];
  for (const key in myarr) {
  let username=myarr[key]["username"];
  let total=parseFloat(myarr[key]["total_savings"].toFixed(2));
  let afarr=[username,total];
  inarr.push(afarr);
  }
  resolve(inarr);
});
  };

  //Process Graph 4
  const processGraph4= async (arr)=>{
    return new Promise((resolve,reject)=>{
    let myarr=groupBy3(arr);
    let inarr=[['Client Name', 'Total']];
    for (const key in myarr) {
    let client_name=myarr[key]["client_name"];
    let total=parseFloat(myarr[key]["total_savings"].toFixed(2));
    let afarr=[client_name,total];
    inarr.push(afarr);
    }
    resolve(inarr);
  });
    };

    //Process Graph 5
const processGraph5= async (arr)=>{
  return new Promise((resolve,reject)=>{
  let myarr=groupBy4(arr); 
  let inarr=[['CS', 'Total']];
  for (const key in myarr) {
  let username=myarr[key]["username"];
  let total=parseFloat(myarr[key]["claim_value"].toFixed(2));
  let afarr=[username,total];
  inarr.push(afarr);
  }
  resolve(inarr);
});
  };

  //Process Graph 6
  const processGraph6= async (arr)=>{
    
    return new Promise((resolve,reject)=>{
    let myarr=groupBy5(arr);
    let inarr=[['Client Name', 'Total']];
    for (const key in myarr) {
    let client_name=myarr[key]["client_name"];
    let total=parseFloat(myarr[key]["claim_value"].toFixed(2));
    let afarr=[client_name,total];
    inarr.push(afarr);
    }    
    resolve(inarr);
  });
    };

    //Sumup
    const sumUp=(arr)=>
    {
      let total=0;
      for (let i = 1; i < arr.length; i++) {
        total += arr[i][1];
      }
      return total;
    }
    //Process Graph 5
//main change
$(document).on('change','input[name="components"]',function(){
let val=$(this).val();
defaultVal=val;
reset();
});

$(document).on('change','input[name="mychecked[]"]',function(){
  getSecSec("badge badge-danger","final");
  });

//sec top change
$(document).on('change','input[name="sel"]',function(){
  let val=$(this).val();
  topDefault=val!=="none_sub"?val:"";
  finalDefault="";
  getSecSec("badge badge-danger","final");
  });

  //final final
$(document).on('click','.final',function(){
  let val=$(this).attr("my");
  finalDefault=val;
  getSecSec("badge badge-danger","finalnext");
  $(".final").removeClass("hover-color");
  $(this).addClass("hover-color");
  });

  //Open change
  $(document).on('change','input[name="r1"]',function(){
    reset();
    });

    const reset = () =>{
      secDefault="";
      topDefault="";
      finalDefault="";
      arrangeBlock2();
      getSecSec();
      $("#final").html("<p align='center'>Panel</p>");     
    }
    //Checkboxes
    const getVals=()=>{
      var cboxes = document.getElementsByName('mychecked[]');
      var len = cboxes.length;
      let arrayCheck=[];
      for (var i=0; i<len; i++) {          
          let ii={
            status:(cboxes[i].checked?'checked':'unchecked'),
            val:cboxes[i].value
          };
          arrayCheck.push(ii);            
      }
      return arrayCheck;      
}
    
  //click sec section
  $(document).on('click','.myTable',function(){
    let val=$(this).attr("my");  
    secDefault=val;
    finalDefault="";
    getSecSec("badge badge-danger","final");
    $(".myTable").removeClass("hover-color");
    $(this).addClass("hover-color");
    });

    const  loadTable = async (obj) => {
      return new Promise((resolve,reject)=>{
      let template="";
      obj = [...new Map(obj.map((m) => [m.claim_id, m])).values()];      
      let tot_claim_value=0;
      let tot_savings_scheme=0;
      let tot_savings_discount=0;
      let tot_total_savings=0;
      $("#info").empty();
    for(let key in obj)
    {
    let claim_number=obj[key]["claim_number"];
    let full_name=obj[key]["full_name"];
    let username=obj[key]["username"];
    let client_name=obj[key]["client_name"];
    let claim_value=obj[key]["claim_value"];
    let savings_scheme=obj[key]["savings_scheme"];
    let savings_discount=obj[key]["savings_discount"];
    let total_savings=obj[key]["total_savings"];
    let claim_id=obj[key]["claim_id"];
    tot_claim_value+=parseFloat(claim_value);
    tot_savings_scheme+=parseFloat(savings_scheme);
    tot_savings_discount+=parseFloat(savings_discount);
    tot_total_savings+=parseFloat(total_savings);
    template+="<tr><td><form action='../case_details.php' method='post' target='_blank'><input type='hidden' name='claim_id' value="+claim_id+"/><button name='btn' class='yu'>"+claim_number+"</button></form></td><td>"+full_name+"</td><td>"+username+"</td><td>"+client_name+"</td><td>"+claim_value+"</td><td>";
    template+=savings_scheme+"</td><td>"+savings_discount+"</td><td>"+total_savings+"</td></tr>";        
    }
    tot_claim_value=tot_claim_value.toFixed(2);
    tot_savings_scheme=tot_savings_scheme.toFixed(2);
    tot_savings_discount=tot_savings_discount.toFixed(2);
    tot_total_savings=tot_total_savings.toFixed(2);
    template+="<tr><th colspan='4'></th><th>"+tot_claim_value+"</th><th>"+tot_savings_scheme+"</th><th>"+tot_savings_discount+"</th><th>"+tot_total_savings+"</th></tr>";
      $("#tot_val1").text(obj.length);
    $("#info").append(template);
    resolve(1);
  });
    } 
       
    //Graph1
      function drawChart() {
     var data = google.visualization.arrayToDataTable(arrC);
     let tot=sumUp(arrC);     
var options = {
  title: 'CS Claims ('+tot+')'
};
var chart = new google.visualization.PieChart(document.getElementById('cs_claims'));
chart.draw(data, options);
}
//Graph 2
function drawChart1() {
        var data = google.visualization.arrayToDataTable(arrC1);
        let tot=sumUp(arrC1);
        var options = {
          title: 'Clients Claims ('+tot+')',
          is3D: true,
        };
        var chart = new google.visualization.PieChart(document.getElementById('clients_claims'));
        chart.draw(data, options);
      } 
      //Graph 3
      function drawChart2() {
        var data = google.visualization.arrayToDataTable(arrC2);
        let tot=sumUp(arrC2).toFixed(2);
        var options = {
          title: 'Cs Savings ('+tot+')',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('cs_savings'));
        chart.draw(data, options);
      }

      //Graph 4
      function drawChart3() {
        var data = google.visualization.arrayToDataTable(arrC3);
        let tot=sumUp(arrC3).toFixed(2);
        var options = {
          title: 'Clients Savings ('+tot+')',
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('clients_savings'));
        chart.draw(data, options);     
      }

      //Graph 5
      function drawChart4() {

        var data = google.visualization.arrayToDataTable(arrC4);
        let tot=sumUp(arrC4).toFixed(2);
        var options = {
          title: 'CS Claim Value ('+tot+')'
        };

        var chart = new google.visualization.PieChart(document.getElementById('cs_claim_value'));

        chart.draw(data, options);
      }

      //Graph 6
      function drawChart5() {
        var data = google.visualization.arrayToDataTable(arrC5);
        let tot=sumUp(arrC5).toFixed(2);
        var options = {
          title: 'Clients Claim Value ('+tot+')',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('clients_claim_value'));
        chart.draw(data, options);
      }

      //Search
      function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[0];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }       
        }
      }
      function searchTable1() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput1");
        filter = input.value.toUpperCase();
        table = document.getElementById("final");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[0];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }       
        }
      }



