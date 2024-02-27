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
$title="View Claims with 4 days";
include_once "../dbconn1.php";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | View Claims with 4 days</title>
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
          echo " First Name ";
          echo "</th>";
          echo "<th>";
          echo "Surname";
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Policy Number";
          echo "</th>";
          echo "<th>";
          echo "Date Entered";
          echo "</th>";
 echo "<th>";
          echo "Days Over";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";
          echo "Member Contacted?";
          echo "</th>";
          echo "<th>";
echo "<form action='download_report.php' method='post'><button title='Download here' name='txt2' class=\"fa fa-download\"></button></form>";
          echo "</th>";
          echo "</tr>";
          echo "</thead>";

          echo "<tfoot>";
          echo "<tr>";
          echo "<th>";
          echo " First Name ";
          echo "</th>";
          echo "<th>";
          echo "Surname";
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Policy Number";
          echo "</th>";
          echo "<th>";
          echo "Date Entered";
          echo "</th>";
 echo "<th>";
          echo "Days Over";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";
          echo "Member Contacted?";
          echo "</th>";
          echo "<th>";
          echo "</th>";
          echo "</tr>";
          echo "</tfoot>";
          try {
 date_default_timezone_set('Africa/Johannesburg');
            $holidays=array("01-01","03-21","04-19","04-27","05-01","06-17","08-09","09-24","12-16","12-25","12-26");
            $today = date('Y-m-d H:i:s');
            $conn=connection("mca","MCA_admin");
            $sql = $conn->prepare('SELECT b.first_name,b.surname,a.claim_number,b.policy_number,a.date_entered,a.member_contacted,NOW() as time,TIMESTAMPDIFF(day,a.date_entered,NOW()) as period,a.username,a.claim_id 
FROM claim as a INNER JOIN  member as b ON a.member_id=b.member_id WHERE a.Open=1 GROUP BY a.claim_id having period>=8 AND (a.member_contacted<>1 OR a.member_contacted IS NULL);');
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
              foreach ($sql->fetchAll() as $row) {
                $contacted=$row[5]==1?"Yes":"No";
                $record_index=$row[9];
  $datetime1 = strtotime($row[4]);
                $datetime2 = strtotime($today);
                $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                $days = round($secs / 86400)-4;
                echo "<tr>";
                echo "<td>";
                echo $row[0];
                echo "</td>";
                echo "<td>";
                echo $row[1];
                echo "</td>";
                echo "<td>";
                echo $row[2];
                echo "</td>";
                echo "<td>";
                echo $row[3];
                echo "</td>";
                echo "<td>";
                echo $row[4];
                echo "</td>";
  echo "<td>";
                echo $days;
                echo "</td>";
                echo "<td>";
                echo $row[8];
                echo "</td>";
                echo "<td>";
                echo $contacted;
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

function getWorkingDaysx($startDate,$endDate,$holidays){
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);


        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)

            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        //We subtract the holidays
        foreach($holidays as $holiday){
            $myholiday=date("Y")."-";
            $time_stamp=strtotime($myholiday.$holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }

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

