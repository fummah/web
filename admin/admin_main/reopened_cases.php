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
$title="Reopened Cases";
include_once "../dbconn1.php";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Reopened Cases</title>
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


        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-12">
          <!-- general form elements disabled -->
          <?php

          echo "<table id=\"example\" class=\"table table-striped \" cellspacing=\"0\" width=\"100%\">";
          echo "<thead>";
          echo "<tr>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Date Reopened";
          echo "</th>";
          echo "<th>";
          echo "Date Closed";
          echo "</th>";
          echo "<th>";
          echo "Last Savings";
          echo "</th>";
          echo "<th>";
          echo "Reason";
          echo "</th>";
          echo "<th>";

          echo "</th>";
          echo "</tr>";
          echo "</thead>";

          echo "<tfoot>";
          echo "<tr>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Client Name";
          echo "</th>";
          echo "<th>";
          echo "Date Reopened";
          echo "</th>";
          echo "<th>";
          echo "Date Closed";
          echo "</th>";
          echo "<th>";
          echo "Last Savings";
          echo "</th>";
          echo "<th>";
          echo "Reason";
          echo "</th>";
          echo "<th>";
          echo "</th>";
          echo "</tr>";
          echo "</tfoot>";
          try {
            date_default_timezone_set('Africa/Johannesburg');

            $conn=connection("mca","MCA_admin");
            $sql = $conn->prepare('SELECT a.claim_id,a.claim_number,c.client_name,k.reopened_date,k.reason,k.date_closed,b.client_id,k.last_scheme_savings+k.last_discount_savings as last_savings FROM reopened_claims as k INNER JOIN claim as a ON k.claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.Open=1');
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
              foreach ($sql->fetchAll() as $row) {
                $record_index=$row["claim_id"];
                $claim_number=$row["claim_number"];
                $client_name=$row["client_name"];
                $date_reopened=$row["reopened_date"];
                $date_closed=$row["date_closed"];
                $client_id=$row["client_id"];
                $last_savings=$row["last_savings"];
                $reason=$row["reason"];
                echo "<tr>";
                echo "<td>";
                echo $claim_number;
                echo "</td>";
                echo "<td>";
                echo $client_name;
                echo "</td>";
                echo "<td>";
                echo $date_reopened;
                echo "</td>";
                echo "<td>";
                echo $date_closed;
                echo "</td>";
                echo "<td>";
                echo $last_savings;
                echo "</td>";
                echo "<td>";
                echo $reason;
                echo "</td>";
                echo "<td>";
                echo "<form action='../../demo/case_details.php' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                echo "<button title='View Claim' name='btn' class=\"btn fa fa-eye\"></button></form>";

                echo "</td>";
                echo "</tr>";
              }
            }
          } catch (Exception $re) {
            echo "There is an error : ".$re->getMessage();
          }
          echo "</table>";

          ?>
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

