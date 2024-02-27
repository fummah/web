<?php
session_start();
define("access",true);
$title="PMBS";
require_once("../top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../../logout.php");
            die();
}
?>
<link rel="stylesheet" href="./css/icheck-bootstrap.min.css">
<link rel="stylesheet" href="./css/select2.min.css">
<link rel="stylesheet" href="./css/daterangepicker.css">
<style type="text/css">
  .select2-container{
    z-index: 4000 !important;
  }
</style>
<body class="crm_body_bg">
<?php
$role=$db->myRole();
$mcausername=$db->loggedAs();
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$val=$role=="admin"?"1":$mcausername;         
?>

<section class="main_content dashboard_part large_header_bg">

<div class="main_content_iner overly_inner ">
<div class="container-fluid p-0 ">

<div class="row">
  <div class="col-md-12">
  </div>
</div>
</div>
</div>
</section>
<?php
require_once("../footer.php");
?>
</body>
<script src="./js/select2.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
<script src="./js/claims-dash.js"></script>
