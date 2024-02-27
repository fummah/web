<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">

    <title>Add Broker</title>

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
        .row{
            padding-top: 20px;
        }
        .container{

            position: relative;
            margin-right: auto;
            margin-left: auto;
            font-weight: bolder;
            border-radius: 5px;
            border: groove;
            border-color: grey;
            padding-bottom: 10px;
        }
        input[type=text],textarea,input[type=date],input[type=email],select{
            padding-left: 10px;
            border-radius: 3px;
            border: none;
            background-color: lightgrey;
            outline: none;
            width: 200px;

        }
        button{
            width: 100px;
        }

    </style>
    <script>
        function addRecord() {
            
          document.getElementById('loadingmsg').style.display = 'block';
            var  obj= {
                id:2,
                first_name : $('#first_name').val(),
                last_name : $('#last_name').val(),
                dob : $('#dob').val(),
                id_num : $('#id_num').val(),
                email : $('#email').val(),
                phone : $('#phone').val(),
                medical_scheme : "---",
                scheme_option : "---",
                aid_id : "---",
                addr_1 : $('#addr_1').val(),
                addr_2 : $('#addr_2').val(),
myVal : "admin"
            };
            $.ajax({
                url:"../ajaxPhp/web_ajax.php",
                type:"GET",
                data:obj,
                async: false,
                success:function(data) {
                    $('#loadingmsg').html(data);
                },
                error:function (Exception) {

                }

            });

            //document.getElementById('myModal').style.display = "block";
            return false;
        }
    </script>
</head>
<body>
<?php
require_once('validateAdmin.php');
$_SESSION['mca_admin']=true;
$_SESSION['mca_role']="broker";
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
<br><br>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header myDiv" style="color: black">
                        Fill broker details below
                    </h1>

                </div>

                    <form method="get" action="" onsubmit="return addRecord()">
                        <div class="container">
                            <div></div>
                            <div class="row">
                                <div class="col-sm-6">First Name</div>
                                <div class="col-sm-6"><input type="text" name="first_name" id="first_name" REQUIRED> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">Surname</div>
                                <div class="col-sm-6"><input type="text" name="last_name" id="last_name" REQUIRED> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">D.O.B</div>
                                <div class="col-sm-6"><input type="date" name="dob" id="dob"> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">ID Number</div>
                                <div class="col-sm-6"><input type="text" id="id_num" name="id_num"> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">Email Address</div>
                                <div class="col-sm-6"><input type="email" id="email" name="email"> </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">Contact Number</div>
                                <div class="col-sm-6"><input type="text" id="phone" name="phone"> </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">Physical Address 1</div>
                                <div class="col-sm-6"><textarea id="addr_1"></textarea> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">Physical Address 2</div>
                                <div class="col-sm-6"><textarea id="addr_2"></textarea> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6"><button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-ok" style="color: white"></span> Submit</button>
                                    <button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-remove" style="color: white"></span> Clear</button></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <span id='loadingmsg' style='display: none;'>please wait...</span>
                                </div>

                            </div>

                        </div>
                    </form>

            </div>
            <!-- /.row -->



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
