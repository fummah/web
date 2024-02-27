<?php
session_start();
error_reporting(0);
?>
<html>
<head>
    <title>MedClaim Assist : Search Doctor</title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <style type="text/css">
        <!--
        .tab { margin-left: 170px; }
        -->
        input[type=text] {
            width: 300px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 11px;
            background-color: white;
            background-image: url('images/search.png');
            background-position: 10px 10px;
            background-size: 20px;
            background-repeat: no-repeat;
            padding: 12px 20px 12px 40px;
            -webkit-transition: width 0.4s ease-in-out;
            transition: width 0.4s ease-in-out;
        }
        #doc{
            width: 80%;
            display: block;
            margin-right: auto;
            margin-left: auto;
        }

    </style>
</head>

<body>
<?php
include("header.php");
include_once ("dbconn.php");
$vl="";
if(isset($_POST['btn']))
{
    $vl=validateXss($_POST['search']);

}
?>
<br>
<br>
<br>
<br>
<br>
<h3 class="tab">Search Doctor</h3>
<form class="tab" name="form" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div id="ss">
        <input type="text" id="search" name="search" value="<?php echo $vl; ?>" placeholder="Enter...">

        <input type="submit" class="btn btn-info" name="btn" value="Search"><a href="search_form.php" data-toggle="tooltip" title="Refresh"><span class="glyphicon glyphicon-refresh"></span></a>
    </div>

</form>

<form class="tab" action="" method="post" />

</form>
<div id="doc">
    <?php
    include('search_target.php');
    if(isset($_POST['btn']))
    {
        $vl=validateXss($_POST['search']);
        searchDoctor($vl);
    }
    else
    {
        allDoctors();
    }
    ?>
</div>
<hr>
<?php
include('footer.php');
?>
</body>
</html>