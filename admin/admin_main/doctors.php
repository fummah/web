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
$title="Doctors";
include ("../classes/reportsClass.php");
$results=new reportsClass();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>MCA | Doctors</title>

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
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Doctors</span>
                <span class="info-box-number">
                  <?php
                  echo $results->allDoctors();
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">New Doctors</span>
                <span class="info-box-number">
                  <?php
                  echo $results->newDoctors();
                  ?>
                </span>
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
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Doctors give Discount</span>
                <span class="info-box-number">
                  <?php
                  echo $results->withdiscountDoctors();
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Doctors without Discount</span>
                <span class="info-box-number">
                   <?php
                   echo $results->withoutdiscountDoctors();
                   ?>
                </span>
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
              <div class="card-header">
                <h5 class="card-title">Doctors Report</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                      <i class="fas fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                     <p>
                       5<br/>
                       10<br/>
                       20<br/>
                     </p>
                    </div>
                  </div>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-7 border-right">
                    <div class="card-body">
                      <div class="d-flex">
                        <p class="d-flex flex-column">
                          <span class="text-bold text-lg"><?php echo $results->updatedDoctors();?></span>
                          <span>Number of Doctors</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-danger text-bold">
                      <?php
                      echo $results->allDoctors()-$results->updatedDoctors();
                      ?>
                    </span>
                          <span class="text-muted">Other Doctors</span>
                        </p>
                      </div>
                      <!-- /.d-flex -->
                      <div class="position-relative mb-4">
                        <canvas id="visitors-chart" height="150"></canvas>
                      </div>
                      <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Gives Discount
                  </span>
                        <span>
                    <i class="fas fa-square text-gray"></i> No Discount
                  </span>
                      </div>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-5">
                    <p class="text-center">
                      <strong>Total Claims per Doctor</strong>

                    </p>
                    <?php
                    $sum = 0;
                    foreach ($results->topclaimsDoctors() as $row) {
                      $sum += $row[2];
                    }
                    $rcount=0;
                    $arrc=count($results->topclaimsDoctors());
                    foreach($results->topclaimsDoctors() as $row)
                    {
                      $fuul="[".$row[1]."] ";
                      $tile=$row[0];
                      $tot=$row[2];
                      $per=($tot/$sum)*100;
                      $arr=["progress-bar bg-primary","progress-bar bg-success","progress-bar bg-warning","progress-bar bg-danger","progress-bar bg-info"];
                      $clas=$arr[$rcount%5];
                      echo"<div class=\"progress-group\" title='$tile'>".$fuul;
                    echo"<span class=\"float-right\"><b>$tot</b></span>";
                      echo "<div class=\"progress progress-sm\">";
                        echo "<div class=\"$clas\" style=\"width: $per%\"></div>";
                      echo "</div></div>";
                      $rcount++;
                    }
                    ?>
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row" title="Top Doctors with highest Charged Amount" style="cursor: pointer">
                  <?php
                  foreach ($results->chargedamtDoc() as $row)
                  {

                    $name=$row[0];
                    $prac=$row[1];
                    $charged=(double)$row[2];
               $dd=date("Y-m-01");
$d = new DateTime( $dd );
$d->modify( '-1 month' );
$date= $d->format( 'Y-m' );
                    $amnt=(double)$results->previousDoc($prac,$date)[0][1];
                    $subtract=0.0;
                    $cl="text-warning";
                    $caret="fas fa-caret-left";
                    if($charged>$amnt)
                    {
                      $cl="text-success";
                      $caret="fas fa-caret-up";
                      $subtract=$charged-$amnt;
                    }
                    elseif ($charged<$amnt)
                    {
                      $cl="text-danger";
                      $caret="fas fa-caret-down";
                      $subtract=$amnt-$charged;
                    }
                    $subtract=number_format($subtract,2,'.',' ');
                    $charged=number_format($charged,2,'.',' ');
                    echo"<div class=\"col-sm-3 col-6\">";
                    echo"<div class=\"description-block border-right\">";
                      echo"<span class=\"description-percentage $cl\"><i class=\"$caret\"></i> $subtract</span>";
                      echo"<h5 class=\"description-header\">R $charged</h5>";
                      echo"<span class=\"description-text\">[$prac] $name</span>";
                    echo"</div></div>";

                  }
                  ?>

                </div>
                <hr>
                <div class="row" title="Top Doctors with highest Total Amount" style="cursor: pointer">
                  <?php
                  foreach ($results->totdamtDoc() as $row)
                  {

                    $name=$row[0];
                    $prac=$row[1];
                    $charged=(double)$row[2];
              $dd=date("Y-m-01");
$d = new DateTime( $dd );
$d->modify( '-1 month' );
$date= $d->format( 'Y-m' );
                    $amnt=(double)$results->previousDoc($prac,$date)[0][0];
                    $subtract=0.0;
                    $cl="text-warning";
                    $caret="fas fa-caret-left";
                    if($charged>$amnt)
                    {
                      $cl="text-success";
                      $caret="fas fa-caret-up";
                      $subtract=$charged-$amnt;
                    }
                    elseif ($charged<$amnt)
                    {
                      $cl="text-danger";
                      $caret="fas fa-caret-down";
                      $subtract=$amnt-$charged;
                    }
                    $subtract=number_format($subtract,2,'.',' ');
                    $charged=number_format($charged,2,'.',' ');
                    echo"<div class=\"col-sm-3 col-6\">";
                    echo"<div class=\"description-block border-right\">";
                    echo"<span class=\"description-percentage $cl\"><i class=\"$caret\"></i> $subtract</span>";
                    echo"<h5 class=\"description-header\">R $charged</h5>";
                    echo"<span class=\"description-text\">[$prac] $name</span>";
                    echo"</div></div>";

                  }
                  ?>

                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->

        <!-- /.row -->
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

<!-- PAGE SCRIPTS -->

<script src="dist/js/graphs2.js"></script>
</body>
</html>
