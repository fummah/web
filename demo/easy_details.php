<span align="center" style="padding-top: 1px; float: right;">
    <button class="uk-button uk-button-danger uk-button-small" onclick="document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'"><span style="color: red" uk-icon="icon: close" class="uk-icon-button"></span> Close</button>
 </span>
    <style>
    .form-control{
        border: 1px solid green !important;
        padding-left: 2px !important;
    }
    .detab{
        padding-top: 7px !important;
    }
</style>
<?php
require_once("classes/controls.php");
require_once("templates/claim_templates.php");
$control=new controls();
$claim_id=(int)$_POST["claim_id"];
$control->claim_id=$claim_id;
$data=$control->viewSingleClaim($claim_id);
//details starting here
echo "<div class=\"row\" style=\"padding-top: 20px;padding-left: 20px;padding-right: 20px;\"><div class=\"col-md-12\">";
claim_header($data,$control);
//doctor and claim line level
doctor_line($control->viewClaimDoctor($claim_id),$claim_id,$control,"admin");
//Notes Section
echo "<section id='notes_section'><div class=\"row\"><div class=\"col-md-12\"></div>";
claimtabs("admin");

//Starting notes
echo "<div id=\"notes_tab\" class=\"col s12 uk-animation-slide-left detab\">";
echo "<div class=\"row\">";

echo "<div class=\"col-md-8\">";
notes_temp($control->viewFeedback($claim_id),"admin","Client Feedback");
echo "<hr><span id='t01'></span>";
notes_temp($control->viewNotes($claim_id),"admin","Notes");
echo"</div>";

echo " <div class=\"col-md-4\" style=\"border: 1px solid whitesmoke\">";
echo "<div style=\"z-index: 980;\" uk-sticky=\"offset: 40\">";
claim_buttons_temp("admin",$control);
claim_notetext_temp("admin",$control);
echo "<span style='margin: 20px'>";
echo "<button class=\"uk-button uk-button-secondary uk-button-small\" onclick=\"closeLoad()\"><span uk-icon=\"icon: check\" style=\"color: black\" class=\"uk-icon-button\"></span> Done</button>";
echo "  <button class=\"uk-button uk-button-danger uk-button-small\" onclick=\"document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'\"><span style=\"color: red\" uk-icon=\"icon: close\" class=\"uk-icon-button\"></span> Close</button>";
echo"</span>";
echo"</div>";
echo"</div>";

echo"</div>";
echo "</div>";
echo "<div id=\"feedback_tab\" class=\"col s12 uk-animation-slide-left detab\" style=\"display:none\"><p><u>Feedback</u></p>";
echo "<div class=\"row\">";
echo "<div class=\"col-md-8\"><span id='t02'></span>";
notes_temp($control->viewFeedback($claim_id),"admin","Client Feedback");
echo "</div>";
echo "<div class=\"col-md-4\">";
feedbackOptions($control);
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div id=\"validations_tab\" class=\"col s12 uk-animation-slide-left detab\" style=\"display:none\"><p><u>Validations</u></p>";
echo "</div>";
echo "</div></section>";
echo "</div></div>";
savingsModal("",$control);
?>
<button uk-toggle="target: #close_case_modal" id="closesavings" style="display: none" type="button">Test</button>
<!-- This is the modal -->
<div id="edit_note" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Edit the Note below</h2>
        <textarea class="uk-textarea" style="width: 100%; height: 200px !important;" id="editnote" rows="20"></textarea>
        <input type="hidden" id="hid">
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button" onclick="updateText()">Save</button>
        </p>
        <span id="resultText"></span>
    </div>
</div>

<script>

    $(document).ready(function(){
        $('select').formSelect();
        //$('.tabs').tabs();
    });
    function openTab(tabname) {
        var i;
        var x = document.getElementsByClassName("detab");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        document.getElementById(tabname).style.display = "block";
    }

</script>






