<?php
session_start();
error_reporting(0);
?>
<html>
<head>
    <title>
       Change Password
    </title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">

    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.min.js"></script>
    <script src="js/jquery-1.12.4.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/animate.min.css">
    <style>
        .row{
            padding-top: 20px;
        }
        .container{

            position: relative;
            margin-right: auto;
            margin-left: auto;
            font-weight: bolder;
            border-radius: 5px;
            -webkit-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
            -moz-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
            box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
            padding-bottom: 30px;
        }
        input[type=text],textarea{
            padding-left: 10px;
            border-radius: 3px;
            border: none;
            background-color: lightgrey;
            outline: none;

        }
        b{
            color: grey;
        }
        .login-block {
            width: 320px;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            border-top: 5px solid black;
            margin: 0 auto;
        }

        .login-block h1 {
            text-align: center;
            color: #000;
            font-size: 18px;
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .login-block input {
            width: 100%;
            height: 42px;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            font-size: 14px;
            font-family: Montserrat;
            padding: 0 20px 0 50px;
            outline: none;
        }

        .login-block input#username {
            background: #fff url('http://i.imgur.com/u0XmBmv.png') 20px top no-repeat;
            background-size: 16px 80px;
        }

        .login-block input#username:focus {
            background: #fff url('http://i.imgur.com/u0XmBmv.png') 20px bottom no-repeat;
            background-size: 16px 80px;
        }

        .login-block input#password {
            background: #fff url('http://i.imgur.com/Qf83FTt.png') 20px top no-repeat;
            background-size: 16px 80px;
        }

        .login-block input#password:focus {
            background: #fff url('http://i.imgur.com/Qf83FTt.png') 20px bottom no-repeat;
            background-size: 16px 80px;
        }

        .login-block input:active, .login-block input:focus {
            border: 1px solid #ff656c;
        }

        .login-block button {
            width: 100%;
            height: 40px;
            background: lightblue;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid deepskyblue;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            font-family: Montserrat;
            outline: none;
            cursor: pointer;
        }

        .login-block button:hover {
            background: grey;
        }
    </style>
<script>
    function  action() {
        document.getElementById("pp").innerHTML="please wait...";

        var old_password=document.getElementById("old_password").value;
        var new_password=document.getElementById("new_password").value;
        var confirm_password=document.getElementById("confirm_password").value;
if(new_password == confirm_password && new_password.length>6)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            var mess=this.responseText;

            document.getElementById("pp").innerHTML=mess;

        }
    };
    xhttp.open("GET", "ajaxPhp/web_ajax.php?id=4&old="+old_password+"&new="+new_password, true);
    xhttp.send();
}
else {
    document.getElementById("pp").innerHTML="Incorrect Password";
}


    }
</script>
</head>

<body>
<div class="row1">

    <?php require_once ("myHeader.php")?>

</div>

<div class="container">

    <h1 align="center" style="color:deepskyblue">Change Password</h1>

    <div class="login-block">

        <input type="password" value="" placeholder="Old Password" id="old_password" />
        <input type="password" value="" placeholder="New Password" id="new_password" />
        <input type="password" value="" placeholder="Confirm Password" id="confirm_password" />
        <button onclick="action()">Change Password</button>

        <p align="center" style="color: red; font-weight: bolder" id="pp"></p>
    </div>
</div>
<hr>
<?php
include('footer.php');
?>
</body>
</html>