<?php
session_start();
define("access",true);
$title="SLA";
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
<br>
<div class="white_card_body QA_section" style="padding: 1px 15px 1px !important">
     <div class="row">
       <div class="col-sm-3">
            <div class="form-group">

              <div class="input-group">
                <button type="button" class="btn btn-default float-right daterange" id="daterange-btn">
                  <i class="far fa-calendar-alt"></i> Date range picker
                  <i class="fas fa-caret-down"></i>
                </button>
              </div>
            </div>
                 <input type="hidden" id="dat1" value="<?php echo date('Y-m-01');?>">
            <input type="hidden" id="dat2">
            <div class="form-group">
              <span class="text text-orange" id="datetxt" value="<?php echo date('Y-m-29');?>"></span>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">              
     
              <select class="select2bs4" multiple="multiple" id="clients" data-placeholder="Select a Client"
                      style="width: 100%;margin-bottom: 10px !important;">
               <option selected>Kaelo</option>
               <option selected>Western</option>
              </select>

            </div>
          </div>
          <div class="col-sm-3 border-left" style="z-index: 3000 !important;">
            <div class="form-group">              
            
              <select class="select2bs4" multiple="multiple" id="users" data-placeholder="Select a User"
                      style="width: 100%; margin-bottom: 10px !important;">
                <?php
                foreach ($db->usersCase($condition,$val) as $row)
                {
                  echo "<option>$row[0]</option>";
                }
                ?>
              </select>
            </div>
          </div>
            <div class="col-sm-3 border-left" style="z-index: 3000 !important;">
            <div class="form-group">              
            <h4>Total Out of SLA : <span class="badge bg-danger" id="os">0</span></h4>
            </div>
          </div>

        </div>
        <hr>
<div class="QA_table">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
  <h4 id='info' style="display:none">Loading...</h4>
<table id="example" class="display" style="width:100%">
                <thead>
                
           <tr><th>Claim Number</th><th>Username</th><th>Client</th><th>Last Date of Action</th><th>Date of Action</th><th>SLA Hours</th><th>Status</th><th>View</th> </tr>                  
               
                </thead>
                <tbody id="claims">

                </tbody>
                <tfoot>
            <tr><th>Claim Number</th><th>Username</th><th>Client</th><th>Last Date of Action</th><th>Date Entered</th><th>Number of Days for SLA</th><th>Status</th><th>View</th> </tr>  
                </tfoot>
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
<script src="./js/select2.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
<script src="./js/sla.js"></script>