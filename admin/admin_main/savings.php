<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level']=="controller") {


}
else
{
    ?>
    <script type="text/javascript">
         location.href = "../../demo/login.html";
    </script>

    <?php
}
$title="Savings";
$role=$_SESSION['level'];
$mcausername=$_SESSION['user_id'];
$condition=$role=="admin"?":username":"username=:username";
$val=$role=="admin"?"1":$mcausername;
include ("../classes/reportsClass.php");
$results=new reportsClass();
$dd=date("Y-m-01");
$d = new DateTime( $dd );
$d->modify( '-1 month' );
$date= $d->format( 'Y-m' );
$today=date("Y-m");
$dat="%".$today."%";
$dat1="%".$date."%";
$current_savings=$results->currentSavings($dat,"",$condition,$val);
$last_month_savings=$results->currentSavings($dat1,"",$condition,$val);

$subtract=0;
$cl="text-warning";
$caret="fas fa-caret-left";
if($current_savings>$last_month_savings)
{
    $cl="text-success";
    $caret="fas fa-arrow-up";
    $subtract=$current_savings-$last_month_savings;

}
elseif ($current_savings<$last_month_savings)
{
    $cl="text-danger";
    $caret="fas fa-arrow-down";
    $subtract=$current_savings-$last_month_savings;

}
$tot_amnt=number_format($current_savings,2,'.',',');
$subtract=number_format($subtract,2,'.',',');
//Rercentage here
$subtract1=0;
$perCurrent=$results->calcPerc($dat,"",$condition,$val);
$perLast=$results->calcPerc($dat1,"",$condition,$val);
$cl1="text-warning";
$caret1="fas fa-caret-left";

if($perCurrent>$perLast)
{
    $cl1="text-success";
    $caret1="fas fa-arrow-up";
    $subtract1=$perCurrent-$perLast;

}
elseif ($perCurrent<$perLast)
{
    $cl1="text-danger";
    $caret1="fas fa-arrow-down";
    $subtract1=$perCurrent-$perLast;

}
$subtract1=round($subtract1);
$perCurrent=(int)round($perCurrent);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>MCA | Savings</title>

    <!-- Font Awesome Icons -->
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
    <style>
        .bg-default{
            max-width:100%;
            margin: auto;
            border: 3px solid #73AD21;
        }
    </style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php
    require_once("main_temp.php");
    ?>

    <!-- Sidebar -->


    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Savings Trend</h3>
                                <a href="#" data-toggle="modal" data-target="#modal-default">Analyse</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg"><?php echo "R ".$tot_amnt;?></span>
                                    <span>Current Savings</span>
                                </p>
                                <?php
                                echo"<p class=\"ml-auto d-flex flex-column text-right\">
                    <span class=\"$cl\">
                      <i class=\"$caret\"></i> R $subtract
                    </span>
                    <span class=\"text-muted\">Savings Change</span>
                  </p>";
                                ?>
                            </div>
                            <!-- /.d-flex -->
                            <p align="center" class="hid">Loading...</p>
                            <div class="position-relative mb-4">

                                <canvas id="sales-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Scheme Savings
                  </span>

                                <span>
                    <i class="fas fa-square text-gray"></i> Discount Savings
                  </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->

                    <!-- /.card -->
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Percentage Savings Trend</h3>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <?php
                                echo "  <p class=\"d-flex flex-column\">
                    <span class=\"text-bold text-lg\">$perCurrent %</span>
                    <span>Current Percentage</span>
                  </p>
                  <p class=\"ml-auto d-flex flex-column text-right\">
                    <span class=\"$cl1\">
                      <i class=\"$caret1\"></i> $subtract1 %
                    </span>
                    <span class=\"text-muted\">% change since last month</span>
                  </p>";
                                ?>

                            </div>
                            <!-- /.d-flex -->
                            <p align="center" class="hid">Loading...</p>
                            <div class="position-relative mb-4">
                                <canvas id="visitors-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Scheme Savings (%)
                  </span>

                                <span>
                    <i class="fas fa-square text-gray"></i> Discount Savings (%)
                  </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->


                </div>
                <!-- /.col-md-6 -->
            </div>
            <?php
            if($role=="admin") {


                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Clients Savings</h3>
                                <div class="card-tools">
                <span  class="btn btn-tool btn-sm">
                  <input type="number" max="100" min="1" value="12" id="mmnths" title="select number of months">
                  <input type="checkbox" id="current" title="include this month?">
                </span>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Savings</th>
                                        <th>Savings Change</th>
                                        <th>Budget Savings Target</th>
                                        <th>Variance</th>
                                        <th>Weekly Report</th>
                                        <th>
                                            <span id='mesg'  style='color: red;display: none'>please wait...</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php


                                    foreach ($results->client_savings() as $row) {
                                        $client = $row["client_name"];
                                        $savings = (double)$row["savings"];
                                        $target = (double)$row["target"];
                                        $variance=$savings-$target;
                                        $id = $row["client_id"];

                                        $lastsavings = (double)$results->savingsChange($id, $date);
                                        $subtract = 0.0;
                                        $cl = "text-warning";
                                        $caret = "fas fa-caret-left";
                                        if ($savings > $lastsavings) {
                                            $cl = "text-success";
                                            $caret = "fas fa-arrow-up";
                                            $subtract = $savings - $lastsavings;

                                        } elseif ($savings < $lastsavings) {
                                            $cl = "text-danger";
                                            $caret = "fas fa-arrow-down";
                                            $subtract = $savings - $lastsavings;

                                        }
                                        $savings = number_format($savings, 2, '.', ',');
                                        $target1 = number_format($target, 2, '.', ',');
                                        $variance1 = number_format($variance, 2, '.', ',');
                                        $subtract = number_format($subtract, 2, '.', ',');
                                        echo "<tr><td><img src=\"..\images\Med ClaimAssist Logo_1000px.png\" alt=\"\" class=\"img-circle img-size-32 mr-2\">$client</td>";
                                        echo "<td>R $savings </td><td><span class=\"$cl mr-1\"><i class=\"$caret\"></i>";
                                        echo " $subtract</span></td><td style='background-color:lightyellow'>R $target1 </td><td>R $variance1 </td><td><form method='post' action='download_summary_report.php'><input type='hidden' name='xxc' value='$id'> <button class=\"btn btn-danger\"><i class=\"fas fa-download\"></i></button></form></td><td><button class=\"btn btn-primary btn-sm bg-info toastsDefaultMaroon\" value='$client'><i class=\"fas fa-folder\"></i> View Report</button></td></tr>";

                                    }
                                    if (count($results->client_savings()) < 1) {
                                        echo "<caption><p align='center' class='text-muted'>No Information</p></caption>";
                                    }

                                    ?>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <section class="connectedSortable">
                <div class="row">
                    <!-- Map card -->
                    <div class="col-lg-6">
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

                    </div>
                    <!-- /.card -->


                    <!-- BAR CHART -->
                    <div class="col-lg-6">
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
                    </div>
                    <!-- /.card -->
                    <!-- /.card -->
                </div>
            </section
                <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
    <strong><a href="#">MCA Admin</a>.</strong>

    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.1
    </div>
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<div class="modal fade " id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center text-green"> Analyse Savings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

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
                            <span class="text text-blue" id="datetxt"></span>
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
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="plugins/flot-old/jquery.flot.resize.min.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="plugins/flot-old/jquery.flot.pie.min.js"></script>

<script src="dist/js/demo.js"></script>
<script src="dist/js/pages/dashboard3.js"></script>
<script src="dist/js/graphs1.js"></script>
</body>
</html>
