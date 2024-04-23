<?php
session_start();
define("access",true);
$title="Web Broker Claims";
require_once("top.php");
$db=new reportsClass();
if(!$db->isTopLevel()){
   header("Location: ../logout.php");
            die();
}
?>
<body class="crm_body_bg">
<?php
require_once("side.php");
$aar1=[];
$aar2=[];
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
<select class="form-control allc" name="from_month" id="from_month" onchange="selectBroker()">
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
<div class="header_more_tool">
<div class="float-lg-right float-none sales_renew_btns justify-content-end">
<ul class="nav">
<li class="nav-item">
 <select class="form-control" id="broker" onchange="selectBroker()">
                        <option value="">[select broker]</option>
                        <?php
                        foreach($db->webBrokersList() as $row)
                        {
                            $id=$row[0];
                            $broker=$row[1];
                            echo "<option value='$id'>$broker</option>";
                        }
                        ?>
                    </select>
</li>


</ul>
</div>
</div>
</div>
</div>
<div class="white_card_body QA_section" style="padding: 1px 1px 1px !important">
<div class="QA_table ">

<div id="DataTables_Table_1_wrapper" class="dataTables_wrapper no-footer">
<table id="example" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Claim Number</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date Entered</th>
                    <th>View</th>
                    
                </tr>
                </thead>
                <tbody id="claims">

                </tbody>
                <tfoot>
                <tr>
                <th>First name</th>
                    <th>Last name</th>
                    <th>Claim Number</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date Entered</th>
                    <th>View</th>
                </tr>
                </tfoot>
            </table>
            <form action="../classes/downloadClass.php" method="POST">
                <input type="hidden" name="broker_name" value="">
                <p align="center">
                    <button class="btn btn-primary" name="web_clients" type="submit"><i class="ti-arrow-circle-down"></i> Download</button>
                </p>
            </form>
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
        getClaims();
    } );

    function selectBroker()
    {
        getClaims();  
             
    }

    function getClaims(){
        var table = $('#example').DataTable();
table.destroy();
        let date = $("#from_month").val();
        let broker = $("#broker").val();
        $.ajax({
      url:"../ajax/reports.php",
      type:"GET",
      data:{
        identityNum:45,date:date,broker
          },
      async: true,
      success:function (data) { 
const json = JSON.parse(data);
let txtval ="";
for(let key in json)
{ 
    const claim_id =json[key]["claim_id"];
                    const first_name =json[key]["first_name"];
                    const last_name =json[key]["last_name"];
                    const claim_number =json[key]["claim_number"];
                    const email =json[key]["email"];
                    const status =json[key]["Open"]>0?"<span style='color:red'>Open</span>":"Closed";
                    const date_entered =json[key]["date_entered"];
                    const txt = `<tr><td>${first_name}</td><td>${last_name}</td><td>${claim_number}</td><td>${email}</td><td>${status}</td>
                    <td>${date_entered}</td><td><form action='../case_details.php' method='post' target='_blank'/><input type='hidden' name='claim_id'
                     value='${claim_id}' /><button title='View Claim' name='btn' class='btn ti-angle-double-right'></button></form></td></tr>`;
                    txtval+=txt;
                }
                $("#claims").html(txtval);
                
      },
      complete: function () {
        $('#example').DataTable(); 
     },
      error:function (jqXHR, exception) {
        console.log(jqXHR.responseText);
      }
    });
    
    }
</script>