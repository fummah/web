<?php
session_start();
define("access",true);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include ("classes/controls.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
include("header.php");
$mail = new PHPMailer(true);

require_once ("classes/leadClass.php");
$obj=new leadClass();
$mail = new PHPMailer(true);
require_once ("classes/leadClass.php");
$obj=new leadClass();
$lead_id=(int)isset($_POST["lead_btn"]) || isset($_POST["promote"]) || isset($_POST["decline"]) || isset($_POST["requestt"])?$_POST["lead_id"]:0;
$_SESSION["sess_lead"] = $lead_id;
$details=$obj->getDetails($lead_id);
$first_name=$details['first_name'];
$last_name=$details['last_name'];
$email=$details['email'];
$contact_number=$details['contact_number'];
$medical_name=$details['medical_scheme'];
$scheme_number=$details['scheme_number'];
$amount=$details['amount_claimed'];
$descrip=$details['description'];
$date_entered=$details['date_entered'];
$username=$details['username'];
$status=(int)$details['status'];
$claim_id=(int)$details['claim_id'];
?>
<html>
<head>

    <title>MCA | View Lead</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/simplePagination.css" />
    <script src="js/jquery.simplePagination.js"></script>

    <script>
        function addNote() {

            var notes= $("#mynotes").val();
            var author="<?php echo $_SESSION['user_id'];?>";
            var mytime=currentDate();
            var lead_id="<?php echo $lead_id;?>";


            if(notes!="") {

                var obj={

                    lead_id:lead_id,
                    author:author,
                    notes:notes
                };
                $.ajax({
                    url:"ajax/deleting.php?identity=40",
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
        function promoteForm()
        {
            if (confirm('Usually a lead is automatically promoted to a claim after the client has paid for the service. Are you sure you want to promote this lead internally?')) {

                return true;

            } else {

                return false;
            }
        }
        function declineForm()
        {
            if (confirm('Are you sure you want to decline this lead?')) {

                return true;

            } else {

                return false;
            }
        }
        function requestForm()
        {
            if (confirm('Are you sure you want to request the client to proceed to payment portal?')) {
                $("#xx").addClass("uk-text-danger");
                $("#xx").text("please wait...");
                return true;

            } else {

                return false;
            }
        }
        var ff="";
        function upload(event){
            event.preventDefault();
            document.getElementById('myfiles').innerText ="Please wait...";
            var files = document.getElementById('fileAjax').files;
            var formData = new FormData();
            for(i=0;i<files.length;i++)
            {
                var file = files[i];
                formData.append('fileAjax', file, file.name);
                ff+=";"+file.name;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'upload_lead_file.php', true);
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        document.getElementById('myfiles').innerText = "";
                    } else {
                        document.getElementById('myfiles').innerText = 'Please wait ...';
                    }

                    var node = document.createElement("LI");
                    var textnode = document.createTextNode(this.responseText);
                    node.appendChild(textnode);
                    document.getElementById("demo").appendChild(node);
                };
                xhr.send(formData);
            }

        }
    </script>
    <style>
        .linkButton {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;
        }
        .uk-button{
            border-radius:15px

        }
    </style>
</head>

<body>
<?php
echo "<br><br>";
if(isset($_POST['promote']))
{
    $unk="Unknown";
    if($obj->addClaim($first_name,$last_name,$email,$contact_number,$unk,$scheme_number,$username,$charged_amnt,$lead_id))
    {
        echo "<div class=\"uk-alert-success\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p align='center'>The Lead successfully promoted, you can go ahead and view the claim.</p>
</div>";
        $status=1;
        $claim_id=$obj->mcclaim_id;
   foreach ($obj->notes($lead_id) as $rr)
        {          
            $obj->insertNotes($claim_id,$rr["description"],"System","00-00-00 00:00:00","","","","","",1);
        }
    }
    else
    {
        echo "<div class=\"uk-alert-danger\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p align='center'>Failed to promote this lead, try again.$first_name,$last_name,$email,$contact_number,$unk,$scheme_number,$username,$charged_amnt,$lead_id</p>
</div>";
    }
}
elseif (isset($_POST['decline']))
{
    $status=2;
    $obj->updateLead($lead_id,2);
    echo "<div class=\"uk-alert-success\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p align='center'>This lead was successfully declined.</p>
</div>";
}
elseif (isset($_POST['requestt']))
{
    $status=3;
    $obj->sendMail($email,$first_name,$lead_id);
    echo "<div class=\"uk-alert-success\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p align='center'>The request was successfully sent.</p>
</div>";
}
$amount=number_format($amount,2,'.',' ');
?>
<div class="container">

    <div class="row uk-card uk-card-default uk-card-body">
        <p><b><u>Details</u></b></p>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    Full Name : <b><?php echo $first_name." ".$last_name;?></b>
                </div>
                <div class="col-md-4">
                    Email : <b><?php echo $email;?></b>
                </div>
                <div class="col-md-4">
                    Contact Number : <b><?php echo $contact_number;?></b>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-4">
                    Scheme Name : <b><?php echo $medical_name;?></b>
                </div>
                <div class="col-md-4">
                    Scheme Number : <b><?php echo $scheme_number;?></b>
                </div>
                <div class="col-md-4">
                    Amount to be claimed : <b>R <?php echo $amount;?></b>
                </div>
            </div><br>

            <div class="row">
                <div class="col-md-4">Username : <b><?php echo $username;?></b></div>
                <div class="col-md-4">Date Created : <b><?php echo $date_entered;?></b></div>

            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo"Additional Information : <b>".nl2br($descrip)."</b>";
                    ?>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="uk-inline">
                        <button class="uk-button uk-button-default" type="button">Files</button>
                        <div uk-dropdown>
                            <?php
                            if(count($obj->getFiles($lead_id))<1)
                            {
                                echo "No Files";
                            }
                            foreach ($obj->getFiles($lead_id) as $rrow)
                            {



                                $id = htmlspecialchars($rrow[0]);
                                $ra = htmlspecialchars($rrow[6]);
                                $nname = htmlspecialchars($rrow[2]);


                                $desc = "../../mca/leads/" . $ra . $nname;

                                //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
                                echo "<form action='view_file.php' method='post' target=\"print_popup\" onsubmit=\"window.open('test5.php','print_popup','width=1000,height=800');\"/><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$nname\">

</form>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <form id="formAjax" action="upload_lead_file.php" method="POST">
                        <div class="js-upload" uk-form-custom>
                            <input type="file" id="fileAjax" name="fileAjax[]" multiple="multiple">
                            <button class="uk-button uk-button-default" type="button" tabindex="-1">Select</button>
                        </div>
                        <button name="submitx" id="submitx" class="uk-button uk-button-secondary" onclick="upload(event)">Upload</button>
                    </form>
                </div>

                <div class="col-md-4">
                    <span id="status" style="color: red"></span>
                    <span style="color: #70a0d0; font-size: 16px; font-weight: bolder" id="demo"></span>
                    <span id="myfiles"></span>
                </div>
            </div>


            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p uk-margin>
                        <?php
                        if($status==0 || $status==3)
                        {
                        ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>">
                        <?php
                        $req="Send Request";
                        $reqc="uk-button uk-button-primary";
                        if($status==3)
                        {
                            $req="Waiting";
                            $reqc="uk-button uk-button-default";
                        }
                        ?>
                        <button name="requestt" class="<?php echo $reqc; ?>" onclick="return requestForm()"><span uk-icon="mail"></span><span id="xx"> <?php echo $req; ?></span></button>
                        <button name="promote" id="promote" class="uk-button uk-button-primary" onclick="return promoteForm()"><span uk-icon="check"></span> Promote</button>
                        <button name="decline" class="uk-button uk-button-danger" onclick="return declineForm()"><span uk-icon="close"></span> Decline</button>
                    </form>
                    <?php
                    }
                    elseif ($status==1)
                    {
                        echo "<form action='case_details.php' method='post' /><input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />

<input type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" name=\"btn\" value=\"View Claim\"></form>";
                    }
                    ?>
                    </p>
                </div>

            </div>

        </div>
    </div>
    <div class="row uk-card uk-card-default uk-card-body">
        <p><b><u>Notes</u></b></p>
        <div class="col-md-12">
                    <span id="artsec">

                        <?php

                        foreach ($obj->notes($lead_id) as $row)
                        {
                            $notes=$row[0];
                            $mytime=$row[1];
                            $author=$row[2];
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
                            <p>".nl2br($notes)."</p>
                        </div>
                    </article><hr>";
                        }
                        ?>
                        </span>
            <textarea class="uk-textarea" style="width: 100%" id="mynotes"></textarea>

            <br><br>

            <span class="uk-button uk-button-primary" onclick="addNote()">Post</span>
        </div>
    </div>


</div>
</div>
</body>
</html>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
