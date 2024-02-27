<?php
session_start();
define("access",true);
$title="System Documents";
require_once("top.php");
?>
<body class="crm_body_bg">
<?php
require_once("side.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
$units = explode(' ', 'B KB MB GB TB PB');
$SIZE_LIMIT = 5368709120; // 5 GB
$path="/usr/www/users/greenwhc/mca/documents";
//$disk_used = $db->foldersize($path);
$disk_used = $db->dirSize("C:\\wamp64\\www\\demo\\images");
$disk_used =$db->format_size($disk_used);
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
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
    <h1 align="center">Documents Storage</h1>
  <h2 align="center" style="color: #"><u>Disk Space Used:<i style="color:red"><?php echo $disk_used;?></i></u></h2>
              <h3 align="center" style="color: #"><u>Stored in : <i style="color: #3C510C"> <?php echo $path;?> </i> Path</u></h3>
              <hr>

              <h3 align="center">Delete Files on a claims</h3>
                  <p align="center"><b style="color: mediumseagreen;">Enter Claim Number</b><br><input style="width: 40%;text-align: center;" type="text" id="search" class="form-control b"> <br> <button class="btn btn-primary" id="srchBtn"><i class="ti-arrow-circle-right"></i> Search Case</button></p>
                   <br>
                    <p align="center" id="info2"></p>
                    <p align="center" id="info1"></p>
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
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
