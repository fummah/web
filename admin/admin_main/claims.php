<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist"  || $_SESSION['level']=="controller") {

  $cookie_name = "myMCA";
  $cookie_value = "admin";
  setcookie($cookie_name, $cookie_value, time() + (86400 * 7000), "/"); // 86400 = 1 day
}
else
{
  ?>
  <script type="text/javascript">
    location.href = "../../demo/login.html"
  </script>

  <?php
}
$title="Claims Dashboard";
$role=$_SESSION['level'];
$mcausername=$_SESSION['user_id'];
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$val=$role=="admin" || $role=="controller"?"1":$mcausername;
include ("../classes/reportsClass.php");
$results=new reportsClass();
$date= date("Y-m", strtotime("-1 months"));
$today=date("Y-m");
$myopen=$results->openClaims($condition,$val);
$_SESSION["myopen"]=$myopen;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Claims Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">



</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  <?php
  require_once("main_temp.php");
  ?>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>
                <?php
                echo $myopen;
                ?>
              </h3>

              <p>Open Claims</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer"  data-toggle="modal" data-target="#modal-default">Analyse <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>
                <?php
                $tts=$results->closedThisMonth($condition,$val);
                $average=$tts>0?round($results->closedDate($condition,$val)/$tts):0;
                echo (int)$average;
                ?>
                <sup style="font-size: 20px"></sup></h3>

              <p>Average Days to Close Case</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer"  data-toggle="modal" data-target="#average-modal">Analyse <i class="fas fa-arrow-circle-right"></i></a>

          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?php echo $results->closedthisClaims($today,$condition,$val)?></h3>

              <p><?php echo date("F")." Closed Claims";?></p>
            </div>
            <div class="icon">
              <i class="fas fa-eye"></i>
            </div>
            <a href="#" class="small-box-footer">Analyse <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>
                <?php
                echo $results->newClaims($condition,$val);
                ?>
              </h3>

              <p>New Claims</p>
            </div>
            <div class="icon">
              <i class="fas fa-paint-brush"></i>

            </div>
            <a href="#" class="small-box-footer">Analyse <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                Open Claims
              </h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Users</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#sales-chart" data-toggle="tab">Clients</a>
                  </li>
                </ul>
              </div>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content p-0">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane active" id="revenue-chart"
                     style="position: relative; height: 300px;"><p align="center" class="hid">Loading...</p>

                  <canvas id="revenue-chart-canvas" height="300" style="display:none"></canvas>
                  <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>

                </div>
                <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                  <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  <canvas id="sales-chart-canvas" height="300" style="height: 300px;display:none"></canvas>
                </div>
              </div>
            </div><!-- /.card-body -->
          </div>
          <!-- /.card -->

          <!-- DIRECT CHAT -->


          <!-- /.card-header -->
          <!-- STACKED BAR CHART -->
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Clients Trend</h3>

              <!-- card tools -->
              <div class="card-tools">
                <button type="button"
                        class="btn btn-success btn-sm daterange"
                        data-toggle="tooltip"
                        title="Date range">
                  <i class="far fa-calendar-alt"></i>
                </button>
                <button type="button"
                        class="btn btn-success btn-sm"
                        data-card-widget="collapse"
                        data-toggle="tooltip"
                        title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
            <!-- /.card-body -->
          </div>


          <!-- /.card -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

          <!-- Map card -->
          <div class="card bg-gradient-info">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="ion ion-stats-bars mr-1"></i>
                Claims Trend
              </h3>
              <!-- card tools -->
              <div class="card-tools">
                <button type="button"
                        class="btn  btn-sm daterange"
                        data-toggle="tooltip"
                        title="Date range">
                  <i class="far fa-calendar-alt"></i>
                </button>
                <button type="button"
                        class="btn btn btn-sm"
                        data-card-widget="collapse"
                        data-toggle="tooltip"
                        title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <div class="card-body">
              <p align="center" class="hid">Loading...</p>
              <canvas class="chart" id="line-chart" height="260"></canvas>
            </div>
            <!-- /.card-body-->

          </div>
          <!-- /.card -->


          <!-- BAR CHART -->
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Users Trend</h3>

              <!-- card tools -->
              <div class="card-tools">
                <button type="button"
                        class="btn btn-success btn-sm daterange"
                        data-toggle="tooltip"
                        title="Date range">
                  <i class="far fa-calendar-alt"></i>
                </button>
                <button type="button"
                        class="btn btn-success btn-sm"
                        data-card-widget="collapse"
                        data-toggle="tooltip"
                        title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <!-- /.card -->


          <!-- /.card -->
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Productivity View</h5>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">

                  </button>
                  <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <p>
                      Selection here
                    </p>
                  </div>
                </div>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="card-body">
                    <?php
                    $hhed="<div style='color: #0c85d0;font-weight: bolder' class='row'><div class='col-4 border-right'>Notes</div><div class='col-4  border-right'>Viewed</div><div class='col-4'>Edited</div></div>";
                    ?>
                    <table class="table table-bordered">
                      <thead>
                      <tr>
                        <th>Users</th>
                        <th  class="text-center">Yesterday</th>
                        <th  class="text-center">Today</th>
                        <th  class="text-center">This Week</th>
                        <th  class="text-center">This Month</th>

                      </tr>
                      <tr>
                        <td></td>
                        <?php
                        for($i=0;$i<4;$i++)
                        {
                          echo "<td>$hhed</td>";
                        }
                        ?>
                      </tr>

                      </thead>
                      <tbody>
                      <?php

                      $tyt=["badge bg-success","badge bg-danger","badge bg-warning","badge bg-info"];

                      foreach ($results->selectAllusers($condition1,$val) as $names)
                      {
                        $name=$names[0];
                        echo "<tr><td>$name</td>";
                        for($j=0;$j<4;$j++)
                        {
                          $today = date("Y-m-d", strtotime( '-1 days' ) );
                          $results->sql1="SELECT b.claim_id,b.claim_number,b.Open, intervention_id,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";
                          $results->sql2="SELECT DISTINCT a.claim_id,b.claim_number,b.Open,b.date_entered,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";
                          $results->sql3="SELECT claim_id,claim_number,Open,date_entered,username,date_closed,recent_date_time FROM `claim` WHERE `recent_date_time` >= :dat AND username=:username AND Open<>2";
                          $results->sql4="SELECT DISTINCT a.claim_id,a.claim_number,b.Open,b.date_entered,owner,b.date_closed,a.date FROM `logs` as a INNER JOIN claim as b ON a.claim_id=b.claim_id WHERE a.date >= :dat AND a.owner=:username";

                          $totalClaims="SELECT DISTINCT a.claim_id,b.claim_number,b.Open FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";

                          if($j==1) {

                            $today = date("Y-m-d");
                          }
                          elseif ($j==2)
                          {

                            $today = date("Y-m-d", strtotime('monday this week'));
                          }
                          elseif ($j==3)
                          {
                            $today = date("Y-m-01");
                          }
                          else
                          {
                            $results->sql1="SELECT b.claim_id,b.claim_number,b.Open, intervention_id,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered LIKE :dat AND b.username=:username AND b.Open<>2";
                            $results->sql2="SELECT DISTINCT a.claim_id,b.claim_number,b.Open,b.date_entered,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered LIKE :dat AND b.username=:username AND b.Open<>2";
                            $results->sql3="SELECT claim_id,claim_number,Open,date_entered,username,date_closed,recent_date_time FROM `claim` WHERE `recent_date_time` LIKE :dat AND username=:username AND Open<>2";
                            $results->sql4="SELECT DISTINCT a.claim_id,a.claim_number, b.Open,b.date_entered,owner,b.date_closed,a.date FROM `logs` as a INNER JOIN claim as b ON a.claim_id = b.claim_id WHERE `date` LIKE :dat AND a.owner=:username";
                            $totalClaims="SELECT DISTINCT a.claim_id,b.claim_number,b.Open FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered LIKE :dat AND b.username=:username AND b.Open<>2";

                            $today=$today."%";
                          }
                          $d = $results->selectuser($name, $today, $results->sql1);
                          $d2 = $results->selectuser($name, $today, $totalClaims);
                          $d1 = $results->selectuser($name, $today, $results->sql3);
                          $d3 = $results->selectuser($name, $today, $results->sql4);
                          //$mesg="showclaim.php?sql=".$results->sql1."&dat=".$today."&name=".$name;
                          $mesg1="showclaim.php?sql=".$results->sql2."&dat=".$today."&name=".$name;
                          $mesg2="showclaim.php?sql=".$results->sql3."&dat=".$today."&name=".$name;
                          $mesg3="showclaim.php?sql=".$results->sql4."&dat=".$today."&name=".$name;

                          $d = "<span class='$tyt[1]' style='cursor: pointer' onclick=\"window.open('$mesg1','popup','width=800,height=600'); return false;\">$d/$d2</span>";
                          $d1 = "<span class='$tyt[2]' style='cursor: pointer' onclick=\"window.open('$mesg2','popup','width=800,height=600'); return false;\">$d1</span>";
                          $d2 = "<span class='$tyt[3]' style='cursor: pointer' onclick=\"window.open('$mesg3','popup','width=800,height=600'); return false;\">$d2</span>";
                          $hhed = "<div style='color: #0c85d0;' class='row'><div class='col-4 border-right'>$d</div><div class='col-4  border-right'>$d1</div><div class='col-4' style='cursor: pointer' onclick=\"window.open('$mesg3','popup','width=800,height=600'); return false;\">$d3</div></div>";
                          echo "<td>$hhed</td>";

                        }
                        echo "<tr>";
                      }
                      ?>

                      </tbody>
                    </table>

                  </div>
                </div>

                <!-- /.chart-responsive -->
              </div>

              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>


        </div>
      </div>

      <!-- /.card -->

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<div class="modal fade " id="modal-default">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green"> Analyse Claims</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <span class="border-right">
          <div class="icheck-danger d-inline">
            <input type="radio" class="cc" value="open" id="radio1" name="typ" checked>
            <label for="radio1">
            </label>
          </div>
          <label>Open</label>
            </span>
          <span class="border-right" style="padding-left: 20px">
          <div class="icheck-danger d-inline">
            <input type="radio" class="cc" value="closed" id="radio2" name="typ" >
            <label for="radio2">
            </label>
          </div>
          <label>Closed</label>
            </span>
          <div class="icheck-danger d-inline" style="padding-left: 20px">
            <input type="radio" class="cc" value="all" id="radio3" name="typ" >
            <label for="radio3">
            </label>
          </div>
          <label>All</label>
        </div>

        <hr>
        <div class="row">

          <div class="col-sm-6">
            <div class="form-group">

              <div class="input-group">
                <button type="button" class="btn btn-default float-right daterange" id="daterange-btn">
                  <i class="far fa-calendar-alt"></i> Date range picker
                  <i class="fas fa-caret-down"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <input type="hidden" id="dat1">
            <input type="hidden" id="dat2">
            <div class="form-group">
              <span class="text text-orange" id="datetxt"></span>
            </div>
          </div>


        </div>

        <hr>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <div class="icheck-primary d-inline">
                <input type="radio" class="ccval" value="clients" id="radioPrimary1" name="r1" checked>
                <label for="radioPrimary1">
                </label>
              </div>
              <label>Clients</label>

              <select class="select2bs4" multiple="multiple" id="clients" data-placeholder="Select a State"
                      style="width: 100%;">
                <?php
                foreach ($results->clientsCase($condition,$val) as $row)
                {
                  echo "<option>$row[0]</option>";
                }
                ?>
              </select>

            </div>
          </div>
          <div class="col-sm-6 border-left">
            <div class="form-group">
              <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary2" class="ccval" value="users" name="r1">
                <label for="radioPrimary2">
                </label>
              </div>
              <label>Users</label>
              <select class="select2bs4" multiple="multiple" id="users" data-placeholder="Select a State"
                      style="width: 100%;">
                <?php
                foreach ($results->usersCase($condition,$val) as $row)
                {
                  echo "<option>$row[0]</option>";
                }
                ?>
              </select>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="chart mychart">

            </div>
          </div></div>
      </div>
      <div class="modal-footer justify-content-between">


      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.Average modal -->
<div class="modal fade " id="average-modal">
  <div class="modal-dialog modal-lg" style="max-width: 100%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green text-center"> Download Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="../classes/downloadClass.php" method="post">
          <div class="row">
            <div class="col-lg-6">From Month : <select class="form-control allc" name="from_client" id="from_client">
                <?php
                $zarr=array_reverse($results->day_rr(11,0));
                for($i=0;$i<count($zarr);$i++)
                {
                  $newdate=$zarr[$i];
                  echo "<option value='$newdate'>$newdate</option>";
                }
                ?>
              </select></div>
            <div class="col-lg-6">To Month :
              <select class="form-control allc" name="to_client" id="to_client">
                <?php

                for($i=0;$i<count($zarr);$i++)
                {
                  $newdate=$zarr[$i];
                  echo "<option value='$newdate'>$newdate</option>";
                }
                ?>
              </select></div>
          </div>
          <hr>
          <span id="info41"></span><br>
          <p align="center">
            <button class="btn btn-info" name="myaverage" type="submit"><i class="fas fa-arrow-down"></i> Download</button>

          </p>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
require_once ("main_footer.php");
?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<script src="plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="plugins/flot-old/jquery.flot.resize.min.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="plugins/flot-old/jquery.flot.pie.min.js"></script>
<script src="dist/js/graphs.js"></script>
<script>
  $(".allc").change(function () {
    info11();
  });
  function info11() {
    var from_client=$("#from_client").val();
    var to_client=$("#to_client").val();
    $.ajax({
      url:"../ajaxPhp/reports.php",
      type:"GET",
      data:{identityNum:15,from_client:from_client,to_client:to_client},
      async: false,
      success:function (data) {
        $("#info41").html(data);

      },
      error:function (jqXHR, exception) {
        alert(jqXHR.responseText);
      }
    });
  }

  function showClaims()
  {

  }
</script>

</body>
</html>
