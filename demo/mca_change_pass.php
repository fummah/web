<?php
error_reporting(0);
session_start();
define("access",true);
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

    <title>MCA | Change Password</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/materialize.css">
    <link rel="stylesheet" href="css/materialize.min.css">
    <link rel="stylesheet" href="css/ghpages-materialize.css">
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.css">
    <script type="text/javascript" src="js/datatables.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/jquery.timeago.min.js"></script>
    <script src="js/lunr.min.js"></script>
    <script src="js/prism.js"></script>
    <script src="js/search.js"></script>
    <script src="js/init.js"></script>
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
    <style>
        #divp {
            border: 1px solid #54bc9c;
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
                    url:'ajax/pas2.php',
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
    include ("classes/controls.php");
    $control=new controls();
    include("header.php");
    echo "<br><br><br>";
}
?>
<div id="divp" style="position: relative;width: 30%;margin-right: auto;margin-left: auto;">

    <h3 align="center">MCA Change Password</h3>
    <input type="hidden" id="pp" value="<?php echo $_SESSION["my_id"];?>" name="pp" class="form-control">
    <span>Password</span>
    <input type="password" id="password" name="password" class="uk-input" placeholder="type...">
    <span>Confirm Password</span>
    <input type="password" id="passwordC" name="passwordC" class="uk-input" placeholder="type...">
 <p align="center" style="display: none; color:red; font-weight: bolder;" id="modShow">Please wait...</p>
    <p align="center"><button class="uk-button uk-button-primary" style="background-color: #54bc9c" onclick="submitPass()"><span uk-icon="lock"></span> Change Password</button></p>

    <?php
    if(!isset($_SESSION['logxged']) && empty($_SESSION['logxged'])) {
        ?>
        <p align="center"><a href="login.html">Login</a></p>
        <?php
    }
    ?>
</div>

</body></html>