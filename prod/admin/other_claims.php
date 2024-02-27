<?php
session_start();
define("access",true);
$title="Claims with 4 days";
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
echo "<form action='download_report.php' method='post'><button title='Download here' name='txt2' class=\"ti-arrow-circle-down\"></button></form>";
          echo "</th>";
          echo "</tr>";
          echo "</thead>";
          try {
 date_default_timezone_set('Africa/Johannesburg');
            $holidays=array("01-01","03-21","04-19","04-27","05-01","06-17","08-09","09-24","12-16","12-25","12-26");
            $today = date('Y-m-d H:i:s');         
         
              foreach ($db->contact4Days() as $row) {
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
                echo "<button title='View Claim' name='btn' class=\"btn ti-arrow-circle-right\"></button></form>";

                echo "</td>";
                echo "</tr>";
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
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  } );
</script>