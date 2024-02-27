<?php


if(isset($_SESSION['change']) && !empty($_SESSION['change'])) {

}
else
{
unset($_SESSION['my_id']);
unset($_SESSION['user_id']);
unset($_SESSION['myUsername']);
unset($_SESSION['level']);
unset($_SESSION['role_value']);
unset($_SESSION['logxged']);
unset($_SESSION['sitePath']);
unset($_SESSION['LAST_ACTIVITY']);
unset($_SESSION['logged_in']);
session_unset();
session_destroy();
session_write_close();

    $r="<script>location.href = \"login.html\";</script>";
    die($r);
}
$role=$_SESSION['mca_role'];
$link="#";
if($role=="broker")
{
   $link="broker.php";
}
elseif ($role=="client")
{
    $link="client.php";
}
///////////
/// $addCase="";
$home="active";
$prof="";
$script=$_SERVER['SCRIPT_NAME'];


if ($script=="/admin/myprofile.php")
{
    $home="";
    $prof="active";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Header</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="bootstrap3/js/bootstrap.min.js"></script>



</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="https://www.medclaimassist.co.za" style="color: #00b3ee">MED ClaimAssist Site</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="<?php echo $home;?>"><a href="<?php echo $link;?>">My Home</a></li>

                <li class="<?php echo $prof;?>"><a href="myprofile.php">My Profile</a></li>

            </ul>
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <b style="color: #00b3ee"> <?php echo ucwords($_SESSION['mca_name']." ".$_SESSION['mca_surname']);?> </b><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="site_logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
 <li><a href="mca_changePassword.php"><span class="glyphicon glyphicon-edit"></span> Change Password</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>