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
$title="Out of SLA";
include_once "../dbconn1.php";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Out of SLA</title>
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
  <link rel="stylesheet" href="../uikit/css/uikit.min.css" />
  <script src="../uikit/js/uikit.min.js"></script>
  <script src="../uikit/js/uikit-icons.min.js"></script>
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
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Client Name";
          echo "</th>";
          echo "<th>";
          echo "Last Attended";
          echo "</th>";
          echo "<th>";
          echo "Date Entered";
          echo "</th>";
          echo "<th>";
          echo "SLA Date";
          echo "</th>";
          echo "<th>";
          echo "Days Over";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";

          echo "</th>";
          echo "</tr>";
          echo "</thead>";

          echo "<tfoot>";
          echo "<tr>";
          echo "<th>";
          echo "</th>";

          echo "<th>";
          echo "Claim Number";
          echo "</th>";
          echo "<th>";
          echo "Client Name";
          echo "</th>";
          echo "<th>";
          echo "Last Attended";
          echo "</th>";
          echo "<th>";
          echo "Date Entered";
          echo "</th>";
          echo "<th>";
          echo "SLA Date";
          echo "</th>";
          echo "<th>";
          echo "Days Over";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";

          echo "</th>";
          echo "</tr>";
          echo "</tfoot>";
          try {
            date_default_timezone_set('Africa/Johannesburg');
            $holidays=array("01-01","03-21","04-19","04-10","04-13","04-27","05-01","06-16","08-10","09-24","12-16","12-25","12-26");
            $conn=connection("mca","MCA_admin");
            $sql = $conn->prepare('SELECT a.claim_id,c.first_name,c.surname,a.claim_number,b.intervention_desc,b.date_entered,a.username,a.date_entered,a.sla_note,d.client_name FROM `claim` as a INNER JOIN intervention as b ON a.sla_note=b.intervention_id INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE a.sla=1');
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
              foreach ($sql->fetchAll() as $row) {


                $record_index = $row[0];
                $first_name = $row[1];
                $surname = $row[2];
                $claim_number = $row[3];
                $intervention_desc = $row[4];
                $date_entered=$row[5];
                $username = $row[6];
                $claim_date=$row[7];
                $client=$row[9];
                $sla_note=$row[8];
                $sql1 = $conn->prepare('SELECT date_entered,intervention_desc FROM intervention WHERE claim_id=:claim_id AND intervention_id<>:id ORDER BY intervention_id DESC LIMIT 1');
                $sql1->bindParam(':id', $sla_note, PDO::PARAM_STR);
                $sql1->bindParam(':claim_id', $record_index, PDO::PARAM_STR);
                $sql1->execute();
                if($sql1->rowCount()>0)
                {
                  $row=$sql1->fetch();
                  $mdate=$row[0];
                  $msg=$row[1];
                }
                else{
                  $mdate=$claim_date;
                  $msg="No Notes";
                }

                $newdate=sumDays($mdate,$holidays,2);
                $days=getWorkingDays($newdate,$date_entered,$holidays)-1;
                $secs=round(($days)*24*60*60);
                if($days>0) {

                  $days = convert_seconds($secs);

                  //$days=round(getWorkingDaysx($row[4],$today,$holidays));
                  echo "<tr>";
                  echo "<td>";
                  echo "<input class=\"uk-checkbox\" onclick='updateme(\"$record_index\")' id='x$record_index' type=\"checkbox\">";
                  echo "</td>";

                  echo "<td>";
                  echo $claim_number;
                  echo "</td>";
                  echo "<td>";
                  echo $client;
                  echo "</td>";
                  echo "<td>";
                  echo "<div uk-tooltip=\"title: $msg\">$mdate</div>";
                  echo "</td>";
                  echo "<td>";
                  echo "<div uk-tooltip=\"title: $intervention_desc\">$date_entered</div>";
                  echo "</td>";
                  echo "<td>";
                  echo $newdate;
                  echo "</td>";
                  echo "<td>";
                  echo "<span class='uk-text-danger'>$days</span>";
                  echo "</td>";
                  echo "<td>";
                  echo $username;
                  echo "</td>";
                  echo "<td>";
                  echo "<form action='../case_detail.php' method='post' />";
                  echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                  echo "<button title='View Claim' name='btn' class=\"btn fa fa-eye\"></button></form>";

                  echo "</td>";
                  echo "</tr>";
                }
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
  function updateme(claim_id) {
    var ref1 = 1;
    if (document.getElementById("x"+claim_id).checked) {
      ref1 = 0;
    }
    var obj = {identity: 41, claim_id: claim_id,ref1:ref1};
    $.ajax({
      url: "../ajaxPhp/deleting.php",
      type: "GET",
      data: obj,
      success: function (data) {
        alert(data);
      },
      error: function (jqXHR, exception) {
        $('#resultText').html(jqXHR.responseText);
      }
    });
  }
</script>

</body>
</html>

<?php
function convert_seconds($seconds)
{
  $dt1 = new DateTime("@0");
  $dt2 = new DateTime("@$seconds");
  return $dt1->diff($dt2)->format('%a days, %h hours, %i minutes');
}
function sumDays($date_entered,$holidays,$days = 0, $format = 'Y-m-d H:i:s') {
  $incrementing = $days > 0;
  $days         = abs($days);
  $actualDate = date('Y-m-d H:i:s', strtotime($date_entered));
  while ($days > 0) {
    $tsDate    = strtotime($actualDate . ' ' . ($incrementing ? '+' : '-') . ' 1 days');
    $actualDate = date('Y-m-d H:i:s', $tsDate);

    if (date('N', $tsDate) < 6) {
      $days--;
    }
    foreach ($holidays as $h)
    {
      $h=date("Y")."-".$h;
      if($h==date('Y-m-d', $tsDate) && date('N', $tsDate) < 6)
      {
        //$actualDate    = date ("Y-m-d H:i:s", strtotime ($actualDate ."+1 days"));
        $days++;
      }
    }
  }

  return date($format, strtotime($actualDate));
}
function getWorkingDays($startDate,$endDate,$holidays){
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

?>

