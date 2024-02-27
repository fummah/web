<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin"  || $_SESSION['level']=="controller") {


}
else
{
  ?>
  <script type="text/javascript">
   location.href = "../../demo/login.html";
  </script>

  <?php
}
$title="Add User";
//$_SESSION['start_db']=true;
require_once('../dbconn1.php');
$conn = connection("mca", "MCA_admin");
$sql = $conn->prepare('select client_name, min(client_id) as client_id from clients group by client_name ORDER BY client_name ASC');
$sql->execute();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Add Users</title>
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
  <script src="../js/jquery-1.12.4.js"></script>
  <script src="../js/users.js"></script>
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
        <div class="col-md-12">
          <!-- general form elements disabled -->
          <div class="card card">
            <div class="card-header">
              <h3 class="card-title">Add New User</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row">
                <div class="col-sm-6">
                  <!-- text input -->
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Surname</label>
                    <input type="text" name="surname" id="surname" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <!-- text input -->
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" id="email" class="form-control">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Role</label>
                    <select class="form-control" name="role" id="role">
                      <option value="claims_specialist">Claims Specialist</option>
                      <option value="gap_cover">Gap Cover</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Client Name</label>
                    <select class="form-control" name="role" id="client_name">
                      <option value="MCA">MCA</option>
                      <?php

                      try {

                      foreach ($sql->fetchAll() as $row)  {

                          ?>
                          <option value="<?php echo $row['client_name']; ?>"><?php echo $row['client_name']; ?></option>
                          <?php
                        }
                      }
                      catch (Exception $r)
                      {
                        echo "There is an error ".$r;
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-sm-1">
                  <!-- text input -->
                  <div class="form-group">
                    <button id="myBtn" class="btn btn-primary">Create User</button>
                  </div>
                </div>
                <div class="col-sm-1">
                  <div class="form-group">

                    <button class="btn btn-danger" id="myClear">Clear</button>
                  </div>
                </div>
              </div>
              <span id="load" style="color: red;display: none">Please wait...</span>
              <span id="details" style="color: red;font-weight: bolder"></span>


            </div>
            <!-- /.card-body -->
          </div>
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
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    bsCustomFileInput.init();
  });
</script>
</body>
</html>
