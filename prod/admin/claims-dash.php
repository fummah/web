<?php
session_start();
define("access",true);
$title="Claim Dashboard";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
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
<style type="text/css">
  .select2-container{
    z-index: 4000 !important;
  }
</style>
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
<h3 class="f_s_30 f_w_700 text_white"><?php echo $title; ?></h3>

</div>

</div>
</div>
<div class="col-2">
	<a href="#" class="white_btn3" id="analyse" title="Open claims, click to analyse">
		<i class="ti-shine"></i> <span id="open_claims">-</span></a>
	</div>
	<div class="col-2">
		<a href="#" class="white_btn3 claims_modal" data="average" title="Average Days to close a claim">
			<i class="ti-bar-chart"></i> <span id="average">-</span></a>
	</div>
	<div class="col-2">
		<a href="#" class="white_btn3" title="Closed Claims">
			<i class="ti-anchor"></i> <span id="closed_claims">-</span></a>
	</div>
	<div class="col-2">
		<a href="#" class="white_btn3" title="New Claims">
			<i class="ti-target"></i> <span id="new_claims">-</span></a>
	</div>
</div>

<div class="row ">
<div class="col-lg-8 card_height_100">
<div class="white_card mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Trends</h3>
</div>
 <span id="info" style="color:#64c5b1;">please wait...</span>
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">

<li class="nav-item">
<a class="nav-link g1" data="daily" href="#">Daily</a>
</li>
<li class="nav-item">
<a class="nav-link g1" data="weekly" href="#">Weekly</a>
</li>
<li class="nav-item"><a class="nav-link g1 active" data="monthly" href="#">Monthly</a>
</li>
</ul>
</div>
</div>
</div>
<div class="white_card_body" style="height: 286px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
<div id="mychart">
<canvas id="claims_graph" width="913" height="313" style="display: block; height: 251px; width: 731px;" class="chartjs-render-monitor"></canvas>
</div>
</div>
</div>
<div class="white_card mb_20">
<div class="white_card_body renew_report_card d-flex align-items-center justify-content-between flex-wrap">
<div class="renew_report_left">
<h4 class="f_s_19 f_w_600 color_theme2 mb-0">Download snap data for last month</h4>
<p class="color_gray2 f_s_12 f_w_600">You may change date and download for other months.</p>
</div>
<div class="create_report_btn">
<a href="#"  class="btn_1 mt-1 mb-1 claims_modal" data="snap">Download here</a>
</div>
</div>
</div>
</div>
<div class="col-lg-4 card_height_100 mb_20">
<div class="white_card">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Open Claims</h3>
</div>
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">
<li class="nav-item">
<a class="nav-link g2 active" data="cs" href="#">CS</a>
</li>
<li class="nav-item">
<a class="nav-link g2" data="clients" href="#">Clients</a>
</li>
</ul>
</div>
<div class="header_more_tool">
<div class="dropdown">
<span class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown">
<i class="ti-more-alt"></i>
</span>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
<!--dropdown-->

</div>
</div>
</div>
</div>
</div>
<div class="white_card_body p-0">
<div class="card_container">
<div id="platform_type_dates_donut" style="height: 280px; -webkit-tap-highlight-color: transparent; user-select: none; position: relative;" _echarts_instance_="ec_1691052585151"><div style="position: relative; overflow: hidden; width: 343px; height: 280px; padding: 0px; margin: 0px; border-width: 0px;">
  <canvas data-zr-dom-id="zr_0" width="428" height="350" style="position: absolute; left: 0px; top: 0px; width: 343px; height: 280px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div></div></div>
</div>
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

<div class="col-lg-4">
<div class="white_card card_height_100 mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">PMB Percentage</h3>
</div>
<div class="header_more_tool">
<div class="dropdown">
<span class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown">
<i class="ti-more-alt"></i>
</span>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
</div>
</div>
</div>
</div>
</div>
<div class="white_card_body mt_30" id="pmb">
loading...

</div>
</div>
</div>
<div class="col-lg-8">
<div class="white_card card_height_100 mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Reopened Claims Trend</h3>
</div>
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">

</ul>
</div>
</div>
</div>
<div class="white_card_body d-flex align-items-center" style="height:140px">
<h4 class="f_w_900 f_s_60 mb-0 me-2" id="current_reopened">-</h4>
<div class="w-100" style="height:100px"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
<canvas width="403" id="page_views" height="125" style="display: block; height: 100px; width: 323px;" class="chartjs-render-monitor"></canvas>
</div>
</div>
</div>
</div>

<div class="col-lg-12">
<div class="white_card card_height_100 mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Claims Specialist Trend</h3>
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
<h3 class="m-0">Clients Trend</h3>
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
<div class="col-lg-4">
<div class="white_card card_height_100  mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Top Medical Schemes</h3>
</div>
<div class="header_more_tool">
<div class="dropdown">
<span class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown">
<i class="ti-more-alt"></i>
</span>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

</div>
</div>
</div>
</div>
</div>
<div class="white_card_body">
<div class="table-responsive">
<table class="table bayer_table m-0">
<tbody id="myschemes">
<tr>
<td colspan="2">
loading...
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<div class="col-lg-4">
<div class="white_card card_height_100  mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Top Tariff Codes</h3>
</div>
<div class="header_more_tool">
<div class="dropdown">
<span class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown">
<i class="ti-more-alt"></i>
</span>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
</div>
</div>
</div>
</div>
</div>
<div class="white_card_body" id="mytariff">
loading...
</div>
</div>
</div>
<div class="col-lg-4">
<div class="white_card card_height_100  mb_20">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Top ICD10 Codes</h3>
</div>
<div class="header_more_tool">
<div class="dropdown">
<span class="dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown">
<i class="ti-more-alt"></i>
</span>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
</div>
</div>
</div>
</div>
</div>
<div class="white_card_body" id="myicd10">
loading...
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="col-lg-12">
<div class="white_card card_height_100 mb_20 ">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Productivity View</h3>
</div>
<div class="header_more_tool">

</div>
</div>
</div>
<div class="white_card_body QA_section">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
	   <?php
                    $hhed="<div style='color: #0c85d0;font-weight: bolder' class='row'><div class='col-4 border-right'>Notes</div><div class='col-4  border-right'>Viewed</div><div class='col-4'>Edited</div></div>";
                    ?>
<table class="table table-bordered">
                      <thead>
                      <tr>
                        <th>Users</th>
                        <th  class="text-center">Yesterday</th>
                        <th  class="text-center">Today</th>
                        <th  class="text-center">This Week</th>
                        <th  class="text-center">This Month</th>

                      </tr>
                      <tr>
                        <td></td>
                        <?php
                        for($i=0;$i<4;$i++)
                        {
                          echo "<td>$hhed</td>";
                        }
                        ?>
                      </tr>

                      </thead>
                      <tbody id="mydata">
                     
<tr><td style="color:red" colspan="5"><p align="center">Loading...</p></td></tr>
                      </tbody>
                    </table>

</div>
</div>
</div>
</div>
</div>


<div class="modal fade" id="average-modal">
  <div class="modal-dialog modal-lg" style="max-width: 100%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green text-center"> Download Report</h5>
        <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="../classes/downloadClass.php" method="post">
          <div class="row">
            <div class="col-lg-6">From Month : <select class="form-control mymodal allc" name="from_client" id="from_client">
                <?php
                $zarr=array_reverse($db->day_rr(11,0));
                for($i=0;$i<count($zarr);$i++)
                {
                  $newdate=$zarr[$i];
                  echo "<option value='$newdate'>$newdate</option>";
                }
                ?>
              </select></div>
            <div class="col-lg-6">To Month :
              <select class="form-control mymodal allc" name="to_client" id="to_client">
                <?php

                for($i=0;$i<count($zarr);$i++)
                {
                  $newdate=$zarr[$i];
                  echo "<option value='$newdate'>$newdate</option>";
                }
                ?>
              </select></div>
          </div>
          <hr>
          <span id="info41">Loading...</span><br>
          <p align="center">
            <button class="btn btn-primary" id="myaverage" name="myaverage" type="submit"><i class="ti-angle-double-down"></i> Download</button>
<button class="btn btn-primary" id="mysnap" name="mysnap" type="submit"><i class="ti-angle-double-down"></i> Download Snap</button>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="modal-default">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green"> Analyse Claims</h5>
        <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <span class="border-right">
          <div class="icheck-danger d-inline">
            <input type="radio" class="cc" value="open" id="radio1" name="typ" checked>
            <label for="radio1">
            </label>
          </div>
          <label>Open</label>
            </span>
          <span class="border-right" style="padding-left: 20px">
          <div class="icheck-danger d-inline">
            <input type="radio" class="cc" value="closed" id="radio2" name="typ" >
            <label for="radio2">
            </label>
          </div>
          <label>Closed</label>
            </span>
          <div class="icheck-danger d-inline" style="padding-left: 20px">
            <input type="radio" class="cc" value="all" id="radio3" name="typ" >
            <label for="radio3">
            </label>
          </div>
          <label>All</label>
        </div>

        <hr>
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
              <span class="text text-orange" id="datetxt"></span>
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
                      style="width: 100%;margin-bottom: 10px !important;">
                <?php
                foreach ($db->clientsCase($condition,$val) as $row)
                {
                  echo "<option>$row[0]</option>";
                }
                ?>
              </select>

            </div>
          </div>
          <div class="col-sm-6 border-left" style="z-index: 3000 !important;">
            <div class="form-group">
              <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary2" class="ccval" value="users" name="r1">
                <label for="radioPrimary2">
                </label>
              </div>
              <label>Users</label><hr>
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
<script src="./js/claims-dash.js"></script>
