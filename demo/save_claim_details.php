<?php
session_start();
error_reporting(0);
define("access",true);
if(isset($_POST["btn"]) || isset($_POST["edit_btn"]))
{
}
else
{
    die("Invalid entry");
}
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
include ("header.php");
?>
<title>MCA | Save Details</title>
<?php
$client_id = (int)$_POST['client_id'];
$username=$control->loggedAs();
$policy_number = validateXss($_POST['policy_number']);
$member_name = strtoupper(validateXss($_POST['member_name']));
$member_surname = strtoupper($_POST['member_surname']);
$member_email = validateXss($_POST['member_email']);
$patient_name = strtoupper(validateXss($_POST['patient_name']));
if (!preg_match("/^([a-zA-Z \'\-]+)$/", $member_name) || !preg_match("/^([a-zA-Z \'\-]+)$/", $member_surname) || !preg_match("/^([a-zA-Z \'\-]+)$/", $patient_name)) {
    echo "<h3 align=\"center\" class=\"alert alert-danger\">Invalid member name/patient name</b></h3>";
    // echo "Test Pattern";
}
if(!filter_var($member_email,FILTER_VALIDATE_EMAIL) && !empty($member_email))
{
    die("Invalid email");
}
$member_cell = validateXss($_POST['cell_number']);
$member_telephone = validateXss($_POST['member_telephone']);
$id_number = validateXss($_POST['id_number']);
$scheme_number = validateXss($_POST['member_number']);
$medical_scheme = filter_var($_POST['medical_scheme'],FILTER_SANITIZE_STRING);
$scheme_option = validateXss($_POST['scheme_option']);
$d_o_b = validateXss($_POST['d_o_b']);
$medication_value = validateXss($_POST['medication_value']);
$fusion_done = validateXss($_POST['fusion_done']);
$dosage = validateXss($_POST['dosage']);
$codes = validateXss($_POST['codes']);
$nappi = validateXss($_POST['nappi']);
$person_email = validateXss($_POST['person_email']);
$account_number="";
$doctors = validateXss($_POST['doctors']);
$start_date = validateXss($_POST['start_date']);
$start_date = date('Y-m-d',strtotime($start_date));
$end_date = validateXss($_POST['end_date']);
$end_date = date('Y-m-d',strtotime($end_date));
$icd10 = validateXss($_POST['icd10']);
$icd10=strtoupper($icd10);
$pmb=0;
if($control->viewICD10Details($icd10)==true) {
    $pmb_data = $control->viewICD10Details($icd10);
    $pmb_code = $pmb_data["pmb_code"];
    $pmb_code = strlen($pmb_code);
    if (strlen($pmb_code) > 0) {
        $pmb=1;
    }
}
$charged_amnt = validateXss($_POST['charged_amount']);
$scheme_paid = validateXss($_POST['scheme_paid']);
$client_gap = validateXss($_POST['client_gap_amount']);
$member_portion = validateXss($_POST['member_portion']);
$claim_number = validateXss($_POST['claim_number']);
$emergency = validateXss($_POST['emergency']);
$patient_gender = validateXss($_POST['patient_gender']);
if(isset($_POST["btn"]))
{
    $member_id=0;
    try {
        if ($control->validateMember($policy_number, $id_number, $client_id) == true) {
            $member_data = $control->validateMember($policy_number, $id_number, $client_id);
            $olduser = $member_data["entered_by"];
            $member_id = $member_data["member_id"];
            echo "<h4 align='center' style='color: red;font-weight: bolder'>$member_name $member_surname was already loaded by $olduser</h4>";
        } else {
            if ($control->callInsertMember($client_id, $policy_number, $member_name, $member_surname, $member_email, $member_cell, $member_telephone, $id_number, $scheme_number, $medical_scheme, $scheme_option, $account_number, $username)) {
                $member_id = $control->viewLatestMember($username);
            } else {
                die("<h4 align='center' style='color: red;font-weight: bolder'>There is an loading the member</h4>");
            }

        }
        if ($member_id > 0) {
            if ($control->validateClaim($claim_number, $client_id) > 0) {
                die("<h4 align='center' style='color: red;font-weight: bolder'>This is a duplicate claim</h4>");
            } else {
                $claim_id = 0;
                if ($control->callInsertClaim($member_id, $claim_number, $start_date, $icd10, $pmb, $charged_amnt, $scheme_paid, $member_portion, $username, $emergency, $username, $end_date, $client_gap, "", $medication_value, $d_o_b, $fusion_done, $dosage, $codes, $nappi, $person_email, $patient_gender, "")) {
                    $claim_id = $control->viewLatestClaim($username);
                    $patres = $control->callInsertPatient($claim_id, $patient_name, $username);
                    $doc_array = array();
                    $doc_array = explode(",", $doctors);
                    $doc_count = count($doc_array);
                    for ($i = 0; $i < $doc_count; $i++) {
                        if (!empty($doc_array[$i])) {
                            $control->callInsertClaimDoctor($doc_array[$i], $claim_id, $username);
                        }
                    }
                    if($client_id==31)
                    {
                        $as=$control->callInsertAspen($claim_id);
                    }
                    echo "<div class=\"uk-alert-success\" uk-alert style='width: 50%; margin-right: auto;margin-left: auto;position: relative'><a class=\"uk-alert-close\" uk-close></a><h3 align='center'>New Claim Successfully added.</h3>";
                    $path = (int)$client_id == 31 ? "view_aspen.php" : "case_details.php";
                    echo "<h4 align='center'> <form action='$path' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                    echo "<h4 align='center'><button type=\"submit\" class=\"uk-button-primary uk-button-small\" name=\"btn\">View Claim</button></h4>";
                    echo "</form></h4>";
                    echo "<h4 align='center'> <form action='edit_case.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                    echo "<h4 align='center'><button type=\"submit\" class=\"uk-button-primary uk-button-small\" name=\"btn\">Edit Claim</button></h4>";
                    echo "</form></h4><hr>";

                    echo "</div>";
                } else {
                    die("<h4 align='center' style='color: red;font-weight: bolder'>There is an error loading the claim</h4>");
                }

            }
        } else {
            die("<h4 align='center' style='color: red;font-weight: bolder'>Failed to find this member</h4>");
        }
    }
    catch (Exception $e)
    {
        die("<h4 align='center' style='color: red;font-weight: bolder'>There is an Error -> </h4>".$e->getMessage());
    }

}
elseif (isset($_POST["edit_btn"]))
{
    if($username==$control->loggedAs() || $control->isTopLevel())
    {}
    else
    {
        die("Invalid access");
    }
    $claim_id=(int)$_POST["claim_id"];
    $control->claim_id=$claim_id;
    $data=$control->viewSingleClaim($claim_id);
    $dbclaim_number=$data["claim_number"];
    $dbpolicy_number=$data["policy_number"];
    $member_id=(int)$data["member_id"];
    $dbclient_id=(int)$data["client_id"];
    $dbowner_name=$data["username"];
    $dbcreated_by=$data["createdBy"];
    $dbmember_name=$data["first_name"];
    $dbmember_surname=$data["surname"];
    $dbid_number=$data["id_number"];
    $dbmember_email=$data["email"];
    $dbtelephone=$data["telephone"];
    $dbcell=$data["cell"];
    $dbpatient=$data["patient_name"];
    $dbmedical_scheme=$data["medical_scheme"];
    $dbscheme_option=$data["scheme_option"];
    $dbopen_reason=$data["open_reason"];

    $dbmember_number=$data["scheme_number"];
    $dbpmb=(int)$data["pmb"];
    $dbemergency=(int)$data["emergency"];
    $dbicd10=$data["icd10"];
    $dbdate_reopened=$data["date_reopened"];
    $dbdate_closed=$data["date_closed"];
    $dbstart_date=$data["Service_Date"];
    $dbend_date=$data["end_date"];
    $dbclaim_status=$data["Open"];
    $dbcase_status=(int)$data["Open"];
    $dbquality=(int)$data["quality"];
    $dbcharged_amnt=$data["charged_amnt"];
    $dbscheme_paid=$data["scheme_paid"];
    $dbheader_gapamount=$data["client_gap"];
    $dbheader_memberportion=$data["gap"];
    $dbheader_scheme_savings=$data["savings_scheme"];
    $dbheader_discount_savings=$data["savings_discount"];

    if($control->isTopLevel()) {
        $savings_discount = (double)$_POST['savings_discount'];
        $savings_scheme = (double)$_POST['savings_scheme'];
        $current_username = validateXss($_POST['owner']);
        $open_reason = validateXss($_POST['open_reason']);
        $claim_status = (int)$_POST['claim_status'];
    }
    else
    {
        if ($username==$dbowner_name)
        {
            $savings_discount = (double)$dbheader_discount_savings;
            $savings_scheme = (double)$dbheader_scheme_savings;
            $current_username = $dbowner_name;
            $open_reason = "";
            $claim_status = 1;
        }
        else
        {
            die("Invalid access");
        }

    }
    $reopened_date=$claim_status!=(int)$dbclaim_status?date("Y-m-d H:i:s"):$dbdate_reopened;
    if($dbcase_status==5)
    {
        $claim_status = 5;
    }
    if($control->callEditMember($member_id,$client_id,$policy_number,$medical_scheme,$scheme_number,$id_number,$member_name,$member_surname,$member_telephone,$member_cell,$member_email,$scheme_option))
    {
        if($control->callEditClaim($claim_id,$claim_number,$start_date,$end_date,$icd10,$pmb,$charged_amnt,$scheme_paid,$member_portion,$current_username,$claim_status,$emergency,$savings_discount,$savings_scheme,$client_gap,$medication_value,$d_o_b,$fusion_done,$dosage,$codes,$nappi,$person_email,$reopened_date,$patient_gender,$open_reason))
        {
            if($dbcase_status==0 && $claim_status==1)
            {
                $control->callInsertReOpenedCases($claim_id,"System User. ".$open_reason,$username,$dbdate_closed,$dbheader_scheme_savings,$dbheader_discount_savings);
            }
            $doc_array=array();

            $doc_array=explode(",",$doctors);
            $doc_count=count($doc_array);


            for($i=0;$i<$doc_count;$i++)
            {
                $tt=(int)$doc_array[$i];
                if($tt>0)
                {
                    $control->callInsertClaimDoctor($doc_array[$i], $claim_id, $username);
                }

            }
            if(strlen($patient_name)>0)
            {
                $control->callDeletePatient($claim_id);
                $patres = $control->callInsertPatient($claim_id, $patient_name, $username);
            }
            echo "<div class=\"uk-alert-success\" uk-alert style='width: 50%; margin-right: auto;margin-left: auto;position: relative'><a class=\"uk-alert-close\" uk-close></a><h3 align='center'>You have successfully updated your case</h3>";
            $path = (int)$client_id == 31 ? "view_aspen.php" : "case_details.php";
            echo "<h4 align='center'> <form action='$path' method='post' />";
            echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
            echo "<h4 align='center'><button type=\"submit\" class=\"uk-button-primary uk-button-small\" name=\"btn\">View Claim</button></h4>";
            echo "</form></h4>";
            echo "<h4 align='center'> <form action='edit_case.php' method='post' />";
            echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
            echo "<h4 align='center'><button type=\"submit\" class=\"uk-button-primary uk-button-small\" name=\"btn\">Edit Claim</button></h4>";
            echo "</form></h4><hr>";

            echo "</div>";
            $logs=$control->callInsertClaimLogs($claim_id,$current_username,$dbclient_id,$dbpolicy_number,$dbclaim_number,$dbmedical_scheme,$dbmember_number,$dbid_number,$dbstart_date,$dbicd10,$dbcharged_amnt,$dbscheme_paid,$dbheader_memberportion,$dbmember_name,$dbmember_surname,$dbheader_scheme_savings,$dbheader_discount_savings,$dbtelephone,$dbcell,$dbmember_email,$dbscheme_option,$dbemergency,$dbowner_name);

        }
        else{
            die("<h4 align='center' style='color: red;font-weight: bolder'>Failed to update the claim</h4>");
        }
    }
    else{
        die("<h4 align='center' style='color: red;font-weight: bolder'>Failed to update the member</h4>");
    }


}
else
{
    die ("Invalid Request");
}

?>
<?php
include "footer.php";
?>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
