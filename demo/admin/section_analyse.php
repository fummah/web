<?php
session_start();
define("access",true);
$title="Analysis";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../../logout.php");
            die();
}

if(!isset($_GET['section_name']))
{
  die("Invalid Entry");
}
$section_name=$_GET['section_name'];
$date=date("Y-m");

?>
<link rel="stylesheet" href="./css/icheck-bootstrap.min.css">
<link rel="stylesheet" href="./css/select2.min.css">
<link rel="stylesheet" href="./css/daterangepicker.css">

<style type="text/css">
  .select2-container{
    z-index: 4000 !important;
  }
  .footer_part{
display:none;
  }
  .myactive{
    color: darkblue; !important;
    font-weight: bolder !important;
  }
  .google-visualization-tooltip { pointer-events: none; }
</style>
<body class="crm_body_bg">
<?php
$role=$db->myRole();
$mcausername=$db->loggedAs();
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$val=$role=="admin"?"1":$mcausername;         
?>
<section class="">
<div class="main_content_iner overly_inner ">
<div class="container-fluid p-0 ">
  <input type="hidden" id="open">
<div class="row bg-info" style="padding-top:10px !important;padding-bottom:10px !important">
  <div class="col-md-1"></div>
  <div class="col-md-5">
    <?php
echo ucfirst($section_name)
    ?>
     <span class="badge bg-warning" id="intotal">0</span> | <span class="badge bg-danger" id="total">0</span> | 
       All<div class="icheck-primary d-inline">
                <input type="radio" class="ccval" value="" id="radioPrimary3" name="r1" checked>
                <label for="radioPrimary3">
                </label>
              </div>
       Open<div class="icheck-primary d-inline">
                <input type="radio" class="ccval" value="1" id="radioPrimary1" name="r1">
                <label for="radioPrimary1">
                </label>
              </div>
               Closed<div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary2" class="ccval" value="0" name="r1">
                <label for="radioPrimary2">
                </label>
              </div>
  </div>
   <div class="col-md-4">
     Month :
              <select class="form-control mymodal allc" name="to_client" id="to_client">
                <?php
$zarr=array_reverse($db->day_rr(11,0));
                for($i=0;$i<count($zarr);$i++)
                {
                  $newdate=$zarr[$i];
                  echo "<option value='$newdate'>$newdate</option>";
                }
                ?>
              </select>
  </div>
  <div class="col-md-2"></div>
</div><hr>
<div class="row">
  <div class="col-md-4">
<div style="overflow-y: scroll; height:600px; padding: 10px !important;" id="det">
  
</div>

  </div>
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-6"><div id="piechart" style="width: auto; height: auto;"></div></div>
        <div class="col-md-6"><div id="piechart_3d" style="width: auto; height: auto;"></div></div>
      </div>
       <div class="row">
        <div class="col-md-6"><div id="piechart_savings1" style="width: auto; height: auto;"></div></div>
        <div class="col-md-6"><div id="piechart_savings2" style="width: auto; height: auto;"></div></div>
      </div>
       

<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
  <table id="example" class="table table-striped" cellspacing="0" width="100%">
    <thead>

      <tr><th>Claim Number</th><th>Full Name</th><th>Username</th><th>Client</th><th>Claim Value</th><th>Sch.Savings</th><th>Disc.Savings</th></tr>     
</thead>
<tbody id="info">
  </tbody>
</table>
</div>
</div>
  </div>
    </div>
</div>
</div>
</div>
</section>
<hr>
<?php
require_once("footer.php");
?>
<script type="text/javascript">
  $(document).ready(function() {
  $('#example').DataTable();
  } );
</script>
</body>
<script src="./js/select2.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      let section_name="<?php echo $section_name;?>";
      let date="<?php echo $date;?>";      
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);      
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);
      $(document).ready(function(){
getData(date);
      });
      $(document).on('change','.allc',function(e){
        date=$(this).val();
        getData(date);
      });
       $(document).on('click','.my',function(e){
        let val=$(this).attr("my");
        $(".my").removeClass("myactive");
        $(this).addClass("myactive");
        getClaims(date,val);
      });
       $(document).on('change','input[name="r1"]',function() {
    let open=$('input[name="r1"]:checked').val();
    $("#open").val(open);
    getData(date);
});

      const getData = (date)=>{    
      let open=$("#open").val();  
      reset();
        $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:38,
        month_date:date,
        section_name:section_name,
        open:open
          },
      async: true,
      success:function (data) { 
        let json=JSON.parse(data);
        loadTable(json);
             
      },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });
      };
      //Get Values
       const getClaims = (date,val)=>{  
       let open=$("#open").val();     
        $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:39,
        month_date:date,
        val:val,
        open:open,
        section_name:section_name
          },
      async: true,
      success:function (data) {   
           
        let json=JSON.parse(data);
        loadTable2(json);  
        processCSChart(json);
        processClientChart(json); 
        processCSSavingsChart(json);  
        processClientSavingsChart(json);         
      },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });
      };
      const processCSChart=(arr)=>{
let myarr=groupBy(arr);
let inarr=[['CS Name', 'Total']];
for (const key in myarr) {
let total=parseFloat(myarr[key]);
let afarr=[key,total];
inarr.push(afarr);
}
drawChart(inarr);
      };
      const processClientChart=(arr)=>{
let myarr=groupBy1(arr);
let inarr=[['Client Name', 'Total']];
for (const key in myarr) {
let total=parseFloat(myarr[key]);
let afarr=[key,total];
inarr.push(afarr);
}
drawChart1(inarr);
};
   const processCSSavingsChart=(arr)=>{
let myarr=groupBy2(arr);
console.log(myarr);
let inarr=[['CS', 'Total']];
for (const key in myarr) {
let username=myarr[key]["username"];
let total=parseFloat(myarr[key]["total_savings"].toFixed(2));
let afarr=[username,total];
inarr.push(afarr);
}
drawChart2(inarr);
};
const processClientSavingsChart=(arr)=>{
let myarr=groupBy3(arr);
let inarr=[['Client Name', 'Total']];
for (const key in myarr) {
let client_name=myarr[key]["client_name"];
let total=parseFloat(myarr[key]["total_savings"].toFixed(2));
let afarr=[client_name,total];
inarr.push(afarr);
}
drawChart3(inarr);
};
      const groupBy = (arr) =>{
 return arr.reduce(function (r, o) {
    (r[o.username])? r[o.username] += 1 : r[o.username] = 1;
    return r;
  }, {});
};
    const groupBy1 = (arr) =>{
 return arr.reduce(function (r, o) {
    (r[o.client_name])? r[o.client_name] += 1 : r[o.client_name] = 1;
    return r;
  }, {});
};
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
        const loadTable=(arr)=>{
          $("#det").empty(); 
          let sum=0;
          let intotal=arr.length;
          if(section_name=="schemes")
          {
          for(let key in arr)
          {
            let val_name=arr[key]["val_name"];
            let total=parseFloat(arr[key]["total"]);
            $("#det").append("<p my='"+val_name+"' class='my' style='cursor:pointer'><span class='badge bg-info'>"+total+"</span> "+val_name+"</p><hr>");
            sum+=total;
          } 
          }
          else if(section_name=="ICD10_codes") 
          {
             for(let key in arr)
          {
            let primaryICDCode=arr[key]["primaryICDCode"];
            let shortdesc=arr[key]["shortdesc"];
            let total=parseFloat(arr[key]["total"]);
            $("#det").append("<h5 my='"+primaryICDCode+"' class='my byer_name  f_s_16 f_w_600 color_theme2' style='cursor:pointer'><span class='badge bg-success'>"+total+"</span> "+primaryICDCode+"</h5>"+shortdesc+"<hr>");
            sum+=total;
          } 
          }
            else if(section_name=="tariff_codes") 
          {
             for(let key in arr)
          {
            let tariff_code=arr[key]["tariff_code"];
            let description=arr[key]["Description"];
            let total=parseFloat(arr[key]["total"]);
            $("#det").append("<h5 my='"+tariff_code+"' class='my byer_name  f_s_16 f_w_600 color_theme2' style='cursor:pointer'><span class='badge bg-success'>"+total+"</span> "+tariff_code+"</h5>"+description+"<hr>");
            sum+=total;
          } 
          }
          $("#intotal").text(intotal);
          $("#total").text(sum);
      };
      const loadTable2=(arr)=>{
          $("#info1").empty();  
            let datatable=$('#example').DataTable();
          datatable.clear().destroy();      
          for(let key in arr)
          {
            let claim_id=arr[key]["claim_id"];
            let claim_number=arr[key]["claim_number"];
            let full_name=arr[key]["first_name"]+" "+arr[key]["surname"];    
          }  
          $("#example").DataTable({    // Reinitialize it like new
    data: arr,
    pagingType: "full_numbers",
   });
    
      };
      function drawChart(arr) {
        var data = google.visualization.arrayToDataTable(arr);
        var options = {
          title: 'CS Claims'
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
        
      function drawChart1(arr) {     
        var data1 = google.visualization.arrayToDataTable(arr);
        var options1 = {
          title: 'Client Claims',
          is3D: true,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data1, options1);
      }
          function drawChart2(arr) {
      var data = google.visualization.arrayToDataTable(arr);
        var options = {
          title: 'CS Savings',
          pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart_savings1'));
        chart.draw(data, options);
      }
      function drawChart3(arr) {
       
             var data = google.visualization.arrayToDataTable(arr);
        var options = {
          title: 'Client Savings',
          legend: 'none',
          pieSliceText: 'label',
          slices: {  4: {offset: 0.2},
                    12: {offset: 0.3},
                    14: {offset: 0.4},
                    15: {offset: 0.5},
          },
        };
         var chart = new google.visualization.PieChart(document.getElementById('piechart_savings2'));
        chart.draw(data, options);
      }

      function reset()
      {
        $("#piechart").empty();
        $("#piechart_3d").empty(); 
        $("#piechart_savings1").empty();
        $("#piechart_savings2").empty();
         $("#info1").empty();  
            let datatable=$('#example').DataTable();
          datatable.clear().destroy();     
            
          $("#example").DataTable({    // Reinitialize it like new
    data: [],
    pagingType: "full_numbers",
   });        
            
          
      }
    </script>

