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
$title="Add Broker";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Add Broker</title>
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
  <script>
    function addRecord() {

      document.getElementById('loadingmsg').style.display = 'block';
      var  obj= {
        id:2,
        first_name : $('#first_name').val(),
        last_name : $('#last_name').val(),
        dob : $('#dob').val(),
        id_num : $('#id_num').val(),
        email : $('#email').val(),
        phone : $('#phone').val(),
        medical_scheme : "---",
        scheme_option : "---",
        aid_id : "---",
        addr_1 : $('#addr_1').val(),
        addr_2 : $('#addr_2').val(),
        myVal : "admin"
      };
      $.ajax({
        url:"../ajaxPhp/web_ajax.php",
        type:"GET",
        data:obj,
        async: false,
        success:function(data) {
          $('#loadingmsg').html(data);
        },
        error:function (Exception) {

        }

      });

      //document.getElementById('myModal').style.display = "block";
      return false;
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
        <div class="col-md-12">
          <!-- general form elements disabled -->
          <div class="card card">
            <div class="card-header">
              <h3 class="card-title">Add New Broker</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form method="get" action="" onsubmit="return addRecord()">
                <div class="container">
                  <div></div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" REQUIRED>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Surname</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" REQUIRED>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>D.O.B</label>
                        <input type="date" name="dob" id="dob" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>ID Number</label>
                        <input type="text" id="id_num" name="id_num" class="form-control">
                      </div>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" id="email" name="email" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" id="phone" name="phone" class="form-control">
                      </div>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Physical Address 1</label>
                        <textarea id="addr_1" class="form-control"></textarea>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Physical Address 2</label>
                        <textarea id="addr_2" class="form-control"></textarea>
                      </div>
                    </div>

                  </div>


                  <div class="row">

                    <div class="col-sm-6"><button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-ok" style="color: white"></span> Submit</button>
                      <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-remove" style="color: white"></span> Clear</button></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                      <span id='loadingmsg' style='display: none;'>please wait...</span>
                    </div>

                  </div>

                </div>
              </form>
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
