<?php
session_start();
define("access",true);
$title="KPI";
require_once("top.php");
$db=new reportsClass();
if(!$db->isInternal()){
   header("Location: ../logout.php");
            die();
}
?>
<style>
.info-box .info-box-icon {
    border-radius: 0.25rem;
    -ms-flex-align: center;
    align-items: center;
    display: -ms-flexbox;
    display: flex;
    font-size: 1.875rem;
    -ms-flex-pack: center;
    justify-content: center;
    text-align: center;
    width: 70px;
    padding: 10px;
}
    </style>
<body class="crm_body_bg">
<?php
require_once("side.php");
$vmonth=date("Y-m");
$role=$db->myRole();
$mcausername=$db->loggedAs();
$condition=$role=="admin"?":username":"username=:username";
$val=$role=="admin"?"1":$mcausername;
require_once ("../classes/targetClass.php");
$mytarger=new targetClass();
// variables from db
$row=$db->showTarget();

$month=$row[0]["month"];
$savings_target=(double)$row[0]["savings_target"];
$total_users=count($row);

$date_entered=$row[0]["date_entered"];
$entered_by=$row[0]["entered_by"];
$closed_cases_target=$row[0]["closed_cases_target"];
$entered_caes_target=$row[0]["entered_cases_target"];
$rrow=$db->targets($savings_target);
$target_names=["Savings","Closed Cases", "Cases Entered"];
$icons=["ti-star","ti-arrow-circle-right", "ti-brush-alt"];
$target_text=$mytarger->moneyFormat($savings_target);
$table="<tr><td>$month</td><td>$target_text</td><td>$closed_cases_target</td><td>$entered_caes_target</td><td>$date_entered</td><td>$entered_by</td></tr>";
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
<div class="white_card_body QA_section" style="padding: 10px 1px 1px !important">
<?php
  $target=$savings_target;
  for ($i=0;$i<count($target_names);$i++)
  {

    if($target_names[$i]=="Closed Cases")
    {
//closed cases
      $weekly_string="SELECT COUNT(*) FROM claim WHERE date_closed > :dat1 AND date_closed < :dat2 AND Open=0";
      $daily_string="SELECT COUNT(*) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $monthly_string="SELECT COUNT(*) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $str="SELECT COUNT(*) as total FROM claim WHERE date_closed like :closed AND username=:user1 AND Open=0";

      $target=$closed_cases_target;
    }
    elseif($target_names[$i]=="Cases Entered")
    {
//entered cases
      $weekly_string="SELECT COUNT(*) FROM claim WHERE date_entered > :dat1 AND date_closed < :dat2 AND Open=0";
      $daily_string="SELECT COUNT(*) FROM claim WHERE date_entered LIKE :dat AND Open=0";
      $monthly_string="SELECT COUNT(*) FROM claim WHERE date_entered LIKE :dat AND Open=0";
      $str="SELECT COUNT(*)  as total FROM claim WHERE date_entered like :closed AND username=:user1 AND Open<>2";
      $target=$entered_caes_target;
    }
    else
    {
      //savings
      $weekly_string="SELECT SUM(savings_scheme+savings_discount) FROM claim WHERE date_closed > :dat1 AND date_closed < :dat2 AND Open=0";
      $daily_string="SELECT SUM(savings_scheme+savings_discount) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $monthly_string="SELECT SUM(savings_scheme+savings_discount) FROM claim WHERE date_closed LIKE :dat AND Open=0";
      $str="SELECT SUM(savings_scheme + savings_discount) as total FROM claim WHERE date_closed like :closed AND username=:user1 AND Open<>2";
      $target=$savings_target;
    }
    $daily_target=(double)$db->dailyTarget($daily_string,$condition,$val);
    $weekly_target=(double)$db->weeklyTarget($weekly_string,$condition,$val);
    $monthly_target=(double)$db->monthlyTarget($monthly_string,$condition,$val);
    $single_target=$target;
    $main_target=$target;
    $user_array=$role=="admin"?$db->getSpecialists():array();
    $myarray=array();
    foreach ($user_array as $row1)
    {
      $user=$row1[0];
      $user_monthly_target=(double)$db->monthlyTarget($monthly_string,"username=:username",$user);
      $charged=$db->claimValue($vmonth,"username=:username",$user);
      array_push($myarray,array("username"=>$user,"monthly_target"=>$user_monthly_target,"charged"=>$charged));
    }

    $charged1=$db->claimValue($vmonth,$condition,$val);
    $perc=round(($monthly_target/$charged1)*100);
    $perc=is_nan($perc)?0:$perc;
    $perc=is_infinite($perc)?0:$perc;

    $total_number=count($user_array);
    $target=$role=="admin"?$total_number*$target:$target;
    $adminactions=$role=="admin" && $target_names[$i]=="Savings"?"<span class='text-info' style='cursor: pointer'><span class=\"badge bg-info\"  data-toggle=\"modal\" data-target=\"#modal-default\"><i class=\"ti-pencil\"></i></span> | $single_target%</span>":"| $single_target";
    $adminactions=$role=="admin" && $target_names[$i]!="Savings"?"<span class='text-info'> | $main_target </span>":$adminactions;
    $mytarger->targetTempltemplete($target_names[$i],$target,$icons[$i],$weekly_target,$daily_target,$monthly_target,$adminactions,$myarray,$main_target,$i,$db,$str,$perc);

  }
  ?>
</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>
<div class="modal fade " id="modal-default">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center text-green"> Current Target</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <table id="example" class="table table-striped " cellspacing="0" width="100%">
              <thead class="text-info">
              <tr>
                <td>Month</td>
                <td>Savings Target</td>
                <td>Closed Cases Target</td>
                <td>Entered Cases Target</td>
                <td>Date Entered</td>
                <td>Entered By</td>

              </tr>
              </thead>
              <tbody>
              <?php
              echo $table;
              ?>
              <form method="post" action="download_summary_report.php">
                <input type="hidden" name="kpi">
                <tr>
                  <td></td>
                  <td><button name="savings" class='btn btn-info'><i class="ti-arrow-circle-down"></i> Download Excel</button></td>
                  <td><button name="closed" class='btn btn-info'><i class="ti-arrow-circle-down"></i> Download Excel</button></td>
                  <td><button name="entered" class='btn btn-info'><i class="ti-arrow-circle-down"></i> Download Excel</button></td>
                  <td></td>
                  <td></td>
                </tr>
              </form>
              </tbody>
            </table>
          </div>
        </div>
        <hr>
        <div class="row" style="padding: 7px">
          <div class="col-sm-3">
            <span class="text-bold"> Savings Target : <span class="text-red">*</span></span>
          </div>
          <div class="col-sm-5">
            <input type="text" value="<?php echo $savings_target?>" class="form-control" name="target" id="target"/>
          </div>
          <div class="col-sm-4">

          </div>
        </div>
        <div class="row" style="padding: 7px">
          <div class="col-sm-3">
            <span class="text-bold"> Closed Cases Target : <span class="text-red">*</span></span>
          </div>
          <div class="col-sm-5">
            <input type="text" value="<?php echo $closed_cases_target?>" class="form-control" name="closed_target" id="closed_target"/>
          </div>
          <div class="col-sm-4">

          </div>
        </div>
        <div class="row" style="padding: 7px">
          <div class="col-sm-3">
            <span class="text-bold"> Entered Cases Target : <span class="text-red">*</span></span>
          </div>
          <div class="col-sm-5">
            <input type="text" value="<?php echo $entered_caes_target?>" class="form-control" name="entered_target" id="entered_target"/>
          </div>
          <div class="col-sm-4">

          </div>
        </div>

        <div class="row" style="padding: 7px">
          <div class="col-sm-3">

          </div>
          <div class="col-sm-5">
            <button type="submit" class="btn btn-info" id="save_target"><i class="ti-folder"></i> Save Changes</button>
          </div>
          <div class="col-sm-4">

          </div>
        </div>
        <hr>
        <p align="center" id="target_info"> </p>
      </div>
    </div>
    <div class="modal-footer justify-content-between">


    </div>
  </div>
  <!-- /.modal-content -->
</div>
<?php
require_once("footer.php");
?>
