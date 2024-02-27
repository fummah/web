<?php
session_start();
define("access",true);
$title="All Brokers";
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
<h3 class="m-0"><?php echo $title;?></h3>

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
                            echo "<option value='$broker'>$broker</option>";
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
                    <th>Broker Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Subscription Rate Amount</th>
                    <th>Date Entered</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Broker Name</th>
                    <th>Email</th>
                    <th>Subscription Rate Amount</th>
                    <th>Scheme Name</th>
                    <th>Date Entered</th>
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
        $('#example').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "ajax_processing.php"
        } );
    } );

    function selectBroker()
    {
        var broker=$("#broker").val();
        $("input").val(broker);
        var table = $('#example').DataTable();
        var table = $('#example').DataTable();
        table.column(2).search(broker).draw();
    }
</script>