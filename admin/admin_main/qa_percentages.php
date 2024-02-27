<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level']=="controller") {

    $cookie_name = "myMCA";
    $cookie_value = "admin";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 7000), "/"); // 86400 = 1 day
}
else
{
    ?>
    <script type="text/javascript">
       location.href = "../../demo/login.html";
    </script>

    <?php
}
$title="QA Percentages";
$role=$_SESSION['level'];
$mcausername=$_SESSION['user_id'];
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$val=$role=="admin"?"1":$mcausername;
include ("../classes/reportsClass.php");
$results=new reportsClass();
$date= date("Y-m", strtotime("-1 months"));
$today=date("Y-m");
$myopen=$results->openClaims($condition,$val);
$_SESSION["myopen"]=$myopen;

//echo $_SESSION['level'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MCA | QA Percentages</title>
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

            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
          <span class="">
                <hr>
          <div class="row">

            <div class="col-sm-3">
              <div class="form-group">

                <div class="input-group">
                  <button type="button" class="btn btn-default float-right daterange" id="daterange-btn">
                    <i class="far fa-calendar-alt"></i> Select date
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
            <div class="col-sm-3">

            </div>

          </div>
          <hr>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <div class="icheck-primary d-inline">
                  <input type="radio" id="radioPrimary2" class="ccval" value="users" name="r1" checked>
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
            </div>
          </div>
                    </div>
                </div>



                <!-- /.card -->

            </div><!-- /.container-fluid -->


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

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
<script src="dist/js/qa1.js"></script>
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
</script>

</body>
</html>
