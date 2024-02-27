<html>
<head>
    <title>PMB Code</title>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <style type="text/css">
        <!--

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
    <script>
        function show() {
            $("#mess").text("please wait...");
            $("#mess").css("color","red");
        }
    </script>
</head>

<body>
<?php
session_start();
$_SESSION['start_db']=true;
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
                if($pmbCode=="")
                {
                    $pmbCode= "<span class='glyphicon glyphicon-warning-sign' style='color: goldenrod'></span> <b style='color:goldenrod'> This is not a PMB code.</b>";
                }
                else
                {
                    $pmbCode= "<span class='glyphicon glyphicon-ok' style='color: green'></span> <b style='color:green'> This is a PMB code.</b>";
                }
                $desc= $row[2];
                echo "<hr>Diagonisis Code : <b>".$row[0]."</b><br><br>";
                echo $pmbCode."<br><br>";
                echo "<u>Description</u> : <b>".$row[2]."</b><hr>";

            
        }
        else
        {
            echo"<span class='glyphicon glyphicon-remove-sign' style='color: red'></span> <b style='color:red'> Invalid Code.</b>";
        }
    }
    catch(Exception $e)
    {
        echo("There is an error. ");
    }
}
$vl="";
if(isset($_POST['btn']))
{
    $vl=validateXss($_POST['search']);

}

?>
<div>
    <div id="ss">

        <form class="tab" name="form" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

            <input type="text" id="search" name="search" value="<?php echo $vl; ?>" placeholder="type here...">
            <br><p></p>
            <button type="submit" class="btn btn-success" name="btn" onclick="show()"><span id="mess">Decode</span></button>
        </form>
    </div>




    <?php

    if(isset($_POST['btn']))
    {
        $vl=validateXss($_POST['search']);
        pmb();
    }

    ?>

</div>


</body>
</html>