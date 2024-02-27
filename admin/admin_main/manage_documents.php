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
require_once('../dbconn1.php');
$title="Manage Documents";
$conn=connection("mca","MCA_admin");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Manage Files</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="../jquery/jquery.min.js"></script>
  <script src="../bootstrap3/js/bootstrap.min.js"></script>
  <script src="../jquery/jquery.js"></script>

  <script>
    $(document).ready(function () {
      $('#cleanUp').click(function () {
        $('#info').text("Please wait...");
        var obj={

          start_date:$('#start').val(),
          end_date:$('#end').val()

        };
        $('#load').show();
        $.ajax({
          url: "../ajaxPhp/deleting.php?identity=11",
          type: "POST",
          data: obj,
          success: function (data) {

            $('#info').html(data);
          },
          error: function (jqXHR, exception) {
            $('#info').html(jqXHR.responseText);
            $('#info').css('color','red');
          }
        });

      });

      $('#srchBtn').click(function () {
        $('#info1').text("Please wait...");
        var obj={

          claim:$('#search').val()

        };

        $.ajax({
          url: "../ajaxPhp/deleting.php?identity=12",
          type: "POST",
          data: obj,
          success: function (data) {

            $('#info1').html(data);
          },
          error: function (jqXHR, exception) {
            $('#info1').html(jqXHR.responseText);
            $('#info1').css('color','red');
          }
        });

      });



    });


    function  deleteAll(claim) {

      $('#info1').text("Please wait...");
      var obj={

        claim:claim

      };

      $.ajax({
        url: "../ajaxPhp/deleting.php?identity=13",
        type: "POST",
        data: obj,
        success: function (data) {

          $('#info1').html(data);
        },
        error: function (jqXHR, exception) {
          $('#info1').html(jqXHR.responseText);
          $('#info1').css('color','red');
        }
      });


    }
    function  deletex(docID) {

      $('#info2').text("Please wait...");
      var obj={

        doc:docID

      };

      $.ajax({
        url: "../ajaxPhp/deleting.php?identity=14",
        type: "POST",
        data: obj,
        success: function (data) {

          $('#info2').html(data);
          if($('#info2').text()=="Deleted")
          {
            $('#info2').html(data);
            $('#info2').css('color','green');
            $('#'+docID).hide();
          }
          else
          {
            $('#info2').html(data);
            $('#info2').css('color','red');
          }
        },
        error: function (jqXHR, exception) {
          $('#info2').html(jqXHR.responseText);
          $('#info2').css('color','red');
        }
      });


    }
  </script>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php
    require_once("main_temp.php");
    ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">

          <!--/.col (left) -->
          <!-- right column -->

          <div class="col-lg-12">

            <h2 align="center"><i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i>Documents<i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i><i class="fa fa-fw fa-book"></i></h2>

            <div class="">
              <h2 align="center" style="color: #"><u><?php require_once('execDoc.php');?></u></h2>
              <h3 align="center" style="color: #"><u>Stored in : <i style="color: #3C510C"> -----/------</i> Path</u></h3>
              <hr>
              <div style="font-weight: bold; border: double; border-color: #; border-radius: 11px">


                <div class="tab-content">
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                      <li class="nav-item">
                        <a class="nav-link active" href="#menu2" data-toggle="tab">Upload Web Clients</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#home" data-toggle="tab">Delete Single Case</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#menu1" data-toggle="tab">Delete Multiple Cases</a>
                      </li>
                    </ul>
                  </div>
                  <hr>
                  <div id="menu2" class="tab-pane active">


                    <iframe src="../PHPExcel/upload_excel.php" scrolling="yes" frameborder="0" width="100%" height="300"></iframe>



                      </li>
                      <li class="nav-item">

                  </div>
                  <div id="home" class="tab-pane fade">



                    <p align="center"><b style="color: mediumseagreen">Enter Claim Number</b><br><input style="width: 40%" type="text" id="search" class="form-control b"> <br> <button class="btn btn-info" id="srchBtn">Search Case</button></p>
                    <p align="center" id="info2"></p>
                    <p align="center" id="info1"></p>




                  </div>
                  <div id="menu1" class="tab-pane fade">

                    <table align="center" width="50%">
                      <caption><h4 style="color: mediumseagreen" align="center">Select the period that you want to clean up</h4></caption>
                      <tr>
                        <td>Start Date : <input type="date" id="start" class="form-control b"></td>
                        <td style="float: right">End Date<input type="date" id="end" class="form-control b"></td>

                      </tr></table>
                    <h4 style="color:#00b3ee" align="center"><b>Note :</b> Only documents of closed cases with more than 90 days will be removed.</h4>
                    <hr>
                    <p align="center"> <button class="btn btn-info" id="cleanUp"><span style="color: red" class="glyphicon glyphicon-trash"></span><b>Clean Up</b><span style="color: red" class="glyphicon glyphicon-trash"></span></button></p>
                    <p align="center" id="info"></p>
                  </div>

                  <hr>
                </div></div>
              <?php
              include('footer.php');
              ?>
            </div>
            <!-- /.container-fluid -->

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
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
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>




</body>
</html>
