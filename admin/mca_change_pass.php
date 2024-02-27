<?php
error_reporting(0);
session_start();
if(isset($_SESSION['change']) && !empty($_SESSION['change'])) {

    if(!isset($_SESSION['my_id']) && empty($_SESSION['my_id'])) {

         $r="<script>location.href = \"login.html\";</script>";
    die($r);
    }
}
else
{
   $r="<script>location.href = \"login.html\";</script>";
    die($r);
}
?>
<html>
<head>

    <title>MCA Change Password</title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <style>
        #divp {
            -webkit-box-shadow: 9px 33px 199px 39px rgba(46, 42, 46, 1);
            -moz-box-shadow: 9px 33px 199px 39px rgba(46, 42, 46, 1);
            box-shadow: 9px 33px 199px 39px rgba(46, 42, 46, 1);
            padding: 20px;
        }
    </style>
    <script>
        function submitPass() {
            document.getElementById("modShow").style.display = "block";
            var id=document.getElementById("pp").value;
            var pass=document.getElementById("password").value;
            var cpass=document.getElementById("passwordC").value;
            if(pass.length<8 || pass!=cpass)
            {
                document.getElementById("modShow").innerHTML = "Invalid Password";
                document.getElementById("modShow").style.color = "Red";
            }
            else {
                var myObj={
                    id:id,
                    identity:4,
                    pass:pass
                };


                $.ajax({
                    url:'ajaxPhp/pas2.php',
                    type:'POST',
                    data:myObj,
                    success:function (data) {
                        document.getElementById("modShow").style.color = "grey";
                     
                        $('#modShow').html(data);

                    },
                    error:function(jqXHR, exception)
                    {
                        alert("Error");
                    }
                });
            }
        }
    </script>
</head>

<body>
<?php
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {

    include("header.php");
    echo "<br><br><br>";
}
?>
<div id="divp" style="position: relative;width: 30%;margin-right: auto;margin-left: auto;">

    <h2 align="center" style="color: #0d92e1"><u>MCA Change Password</u></h2>
    <input type="hidden" id="pp" value="<?php echo $_SESSION["my_id"];?>" name="pp" class="form-control">
    <b>Password</b>
    <input type="password" id="password" name="password" class="form-control">
    <b>Confirm Password</b>
    <input type="password" id="passwordC" name="passwordC" class="form-control">

    <hr> <p align="center" style="display: none; color:red; font-weight: bolder;" id="modShow">Please wait...</p>
    <p align="center"><button class="btn btn-info" onclick="submitPass()">Change Password</button></p>

    <?php
    if(!isset($_SESSION['logxged']) && empty($_SESSION['logxged'])) {
        ?>
        <p align="center"><a href="login.html">Login</a></p>
        <?php
    }
    ?>
</div>
<?php
include('footer.php');
?>
</body></html>