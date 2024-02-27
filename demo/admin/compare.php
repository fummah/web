<?php
session_start();
define("access",true);
$title="Compare";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../../logout.php");
            die();
}

$date=date("Y-m");

?>
<link rel="stylesheet" href="./css/icheck-bootstrap.min.css">
<link rel="stylesheet" href="./css/select2.min.css">
<link rel="stylesheet" href="./css/daterangepicker.css">

<style type="text/css">
  .graph{
    display:none;
  }
  .select2-container{
    z-index: 4000 !important;
  }
  .footer_part{
display:none;
  }
  .myactive{
    color: darkblue; !important;
    font-weight: bolder !important;
  }
  .google-visualization-tooltip { pointer-events: none; }
  .load-bar {
  position: relative; 
  width: 100%;
  height: 6px;
  background-color: #fdba2c;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
}
.bar:nth-child(1) {
  background-color: #54bc9c;
  animation: loading 3s linear infinite;
}
.bar:nth-child(2) {
  background-color: #84c4dc;
  animation: loading 3s linear 1s infinite;
}
.bar:nth-child(3) {
  background-color: #fdba2c;
  animation: loading 3s linear 2s infinite;
}
@keyframes loading {
    from {left: 0; width: 0;z-index:100;}
    33.3333% {left: 0; width: 100%;z-index: 10;}
    to {left: 0; width: 100%;}
}
.container-fluid{
  width: 98% !important;
}
.btn-list{
  margin-top: 10px;
  padding-bottom: 5px;
  border-bottom: 1px dashed lightgrey;
}
</style>
<body class="crm_body_bg">
<?php
$montharr=$db->actualMonthsArray();
$yeararr=$db->yearGroup();
$role=$db->myRole();
$mcausername=$db->loggedAs();
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$val=$role=="admin"?"1":$mcausername;         
?>
<section class="">
<div class="main_content_iner overly_inner ">
<div class="container-fluid p-0 ">
  <input type="hidden" id="open">
<div class="row bg-info" style="padding-top:10px !important;padding-bottom:10px !important">
  
  <div class="col-md-3" style="margin-left:10px">
  <h3>Compare</h3>   
       CS<div class="icheck-primary d-inline">
                <input type="radio" class="ccval" value="cs" id="radioPrimary3" name="r1" checked>
                <label for="radioPrimary3">
                </label>
              </div>
       Clients<div class="icheck-primary d-inline">
                <input type="radio" class="ccval" value="clients" id="radioPrimary1" name="r1">
                <label for="radioPrimary1">
                </label>
              </div>
              
  </div>
   <div class="col-md-2">
     Year 1 :
              <select class="form-control mymodal allc" name="val1" id="val1">
                <option value="">Select Year</option>
                <?php
  foreach($yeararr as $y) {                
                  $year=$y["claim_date"];
                  echo "<option value='$year'>$year</option>";
                }
                ?>
              </select>
  </div>
  <div class="col-md-2">
     Year 2 :
              <select class="form-control mymodal allc" name="val2" id="val2">
                <option value="">Select Year</option>
                <?php
  foreach($yeararr as $y) {                
                  $year=$y["claim_date"];
                  echo "<option value='$year'>$year</option>";
                }
                ?>
              </select>
  </div>
  <div class="col-md-2" style="background-color: #64c5b1;">
     Month From :
              <select class="form-control mymodal allc" name="from_month" id="from_month">
                <option value="">Select Month</option>
                <?php
                foreach($montharr as $mon) {                
                  $month=$mon["Month"];
                  $val=$mon["Val"];
                  echo "<option value='$val'>$month</option>";
                }
                ?>
              </select>
  </div>
  <div class="col-md-2" style="background-color: #64c5b1;">
     Month To :
              <select class="form-control mymodal allc" name="to_month" id="to_month">
                <option value="">Select Month</option>
                <?php
               foreach ($montharr as $mon) {                
                  $month=$mon["Month"];
                  $val=$mon["Val"];
                  echo "<option value='$val'>$month</option>";
                }
                ?>
              </select>
  </div>
</div>
 <div class="load-bar">
  <div class="bar"></div>
  <div class="bar"></div>
  <div class="bar"></div>
</div>
<hr>

<div class="row">
<div class="col-md-10" style="overflow-y: scroll; height:600px;">
<div class="row">

<div class="col-md-6 graph graph1">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center" style="height:auto">
<div class="w-100 cs" style="height:auto">

</div>
</div>
</div>
</div>
<div class="col-md-6 graph graph1">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center" style="height:auto">
<div class="w-100 clients" style="height:auto">

</div>
</div>
</div>
</div>
</div>
<!-- Graph 2 -->
<div class="row">
<div class="col-md-6 graph graph2">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center 41p1" style="height:auto">

</div>
</div>
</div>
<div class="col-md-6 graph graph2">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center 41p2" style="height:auto">

</div>
</div>
</div>
</div>
<!-- Graph 3 -->
<div class="row">
<div class="col-md-6 graph graph3">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center 42p1" style="height:auto">

</div>
</div>
</div>
<div class="col-md-6 graph graph3">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center 42p2" style="height:auto">

</div>
</div>
</div>
</div>
<!-- Graph 4 -->
<div class="row">
<div class="col-md-12 graph graph4">
<div class="white_card card_height_100 mb_20">
  <label><input type='radio' name='emerg' value='1' checked>Emergency</label> | <label><input type='radio' name='emerg' value='0'>Non-Emergency</label>
<div class="white_card_body d-flex align-items-center 43p1" style="height:auto">

</div>
</div>
</div>

</div>
<!-- Graph 5 -->
<div class="row">
<div class="col-md-6 graph graph5">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center 44p1" style="height:auto">

</div>
</div>
</div>
<div class="col-md-6 graph graph5">
<div class="white_card card_height_100 mb_20">
<div class="white_card_body d-flex align-items-center 44p2" style="height:auto">

</div>
</div>
</div>
</div>
</div>
<div class="col-md-2" style="border-left:1px dashed lightgrey;">
  <div style="">
     Claims<div class="icheck-primary d-inline">
                <input type="radio" class="xcv" value="claims" id="radioX1" name="r3" checked>
                <label for="radioX1">
                </label>
              </div>
       Savings<div class="icheck-primary d-inline">
                <input type="radio" class="xcv" value="savings" id="radioX2" name="r3">
                <label for="radioX2">
                </label><hr/>
              </div>
              <select class="select2bs4" multiple="multiple" id="users"
                      style="width: 100%;margin-bottom: 10px !important;">
                <?php

                foreach ($db->getUser(" IN (0,1)") as $row)
                {
                  echo "<option>$row</option>";
                }

                ?>
              </select>
              <?php 

              ?>
             
<hr/>
              <select class="select2bs4" multiple="multiple" id="clients" 
                      style="width: 100%;margin-bottom: 10px !important;">
                <?php
                foreach ($db->clientsCase($condition,$val) as $row)
                {
                  echo "<option>$row[0]</option>";
                }
                ?>
              </select>
              <div class='btn-list'>
  <button class="btn btn-small btn-danger" id="predictBtn41" disabled>Savings/Claim Value</button>
</div>
<div class='btn-list'>
  <button class="btn btn-small btn-danger" id="predictBtn42" disabled>Weekly</button>
</div>
<div class='btn-list'>
  <button class="btn btn-small btn-danger" id="predictBtn43" disabled>Emergency Scoring</button>
</div>
<div class='btn-list'>
  <button class="btn btn-small btn-danger" id="predictBtn44" disabled>Split Tariff</button>
</div>
<div class='btn-list'>
 <a href="../coding/analysis.php" class="white_btn3" target="_blank" title="Closed Claims">Further Analysis</a>
</div>
  <div id="info" style="margin-left:10px" style="font-size: 10px !important;"></div>

</div>
</div>
</div>

</div>
</div>

</section>
<hr>

<?php
require_once("footer.php");
?>
<script type="text/javascript">
  $(document).ready(function() {
  $('#example').DataTable();
  } );
</script>
</body>
<script src="./js/select2.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
<script src="./js/compare.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


