<?php
session_start();
error_reporting(0);
define("access",true);
include ("classes/controls.php");
$control=new controls();
if(!$control->isInternal())
{
    die("Invalid access");
}
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
    .showcl{
        display: none;
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

?>
<div class="row" style="padding: 20px;">
    <ul class="uk-breadcrumb" style="padding-left: 20px;padding-bottom: 20px;">
        <li><a href="index.php">Home</a></li>
        <li><a href="">Add Claim</a></li>

    </ul>

    <div class="col-md-8">
        <form class="tab" action="save_claim_details.php" method="post" onsubmit="return validateForm()">

            <div class="row">
                <div class="input-field col s4">
                    <select id="client_id" name="client_id" onchange="validateClient(),validatePolicy(),generateClaimNomber()">
                        <option value="">Select Client</option>
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
                    <input id="member_name" name="member_name" type="text" value="" class="validate">
                    <label for="member_name">Member's First Name</label>
                </div>
                <div class="input-field col s4">
                    <input id="member_surname" name="member_surname" type="text" value="" class="validate">
                    <label for="member_surname">Member's Surname</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="patient_name" name="patient_name" type="text" value="" class="validate">
                    <label for="patient_name">Patient Name</label>
                </div> <div class="input-field col s4">
                    <input id="member_telephone" name="member_telephone" type="text" value="" class="validate">
                    <label for="member_telephone">Member's Telephone Number</label>
                </div>
                <div class="input-field col s4">
                    <input id="cell_number" name="cell_number" type="text" value="" class="validate">
                    <label for="cell_number">Member's Cell Phone Number</label>
                </div>

            </div>
            <div class="row showcl">
                <div class="input-field col s4">
                    <input type="date" id="d_o_b" name="d_o_b" value=""/>
                    <label for="email">Patient D.O.B</label>
                </div>
                <div class="input-field col s4">
                    <select id="patient_gender" name="patient_gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <label for="patient_gender">Patient Gender</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="member_email" name="member_email" type="email" value="" class="validate">
                    <label for="member_email">Member's e-mail Address</label>
                </div>
                <div class="input-field col s4">
                    <input id="policy_number" name="policy_number" type="text" value="" class="validate" onblur="return validatePolicy(1)">
                    <label for="policy_number">GAP Policy Number</label>
                </div>   <div class="input-field col s4">
                    <input id="claim_number" name="claim_number" type="text" value="" class="validate" onblur="return validateClaimNo()">
                    <label for="claim_number">Claim Number</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s4">
                    <select id="medical_scheme" name="medical_scheme" onchange="return Schemes()" REQUIRED>
                        <option value="">Select Scheme</option>
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
                    <input id="scheme_option" name="scheme_option" type="text" value="" list="options" class="validate">
                    <label for="scheme_option">Scheme Option</label>
                </div>   <div class="input-field col s4">
                    <input id="member_number" name="member_number" type="text" value="" class="validate">
                    <label for="member_number">Scheme Membership Number</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="id_number" name="id_number" type="text" onblur="validateNumber('id_number','13','14')" value="" class="validate">
                    <label for="id_number">Member ID Number</label>
                </div>
                <div class="input-field col s4">
                    <input id="start_date" name="start_date" type="text" value="" class="datepicker">
                    <label for="start_date">From Date (Incident Date)</label>
                </div>  <div class="input-field col s4">
                    <input id="end_date" name="end_date" type="text" value="" class="datepicker">
                    <label for="end_date">To Date (Incident Date)</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4">
                    <input id="practice_number" type="text" class="validate" onblur="return checkDoctor()">
                    <label for="practice_number">Practice Number</label>
                    <input type="hidden" id="doctors" name="doctors">
                </div>
                <div class="input-field col s4">
                     <input id="icd10" name="icd10" type="text" value="" class="validate" onblur="Codes()">
                    <span id="suggesstion-box2" class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light"></span>                 
                    <label for="icd10">Primary ICD10 code</label>
                </div> <div class="input-field col s4 hidecl">
                    Emergency?
                    <p>
                        <label>
                            <input name="emergency" value="1" id="emergency1" type="radio"/>
                            <span>Yes</span>
                        </label>

                        <label>
                            <input name="emergency" value="0" id="emergency2" type="radio"/>
                            <span>No</span>
                        </label>
                    </p>
                </div>
            </div>
            <div class="row showcl">
                <div class="input-field col s4">
                    <select id="medication_value" name="medication_value">
                        <option value="">Select Medication</option>
                        <option value="VENOFER ">VENOFER</option>
                        <option value="FERINJECT">FERINJECT</option>
                    </select>
                    <label for="charged_amount">Name of Medication</label>
                </div>
                <div class="input-field col s4">
                    <select id="fusion_done" name="fusion_done">
                        <option value="IN ROOMS ">IN ROOMS</option>
                        <option value="IN HOSPITAL">IN HOSPITAL</option>
                    </select>
                    <label for="fusion_done">Infusion to be do</label>
                </div>  <div class="input-field col s4">
                    <input type="text" id="dosage" name="dosage" list="dosage1" value=""/>
                    <label for="dosage">Dosage</label>
                </div>
            </div>
            <div class="row showcl">
                <div class="input-field col s4">
                    <input type="text" id="codes" name="codes" value=""/>
                    <label for="codes">Codes</label>
                </div>
                <div class="input-field col s4">
                    <input type="text" id="nappi" name="nappi" value=""/>
                    <label for="nappi">Nappi</label>
                </div>  <div class="input-field col s4">
                    <input type="text" id="person_email" name="person_email" value="" />
                    <label for="person_email">Contact Person Email</label>
                </div>
            </div>
            <div class="row hidecl">
                <div class="input-field col s3">
                    <input id="charged_amount" name="charged_amount" type="text" value="" onblur="amountCalc()" class="validate">
                    <label for="charged_amount">Total Charged Amount.</label>
                </div>
                <div class="input-field col s3">
                    <input id="scheme_paid" name="scheme_paid" type="text" value="" onblur="amountCalc()" class="validate">
                    <label for="scheme_paid">Total Value the Scheme Paid.</label>
                </div>  <div class="input-field col s3">
                    <input id="member_portion" name="member_portion" type="text" value="" class="validate">
                    <label for="member_portion">Member's Portion.</label>
                </div>
                <div class="input-field col s3">
                    <input id="client_gap_amount" name="client_gap_amount" type="text" value="" class="validate">
                    <label for="client_gap_amount">Client Gap Amount.</label>
                </div>
            </div>

                <div class="row">
                    <div class="input-field col s12">
                        <p uk-margin>
                            <button class="uk-button uk-button-primary mybtn" id="btn" name="btn"><span uk-icon="icon: check"></span> Add Claim</button>
                            <button class="uk-button uk-button-danger" type="reset"><span uk-icon="icon: close"></span>Cancel</button>

                        </p>
                    </div>

                </div>
        </form>
            </div>


    <div class="col-md-4 uk-placeholder">
        <div uk-alert class="uk-alert-primary">
            <a class="uk-alert-close" uk-close></a>
            <p>After adding Practice Number, valid Doctor information will be displayed below.</p>
        </div>
        <span id="show_info"></span>
        <table class="uk-table uk-table-striped uk-table-small">
            <thead><tr>
                <td>Dr Prac.No</td>
                <td>Disc?</td>
                <td>Contact</td>
                <td>Name</td>
            </tr></thead>

            <tbody id="my_doctors">

            </tbody>
        </table>


    </div>

</div>

<?php
include "footer.php";
?>

<datalist id="options">
    <option value="---">
</datalist>
<datalist id="dosage1">
    <option value="200mgx2">
    <option value="1000mg">
</datalist>
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