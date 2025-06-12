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
$_SESSION['email_claim_id']="Zero";

$arr=$control->viewExtraEmails();
$cccount=count($arr);
?>
<title>MCA | Extra Emails</title>
<style>
 .et_pb_texta{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        font-size: 20px;

    }
    .et_pb_textr{
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
        font-size: 14px;
    }
    .cop{
        padding:10px !important;
        border-bottom: 1px solid lightgray !important;
        cursor: pointer;
    }
    </style>
<div style="padding-left: 10px; padding-top: 20px; border: 1px solid red">

    <div class="row">
        <div class="col-md-12">
            <h3 align="center"><span style="color: red"><?php echo count($arr);?></span> Extra Emails</h3>
            <table class="uk-table uk-table-striped" width="100%">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Email</th>
                    <th>Date Entered</th>
                    <th>Link Claim</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if($cccount>0) {
                    foreach ($arr as $rows) {
                        $id = $rows["id"];
                        $subject = $rows["subject"];
                        $email_from = $rows["email_from"];
                        $date1 = new DateTime($rows["date_entered"]);
                        $date_entered=$date1->format('d M Y, H:i:s');
  echo "<tr id='$id'><td><div class='uk-label'><span> $subject</span></div></td>
                        <td> <form action='mailbox/read_mail.php' method='post' target=\"print_popup\" onsubmit=\"window.open('#','print_popup','width=1000,height=800');\">
<input type=\"hidden\" name=\"mail_id\" value=\"$id\" />
<input type=\"hidden\" name=\"email_from\" value=\"$email_from\" />
<input type=\"hidden\" name=\"subject\" value=\"$subject\" />              
<input type=\"submit\" class=\"linkbutton\" name=\"inbox\" value=\"$email_from\">
</form></td> <td class=\"mailbox-date\">$date_entered</td>
<td><button style='border-radius: 20px' id='open_modal' email_id='$id' class=\"uk-button uk-button-danger\" href=\"#modal-group-2\" uk-toggle><span uk-icon='link'></span>Link</button></td>
<td><button style='border-radius: 20px' id='archive' email_id='$id' class=\"uk-button uk-button-info\"><span uk-icon='history'></span> Archive</button></td>
</tr>";
                    }
                }
                else
                {
                    echo "<h3 align='center' style='color: red'>No Extra Emails</h3>";
                }
              
                ?>
                </tbody>
        </div>

    </div>
</div>

<div id="modal-group-2" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Select Claim</h2>
        </div>
        <div class="uk-modal-body">
            <div class="uk-margin">
            <input class="uk-search-input" name="search_term_txt" id="search_term_txt" type="search" placeholder="Search Claim">
            <span id="suggesstion-box-member" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <input type="hidden" name="claim_id" id="claim_id" value="0">
            <input type="hidden" name="email_id" id="email_id" value="0">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary bt" onclick="linkEmail()" disabled>Link Email to Claim</button>
            <span id="infoerror"></span>
        </div>
    </div>
</div>
<script>
    $(document).on('click','#open_modal',function() {
        $('#email_id').val($(this).attr('email_id'));
    });
    function linkEmail()
    {
        let claim_id = $('#claim_id').val();
        let email_id = $('#email_id').val();
        let identity=5;
        let obj={identity,claim_id,email_id};
        $.ajax({
            type: "GET",
            url: "mailbox/emailajax.php",
            data:obj,
            success: function(data){
                console.log(data);
                if(data==="1")
            {
                UIkit.notification({message: "Email Successfully Linked"});
                $("#"+email_id).remove();
                UIkit.modal("#modal-group-2").hide();
            }
            else
            {
                UIkit.notification({message: "Failed to link the email"});
            }

            },            
            error:function (xhr,status,error) {
                console.log("There is an error");
            }
        });
    }
    
    $("#search_term_txt").keyup(function(){
        $(".bt").prop("disabled",true);
        var obj={
            identity:4,
            keyword:$(this).val()
        };
        $.ajax({
            type: "GET",
            url: "mailbox/emailajax.php",
            data:obj,
            beforeSend: function(){
                console.log("Waiting");
            },
            success: function(data){
                $("#suggesstion-box-member").show();
                $("#suggesstion-box-member").html(data);
            }
            ,
            error:function (xhr,status,error) {
                console.log("There is ana err");
            }
            ,
            complete:function () {
            }
        });
    });
    $(document).on('click','#archive',function(){
        if(confirm("Do you want to archive this email?"))
    {
      let email_id = $(this).attr("email_id");
        var obj={
            identity:7,
            email_id:email_id
        };
        $.ajax({
            type: "GET",
            url: "mailbox/emailajax.php",
            data:obj,
            beforeSend: function(){
            },
            success: function(data){
                console.log(data);
                if(data==="1")
            {
                UIkit.notification({message: "Email Successfully Archived"});
                $("#"+email_id).remove();
            }
            else
            {
                UIkit.notification({message: "Failed to archive the email"});
            }
            }
            ,
            error:function (xhr,status,error) {
                console.log("There is ana err");
            }
            ,
            complete:function () {
            }
        });
    }
    });
    function selectSearchedClaim(claim_id,claim_number)
 {
    $("#search_term_txt").val(claim_number);
    $("#claim_id").val(claim_id);
    $("#suggesstion-box-member").hide();
    $(".bt").prop("disabled",false);
}
</script>
