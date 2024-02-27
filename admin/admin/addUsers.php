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

    <title>Add System Users</title>

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
            border-color: #0d92e1;
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
require_once('validateAdmin.php');
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
                        Add New User
                    </h1>

                </div>
                <div class="myDiv">
                <b>First Name</b><input type="text" name="name" id="name" class="form-control">
                <b>Surname</b><input type="text" name="surname" id="surname" class="form-control">
                <b>Email</b><input type="text" name="email" id="email" class="form-control">
                <b>Phone Number</b><input type="text" name="phone" id="phone" class="form-control">
                <b>Role</b><select class="form-control" name="role" id="role">
                        <option value="claims_specialist">Claims Specialist</option>
                        <option value="gap_cover">Gap Cover</option>
                    </select><hr>
                <b>Password</b><input type="password" id="password" class="form-control b">
                <b>Confirm Password</b><input type="password" id="passwordC" class="form-control b"><br>
                <button id="myBtn">Add</button><button id="myClear">Clear</button><br>
                    <span id="load" style="color: red;display: none">Please wait...</span>
                    <span id="details" style="color: red;font-weight: bolder"></span>
                </div>
            </div>
            <!-- /.row -->


            </form>
<?php
include('footer.php');
?>
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
