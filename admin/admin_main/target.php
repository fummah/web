<?php
session_start();

if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && ($_SESSION['level'] == "admin"  || $_SESSION['level'] == "claims_specialist" || $_SESSION['level']=="controller")) {


}
else
{

  ?>
  <script type="text/javascript">
      location.href = "../../demo/login.html";
  </script>
  <?php
}
//page title
$title="KPI";

//roles config
$vmonth=date("Y-m");
$role=$_SESSION['level'];
$mcausername=$_SESSION['user_id'];
$condition=$role=="admin"?":username":"username=:username";
$val=$role=="admin"?"1":$mcausername;

//include libraries
require_once ("../classes/reportsClass.php");
require_once ("../classes/targetClass.php");

// creating objects
$results=new reportsClass();
$mytarger=new targetClass();

// variables from db
$row=$results->showTarget();

$month=$row[0]["month"];
$savings_target=(double)$row[0]["savings_target"];
$total_users=count($row);

$date_entered=$row[0]["date_entered"];
$entered_by=$row[0]["entered_by"];
$closed_cases_target=$row[0]["closed_cases_target"];
$entered_caes_target=$row[0]["entered_cases_target"];
$rrow=$results->targets($savings_target);
$target_names=["Savings","Closed Cases", "Cases Entered"];
$icons=["fa fa-balance-scale","fas fa-eye", "fas fa-paint-brush"];
$target_text=$mytarger->moneyFormat($savings_target);
$table="<tr><td>$month</td><td>$target_text</td><td>$closed_cases_target</td><td>$entered_caes_target</td><td>$date_entered</td><td>$entered_by</td></tr>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>MCA | KPI</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/ionicons.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body class="hold-transition sidebar-mini layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php
  require_once("main_temp.php");
  $target=$savings_target;
  for ($i=0;$i<count($target_names);$i++)
  {

    if($target_names[$i]=="Closed Cases")
    {
//closed cases
      $weekly_string="SELECT COUNT(*) FROM claim WHERE date_closed > :dat1 AND date_closed < :dat2 AND Open=0";
      $daily_string="SELECT COUNT(*) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $monthly_string="SELECT COUNT(*) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $str="SELECT COUNT(*) as total FROM claim WHERE date_closed like :closed AND username=:user1 AND Open=0";

      $target=$closed_cases_target;
    }
    elseif($target_names[$i]=="Cases Entered")
    {
//entered cases
      $weekly_string="SELECT COUNT(*) FROM claim WHERE date_entered > :dat1 AND date_closed < :dat2 AND Open=0";
      $daily_string="SELECT COUNT(*) FROM claim WHERE date_entered LIKE :dat AND Open=0";
      $monthly_string="SELECT COUNT(*) FROM claim WHERE date_entered LIKE :dat AND Open=0";
      $str="SELECT COUNT(*)  as total FROM claim WHERE date_entered like :closed AND username=:user1 AND Open<>2";
      $target=$entered_caes_target;
    }
    else
    {
      //savings
      $weekly_string="SELECT SUM(savings_scheme+savings_discount) FROM claim WHERE date_closed > :dat1 AND date_closed < :dat2 AND Open=0";
      $daily_string="SELECT SUM(savings_scheme+savings_discount) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $monthly_string="SELECT SUM(savings_scheme+savings_discount) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $str="SELECT SUM(savings_scheme + savings_discount) as total FROM claim WHERE date_closed like :closed AND username=:user1 AND Open<>2";
      $target=$savings_target;
    }
    $daily_target=(double)$results->dailyTarget($daily_string,$condition,$val);
    $weekly_target=(double)$results->weeklyTarget($weekly_string,$condition,$val);
    $monthly_target=(double)$results->monthlyTarget($monthly_string,$condition,$val);
    $single_target=$target;
    $main_target=$target;
    $user_array=$role=="admin"?$results->getSpecialists():array();
    $myarray=array();
    foreach ($user_array as $row1)
    {
      $user=$row1[0];
      $user_monthly_target=(double)$results->monthlyTarget($monthly_string,"username=:username",$user);
      $charged=$results->claimValue($vmonth,"username=:username",$user);
      array_push($myarray,array("username"=>$user,"monthly_target"=>$user_monthly_target,"charged"=>$charged));
    }

    $charged1=$results->claimValue($vmonth,$condition,$val);
    $perc=round(($monthly_target/$charged1)*100);
    $perc=is_nan($perc)?0:$perc;
    $perc=is_infinite($perc)?0:$perc;

    $total_number=count($user_array);
    $target=$role=="admin"?$total_number*$target:$target;
    $adminactions=$role=="admin" && $target_names[$i]=="Savings"?"<span class='text-info' style='cursor: pointer'><span class=\"badge badge-info\"  data-toggle=\"modal\" data-target=\"#modal-default\"><i class=\"fas fa-pen\"></i></span> | $single_target%</span>":"| $single_target";
    $adminactions=$role=="admin" && $target_names[$i]!="Savings"?"<span class='text-info'> | $main_target </span>":$adminactions;
    $mytarger->targetTempltemplete($target_names[$i],$target,$icons[$i],$weekly_target,$daily_target,$monthly_target,$adminactions,$myarray,$main_target,$i,$results,$str,$perc);

  }
  ?>

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

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<div class="modal fade " id="modal-default">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green"> Current Target</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <table id="example" class="table table-striped " cellspacing="0" width="100%">
              <thead class="text-info">
              <tr>
                <td>Month</td>
                <td>Savings Target</td>
                <td>Closed Cases Target</td>
                <td>Entered Cases Target</td>
                <td>Date Entered</td>
                <td>Entered By</td>

              </tr>
              </thead>
              <tbody>
              <?php
              echo $table;
              ?>
              <form method="post" action="download_summary_report.php">
                <input type="hidden" name="kpi">
                <tr>
                  <td></td>
                  <td><button name="savings" class='btn btn-info'><i class="fas fa-download"></i> Download Excel</button></td>
                  <td><button name="closed" class='btn btn-info'><i class="fas fa-download"></i> Download Excel</button></td>
                  <td><button name="entered" class='btn btn-info'><i class="fas fa-download"></i> Download Excel</button></td>
                  <td></td>
                  <td></td>
                </tr>
              </form>
              </tbody>
            </table>
          </div>
        </div>
        <hr>
        <div class="row" style="padding: 7px">
          <div class="col-sm-3">
            <span class="text-bold"> Savings Target : <span class="text-red">*</span></span>
          </div>
          <div class="col-sm-5">
            <input type="text" value="<?php echo $savings_target?>" class="form-control" name="target" id="target"/>
          </div>
          <div class="col-sm-4">

          </div>
        </div>
        <div class="row" style="padding: 7px">
          <div class="col-sm-3">
            <span class="text-bold"> Closed Cases Target : <span class="text-red">*</span></span>
          </div>
          <div class="col-sm-5">
            <input type="text" value="<?php echo $closed_cases_target?>" class="form-control" name="closed_target" id="closed_target"/>
          </div>
          <div class="col-sm-4">

          </div>
        </div>
        <div class="row" style="padding: 7px">
          <div class="col-sm-3">
            <span class="text-bold"> Entered Cases Target : <span class="text-red">*</span></span>
          </div>
          <div class="col-sm-5">
            <input type="text" value="<?php echo $entered_caes_target?>" class="form-control" name="entered_target" id="entered_target"/>
          </div>
          <div class="col-sm-4">

          </div>
        </div>

        <div class="row" style="padding: 7px">
          <div class="col-sm-3">

          </div>
          <div class="col-sm-5">
            <button type="submit" class="btn btn-info" id="save_target"><i class="fas fa-folder"></i> Save Changes</button>
          </div>
          <div class="col-sm-4">

          </div>
        </div>
        <hr>
        <p align="center" id="target_info"> </p>
      </div>
    </div>
    <div class="modal-footer justify-content-between">


    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
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
<script src="dist/js/target.js"></script>
<!-- PAGE SCRIPTS -->
</body>
</html>
