<?php
session_start();
error_reporting(0);
define("access",true);
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();

?>
<link rel="stylesheet" href="css/uikit.min.css" />
<script src="js/uikit.min.js"></script>
<script src="js/uikit-icons.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js" integrity="" crossorigin="anonymous"></script>
<script>
    function checkHorse()
    {
        var client = document.getElementById("client_id").value;
        checkAspen(client);
        $.ajax({
            url:"ajax/claims.php",
            type:"POST",
            data:{
                identity_number:3
            },
            success:function(data)
            {
                //console.log(data);
                if(data.indexOf("diag_code")>-1)
                {
                    var json = JSON.parse(data);
                    for(var key in json)
                    {
                        $("#icd10_codes").append("<option value='"+json[key]["diag_code"]+"'>");

                    }
                }
//diag_code
            },
            error:function(jqXHR, exception)
            {
                alert("There is an error : "+jqXHR.responseText);
            }
        });
    }
    var i=0;
    setInterval(function () {

    }, 15000);
</script>
<style>
    .text {
        color: white;
        float: right;
        position: relative;
        border-right: 3px solid yellow;
        top:25%;
        width:7%;
        font-size: 18px;
        font-family: 'georgia';
        font-weight: bold;
        padding-right: 70px;
    }
    .boundary{
        height: 100%;
        float: right;
        margin-right: 100px;
        border-right: 10px dashed white;
        border-left: 10px dashed black;
        position: relative;
        background-color: red;
    }
    .outer{
        width: 100%;
        background-color: #54bc9c;
        position: relative;
        border-radius: 20px;
        margin-left: auto;
        margin-right: auto;
    }
    .pos1{
        position: absolute;margin-top: 30px;
        z-index: 1;
        color: #54bc9c;
        font-weight: bolder;
    }
    .track-outer{
        width: 100%;
        background-color: green;
        position: relative;
        margin-left: auto;
        margin-right: auto;
        border-radius: 20px;
        padding: 10px;
    }
    .track{
        width: 100%;
        background-color: green;
        height: 50px;
        border-bottom: 3px solid white;
    }
    .shirley{
        border-top: 3px solid white;
    }
    img {
        width:auto;
        height:100%;

    }
    .stella>img{
        margin-left: 50px
    }
    .shirley>img{
        margin-left: 200px
    }
    .white{background-color: white}
    .black{background-color: black;}
    td{width: 20px; height: 20px}
    h1 {
        -webkit-transform: rotate(-270deg);
        position: absolute;
        color: white;
        margin-left: -30px;
        margin-top: 100px;
    }
</style>
<div class="">
    <div class="track-outer uk-card row">
        <div class="col-md-1" style="background-color: red">
            <h1>Start</h1>
            <table style="float: right">
                <tr><td class="white"></td><td class="black"></td><td class="white"></td></tr>
                <tr><td class="black"></td><td class="white"></td><td class="black"></td></tr>
                <tr><td class="white"></td><td class="black"></td><td class="white"></td></tr>
                <tr><td class="black"></td><td class="white"></td><td class="black"></td></tr>
                <tr><td class="white"></td><td class="black"></td><td class="white"></td></tr>
                <tr><td class="black"></td><td class="white"></td><td class="black"></td></tr>
                <tr><td class="white"></td><td class="black"></td><td class="white"></td></tr>
                <tr><td class="black"></td><td class="white"></td><td class="black"></td></tr>
                <tr><td class="white"></td><td class="black"></td><td class="white"></td></tr>
                <tr><td class="black"></td><td class="white"></td><td class="black"></td></tr>
                <tr><td class="white"></td><td class="black"></td><td class="white"></td></tr>
                <tr><td class="black"></td><td class="white"></td><td class="black"></td></tr>
                <tr><td class="white"></td><td class="black"></td><td class="white"></td></tr>

            </table>

        </div>
        <div class="col-md-11" style="background-color: white">
            <?php
            foreach ($control->viewActiveUsers() as $row)
            {
                $username=$row["username"];
                $mydate=date( 'Y-m' );

                $claims_value=$control->viewClaimValue($mydate,"username=:username",$username);
                $savings=$control->viewMonthlySavings($mydate,"username=:username",$username);
                $perc=$claims_value>0?(int)round(($savings/$claims_value)*100):0;
                $alignperc=$perc*10;
                $nummove=$alignperc+100;
                $horsemove="margin-left:".$alignperc."px;";
                $textmove="margin-left:".$nummove."px;";
                echo "<div class=\"track $username\">
        <a href=\"\" style=\"color: #fff\" uk-icon=\"icon:chevron-double-right; ratio: 3\">
        <span class=\"pos1\" style=\"$textmove\">$perc</span></a>
        <img src=\"images/x1.gif\" style=\"$horsemove\">
        <span class=\"text\">$username</span>
        <span class=\"boundary\"></span>
    </div>";
            }

            ?>
        </div>


        <div class="outer">
            <span style="color: white; margin-left: 100px">0%</span>
            <span style="color: white; margin-right: 11%; float: right">100%</span>
        </div>
    </div>
</div>