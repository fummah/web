<?php
require_once('validateAdmin.php');
$head1="";
$head2="";
$head3="";
$head4="";
$head5="";
$head6="";
$head7="";
$head8="";
$head9="";
$head10="";
$head11="";
$head12="";
$head13="";
$head14="";
$head15="";
$head16="";
$head17="";
$head18="";
$head19="";
$head20="";
$head21="";
$head22="";
$head70="";
$menuopen1="";
$menuopen2="";
$menuopen3="";
$menuopen4="";
$menuopen5="";
$menuopen6="";
$menuopen7="";
$active1="";
$active2="";
$active3="";
$active4="";
$active5="";
$active6="";
$active7="";
$role=$_SESSION['level'];
$name2 =pathinfo($_SERVER['REQUEST_URI'], PATHINFO_FILENAME);
if($name2=="savings")
{
    $menuopen1="menu-open";
    $head2="active";
    $active1="active";
}
elseif ($name2=="doctors")
{
    $active1="active";
    $menuopen1="menu-open";
    $head3="active";
}
elseif ($name2=="billing")
{
    $active1="active";
    $menuopen1="menu-open";
    $head70="active";
}
elseif ($name2=="aspen")
{
    $active1="active";
    $menuopen1="menu-open";
    $head16="active";
}
elseif ($name2=="aaa")
{
    $head4="active";
}
elseif ($name2=="target")
{
    $head12="active";
}
elseif ($name2=="manage_documents")
{
    $menuopen2="menu-open";
    $head5="active";
    $active2="active";
}
elseif ($name2=="daily_reports")
{
    $menuopen2="menu-open";
    $head6="active";
    $active2="active";
}
elseif ($name2=="add_user")
{
    $menuopen3="menu-open";
    $head7="active";
    $active3="active";
}
elseif ($name2=="view_users")
{
    $menuopen3="menu-open";
    $head8="active";
    $active3="active";
}
elseif ($name2=="add_broker")
{
    $menuopen3="menu-open";
    $head9="active";
    $active3="active";
}
elseif ($name2=="other_claims")
{
    $menuopen4="menu-open";
    $head10="active";
    $active4="active";
}
elseif ($name2=="copayments")
{
    $menuopen4="menu-open";
    $head11="active";
    $active4="active";
}
elseif ($name2=="reopened_cases")
{
    $menuopen4="menu-open";
    $head13="active";
    $active4="active";
}
elseif ($name2=="notes_report")
{
    $menuopen4="menu-open";
    $head14="active";
    $active4="active";
}
elseif ($name2=="no_notes")
{
    $menuopen4="menu-open";
    $head15="active";
    $active4="active";
}
elseif ($name2=="web_brokers")
{
    $menuopen5="menu-open";
    $head19="active";
    $active5="active";
}
elseif ($name2=="subscriptions")
{
    $menuopen5="menu-open";
    $head18="active";
    $active5="active";
}
elseif ($name2=="qa")
{
    $menuopen6="menu-open";
    $head17="active";
    $active6="active";
}
elseif ($name2=="qa_percentages")
{
    $menuopen6="menu-open";
    $head20="active";
    $active6="active";
}
elseif ($name2=="incentive")
{
    $head21="active";
}
else
{
    $menuopen1="menu-open";
    $head1="active";
    $active1="active";
}

?>
<script>
    $(document).ready(function()
    {
        console.log("Testing");
        var url="<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>";
        console.log(url);
        $.ajax({

            url:"../ajaxPhp/ajaxRetrieve.php",
            type:"GET",
            data:{
                url:url,
                identityNum:15
            },
            success:function(data)
            {
                //console.log(data);
            },
            error:function(jqXHR, exception)
            {

            }
        });
    });
</script>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link"><span class="badge badge-warning" title='No additional notes for the second day'><?php echo $_SESSION["myorange"];?> / <?php echo $_SESSION["myopen"];?></span></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link"><span class="badge badge-danger" title='No additional notes for more than 2 days'><?php echo $_SESSION["myred"];?> / <?php echo $_SESSION["myopen"];?></span></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link"><span class="badge" title='No Notes for more than 2 days' style="background-color: purple; color: white"><?php echo $_SESSION["mypurple"];?> / <?php echo $_SESSION["myopen"];?></span></a>
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Actions for today</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 4 Cases
                    <span class="float-right text-muted text-sm">2019/03/04 12:22:09</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 Opened
                    <span class="float-right text-muted text-sm">2019/03/04 12:22:09</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 3 Notes
                    <span class="float-right text-muted text-sm">2019/03/04 12:22:09</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See More</a>
            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <?php
    //echo $_SESSION["admin_main"];
    if(strpos($_SESSION["admin_main"], "demo") !== false){
        ?>
        <a href="../../demo/index.php" class="brand-link">
            <img style="width: 100%; height: auto;" id="ximg" src="..\images\Med ClaimAssist Logo_1000px.png" >
        </a>
        <?php
    } else{

        ?>
        <a href="../../demo/index.php" class="brand-link">
            <img style="width: 100%; height: auto;" id="ximg" src="..\images\Med ClaimAssist Logo_1000px.png" >
        </a>
        <?php
    }
    ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">

            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION['user_id']." (".$role.")";?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview <?php echo $menuopen1;?>">
                    <a href="#" class="nav-link <?php echo $active1;?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./claims.php" class="nav-link <?php echo $head1;?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Claims</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./savings.php" class="nav-link <?php echo $head2;?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Savings</p>
                            </a>
                        </li>

                        <?php
                        if($role=="admin"|| $role=="controller")
                        {
                            ?>
                            <li class="nav-item">
                                <a href="./doctors.php" class="nav-link <?php echo $head3;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Doctors</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./billing.php" class="nav-link <?php echo $head70;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Billing</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./aspen.php" class="nav-link <?php echo $head16;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Aspen Report</p>
                                </a>
                            </li>

                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
                if($role=="admin"|| $role=="controller" || $role=="claims_specialist")
                {
                    ?>
                    <li class="nav-item has-treeview <?php echo $menuopen6;?>">
                        <a href="#" class="nav-link <?php echo $active6;?>">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                QA Reports
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="./qa.php" class="nav-link <?php echo $head17;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Main QA Report</p>
                                </a>
                            </li>
                          
                                <li class="nav-item">
                                    <a href="./qa_percentages.php" class="nav-link <?php echo $head20;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>QA Percentages</p>
                                    </a>
                                </li>
                         
                        </ul>
                    </li>
                    <?php
                    if($_SESSION['level']=="admin")
                    {
                        ?>
                        <li class="nav-item has-treeview <?php echo $menuopen5;?>">
                            <a href="#" class="nav-link <?php echo $active5;?>">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    Brokers
                                    <i class="fas fa-angle-left right"></i>

                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="subscriptions.php" class="nav-link <?php echo $head18;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Subscriptions</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="web_broker.php" class="nav-link <?php echo $head19;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Brokers</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="incentive.php" class="nav-link <?php echo $head21;?>">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Incentive Model
                                </p>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a href="aaa.php" class="nav-link <?php echo $head4;?>">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                AAA
                            </p>
                        </a>
                    </li>
                    <?php
                }
                if($role=="admin" || $role=="claims_specialist")
                {
                    ?>
                    <li class="nav-item">
                        <a href="target.php" class="nav-link <?php echo $head12;?>">
                            <i class="nav-icon fas fa-user-edit"></i>
                            <p>
                                KPI
                            </p>
                        </a>
                    </li>
                    <?php
                }
                if($role=="admin" || $role=="controller")
                {
                    ?>

                    <li class="nav-item has-treeview <?php echo $menuopen4;?>">
                        <a href="#" class="nav-link <?php echo $active4;?>">
                            <i class="nav-icon fas fa-table"></i>
                            <p>
                                Quality Control
                                <i class="fas fa-angle-left right"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="other_claims.php" class="nav-link <?php echo $head10;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Contacted Members</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="copayments.php" class="nav-link <?php echo $head11;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Zero Amounts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="reopened_cases.php" class="nav-link <?php echo $head13;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Reopened Cases</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="notes_report.php" class="nav-link <?php echo $head14;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Notes Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="no_notes.php" class="nav-link <?php echo $head15;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Out Of SLA</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="eightdays.php" class="nav-link <?php echo $head22;?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Claims with 8 days or more</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    if($_SESSION['level']=="admin")
                    {
                        ?>
                        <li class="nav-item has-treeview <?php echo $menuopen2;?>">
                            <a href="#" class="nav-link <?php echo $active2;?>">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Manage Documents
                                    <i class="fas fa-angle-left right"></i>

                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="manage_documents.php" class="nav-link <?php echo $head5;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Files</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="daily_reports.php" class="nav-link <?php echo $head6;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daily Reports</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview <?php echo $menuopen3;?>">
                            <a href="#" class="nav-link <?php echo $active3;?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    System Users
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./add_user.php" class="nav-link <?php echo $head7;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add User</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="view_users.php" class="nav-link <?php echo $head8;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Users</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="add_broker.php" class="nav-link <?php echo $head9;?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Broker</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                }
                ?>


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?php echo $title;?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="claims.php">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $title;?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->



