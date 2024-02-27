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

if(!isset($_GET['pmb']))
{
  die("Invalid Entry");
}
$pmb=$_GET['pmb'];
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
    <?php echo $mypmb;?> <span class="badge bg-warning" id="pmb">0</span>
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
<div id="piechart" style="width: auto; height: auto;"></div>
<div id="piechart_3d" style="width: auto; height: auto;"></div>
  </div>
    <div class="col-md-8">
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
  <table id="example" class="table table-striped" cellspacing="0" width="100%">
    <thead>
      <tr><th>Claim Number</th><th>First Name</th><th>Surname</th><th>Username</th><th>Client</th></tr>     
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
      let pmb="<?php echo $pmb;?>";
      let date="<?php echo $date;?>";      
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);      
      google.charts.setOnLoadCallback(drawChart1);
      $(document).ready(function(){
getData(date);
      });
      $(document).on('change','.allc',function(e){
        date=$(this).val();
        getData(date);
      })

      const getData = (date)=>{      
        $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:35,
        month_date:date,
        pmb,pmb
          },
      async: true,
      success:function (data) { 
        let json=JSON.parse(data);
        $("#pmb").text(json.length);      
        loadTable(json);
        processCSChart(json);
        processClientChart(json);
       
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
        const loadTable=(arr)=>{
          $("#info").empty();  
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
          title: 'Claims Specialist'
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
        
      function drawChart1(arr) {
        var data1 = google.visualization.arrayToDataTable(arr);
        var options1 = {
          title: 'Clients',
          is3D: true,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data1, options1);
      }
    </script>

