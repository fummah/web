<?php
session_start();
define("access",true);
if(!isset($_POST["claim_id"]))
{
    die("Invalid entry");
}
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
include ("header.php");
?>
<title>MCA | Edit Claim</title>
<script src="js/claim_loading_js.js"></script>
<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }
</script>
<style>
    .form-control{
        border: 1px solid green !important;
        padding-left: 2px !important;
    }
    #country-list{
        float:left;
        list-style:none;
        width:190px;
        z-index: 3;
        padding: 2px;
        position: absolute;
        border:#eee 1px solid;
    }
    #country-list li{
        padding: 10px;
        background: #54bf99;
        border-bottom: #E1E1E1 1px solid;
        z-index: 3;
    }
    #country-list li:hover{
        background:lightblue;
        cursor: pointer;
        -webkit-transition: background-color 300ms linear;
        -ms-transition: background-color 300ms linear;
        transition: background-color 300ms linear;
        color: #54bf99;
    }
    .et_pb_text_3 {
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        font-size: 14px;
        line-height: 1.6em;
        padding-bottom: 31px!important;
        margin-right: -6px!important;
        margin-bottom: 10px!important;

    }
</style>
<?php
$claim_id=(int)$_POST["claim_id"];
$control->claim_id=$claim_id;
$_SESSION['docClaimID']=$claim_id;
$data=$control->viewSingleClaim($claim_id);
$control->owner_name=$data["username"];
if($control->owner_name==$control->loggedAs() || $control->isTopLevel())
{}
else
{
    die("Invalid access");
}
$control->claim_number=$data["claim_number"];
$policy_number=$data["policy_number"];
$control->client_name=$data["client_name"];
$date_entered=$data["date_entered"];
$client_id=$data["client_id"];
$created_by=$data["createdBy"];
$control->member_name=$data["first_name"];
$control->member_surname=$data["surname"];
$full_name=$control->member_name." ".$control->member_surname;
$id_number=$data["id_number"];
$control->member_email=$data["email"];
$telephone=$data["telephone"];
$cell=$data["cell"];
$patient=$data["patient_name"];
$control->medical_scheme=$data["medical_scheme"];
$scheme_option=$data["scheme_option"];
$open_reason=$data["open_reason"];
$medication_value=$data["medication_value"]; $fusion_done=$data["fusion_done"]; $dosage=$data["code_description"]; $person_email=$data["contact_person_email"]; $codes=$data["modifier"]; $nappi=$data["reason_code"];
$patient_dob=$data["patient_dob"];$patient_gender=$data["patient_gender"];
$control->member_number=$data["scheme_number"];
$control->consent_description=$data["consent_descr"];
$pmb=(int)$data["pmb"];
$emergency=(int)$data["emergency"];
$icd10=$data["icd10"];
$date_reopened=$data["date_reopened"];
$date_closed=$data["date_closed"];
$start_date=$data["Service_Date"];
$end_date=$data["end_date"];
$control->case_status=(int)$data["Open"];
$quality=(int)$data["quality"];
$control->header_chargedamount=$data["charged_amnt"];
$control->header_schemeamount=$data["scheme_paid"];
$control->header_gapamount=$data["client_gap"];
$control->header_memberportion=$data["gap"];
$control->header_scheme_savings=$data["savings_scheme"];
$control->header_discount_savings=$data["savings_discount"];
$control->sla=(int)$data["sla"];
$patient_address=$data["patient_address"];
$patient_contact=$data["patient_contact"];
$open1 = "Closed";
$readyonly="readyonly";
if ($control->case_status == 1) {
    $open1 = "Open";
}
?>
<div class="row" style="padding: 20px;">
    <ul class="uk-breadcrumb" style="padding-left: 20px;padding-bottom: 20px;">
        <li><a href="index.php">Home</a></li>
        <li><a href="">Edit Claim</a></li>

    </ul>

    <div class="col-md-8">
        <form class="tab" action="save_claim_details.php" method="post" onsubmit="return validateForm()">
            <input type="hidden" value="<?php echo $claim_id;?>" id="claim_id" name="claim_id">
            <?php
            if($control->isTopLevel())
            {
                $readyonly="";
                ?>
                <div class="row">
                    <div class="input-field col s4">

                        <select id="owner" name="owner">
                            <option value="<?php echo $control->owner_name; ?>"><?php echo $control->owner_name; ?></option>
                            <?php
                            foreach ($control->viewValidUsers() as $row) {
                                ?>
                                <option value="<?php echo $row['username']; ?>"><?php echo $row['username']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <label>Username</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="claim_status" name="claim_status">
                            <?php
                            if($control->case_status==4){
                                echo "<option value='4'>Under Review</option>";
                            }
                            elseif ($control->case_status==5)
                            {
                                echo "<option value='5'>Pre-Assessment</option>";

                            }
                            else
                            {
                                echo "<option value='$control->case_status'>$open1</option>";
                                echo "<option value='1'>Open</option>";
                            }
                            ?>
                        </select>
                        <label>Claim Status</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="open_reason" name="open_reason">
                            <option value="<?php echo $open_reason; ?>"><?php echo $open_reason; ?></option>
                            <option value="CS Request">CS Request</option>
                            <option value="Client Request">Client Request</option>
                        </select>
                        <label>Reason</label>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="row">
                <div class="input-field col s4">
                    <select id="client_id" name="client_id" onchange="validateClient(),validatePolicy(),generateClaimNomber()">
                        <option value="<?php echo $client_id; ?>"><?php echo $control->client_name; ?></option>
                        <?php
                        foreach ($control->viewClients() as $row) {
                            ?>
                            <option value="<?php echo $row['client_id']; ?>"><?php echo $row['client_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <label>Client Name</label>
                </div>
                <div class="input-field col s4">
                    <input id="member_name" name="member_name" type="text" value="<?php echo $control->member_name;?>" class="validate">
                    <label for="member_name">Member's First Name</label>
                </div>
                <div class="input-field col s4">
                    <input id="member_surname" name="member_surname" type="text" value="<?php echo $control->member_surname;?>" class="validate">
                    <label for="member_surname">Member's Surname</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="patient_name" name="patient_name" type="text" value="<?php echo $patient;?>" class="validate">
                    <label for="patient_name">Patient Name <a uk-toggle="target: #modal-close-default"><span uk-icon="pencil"></span></a></label>
                </div> <div class="input-field col s4">
                    <input id="member_telephone" name="member_telephone" type="text" value="<?php echo $telephone;?>" class="validate">
                    <label for="member_telephone">Member's Telephone Number</label>
                </div>
                <div class="input-field col s4">
                    <input id="cell_number" name="cell_number" type="text" value="<?php echo $cell;?>" class="validate">
                    <label for="cell_number">Member's Cell Phone Number</label>
                </div>

            </div>
            <div class="row showcl">
                <div class="input-field col s4">
                    <input type="date" id="d_o_b" name="d_o_b" value="<?php echo $patient_dob; ?>"/>
                    <label for="email">Patient D.O.B</label>
                </div>
                <div class="input-field col s4">
                    <select id="patient_gender" name="patient_gender">
                        <option value="<?php echo $patient_gender; ?>"><?php echo $patient_gender; ?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <label for="policy_number">Patient Gender</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="member_email" name="member_email" type="email" value="<?php echo $control->member_email;?>" class="validate">
                    <label for="member_email">Member's e-mail Address</label>
                </div>
                <div class="input-field col s4">
                    <input id="policy_number" name="policy_number" type="text" value="<?php echo $policy_number;?>" onblur="return validatePolicy(1)">
                    <label for="policy_number">GAP Policy Number</label>
                </div>   <div class="input-field col s4">
                    <input id="claim_number" name="claim_number" type="text" value="<?php echo $control->claim_number;?>" <?php echo $readyonly;?> REQUIRED>
                    <label for="claim_number">Claim Number</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s4">
                    <select id="medical_scheme" name="medical_scheme" onchange="return Schemes()" REQUIRED>
                        <option value="<?php echo $control->medical_scheme; ?>"><?php echo $control->medical_scheme; ?></option>
                        <?php

                        foreach ($control->viewClaimSchemes() as $row) {

                            ?>
                            <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <label>Medical Scheme</label>
                </div>
                <div class="input-field col s4">
                    <input id="scheme_option" name="scheme_option" type="text" value="<?php echo $scheme_option;?>" class="validate">
                    <label for="scheme_option">Scheme Option</label>
                </div>   <div class="input-field col s4">
                    <input id="member_number" name="member_number" type="text" value="<?php echo $control->member_number;?>" class="validate">
                    <label for="member_number">Scheme Membership Number</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="id_number" name="id_number" type="text" onblur="validateNumber('id_number','13','14')" value="<?php echo $id_number;?>" class="validate">
                    <label for="id_number">Member ID Number</label>
                </div>
                <div class="input-field col s4">
                    <input id="start_date" name="start_date" type="text" value="<?php echo $start_date;?>" class="datepicker">
                    <label for="start_date">From Date (Incident Date)</label>
                </div>  <div class="input-field col s4">
                    <input id="end_date" name="end_date" type="text" value="<?php echo $end_date;?>" class="datepicker">
                    <label for="end_date">To Date (Incident Date)</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="practice_number" name="practice_number" type="text" class="validate" onblur="return checkDoctor()">
                    <label for="practice_number">Practice Number</label>
                    <input type="hidden" id="doctors" name="doctors">
                </div>
                <div class="input-field col s4">
                    <input id="icd10" name="icd10" type="text" value="<?php echo $icd10;?>" class="validate" onblur="Codes()" required>

                    <span id="suggesstion-box2" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>

                    <label for="icd10">Primary ICD10 code</label>
                </div> <div class="input-field col s4">
                    Emergency?
                    <p>
                        <label>
                            <input name="emergency" value="1" id="emergency1" type="radio" <?php echo ($emergency== '1') ?  "checked" : "" ;  ?> />
                            <span>Yes</span>
                        </label>

                        <label>
                            <input name="emergency" value="0" id="emergency2" type="radio" <?php echo ($emergency== '0') ?  "checked" : "" ;  ?>/>
                            <span>No</span>
                        </label>
                    </p>
                </div>
            </div>
            <div class="row showcl">
                <div class="input-field col s4">
                    <select id="medication_value" name="medication_value">
                        <option value="<?php echo $medication_value; ?>"><?php echo $medication_value; ?></option>
                        <option value="VENOFER ">VENOFER</option>
                        <option value="FERINJECT">FERINJECT</option>
                    </select>
                    <label for="charged_amount">Name of Medication</label>
                </div>
                <div class="input-field col s4">
                    <select id="fusion_done" name="fusion_done">
                        <option value="<?php echo $fusion_done; ?>"><?php echo $fusion_done; ?></option>
                        <option value="IN ROOMS ">IN ROOMS</option>
                        <option value="IN HOSPITAL">IN HOSPITAL</option>
                    </select>
                    <label for="fusion_done">Infusion to be do</label>
                </div>  <div class="input-field col s4">
                    <input type="text" id="dosage" name="dosage" list="dosage1" value="<?php echo $dosage; ?>"/>
                    <label for="dosage">Dosage</label>
                </div>
            </div>
            <div class="row showcl">
                <div class="input-field col s4">
                    <input type="text" id="codes" name="codes" value="<?php echo $codes; ?>"/>
                    <label for="codes">Codes</label>
                </div>
                <div class="input-field col s4">
                    <input type="text" id="nappi" name="nappi" value="<?php echo $nappi; ?>"/>
                    <label for="nappi">Nappi</label>
                </div>  <div class="input-field col s4">
                    <input type="text" id="person_email" name="person_email" value="<?php echo $person_email; ?>" />
                    <label for="person_email">Contact Person Email</label>
                </div>
            </div>
            <div class="row hidecl">
                <div class="input-field col s4">
                    <input id="charged_amount" name="charged_amount" type="text" value="<?php echo $control->header_chargedamount;?>" onblur="amountCalc()">
                    <label for="charged_amount">Total Charged Amount.</label>
                </div>
                <div class="input-field col s4">
                    <input id="scheme_paid" name="scheme_paid" type="text" value="<?php echo $control->header_schemeamount;?>" onblur="amountCalc()">
                    <label for="scheme_paid">Total Value the Scheme Paid.</label>
                </div>  <div class="input-field col s4">
                    <input id="member_portion" name="member_portion" type="text" value="<?php echo $control->header_memberportion;?>">
                    <label for="member_portion">Member's Portion.</label>
                </div>
            </div>



            <div class="row hidecl">
                <div class="input-field col s4">
                    <input id="client_gap_amount" name="client_gap_amount" type="text" value="<?php echo $control->header_gapamount;?>" class="validate">
                    <label for="client_gap_amount">Client Gap Amount.</label>
                </div>

                    <div class="input-field col s4" style="display: none">
                        <input type="text" style="color: #449d44" id="savings_scheme" name="savings_scheme" value="<?php echo $control->header_scheme_savings; ?>"  />
                        <label for="savings_scheme">Scheme Savings.</label>
                    </div>
                    <div class="input-field col s4" style="display: none">
                        <input type="text" style="color: #449d44" id="savings_discount" name="savings_discount" value="<?php echo $control->header_discount_savings; ?>"  />
                        <label for="savings_discount">Discount Savings.</label>
                    </div>

            </div>
            <?php
            if($control->isTopLevel() || $control->case_status==1 || $control->case_status==5)
            {
                ?>

                <div class="row hidecl">
                    <div class="input-field col s12">

                        <button class="uk-button uk-button-primary mybtn" id="edit_btn" name="edit_btn"><span uk-icon="icon: check"></span> Save Changes</button>

                    </div>

                </div>
                <?php
            }
            ?>
        </form>
    </div>

    <div class="col-md-4 uk-placeholder">
        <span id="show_info"></span>
        <table class="uk-table uk-table-striped uk-table-small">
            <thead><tr>
                <td>Dr Prac.No</td>
                <td>Disc?</td>
                <td>Contact</td>
                <td>Name</td>
            </tr></thead>

            <tbody id="my_doctors">
            <?php
            if(count($control->viewClaimDoctor($claim_id))>0) {
                foreach ($control->viewClaimDoctor($claim_id) as $row) {
                    $practice_number = $row["practice_number"];
                    $doctor = $control->viewDoctor($practice_number);
                    $doctor_name = $doctor["name_initials"];
                    $doctor_surname = $doctor["surname"];
                    $contact = "(" . $doctor["tel1code"] . ")" . $doctor["tel1code"];
                    $gives_discount = $doctor["gives_discount"];
                    $doctor_id = $doctor["doc_id"];
                    $doctor_form="<form action=\"edit_doctor.php\" method=\"post\" target=\"print_popup\" onsubmit=\"window.open('edit_doctor.php','print_popup','width=1000,height=800');\">
                                        <input type=\"hidden\" name=\"doc_id\" value=\"$doctor_id\">
        <button class=\"linkbutton\" name=\"doctor_edit_btn\" title=\"edit claim\"> $practice_number</button> </form>
        ";
                    echo "<tr><td>$doctor_form</td><td>$gives_discount</td><td>$contact</td><td><span onclick='deleteDoctor(\"$claim_id\",\"$practice_number\")' uk-icon=\"trash\" title='delete' style='color:red;cursor: pointer'></span> $doctor_name $doctor_surname</td></tr>";
                }
            }
            else
            {
                echo "<p>No Doctors</p>";
            }
            ?>

            </tbody>
        </table>

        <iframe src="upload_file.php" style="width: 100% !important;" scrolling="no" frameborder="0" onload="resizeIframe(this)"></iframe>
    </div>

</div>


<datalist id="options">
    <option value="---">
</datalist>
<datalist id="dosage1">
    <option value="200mgx2">
    <option value="1000mg">
</datalist>
<!-- This is the modal with the default close button -->
<div id="modal-close-default" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Edit Patient</h2>
         <div class="uk-margin">
        <label class="uk-form-label" for="form-stacked-text">Patient Name</label>
        <div class="uk-form-controls">
            <input class="uk-input"  type="text" value="<?php echo $patient;?>" placeholder="Patient Name" disabled>
        </div>
    </div>  
     <div class="uk-margin">
        <label class="uk-form-label" for="form-stacked-text">Patient Email</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="patient_email" value="<?php echo $patient_address;?>" type="text" placeholder="Patient Email">
        </div>
    </div>
     <div class="uk-margin">
        <label class="uk-form-label" for="form-stacked-text">Patient Phone Number</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="patient_contact" type="text" value="<?php echo $patient_contact;?>" placeholder="Patient Phone Number">
        </div>
    </div>
    <div id="patientinfo" style="display:none"></div>
    <p uk-margin>
    <button class="uk-button uk-button-default uk-button-small" onclick="addPatientChange()">Save Changes</button>
    </div>
</div>
<?php
include "footer.php";
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems, options);
    });

    // Or with jQuery

    $(document).ready(function(){
        $('select').formSelect();
    });
</script>