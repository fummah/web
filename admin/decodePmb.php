<?php
session_start();
error_reporting(0);
?>
<html>
<head>
    <title>PMB Code</title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <style type="text/css">
        <!--
        .tab { margin-left: 200px; }
        -->
        input[type=text] {
            width: 260px;
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
            margin-left: 200px;
        }

    </style>
</head>

<body>
<?php
include("header.php");
include_once "dbconn.php";

function pmb()
{
    try
    {

        $conn=connection("cod","Coding");

        $code=validateXss($_POST['search']);
        $stmt = $conn->prepare('SELECT `diag_code`,`pmb_code`,`shortdesc` FROM `Diagnosis` WHERE diag_code=:num UNION SELECT `ICD10_Code`,`pmb`,`ICD10_3_Code_Desc` FROM `diagonisis1`  WHERE ICD10_Code=:num');
        $stmt->bindParam(':num', $code, PDO::PARAM_STR);
        $stmt->execute();
        $nu=$stmt->rowCount();
        if($nu>0)
        {
             $row=$stmt->fetch();
                $pmbCode= $row[1];
                $desc= $row[2];
                echo "Diagonisis Code :<b>".$row[0]."</b><br>";
                echo "PMB Code :<b>".$row[1]."</b><br>";
                echo "Description :<b>".$row[2]."</b><br>";

            
        }
        else
        {
            echo"Invalid Code";
        }
    }
    catch(Exception $e)
    {
        echo("There is an error.");
    }
}
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
<h3 class="tab">Decode ICD10 Code</h3>
<form class="tab" name="form" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div id="ss">
        <input type="text" id="search" name="search" value="<?php echo $vl; ?>" placeholder="Enter...">
        <br><br>
        <input type="submit" class="btn btn-info" name="btn" value="Decode">
    </div>

</form>

<form class="tab" action="" method="post" />

</form>
<div id="doc" class="alert alert-info">
    <?php

    if(isset($_POST['btn']))
    {
        $vl=validateXss($_POST['search']);
        pmb();
    }

    ?>
</div>
<hr>
<?php
include('footer.php');
?>
</body>
</html>