<?php
session_start();
error_reporting(0);
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
    <style type="text/css">
        <!--
        .tab { margin-left: 10px; }
        .linkButton {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;
        }
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
            border-color: #bce8f1;
            box-shadow: 0px 0px 70px #bce8f1;
        }
        #download{
            font-weight: bolder;
            padding: 10px;
            background-color: #fff;
            border-color:#00b3ee ;
            color: #00b3ee;
        }
        #download:hover{
            background-color: #449d44;
            color:#fff;
            border-color: #fff ;
        }

        .highlight {
            background-color: #fff34d;
            -moz-border-radius: 5px; /* FF1+ */
            -webkit-border-radius: 5px; /* Saf3-4 */
            border-radius: 5px; /* Opera 10.5, IE 9, Saf5, Chrome */
            -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* FF3.5+ */
            -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* Saf3.0+, Chrome */
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); /* Opera 10.5+, IE 9.0 */
        }

        .highlight {
            padding:1px 4px;
            margin:0 -4px;
        }
        p .sep:not(:first-child){
            border-left:1px solid #000;

        }

        p{
            padding:5px 0;
            margin:0;
        }

        .sep{
            padding:0 5px;
            color: #00b3ee;
            font-size: 20px;
        }

    </style>
    <script>
        function closedCases()
        {
            $('#myH').html("<h1 style='color: red'>Loading Please wait</h1>");
            $.ajax({
                url:"closedCases.php",
                type:"GET",
                success:function(data)
                {

                    $('#myH').html(data);

                },
                error:function(jqXHR, exception)
                {
                    alert("Error");
                }
            });
        }
        function loadNow() {
            location.href = "case_search.php";
        }
  function trydwnload() {

            event.preventDefault();
        }
    </script>
    <title>Med ClaimAssist: Case Search</title>
</head>

<body>
<?php

include("header.php");
include_once ("dbconn.php");
$username=$_SESSION['user_id'];
$vl="";
if(isset($_POST['btn']))
{
    $vl=validateXss($_POST['search']);

}
?>
<div  style='border-style: solid; border-color: #00b3ee; border-width: 2px;'>
<br><br><br>
<form class="tab" action="download_cases.php" method="post" style="border-bottom: groove;border-bottom-color:deepskyblue">
    <input style="display: none" type="text" id="search1" name="search" value="<?php echo $vl; ?>">
    <p >
        <span class="sep"><input type="radio" name="rad" value="1" onclick="loadNow()" checked>All Cases</span>
        <span class="sep"><input type="radio" name="rad" value="2">Open Cases</span>
        <span class="sep"><input type="radio" name="rad" value="3" onclick="closedCases()">Closed Cases</span>
        <span class="sep"><input type="radio" name="rad" value="4">PMB Cases</span>
        <span class="sep"><input type="radio" name="rad" value="5">Non-PMB Cases</span>
   <?php
     
            if($_SESSION["gap_admin"]=="admin")
            {
            ?>
            <button type="submit" style="float: right"  id="download" uk-toggle="target: #my_clients" class="btn btn-info" uk-icon="download" onclick="trydwnload()">Report</button>
            <?php
            }
            ?>
        <button type="submit" style="float: right" name="download" id="download" class="btn btn-info">View in Excel</button>
    </p>
</form>
<form class="tab" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
    <div id="ss">

        <b><input type="text" id="search" name="search" value="<?php echo $vl; ?>" placeholder="Search for any case..." REQUIRED><b>
                <button name="btn" data-toggle="tooltip" data-placement="top" title="start searching" class="btn btn-info"><span class="glyphicon glyphicon-ok-sign"></span> Search</button>  <a href="case_search.php" data-toggle="tooltip" title="Refresh"><span class="glyphicon glyphicon-refresh" style="color:#5BBCDE"></span></a>
    </div>
</form>
<?php
$mnarr=[];
for($x=11; $x>=0;$x--){
    $datt= date('Y-m', strtotime(date('Y-m')." -" . $x . " month"));
    array_push($mnarr,$datt);
}
$zarr=array_reverse($mnarr);
?>
    <div id="my_clients" uk-modal>

        <div class="uk-modal-dialog uk-modal-body">
            <h2 class="uk-modal-title"><hr class="uk-divider-icon"></h2>
            <form action="classes/downloadClass.php" method="post">
                <div class="row">
                    <div class="col-lg-6">From Month : <select class="uk-select" name="from_client">
                            <?php
                          for($i=0;$i<count($zarr);$i++)
                        {
                            $newdate=$zarr[$i];
                            echo "<option value='$newdate'>$newdate</option>";
                        }
                            ?>
                        </select></div>
                    <div class="col-lg-6">To Month :
                        <select class="uk-select" name="to_client">
                        <?php
                    for($i=0;$i<count($zarr);$i++)
                        {
                            $newdate=$zarr[$i];
                            echo "<option value='$newdate'>$newdate</option>";
                        }
                        ?>
                        </select></div>
                </div>
  <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                    <label><input class="uk-radio" type="radio" name="status" value="0" checked> Closed</label>
                    <label><input class="uk-radio" type="radio" name="status" value="1"> Open</label>
                </div>
                <hr>
                <p align="center">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" name="claims_client" type="submit">Download</button>

                </p>
            </form>
        </div>

    </div>
<div id="myH">
    <?php
    include('classMainSearch.php');
    if(isset($_POST['btn']))
    {
        $vl=validateXss($_POST['search']);
        $_SESSION['term']=$vl;
        searchFunction($vl);
    }
    else
    {
        default1();
    }


    ?>
    <hr>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>
    <?php
    include('footer.php');
    ?>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js
"></script>
    <script type="text/javascript" src="js/highlight.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#search').bind('keyup change', function(ev) {
                // pull in the new value
                var searchTerm = $('#search').val();

                // remove any old highlighted terms
                $('#myH').removeHighlight();

                // disable highlighting if empty
                if ( searchTerm ) {
                    // highlight the new term
                    $('#myH').highlight( searchTerm );
                }
            });

            $(document).ready(function(ev) {
                // pull in the new value
                var searchTerm = $('#search').val();

                // remove any old highlighted terms
                $('#myH').removeHighlight();

                // disable highlighting if empty
                if ( searchTerm ) {
                    // highlight the new term
                    $('#myH').highlight( searchTerm );
                }
            });
        });
    </script>
</div>
</div>
</body>