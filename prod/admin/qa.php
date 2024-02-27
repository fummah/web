<?php
session_start();
define("access",true);
$title="QA Report";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}

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
<div class="col-lg-12" style="background-color: white;padding: 20px; border-bottom: 1px dashed lightgrey;"> 
<div class="white_card card_height_100 mb_20">      
          <span class="border-right">
          <div class="icheck-danger d-inline">
            <input type="radio" class="cc" value="ready" id="radio1" name="typ" checked>
            <label for="radio1">
            </label>
          </div>
          <label>Ready for QA</label>
            </span>
            <span class="border-right" style="padding-left: 20px">
          <div class="icheck-danger d-inline">
            <input type="radio" class="cc" value="pending" id="radio4" name="typ" >
            <label for="radio4">
            </label>
          </div>
          <label>Pending</label>
            </span>
            <span class="border-right" style="padding-left: 20px">
          <div class="icheck-danger d-inline">
            <input type="radio" class="cc" value="completed" id="radio2" name="typ" >
            <label for="radio2">
            </label>
          </div>
          <label>Completed</label>
            </span>
            <div class="icheck-danger d-inline" style="padding-left: 20px">
              <input type="radio" class="cc" value="all" id="radio3" name="typ" >
              <label for="radio3">
              </label>
            </div>
            <label>All</label>
         

         
<ul class="nav" style="float:right; display: inline;">
<li class="nav-item">      
                  <div class="icheck-success d-inline">
                <input type="checkbox" id="checkboxSuccess1">
                <label for="checkboxSuccess1">Display As A Percentage(%)</label>                       
              </div> 
</li>


</ul>
 </div>
      </div>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20" >
<div class="white_card_header" >
<div class="box_header m-0">
<div class="" style="width:100%; display: inline;">
    <div class="row">
        <div class="col-md-6">
           <div class="form-group">
                <div class="icheck-primary d-inline">
                  <input type="radio" class="ccval" value="clients" id="radioPrimary1" name="r1" checked>
                  <label for="radioPrimary1">
                  </label>
                </div>
                <label>Clients</label><hr>

                <select class="select2bs4" multiple="multiple" id="clients" data-placeholder="Select a client" style="width: 100%;">
                    
                  <?php                
                  foreach ($db->clientsCase($condition,$val) as $row)
                  {
                    echo "<option>$row[0]</option>";
                  }
                  ?>
                </select>
</div>
              </div>
              <div class="col-md-6">
               <div class="form-group">
                <div class="icheck-primary d-inline">
                  <input type="radio" id="radioPrimary2" class="ccval" value="users" name="r1">
                  <label for="radioPrimary2">
                  </label>
                </div>
                <label>Users</label><hr>
                <select class="select2bs4" multiple="multiple" id="users" data-placeholder="Select a User" style="width: 100%;">
                 
                  <?php
                  foreach ($db->usersCase($condition,$val) as $row)
                  {
                    echo "<option>$row[0]</option>";
                  }
                  ?>
                </select>
              </div>
              </div></div>

</div>
<div class="header_more_tool">
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">
<li class="nav-item">


        

                <div class="input-group">
                  <button type="button" class="btn btn-default float-right daterange" id="daterange-btn">
                    <i class="ti-calendar"></i> Select date
                    <i class="ti-angle-down"></i>
                  </button>
             
              </div>
                  <input type="hidden" id="dat1">
              <input type="hidden" id="dat2">
              <div class="form-group">
                <span class="text text-orange" id="datetxt"></span>
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
            <div class="col-sm-12">
              <div class="chart mychart" id="mychart">
              </div>
            </div>
          </div>
          <div class="row">
               <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Passed vs Failed</h3>
              <!-- /.card-tools -->
            </div>
            <div class="card-body">
              <div class="chart mychart1" id="mychart1">

              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
         <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Improvements</h3>
            <!-- card tools -->
            <div class="card-tools">
            </div>
            <!-- /.card-tools -->
          </div>
          <div class="card-body">
            <div class="improvents">

            </div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
          </div>
</div>
</div>
</div>

</div>

</div>
</section>
</body>
<?php
require_once("footer.php");
?>
<script src="./js/select2.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
<script src="./js/qa.js"></script>
