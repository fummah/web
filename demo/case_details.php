<?php
session_start();
error_reporting(0);
define("access",true);
if(!isset($_POST["claim_id"]))
{
    die("Invalid access");
}
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
if(!isset($_POST["quick_view"])){
    include ("header.php");
    ?>
    <title>MCA | Claim Details</title>
    <?php
}
?>
<script src="js/claim_details_js.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<style>
        /* CSS to style the table */
.table {
    width: 100%;
    border-collapse: collapse;
    font-family: "Roboto", sans-serif;
  font-weight: 300;
  font-style: normal;
}

/* Style table header */
.table th {
    background-color: whitesmoke;
    color: #333;
    padding: 10px;
    text-align: left;
    border: 1px solid white;
    font-family: "Roboto", sans-serif;
  font-weight: 500;
}

/* Style table body */
.table td {
    text-align: left;
    border: 1px solid whitesmoke;
    font-family: "Roboto", sans-serif;
  font-weight: 300;
  font-style: normal;
}

/* Style alternating rows */
.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Style hover effect on rows */
.table tbody tr:hover {
    background-color: #f2f2f2;
}

/* Style span with uk-badge class */
.uk-badge {
    background-color: #54bf99;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
}

/* Style spans with uk-icon class */
.uk-icon {
    color: #54bf99; /* or any color you prefer */
    cursor: pointer;
}


/* Style text-info class */
.text-info {
    color: #17a2b8 !important;
}

/* Style text-success class */
.text-success {
    color: #28a745 !important;
}

/* Style uk-margin-small-right class */
.uk-margin-small-right {
    margin-right: 5px;
}

/* Style text-meta class */
.uk-text-meta {
    color: deepskyblue;
    border-bottom: 1px solid whitesmoke;
    padding: 10px;
    font-weight: bolder;
    text-align: center;
}

/* Style tooltip */
/* You can add CSS for tooltip based on your tooltip library or implementation */

    .form-control{
        border: 1px solid green !important;
        padding-left: 2px !important;
    }
    .detab{
        padding-top: 7px !important;
    }
    .inaction{
        background-color: lightblue !important;
        padding :5px !important;
        text-decoration: none !important;
        font-weight: bold !important;
    }
    .table>:not(:first-child) {
    border-top: 2px solid #b0cac8 !important;
}
/* Popup styles */
.popup {
  position: fixed;
  top: 20px; /* Adjust as needed */
  right: 20px; /* Adjust as needed */
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  z-index: 9999; /* Higher z-index to ensure it's above other elements */
  display: none;
}

.closeBtn {
  position: absolute;
  top: 5px;
  right: 5px;
  cursor: pointer;
}

#savingsResult {
  padding: 10px 20px;
  background-color: red;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
#savingsBalance {
  padding: 10px 20px;
  background-color: darkcyan;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

</style>
<?php
$claim_id=(int)$_POST["claim_id"];
$role=$control->myRole();
$username=$control->loggedAs();
$escalation=isset($_POST["catergory"])?$_POST["catergory"]." > ".$_POST["sub_catergory"]." > ".$_POST["source"]:$username." = ".$role;
$control->callInsertEscalationsLogs($claim_id,$escalation);
$control->claim_id=$claim_id;
$data=$control->viewSingleClaim($claim_id);
$quality=(int)$data["quality"]>0?"checked":"";
//details starting here
echo "<div class=\"row\" style=\"padding-top: 10px;padding-left: 5px;padding-right: 5px; border: 1px solid lightgrey; width: 99%; margin-left: auto; position: relative; margin-right: auto\"><div class=\"col-md-12\">";
claim_header($data,$control,$username);
$control->eightdays();
//doctor and claim line level
doctor_line($control->viewClaimDoctor($claim_id),$claim_id,$control);
$qa_tick_id=$control->qa_disabled."_".$claim_id;
$clinical_tick_id=$control->clinical_disabled."_".$claim_id;
$clinical_number=count($control->viewClinicalNotes($control->claim_id));
$clinical_review=$control->case_status==4 || $clinical_number>0?"checked":"";
//Notes Section
echo "<hr class='uk-divider-icon'>";
if($control->case_status==5)
{
    echo "<div style='float: right'>";
    claim_buttons_temp($control);
    echo "</div>";
    preAssessment($control);
}
else {
    echo "<section id='notes_section'><div class=\"row\"><div class=\"col-md-8\">";
    claimtabs($control,$clinical_number);
    echo "</div><div class=\"col-md-4\" style='border-bottom: 1px dashed grey !important;\'>";
    if($control->isInternal()) {
        echo "<label><label title='QA Box'><input type='checkbox' class='uk-checkbox qa_tick' id='$qa_tick_id' $quality><span>QA?</span><label> 
<label title='Send for Clinical Review' style='padding-left: 20px !important;'><input type='checkbox' id='$clinical_tick_id' class='uk-checkbox clinical_review' $clinical_review><span>Clinical Review?</span><label>";
    }    
echo"</div>";
//Starting notes
    echo "<div id=\"notes_tab\" class=\"col s12 uk-animation-fade detab\">";
    echo "<div class=\"row\">";

    echo "<div class=\"col-md-7\" style='overflow-y: scroll; height:500px;'>";
    notes_temp($control->viewFeedback($claim_id),$control,"Client Feedback");
    echo "<hr><span id='t01'></span>";
    notes_temp($control->viewNotes($claim_id),$control,"Notes");
    echo "</div>";
    echo " <div class=\"col-md-5\" style=\"border: 1px solid whitesmoke\">";
    echo "<div>";
    claim_buttons_temp($control);
    if($control->isInternal())
    {
        claim_notetext_temp($control);
        if(isset($_POST["quick_view"])) {
            echo "<span style='margin-top: 10px; margin-bottom: 10px'>";
            echo "<button style='display: none' id='donenext' class=\"uk-button uk-button-secondary uk-button-small\" onclick=\"closeLoad()\"><span uk-icon=\"icon: check\" style=\"color: black\" class=\"uk-icon-button\"></span> Done, move to Next Claim</button>";
            //echo "  <button class=\"uk-button uk-button-danger uk-button-small\" onclick=\"document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'\"><span style=\"color: red\" uk-icon=\"icon: close\" class=\"uk-icon-button\"></span> Return to Home</button>";
            echo "<br></span>";
            echo "<span style=\"padding-top: 1px;\"><button class=\"uk-button uk-button-danger uk-button-small\" onclick='exitNow()'><span style=\"color: white\" uk-icon=\"icon: close\"></span> Return to Home</button></span>";
            echo "<span align=\"center\" style=\"padding-top: 1px;\" title='refresh'> <button class=\"uk-button uk-button-small\" style='border-radius: 20px; background-color: #0a58ca' onclick='refreshNow()'><span style=\"color: white\" uk-icon=\"icon: refresh\"></span></button></span>";

        }
    }
    echo "</div>";
    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "<div id=\"feedback_tab\" class=\"col s12 uk-animation-fade detab\" style=\"display:none\"><p><u>Feedback</u></p>";
    echo "<div class=\"row\">";
    echo "<div class=\"col-md-8\"><span id='t02'></span>";
    notes_temp($control->viewFeedback($claim_id),$control,"Client Feedback");
    echo "</div>";
    echo "<div class=\"col-md-4\">";
    feedbackOptions($control);
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "<div id=\"validations_tab\" class=\"col s12 uk-animation-fade detab\" style=\"display:none\"><p align='center'>Validations</p>";
    echo "<div id='validations'></div>";
    echo "</div>";
    if($control->case_status==4 || $clinical_number>0) {
        echo "<div id=\"clinical_tab\" class=\"col s12 uk-animation-fade detab\" style=\"display:none\">";
        clinicalReview($control, $username);
        echo "</div>";
    }
    if($control->validate8days && $control->isInternal() || $control->client_name=="Individual") {
        echo "<div id=\"days8_tab\" class=\"col s12 uk-animation-fade detab\" style=\"display:none\">";
        my8days($control);
        echo "</div>";
    }
    echo "</div></section>";
}
echo "</div></div>";
savingsModal($control);
?>
<button uk-toggle="target: #close_case_modal" id="closesavings" style="display: none" type="button">modal</button>
<button uk-toggle="target: #send_clinical" id="sendclinicalx" style="display: none" type="button">clinical</button>
<button uk-toggle="target: #client_gap" id="gapx" style="display: none" type="button">gap</button>
<!-- This is the modal -->
<div id="edit_note" uk-modal="stack: true">
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

<div id="send_clinical" uk-modal="stack: true">
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Type a note for clinical review</h2>
        <textarea class="uk-textarea" style="width: 100%; height: 200px !important;" id="clinicalnote1" rows="20"></textarea>
        <input type="hidden" id="hidclinical">
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button" onclick="newClinical()">Save</button>
        </p>
        <span id="clinicalText"></span>
    </div>
</div>

<div id="client_gap" uk-modal="stack: true">
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Edit Client Gap (<span id='docpractice_number'></span>)</h2>
          <div class="uk-margin">
            Client Charged Amount : <input id="doccharged_amount" class="uk-input" type="number" placeholder="Charged" aria-label="Input">
        </div>
          <div class="uk-margin">
            Client Scheme Amount : <input id="docscheme_amount" class="uk-input" type="number" placeholder="Scheme" aria-label="Input">
        </div>
          <div class="uk-margin">
            Client Gap Amount : <input id="docgap" class="uk-input" type="number" placeholder="Gap" aria-label="Input">
        </div>
        <div id="docinfo" style="display:none"></div>
        <input type="hidden" id="docclaim_id">
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button" onclick="clientAmounts()">Save</button>
        </p>
        <span id="clinicalText"></span>
    </div>
</div>
<div id="popup" class="popup">
  <span class="closeBtn" onclick="closePopup()">&times;</span>
  <h3 id="dr_name"></h3>
  <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-placeholder" style="display: flex; justify-content: space-between; padding-right:15px;padding-top:9px;padding-bottom:9px">
    <label><input class="uk-radio" type="radio" value="1" name="radioCalc" checked> <span>Percentage (%)</span></label>
    <label style="margin-left: auto;"><input class="uk-radio" value="0" type="radio" name="radioCalc"> <span>Fixed Amount</span></label>
</div>
<div class="row">
    <div class="col-md-6">
  <div class="uk-margin input-field" id="f1">
           <input id="percentageF" class="uk-input uk-form-width-medium uk-form-small calc" type="text" placeholder="" aria-label="Small">
        <label for="percentageF" class="xf">Percentage (%)</label>
        
    </div>
    <div class="uk-margin input-field" id="f2" style="display:none">
    <input id="fixedAmountF" class="uk-input uk-form-width-medium uk-form-small calc" type="text" placeholder="" aria-label="Small">
        <label for="fixedAmountF" class="xf">Fixed Amount</label>
    </div>
</div>
<div class="col-md-6">
  <div class="uk-margin input-field">
        <input class="uk-input uk-form-width-medium uk-form-small calc" id="gap_amountF" type="text" placeholder="" aria-label="Small">
        <label for="gap_amountF" class="xf">Gap Amount</label>
    </div>
</div>
</div>

<p align="center" title="Discount"><span id="savingsResult">0.00</span></p>
<p align="center" title="Balance" style="padding-top:10px"><span id="savingsBalance">0.00</span></p>
</div>
<span class="not"  style="bottom: 0px; position: fixed;left: 0px;"></span>
<?php
if(!isset($_POST["quick_view"])) {
    include "footer.php";
}
?>
<script>
    $(document).ready(function(){
        
        checkDates('<?php echo $claim_id;?>');
        loadValidations('<?php echo $claim_id;?>');
        chekconfirm('<?php echo $claim_id;?>');
        addReasons();
        $('select').formSelect();
       
    });
    function openTab(tabname) {
        var i;
        $(".tab>a").removeClass("inaction");
        var x = document.getElementsByClassName("detab");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        $("."+tabname).addClass("inaction");

        document.getElementById(tabname).style.display = "block";
    }
    function exitNow()
    {
        $(".uk-modal-close-full").click();
    }
    function refreshNow()
    {
        var claim_id=$("#next_claim_id").val();
        var obj={claim_id:claim_id,"quick_view":1};
        $("#claim_details").empty();
        $("#close_case_modal").remove();
        $("#ddd").html("<p class=\"card-text placeholder-glow\">\n"+"<span class=\"placeholder col-6\" style=\"height: 100%\"></span><span class=\"placeholder col-6\" style=\"height: 100%\"></span>\n"+"\n"+"</p>");
        $.ajax({
            url:"case_details.php",
            type:"POST",
            data:obj,
            success:function(data){
                $("#claim_details").html(data);
                $("#ddd").empty();
            },
            error:function(jqXHR, exception)
            {

            }
        });

    }
    const addReasons=()=>{
        const obj={identity_number:45};
        $.ajax({
            url:"ajax/claims.php",
            type:"POST",
            data:obj,
            success:function(data){
               const json=JSON.parse(data);
               for(key in json)
               {
                const id=json[key]["id"];
                const desc=json[key]["description"];               
                $(".reason").append("<option value='"+id+"'>"+desc+"</option>");
               }               
            },
            error:function(jqXHR, exception)
            {
                console.log(jqXHR);
            }
        });
    };
    const getClc = (percentageF,fixedAmountF,gap_amountF) =>{    
    $("#gap_amountF").val(gap_amountF);
    $("#percentageF").val(percentageF);
    $("#fixedAmountF").val(fixedAmountF);
    resDisc(percentageF,gap_amountF,fixedAmountF);   
  };
    $(document).on('change','input[name="scheme_declined"]',function(e){
        let isreason=$('input[name="scheme_declined"]:checked').val();
        if(isreason=="yes")
        {
            $(".te").show();
        }
        else{
            $(".te").hide();
        }
        
    });
    $(document).on('change','.reason',function(e){
        let reason=$("#reason option:selected").text();
        let reason_id=$(this).val();
        if(reason_id!=="0")
        {
        let notes=$("#intervention_desc").val(); 
        $("#intervention_desc").val(notes+". "+reason);   
        }    
    });
      $(document).on('click','.gapr',function(){
        let claim_id=$(this).attr('claim_id');
        let practice_number=$(this).attr('practice_number');
        let charged_amount=$(this).attr('charged').replace(' ','');
        let scheme_amount=$(this).attr('scheme').replace(' ','');
        let gap=$(this).attr('gap').replace(' ','');
        console.log(gap);

        $("#docclaim_id").val(claim_id);
        $("#docpractice_number").text(practice_number);
        $("#doccharged_amount").val(charged_amount);
        $("#docscheme_amount").val(scheme_amount);
        $("#docgap").val(gap);

        $("#docinfo").hide();
        $("#gapx").click();
        
    });

    $(document).on('keyup','.calc', function(){
const gap_amountF = $("#gap_amountF").val(); 
const percentageF = $("#percentageF").val();
const fixedAmountF = $("#fixedAmountF").val();
resDisc(percentageF,gap_amountF,fixedAmountF);   
  });

  $(document).on('click','#calculator', function(){
    $(".xf").addClass("active");
    console.log("clfx")
    const gap_amountF = $(this).attr("gap");
    $("#dr_name").text($(this).attr("dr_name"));
getClc(8,0,gap_amountF);    
    document.getElementById('popup').style.display = 'block';
  });
  
  $(document).on('click','.closeBtn', function(){
    document.getElementById('popup').style.display = 'none';
  });
  $(document).on('change','input[name="radioCalc"]',function(){
    const gap_amountF = $("#gap_amountF").val();
    getClc(8,0,gap_amountF);  
  });
  
  const resDisc = (perc,gap,fixedAmount) =>{
    const selectCalc = document.querySelector('input[name="radioCalc"]:checked').value;
    gap=gap.replace(" ", "");
    let result=0;
    if(selectCalc==="1")
    {
    $("#f1").show();
    $("#f2").hide();
        result = (perc/100)*gap;
    }   
    else{
    $("#f2").show();
    $("#f1").hide();
        result = parseFloat(fixedAmount);
    }
    
    let rem = gap-result;
    result = fomata(result.toFixed(2));
    rem = fomata(rem.toFixed(2));
$("#savingsResult").text(result);
$("#savingsBalance").text(rem);
  }

  const fomata=(number)=>{
    var nf = Intl.NumberFormat();
    return nf.format(number);
};
</script>




