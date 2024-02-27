<?php
session_start();
define("access",true);
$title="PMBS";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../../logout.php");
            die();
}

if(!isset($_GET['client']))
{
  die("Invalid Entry");
}
$pmb=$_GET['pmb'];
$month=$_GET['month'];
$client=$_GET['client'];
$date=date("Y-m");
$mypmb=$pmb=="yes"?"PMB":"NON-PMB";
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
<div class="row bg-info" style="padding-top:10px !important;padding-bottom:10px !important">
  <div class="col-md-2"></div>
  <div class="col-md-4">
    Client Name : <b><?php echo $client;?></b>
  </div>
   <div class="col-md-4">
     Month : <b><?php echo $month;?></b>
  </div>
  <div class="col-md-2"></div>
</div><hr>
<div class="row">

    <div class="col-md-12">
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
  <table class="table table-striped" cellspacing="0" width="100%">

      <thead><tr><th>Username</th><th>Claims Closed</th><th>Discount Savings</th><th>Scheme Savings</th><th>Total Savings</th><th>Claim Value</th>
<th>Percentage</th><th>Average time to close (days)</th><th>Claims Referred</th></tr></thead>   

<tbody id="info">
  </tbody>
</table>
</div>
</div>
  </div>
    </div>
</div>
<hr>
<div class="row">
  <div class="col-md-4">
    <div id="piechart" style="width: 400px; height: 400px;"></div>
    </div>
    <div class="col-md-8">
      <div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
  <table id="example" class="table table-striped" cellspacing="0" width="100%">
<thead>
      <tr><th>Claim Number</th><th>First Name</th><th>Surname</th><th>Username</th><th>Client</th></tr>     
</thead>
<tbody id="info1">
 
  </tbody>
</table>
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
      let client="<?php echo $client;?>";
      let month="<?php echo $month;?>";      
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);     

      $(document).ready(function(){
getData(month,client);
      });
    $(document).on('click','.myuser',function(e){
      let username=$(this).attr("username");
      getClaims(month,client,username);
    })

      const getData = (month,client)=>{      
        $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:36,
        month_date:month,
        client_name:client
          },
      async: true,
      success:function (data) {         
      let json=JSON.parse(data);
      console.log(json);
      loadTable(json); 
      processCSChart(json);       
      },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });
      };
      const getClaims = (month,client,username)=>{      
        $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:37,
        month_date:month,
        client_name:client,
        username:username
          },
      async: true,
      success:function (data) {         
      let json=JSON.parse(data);
      console.log(json);  
      loadTable2(json);           
      },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });
      };
      const processCSChart=(arr)=>{
let inarr=[['CS Name', 'Total']];
for (const key in arr) {
 let username=arr[key]["username"];
let total=parseFloat(arr[key]["claims"]);
let afarr=[username,total];
inarr.push(afarr);
}
console.log(inarr);
drawChart(inarr);
      };      
    
      const groupBy = (arr) =>{
 return arr.reduce(function (r, o) {
    (r[o.username])? r[o.username] += 1 : r[o.username] = 1;
    return r;
  }, {});
};
  
        const loadTable=(arr)=>{
          $("#info").empty();                 
          for(let key in arr)
          {
                
            let username=arr[key]["username"];
            let claims=arr[key]["claims"];  
            let discount=arr[key]["discount"];
             let scheme=arr[key]["scheme"];
              let total_savings=arr[key]["total_savings"];
               let charged=arr[key]["charged"];
               let percentage=arr[key]["percentage"];
               let average=arr[key]["average"];
               let total_referred=arr[key]["total_referred"];
            $("#info").append("<tr><th><span class='bg-info myuser' style='padding:5px; cursor:pointer' username='"+username+"'>"+username+"</span></th><th>"+claims+"</th><th>"+discount+"</th><th>"+scheme+"</th><th>"+total_savings+"</th><th>"+charged+"</th><th>"+percentage+"</th><th>"+average+"</th><th>"+total_referred+"</th></tr>");  
          }      
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
          title: 'Claims Specialist Closed Claims'
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }        
      
    </script>

