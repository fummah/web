<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin" || $_SESSION['level']=="controller") {


}
else
{
  ?>
  <script type="text/javascript">
    location.href = "../../demo/login.html";
  </script>

  <?php
}
$title="Billing";
include ("../classes/reportsClass.php");
$results=new reportsClass();
$date= date("Y-m", strtotime("-1 months"));
$today=date("Y-m");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>MCA | Billing</title>

  <!-- Font Awesome Icons -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/ionicons.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .reop {
      background: none;
      border: none;
      color: cadetblue;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php
  require_once("main_temp.php");
  ?>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <div class="info-box-content">
                <span class="info-box-number">
                      Select Month : <select class="form-control allc" name="from_month" id="from_month" onchange="getBill()">
                <?php
                $zarr=array_reverse($results->day_rr(50,0));
                for($i=0;$i<count($zarr);$i++)
                {
                  $newdate=$zarr[$i];
                  echo "<option value='$newdate'>$newdate</option>";
                }
                ?>
              </select>
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-sun"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Brokers</span>
              <span class="info-box-number">
                  <?php
                  echo $results->webClients("broker");
                  ?>
                </span>
              <form method='post' action='../classes/downloadClass.php'><input type='hidden' name='role' value="broker"><button class='reop' name='broker_vap'>Download <i class="fas fa-arrow-circle-right"></i></button></form>

            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-mountain"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total VAP Clients</span>
              <span class="info-box-number">
                  <?php
                  echo $results->webClients("client");
                  ?>
                </span>
              <form method='post' action='../classes/downloadClass.php'><input type='hidden' name='role' value="client"><button class='reop' name='vap'>Download <i class="fas fa-arrow-circle-right"></i></button></form>

            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-spinner"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Switch Claims</span>
              <span class="info-box-number sswi">
                </span>
              <form method='post' action='../classes/downloadClass.php'><input type='hidden' name='client_name' value="--"><input type='hidden' name='month' id="mainmonth" value=""><button class='reop' name='switchdwn'>Download <i class="fas fa-arrow-circle-right"></i></button></form>

            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <h2 class="card-title" style="color: darkblue !important; font-size: 20px !important; padding: 20px !important; font-weight: bolder !important;">Client Savings</h2>
            <table class="table table-striped table-valign-middle">
              <thead>
              <tr>
                <th>Client Name</th>
                <th>Savings</th>
                <th>Actual Savings</th>
                <th>VAT.Ex(15%)</th>
                <th>Base Fee</th>
                <th>Threshold 1</th>
                <th>Threshold 2</th>
                <th>Thres1 Amnt</th>
                <th>Thres2 Amnt</th>
                <th>25%</th>
                <th>30%/33%</th>
                <th>Switch No.</th>
                <th>CHF</th>

              </tr>
              </thead>
              <tbody id="bill_infor">
              <h3 id="lod" align='center' style='color: red'>Loading ....</h3>
              </tbody>
              <tfoot>
              <tr>
                <td colspan="7"></td>
                <td colspan="2"><form method='post' action='../classes/downloadClass.php'><textarea id="myobj" name="myobj" hidden></textarea> <button class="btn btn-secondary btn-sm bg-info" name="billing"><i class="fas fa-download"></i> Download All</button></form></td>
              </tr></tfoot>
            </table>
          </div>
        </div>
      </div>

    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<?php
require_once ("main_footer.php");
?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->

<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="dist/js/demo.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="plugins/raphael/raphael.min.js"></script>
<script src="plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>

<script>
  function getBill()
  {
    $("#lod").show();
    var month=$("#from_month").val();
    var total_switch=0;

    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{
        identityNum:23,
        month:month
      },
      async: false,
      success:function (data) {
        $("#lod").hide();
        let json=JSON.parse(data);
        $("#bill_infor").empty();
        $.each(json, function(key, value){
          var client=json[key].client;
          var savings=json[key].savings;
          var cl=json[key].cl;
          var caret=json[key].caret;
          var threshold1=json[key].threshold1;
          var actualsavings=json[key].actualsavings;
          var vatexcl=json[key].vatexcl;
          var base_fee=json[key].base_fee;
          var variance=json[key].variance;
          var variance1=json[key].variance1;
          var threshold=json[key].threshold;
          var perc25=json[key].perc25;
          var perc30=json[key].perc30;
          var switch_number=json[key].switch_number;
          var chf=json[key].chf;
          var client_id=json[key].client_id;
          total_switch+=switch_number;
          let cina="";
          if(client==="Gaprisk_administrators")
          {
            client="GapRisk";
          }
          else if(client==="Total_risk_administrators")
          {
            client="TotalRisk";
          }
          else if(client==="Cinagi")
          {
            cina="<span title='Download Cinage Claims'><form method='post' action='../classes/downloadClass.php'><input type='hidden' name='month' value='"+month+"'> <button class='reop' name='cinagi_c'>(+)</button></span></form></span>";
          }

          $("#bill_infor").append("<tr><td>"+client+cina+"</td><td>"+savings+"</td><td style='font-weight: bold !important;'>" +
            "<span class='"+cl+"'><form method='post' action='../classes/downloadClass.php'><input type='hidden' name='client_name' value='"+client+"'><input type='hidden' name='month' value='"+month+"'><i class='"+caret+"'></i> <button class='reop' name='reop'>"+actualsavings+"</button></span></form></td><td style='color:lightseagreen !important; font-weight: bold !important;'>"+vatexcl+"</td>"+
            "<td>"+base_fee+"</td><td>"+threshold1+"</td><td>"+threshold+"</td><td>"+variance1+"<td>"+variance+" </td><td>"+perc25+" </td><td>"+perc30+" </td>"+
            "<td><form method='post' action='../classes/downloadClass.php'><input type='hidden' name='client_name' value='"+client+"'><input type='hidden' name='month' value='"+month+"'><button class='reop' name='switchdwn'>"+switch_number+"</button></form></td><td>"+chf+"</td></tr>");

        });


        $("#myobj").val(data);
      },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });
    $(".sswi").text(total_switch);
    $("#mainmonth").val(month);
  }
  $(document).ready(function () {
    getBill();
  });
</script>
</body>
</html>
