<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist"  || $_SESSION['level']=="controller") {


}
else
{
  ?>
  <script type="text/javascript">
      location.href = "../../demo/login.html";
  </script>

  <?php
}
$title="Aspen Report";
$role=$_SESSION['level'];
$mcausername=$_SESSION['user_id'];
$condition=$role=="admin"?":username":"username=:username";
$val=$role=="admin"?"1":$mcausername;
include ("../classes/reportsClass.php");
$results=new reportsClass();
$mnarr=[];
for($x=1; $x>=0;$x--){
  $datt= date('Y-m', strtotime(date('Y-m')." -" . $x . " month"));
  array_push($mnarr,$datt);
}

$zarr=array_reverse($mnarr);
$tdate=date("Y-m");
$date=isset($_POST['selbtn'])?$_POST['mydate']:$tdate;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>MCA | Aspen Report</title>

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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="dist/js/jquery-simple-tree-table.js"></script>
  <style>
    .bg-default{
      max-width:100%;
      margin: auto;
      border: 3px solid #73AD21;
    }
    .fir{
      color:#1ba87e;
      font-weight: bolder;
    }
    .sec{
      color: #1c7430;
      font-weight: bolder;
    }
    .cv{
      color:#0e84b5;
      font-weight: bolder;
    }
    .linkButton {
      background: none;
      border: none;
      color: #0066ff;
      text-decoration: underline;
      cursor: pointer;
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
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <form action="" method="POST">
                  <h5>Select Month : <select class="uk-select" id="mydate" name="mydate">
                      <?php
                      echo "<option value='$date'>$date</option>";
                      for($i=0;$i<count($zarr);$i++)
                      {
                        $newdate=$zarr[$i];
                        if($date==$newdate)
                        {
                          continue;
                        }
                        echo "<option value='$newdate'>$newdate</option>";
                      }
                      ?>
                    </select>
                    <button class="btn btn-info" name="selbtn" type="submit">Select</button>
                  </h5>

                </form>
                <a href="#" data-toggle="modal" data-target="#modal-default"></a>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex">

                <table id="basic" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                  <tr>
                    <th  class="text-center">Row Labels</th>
                    <th  class="text-center">Ferinject</th>
                    <th  class="text-center">Venofer</th>
                    <th  class="text-center">Grand Total</th>

                  </tr>
                  </thead>
                  <tbody>
                  <?php

                  $stasarr=$results->getAspenStatus($date);
                  $gge=["Female","Male",""];
                  $ferinject="Ferinject";
                  $venofer="Venofer";
                  $ccount=0;
                  $schemevalfg=0;$schemevalv=0;$totalscheme=0;
                  foreach($stasarr as $xc)
                  {
                    $ccount1=0;
                    $ccount++;
                    $sstus=$xc[0];
                    $stadisplay=strlen($sstus)>1?$sstus:"Pending";

                    $ferinjectval=$results->getAspenValue($sstus,$ferinject,$date);

                    $venoferval=$results->getAspenValue($sstus,$venofer,$date);
                    $total=$ferinjectval+$venoferval;
                    $schemevalfg+=$ferinjectval;$schemevalvg+=$venoferval;$totalschemeg+=$total;
                    echo "<tr data-node-id=\"$ccount\" style=\"color: #0b1338\" class=\"fir\"><td>$stadisplay</td><td>$ferinjectval</td><td>$venoferval</td><td>$total</td></tr>";


                    $arrgender=$results->getAspenGender($sstus,$date);
                    for($xx=0;$xx<3;$xx++)
                    {
                      $ccount2=0;
                      $ccount1++;
                      $iid=$ccount.".".$ccount1;
                      $gender=ucfirst($gge[$xx]);
                      $genderdisplay=strlen($gender)>1?$gender:"Unknown";
                      $gendervalf=$results->getAspenGenderValue($sstus,$ferinject,$gender,$date);
                      $gendervalv=$results->getAspenGenderValue($sstus,$venofer,$gender,$date);
                      $totalgender=$gendervalf+$gendervalv;
                      if($totalgender<1)
                      {
                        continue;
                      }
                      echo "<tr data-node-id=\"$iid\" data-node-pid=\"$ccount\" class=\"sec\"><td>$genderdisplay</td><td>$gendervalf</td><td>$gendervalv</td><td>$totalgender</td></tr>";

                      $schemearr=$results->getAspenScheme($sstus,$gender,$date);
                      foreach($schemearr as $xc2)
                      {
                        $ccount3=0;
                        $ccount2++;
                        $iid1=$ccount.".".$ccount1.".".$ccount2;
                        $scheme_name=$xc2[0];
                        $schemevalf=$results->getAspenSchemeValue($sstus,$ferinject,$gender,$scheme_name,$date);
                        $schemevalv=$results->getAspenSchemeValue($sstus,$venofer,$gender,$scheme_name,$date);
                        $totalscheme=$schemevalf+$schemevalv;
                        echo "<tr data-node-id=\"$iid1\" data-node-pid=\"$iid\"><td>$scheme_name</td><td>$schemevalf</td><td>$schemevalv</td><td>$totalscheme</td></tr>";
                        $icdarr=$results->getAspenIcd10($sstus,$gender,$scheme_name,$date);

                        foreach($icdarr as $xc3)
                        {
                          $icd10=$xc3[0];
                          $ccount3++;
                          $v9=$results->getAspenIcd10Value($sstus,$ferinject,$gender,$scheme_name,$date,$icd10);
                          $v10=$results->getAspenIcd10Value($sstus,$venofer,$gender,$scheme_name,$date,$icd10);
                          $schemevalx=count($v9);
                          $schemevaly=count($v10);
                          $totalschemeicd10=$schemevalx+$schemevaly;
                          $iid2=$ccount.".".$ccount1.".".$ccount2.".".$ccount3;
                          $xx9="<a class=\"\" data-toggle=\"dropdown\" href=\"#\" title='Click here to view claims'>$totalschemeicd10</a>";
                        $xx9.="<div class=\"dropdown-menu dropdown-menu-lg dropdown-menu-right\"><a href=\"#\" class=\"dropdown-item\"><div class=\"media\"><div class=\"media-body\">";
                          foreach($v9 as $xc9)
                          {
                            $cid=$xc9[0];
                            $cnum=$xc9[1];
                            $xx9.="<form action='../view_aspen.php' method='post' /><input type=\"hidden\" name=\"claim_id\" value=\"$cid\"/><input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$cnum\"></form><hr>";

                          }
                          foreach($v10 as $xc10)
                          {
                            $cid=$xc10[0];
                            $cnum=$xc10[1];
                            $xx9.="<form action='../view_aspen.php' method='post' /><input type=\"hidden\" name=\"claim_id\" value=\"$cid\"/><input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$cnum\"></form><hr>";

                          }

                    $xx9.="</div></div></a></div>";
                          echo "<tr style='color: #0f6ecd' data-node-id=\"$iid2\" data-node-pid=\"$iid1\"><td>$icd10</td><td>$schemevalx</td><td>$schemevaly</td><td>$xx9</td></tr>";
                        }
                      }

                    }

                  }
                  echo "<tr class='cv'><th>Grand Total</th><th>$schemevalfg</th><th>$schemevalvg</th><th>$totalschemeg</th></tr>";
                  ?>
                  </tbody>
                </table>
                <script>
                  $('#basic').simpleTreeTable({
                    expander: $('#expander'),
                    collapser: $('#collapser'),
                    store: 'session',
                    storeKey: 'simple-tree-table-basic'
                  });
                  $('#open1').on('click', function() {
                    $('#basic').data('simple-tree-table').openByID("1");
                  });
                  $('#close1').on('click', function() {
                    $('#basic').data('simple-tree-table').closeByID("1");
                  });
                  $('#opened').simpleTreeTable({
                    opened: [1, 1.1]
                  });
                </script>
              </div>
            </div>
            <!-- /.card -->
            <form action="../classes/aspenDownload.php" method="POST">
              <input type="hidden" name="download" value="<?php echo $date; ?>">
            <p align="center">
              <button class="btn btn-info" name="aspen_download" type="submit"><i class="fas fa-arrow-down"></i> Download</button>
            </p>
            </form>
            <!-- /.card -->
          </div>
        </div>


      </div>
    </div>
    <?php

    ?>

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
<script>
  var toggler = document.getElementsByClassName("caret");
  var i;

  for (i = 0; i < toggler.length; i++) {
    toggler[i].addEventListener("click", function() {
      this.parentElement.querySelector(".nested").classList.toggle("active");
      this.classList.toggle("caret-down");
    });
  }
</script>
</body>
</html>
