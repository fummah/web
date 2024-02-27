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
$title="Zero Amounts";
include_once "../dbconn1.php";
include ("../classes/reportsClass.php");
$results=new reportsClass();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Zero Amounts</title>
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

  <script>
    function add(username) {
$("#username").val(username);
      var table = $('#example').DataTable();

      table.search(username).draw();
    }
  </script>
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
      <?php
      $sum = 0;
      foreach ($results->copayments() as $row) {
        $sum += $row[0];
      }
      $rcount=0;
      $arrc=count($results->copayments());
      $arr=["progress-bar bg-primary","progress-bar bg-success","progress-bar bg-warning","progress-bar bg-danger","progress-bar bg-info"];
      foreach($results->copayments() as $row)
      {
        $tot=$row[0];
        $username=$row[1];

        $per=($tot/$sum)*100;
        $clas=$arr[$rcount%5];

        echo"<div class=\"row\"><div class=\"col-md-12\"><div class=\"progress-group\">";
        echo "<div class=\"progress progress-sm\" style='cursor: pointer' onclick='add(\"$username\")'>";
        echo "<div class=\"progress-bar bg-success\" style=\"width: $per%\">
<span style=\"color: #fff\"><b>$username</b> ($tot)</span>
</div>";
        echo "</div></div></div></div>";
        $rcount++;
      }
      ?>
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
          echo " Claim ID ";
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";
          echo "Date Entered";
          echo "</th>";
  echo "<th>";
          echo "Days Over";
          echo "</th>";
          echo "<th>";
echo "<form action='download_report.php' method='post'><input type='hidden' name='username' id='username'><button title='Download here' name='txt1' class=\"fa fa-download\"></button></form>";
          echo "</th>";
          echo "</tr>";
          echo "</thead>";

          echo "<tfoot>";
          echo "<tr>";
          echo "<th>";
          echo " Claim ID ";
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";
          echo "Date Entered";
          echo "</th>";
  echo "<th>";
          echo "Days Over";
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
            $sql = $conn->prepare('SELECT DISTINCT mca_claim_id,claim_number,username,b.date_entered FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId is null AND a.date_entered>"2020-05-28"');
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
              foreach ($sql->fetchAll() as $row) {
                $record_index=$row[0];
  $datetime1 = strtotime($row[3]);
                $datetime2 = strtotime($today);
                $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                $days = round($secs / 86400);
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
                echo $days;
                echo "</td>";
                echo "<td>";
                echo "<form action='../case_detail.php' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                echo "<button title='View Claim' name='btn' style='color: red' class=\"btn fa fa-eye\"></button></form>";

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

