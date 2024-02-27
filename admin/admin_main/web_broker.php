<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin" || $_SESSION['level']=="controller") {


}
else
{
    ?>
    <script type="text/javascript">
        location.href = "../../demo/login.html"
    </script>

    <?php
}

require_once('../dbconn1.php');
$conn = connection("mca", "MCA_admin");
$conn1 = connection("mca1", "MCA_admin");
$aar1=[];
$aar2=[];
$title="Brokers Report";
$sql=$conn->prepare('SELECT DISTINCT client_id,name FROM `web_clients` WHERE role="broker"');
$sql->execute();
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />

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
                <div class="col-sm-3">
                </div>
                <div class="col-sm-3">
                    Select Broker :
                </div>
                <div class="col-sm-2">
                    <select class="form-control" id="broker" onchange="selectBroker()">
                        <option value="">[select broker]</option>
                        <?php
                        foreach($sql->fetchAll() as $row)
                        {
                            $id=$row[0];
                            $broker=$row[1];
                            echo "<option value='$broker'>$broker</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                </div>
            </div>

            <hr>
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
                    <button class="btn btn-info" name="web_clients" type="submit"><i class="fas fa-arrow-down"></i> Download</button>
                </p>
            </form>
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


</body>
</html>
