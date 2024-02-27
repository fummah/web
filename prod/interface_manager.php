<?php
session_start();
define("access",true);
require "classes/controls.php";
include ("templates/claim_templates.php");
$control=new controls();
include ("header.php");
$role=$control->myRole();
$username=$control->loggedAs();
$val=1;
$condition=":username";
$rolex="other";
if($control->isClaimsSpecialist())
{
    $condition="username=:username";
    $val=$control->loggedAs();
    $rolex="cs";
}
elseif ($control->isGapCover())
{
    $condition="client_name=:username";
    $val=$control->loggedAs();
}
$arr=$control->viewErrorOwls($condition,$val);
$cccount=count($arr);
?>
<title>MCA | Interface</title>
<div style="padding-left: 10px; padding-top: 20px; border: 1px solid red">

    <div class="row">
        <div class="col-md-12">
            <h3 align="center"><span style="color: red"><?php echo count($arr);?></span> Interface Errors</h3>
            <table class="uk-table uk-table-striped" width="100%">
                <thead>
                <tr>
                    <th>Claim Number</th>
                    <th>Client</th>
                    <th>Date Entered</th>
                    <th>Username</th>
                    <th>Error Message</th>
                    <th>Status</th>
                    <th>Resend</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if($cccount>0) {
                    foreach ($arr as $rows) {
                        $claim_id = $rows["claim_id"];
                        $error_id = $rows["id"];
                        $claim_number = $rows["claim_number"];
                        $client_name = $rows["client_name"];
                        $date_entered = $rows["date_time"];
                        $error = $rows["desciption"];
                        $content = json_decode($rows["desciption1"], true);
                        $username = $rows["username"];
                        $sender_id = $rows["senderId"];
                        $note = $content["intervention_description"];
                        $status = $content["status"];
                        $spin_id = "sp-" . $error_id;
                        echo "<tr id='$error_id'><td style='color: #0b8278'><b>$claim_number</b></td><td><b>$client_name</b></td><td>$date_entered</td><td>$username</td>";
                        echo "<td style='color: red !important;'>";
                        echo htmlspecialchars($error);
                        echo "</td>";
                        echo "<td style='background-color: floralwhite' uk-tooltip=\"title: $note\">$status</td>";
                        echo "<td width='15%'><button style='border-radius: 20px' class=\"uk-button uk-button-danger\" onclick='resendOwls(\"$error_id\",\"$sender_id\",\"$claim_number\")'><span uk-icon='bolt'></span>Resend <div id='$spin_id' style='color: white; display: none' uk-spinner></div></button></td>";
echo "<td title='Archive'><span class='uk-badge' style='cursor: pointer; padding: 20px; background-color: black' href=\"#modal-group-2\" onclick='openModel(\"$error_id\")' uk-toggle><span uk-icon='bookmark'></span></span></td>";
echo"</tr>";
                    }
                }
                else
                {
                    echo "<h3 align='center' style='color: red'>No Errors found</h3>";
                }
                ?>
                </tbody>
        </div>

    </div>
</div>

<div id="modal-group-2" uk-modal>
    <div class="uk-modal-dialog">
        <input type="hidden" name="errorid" id="errorid">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Archive this issue</h2>
        </div>
        <div class="uk-modal-body">
            <div class="uk-margin">
                <textarea class="uk-textarea" rows="5" placeholder="Type the reason for archiving ..." aria-label="Textarea" id="errornote"></textarea>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" onclick="saveArchive()">Save Note</button>
            <span id="infoerror"></span>
        </div>
    </div>
</div>
<script>
    function openModel(id) {
        $("#errorid").val(id);
    }
    function resendOwls(id,sender_id,claim_number)
    {
        let obj={identity_number:37,error_id:id,sender_id:sender_id,claim_number:claim_number};
        $.ajax({
            url: "ajax/claims.php",
            beforeSend:function (xhr)
            {
                $("#sp-"+id).show("fast");
            },
            type:"POST",
            data:obj,
            success: function(data){
                if(data.indexOf("Success")>-1)
                {
                    $("#"+id).remove();
                }
                UIkit.notification({message: data});

            },
            complete:function (xhr,status) {
                $("#sp-"+id).hide("fast");
            },
            error:function (xhr,status,error) {
                UIkit.notification({message: "There is an error"});
            }
        });
    }
    function saveArchive() {
        let error_id=$("#errorid").val();
        let errornote=$("#errornote").val();
        let obj={identity_number:38,error_id:error_id,errornote:errornote};
        $.ajax({
            url: "ajax/claims.php",
            beforeSend:function (xhr)
            {
                $("#infoerror").text("please wait...");
            },
            type:"POST",
            data:obj,
            success: function(data){
               $("#infoerror").text(data);
                if(data.indexOf("Success")>-1)
                {
                    $("#"+error_id).remove();
                }
            },
            complete:function (xhr,status) {

            },
            error:function (xhr,status,error) {
                UIkit.notification({message: "There is an error"});
            }
        });
    }
</script>
