<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$_SESSION["admin_main"]=true;

if(!isset($_POST["btn"]))
{
    die("Invalid entry");
}
require_once "dbconn.php";
$conn=connection("mca","MCA_admin");
$mail = new PHPMailer(true);
require_once ("classes/leadClass.php");
$obj=new leadClass();
$claim_id=(int)isset($_POST["claim_id"])?$_POST["claim_id"]:0;
$_SESSION['docClaimID'] = $claim_id;
$details=[];
$details=$obj->getClaim($claim_id);
$claim_number=$details['claim_number'];
$first_name=$details['first_name'];
$last_name=$details['surname'];
$email=$details['email'];
$contact_number=$details['contact_number'];
$medical_name=$details['medical_scheme'];
$scheme_number=$details['scheme_number'];
$scheme_option=$details['scheme_option'];
$telephone=$details['telephone'];
$cell=$details['cell'];
$username=$details['username'];
$open=(int)$details['Open'];
$date_closed=$details['date_closed'];
$date_entered=$details['date_entered'];
$id_number=$details['id_number'];
$pmb=(int)$details['pmb'];
$icd10=$details['icd10'];
$start_date=$details['Service_Date'];
$end_date=$details['end_date'];
$createdBy=$details['createdBy'];
$medication_value=$details['medication_value'];
$patient_dob=$details['patient_dob'];
$fusion_done=$details['fusion_done'];
$dosage=$details['code_description'];
$codes=$details['modifier'];
$nappi=$details['reason_code'];
$person_email=$details['contact_person_email'];
$client_name=$details['client_name'];
$patient_gender=$details['patient_gender'];
$patient_id=$details['patient_idnumber'];
$patient=$obj->getPatient($claim_id);
$aspen=$obj->getAspen($claim_id);

$delivery_required=(int)$aspen[1]==1?"checked":"";
$c1=(int)$aspen[2]==1?"checked":"";$c2=(int)$aspen[3]==1?"checked":"";$c3=(int)$aspen[4]==1?"checked":"";$c4=(int)$aspen[5]==1?"checked":"";$c5=(int)$aspen[6]==1?"checked":"";
$c6=(int)$aspen[7]==1?"checked":"";$c7=(int)$aspen[8]==1?"checked":"";$c8=(int)$aspen[9]==1?"checked":"";$c9=(int)$aspen[10]==1?"checked":"";$c10=(int)$aspen[11]==1?"checked":"";
$c11=(int)$aspen[12]==1?"checked":"";
$reason=$aspen[14];
$status=strlen($aspen[13])>2?$aspen[13]:"[select]";
$stat=$aspen[13]=="Declined" || $aspen[13]=="Partially-Approved"?"block":"none";
$oStatus="<b style='color: red'><u>(Open Case) [ $date_entered ]</u></b>";
if($open==0)
{
    $oStatus="<b style='color:#b9bbbe'><u>Case Closed[$date_closed]</b>";

}

?>
<html>
<head>

    <title>MCA : Pre-Authorisation</title>
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
        $(document).ready(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");
        });
        function addNote() {


            var notes= $("#mynotes").val();
            var author="<?php echo $_SESSION['user_id'];?>";
            var mytime=currentDate();
            var claim_id="<?php echo $claim_id;?>";
            var email="<?php echo $person_email;?>";
            var claim_number="<?php echo $claim_number;?>";
            var val=1;

            if(document.getElementById("cclose").checked)
            {
                val=7;
            }

            if(notes!="") {
                $("#spi").show();
                var obj={

                    claim_id:claim_id,
                    author:author,
                    notes:notes,
                    val:val,
                    email:email,
                    claim_number:claim_number

                };
                $.ajax({
                    url:"ajaxPhp/deleting.php?identity=46",
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
                        $("#spi").hide();
                    },
                    error:function(jqXHR, exception)
                    {
                        alert(jqXHR.responseText);
                        //alert("Testing pliee");
                        $("#spi").hide();
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

        function checkList(c)
        {
            var claim_id="<?php echo $claim_id;?>";
            var val=0;

            if(document.getElementById(c).checked)
            {
                val=1;
            }

            var obj={

                claim_id:claim_id,
                checklist: c,
                val: val
            };
            $.ajax({
                url:"ajaxPhp/deleting.php?identity=47",
                type:"GET",
                data:obj,
                success:function(data){
                    UIkit.notification({message: data})
                },
                error:function(jqXHR, exception)
                {
                    alert(jqXHR.responseText);
                }
            });
        }

        function checkDoctors(prac)
        {
            var claim_id="<?php echo $claim_id;?>";
            var arrprac=prac.split("_");
            var practice_number=arrprac[0];
            var val=arrprac[1];

            var valTick=0;

            if(document.getElementById(prac).checked)
            {
                valTick=1;
            }

            var obj={

                claim_id:claim_id,
                practice_number: practice_number,
                val: val,
                valTick:valTick
            };
            $.ajax({
                url:"ajaxPhp/deleting.php?identity=48",
                type:"GET",
                data:obj,
                success:function(data){
                    UIkit.notification({message: data})
                },
                error:function(jqXHR, exception)
                {
                    alert(jqXHR.responseText);
                }
            });


        }
        function openModal(id)

        {
            var txt=$("#"+id).text();
            $("#editnote").val(txt);
            $("#hid").val(id);

        }
        function updateText() {
            $('#resultText').show();
            var text= $('#editnote').val();
            var textid=$('#hid').val();
            if(text==""){
                $('#resultText').html("<b style='color: red'>Please write something</b>");
            }
            else {
                $('#resultText').html("<b style='color: red'>Please wait...</b>");
                var obj = {identity: 16, textid: textid, text: text};
                $.ajax({
                    url: "ajaxPhp/deleting.php",
                    type: "GET",
                    data: obj,
                    success: function (data) {
                        $('#resultText').html(data)
                        var resT= $('#resultText').text();
                        if(data.indexOf("Updated!!!")>-1)
                        {
                            $("#"+textid).text(text);
                            $("#"+textid).addClass("uk-alert-success");
                        }


                    },
                    error: function (jqXHR, exception) {
                        $('#resultText').html(jqXHR.responseText);
                    }
                });
            }
        }

        function delete1(id) {

            if(confirm("Are you sure you want to delete this note?"))
            {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {

                        var display2 = id;
                        var mess = this.responseText;
                        if (mess.indexOf("Deleted")>-1) {
                            UIkit.notification({message: this.responseText});
                            document.getElementById(display2).style.backgroundColor = "pink";

                        }
                        else {
                            UIkit.notification({message: this.responseText});
                        }

                    }
                };
                xhttp.open("GET", "ajaxPhp/deleting.php?id=" + id + "&identity=2", true);
                xhttp.send();
            }

        }
        function resizeIframe(obj) {
            obj.style.height = "100%";
        }
        function changeOn()
        {
            //alert("Yes");
            var val=$("#status").val();
            //alert(val);
            if(val=="Declined" || val=="Partially-Approved")
            {
                $("#xcv").show();
            }
            else
            {
                $("#xcv").hide();
            }
            $("#buttn").show();
        }

        function addStatus()
        {
            var claim_id="<?php echo $claim_id;?>";
            var status=$("#status").val();
            var reason=$("#reason").val();

            var obj={

                claim_id:claim_id,
                status: status,
                reason:reason
            };
            $.ajax({
                url:"ajaxPhp/deleting.php?identity=49",
                type:"GET",
                data:obj,
                success:function(data){
                    UIkit.notification({message: data})
                },
                error:function(jqXHR, exception)
                {
                    alert(jqXHR.responseText);
                }
            });

        }
    </script>
    <style>
        label{
            padding-bottom: 20px;
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
    </style>
</head>

<body>
<div class="se-pre-con"></div>
<?php
include("header.php");
echo "<br><br><br>";
?>
<div class="container" style="width: 99%">
    <div class="row">
        <div class="col-xs-9">

            <div class="row uk-card uk-card-default uk-card-body">
                <p class="uk-text-primary"><b><u>Pre-Authorisation Details</u></b></p>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-4">
                            Primary ICD10 Code : <b><?php echo $icd10;?></b>
                        </div>
                        <div class="col-xs-4">

                        </div>
                        <div class="col-xs-4">
                            <?php
                            echo "<form action='edit_case.php' id='vv' method='post' />";
                            echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                            echo "<button name='btn' class=\"uk-button uk-button-primary\"><span uk-icon=\"pencil\"></span> Edit Case</button></form>";
                            ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            Client Name : <b><?php echo $client_name?></b>
                        </div>
                        <div class="col-xs-4">
                            Status : <b><?php echo $oStatus;?></b>
                        </div>
                        <div class="col-xs-4">
                            Created By : <b><?php echo $createdBy;?></b>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-4">
                            Full Name : <b><?php echo $first_name." ".$last_name;?></b>
                        </div>
                        <div class="col-xs-4">
                            ID Number : <b><?php echo $id_number;?></b>
                        </div>
                        <div class="col-xs-4">
                            Email : <b><?php echo $email;?></b>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-4">
                            Telephone : <b><?php echo $telephone;?></b>
                        </div>
                        <div class="col-xs-4">
                            Cell Number : <b><?php echo $cell;?></b>
                        </div>
                        <div class="col-xs-4">
                            Patient(s) : <b>(<?php echo $patient;?>)</b> <b>(<?php echo $patient_dob;?>)[<?php echo $patient_id;?>][<?php echo $patient_gender;?>]</b>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-4">
                            Scheme Name : <b><?php echo $medical_name;?></b>
                        </div>
                        <div class="col-xs-4">
                            Scheme Option : <b><?php echo $scheme_option;?></b>
                        </div>
                        <div class="col-xs-4">
                            Member Number : <b><?php echo $scheme_number;?></b>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-4">
                            Infusion Date From : <b><?php echo $start_date. " TO ".$end_date;?></b>
                        </div>
                        <div class="col-xs-4">
                            Name of Medication : <b><?php echo $medication_value;?></b>
                        </div>
                        <div class="col-xs-4">
                            Infusion to be done : <b><?php echo $fusion_done;?></b>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-4">
                            Dosage : <b><?php echo $dosage;?></b>
                        </div>
                        <div class="col-xs-4">
                            Codes : <b><?php echo $codes;?></b>
                        </div>
                        <div class="col-xs-4">
                            Nappi : <b><?php echo $nappi;?></b>
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-xs-3">MCA Request Number : <b><?php echo $claim_number;?></b></div>
                        <div class="col-xs-3">Username : <b><?php echo $username;?></b></div>
                        <div class="col-xs-3">
                            <div class="uk-inline">
                                <button class="uk-button uk-button-default" type="button"><span uk-icon="cloud-download"></span> Files</button>
                                <div uk-dropdown>
                                    <?php
                                    if(count($obj->getclaimFiles($claim_id))<1)
                                    {
                                        echo "No Files";
                                    }
                                    foreach ($obj->getclaimFiles($claim_id) as $row1)
                                    {
                                        $id = htmlspecialchars($row1[0]);
                                        $ra=htmlspecialchars($row1[6]);
                                        $nname = htmlspecialchars($row1[2]);
                                        $file_id=$row1[11];
                                        $file_type=$row1[3];
                                        $desc = "../../mca/documents/" . $ra.$nname;
                                        $type = htmlspecialchars($row1[3]);
                                        $size = round($row1[4] / 1024);
                                        $dd=(int)$row1[9];
                                        $ffj="";
                                        if($dd==1)
                                        {
                                            $ffj="style='color: red'";
                                        }
                                        //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
                                        echo "<form action='test5.php' method='post' target=\"print_popup\" onsubmit=\"window.open('view_doc.php','print_popup','width=1000,height=800');\"/>
<input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" $ffj name=\"doc\" value=\"$nname\">
</form>";

                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <form action="../pdf/viewdownload.php" method="post">
                                <input name="claim_id" type="hidden" value="<?php echo $claim_id;?>">
                                <button class="uk-button uk-button-default" type="submit"><span uk-icon="cloud-download"></span> Download Consent</button>
                            </form></div>
                    </div>
                    <hr>
                    <div class="row">
                        <p><b><u>Providers</u></b></p>
                        <div class="col-xs-12">
                            <table class="uk-table uk-table-striped" width="100%">
                                <thead>
                                <tr><th>Name</th><th>Practice Number</th><th>Prescription</th><th>Administering</th></tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($obj->getProviders($claim_id) as $row1)
                                {
                                    $name=$row1[1]." ".$row1[2];
                                    $practice_number=$row1[0];
                                    $prescription=$row1[3];
                                    $administering=$row1[4];
                                    $rr=$prescription== '1'?"checked":"" ;
                                    $rr1=$administering== '1'?"checked":"" ;
                                    $pid=$practice_number."_1";
                                    $pid1=$practice_number."_2";
                                    echo "<tr><td>$name</td><td>$practice_number</td><td><input class=\"uk-checkbox\" type=\"checkbox\" id='$pid' onclick='checkDoctors(\"$pid\")' $rr/></td><td><input class=\"uk-checkbox\" type=\"checkbox\" id='$pid1' onclick='checkDoctors(\"$pid1\")' $rr1/></td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>

                        </div>
                    </div>



                </div>
            </div>
            <div class="row uk-card uk-card-default uk-card-body">
                <p><b><u>Notes</u></b></p>
                <div class="col-xs-12">
                    <span id="artsec">

                        <?php

                        foreach ($obj->notesClaim($claim_id) as $row)
                        {
                            $notes=$row[0];
                            $mytime=$row[1];
                            $author=$row[2];
                            $nid=$row[3];
                            date_default_timezone_set('Africa/Johannesburg');
                            $from_date = date('Y-m-d', strtotime($mytime));
                            $today=date('Y-m-d');
                            $datetime1 = strtotime($from_date);
                            $datetime2 = strtotime($today);
                            $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                            $days = $secs / 86400;
                            $days=round($days);
                            echo "<article class=\"uk-comment\">
                        <header class=\"uk-comment-header\">
                            <div class=\"uk-grid-medium uk-flex-middle\" uk-grid>
                                <div class=\"uk-width-expand\">
                                  
                                    <ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">
                                        <li><a href=\"#\" id=\"mytime\">$mytime</a></li>
                                        <li><a href=\"#\">$author</a></li>
                                        <li><i><a href=\"#\">$days Days Ago</a></i></li>
                                        <li><a href=\"#edit_note\" uk-icon=\"icon: pencil\" title='edit' onclick='openModal(\"$nid\")' uk-toggle></a></li>
                                        <li><span style='cursor: pointer' uk-icon=\"icon: trash\" title='delete' onclick='delete1(\"$nid\")'></span></li>
                                    </ul>
                                </div>
                            </div>
                        </header>
                        <div class=\"uk-comment-body\">
                            <p id='$nid'>".nl2br($notes)."</p>
                        </div>
                    </article><hr>";
                        }
                        ?>

                        </span>
                    <textarea class="uk-textarea" style="width: 100%" id="mynotes"></textarea>

                    <br><br>
                    <div class="row">
                        <div class="col-xs-12">

                            <span class="uk-button uk-button-primary" onclick="addNote()">Post</span>
                            <div style="display: none" id="spi" uk-spinner></div>
                            <?php
                            if($open==1)
                            {
                                echo "<span><label><b><input class=\"uk-checkbox\" type='checkbox' id='cclose'/> Close Case?</b></label></span>";
                            }
                            else
                            {
                                echo "<span class='uk-text-warning'>Case Closed.</span>";
                            }
                            ?>
                        </div>


                    </div>
                    <hr>
                    <div class="uk-margin">
                        Select Status : <select class="uk-select" id="status" style="border-color: #0b2e13" onchange="changeOn()">
                            <option value="<?php echo $status;?>"><?php echo $status;?></option>

                            <option value="Approved">Approved</option>
                            <option value="Partially-Approved">Partially-Approved</option>
                            <option value="Declined">Declined</option>
                        </select><br>
                        <span id="xcv" style="display: <?php echo $stat;?>">
                        Reason : <textarea class="uk-textarea" style="width: 100%; border-color: #0b2e13" id="reason"><?php echo $reason;?></textarea>
                        </span>
                        <span style="display: none" id="buttn" class="uk-button uk-button-primary" onclick="addStatus()">Update</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-3">
            <ul uk-tab>
                <li class="uk-active"><a href="#">Aspen Checklist</a></li>
                <li><a href="#">Upload Files</a></li>
            </ul>
            <ul class="uk-switcher uk-margin">
                <li>

                    <div class="uk-card uk-card-default uk-card-body uk-animation-slide-right-medium" style="z-index: 980;" uk-sticky="offset: 40">
                        <label uk-tooltip="title: Check if all required documents were received (ie. Motivation form, Aspen/Takeda consent form, Pathology Reports, Dr's Script for the medication);top-right"><input class="uk-checkbox" type='checkbox' id="c1" onclick="checkList('c1')" <?php echo $c1;?>/> Documents were received?</label>
                        <label uk-tooltip="title: Check that all the required information is completed on the forms and consent form is signed by the patient;top-right"><input class="uk-checkbox" type='checkbox' id="c2" onclick="checkList('c2')" <?php echo $c2;?>/> Required information is completed?</label>
                        <label uk-tooltip="title: Confirm receipt of documents / Request the additional information where outstanding;top-right"><input class="uk-checkbox" type='checkbox' id="c3" onclick="checkList('c3')" <?php echo $c3;?>/> Documents / Request received?</label>
                        <label uk-tooltip="title: Forward Pre-Authorisation request to Medical Scheme and note the reference number;top-right"><input class="uk-checkbox" type='checkbox' id="c4" onclick="checkList('c4')" <?php echo $c4;?>/> Pre-Authorisation request forwarded?</label>
                        <label uk-tooltip="title: Next day follow up with the Medical Scheme if documents were received, provide them with the reference number;top-right"><input class="uk-checkbox" type='checkbox' id="c5" onclick="checkList('c5')" <?php echo $c5;?>/> Follow up with the Medical Scheme?</label>
                        <label uk-tooltip="title: Go through the Authorisation details with Pre-Authorisations (if not already done, they will load and pend the case for feedback from Clinical Review Managers(CRM) or Medical Advisor)<br>*Make sure the correct patient is captured (Patient DOB will be required if different from Main Member)<br>*Make sure correct diagnosis (ICD10) code, tariff codes and nappi code for medication is captured.<br>*Make sure the correct provider is loaded (Admitting Doctor and not Prescribing Doctor)<br>* Make sure they have the correct facility information (ie. IN ROOMS or IN HOSPITAL);top-right"><input class="uk-checkbox" type='checkbox' id="c6" onclick="checkList('c6')" <?php echo $c6;?>/> Go through the Authorisation details?</label>
                        <label uk-tooltip="title: Upon Approval, note the Authorisation number (if multiple dates, note the authorisation number for each date)<br>*Confirm from which benefit the Medication will pay/fund from<br>*Confirm from which benefit the Doctor's fees and consumables/materials will fund from<br>*Confirm which benefit the Hospital/Facility will fund from;top-right"><input class="uk-checkbox" type='checkbox' id="c7" onclick="checkList('c7')" <?php echo $c7;?>/> The Authorisation number noted?</label>
                        <label uk-tooltip="title: Provide feedback to the Practice/Hospital and send them a copy of the Approval Letter/Notification;top-right"><input class="uk-checkbox" type='checkbox' id="c8" onclick="checkList('c8')" <?php echo $c8;?>/> Practice/Hospital feedback provided?</label>
                        <label uk-tooltip="title: Confirm with Practice/Hospital if delivery is required;top-right"><input class="uk-checkbox" type='checkbox' id="c9" onclick="checkList('c9')" <?php echo $c9;?>/> Practice/Hospital required?</label>
                        <label uk-tooltip="title: Request Delivery from the relevant Pharmacy/Delivery service. Copy in the Practice/Hospital.;top-right"><input class="uk-checkbox" type='checkbox' id="c10" onclick="checkList('c10')" <?php echo $c10;?>/> Delivery requested?</label>
                        <label uk-tooltip="title: You will receive a response email with the details of delivery within +- 4hrs. If not, follow up that the delivery request was received. If the Practice/Hospital was not copied in by the Pharmacy/Delivery service, forward the details to them to keep them informed.;top-right"><input class="uk-checkbox" type='checkbox' id="c11" onclick="checkList('c11')" <?php echo $c11;?>/> Response email received?</label>

                        <hr class="uk-divider-icon">
                        <label class="uk-text-danger"><input class="uk-checkbox" type='checkbox' id="delivery_required" onclick="checkList('delivery_required')" <?php echo $delivery_required;?>/> Delivery Required?</label>
                        <hr>
                        <h5 class="uk-text-danger">Contact Person Email Address: <b><?php echo $person_email;?></b></h5>
                    </div>
                </li>
                <li>


                    <iframe class="uk-card uk-card-default uk-card-body uk-animation-slide-right-medium" src="upload.php" scrolling="no" frameborder="0" onload="resizeIframe(this)" width="100%"></iframe>


                </li>

            </ul>
        </div>
    </div>

</div>
</div>

<?php
require ("footer.php");
?>
<!-- This is the modal -->
<div id="edit_note" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Edit the Note below</h2>
        <textarea class="uk-textarea" style="width: 100%" id="editnote"></textarea>
        <input type="hidden" id="hid">
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button" onclick="updateText()">Save</button>
        </p>
        <span id="resultText"></span>
    </div>
</div>
</body>
</html>
