<?php
session_start();
define("access",true);
$title="Savings Dashboard";
require_once("top.php");
$db=new reportsClass();
if(!$db->isInternal()){
   header("Location: ../logout.php");
            die();
}
$role=$db->myRole();
$mcausername=$db->loggedAs();
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$val=$role=="admin"?"1":$mcausername;
?>
<link rel="stylesheet" href="./css/icheck-bootstrap.min.css">
<link rel="stylesheet" href="./css/select2.min.css">
<link rel="stylesheet" href="./css/daterangepicker.css">
<style>
  .select2-container{
    z-index: 4000 !important;
  }
  </style>
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
<div class="col-12">
<div class="page_title_box d-flex align-items-center justify-content-between">
<div class="page_title_left">
<h3 class="f_s_30 f_w_700 text_white"><?php echo $title; ?></h3>

</div>

</div>
</div>

</div>

<div class="row ">
<div class="col-lg-12 card_height_100">
<div class="white_card mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Trends</h3>
</div>

<span id="info" style="color:#64c5b1;display: none;">please wait...</span>
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">

<li class="nav-item"><a class="nav-link g1 active" data="monthly" href="#">Monthly</a>
</li>
<li class="nav-item"><button class="btn btn-primary" id="analyse" style="background-color: #64c5b1 !important; border-color: #64c5b1;"><i class="ti-pie-chart"></i> Analyse</button>
</li>

</ul>
</div>
</div>
</div>
<div class="white_card_body" style="height: auto"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
<div id="savings_graph" style="min-height: 365px;"></div>
</div>
</div>
<div class="sales_unit_footer d-flex justify-content-between">
<div class="single_sales">
<p>This Month Claims</p>
<h3 id="claims2">-</h3>
<p class="d-flex align-items-center"> <i class="" id="arrow1"></i> <span class="c_perc"> </span> <i id="up1">-</i></p>
</div>
<div class="single_sales disable_sales">
<p>Last Month Claims</p>
<h3 id="claims1">-</h3>
<p class="d-flex align-items-center"> <i class="" id="arrow2"></i> <span class="c_perc"> </span> <i id="up2">-</i></p>
</div>
</div>
</div>
<?php
if($db->isTopLevel())
{
?>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20 ">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Savings Report</h3>
</div>
<div class="header_more_tool">

</div>
</div>
</div>
<div class="white_card_body QA_section">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
                    <th>Client Name</th>
                    <th>Savings</th>
                    <th>Savings Change</th>
                    <th>Budget Savings Target</th>
                    <th>Variance</th>
                    <th>Weekly Report</th>
                    <th>
                      <span  class="btn btn-tool btn-sm">
                  <input type="number" class="form-control" max="100" min="1" value="12" id="mmnths" title="select number of months">
                  
                </span>
                    </th>
                    <th>
                    <input type="checkbox" id="current" title="include this month?">
                      <span id='mesg'  style='color: red;display: none'>please wait...</span>
                    </th>
                  </tr>
                  </thead>
                  <tbody id="report">
                  

                  </tbody>
                </table>
</div>
</div>
</div>
</div>
</div>
<?php
}
?>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Savings Percentage Trend</h3>
</div>
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">

</ul>
</div>
</div>
</div>
<div class="white_card_body d-flex align-items-center" style="height:auto">

<div class="w-100" style="height:auto"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
<div id="perc_sav" style="min-height: 365px;"></div>
</div>
</div>
</div>
</div>

<div class="col-lg-12">
<div class="white_card card_height_100 mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Claim Specialist Savings Trend</h3>
</div>
</div>
</div>
<div class="white_card_body d-flex align-items-center" style="height:auto">
<div class="w-100" style="height:auto"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
<div id="cs_trend" style="min-height: 365px;"></div>
</div>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Clients Savings Trend</h3>
</div>
</div>
</div>
<div class="white_card_body d-flex align-items-center" style="height:auto">
<div class="w-100" style="height:auto"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
<div id="apex_2" style="min-height: 365px;"></div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="average-modal-sav">
  <div class="modal-dialog modal-lg" style="max-width: 100%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green text-center"> <span id="cclient_name"></span> Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="desc" style="max-width: 100%;">
        loading...
      </div>
    </div>
  </div>
</div>


<div class="modal fade " id="modal-default">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green"> Analyse Savings</h5>
        <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="row">

          <div class="col-sm-6">
            <div class="form-group">

              <div class="input-group">
                <button type="button" class="btn btn-default float-right daterange" id="daterange-btn">
                  <i class="far fa-calendar-alt"></i> Date range picker
                  <i class="fas fa-caret-down"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <input type="hidden" id="dat1">
            <input type="hidden" id="dat2">
            <div class="form-group">
              <span class="text text-blue" id="datetxt"></span>
            </div>
          </div>


        </div>

        <hr>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <div class="icheck-primary d-inline">
                <input type="radio" class="ccval" value="clients" id="radioPrimary1" name="r1" checked>
                <label for="radioPrimary1">
                </label>
              </div>
              <label>Clients</label>
<hr>
              <select class="select2bs4" multiple="multiple" id="clients" data-placeholder="Select a Client"
                      style="width: 100%; margin-bottom: 5px !important;">
                <?php
                foreach ($db->clientsCase($condition,$val) as $row)
                {
                  echo "<option>$row[0]</option>";
                }
                ?>
              </select>

            </div>
          </div>
          <div class="col-sm-6 border-left">
            <div class="form-group">
              <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary2" class="ccval" value="users" name="r1">
                <label for="radioPrimary2">
                </label>
              </div>
              <label>Users</label><hr>
              <select class="select2bs4" multiple="multiple" id="users" data-placeholder="Select a User"
                      style="width: 100%; margin-bottom: 5px !important;">
                <?php
                foreach ($db->usersCase($condition,$val) as $row)
                {
                  echo "<option>$row[0]</option>";
                }
                ?>
              </select>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="chart mychart">

            </div>
          </div></div>
      </div>
      <div class="modal-footer justify-content-between">


      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php
require_once("footer.php");
?>
<script src="./js/select2.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.min.js"></script>
<script src="./js/savings-dash.js"></script>


