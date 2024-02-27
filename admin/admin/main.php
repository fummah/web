<?php
session_start();

require_once('validateAdmin.php');
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">

    <title>View Users</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery-1.12.4.js"></script>

    <link rel="stylesheet" href="../bootstrap3/css/bootstrap.min.css">
    <script src="../jquery/jquery.min.js"></script>
    <script src="../bootstrap3/js/bootstrap.min.js"></script>
    <script src="../js/users.js"></script>
    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
 <link href="../w3/w3.css" rel="stylesheet" />
    <style type="text/css">


    </style>

</head>
<body>
<?php
include('viewUsers.php');
?>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="../index.php" style="color:#00ffff;">Med Claim Assist</a>
        </div>

      <?php
        require_once ('admin_header.php');
        ?>
            <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
<br>
            <div class="row">
                <div class="col-lg-10">
                    <h1 class="page-header myDiv" style="color: black">
                       All System Users
                    </h1>

                </div>
   <div class="col-lg-2">
 <h4 class="page-header myDiv" style="color: #3e8f3e">
                    <a href="add_broker.php"> <button type="submit" class="w3-btn w3-white w3-border w3-border-blue w3-round-large"><span class="glyphicon glyphicon-user" style="color:mediumseagreen"> </span> <b style="color:mediumseagreen">Add Broker</b></button>
                    </a>
                    </h4> </div>
<?php
usersView();
?>

            </div>


        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<div id="xsx" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 align="center" class="modal-title" style="color: green"><b>Change Password</b></h4>

            </div>
            <div class="modal-body">
                <h4 align="center" style="color: #0d92e1">ID : <span id="myName"></span></h4>
                <input type="hidden" value="" id="pp">
                <b>Password</b>
                <input type="password" id="password" name="password" class="form-control">
                <b>Confirm Password</b>
                <input type="password" id="passwordC" name="passwordC" class="form-control">
                <p align="center" style="display: none; color:red; font-weight: bolder;" id="modShow">Please wait...</p>

                <div class="modal-footer">
                    <p align="center"><button class="btn btn-warning" onclick="submitPass()">Change Password</button><button class="btn btn-danger" onclick="reset()">Reset</button></p>

                </div>
            </div>

        </div>
    </div>
</div>

</script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.bootstrap.min.js"></script>
<script src="../js/responsive.bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>
</body>
</html>
