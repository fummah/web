<?php
session_start();
define("access",true);
$title="Billing";
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
$date= date("Y-m", strtotime("-1 months"));
$today=date("Y-m");
?>
  <style>
    .reop {
      background: none;
      border: none;
      color: cadetblue;
      text-decoration: none;
      cursor: pointer;
    }
    .QA_section .QA_table tbody th, .QA_section .QA_table tbody td {
    font-size: 11px !important;   
}
  </style>
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
<div class="col-2" title="Select Month">
<select class="form-control allc" name="from_month" id="from_month" onchange="getBill()">
                <?php
                $zarr=array_reverse($db->day_rr(50,0));
                for($i=0;$i<count($zarr);$i++)
                {
                  $newdate=$zarr[$i];
                  echo "<option value='$newdate'>$newdate</option>";
                }
                ?>
              </select>
	</div>
	<div class="col-2">
       <form method='post' action='../classes/downloadClass.php'><input type='hidden' name='role' value="broker">
        <button class="white_btn3" title="Average Days to close a claim" name='broker_vap'>
          Brokers <span class="badge bg-info">
                  <?php
                  echo $db->webClients("broker");
                  ?>
          </span></button></form>

	</div>
	<div class="col-2">
		        <form method='post' action='../classes/downloadClass.php'><input type='hidden' name='role' value="client">
        <button class="white_btn3" title="Average Days to close a claim" name='vap'>
          VAP Clients <span class="badge bg-info">
                  <?php
                  echo $db->webClients("client");
                  ?>
          </span></button></form>
	</div>
	<div class="col-2">

        <form method='post' action='../classes/downloadClass.php'><input type='hidden' name='client_name' value="--"><input type='hidden' name='month' id="mainmonth" value="">
          <button title="Switch Claims" name='switchdwn' class="white_btn3">
          Switch Claims <span class="badge bg-info">
                 <span class="sswi"></span>
        </span></button></form>
	</div>
</div>
<div class="col-lg-12">
<div class="white_card card_height_100 mb_20 ">
<div class="white_card_header">
<div class="box_header m-0">
<div class="main-title">
<h3 class="m-0">Billing Report</h3>
</div>
<div class="header_more_tool">

</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">
<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<table class="table table-striped table-valign-middle">
              <thead>
              <tr>
                <th>Client Name</th>
                <th>Savings</th>
                <th>Act Savings</th>
                <th>VAT.Ex(15%)</th>
                <th>Base Fee</th>
                <th>Thres1</th>
                <th>Thres2</th>
                <th>Thres1 Amnt</th>
                <th>Thres2 Amnt</th>
                <th>25%</th>
                <th>30%/33%</th>
                <th>Switch No.</th>
                <th>CHF</th>

              </tr>
              </thead>
              <tbody id="bill_infor">
              <h3 class="lod" align='center' style='color: red'>Loading ....</h3>
              </tbody>
              <tfoot>
              <tr>
                <td colspan="7"></td>
                <td colspan="2"><form method='post' action='../classes/downloadClass.php'><textarea id="myobj" name="myobj" hidden></textarea> <button class="btn btn-secondary btn-sm bg-info" name="billing"><i class="ti-arrow-circle-down"></i> Download All</button></form></td>
              </tr></tfoot>
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
<script>
  function getBill()
  {
    $(".lod").show();
    var month=$("#from_month").val();
    var total_switch=0;

    $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:23,
        month:month
      },
      //async: false,
      beforeSend: function() {
         // $("#loader").text("please wait...t");

    },
      success:function (data) {
        $(".lod").hide();
        let json=JSON.parse(data);
        $("#bill_infor").empty();
        $.each(json, function(key, value){
          var client=json[key].client;
          var savings=json[key].savings;
          var cl=json[key].cl;
          var caret=json[key].caret;
          var threshold1=json[key].threshold1;
          var actualsavings=json[key].actualsavings;
          var vatexcl=json[key].vatexcl;
          var base_fee=json[key].base_fee;
          var variance=json[key].variance;
          var variance1=json[key].variance1;
          var threshold=json[key].threshold;
          var perc25=json[key].perc25;
          var perc30=json[key].perc30;
          var switch_number=json[key].switch_number;
          var chf=json[key].chf;
          var client_id=json[key].client_id;
          total_switch+=switch_number;
          let cina="";
          if(client==="Gaprisk_administrators")
          {
            client="GapRisk";
          }
          else if(client==="Total_risk_administrators")
          {
            client="TotalRisk";
          }
          else if(client==="Cinagi")
          {
            cina="<span title='Download Cinage Claims'><form method='post' action='../classes/downloadClass.php'><input type='hidden' name='month' value='"+month+"'> <button class='reop' name='cinagi_c'>(+)</button></span></form></span>";
          }

          $("#bill_infor").append("<tr><td>"+client+cina+"</td><td>"+savings+"</td><td style='font-weight: bold !important;'>" +
            "<span class='"+cl+"'><form method='post' action='../classes/downloadClass.php'><input type='hidden' name='client_name' value='"+client+"'><input type='hidden' name='month' value='"+month+"'><i class='"+caret+"'></i> <button class='reop' name='reop'>"+actualsavings+"</button></span></form></td><td style='color:lightseagreen !important; font-weight: bold !important;'>"+vatexcl+"</td>"+
            "<td>"+base_fee+"</td><td>"+threshold1+"</td><td>"+threshold+"</td><td>"+variance1+"<td>"+variance+" </td><td>"+perc25+" </td><td>"+perc30+" </td>"+
            "<td><form method='post' action='../classes/downloadClass.php'><input type='hidden' name='client_name' value='"+client+"'><input type='hidden' name='month' value='"+month+"'><button class='reop' name='switchdwn'>"+switch_number+"</button></form></td><td>"+chf+"</td></tr>");
$(".sswi").text(total_switch);
        });


        $("#myobj").val(data);
        //$("#loader").text("");
      },
       complete: function() {
      //$("#loader").text("yy");
      },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });
    //$(".sswi").text(total_switch);

    $("#mainmonth").val(month);
  }
  $(document).ready(function () {
    getBill();
  });
</script>
