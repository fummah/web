<?php
session_start();
define("access",true);
$title="Incentive Model";
require_once("top.php");
$db=new reportsClass();
if(!$db->isInternal()){
   header("Location: ../logout.php");
            die();
}
$cookie_name = "myMCA";
  $cookie_value = "admin";
  setcookie($cookie_name, $cookie_value, time() + (86400 * 7000), "/"); // 86400 = 1 day
?>
<link rel="stylesheet" href="./css/icheck-bootstrap.min.css">
<link rel="stylesheet" href="./css/select2.min.css">
<link rel="stylesheet" href="./css/daterangepicker.css">
<body class="crm_body_bg">
<?php
require_once("side.php");
$role=$db->myRole();
$mcausername=$db->loggedAs();
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$val=$role=="admin"?"1":$mcausername;
$date= date("Y-m", strtotime("-1 months"));
$today=date("Y-m");
$myopen=$db->openClaims($condition,$val);
$_SESSION["myopen"]=$myopen;
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

                <div class="icheck-primary d-inline">
                  <input type="radio" id="radioPrimary2" class="ccval" value="users" name="r1" checked>
                  <label for="radioPrimary2">
                  </label>
                </div>
                <label>Users</label>
                <div class="input-group">
                <select class="select2users form-select" multiple="multiple" id="users" data-placeholder="Select User"
                        style="width: 100%;">
                  <?php
                  foreach ($db->usersCase($condition,$val) as $row)
                  {
                    echo "<option>$row[0]</option>";
                  }
                  ?>
                </select>
              </div>

</div>
<div class="header_more_tool">
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">
<li class="nav-item">

              <div class="form-group">

                <div class="input-group">
                  <button type="button" class="btn btn-default float-right daterange" id="daterange-btn">
                    <i class="ti-calendar"></i> Select Date
                    <i class="ti-angle-down"></i>
                  </button>
                </div>
              </div>
           
            <div class="col-sm-6">
              <input type="hidden" id="dat1">
              <input type="hidden" id="dat2">
              <div class="form-group">
                <span class="text text-orange" id="datetxt"></span>
              </div>
            </div>
          
</li>


</ul>
</div>
</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<hr>
         
            <div class="row">
            <div class="col-sm-12" id="incentive">

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
<script src="./js/select2.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
<script src="./js/incentive.js"></script>