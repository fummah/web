<?php
session_start();
error_reporting(0);
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">

    <title>Daily Reports</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery-1.12.4.js"></script>
    <script src="../jquery.min.js"></script>
    <script src="../js/users.js"></script>
    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style type="text/css">

        input[type=text]{
            width:300px;
        }
        #role{
            width:300px;
            border-color: #3C510C;
        }
        .b{
            width:300px;
            border-color:#bee5eb;

        }
        .v{
            width:300px;

        }
        #myBtn{
            background-color: #0d92e1;
            border: none;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 2px 2px;
            cursor: pointer;
            padding: 5px;
            font-weight: bolder;
            color:#00ffff;
            width: 100px;
            border-radius: 2px;

        }
        #myBtn:hover{
            background-color: #00cc00;
        }
        #myClear:hover{
            background-color: #3C510C;
        }
        #myClear{
            background-color: grey;
            border: none;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 2px 2px;
            cursor: pointer;
            padding: 5px;
            font-weight: bolder;
            color:red;
            width: 100px;
            border-radius: 2px;

        }
        .myDiv{
            width:60%;
            margin-right: auto;
            margin-left: auto;
            display: block;
        }


    </style>

</head>
<body>

<?php

session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {

if($_SESSION['level']!="controller")
{
    $r="<script>location.href = \"login.html\";</script>";
    die($r);

}

}
else
{
 $r="<script>location.href = \"login.html\";</script>";
    die($r);
}

require_once('../dbconn1.php');

$conn=connection("mca","MCA_admin");

?>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="../index.php" style="color:#00ffff;">Med Claim Assist</a>
        </div>

        <?php
        //require_once ('admin_header.php');
        ?>

        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header myDiv" style="color: black">
                        Clients Daily Reports
                    </h1>

                </div>
                <form action="download_report.php" method="post">
                    <table align="center" class="myDiv">
                        <tr>
                            <td><b>Start Client : </b><select id="client_id" name="client_id" class="form-control b">

                                    <?php

                                    $sql = 'SELECT DISTINCT client_name,reporting_client_id FROM clients ORDER BY client_name ASC';
                                    $r=$conn->query($sql);
                                    foreach ($r as $row) {

                                        ?>
                                        <option value="<?php echo $row['reporting_client_id']; ?>"><?php echo $row['client_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><b>Select Date : </b><input type="date" id="dat" name="dat" class="form-control b"></td>

                        </tr>
                        <tr>

                            <td><button class="btn btn-info" name="download"><span style="color: white" class="fa fa-download"></span> Download Excel</button> <button class="btn btn-info" name="send"><span style="color: white" class="fa fa-send"></span> Send Now</button><img src="../images/loading.gif" height="100" width="100"></td>

                        </tr>
                    </table>
                </form>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="../js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../js/bootstrap.min.js"></script>

<!-- Morris Charts JavaScript -->
<script src="../js/plugins/morris/raphael.min.js"></script>
<script src="../js/plugins/morris/morris.min.js"></script>
<script src="../js/plugins/morris/morris-data.js"></script>

</body>
</html>
