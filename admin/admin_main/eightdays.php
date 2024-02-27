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
$title="Claims with 8 days or more";
include_once "../dbconn1.php";

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Claims with 8 days or more</title>
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
          echo "Name And Surname";
          echo "</th>";
             echo "<th>";
          echo "Policy Number";
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
           echo "<th>";
          echo "Days Open";
          echo "</th>";
            echo "<th>";
          echo "Medical Scheme";
          echo "</th>";
          echo "<th>";
          echo "Client";
          echo "</th>";

          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";
          echo "Add Notes";
          echo "</th>";
          echo "<th>";
          echo "View Notes";
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
          echo "Name And Surname";
          echo "</th>";
             echo "<th>";
          echo "Policy Number";
          echo "</th>";
          echo "<th>";
          echo "Claim Number";
          echo "</th>";
           echo "<th>";
          echo "Days Open";
          echo "</th>";
            echo "<th>";
          echo "Medical Scheme";
          echo "</th>";
          echo "<th>";
          echo "Client";
          echo "</th>";
          echo "<th>";
          echo "Username";
          echo "</th>";
          echo "<th>";
          echo "Add Notes";
          echo "</th>";
          echo "<th>";
          echo "View Notes";
          echo "</th>";
          echo "<th>";

          echo "</th>";
          echo "</tr>";
          echo "</tfoot>";
          try {
            date_default_timezone_set('Africa/Johannesburg');
            $holidays=array("01-01","03-21","04-19","04-10","04-13","04-27","05-01","06-16","08-10","09-24","12-16","12-25","12-26");
            $date = new DateTime(date("Y-m-d")); // For today/now, don't pass an arg.
            $date->modify("-1 day");
            $xdate= $date->format("Y-m-d");
            $conn=connection("mca","MCA_admin");
            $sql = $conn->prepare('SELECT a.claim_id,a.claim_number,b.first_name,b.surname,b.medical_scheme, 
       b.policy_number,a.username,c.client_name,a.date_entered FROM claim as a inner join member as b 
           ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE 
           a.date_entered <= :dat AND a.Open=1 AND eightdays<>1 ORDER BY a.claim_id DESC LIMIT 200');
            $sql->bindParam(':dat', $xdate, PDO::PARAM_STR);
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0) {
              foreach ($sql->fetchAll() as $row) {
                $record_index = $row[0];
                $claim_number = $row[1];
                $first_name = $row[2];
                $surname = $row[3];
                $medical_scheme = $row[4];
                $policy_number=$row[5];
                $username = $row[6];
                $client=$row[7];
                $date_entered=$row[8];
                $fullname=$first_name." ".$surname;
                $today=date("Y-m-d H:i:s");
                $days=round(getWorkingDays($date_entered,$today,$holidays));
                if($days>7) {
                  //$days=round(getWorkingDaysx($row[4],$today,$holidays));
                  echo "<tr>";
                  echo "<td>";
                  echo "<input class=\"uk-checkbox\" onclick='updateme(\"$record_index\")' id='x$record_index' type=\"checkbox\">";
                  echo "</td>";
                  echo "<td>";
                  echo $fullname;
                  echo "</td>";
                  echo "<td>";
                  echo "<div uk-tooltip=\"title: $policy_number\">$policy_number</div>";
                  echo "</td>";
                  echo "<td>";
                  echo $claim_number;
                  echo "</td>";
                  echo "<td>";
                  echo $days;
                  echo "</td>";
                  echo "<td>";
                  echo $medical_scheme;
                  echo "</td>";
                  echo "<td>";
                  echo $client;
                  echo "</td>";
                  echo "<td>";
                  echo $username;
                  echo "</td>";
                  echo "<td>";
                  echo"<a href=\"#add_note\" uk-icon=\"icon: plus\" title='Add Note' onclick='openModal(\"$record_index\")' uk-toggle></a>";
                  echo "</td>";
                  echo "<td>";
                  echo"<a href=\"#view_note\" uk-icon=\"icon: list\" title='View Notes' onclick='openModal1(\"$record_index\")' uk-toggle></a>";
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
<div id="add_note" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
    <h2 class="uk-modal-title" align="center">Add Notes</h2>
    <textarea class="uk-textarea" style="width: 100%" id="editnote"></textarea>
    <input type="hidden" id="hid">
    <p class="uk-text-right">
      <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
      <button class="uk-button uk-button-primary" type="button" onclick="updateText()">Save</button>
    </p>
    <span id="resultText"></span>
  </div>
</div>
<div id="view_note" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
    <h2 class="uk-modal-title" align="center">View Notes</h2>
    <div id="myview" style="margin-left:10px"></div>
    <p class="uk-text-right">
      <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>

    </p>

  </div>
</div>
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
    var ref1 = 0;
    if (document.getElementById("x"+claim_id).checked) {
      ref1 = 1;
    }
    var obj = {identity: 54, claim_id: claim_id,ref1:ref1};
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
  function openModal(id)
  {
    $('#editnote').val("");
    $('#resultText').text("");
   $("#hid").val(id);
  }
  function updateText() {
    $('#resultText').show();
    var text= $('#editnote').val();
    var textid=$('#hid').val();
    if(text==""){
      $('#resultText').html("<b style='color: red'>Please write something</b>");
    }
    else {
      $('#resultText').html("<b style='color: red'>Please wait...</b>");
      var obj = {identity: 52, textid: textid, text: text};
      $.ajax({
        url: "../ajaxPhp/deleting.php",
        type: "GET",
        data: obj,
        success: function (data) {
          $('#resultText').html(data)
          var resT= $('#resultText').text();
          if(data.indexOf("Successfully Added!!!")>-1)
          {
            $("#"+textid).text(text);
            $("#"+textid).addClass("uk-alert-success");
          }
        },
        error: function (jqXHR, exception) {
          $('#resultText').html(jqXHR.responseText);
        }
      });
    }
  }
  function openModal1(id) {

      var obj = {identity: 53, id: id};
      $.ajax({
        url: "../ajaxPhp/deleting.php",
        type: "GET",
        data: obj,
        success: function (data) {
          $('#myview').html(data)
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

