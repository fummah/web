<?php

if(!isset($_SESSION['logxged']))
{
    header("Location:login.html");

}
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
if($_SESSION['level']=="claims_specialist")
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
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="images/test-1-1026x354.png" type="image/x-icon">
<link rel="shortcut icon" type="image/png" href="/images\Med ClaimAssist Logo_1000px.png"/>
<link rel="shortcut icon" type="image/png" href="images\Med ClaimAssist Logo_1000px.png"/>
<head>
    <style>


        .show {
            display: block;
        }
        .hh{
            border:none;
            border-radius: 3px;
        }
        #ximg{

            width: 25%;
            height: auto;


        }
        .current{

            font-weight: bolder;
            color: green;
            border-bottom: double;
            border-bottom-color:#00ffff;
            background-color: black;

        }

    </style>

<body>
<?php


$addCase="";
$addDoc="";
$openCase="";
$openCase1="";
$diagCode="";
$advanced="";
$script=$_SERVER['SCRIPT_NAME'];

if($script=="/admin/add_new_case.php")
{
    $addCase="current";
}
elseif ($script=="/admin/add_old_cases.php")
{
    $addCase="current";
}
elseif ($script=="/admin/add_doc_form.php")
{
    $addDoc="current";
}
elseif ($script=="/admin/add_documents.php")
{
    $openCase1="current";
}
elseif ($script=="/admin/list_cases.php")
{
    $openCase="current";
}
elseif ($script=="/admin/decodePmb.php")
{
    $diagCode="current";
}
elseif ($script=="/admin/case_search.php")
{
    $advanced="current";
}
elseif ($script=="/admin/search_form.php")
{
    $advanced="current";
}

?>
<header>
    <nav class="navbar navbar navbar-default navbar-fixed-top" style="border-bottom: double; border-bottom-color: #00b3ee">
        <div class="container-fluid navbar-inverse">
            <div class="navbar-header ">
                <a class="navbar-brand" href="index.php" style="color:#00ffff;">Med ClaimAssist</a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="hidden-xs hidden-md collapse navbar-collapse navbar-right">
                <?php
                if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                    ?>
                    <ul class="nav navbar-nav" id="removeOnCollapse">
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span>
                                <strong style="color: mediumseagreen"><?php echo $_SESSION['myUsername']; ?></strong> <span
                                        class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="logout.php" data-toggle="tooltip" title="Logout">
                                        <button type="button" class="hh btn" style="background-color: red; color: white">Logout <span
                                                    class="glyphicon glyphicon-log-out"></span></button>
                                    </a></li>
                                <li><a href="mca_change_pass.php" data-toggle="tooltip" title="change password"><button type="button" class="hh btn" style="background-color: red; color: white">
                                            Change Password <span class="glyphicon glyphicon-wrench"></span></button></a></li>

                            </ul>
                        </li>
                    </ul>
                    <?php
                }
                else{
                    ?>
                    <ul class="nav navbar-nav" id="removeOnCollapse">
                        <li> <span><span class="glyphicon glyphicon-user"></span>
                        <strong style="color: red"><?php echo $_SESSION['myUsername']." "; ?></strong><a href="logout.php"> <button class="btn btn-danger">Logout</button></a></span>

                        </li></ul>
                    <?php
                }
                ?>
            </div>
            <div class="collapse navbar-collapse navbar-left">
                <ul class="nav navbar-nav ">
                    <?php

                    if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                        ?>
                        <li><a href="admin_main/claims.php"><b>Dashboard</b></a></li>
                        <?php
                    }


                    if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                        ?>
                        <li class="dropdown <?php echo $addCase;?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Add Case <span
                                        class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="add_new_case.php">New Case</a></li>
                                <li><a href="schemes.php">Medical Schemes</a></li>

                            </ul>
                        </li>


                        <li class="<?php echo $addDoc;?>"><a href="add_doc_form.php">Add New Doctor</a></li>
                        <?php
                    }
                    ?>
                    <li class="<?php echo $openCase1;?>"><a href="add_documents.php">Add New Claim</a></li>
                    <li class="<?php echo $openCase;?>"><a href="list_cases.php">View Open Cases</a></li>
                    <li class="<?php echo $diagCode;?>"><a href="decodePmb.php">Diagonisis Code(PMB)</a></li>
                    <?php
                    if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                        ?>
                        <li class="dropdown <?php echo $advanced;?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Advanced Search <span
                                        class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="case_search.php">Search your Cases</a></li>
                                <li><a href="search_form.php">Search Doctor</a></li>
                                <li><a href="recent_files.php">Recent Cases</a></li>
                                <li class="w3-bar-item"><a href="new_issue.php">MCA Helpdesk</a></li>

                            </ul>
                        </li>
                        <?php
                    }else{
                        echo"<li class='$advanced'><a href=\"case_search.php\">View All Cases</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

</body>
</html>
