<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$_SESSION["admin_main"]=true;
if(!isset($_POST["claim_id"]))
{
    die("Error");
}
$mail = new PHPMailer(true);
require_once "dbconn.php";
$conn=connection("mca","MCA_admin");
require_once ("classes/leadClass.php");
$obj=new leadClass();

$claim_id=(int)$_POST["claim_id"];

$arr1=$obj->getmemberDetails($claim_id);
//start of default fields
$name=$arr1[0]." ".$arr1[1];
$claim_number=$arr1[2];
$policy_number=$arr1[3];
$quality=(int)$arr1[5];
$assessment_score="0";
$date=date("Y-m-d H:i:s");
$hid1="";
$hid2="hidden";
//end of default fields
//section1
$data1="";$data2="";$data3="";$data4="";$data5="";$sla17="";$data7="";$sla19="";$data9="";
//end
$sla1="";$sla2="";$sla3="";$sla4="";$sla5="";$sla6="";$sla7="";$sla8="";$sla9="";$sla10="";$sla11="";$sla12="";$sla13="";$sla14="";$sla15="";
//section2
$sla16="";$auto2="";$auto3="";$sla18="";$auto5="";$sla20="";$sla21="";$sla22="";$auto9="";$auto10="";$auto11="";
//end
//section2
$calls1="";$calls2="";$calls3="";$calls4="";$calls5="";$calls6="";$calls7="";$calls8="";$calls9="";$calls10="";$calls11="";
//
$emails1="";$emails2="";$emails3="";$emails4="";$emails5="";$emails6="";$emails7="";$emails8="";$emails9="";$emails10="";
//end
$qa_signed="";$cs_signed="";$cs_date="";

?>
<html>
<head>

    <title>MCA : Quality Assurance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <link href="w3/w3.css" rel="stylesheet" />
    <link rel="stylesheet" href="uikit/css/uikit.min.css" />
    <script src="uikit/js/uikit.min.js"></script>
    <script src="uikit/js/uikit-icons.min.js"></script>
    <link rel="stylesheet" href="js/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>

    <script>
        function addNote() {

            var notes= $("#mynotes").val();
            var author="<?php echo $_SESSION["user_id"];?>";
            var mytime=currentDate();
            var claim_id=$("#claim_id").val();

            if(notes!="") {

                var obj={

                    claim_id:claim_id,
                    author:author,
                    notes:notes
                };
                $.ajax({
                    url:"ajaxPhp/woo_user.php?id=2",
                    type:"GET",
                    data:obj,
                    success:function(data){

                        if(data==1)
                        {
                            $("#artsec").append("<article class=\"uk-comment\"><header class=\"uk-comment-header\"><div class=\"uk-grid-medium uk-flex-middle\" uk-grid><div class=\"uk-width-expand\">" +
                                "<ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">" +
                                "<li><a href=\"#\" id=\"mytime\">" + mytime + "</a></li><li><a href=\"#\" id=\"mytime\">" + author + "</a></li></ul></div></div></header><div class=\"uk-comment-body\"><p>" + notes + "</p></div></article><hr>");

                            $("#mynotes").val("");
                        }
                        else
                        {
                            alert("Failed to update");
                        }

                    },
                    error:function(jqXHR, exception)
                    {
                        alert("ErrorTest");
                        alert(jqXHR.responseText);
                    }
                });


            }

        }

        function currentDate()
        {
            var currentTime = new Date();
            hour = currentTime.getHours();
            min = currentTime.getMinutes();
            mon = currentTime.getMonth() + 1;
            day = currentTime.getDate();
            year = currentTime.getFullYear();
            if (mon.toString().length == 1) {
                var mon = '0' + mon;
            }
            if (day.toString().length == 1) {
                var day = '0' + day;
            }
            if (hour.toString().length == 1) {
                var hour = '0' + hour;
            }
            if (min.toString().length == 1) {
                var min = '0' + min;
            }

            var gg = year + "-" + mon + "-" + day + " " + hour + ":" + min;

            return gg;
        }
        function update() {
            var upd=0;
            var x = document.getElementById("upd").checked;
            var claim_id=$("#claim_id").val();

            if (x) {
                upd = 1;
            }
            var obj = {
                claim_id: claim_id,
                upd: upd
            };
            $.ajax({
                url: "ajaxPhp/woo_user.php?id=3",
                type: "GET",
                data: obj,
                success: function (data) {
                    alert(data);

                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseText);
                }
            });

        }
    </script>

</head>

<body>
<?php
include("header.php");
echo "<br><br><br><br>";
$totalass=0;
$datatot=0;$slatot=0;$autotot=0;$emailstot=0;$callstot=0;
if(isset($_POST["btnsave"]) || isset($_POST["btndraft"]))
{
    $entered_by=$_SESSION["user_id"];
    $assessment_score=(double)$_POST["assessment_score"];
    if($obj->insertQuality($claim_id,$entered_by,$assessment_score,$_POST["data1"],$_POST["data2"],$_POST["data3"],$_POST["data4"],$_POST["data5"],$_POST["sla17"],$_POST["data7"],$_POST["sla19"],$_POST["data9"],$_POST["sla16"],$_POST["auto2"],$_POST["auto3"],$_POST["sla18"],$_POST["auto5"],$_POST["sla20"],$_POST["sla21"],$_POST["sla22"],$_POST["auto9"],$_POST["auto10"],$_POST["calls1"],$_POST["calls2"],$_POST["calls3"],$_POST["calls4"],$_POST["calls5"],$_POST["calls6"],$_POST["calls7"],$_POST["calls8"],$_POST["calls9"],$_POST["calls10"],$_POST["calls11"],$_POST["sla1"],$_POST["sla2"],$_POST["sla3"],$_POST["sla4"],$_POST["sla5"],$_POST["sla6"],$_POST["sla7"],$_POST["sla8"],$_POST["sla9"],$_POST["sla10"],$_POST["sla11"],$_POST["sla12"],$_POST["sla13"],$_POST["sla14"],$_POST["sla15"],$_POST["emails1"],$_POST["emails2"],$_POST["emails3"],$_POST["emails4"],$_POST["emails5"],$_POST["emails6"],$_POST["emails7"],$_POST["emails8"],$_POST["emails9"],$_POST["emails10"]))
    {
        $sst="Draft Successfully Saved.";
        if(isset($_POST["btnsave"]))
        {
            $sst="Record Successfully Saved.";
            $email=$obj->getUserEmail($claim_id);
            $subject="New QA claim -- ".$obj->getClaimNumber($claim_id);
            $body="New QA claim is ready for you<br><br>MCA Mailer";
            $obj->updateQuality($claim_id);
            $obj->sendMail1($email,$subject,$body);
        }

        echo "<div class=\"uk-alert-success\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p align='center'>$sst</p>
</div>";

    }
    else{
        echo "<div class=\"uk-alert-danger\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p align='center'>Failed to load.</p>
</div>";
    }
}
$arr=$obj->getQualityDetails($claim_id);
if(count($arr)>0)
{

    $hid2="";
    $assessment_score=$arr["assessment_score"];
    $date=$arr["date_entered"];
    $data1=$arr["data1"];$data2=$arr["data2"];$data3=$arr["data3"];$data4=$arr["data4"];$data5=$arr["data5"];$sla17=$arr["sla17"];$data7=$arr["data7"];$sla19=$arr["sla19"];$data9=$arr["data9"];
    $datatot=$obj->calcVal("data",5,$arr);
    //end
    $sla1=$arr["sla1"];$sla2=$arr["sla2"];$sla3=$arr["sla3"];$sla4=$arr["sla4"];$sla5=$arr["sla5"];$sla6=$arr["sla6"];$sla7=$arr["sla7"];$sla8=$arr["sla8"];$sla9=$arr["sla9"];$sla10=$arr["sla10"];$sla11=$arr["sla11"];$sla12=$arr["sla12"];$sla13=$arr["sla13"];$sla14=$arr["sla14"];$sla15=$arr["sla15"];$sla16=$arr["sla16"];$sla20=$arr["sla20"];$sla21=$arr["sla21"];$sla22=$arr["sla22"];
    $slatot=$obj->calcVal("sla",22,$arr);
    //section2
    $auto2=$arr["auto2"];$auto3=$arr["auto3"];$sla18=$arr["sla18"];$auto5=$arr["auto5"];$auto9=$arr["auto9"];$auto10=$arr["auto10"];
    //$autotot=$obj->calcVal("auto",9,$arr);
    //end
    $emails1=$arr["emails1"];$emails2=$arr["emails2"];$emails3=$arr["emails3"];$emails4=$arr["emails4"];$emails5=$arr["emails5"];$emails6=$arr["emails6"];$emails7=$arr["emails7"];$emails8=$arr["emails8"];$emails9=$arr["emails9"];$emails10=$arr["emails10"];
    $emailstot=$obj->calcVal("emails",9,$arr);
//section2
    $calls1=$arr["calls1"];$calls2=$arr["calls2"];$calls3=$arr["calls3"];$calls4=$arr["calls4"];$calls5=$arr["calls5"];$calls6=$arr["calls6"];$calls7=$arr["calls7"];$calls8=$arr["calls8"];$calls9=$arr["calls9"];$calls10=$arr["calls10"];$calls11=$arr["calls11"];
    $callstot=$obj->calcVal("calls",10,$arr);
    //end
    $qa_signed=$arr["qa_signed"];$cs_signed=$arr["cs_signed"];$cs_date=$arr["cs_date"];

    $totalass=$datatot+$slatot+$emailstot+$callstot;
}
$percdata=(double)($datatot/5)*100;
$percsla=(double)($slatot/21)*100;
$percauto="";
$callsOg=(5*$obj->calls_total);
$emailsOg=5*$obj->emails_total;
$originalTot=5+21+$callsOg+$emailsOg;
$percemail=(int)(($emailstot/$emailsOg)*100);
$perccall=(int)(($callstot/$callsOg)*100);
$arrayy = array($emailsOg,$callsOg);
$valsy = array_count_values($arrayy);
$rtrre=isset($valsy[0])?$valsy[0]:0;
$avr=4-$rtrre;
$perc=round(($percdata+$percsla+$percemail+$perccall)/$avr);
$percdata=round($percdata);
$percsla=round($percsla);
$percauto="";
$percemail=round($percemail);
$perccall=round($perccall);
$class1="";

$results="-------";
if($totalass>0)
{
    $results=$perc>=80 && $slatot==21?"Passed":"Failed";
    $class1=$perc>=80 && $slatot==21?"uk-alert-success":"uk-alert-danger";
    $obj->updateScore($claim_id,$perc,$results);
}

if(($qa_signed=="1" && $cs_signed=="1") || $_SESSION['level']=="claims_specialist")
{
    $hid1="hidden";
}

$userhere=array_unique($obj->getQAUsers($claim_id));
$uepipe= implode( ' | ', $userhere );
?>

<div class="container uk-background-muted">
    <form action="" method="post">
        <input type="hidden" name="claim_id" id="claim_id" value="<?php echo $claim_id;?>">
        <table class="uk-table uk-table-small uk-table-divider" style="width: 60%">
            <caption><h3 class="uk-text"><u>QUALITY ASSESSMENT FORM</u></h3></caption>

            <tbody>
            <tr>
                <td>Name</td>
                <td><input type="text" class="uk-input uk-form-small" name="name" value="<?php echo $name;?>"></td>
            </tr>
            <tr>
                <td>Assessment Date</td>
                <td><input type="text" class="uk-input uk-form-small" name="assessment_date" value="<?php echo $date;?>"></td>
            </tr>
            <tr>
                <td>Claim number</td>
                <td><input type="text" class="uk-input uk-form-small" name="claim_number" value="<?php echo $claim_number;?>"></td>
            </tr>
            <tr>
                <td>GAP policy holder number</td>
                <td><input type="text" class="uk-input uk-form-small" name="policy_number" value="<?php echo $policy_number;?>"></td>
            </tr>
            <tr>
                <td>Total Assessment score</td>
                <td><input type="hidden" class="uk-input uk-form-small" name="assessment_score" value="<?php echo $perc;?>"><span class="uk-badge"><?php echo $totalass;?></span> / <span class="uk-badge"><?php echo $originalTot;?></span></td>
            </tr>
            <tr>
                <td>
                    <span class="uk-badge" style="background-color: lightgrey"><?php echo $perc;?>%</span> <span class="<?php echo $class1;?>"><?php echo $results;?></span>
                </td>
            </tr>
            </tbody>
        </table>
        <hr class="uk-divider-icon">
        <table class="uk-table uk-table-large uk-table-divider">
            <caption><h5 class="uk-text-muted"><span class="uk-text-danger">**</span> Scores are Yes / No / N/A (weightings apply)</h5></caption>

            <tbody>
            <tr>
                <td><b>DATA CAPTURING</b> <span class="uk-badge" style="background-color: lightgrey"><?php echo $datatot;?></span> / <span class="uk-badge" style="background-color: lightgrey">5</span> <span class="uk-badge" style="background-color: red"><?php echo $percdata;?>%</span> | <?php echo $uepipe;?></td>
                <td><b>Yes</b></td>
                <td><b>No</b></td>
                <td><b>N/A</b></td>
                <td></td>
            </tr>
            <tr>
                <td>The member detail fields are completed</td>
                <td><input class="uk-radio" type="radio" value="1" name="data1" <?php echo ($data1=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="data1" <?php echo ($data1=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="data1" <?php echo ($data1=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>The correct dependant is chosen</td>
                <td><input class="uk-radio" type="radio" value="1" name="data2" <?php echo ($data2=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="data2" <?php echo ($data2=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="data2" <?php echo ($data2=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>All the treating doctors are captured</td>
                <td><input class="uk-radio" type="radio" value="1" name="data3" <?php echo ($data3=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="data3" <?php echo ($data3=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="data3" <?php echo ($data3=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>The Charged amount, Scheme paid amount and Member portions are captured and correct?</td>
                <td><input class="uk-radio" type="radio" value="1" name="data4" <?php echo ($data4=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="data4" <?php echo ($data4=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="data4" <?php echo ($data4=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>

            <tr>
                <td>The treatment code and ICD10 codes complement one another</td>
                <td><input class="uk-radio" type="radio" value="1" name="data5" <?php echo ($data5=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="data5" <?php echo ($data5=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="data5" <?php echo ($data5=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>

            <tr style="display: none">
                <td>The notes are accurate and factual</td>
                <td><input class="uk-radio" type="radio" value="1" name="data7" <?php echo ($data7=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="data7" <?php echo ($data7=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="data7" <?php echo ($data7=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>

            <tr>
                <td colspan="4">
                    <span class="uk-text-danger">Comments:</span>
                    <div class="uk-margin">
                        <textarea class="uk-textarea" name="data9" rows="5" placeholder=""><?php echo $data9;?></textarea>
                    </div>
                </td>

            </tr>

            </tbody>
        </table>
        <hr class="uk-divider-icon">
        <table class="uk-table uk-table-large uk-table-divider">
            <caption><h5 class="uk-text-muted"><span class="uk-text-danger">**</span> Please note: a no captured on any of the sla fails listed below will result in an automatic fail of your quality assessment.</h5></caption>

            <tbody>
            <tr>
                <td><b>SLA & VALIDATIONS / AUTO FAILS</b> <span class="uk-badge" style="background-color: lightgrey"><?php echo $slatot;?></span> / <span class="uk-badge" style="background-color: lightgrey">21</span> <span class="uk-badge" style="background-color: red"><?php echo $percsla;?>%</span></td>
                <td><b>Yes</b></td>
                <td><b>No</b></td>
                <td><b>N/A</b></td>
                <td></td>
            </tr>
            <tr>
                <td>Provider details were updated  and Indicated whether provider does give discounts (where applicable)</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla1" <?php echo ($sla1=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla1" <?php echo ($sla1=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla1" <?php echo ($sla1=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>The Primary ICD10 code is captured</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla16" <?php echo ($sla16=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla16" <?php echo ($sla16=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla16" <?php echo ($sla16=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>The Treatment is correctly identified as PMB or Non-PMB / Emergency or Non-Emergency (Planned/Pre-booked)</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla2" <?php echo ($sla2=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla2" <?php echo ($sla2=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla2" <?php echo ($sla2=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>CS indicated the type of procedure performed</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla3" <?php echo ($sla3=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla3" <?php echo ($sla3=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla3" <?php echo ($sla3=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>In Emergency, consent was sent to Member (where necessary)</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla4" <?php echo ($sla4=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla4" <?php echo ($sla4=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla4" <?php echo ($sla4=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>

            <tr>
                <td>Consent form received from Member is Uploaded to MCA system</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla5" <?php echo ($sla5=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla5" <?php echo ($sla5=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla5" <?php echo ($sla5=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>Regular updates were given to Member/Broker? Was it done timeously?</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla6" <?php echo ($sla6=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla6" <?php echo ($sla6=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla6" <?php echo ($sla6=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>The notes are clear, accurate and factual</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla17" <?php echo ($sla17=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla17" <?php echo ($sla17=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla17" <?php echo ($sla17=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>No savings opportunity was missed</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla18" <?php echo ($sla18=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla18" <?php echo ($sla18=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla18" <?php echo ($sla18=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>Correct payment instruction given to Client </td>
                <td><input class="uk-radio" type="radio" value="1" name="sla19" <?php echo ($sla19=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla19" <?php echo ($sla19=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla19" <?php echo ($sla19=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>Validations were done</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla7" <?php echo ($sla7=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla7" <?php echo ($sla7=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla7" <?php echo ($sla7=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>New files checked</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla8" <?php echo ($sla8=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla8" <?php echo ($sla8=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla8" <?php echo ($sla8=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>Zero amounts updated</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla9" <?php echo ($sla9=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla9" <?php echo ($sla9=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla9" <?php echo ($sla9=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>

            <tr>
                <td>ZESTLIFE: Claim documents Uploaded to MCA system</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla10" <?php echo ($sla10=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla10" <?php echo ($sla10=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla10" <?php echo ($sla10=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>Provider Indicator used (stand on provider when making note/ add savings)</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla11" <?php echo ($sla11=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla11" <?php echo ($sla11=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla11" <?php echo ($sla11=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>MAGPI: Was claim assessed correctly in respect of Claims Actions (eg. PMB / MSP / PMB, Continue with MSP)</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla12" <?php echo ($sla12=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla12" <?php echo ($sla12=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla12" <?php echo ($sla12=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>MAGPI: Was provider banking details updated in Housekeeping</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla13" <?php echo ($sla13=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla13" <?php echo ($sla13=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla13" <?php echo ($sla13=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>MAGPI Seamless Claims: Necessary information retrieved from Magpi</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla14" <?php echo ($sla14=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla14" <?php echo ($sla14=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla14" <?php echo ($sla14=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>Documents: Where documents were requested the notes confirms that documents were received</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla20" <?php echo ($sla20=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla20" <?php echo ($sla20=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla20" <?php echo ($sla20=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>Received documents were uploaded to the system</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla21" <?php echo ($sla21=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla21" <?php echo ($sla21=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla21" <?php echo ($sla21=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>The correct business process followed - Colours</td>
                <td><input class="uk-radio" type="radio" value="1" name="sla22" <?php echo ($sla22=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="sla22" <?php echo ($sla22=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="sla22" <?php echo ($sla22=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="uk-text-danger">Comments:</span>
                    <div class="uk-margin">
                        <textarea class="uk-textarea" name="sla15" rows="5" placeholder=""><?php echo $sla15;?></textarea>
                    </div>
                </td>

            </tr>

            </tbody>
        </table>

        <table class="uk-table uk-table-large uk-table-divider" style="display: none">
            <caption><h5 class="uk-text-muted"><span class="uk-text-danger">**</span> Please note: a no captured on any of the auto fails listed below will result in an automatic fail of your quality assessment.</h5></caption>

            <tbody>
            <tr>
                <td><b>AUTO FAILS</b> <span class="uk-badge" style="background-color: lightgrey"><?php echo $autotot;?></span> / <span class="uk-badge" style="background-color: lightgrey">9</span> <span class="uk-badge" style="background-color: red"><?php echo $percauto;?>%</span></td>
                <td><b>Yes</b></td>
                <td><b>No</b></td>
                <td><b>N/A</b></td>
                <td></td>
            </tr>

            <tr>
                <td>The treatment is correctly identified (i.e. PMB / non PMB and emergency / planned)</td>
                <td><input class="uk-radio" type="radio" value="1" name="auto2" <?php echo ($auto2=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="auto2" <?php echo ($auto2=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="auto2" <?php echo ($auto2=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td>The primary treatment code is correctly captured/identified</td>
                <td><input class="uk-radio" type="radio" value="1" name="auto3" <?php echo ($auto3=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="auto3" <?php echo ($auto3=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="auto3" <?php echo ($auto3=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>


            <tr>
                <td>The consent form was sent to the member where necessary</td>
                <td><input class="uk-radio" type="radio" value="1" name="auto5" <?php echo ($auto5=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="auto5" <?php echo ($auto5=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="auto5" <?php echo ($auto5=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>

            <tr>
                <td>All claim lines captured</td>
                <td><input class="uk-radio" type="radio" value="1" name="auto9" <?php echo ($auto9=="1") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="0" name="auto9" <?php echo ($auto9=="0") ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="auto9" <?php echo ($auto9=="2") ?  "checked" : "" ;  ?>></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="uk-text-danger">Comments:</span>
                    <div class="uk-margin">
                        <textarea class="uk-textarea" name="auto10" rows="5" placeholder=""><?php echo $auto10;?></textarea>
                    </div>
                </td>

            </tr>

            </tbody>
        </table>

        <hr class="uk-divider-icon">
        <table class="uk-table uk-table-large uk-table-divider">
            <caption><h5 class="uk-text-muted"><span class="uk-text-danger">**</span> Scores range from 1 - 5 (1/2 partially obtained: 3 done what is required: 4/5 exceeded) (weightings apply)</h5></caption>

            <tbody>
            <tr>
                <td><b>CALLS</b> <span class="uk-badge" style="background-color: lightgrey"><?php echo $callstot;?></span> / <span class="uk-badge" style="background-color: lightgrey"><?php echo $callsOg;?></span> | <span class="uk-badge" style="background-color: darkslategrey"><?php echo $obj->calls_total;?></span> | <span class="uk-badge" style="background-color: red"><?php echo $perccall;?>%</span></td>
                <td><b>1</b></td>
                <td><b>2</b></td>
                <td><b>3</b></td>
                <td><b>4</b></td>
                <td><b>5</b></td>
                <td><b>N/A</b></td>
            </tr>
            <tr>
                <td>The greeting was clear and sincere</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls1" <?php echo ($calls1== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls1" <?php echo ($calls1== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls1" <?php echo ($calls1== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls1" <?php echo ($calls1== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls1" <?php echo ($calls1== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls1" <?php echo ($calls1== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>Rapport was built with caller (i.e. medical aid scheme / doctors' rooms</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls2" <?php echo ($calls2== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls2" <?php echo ($calls2== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls2" <?php echo ($calls2== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls2" <?php echo ($calls2== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls2" <?php echo ($calls2== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls2" <?php echo ($calls2== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The CS asked appropriate questions to help fact find needs</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls3" <?php echo ($calls3== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls3" <?php echo ($calls3== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls3" <?php echo ($calls3== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls3" <?php echo ($calls3== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls3" <?php echo ($calls3== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls3" <?php echo ($calls3== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>Good listening skills were applied</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls4" <?php echo ($calls4== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls4" <?php echo ($calls4== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls4" <?php echo ($calls4== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls4" <?php echo ($calls4== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls4" <?php echo ($calls4== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls4" <?php echo ($calls4== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The CS took the correct approach with regards to his/her query</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls5" <?php echo ($calls5== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls5" <?php echo ($calls5== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls5" <?php echo ($calls5== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls5" <?php echo ($calls5== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls5" <?php echo ($calls5== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls5" <?php echo ($calls5== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The correct information was provided to the doctor's rooms / member?</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls6" <?php echo ($calls6== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls6" <?php echo ($calls6== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls6" <?php echo ($calls6== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls6" <?php echo ($calls6== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls6" <?php echo ($calls6== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls6" <?php echo ($calls6== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The CS did not interrupt the caller unnecessarily </td>
                <td><input class="uk-radio" type="radio" value="1" name="calls7" <?php echo ($calls7== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls7" <?php echo ($calls7== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls7" <?php echo ($calls7== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls7" <?php echo ($calls7== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls7" <?php echo ($calls7== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls7" <?php echo ($calls7== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The CS showed empathy where required </td>
                <td><input class="uk-radio" type="radio" value="1" name="calls8" <?php echo ($calls8== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls8" <?php echo ($calls8== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls8" <?php echo ($calls8== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls8" <?php echo ($calls8== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls8" <?php echo ($calls8== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls8" <?php echo ($calls8== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The CS maintained a level of courtesy throughout the call</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls9" <?php echo ($calls9== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls9" <?php echo ($calls9== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls9" <?php echo ($calls9== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls9" <?php echo ($calls9== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls9" <?php echo ($calls9== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls9" <?php echo ($calls9== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>A willingness to help was displayed through positive statements when necessary</td>
                <td><input class="uk-radio" type="radio" value="1" name="calls10" <?php echo ($calls10== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="calls10" <?php echo ($calls10== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="calls10" <?php echo ($calls10== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="calls10" <?php echo ($calls10== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="calls10" <?php echo ($calls10== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="calls10" <?php echo ($calls10== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td colspan="6">
                    <span class="uk-text-danger">Comments:</span>
                    <div class="uk-margin">
                        <textarea class="uk-textarea" name="calls11" rows="5" placeholder=""><?php echo $calls11;?></textarea>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <hr class="uk-divider-icon">
        <table class="uk-table uk-table-large uk-table-divider">
            <caption><h5 class="uk-text-muted"><span class="uk-text-danger">**</span> Scores range from 1 to 5 (1/2 partially obtained: 3 done what is required: 4/5 exceeded) (weightings apply)</h5></caption>

            <tbody>
            <tr>
                <td><b>EMAILS</b> <span class="uk-badge" style="background-color: lightgrey"><?php echo $emailstot;?></span> / <span class="uk-badge" style="background-color: lightgrey"><?php echo $emailsOg;?></span> | <span class="uk-badge" style="background-color: darkslategrey"><?php echo  $obj->emails_total;?></span> | <span class="uk-badge" style="background-color: red"><?php echo $percemail;?>%</span></td>
                <td><b>0</b></td>
                <td><b>1</b></td>
                <td><b>2</b></td>
                <td><b>3</b></td>
                <td><b>4</b></td>
                <td><b>5</b></td>
                <td><b>N/A</b></td>
            </tr>
            <tr>
                <td>Correct email address used (i.e. Member / Medical Scheme/Doctors' rooms</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails1" <?php echo ($emails1== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails1" <?php echo ($emails1== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails1" <?php echo ($emails1== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails1" <?php echo ($emails1== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails1" <?php echo ($emails1== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails1" <?php echo ($emails1== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails1" <?php echo ($emails1== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>Greeting is professional</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails2" <?php echo ($emails2== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails2" <?php echo ($emails2== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails2" <?php echo ($emails2== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails2" <?php echo ($emails2== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails2" <?php echo ($emails2== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails2" <?php echo ($emails2== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails2" <?php echo ($emails2== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>Correct details provided in email (ie. Patient / Account number / Medical Aid details / Service date / Amounts)</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails3" <?php echo ($emails3== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails3" <?php echo ($emails3== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails3" <?php echo ($emails3== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails3" <?php echo ($emails3== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails3" <?php echo ($emails3== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails3" <?php echo ($emails3== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails3" <?php echo ($emails3== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The body of the email contains clear information and is concise</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails4" <?php echo ($emails4== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails4" <?php echo ($emails4== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails4" <?php echo ($emails4== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails4" <?php echo ($emails4== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails4" <?php echo ($emails4== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails4" <?php echo ($emails4== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails4" <?php echo ($emails4== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>Attachments attached (where applicable)</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails5" <?php echo ($emails5== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails5" <?php echo ($emails5== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails5" <?php echo ($emails5== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails5" <?php echo ($emails5== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails5" <?php echo ($emails5== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails5" <?php echo ($emails5== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails5" <?php echo ($emails5== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The CS requested the correct information / documentation to resolve his/her query</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails6" <?php echo ($emails6== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails6" <?php echo ($emails6== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails6" <?php echo ($emails6== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails6" <?php echo ($emails6== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails6" <?php echo ($emails6== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails6" <?php echo ($emails6== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails6" <?php echo ($emails6== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The correct information was provided to the doctors' rooms / member / Medical Scheme?</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails7" <?php echo ($emails7== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails7" <?php echo ($emails7== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails7" <?php echo ($emails7== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails7" <?php echo ($emails7== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails7" <?php echo ($emails7== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails7" <?php echo ($emails7== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails7" <?php echo ($emails7== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>There are no spelling errors / punctuation/grammar mistakes</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails8" <?php echo ($emails8== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails8" <?php echo ($emails8== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails8" <?php echo ($emails8== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails8" <?php echo ($emails8== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails8" <?php echo ($emails8== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails8" <?php echo ($emails8== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails8" <?php echo ($emails8== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td>The email carries the correct tone and is polite</td>
                <td><input class="uk-radio" type="radio" value="0" name="emails9" <?php echo ($emails9== 0) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="1" name="emails9" <?php echo ($emails9== 1) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="2" name="emails9" <?php echo ($emails9== 2) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="3" name="emails9" <?php echo ($emails9== 3) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="4" name="emails9" <?php echo ($emails9== 4) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="5" name="emails9" <?php echo ($emails9== 5) ?  "checked" : "" ;  ?>></td>
                <td><input class="uk-radio" type="radio" value="101" name="emails9" <?php echo ($emails9== 101) ?  "checked" : "" ;  ?>></td>
            </tr>
            <tr>
                <td colspan="6">
                    <span class="uk-text-danger">Comments:</span>
                    <div class="uk-margin">
                        <textarea class="uk-textarea" name="emails10" rows="5" placeholder=""><?php echo $emails10;?></textarea>
                    </div>
                </td>
            </tr>

            <?php
            if($qa_signed=="1")
            {
                ?>
                <tr>
                    <td colspan="6">
                        <div class="uk-text-danger">Other Comments</div><br>
                        <span id="artsec">
                <?php
                $narr=$obj->getQAnotes($claim_id);
                if(count($narr)>0)
                {
                    foreach ($narr as $row)
                    {
                        $notes=$row[2];
                        $mytime=$row[3];
                        $author=$row[4];
                        echo "      <article class=\"uk-comment\">
                        <header class=\"uk-comment-header\">
                            <div class=\"uk-grid-medium uk-flex-middle\" uk-grid>
                                <div class=\"uk-width-expand\">
                                  
                                    <ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">
                                        <li><a href=\"#\" id=\"mytime\">$mytime</a></li>
                                        <li><a href=\"#\">$author</a></li>
                                    </ul>
                                </div>
                            </div>
                        </header>
                        <div class=\"uk-comment-body\">
                            <p>$notes</p>
                        </div>
                    </article><hr>";
                    }
                }
                ?>
                </span>


                        <textarea class="uk-textarea" style="width: 100%" id="mynotes"></textarea>

                        <br><br>

                        <span class="uk-button uk-button-primary" onclick="addNote()">Post</span>

                    </td>
                </tr>

                <tr>
                    <td colspan="6">
                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <label><input class="uk-checkbox" id="upd" onclick="update()" type="checkbox" <?php echo ($qa_signed=="1" && $cs_signed=="1") ?  "checked" : "" ;  ?>> Claims Specialist accepted the Outcome?</label>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <hr class="uk-divider-icon">

        <p uk-margin style="float: left" <?php echo $hid1;?>>

            <button style="float: right" class="uk-button uk-button-primary" name="btnsave" title="Saved content can be viewed by the C.S" <?php echo $hid1;?>><span uk-icon="upload"></span> Save Now</button>
            <?php
            if($quality==1)
            {
                echo "<button style=\"float: right\" class=\"uk-button uk-button-danger\" name=\"btndraft\" title='Saved content cannot be viewed by the Claims Specialist'><span uk-icon=\"file-edit\"></span> Save As Draft</button>";
            }
            ?>

        </p>
        <br>

    </form>
    <form action="download_quality.php" method="post">
        <input type="hidden" name="claim_id" value="<?php echo $claim_id;?>">
        <p uk-margin style="float: right" <?php echo $hid2;?>>
            <button style="float: right" class="uk-button uk-button-danger" name="btndownload" <?php echo $hid2;?>><span uk-icon="cloud-download"></span> Download Now</button>

        </p>
    </form>
</div>
<?php
include ("footer.php");
?>
</body>
</html>
