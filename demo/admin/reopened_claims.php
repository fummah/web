<?php
session_start();
define("access",true);
$title="Reopened Claims";
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
<table id="example" class="table table-striped table-valign-middle">
              <thead>
              <tr>
                <th>Claim Number</th>
                <th>Client Name</th>
                <th>Date Reopened</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
                <?php
try {
            date_default_timezone_set('Africa/Johannesburg');

              foreach ($db->reOpenedClaims() as $row) {
                $record_index=$row[0];
                $claim_number=$row[1];
                $client_name=$row[2];
                $date_reopened=$row[3];
                $date_closed=$row[4];
                $client_id=$row[5];
                if(strlen($date_reopened)<3) {
                  if(strlen($date_closed)>10)
                  {
                    $date_reopened=$db->reOpenedClaimExclusive($record_index,$date_reopened);
                  }

                }              
                echo "<tr><td>$claim_number</td><td>$client_name</td><td>$date_reopened</td>";
             
                echo "<td>";
                echo "<form action='../claim_details.php' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                echo "<button title='View Claim' name='btn' class=\"btn ti-angle-double-right\"></button></form>";
                echo "</td>";
                echo "</tr>";
              }     
          
          } catch (Exception $re) {
            echo "There is an error : ".$re->getMessage();
          }
                ?>                  
              </tbody>
          </table>
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