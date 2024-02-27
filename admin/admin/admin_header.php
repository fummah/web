<?php
session_start();
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {

if($_SESSION['level']!="admin")
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
   $r="<script>location.href = \"../login.html\";</script>";
    die($r);

}

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
$r="<script>location.href = \"../login.html\";</script>";
    die($r);
}
$viewUsers="active";
$addUsers="";
$reports="";
$sendReports="";
$manDoc="";
$aaa="";
$script=$_SERVER['SCRIPT_NAME'];

if($script=="/admin/admin/addUsers.php")
{
    $addUsers="active";
    $viewUsers="main";
}
elseif ($script=="/admin/admin/reports.php")
{
    $reports="active";
    $viewUsers="main";
}
elseif ($script=="/admin/admin/daily_reports.php")
{
    $sendReports="active";
    $viewUsers="main";
}
elseif ($script=="/admin/admin/manageDoc.php")
{
    $manDoc="active";
    $viewUsers="main";
}
elseif ($script=="/admin/admin/aaa.php")
{
    $aaa="active";
    $viewUsers="main";
}
?>


<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li class="<?php echo $viewUsers;?>">
            <a href="main.php"><i class="fa fa-fw fa-dashboard"></i> <u>Dashboard</u></a>
        </li>
        <li class="<?php echo $addUsers;?>">
            <a href="addUsers.php"><i class="fa fa-plus"></i> Add User</a>
        </li>
        <li class="<?php echo $reports;?>">
            <a href="reports.php"><i class="fa fa-bar-chart"></i> Reports</a>
        </li>
        <li class="<?php echo $sendReports;?>">
            <a href="daily_reports.php"><i class="fa fa-file-excel-o"></i> Daily Reports</a>
        </li>
        <li class="<?php echo $manDoc;?>">
            <a href="manageDoc.php"><i class="fa fa-fw fa-book"></i>Manage Documents</i></a>

        </li>
        <li class="<?php echo $aaa;?>">
            <a href="aaa.php"><i class="fa fa-fw fa-arrows-v"></i>AAA Configuration</i></a>

        </li>

    </ul>
</div>