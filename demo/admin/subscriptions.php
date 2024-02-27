<?php
session_start();
define("access",true);
$title="Subscriptions";
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
<div class="white_card_body QA_section" style="padding: 10px 10px 10px !important">
<div class="tab-content">
                <div class="card-tools">
                  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Brokers Amount</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Invoices</button>
  </li>
                  </ul>
                </div>
                <hr>
<div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  <?php
                  echo "<table id=\"example1\" align='center' class=\"table table-striped \" cellspacing=\"0\" width=\"100%\">";

                  echo "<thead><tr><th>Full Name</th><th>Email</th><th class='bro'>Broker Name<br><select class='form-control vb'><option>[Select]</option><option>Ginette</option></select></th><th class='sub'>Subscription<select class='form-control xx'><option>[Select]</option><option>Once Off Service Fee</option><option>Monthly Subscription</option><option>Annual Subscription</option></select></th><th>First Date</th><th>Amount(Zar)</th></thead></tr>";
                  foreach ($db->getAll() as $row)
                  {
                    $order_id=$row[0];

                    //$order_id=getValue($post_id,"order_id");
                    $first_name=$db->getValue($order_id,"_billing_first_name");
                    $last_name=$db->getValue($order_id,"_billing_last_name");
                    $email=$db->getValue($order_id,"_billing_email");
                    $broker=$db->getBroker($email);
                    $subscription=$db->getSubscription($order_id);
                    $date=$db->getValue($order_id,"_paid_date");
                    $amount=$db->getValue($order_id,"_order_total");
                    $name=$first_name." ".$last_name;
                    $arrx=array("name"=>$name,"email"=>$email,"broker"=>$broker,"subscription"=>$subscription,"date"=>$date,"amount"=>$amount);
                    $aar1[] = $arrx;
                    echo "<tr><td>$name</td><td>$email</td><td>$broker</td><td>$subscription</td><td>$date</td><td>$amount</td></tr>";
                    //echo "<tr><td>$first_name</td><td>$order_id</td><td>$order_id</td><td>$order_id</td><td>$order_id --- $order_id</td></tr>";
                  }

                  echo "</table>";
                  $sendobj=json_encode($aar1);
                  ?>
                  <form action='download_summary_report.php' method='post'><textarea name='txt11' id='txt11' hidden><?php echo $sendobj;?></textarea><button title='Download here' name='txt3' class="btn btn-info">Download</button></form>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                  <?php

                  echo "<table id=\"example\" align='center' class=\"table table-striped \" cellspacing=\"0\" width=\"100%\">";

                  echo "<thead><tr><th>Full Name</th><th>Email</th><th class='bro'>Broker Name<br><select class='form-control vb'><option>[Select]</option><option>Ginette</option></select></th><th class='sub'>Subscription<select class='form-control xx'><option>[Select]</option><option>Once Off Service Fee</option><option>Monthly Subscription</option><option>Annual Subscription</option></select></th><th>Transaction Date</th><th>Amount(Zar)</th></thead></tr>";
                  foreach ($db->getAll1() as $row)
                  {
                    $order_id=$row[0];


                    $first_name=$db->getValue($order_id,"_billing_first_name");
                    $last_name=$db->getValue($order_id,"_billing_last_name");
                    $email=$db->getValue($order_id,"_billing_email");
                    $broker=$db->getBroker($email);
                    $subscription=$db->getSubscription($order_id);
                    $date=$db->getValue($order_id,"_paid_date");
                    $name=$first_name." ".$last_name;
                    $amount=$db->getValue($order_id,"_order_total");
                    echo "<tr><td>$name</td><td>$email</td><td>$broker</td><td>$subscription</td><td>$date</td><td>$amount</td></tr>";
                    $arrx=array("name"=>$name,"email"=>$email,"broker"=>$broker,"subscription"=>$subscription,"date"=>$date,"amount"=>$amount);
                    $aar2[] = $arrx;
                  }
                  echo "</table>";
                  $sendobj1=json_encode($aar2);
                  ?>
                  <form action='download_summary_report.php' method='post'><textarea name='txt12' id='txt12' hidden><?php echo $sendobj1;?></textarea><button title='Download here' name='txt4' class="btn btn-info">Download</button></form>
                </div>


                <hr>
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
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  } );

  $(document).ready(function() {
    $('#example1').DataTable();
  } );
</script>