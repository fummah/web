<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']) && $_SESSION['level'] == "admin") {


}
else
{
    ?>
    <script type="text/javascript">
        location.href = "../login.html"
    </script>

    <?php
}
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">

    <title>Automatic Case Allocation</title>

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
            border-color:red;
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
#mm:hover{

            background-color: #e8f6ff
    
}

    </style>

</head>
<body>
<?php
require_once('validateAdmin.php');
include('../classes/aaaClass.php');
$email = "";
$password = "";
$folder = "";
$imap = "";
$smtp = "";
$cc = "";
$conn = connection("mca", "MCA_admin");
$sql = $conn->prepare('SELECT email,password,destination_folder,smtp_server,imap_server,cc FROM email_configs');
$sql->execute();
$nu = $sql->rowCount();
if ($nu > 0) {

    foreach ($sql->fetchAll() as $row) {
        $email=$row[0];
        $password=$row[1];
        $folder=$row[2];
        $imap=$row[4];
        $smtp=$row[3];
        $cc=$row[5];
    }
}
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
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header myDiv" style="color: black">
                        Automatic Allocation of Cases
                    </h1>

                </div>

                <table style="font-weight: bolder" class="alert-info" cellspacing="5" width="100%">
                    <caption>Active Email Account</caption>
                    <tr>
                        <td>Email : <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="form-control b"></td>
                        <td>Password : <input type="password" id="password" value="<?php echo $password; ?>" class="form-control b"></td>
                        <td>Copied Email Address : <input type="text" id="cc" value="<?php echo $cc; ?>" class="form-control b"></td>
                    </tr>
                    <tr>
                        <td>Destination Folder : <input type="text" id="folder" value="<?php echo $folder; ?>" class="form-control"></td>
                        <td>Incoming Server : <input type="text" id="smtp" value="<?php echo $smtp; ?>" class="form-control v"></td>
                        <td>Outgoing Server : <input type="text" id="imap" value="<?php echo $imap; ?>" class="form-control v"></td>
                    </tr>
                </table>

                <button id="saveChanges" class="btn-info">Save Changes</button><button id="duplicates" style="float: right" class="btn-info">Show Duplicates</button><br>
                <span id="load" style="color: red;display: none">Please wait...</span>
                <span id="details" style="color: red;font-weight: bolder"></span>
                <hr>
                <h4>Number of Claims Processed Today</h4>
                <div id="dup" style="display: none">
                    <?php
                    duplicates();
                    ?>
                    <div id="close1" style="color: red;cursor: pointer" title="Close">&times;</div>
                </div>
                <div id="users">
                    <?php
                    aaaUsers();
                    aaaUsers1();

                    ?>
                </div>


                <hr>

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
