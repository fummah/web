<?php
session_start();
define("access",true);
require "classes/controls.php";
include ("templates/claim_templates.php");
$control=new controls();
include ("header.php");
$role=$control->myRole();
$username=$control->loggedAs();
$arr_purple_username=array();
$arr_red_username=array();
$arr_orange_username=array();
$arr_green_username=array();

$arr_purple_client=array();
$arr_red_client=array();
$arr_orange_client=array();
$arr_green_client=array();
$sla_req=isset($_GET["sla"])?$_GET["sla"]:"";
?>
<title>
    MCA | Open Claims
</title>
<link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.css">
<script type="text/javascript" src="js/datatables.min.js"></script>
<style>
    .purple{background-color: purple}
    .red{background-color: red}
    .orange{background-color: orange}
    .green{background-color: lightgreen}
</style>

<body><br><br>
<div class="row" style="border: 1px solid #54bf99; width: 95%; margin-left: auto;margin-right: auto; position: relative;padding: 10px">
    <div class="col-md-12">

        <div style="width: 40%; margin-left: auto; margin-right: auto; position: relative; padding: 5px; border: 1px solid whitesmoke">
            <span style="color: #54bc9c;">Open Claims</span> <span uk-icon="chevron-double-right"></span>
            <?php
            if($sla_req=="" || $sla_req=="purple")
            {
                ?>
                <div class="uk-inline">
                    <button class="uk-button uk-button-default" type="button" style="background-color: purple;color: white; border-radius: 20px" onclick="color('~')"><span id="purple_num">0</span> <span uk-icon="chevron-down"></span></button>
                    <div uk-dropdown>
                        <ul uk-accordion='collapsible: false'>
                            <li><a class='uk-accordion-title' href='#'>User(s)</a><div class='uk-accordion-content'><span id='bas11'></span></div></li>
                            <li><a class='uk-accordion-title' href='#'>Clients</a><div class='uk-accordion-content'><span id='bas1'></span></div></li>
                        </ul>
                    </div>
                </div>
                <?php
            }
            if ($sla_req == "" || $sla_req == "red")
            {
                ?>
                <div class="uk-inline">
                    <button class="uk-button uk-button-default" style="background-color: red;color: white;border-radius: 20px" type="button" onclick="color('^')"><span id="red_num">0</span> <span uk-icon="chevron-down"></span></button>
                    <div uk-dropdown>
                        <ul uk-accordion='collapsible: false'>
                            <li><a class='uk-accordion-title' href='#'>User(s)</a><div class='uk-accordion-content'><span id='bas21'></span></div></li>
                            <li><a class='uk-accordion-title' href='#'>Clients</a><div class='uk-accordion-content'><span id='bas2'></span></div></li>
                        </ul>
                    </div>
                </div>
                <?php
            }
            if ($sla_req == "" || $sla_req == "orange")
            {
                ?>
                <div class="uk-inline">
                    <button class="uk-button uk-button-default" style="background-color: orange;color: white;border-radius: 20px" type="button" onclick="color('*')"><span id="orange_num">0</span> <span uk-icon="chevron-down"></span></button>
                    <div uk-dropdown>
                        <ul uk-accordion='collapsible: false'>
                            <li><a class='uk-accordion-title' href='#'>User(s)</a><div class='uk-accordion-content'><span id='bas31'></span></div></li>
                            <li><a class='uk-accordion-title' href='#'>Clients</a><div class='uk-accordion-content'><span id='bas3'></span></div></li>
                        </ul>
                    </div>
                </div>
                <?php
            }
            if ($sla_req == "" || $sla_req == "green")
            {
                ?>
                <div class="uk-inline">
                    <button class="uk-button uk-button-default" style="background-color: lightgreen;color: white;border-radius: 20px" type="button" onclick="color('#')"><span id="green_num">0</span> <span uk-icon="chevron-down"></span></button>
                    <div uk-dropdown>
                        <ul uk-accordion='collapsible: false'>
                            <li><a class='uk-accordion-title' href='#'>User(s)</a><div class='uk-accordion-content'><span id='bas41'></span></div></li>
                            <li><a class='uk-accordion-title' href='#'>Clients</a><div class='uk-accordion-content'><span id='bas4'></span></div></li>
                        </ul>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <table id="example" class="striped" style="width:100%">

            <thead>
            <tr>
                <th>Name</th>
                <th>Claim Number</th>
                <th>SLA Days</th>
                <th>Note Date</th>
                <th>Days Open</th>
                <th>Owner</th>
<th>Scheme</th>
                <th>Client</th>
                <th>PMB?</th>
                <?php
                if($control->isInternal()) {
                    ?>
                    <th>Edit</th>
                    <?php
                }
                ?>
                <th>View</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $val=1;
            $condition=":username";
            if($control->isClaimsSpecialist())
            {
                $condition="username=:username";
                $val=$control->loggedAs();
            }
            elseif ($control->isGapCover())
            {
                $condition="c.client_name=:username";
                $val=$control->loggedAs();
            }
            $nonotes_array=$control->viewNoNotesClaims($condition,$val);
            $notes_array=$control->viewNotesClaims($condition,$val);
            $all_array=array_merge($nonotes_array,$notes_array);
            $purple_arr=array();
            $red_arr=array();
            $orange_arr=array();
            $green_arr=array();
            $all_array_sort=asort($all_array);
            foreach ($all_array as $row)
            {
                $claim_id=$row["claim_id"];
                $date_entered=$row["date_entered"];
                $status_type=$row["status_type"];
                $user=$row["username"];
                $client=$row["client_name"];
             
             $date_closed=$row["date_closed"]!== null?$row["date_closed"]:"";
            $date_reopened=$row["date_reopened"]!== null?$row["date_reopened"]:"";
                $descr=$row["descr"];

                if(strlen($date_reopened)<2 && strlen($date_closed)>10)
                {
                    $dat0=$control->viewClaimDate($claim_id,$date_reopened,$date_entered);
                    $date_entered=$date_entered>$dat0?$date_entered:$dat0;
                }
                $from_date1 = date('Y-m-d', strtotime($date_entered));
                $days=round($control->getWorkingDays($from_date1,$control->todayDate(),$control->holidays()));
                $arr=array("date_entered"=>$date_entered,"claim_id"=>$claim_id,"days"=>$days,"notes"=>$status_type,"descr"=>$descr);
                $sla=0;
                if($days>2 && $status_type == "No_Notes")
                {
                    array_push($purple_arr,$arr);
                    array_push($arr_purple_username,$user);
                    array_push($arr_purple_client,$client);
                    $sla=1;
                }
                elseif($days>2)
                {
                    array_push($red_arr,$arr);
                    array_push($arr_red_username,$user);
                    array_push($arr_red_client,$client);
                    $sla=1;
                }
                elseif($days==2 && $status_type == "No_Notes")
                {
                    array_push($red_arr,$arr);
                    array_push($arr_red_username,$user);
                    array_push($arr_red_client,$client);
                    $sla=1;
                }

                elseif ($days==2)
                {
                    array_push($orange_arr,$arr);
                    array_push($arr_orange_username,$user);
                    array_push($arr_orange_client,$client);
                }
                else
                {
                    array_push($green_arr,$arr);
                    array_push($arr_green_username,$user);
                    array_push($arr_green_client,$client);
                }
                if($sla==1)
                {
                    $control->callUpdateClaimKey($claim_id,"sla",2," AND sla<>1");
                }

            }
            $count_purple=count($purple_arr);
            $count_red=count($red_arr);
            $count_orange=count($orange_arr);
            $count_green=count($green_arr);
            //echo $count_purple."-".$count_red."-".$count_orange."-".$count_green;
            if($sla_req=="" || $sla_req=="purple")
            {
                $control->displaySLA($purple_arr,"~","purple");
            }
            if($sla_req=="" || $sla_req=="red")
            {
                $control->displaySLA($red_arr,"^","red");
            }
            if($sla_req=="" || $sla_req=="orange")
            {
                $control->displaySLA($orange_arr,"*","orange");
            }
            if($sla_req=="" || $sla_req=="green")
            {
                $control->displaySLA($green_arr,"#","limegreen");
            }

            ?>
            </tbody>
        </table>

        <?php
        $xall=array($control->rearrageArray($arr_purple_username));
        array_push($xall,$control->rearrageArray($arr_red_username));
        array_push($xall,$control->rearrageArray($arr_orange_username));
        array_push($xall,$control->rearrageArray($arr_green_username));

        $xall1=array($control->rearrageArray($arr_purple_client));
        array_push($xall1,$control->rearrageArray($arr_red_client));
        array_push($xall1,$control->rearrageArray($arr_orange_client));
        array_push($xall1,$control->rearrageArray($arr_green_client));

        $da=json_encode($xall,true);
        $da1=json_encode($xall1,true);
        echo "<input type='hidden' id='purple1' value='$count_purple'><input type='hidden' id='red1' value='$count_red'><input type='hidden' id='orange1' value='$count_orange'><input type='hidden' id='green1' value='$count_green'><textarea style='display:none' id='arrall'>$da</textarea><textarea style='display:none' id='arrall1'>$da1</textarea>";

        ?>
    </div>
</div>
</body>
<?php
include "footer.php";
escalation();
?>


<script type="text/javascript">
    $(document).ready(function() {

        var  red1=$("#red1").val();
        var  orange1=$("#orange1").val();
        var  purple1=$("#purple1").val();
        var  green1=$("#green1").val();
        var  arrall=$("#arrall").val();
        var  arrall1=$("#arrall1").val();

        var obj = JSON.parse(arrall);
        var obj1 = JSON.parse(arrall1);
        var key1="~";
        var key2="^";
        var key3="*";
        var key4="#";

        for (var i in obj) {


            if(i==0)
            {
                for (var j in obj[i])
                {
                    var txt=obj[i][j].a;
                    $("#bas11").append("<span onclick=\"checkHere('"+txt+"','"+key1+"',5)\" style='cursor:pointer'><span class='uk-badge purple'>"+obj[i][j].num+"</span> : "+txt+"</span><br>");
                }

                for (var p in obj1[i])
                {
                    var txt=obj1[i][p].a;
                    $("#bas1").append("<span onclick=\"checkHere('"+txt+"','"+key1+"',6)\" style='cursor:pointer'><span class='uk-badge purple'>"+obj1[i][p].num+"</span> : "+txt+"</span><br>");
                }


            }
            if(i==1)
            {
                for (var j in obj[i])
                {
                    var txt=obj[i][j].a;
                    $("#bas21").append("<span onclick=\"checkHere('"+txt+"','"+key2+"',5)\" style='cursor:pointer'><span class='uk-badge red'>"+obj[i][j].num+"</span> : "+txt+"</span><br>");
                }
                for (var p in obj1[i])
                {
                    var txt=obj1[i][p].a;
                    $("#bas2").append("<span onclick=\"checkHere('"+txt+"','"+key2+"',6)\" style='cursor:pointer'><span class='uk-badge red'>"+obj1[i][p].num+"</span> : "+txt+"</span><br>");
                }
            }
            if(i==2)
            {
                for (var j in obj[i])
                {
                    var txt=obj[i][j].a;
                    $("#bas31").append("<span onclick=\"checkHere('"+txt+"','"+key3+"',5)\" style='cursor:pointer'><span class='uk-badge orange'>"+obj[i][j].num+"</span> : "+txt+"</span><br>");
                }
                for (var p in obj1[i])
                {
                    var txt=obj1[i][p].a;
                    $("#bas3").append("<span onclick=\"checkHere('"+txt+"','"+key3+"',6)\" style='cursor:pointer'><span class='uk-badge orange'>"+obj1[i][p].num+"</span> : "+txt+"</span><br>");
                }
            }
            if(i==3)
            {
                for (var j in obj[i])
                {
                    var txt=obj[i][j].a;
                    $("#bas41").append("<span onclick=\"checkHere('"+txt+"','"+key4+"',5)\" style='cursor:pointer'><span class='uk-badge green'>"+obj[i][j].num+"</span> : "+txt+"</span><br>");
                }
                for (var p in obj1[i])
                {
                    var txt=obj1[i][p].a;
                    $("#bas4").append("<span onclick=\"checkHere('"+txt+"','"+key4+"',6)\" style='cursor:pointer'><span class='uk-badge green'>"+obj1[i][p].num+"</span> : "+txt+"</span><br>");
                }
            }
        }

        $("#purple_num").text(purple1);
        $("#red_num").text(red1);
        $("#orange_num").text(orange1);
        $("#green_num").text(green1);
        $('#example').DataTable({"order": [[ 2, 'desc' ],[ 3, 'asc' ]]});
        $('.escl').formSelect();
    } );
    function checkHere(txt,ke,col)
    {
        var table = $('#example').DataTable();
        table.search('').draw();
        table.column(5).search('').draw();
        table.column(6).search('').draw();
        table.search(ke).draw();
        table.column(col).search(txt).draw();
//table.order( [[ 3, 'desc' ]] ).draw();
    }
    function color(txt)
    {
        var table = $('#example').DataTable();
        table.search('').draw();
        table.column(5).search('').draw();
        table.column(6).search('').draw();
        table.search(txt).draw();
//table.order( [[ 3, 'desc' ]] ).draw();
    }
</script>
