<?php
if(isset($_GET['sess']))
{
  $_SESSION['email_claim_id'] = (int)$_GET['sess'];
}

if(isset($_SESSION['email_claim_id']) && !empty($_SESSION['email_claim_id'])) {
 
}
else
{
    $_SESSION['email_claim_id'] = 0;
       //die("There is an error");
}

$script=basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$active1="";
$active2="";
if($script=="mailnbox.php"){$active1="active";}
else if($script=="sentitems.php"){$active2="active";}
?>
<style>
    .linkButton {
        background: none;
        border: none;
        color: #0066ff;
        text-decoration: underline;
        cursor: pointer;
    }

.note-box {
     border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-width: 100%;
    padding: 20px;
    margin: 10px;
    position: relative;
}

.note-content {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px;
}
.note-date {
    font-size: 14px;
    color: #888;
    position: absolute;
    bottom: 10px;
    right: 20px;
}
.note-from{
    background-color: whitesmoke; 
}
.active{
    font-weight:bolder !important
}
.subject{
    font-weight: bolder !important;
}
.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: #fff;
    background-color: #264566 !important;
}
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MCA | <?php echo $title;?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../admin/admin_main/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../admin/admin_main/dist/css/adminlte.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../../admin/admin_main/plugins/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="../css/uikit.min.css" />
    <script src="../js/uikit.min.js"></script>
    <script src="../js/uikit-icons.min.js"></script>
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->

    <!-- /.navbar -->


    <!-- Content Wrapper. Contains page content -->
    <div class="">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?php echo $title;?></h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                       
<?php
if($_SESSION['email_claim_id']!="Zero")
{
?>
 <a href="compose_email.php" class="btn btn-primary btn-block mb-3">Compose Email</a>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Folders</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="nav nav-pills flex-column">
                                    <li class="nav-item">
                                        <a href="mailnbox.php?sess=<?php echo $_SESSION['email_claim_id'];?>" class="nav-link <?php echo $active1;?>">
                                            <i class="fas fa-inbox"></i> Inbox

                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="sentitems.php?sess=<?php echo $_SESSION['email_claim_id'];?>" class="nav-link <?php echo $active2;?>">
                                            <i class="far fa-envelope"></i> Sent

                                        </a>
                                    </li>
                                    <li>
                                        <img style="width: 100%; height: auto;" id="ximg" src="..\images\Med ClaimAssist Logo_1000px.png">
                                    </li>

                                </ul>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <?php
}
                        ?>
                        <!-- /.card -->

                        <!-- /.card -->
                    </div>