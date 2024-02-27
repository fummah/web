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
$title="Notes Report";
include_once "../dbconn1.php";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Notes Report</title>
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
          echo "Client Name";
          echo "</th>";
          echo "<th>";
          echo "Notes";
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
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Notes";
          echo "</th>";
          echo "<th>";

          echo "</th>";
          echo "</tr>";
          echo "</tfoot>";
          try {
            date_default_timezone_set('Africa/Johannesburg');
$dat=date("Y-m-d");
$date="%".$dat."%";
            $conn=connection("mca","MCA_admin");
            $sql = $conn->prepare('SELECT k.claim_id,x.claim_number,z.client_name,intervention_desc,len FROM(SELECT claim_id,intervention_desc,COUNT(*) as Num,len FROM (SELECT claim_id,intervention_desc, LENGTH(intervention_desc) - LENGTH(REPLACE(intervention_desc, \' \', \'\')) + 1 as len FROM intervention WHERE date_entered LIKE :dat) AS a GROUP BY claim_id HAVING Num = 1) as k INNER JOIN claim as x ON k.claim_id=x.claim_id INNER JOIN member as y ON x.member_id=y.member_id INNER JOIN clients as z On y.client_id=z.client_id WHERE len<10');
            $sql->bindParam(':dat', $date, PDO::PARAM_STR);
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
              foreach ($sql->fetchAll() as $row) {
                $record_index=$row[0];
                $claim_number=$row[1];
                $client_name=$row[2];
                $date_reopened=$row[3];
                $date_closed=$row[4];
                $client_id=$row[5];
                if($client_id==1) {
                  if(strlen($date_closed)>10)
                  {
                    $stmts = $conn->prepare('SELECT date_entered FROM `claim_line` WHERE mca_claim_id=:claim_id ORDER BY id DESC LIMIT 1');
                    $stmts->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                    $stmts->execute();
                    $ytd = $stmts->fetchColumn();
                    $date_reopened=$date_reopened>$ytd?$date_reopened:$ytd;
                  }

                }
                //$days=round(getWorkingDaysx($row[4],$today,$holidays));
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
                echo "<form action='../case_detail.php' method='post' />";
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

