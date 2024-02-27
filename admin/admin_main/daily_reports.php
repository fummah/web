<?php
session_start();
require_once('../dbconn1.php');
$title="Daily Reports";
$conn=connection("mca","MCA_admin");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Daily Reports</title>
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


  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="../js/users.js"></script>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php
  require_once("main_temp.php");
  ?>
    <!-- Main content -->
  <hr>
    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-12">
            <!-- general form elements disabled -->
            <form action="download_report.php" method="post">
              <table align="center" class="myDiv">
                <tr>
                  <td><b>Select Client : </b><select id="client_id" name="client_id" class="form-control b">

                      <?php

                      $sql = 'SELECT DISTINCT client_name,reporting_client_id FROM clients ORDER BY client_name ASC';
                      $r=$conn->query($sql);
                      foreach ($r as $row) {

                        ?>
                        <option value="<?php echo $row['reporting_client_id']; ?>"><?php echo $row['client_name']; ?></option>
                        <?php
                      }
                      ?>
                    </select></td>
                </tr>
                <tr>
                  <td><b>Select Date : </b><input type="date" id="dat" name="dat" class="form-control b"></td>

                </tr>
                <tr>

                  <td><button class="btn btn-info" name="download"><span style="color: white" class="fa fa-download"></span> Download Excel</button> <button class="btn btn-info" name="send"><span style="color: white" class="fa fa-send"></span> Send Now</button></td>

                </tr>
              </table>
            </form>
            <!-- /.card -->
            <!-- general form elements disabled -->

            <!-- /.card -->
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
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>




</body>
</html>
