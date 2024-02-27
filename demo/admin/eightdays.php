<?php
session_start();
define("access",true);
$title="Claims with 8 days";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
?>
<body class="crm_body_bg">
<?php
require_once("side.php");
?>

<section class="main_content dashboard_part large_header_bg">
<?php
require_once("top_nav.php");
?>

<div class="main_content_iner overly_inner ">
<div class="container-fluid p-0 ">

<div class="row">
<div class="col-4">
<div class="page_title_box d-flex align-items-center justify-content-between">
<div class="page_title_left">
<h3 class="f_s_30 f_w_700 text_white"><?php echo $title;?></h3>

</div>

</div>
</div>


</div>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20 ">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0"><?php echo $title;?></h3>
</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
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
          try {
            date_default_timezone_set('Africa/Johannesburg');
            $holidays=$db->getHolidays();
           
            $date = new DateTime(date("Y-m-d")); // For today/now, don't pass an arg.
            $date->modify("-1 day");
            $xdate= $date->format("Y-m-d");            
              foreach ($db->eightDays($xdate) as $row) {
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
                $days=round($db->getWorkingDays($date_entered,$today,$holidays));
                if($days>7) {                  
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
                  echo "<button title='View Claim' name='btn' class=\"btn ti-arrow-circle-right\"></button></form>";

                  echo "</td>";
                  echo "</tr>";
                }
              }           
          } catch (Exception $re) {
            echo "There is an error : ".$re->getMessage();
          }
          echo "</table>";

          ?>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>
<?php
require_once("footer.php");
?>
<script src="./js/users.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  } );
</script>