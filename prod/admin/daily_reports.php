<?php
session_start();
define("access",true);
$title="Daily Reports";
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
<div class="white_card_body QA_section" style="padding: 20px 20px 20px !important">
<form action="download_report.php" method="post">
              <table align="center" class="myDiv">
                <tr>
                  <td><b>Select Client : </b><select id="client_id" name="client_id" class="form-control b">

                      <?php                      
                      foreach ($db->getSystemClients() as $row) {
                        ?>
                        <option value="<?php echo $row['reporting_client_id']; ?>"><?php echo $row['client_name']; ?></option>
                        <?php
                      }
                      ?>
                    </select></td>
                </tr>
                <tr>
                  <td><b>Select Date : </b><input type="date" id="dat" name="dat" class="form-control b"></td>

                </tr>
                <tr>

                  <td><button class="btn btn-primary" name="download"><span style="color: white" class="ti-arrow-circle-down"> Download Excel</span></button> </td>

                </tr>
              </table>
            </form>
            <br>
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
