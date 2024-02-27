<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin" || $_SESSION['level']=="controller") {


}
else
{
  ?>
  <script type="text/javascript">
     location.href = "../../demo/login.html";
  </script>

  <?php
}

require_once('../dbconn1.php');
$conn = connection("mca", "MCA_admin");
$conn1 = connection("mca1", "MCA_admin");
$aar1=[];
$aar2=[];
$title="Brokers Report";
function getAll()
{
  global $conn1;
  $xx="wc-processing";
  $xx1="wc-active";
  $stmt = $conn1->prepare("SELECT ID FROM `wp_posts` WHERE ID NOT IN(SELECT post_id FROM `wp_postmeta` WHERE meta_key=\"_subscription_renewal\" OR meta_key=\"_requires_manual_renewal\") AND (post_status=\"wc-processing\" OR post_status=\"wc-active\")");
  //$stmt->bindParam(':st', $xx, PDO::PARAM_STR);
  //$stmt->bindParam(':st', $xx, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll();
}
function getAll1()
{
  global $conn1;
  $xx="wc-processing";
  $xx1="wc-processing";
  $stmt = $conn1->prepare("SELECT a.post_id FROM `wp_postmeta` as a INNER JOIN wp_posts as b ON a.post_id=b.ID where meta_key=\"_payfast_subscription_token\" AND (post_status=\"wc-processing\" OR post_status=\"wc-active\")");
  //$stmt->bindParam(':st', $xx, PDO::PARAM_STR);
  //$stmt->bindParam(':st', $xx, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll();
}

function getValue($order_id,$val)
{
  global $conn1;
  $stmt = $conn1->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=:post_id AND meta_key=:meta_key");
  $stmt->bindParam(':post_id', $order_id, PDO::PARAM_STR);
  $stmt->bindParam(':meta_key', $val, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->rowCount()>0?$stmt->fetchColumn():"";
}
function getBroker($email)
{
  global $conn;
  $stmt = $conn->prepare("SELECT broker_id FROM web_clients WHERE email=:email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $brokr= $stmt->rowCount()>0?$stmt->fetchColumn():0;

  $stmt = $conn->prepare("SELECT name,surname FROM web_clients WHERE client_id=:client_id");
  $stmt->bindParam(':client_id', $brokr, PDO::PARAM_STR);
  $stmt->execute();
  $myBroker= $stmt->rowCount()>0?$stmt->fetch()[0]." ".$stmt->fetch()[1]:"";
  return $myBroker;

}
function getSubscription($id)
{
  global $conn1;
  $stmt = $conn1->prepare("SELECT order_item_name FROM wp_woocommerce_order_items WHERE order_id=:order_id");
  $stmt->bindParam(':order_id', $id, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->rowCount()>0?$stmt->fetchColumn():"";

}
function getActual($meta_value,$status)
{
  global $conn1;
  $stmt = $conn1->prepare("SELECT post_id FROM wp_postmeta WHERE meta_value=:meta_value AND meta_key=:meta_key");
  $stmt->bindParam(':meta_value', $meta_value, PDO::PARAM_STR);
  $stmt->bindParam(':meta_key', $status, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MCA | Brokers Reports</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">  <!-- Theme style -->

  <script src="../jquery/jquery.min.js"></script>
  <script src="../bootstrap3/js/bootstrap.min.js"></script>
  <script src="../jquery/jquery.js"></script>


</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <?php
  require_once("main_temp.php");
  ?>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">

        <!--/.col (left) -->
        <!-- right column -->

        <div class="col-lg-12">
          <div class="">

            <hr>
            <div>


              <div class="tab-content">
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">

                    <li class="nav-item">
                      <a class="nav-link active" href="#menu1" data-toggle="tab">Brokers Amounts</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#home" data-toggle="tab">Invoices</a>
                    </li>

                  </ul>
                </div>
                <hr>

                <div id="menu1" class="tab-pane active">
                  <?php
                  echo "<table id=\"example1\" align='center' class=\"table table-striped \" cellspacing=\"0\" width=\"100%\">";

                  echo "<thead><tr><th>Full Name</th><th>Email</th><th class='bro'>Broker Name<br><select class='form-control vb'><option>[Select]</option><option>Ginette</option></select></th><th class='sub'>Subscription<select class='form-control xx'><option>[Select]</option><option>Once Off Service Fee</option><option>Monthly Subscription</option><option>Annual Subscription</option></select></th><th>First Date</th><th>Amount(Zar)</th></thead></tr>";
                  foreach (getAll() as $row)
                  {
                    $order_id=$row[0];

                    //$order_id=getValue($post_id,"order_id");
                    $first_name=getValue($order_id,"_billing_first_name");
                    $last_name=getValue($order_id,"_billing_last_name");
                    $email=getValue($order_id,"_billing_email");
                    $broker=getBroker($email);
                    $subscription=getSubscription($order_id);
                    $date=getValue($order_id,"_paid_date");
                    $amount=getValue($order_id,"_order_total");
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
                <div id="home" class="tab-pane fade">

                  <?php

                  echo "<table id=\"example\" align='center' class=\"table table-striped \" cellspacing=\"0\" width=\"100%\">";

                  echo "<thead><tr><th>Full Name</th><th>Email</th><th class='bro'>Broker Name<br><select class='form-control vb'><option>[Select]</option><option>Ginette</option></select></th><th class='sub'>Subscription<select class='form-control xx'><option>[Select]</option><option>Once Off Service Fee</option><option>Monthly Subscription</option><option>Annual Subscription</option></select></th><th>Transaction Date</th><th>Amount(Zar)</th></thead></tr>";
                  foreach (getAll1() as $row)
                  {
                    $order_id=$row[0];


                    $first_name=getValue($order_id,"_billing_first_name");
                    $last_name=getValue($order_id,"_billing_last_name");
                    $email=getValue($order_id,"_billing_email");
                    $broker=getBroker($email);
                    $subscription=getSubscription($order_id);
                    $date=getValue($order_id,"_paid_date");
                    $name=$first_name." ".$last_name;
                    $amount=getValue($order_id,"_order_total");
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
              </div></div>
            <?php
            include('footer.php');
            ?>
          </div>
          <!-- /.container-fluid -->

        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
require_once ("main_footer.php");
?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>

<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  } );

  $(document).ready(function() {
    $('#example1').DataTable();
  } );
</script>


</body>
</html>
