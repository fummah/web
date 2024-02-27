<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin"  || $_SESSION['level']=="controller") {


}
else
{
  ?>
  <script type="text/javascript">
    location.href = "../login.html"
  </script>

  <?php
}
$title="AAA";
include('../classes/aaaClass.php');
$email = "";
$password = "";
$folder = "";
$imap = "";
$smtp = "";
$cc = "";
$notemail="";
$notpass="";
$conn = connection("mca", "MCA_admin");
$sql = $conn->prepare('SELECT email,password,destination_folder,smtp_server,imap_server,cc,notification_email,notification_password FROM email_configs');
$sql->execute();
$nu = $sql->rowCount();
if ($nu > 0) {

  foreach ($sql->fetchAll() as $row) {
    $email=$row[0];
    $password=$row[1];
    $folder=$row[2];
    $imap=$row[4];
    $smtp=$row[3];
    $cc=$row[5];
 $notemail=$row[6];
    $notpass=$row[7];
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | AAA</title>
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

  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">  <!-- Theme style -->

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="../jquery/jquery.min.js"></script>
  <script src="../bootstrap3/js/bootstrap.min.js"></script>
  <script src="../jquery/jquery.js"></script>
  <script src="../js/users.js"></script>
  <style>
    #mm:hover{

      background-color: #e8f6ff

    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php
  require_once("main_temp.php");
  ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">

          <table style="font-weight: bolder" class="" cellspacing="5" width="100%">

            <tr>
              <td>Email : <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="form-control b"></td>
              <td>Password : <input type="password" id="password" value="<?php echo $password; ?>" class="form-control b"></td>
              <td>Copied Email Address : <input type="text" id="cc" value="<?php echo $cc; ?>" class="form-control b"></td>
            </tr>
            <tr>
              <td>Destination Folder : <input type="text" id="folder" value="<?php echo $folder; ?>" class="form-control"></td>
              <td>Incoming Server : <input type="text" id="smtp" value="<?php echo $smtp; ?>" class="form-control v"></td>
              <td>Outgoing Server : <input type="text" id="imap" value="<?php echo $imap; ?>" class="form-control v"></td>
            </tr>
     <tr>
              <td>Notification Email : <input type="text" id="notemail" value="<?php echo $notemail; ?>" class="form-control b"></td>
              <td>Password : <input type="password" id="notpass" value="<?php echo $notpass; ?>" class="form-control b"></td>
              <td></td>
            </tr>
            <tr>
              <td><button id="saveChanges" class="btn btn-info">Save Changes</button><button id="duplicates" style="float: right" class="btn btn-info">Show Duplicates</button></td>
              <td><span id="load" style="color: red;display: none">Please wait...</span>
                <span id="details" style="color: red;font-weight: bolder"></span></td>

            </tr>
          </table>

          <hr>

            <h4><u>Number of Claims Processed Today</u></h4>
          <div id="dup" style="display: none">
            <?php
            duplicates();
            ?>
            <div id="close1" style="color: red;cursor: pointer" title="Close">&times;</div>
          </div>
          <div id="users" style="font-size: 16px">
            <?php
            aaaUsers();
            echo "<hr>";
            aaaUsers1();

            ?>
          </div>


          <hr>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


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




<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  } );
</script>

</body>
</html>
