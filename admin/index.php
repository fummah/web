<?php
session_start();
error_reporting(0);
$_SESSION["admin_main"]=$_SERVER['REQUEST_URI'];
//$_SESSION["mainback"]="https://medclaimassist.co.za/admin/index.php";
?>
<html>
<head>

    <title>MCA Administration System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.min.js"></script>
    <script src="js/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="admin_main/plugins/datatables-bs4/css/dataTables.bootstrap4.css">  <!-- Theme style -->
    <script src="admin_main/plugins/datatables/jquery.dataTables.js"></script>
    <script src="admin_main/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="js/newCase.js"></script>
    <script src="js/search.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
    <style type="text/css">
        .tab1 { margin-left: 50px; }
        .linkButton {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;

        }

        input[type=text] {
            width: 25%;
            box-sizing: border-box;
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

        #ss
        {
            margin-left: 50px;
            display: none;;
        }

        .xx{
            font-weight: bolder;
        }
        #tb{
            width: 75%;
            margin-left: 50px;
        }
        #advanced{
            margin-left: 50px;
        }
        #notifications{
            float: right;
            width: auto;
            right: 10px;
            position: fixed;
            z-index: 1;

        }
        #view{
            margin-left: 50px;
        }
        #nnb{
            display: block;
            width: 77%;

        }

        #view1{
            margin-left: 20px;
        }
        #myInput{
            width:100%;
            background-image:none;
        }
        #myDiv{
            z-index: 1;
        }
        #blink {

            animation:1s blinker linear infinite;
            -webkit-animation:1s blinker linear infinite;
            -moz-animation:1s blinker linear infinite;

            color: red;
        }

        @-moz-keyframes blinker {
            0% { opacity: 1.0; }
            50% { opacity: 0.0; }
            100% { opacity: 1.0; }
        }

        @-webkit-keyframes blinker {
            0% { opacity: 1.0; }
            50% { opacity: 0.0; }
            100% { opacity: 1.0; }
        }

        @keyframes blinker {
            0% { opacity: 1.0; }
            50% { opacity: 0.0; }
            100% { opacity: 1.0; }
        }

        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(images/Preloader_2.gif) center no-repeat #fff;
        }
        }
    </style>

</head>
<script type="text/javascript">
    $(document).ready(function()
    {
        var  red1=$("#red1").val();
        var  orange1=$("#orange1").val();
        var  purple1=$("#purple1").val();
        var  arrall=$("#arrall").val();
        var  arrall1=$("#arrall1").val();

        var obj = JSON.parse(arrall);
        var obj1 = JSON.parse(arrall1);
        var key1="*";
        var key2="^";
        var key3="~";

        for (var i in obj) {


            if(i==0)
            {
                for (var j in obj[i])
                {
                    var txt=obj[i][j].a;
                    $("#bas3").append("<span onclick=\"checkHere('"+txt+"','"+key3+"',6)\" style='cursor:pointer'><span class='badge w3-purple w3-display-container'>"+obj[i][j].num+"</span> : "+txt+"</span><br>");
                }

                for (var p in obj1[i])
                {
                    var txt=obj1[i][p].a;
                    $("#bas31").append("<span onclick=\"checkHere('"+txt+"','"+key3+"',7)\" style='cursor:pointer'><span class='badge w3-purple w3-display-container'>"+obj1[i][p].num+"</span> : "+txt+"</span><br>");
                }


            }
            if(i==1)
            {
                for (var j in obj[i])
                {
                    var txt=obj[i][j].a;
                    $("#bas2").append("<span onclick=\"checkHere('"+txt+"','"+key2+"',6)\" style='cursor:pointer'><span class='badge w3-red w3-display-container'>"+obj[i][j].num+"</span> : "+txt+"</span><br>");
                }
                for (var p in obj1[i])
                {
                    var txt=obj1[i][p].a;
                    $("#bas21").append("<span onclick=\"checkHere('"+txt+"','"+key2+"',7)\" style='cursor:pointer'><span class='badge w3-red w3-display-container'>"+obj1[i][p].num+"</span> : "+txt+"</span><br>");
                }
            }
            if(i==2)
            {
                for (var j in obj[i])
                {
                    var txt=obj[i][j].a;
                    $("#bas1").append("<span onclick=\"checkHere('"+txt+"','"+key1+"',6)\" style='cursor:pointer'><span class='badge w3-orange w3-display-container'>"+obj[i][j].num+"</span> : "+txt+"</span><br>");
                }
                for (var p in obj1[i])
                {
                    var txt=obj1[i][p].a;
                    $("#bas11").append("<span onclick=\"checkHere('"+txt+"','"+key1+"',7)\" style='cursor:pointer'><span class='badge w3-orange w3-display-container'>"+obj1[i][p].num+"</span> : "+txt+"</span><br>");
                }
            }

        }

        var getleadcolor=$("#gettco").val();
        $("#leed").removeClass("w3-green");
        $("#leed").addClass(getleadcolor);

        $("#red").text(red1);
        $("#orange").text(orange1);
        $("#purple").text(purple1);

        $("#ppp").addClass('animated flip');
        $("#advanced").click(function()
        {
            $("#slider").hide();
            $("#ss").show("slide");

        });

        $("#reset").click(function()
        {
            $("#slider").show("slide");
            $("#ss").hide("slide");
        });


        $("#hid").click(function()
        {
            $("#notifications").hide("slide");
        });
        $(".tab1").addClass("w3-animate-zoom");
        $("#notifications").addClass("w3-animate-zoom");

        //var table = $('#example').DataTable();

        $('#red_on').on( 'click', function () {
            var table = $('#example').DataTable();
            var val="^";
            table.search(val).draw();
//table.order( [[ 3, 'desc' ]] ).draw();
        } );
        $('#orange_on').on( 'click', function () {

            var table = $('#example').DataTable();
            var val="*";
            table.search(val).draw();
//table.order( [[ 3, 'desc' ]] ).draw();
        } );

        $('#purple_on').on( 'click', function () {

            var table = $('#example').DataTable();
            var val="~";
            table.search(val).draw();
//table.order( [[ 3, 'desc' ]] ).draw();
        } );
        $('#clearf').click(function(){

            var table = $('#example').DataTable();
            table.search('').draw();
            table.column(6).search('').draw();
            table.column(7).search('').draw();
        });
    });

</script>

<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
<script>
    //paste this code under the head tag or in a separate js file.
    // Wait for window load
    $(window).load(function() {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
    });

    function checkHere(txt,ke,col)
    {

        var table = $('#example').DataTable();
        table.search(ke).draw();
        table.column(col).search(txt).draw();
//table.order( [[ 3, 'desc' ]] ).draw();
    }
</script>
<body class="uk-background-default uk-card-default">
<div class="se-pre-con"></div>
<?php

include("header.php");

include("searchClass.php");

include("classes/notificationsClass.php");

echo "<div style='border-style: solid; border-color: #00b3ee; border-width: 4px;'> <br/><br/><br/>";

/*** tell the user we are logged in ***/
//$message = 'You are now logged in(<b style="color:green">'.$_SESSION['user_id']."</b>)";

echo "<h3 class=\"tab1\" id='ppp'><u><b>Welcome to the <i style='color: grey'>MED ClaimAssist</i> Claims System</b></u></h3>";
echo "<p class=\"tab1\">";
//echo "<b>".$message." <img src='images/imoj.png' style='width: 25px; height: 25px'></br>";

?>
<hr>
<div id="notifications" class="w3-panel w3-card w3-leftbar w3-border-blue alert w3-hover-shadow uk-card uk-card-small uk-card-default" style="border-color: #00b3ee"><h3><u style="color: red">My Dashboard</u></h3>
    <div class="w3-border-blue uk-text-lighter uk-text-large">

                    <span id="hid" class="w3-button w3-red w3-xl w3-display-topright">
                        &times;
                    </span>
        <?php

        $display=new displayClass();
        $display->levelsDisplay();
        ?>

    </div>

</div>
<?php
if ($_SESSION['level'] == "gap_cover") {
    $vl="";
    if(isset($_POST['btn']))
    {
        $vl=validateXss($_POST['search']);

    }
    ?>
    <div id="view">
        <?php
        if ($_SESSION['user_id'] == "Western") {
            echo "<span class=\"xx\"><a href='add_documents.php'> <button type=\"submit\" class=\"w3-btn w3-white w3-border w3-border-blue w3-round-large\" id=\"btn\" name=\"btn\"><span class=\"glyphicon glyphicon-list-alt\" style=\"color:mediumseagreen\"> </span> <b style=\"color:mediumseagreen\">Add Claim Files</b></button></a></span>";
        }
        ?>
        <hr>
        <form class="tab" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" />


        <b><input type="text" class="uk-input" id="search" name="search" value="<?php echo $vl; ?>" placeholder="search claim..."><b>
                <input type="submit" name="btn" class="btn btn-info" value="Search" /><a href="index.php" data-toggle="tooltip" title="Refresh"><span class="glyphicon glyphicon-refresh"></span></a>
    </div>
    </form>

    <?php

    echo"<div id=\"view1\">";



    if(isset($_POST['btn']))
    {
        include('clients.php');
        $vl=validateXss($_POST['search']);
        if($vl !='') {
            $_SESSION['term']=$vl;

            searchFunction($vl);
        }
    }
    else
    {
        echo"<div id='nnb'>";
        include_once "all_clients.php";

        echo"</div>";
    }
    echo "</div>";
}
else {
    $fb=new Feedback();
    $fb->flashNow();
    ?>
    <div id="slider">
        <?php
        $openCases = new Search();
        $openCases->quickSearch();
        ?>
    </div>
    <?php
}
?>
<hr>
<?php
include('footer.php');
?>
</div>
</body>
</html>