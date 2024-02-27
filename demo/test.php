<?php
if(!defined('access')) {
    die('Access not permited');
}
class Validate
{
    function __construct()
    {
        if(!$this->isLogged() || !in_array($this->myRole(),$this->allRoles()))
        {
            header("Location: logout.php");
            die();
        }
    }
    function allRoles()
    {
        return ["admin","controller","claims_specialist","gap_cover"];
    }
    function isLogged()
    {
        return isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])?true:false;
    }
    function myRole()
    {
        return isset($_SESSION['level']) && !empty($_SESSION['level'])?$_SESSION['level']:"unknown";
    }
 function loggedAsName()
    {
        return $_SESSION['fullname'];
    }
  function isSplit()
    {
        return $_SESSION["ennvironment"]=="split"?true:false;
    }
  function isBI()
    {
        return $_SESSION["ennvironment"]=="bi"?true:false;
    }
    function loggedAs()
    {
        return $_SESSION['user_id'];
    }
    function otherRole()
    {
        return $_SESSION['gap_admin'];
    }
    function isInternal()
    {
        $roles=["admin","controller","claims_specialist"];
        return in_array($this->myRole(),$roles)?true:false;
    }
    function isTopLevel()
    {
        $roles=["admin","controller"];
        return in_array($this->myRole(),$roles)?true:false;
    }
    function isAdmin()
    {
        return $this->myRole()=="admin"?true:false;
    }
    function isController()
    {
        return $this->myRole()=="controller"?true:false;
    }
    function isClaimsSpecialist()
    {
        return $this->myRole()=="claims_specialist"?true:false;
    }
    function isGapCover()
    {
        return $this->myRole()=="gap_cover"?true:false;
    }
    function isGapCoverAdmin()
    {
        return $_SESSION["gap_admin"]=="admin"?true:false;
    }
    function isAssessor()
    {
        return $_SESSION["gap_admin"]=="assessor"?true:false;
    }

}
require "DBConnect.php";
$db=new DBConnect();

Class controls extends Validate
{
    public $db_action;
    public $claim_id;
    public $header_chargedamount;
    public $header_schemeamount;
    public $header_gapamount;
    public $header_memberportion;
    public $case_status;
    public $member_email;
    public $member_name;
    public $member_surname;
    public $client_name;
    public $medical_scheme;
    public $consent_description;
    public $owner_name;
    public $member_number;
    public $claim_number;
    public $header_scheme_savings;
    public $header_discount_savings;
    public $sla;
    public $claim_date_entered;
    public $validatedDoctors;
    public $validate8days;
    public $qa_disabled;
    public $clinical_disabled;
    public function __construct()   {
        parent::__construct();
        global $db;
        $this->db_action=$db;
        $this->db_action->claim_id=$this->claim_id;
    }
    public function viewAllClaims($role,$pageLimit ,$setLimit,$username,$search_value="",$count=0,$txt_catergory=0)
    {
        try {
            $where = "WHERE ";
            $catergory_condition="";
            if($txt_catergory==1){$catergory_condition=" AND a.Open=1";}
            elseif($txt_catergory==2) {$catergory_condition=" AND a.Open=0";}
            elseif($txt_catergory==3){$catergory_condition=" AND a.pmb=1";}
            elseif($txt_catergory==4) {$catergory_condition=" AND a.pmb=0";}
            if (strlen($search_value) > 0) {
                $where = "WHERE (b.first_name like :search OR b.surname like :search OR a.claim_number like :search OR a.claim_number1 like :search OR b.medical_scheme like :search OR 
b.policy_number like :search OR c.client_name like :search OR a.username like :search OR b.scheme_number like :search OR a.date_closed LIKE :search OR b.id_number LIKE :search) AND ";
                $search_value="%$search_value%";
            }

            if ($this->isClaimsSpecialist()) {
                $condition = "(username = :num)";
            } else if ($this->isGapCover()) {
                $username = (int)$this->db_action->selectClientID($username);
                $condition = "(c.client_id = :num AND Open<>2)";

                if ($username == 3) {
                    $condition = "(c.client_id=15 OR c.client_id=27 OR c.client_id = :num) AND Open<>2";
                }
                if ($username == 32) {
                    $condition = "(c.client_id=26 OR c.client_id=21 OR c.client_id=:num) AND Open<>2";
                }
                if ($username == 16) {
                    $condition = "(c.client_id=27 OR c.client_id = :num) AND Open<>2";
                }

            } else if ($this->isTopLevel()) {
                $condition = "1";
            }
            $condition = $where . $condition.$catergory_condition;

            return $this->db_action->selectAllClaims($pageLimit, $setLimit, $condition, $search_value, $username, $count,$this->myRole());
        }
        catch (Exception $e)
        {
            echo "There is an Error : ".$e->getMessage();
            return 0;
        }
    }

    public function viewSingleClaim($claim_id)
    {
        return $this->db_action->getClaimHeader($claim_id);
    }
    public function viewAllDoctors($pageLimit, $setLimit, $search_value, $count)
    {
        try {
            $condition = "WHERE 1";
            if (strlen($search_value) > 0) {
                $condition = "WHERE (name_initials like :search OR surname like :search OR telephone like :search OR practice_number like :search OR email like :search)";
                $search_value="%$search_value%";
            }
            return $this->db_action->selectAllDoctors($pageLimit, $setLimit, $condition, $search_value, $count);
        }
        catch (Exception $e)
        {
            echo "There is an Error : ".$e->getMessage();
            return 0;
        }
    }
    public function viewNoNotesClaims($condition,$val)
    {
        return $this->db_action->getNoNotesClaims($condition,$val);
    }
    public function viewUserById($id)
    {
        return $this->db_action->getUserById($id);
    }
    function viewSubscription($email)
    {
        return $this->db_action->getSubscription($email);
    }
    public function callInsertVisitLogs($user,$url)
    {
        return $this->db_action->insertVisitLogs($user,$url);
    }
    public function viewNotesClaims($condition,$val)
    {
        return $this->db_action->getNotesClaims($condition,$val);
    }
    public function viewUser($username)
    {
        return $this->db_action->getClaimSpecialist($username);
    }
    public function viewDoctor($practice_number)
    {
        return $this->db_action->getDoctorDetails($practice_number);
    }
    public function viewClaimline($claim_id,$practice_number)
    {
        return $this->db_action->getClaimLines($claim_id,$practice_number);
    }
    public function viewEachClaimLine($claim_line_id)
    {
        return $this->db_action->getClaimLine($claim_line_id);
    }
    public function moneyformat($val)
    {
        return number_format($val,2,'.',' ');
    }
    public function viewClaimDoctor($claim_id)
    {
        return $this->db_action->getClaimDoctors($claim_id);
    }
    public function viewZeroAmounts($condition,$val)
    {
        return $this->db_action->getZeroAmounts($condition,$val);
    }
    public function view4DaysMembers($condition,$val)
    {
        return $this->db_action->get4DaysMembers($condition,$val);
    }
    public function viewUpdatedDocs($condition,$val)
    {
        return $this->db_action->getUpdatedDocs($condition,$val);
    }
    public function viewNotes($claim_id)
    {
        return $this->db_action->getNotes($claim_id);
    }
    public function viewFeedback($claim_id)
    {
        return $this->db_action->getFeedback($claim_id);
    }
    public function viewTariffDesc($tariff)
    {
        return $this->db_action->getTarrifDesc($tariff);
    }
    public function viewIcd10Desc($icd10)
    {
        return $this->db_action->getIcd10Desc($icd10);
    }
    public function viewPreassessment($condition=":username",$val=1)
    {
        return $this->db_action->getPreassessment($condition,$val);
    }
    public function viewOpenClaims($condition=":username",$val="1")
    {
        return $this->db_action->getOpenClaims($condition,$val);
    }
    public function viewLeads($condition=":username",$val=1)
    {
        return $this->db_action->getLeads($condition,$val);
    }
    public function viewNewClaims($condition=":username",$val="1")
    {
        return $this->db_action->getNewClaims($condition,$val);
    }
    public function viewDeclineReasons()
    {
        return $this->db_action->getDeclineReasons();
    }
  function viewViewReasons()
    {
        return $this->db_action->getViewReasons();
    }
    public function viewQA($condition = "a.username = :num",$val1="1",$rolex="cs")
    {
        return $this->db_action->getQA($condition,$val1,$rolex);
    }
  function callUpdatePatient($claim_id,$key,$value)
    {
        return $this->db_action->updatePatient($claim_id,$key,$value);
    }
    public function viewClinicalReview($condition = "a.username = :num",$val1="1")
    {
        return $this->db_action->getClinicalReview($condition,$val1);
    }
    public function viewAveragethisClaims($date,$condition=":username",$val="1")
    {
        return $this->db_action->getAveragethisClaims($date,$condition,$val);
    }
    public function viewGetSavings($date,$condition=":username",$val="1")
    {
        return $this->db_action->getSavings($date,$condition,$val);
    }
    public function viewClosedThisMonth($condition=":username",$val="1")
    {
        return $this->db_action->getClosedThisMonth($condition,$val);
    }
    public function viewEnteredthisClaims($date,$condition=":username",$val="1")
    {
        return $this->db_action->getEnteredthisClaims($date,$condition,$val);
    }
    public function viewDocuments($claim_id)
    {
        return $this->db_action->getClaimDocuments($claim_id);
    }
    public function viewClinicalNotes($claim_id)
    {
        return $this->db_action->getClinicalNotes($claim_id);
    }
  public function viewOpenNew($condition=":username",$val="1")
    {
        return $this->db_action->getOpenNew($condition,$val);
    }
    public function viewValidUsers()
    {
        return $this->db_action->getValidUsers();
    }
    public function callInsertDoctorLogs($doctor_id,$username)
    {
        return $this->db_action->insertDoctorLogs($doctor_id,$username);
    }
    public function callUpdateDoctorDetailsKey($doc_id,$key,$value)
    {
        return $this->db_action->updateDoctorDetailsKey($doc_id,$key,$value);
    }
    public function viewSwitcClaims($claim_id,$practice_number)
    {
        return $this->db_action->getSwitcClaims($claim_id,$practice_number);
    }
    public function callCloseSwitchClaim($claim_id,$date_closed,$claim_number)
    {
        return $this->db_action->closeSwitchClaim($claim_id,$this->loggedAsName(),$date_closed,$claim_number);
    }
    public function viewSwitchDoctors($claim_id)
    {
        return $this->db_action->getSwitchDoctors($claim_id);
    }
    public function viewSwitchSingle($claim_id)
    {
        return $this->db_action->getSwitchSingle($claim_id);
    }
    public function viewReopenedClaim($claim_id)
    {
        return $this->db_action->getReopenedClaim($claim_id);
    }
    public function viewDoctorDiscount($practice_number)
    {
        return $this->db_action->getDoctorDiscount($practice_number);
    }
  function viewTemplate($template_name)
    {
        return $this->db_action->getTemplate($template_name);
    } 
    public function viewSplitSingle($doctor_id)
    {
        return $this->db_action->getSplitSingle($doctor_id);
    }
    public function viewSplitClaimlinesDoctor($claim_id,$hospital_name)
    {
        return $this->db_action->getSplitClaimlinesDoctor($claim_id,$hospital_name);
    }
    public function viewErrorDetails($error_code)
    {
        return $this->db_action->getErrorDetails($error_code);
    }
    function viewPendingSplitClaims($status,$fields,$row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
return $this->db_action->getPendingSplitClaims($status,$fields,$row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue);
    }
    public function viewSplitFiles()
    {
        return $this->db_action->getSplitFiles();
    }
    public function viewSplitTotals($startdate,$enddate,$val)
    {
        return $this->db_action->getSplitTotals($startdate,$enddate,$val);
    }
    public function viewAllSplitClaimsDoctors($status,$pageLimit ,$setLimit,$search_value="",$count=0,$archived=0)
    {
        try {
            $where = "WHERE ";
            if (strlen($search_value) > 0) {
                $where = "WHERE (a.loyalty_number like :search OR a.claim_number like :search OR b.beneficiary_id_number like :search OR b.membership_number like :search OR b.beneficiary_name like :search) AND ";
                $search_value="%$search_value%";
            }
            $condition = "1";
            $condition = $where . $condition;
 if($archived>0)
            {
                $condition.=" AND archived_at IS NOT null";
            }
            return $this->db_action->getAllSplitClaimsDoctors($status,$pageLimit, $setLimit, $condition, $search_value, $count);
        }
        catch (Exception $e)
        {
            echo "There is an Error : ".$e->getMessage();
            return 0;
        }
    }
    public function viewSplitFileClaims($filename)
    {
        return $this->db_action->getSplitFileClaims($filename);
    }
    public function viewHospitalNames($split_claim_id)
    {
        return $this->db_action->getHospitalNames($split_claim_id);
    }
  function viewSplitCopayments($claim_id)
    {
        return $this->db_action->getSplitCopayments($claim_id);
    }
    public function callCloseSplit($doctor_id,$status,$date_closed,$note,$claim_number)
    {
        $closed_by=$this->loggedAsName();
        return $this->db_action->closeSplit($doctor_id,$status,$date_closed,$note,$claim_number,$closed_by);
    }
public function viewSeamlessClaims($pageLimit ,$setLimit,$search_value="",$count=0,$client="Kaelo",$open=0)
    {
        try {
            $where = "WHERE ";
            if (strlen($search_value) > 0) {
                $where = "WHERE (a.claim_number like :search OR a.claim_number1 like :search OR b.policy_number like :search OR b.first_name like :search OR b.surname) AND ";
                $search_value="%$search_value%";
            }
            $condition = "1";
            if($client=="Kaelo")
            {
                $condition = "(b.client_id=3 OR b.client_id=15)";
            }
            elseif ($client=="Western")
            {
                $condition = "b.client_id=16";
            }
            $xcon=$open==0?" AND a.Open=0":" AND a.Open<>0";
            $xcon.=" AND a.charged_amnt<>a.scheme_paid";
            $condition = $where . $condition.$xcon;

            return $this->db_action->getSeamlessClaims($pageLimit, $setLimit, $condition, $search_value, $count);
        }
        catch (Exception $e)
        {
            echo "There is an Error : ".$e->getMessage();
            return 0;
        }
    }
    public function viewDoctorLogs($practice_number)
    {
        return $this->db_action->getDoctorLogs($practice_number);
    }
    public function viewDoctorNotes($doctor_id)
    {
        return $this->db_action->getDoctorNotes($doctor_id);
    }
    public function viewDoctorDetailsUsingId($doctor_id)
    {
        return $this->db_action->getDoctorDetailsUsingId($doctor_id);
    }
 public function viewValidationsInd($id)
    {
        return $this->db_action->getValidationsInd($id);
    }
    public function viewClients()
    {
        return $this->db_action->getClients();
    }
    public function viewClaimSchemes()
    {
        return $this->db_action->getClaimSchemes();
    }
    public function viewAllICD10()
    {
        return $this->db_action->getAllICD10();
    }
    public function viewICD10Details($icd10)
    {
        return $this->db_action->getICD10Details($icd10);
    }
  function viewValidations()
    {
        return $this->db_action->getValidations();
    }
    public function isPMB($icd10_code)
    {
        $pmbstatus1=false;
        if($this->viewICD10Details($icd10_code)==true)
        {
            $datax=$this->viewICD10Details($icd10_code);
            if (strlen($datax["pmb_code"]) > 1) {
                $pmbstatus1=true;
            }
        }
        return $pmbstatus1;
    }
    public function viewSchemeOptions($scheme_name)
    {
        return $this->db_action->getSchemeOptions($scheme_name);
    }
    public function generateClaimNumber($client_id)
    {
        return $this->db_action->getGeneratedClaimNumber($client_id);
    }
public function viewAvailableQAFeedback($dat,$claim_id)
    {
        return $this->db_action->getAvailableQAFeedback($dat,$claim_id);
    }
    public function validateClaim($claim_number,$client_id)
    {
        return (int)$this->db_action->getClaimNumber($claim_number,$client_id);
    }
    public function callUpdateDoctor($claim_id,$practice_number,$key,$value)
    {
        return (int)$this->db_action->updateDoctor($claim_id,$practice_number,$key,$value);
    }
    public function viewCoding($tarrif,$icd10,$cpt4="")
    {
        return $this->db_action->getCoding($tarrif,$icd10,$cpt4);
    }
    public function noteLatestNote($owner)
    {
        return $this->db_action->getLatestNote($owner);
    }
    public function callUpdateSla($claim_id,$note_id)
    {
        return $this->db_action->updateSla($claim_id,$note_id);
    }
    function viewICD10five($keyword)
    {
        return $this->db_action->getICD10five($keyword);
    }
    public function viewSpecific($claim_id,$practice_number)
    {
        return $this->db_action->getSpecific($claim_id,$practice_number);
    }
    public function viewNoteId($claim_id,$owner)
    {
        $note_id=$this->noteLatestNote($owner);
        return $this->callUpdateSla($claim_id,$note_id);
    }
    public function callInsertDocuments($claim_id,$description,$size,$type,$random_number,$uploaded_by)
    {
        return $this->db_action->insertDocuments($claim_id,$description,$size,$type,$random_number,$uploaded_by);
    }
    public function callEditClaimLine($id,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$claimline_memberportion,$tariff_code,$primaryICDCode,$pmb,$benefit_description,$msg_code,$clmn_line_pmnt_status,$treatmentDate,$gap_aamount_line,$lng_msg_dscr,$msg_dscr)
    {
        return $this->db_action->editClaimLine($id,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$claimline_memberportion,$tariff_code,$primaryICDCode,$pmb,$benefit_description,$msg_code,$clmn_line_pmnt_status,$treatmentDate,$gap_aamount_line,$lng_msg_dscr,$msg_dscr);

    }
    public function callEditClaim($claim_id,$claim_number,$Service_Date,$end_date,$icd10,$pmb,$charged_amnt,$scheme_paid,$memberportion,$owner,$open,$emergency,$savings_discount,$savings_scheme,$client_gap,$medication_value,$d_o_b,$fusion_done,$dosage,$codes,$nappi,$person_email,$reopened_date,$patient_gender,$open_reason)

    {
        return $this->db_action->editClaim($claim_id,$claim_number,$Service_Date,$end_date,$icd10,$pmb,$charged_amnt,$scheme_paid,$memberportion,$owner,$open,$emergency,$savings_discount,$savings_scheme,$client_gap,$medication_value,$d_o_b,$fusion_done,$dosage,$codes,$nappi,$person_email,$reopened_date,$patient_gender,$open_reason);

    }
    public function callInsertClaimLogs($claim_id,$username,$client_id,$policy_number,$claim_number,$medical_scheme,$scheme_number,$id_number,$Service_Date,$icd10_desc,$charged_amnt,$scheme_paid,$gap,$member_name,$member_surname,$savings_scheme,$savings_discount,$memb_telephone,$memb_cell,$memb_email,$scheme_option,$emergency,$previous_owner)

    {
        return $this->db_action->insertClaimLogs($claim_id,$username,$client_id,$policy_number,$claim_number,$medical_scheme,$scheme_number,$id_number,$Service_Date,$icd10_desc,$charged_amnt,$scheme_paid,$gap,$member_name,$member_surname,$savings_scheme,$savings_discount,$memb_telephone,$memb_cell,$memb_email,$scheme_option,$emergency,$previous_owner);

    }
    public function callInsertClaimLine($claim_id,$practice_number,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$gap,$tariff_code,$service_date_from,$primaryICDCode,$pmb,$benefit_description,$gap_aamount_line,$msg_code,$treatmentDate,$createdBy)

    {
        return $this->db_action->insertClaimLine($claim_id,$practice_number,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$gap,$tariff_code,$service_date_from,$primaryICDCode,$pmb,$benefit_description,$gap_aamount_line,$msg_code,$treatmentDate,$createdBy);


    }
    public function callInsertClaim($member_id,$claim_number,$Service_Date,$icd10,$pmb,$charged_amnt,$scheme_paid,$gap,$username,$emergency,$entered_by,$end_date,$client_gap,$category_type,$medication_value,$patient_dob,$fusion_done,$code_description,$modifier,$reason_code,$person_email,$patient_gender,$sub_category)
    {
        return $this->db_action->insertClaim($member_id,$claim_number,$Service_Date,$icd10,$pmb,$charged_amnt,$scheme_paid,$gap,$username,$emergency,$entered_by,$end_date,$client_gap,$category_type,$medication_value,$patient_dob,$fusion_done,$code_description,$modifier,$reason_code,$person_email,$patient_gender,$sub_category);

    }
    public function callInsertMember($client_id,$policy_number,$first_name,$surname,$email,$cell,$telephone,$id_number,$scheme_number,$medical_scheme,$scheme_option,$account_number,$entered_by)

    {
        return $this->db_action->insertMember($client_id,$policy_number,$first_name,$surname,$email,$cell,$telephone,$id_number,$scheme_number,$medical_scheme,$scheme_option,$account_number,$entered_by);

    }
    public function callInsertClaimDoctor($practice_number,$claim_id,$entered_by)

    {
        return $this->db_action->insertClaimDoctor($practice_number,$claim_id,$entered_by);

    }
    public function callEditMember($member_id,$client_id,$policy_number,$medical_scheme,$scheme_number,$id_number,$member_name,$member_surname,$memb_telephone,$memb_cell,$memb_email,$scheme_option)


    {
        return $this->db_action->editMember($member_id,$client_id,$policy_number,$medical_scheme,$scheme_number,$id_number,$member_name,$member_surname,$memb_telephone,$memb_cell,$memb_email,$scheme_option);

    }
    public function viewLatestClaim($entered_by)

    {
        return $this->db_action->getLatestClaim($entered_by);

    }
    public function viewUserInformation($username)

    {
        return $this->db_action->getUserInformation($username);

    }
    public function view8days($claim_id)

    {
        return $this->db_action->get8days($claim_id);

    }
    public function viewClinicalNote($claim_id,$intervention_desc)

    {
        return $this->db_action->getClinicalNote($claim_id,$intervention_desc);

    }
    public function callInsertAspen($claim_id)

    {
        return $this->db_action->insertAspen($claim_id);

    }
  public function viewAPIURL($sender_id)
    {
        return $this->db_action->getAPIURL($sender_id);
    } 
    public function callInsertDoctorDiscount($dr_value,$discount_perc,$discount_value,$days_number,$practice_number,$username)

    {
        return $this->db_action->insertDoctorDiscount($dr_value,$discount_perc,$discount_value,$days_number,$practice_number,$username);

    }
    public function viewClaimIDfromClaimLine($claimline_id)

    {
        return $this->db_action->getClaimIDfromClaimLine($claimline_id);

    }
    public function clearClaimLine($claimline)

    {
        return $this->db_action->deleteClaimLine($claimline);

    }
    public function clearClaim($claim_id)

    {
        return $this->db_action->deleteClaim($claim_id);

    }
    public function call8days($note,$claim_id,$entered_by)

    {
        return $this->db_action->insert8days($note,$claim_id,$entered_by);

    }
    public function callInsertPatient($claim_id,$patient_name,$username)

    {
        return $this->db_action->insertPatient($claim_id,$patient_name,$username);

    }
    public function callInsertDoctorDetails($name_initials,$surname,$telephone,$admin_name,$practice_number,$gives_discount,$discipline,$displine_type,$subcode,$subdesr,$displine_id,$email,$dr_value,$days_number,$signed,$date_joined,$discount_v,$discount_perc,$discount_value,$username)

    {
        return $this->db_action->insertDoctorDetails($name_initials,$surname,$telephone,$admin_name,$practice_number,$gives_discount,$discipline,$displine_type,$subcode,$subdesr,$displine_id,$email,$dr_value,$days_number,$signed,$date_joined,$discount_v,$discount_perc,$discount_value,$username);

    }
    public function validateMember($policy_number,$id_number,$client_id)

    {
        return $this->db_action->getMember($policy_number,$id_number,$client_id);

    }
    public function viewConfirmOptions($claim_id)
    {
        return $this->db_action->getConfirmOptions($claim_id);
    }
    public function viewLatestClaimLine($claim_id)
    {
        return $this->db_action->getLatestClaimLine($claim_id);
    }
    public function viewLatestMember($entered_by)

    {
        return $this->db_action->getLatestMember($entered_by);

    }
    function checkAllDoctorsCPT4($claim_id)
    {
        $desipline_codes=["56","57","58","59","056","057","058","059"];
        $result="";
        foreach ($this->db_action->getClaimDoctors($claim_id) as $row)
        {
            $practice_number=$row["practice_number"];
            $cpt_code=$row["cpt_code"];
            $data=$this->db_action->getDoctorDetails($practice_number);
            $mydescpline=$data["disciplinecode"];
            if (in_array($mydescpline,$desipline_codes))
            {
                $result=$row["cpt_code"]."---".$cpt_code;
            }


        }
        return $result;
    }
    function checkDoctorTRCP1($practice_number,$cpt_code)
    {
        $desipline_codes=["56","57","58","59","056","057","058","059"];
        $trcp="TRCP";
        $data=$this->db_action->getDoctorDetails($practice_number);
        $mydescpline=$data["disciplinecode"];
        if(in_array($mydescpline,$desipline_codes)) {
            $nu = $this->db_action->getTRCP($trcp,$cpt_code);
            if($nu<1)
            {
                //die("Please enter the correct CPT4");
            }
        }
    }
    function checkDoctorTRCP2($practice_number)
    {
        $check=false;
        $desipline_codes=["56","57","58","59","056","057","058","059"];
        $data=$this->db_action->getDoctorDetails($practice_number);
        $mydescpline=$data["disciplinecode"];
        if(in_array($mydescpline,$desipline_codes))
        {
            $check=true;
        }
        return $check;

    }
    function amountsProcess($claim_id)
    {
        $data=$this->viewAmountsClaimLine($claim_id);
        $charged_amnt=(double)$data["charged_amnt"];
        $scheme_paid=(double)$data["scheme_paid"];
        $gap=$charged_amnt-$scheme_paid;
        $client_gap=$data["gap_aamount_line"];
        $c=$this->db_action->updateAmounts($claim_id,$charged_amnt,$scheme_paid,$gap,$client_gap);
    }
    function viewAmountsClaimLine($claim_id)
    {
        $data["charged_amnt"]=0;
        $data["scheme_paid"]=0;
        $data["gap"]=0;
        $data["gap_aamount_line"]=0;
        if($this->db_action->getTotalClaimLine($claim_id)==true)
        {
            $total_data=$this->db_action->getTotalClaimLine($claim_id);
            $data["charged_amnt"]=$total_data[0];
            $data["scheme_paid"]=$total_data[1];
            $data["gap"]=$total_data[2];
            $data["gap_aamount_line"]=$total_data[3];
        }
        return $data;
    }
    function checkBenefit($code)
    {
        $mess["lng"]="---";
        $mess["shrt"]="---";
        if($this->db_action->getRejectionCodes($code)==true) {
            $row=$this->db_action->getRejectionCodes($code);
            $mess["lng"]=$row[1];
            $mess["shrt"]=$row[2];
        }
        return $mess;
    }
    function viewSingleNote($id)
    {
        return $this->db_action->getSingleNote($id);
    }
    function viewConsent($scheme,$owner)
    {
        return $this->db_action->getConsent($scheme,$owner);
    }
    function callInsertNoteLog($id,$claim_id,$desc,$date_entered,$owner)
    {
        return $this->db_action->insertNoteLog($id,$claim_id,$desc,$date_entered,$owner);
    }
    function callInsertClosedLogs($claim_id,$scheme,$discount,$username)
    {
        return $this->db_action->insertClosedLogs($claim_id,$scheme,$discount,$username);
    }
    function callInsertFeedback($claim_id,$feedback,$username,$open)
    {
        return $this->db_action->insertFeedback($claim_id,$feedback,$username,$open);
    }
    function callUpdateClaimKey($claim_id,$key,$value,$condition="")
    {
        return $this->db_action->updateClaimKey($claim_id,$key,$value,$condition);
    }
    function callInsertClinicalNotes($claim_id,$txtclinicalnote,$username,$open)
    {
        return $this->db_action->insertClinicalNotes($claim_id,$txtclinicalnote,$username,$open);
    }
    function viewCheckClient($client_name)
    {
        return $this->db_action->checkClient($client_name);
    }
    function callInsertEscalationsLogs($claim_id,$escalation)
    {
        return $this->db_action->insertEscalationsLogs($claim_id,$escalation,$this->loggedAs());
    }
    function callInsertConfirm($claim_id,$notes,$option_id)
    {
        return $this->db_action->insertConfirm($claim_id,$notes,$option_id);
    }
    function viewClaimValidations($claim_id,$str)
    {
        return $this->db_action->getClaimValidations($claim_id,$str);
    }
    function viewLatestUser()
    {
        return $this->db_action->getLatestUser();
    }
    function viewActiveUsers()
    {
        return $this->db_action->getActiveUsers();
    }
    function viewClaimValue($date,$condition,$val)
    {
        return $this->db_action->getClaimValue($date,$condition,$val);
    }
 function viewFeedbackQADates()
    {
        return $this->db_action->getFeedbackQADates();
    }
    function viewMonthlySavings($date,$condition,$val)
    {
        return $this->db_action->getMonthlySavings($date,$condition,$val);
    }
    function callUpdateUser($username)
    {
        return $this->db_action->updateUser($username);
    }
    function callInsertReOpenedCases($claim_id,$reason,$entered_by,$date_closed,$last_scheme_savings,$last_discount_savings)
    {
        return $this->db_action->insertReOpenedCases($claim_id,$reason,$entered_by,$date_closed,$last_scheme_savings,$last_discount_savings);
    }
    function callDeletePatient($claim_id)
    {
        return $this->db_action->deletePatient($claim_id);
    }
    function callUpdateNote($note_id,$desc)
    {
        return $this->db_action->updateNote($note_id,$desc);
    }
    function viewClaimDate($claim_id,$date_reopened,$date_entered)
    {
        return $this->db_action->getClaimDate($claim_id,$date_reopened,$date_entered);
    }
    function callUpdateMemberKey($member_id,$key,$value)
    {
        return $this->db_action->updateMemberKey($member_id,$key,$value);
    }
public function callSFTPNegative()
    {
        return $this->db_action->updateSFTPNegative();
    }
    function callDeleteNote($note_id)
    {
        return $this->db_action->deleteNote($note_id);
    }
    function callInsertAPILog($claim_number,$description,$description_sec,$failed)
    {
        return $this->db_action->insertAPILog($claim_number,$description,$description_sec,$failed);
    }
    function viewOtherDoctors($claim_id,$practice_number)
    {
        return $this->db_action->getOtherDoctors($claim_id,$practice_number);
    }
    function callInsertNotes($claim_id,$intervention_desc,$username="",$reminder_time="0000-00-00 00:00:00",$reminder_status="",$claim_id1="",$current_practice_number="",$doc_name="",$consent_dest="",$status=1)
    {
        $username=$this->loggedAs();
        return $this->db_action->insertNotes($claim_id,$intervention_desc,$username,$reminder_time,$reminder_status,$claim_id1,$current_practice_number,$doc_name,$consent_dest,$status);
    }
    function callUpdateMm($claim_id,$descr)
    {
        $this->db_action->updateMm($claim_id,$descr);
    }
    function viewEmailCredentils()
    {
        return $this->db_action->getEmailCredentils();
    }
    function viewErrorOwls($condition = "a.username = :num",$val1="1")
    {
        return $this->db_action->getErrorOwls($condition,$val1);
    }
    function viewOwlsById($id)
    {
        return $this->db_action->getOwlsById($id);
    }
    function callUpdateErrorOwls($issue_id,$key,$value,$condition="")
    {
        return $this->db_action->updateErrorOwls($issue_id,$key,$value,$condition);
    }
  function callupdateQAKey($claim_id,$key,$value)
    {
        return $this->db_action->updateQAKey($claim_id,$key,$value);
    }
    function viewDisciplinecodes()
    {
        return $this->db_action->getDisciplinecodes();
    }
    function callInsertDoctorClaimLogs($claim_id,$practice_number,$type,$charged_amount,$scheme_amount,$gap_amount,$entered_by)
    {
        return $this->db_action->insertDoctorClaimLogs($claim_id,$practice_number,$type,$charged_amount,$scheme_amount,$gap_amount,$entered_by);
    }
    function callUpdateDocumentsKey($doc_id,$key,$value)
    {
        return $this->db_action->updateDocumentsKey($doc_id,$key,$value);
    }


    function sendOwlAPI($claim_number,$status,$date_entered,$intervention_description,$scheme_savings,$discount_savings,$pay_provider,$practice_number,$claimid,$url)
    {
        $messg="";
        $status=$status==1?"open":"closed";
        $pay_provider=$pay_provider=="yes" || $pay_provider=="no"?$pay_provider:"no";
        $myarr=array("claim_number"=>$claim_number,"status"=>$status,"date_entered"=>$date_entered,"intervention_description"=>$intervention_description,
            "scheme_savings"=>$scheme_savings,"discount_savings"=>$discount_savings,"pay_provider"=>$pay_provider,"provider_number"=>$practice_number,"claimedline_id"=>$claimid);
        $sendobj=json_encode($myarr);
        $data_string = $sendobj;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// where to post
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'authKey:e15c4c44-2ea3-4bc7-bc5d-5b7555bb9c63',
                'Content-Type", "application/raw',
                'Content-Type: application/json')
        );
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            $messg="<ul><li><span style='color: red'>(Connection error (connecting to the client) #:)</span></li></ul>";
            $this->callInsertAPILog($claim_number,"Connection error",$data_string,7);
        } else {
            $ffailed=3;
            $pos8 = strpos($result, "success");
            $pos9 = strpos($result, "Claimline ID");
            $pos10 = strpos($result, "Status");
            $jres=false;
            if($pos8 > 0 || ($pos9 > 0 && $pos10 > 0))
            {
            $jres=true;
            }
            if ($jres === false) {
                $ffailed=7;
                $messg="<ul><li><span style='color: red'> (Failed to sent to Client)<hr>Object received : $result</span></li></ul>";
            } else {
                $messg="<ul><li><span style='color: green'> (Response sent to Client)</span></li></ul>";
            }
            $resx=$this->callInsertAPILog($claim_number,$result,$data_string,$ffailed);
        }
        return $messg;
    }
    function sendEmail($mail,$from_email,$from_name,$from_password,$to_email,$to_name,$subject,$body,$attachment=0,$attachment_path="",$copy_email="")
    {
        $send=false;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $from_email;                 // SMTP username
        $mail->Password = $from_password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to_email, $to_name);     // Add a recipient
        if (filter_var($copy_email, FILTER_VALIDATE_EMAIL)) {
            $mail->addCC($copy_email, "System User");
        }
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        if($attachment>0)
        {
            $mail->AddAttachment($attachment_path);
        }

        if (!$mail->send()) {
            $send=false;
        }
        else {
            $send=true;
        }
        return $send;
    }
    function decryptIt($password) {
        $cryptKey="MCA201734X$";
        $qDecoded=openssl_decrypt($password,"AES-128-ECB",$cryptKey);
        return( $qDecoded );
    }
    function getWorkingDays($startDate,$endDate,$holidays){
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);


        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)

            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        //We subtract the holidays
        foreach($holidays as $holiday){
            $myholiday=date("Y")."-";
            $time_stamp=strtotime($myholiday.$holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }
    function checkDisciplineCodes($discipline_id)
    {
        global $conn;
        $data["code"]="";
        $data["subcode"]="";
        $data["descr"]="";
        $data["subdescr"]="";
        if($this->db_action->getDisciplinecode($discipline_id)==true) {
            $arr = $this->db_action->getDisciplinecode($discipline_id);
            $data["code"] = $arr[1];
            $data["subcode"] = $arr[2];
            $data["descr"] = $arr[3];
            $data["subdescr"] = $arr[4];
        }
        return $data;
    }
    function eightdays()
    {
        $this->validate8days=false;
        $today=date("Y-m-d H:i:s");
        $days=round($this->getWorkingDays($this->claim_date_entered,$today,$this->holidays()));
        if($days>7)
        {
            $this->validate8days=true;
        }

    }

    function holidays()
    {
        return array("01-01","03-21","03-29","04-01","04-27","05-01","06-17","08-09","09-24","12-16","12-25","12-26");
    }
    function rearrageArray($arr)

    {
        $uni=array_unique($arr);
        $all=array();
        foreach($uni as $uu)
        {
            $num=count(array_keys($arr, $uu));
            $inn=array("a"=>$uu,"num"=>$num);
            array_push($all,$inn);
        }
        return $all;
    }
    function checkMofifier($claim_id,$practice_number,$practicetype)
    {
        $check = false;
        if ($practicetype != "010" || $practicetype != "10") {
            $check = true;
            $count = 0;
            try {
                foreach ($this->db_action->getClaimLines($claim_id,$practice_number) as $row) {
                    $modifier = $row[3];
                    $arr = ["0109", "0192", "01970", "301", "302", "11041", "300", "303", "304", "305", "306", "307", "308", "400", "401", "402", "403", "404", "405", "406", "407", "408", "409", "410", "411", "490", "001", "0173", "130", "131", "132", "133", "134", "10010", "10020", "10090", "1100", "1101", "1102", "1103", "1110", "0174", "0175", "1031", "1037", "1039", "1071", "1188", "0145", "0146", "0147", "1221", "0148", "0149", "0153", "0161", "0162", "0163", "0164", "0017", "0166", "0167", "0168", "5783", "0169", "0190", "2392", "2616", "3009", "3010", "3035", "3117", "3251", "3252", "3280", "0191", "5793", "4587", "108", "109", "003", "004", "005", "006", "002", "014", "020", "025", "030", "230", "234", "238", "450", "708", "016", "018", "021", "023", "031", "044", "200", "201", "202", "203", "204", "205", "206", "207", "208", "209", "210", "211", "290", "309", "310", "311", "1010", "1011", "1012", "1013", "1015", "1020", "1021", "1022", "1023", "9429", "901", "903", "905", "01070"];

                    if (!empty($modifier)) {
                        if (!in_array($modifier, $arr)) {
                            $count++;
                        }
                    }
                    $modifier5 = "0005";
                    if ($modifier5 == $modifier) {
                        $count = -100;
                    }
                }
                if ($count < 2) {
                    $check = false;
                }
            } catch (Exception $d) {

            }
        }
        return $check;
    }

    function todayDate()
    {
        return date("Y-m-d");
    }
    function displaySLA($arr,$symbol,$color)
    {
        foreach($arr as $row)
        {
            $claim_id=$row["claim_id"];
            $sla_days=$row["notes"]=="No_Notes"?"(No Notes)":$row["days"];
            $date_entered=$row["date_entered"];
            $descr=$row["descr"];
            $claim_data=$this->viewSingleClaim($claim_id);
            $name =$claim_data["first_name"] . " " . $claim_data["surname"];
            //$policy = $claim_data["policy_number"];
            $claim_number = $claim_data["claim_number"];
            $medical_scheme = $claim_data["medical_scheme"];
            $claim_date_entered = $claim_data["date_entered"];
 $redata=$this->viewReopenedClaim($claim_id);
            $date_reopened=$redata==true?$redata["reopened_date"]:$claim_date_entered;
            $days=round($this->getWorkingDays($date_reopened,$this->todayDate(),$this->holidays()));
            $pmb = $claim_data["pmb"]==1?"Yes":"No";
            //$claim_status=$claim_data["Open"];
            $owner = $claim_data["username"];
            $client_name = $claim_data["client_name"];
            $path=$client_name=="Aspen"?"view_aspen.php":"case_details.php";
            $sla_days=(strlen($sla_days) < 2)?"0".$sla_days:$sla_days;
            $days=(strlen($days) < 2)?"0".$days:$days;
            echo "<tr style='color: $color'><td>$name</td><td>$claim_number</td><td title=\"$descr\">$symbol$sla_days</td><td>$date_entered</td><td>$days</td><td>$owner</td><td>$medical_scheme</td><td>$client_name</td><td>$pmb</td>";
            if($this->isInternal()) {
                echo "<td><form action='edit_case.php' method='post'><input type='hidden' name='claim_id' value='$claim_id'/><button name='btn' uk-icon=\"icon: pencil\" style='color:#54bc9c'></button></form></td>";
            }
            if($this->isGapCover() || $client_name=="Aspen" || $this->isTopLevel())
            {
                echo "<td><form action='$path' method='post'><input type='hidden' name='claim_id' value='$claim_id'/><button name='btn' uk-icon=\"icon: info\" style='color:#54bc9c'></button></form></td>";
            }
            else
            {
                echo "<td><a href=\"#modal-container\" title='view claim' uk-icon=\"icon: info\" onclick=\"viewClaim('$claim_id')\" uk-toggle></a></td>";
            }
            echo "</tr>";

        }
    }

    function generalSendAPI($url,$data_string,$claim_number,$status)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// where to post
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'authKey:e15c4c44-2ea3-4bc7-bc5d-5b7555bb9c63',
                'Content-Type", "application/raw',
                'Content-Type: application/json')
        );
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $ffailed=7;
        if ($err) {
            $messg="Failed";
        } else {

            $pos8 = strpos($result, "success");
            if ($pos8 === false) {
                $messg="Failed";
            } else {
                $ffailed=4;
                $messg="Success";
            }
        }
        $this->callInsertAPILog($claim_number,$result,$data_string,$ffailed,$status);
        return $messg;
    }
 function viewPendingQAUsers($dat)
    {
        return $this->db_action->getPendingQAUsers($dat);
    }
 function getMyImprovement($claim_id)
    {
        $improvement_description="";
        $arr=$this->db_action->getAutoFail($claim_id,"sla17,sla19,sla16,sla18,sla20,sla21,sla22,sla1,sla2,sla3,sla4,sla5,sla6,sla7,sla8,sla9,sla10,sla11,sla12,sla13,sla14");
        $key = array_search(0, $arr);
        if(!empty($key))
        {
            $improvement_description= $this->db_action->getQADescr($key);
        }
        else{
            $arr=$this->db_action->getAutoFail($claim_id,"data1,data2,data3,data4,data5");
            $key = array_search(0, $arr);

            if(!empty($key)) {
                $improvement_description= $this->db_action->getQADescr($key);
            }
            else{

                $xarr=$this->db_action->getOtherQA($claim_id,"calls1,calls2,calls3,calls4,calls5,calls6, calls7,calls8,calls9,calls10");
                $array = \array_filter($xarr, static function ($element) {
                    return $element >0;
                });

                array_multisort($array);
                $key = array_search ($array[0], $array);

                if(!empty($key)) {
                    $improvement_description = $this->db_action->getQADescr($key);
                }
            }
        }

        return $improvement_description;
    }
   function viewQACSQADescr($dat)
    {
        return $this->db_action->checkQACSQADescr($dat);
    }
   function viewQAClaims($dat,$username)
    {
        return $this->db_action->getQAClaims($dat,$username);
    }
  function callinsertQAFeedback($claim_id,$month_entered,$qa_position,$username,$improvement_area)
    {
        return $this->db_action->insertQAFeedback($claim_id,$month_entered,$qa_position,$username,$improvement_area);
    }
     public function viewDownloadSplitCompleted($startdate,$enddate,$val)
    {
        return $this->db_action->downloadSplitCompleted($startdate,$enddate,$val);
    }
   function viewQAFeedBackUsers($dat,$condition="1",$status="")
    {
        return $this->db_action->getQAFeedBackUsers($dat,$this->loggedAs(),$condition,$status);
    }
  function viewFeedbackQA($dat,$username)
    {
        return $this->db_action->getFeedbackQA($dat,$username);
    }
   function callupdateFeedbackQA($id,$action_plan,$comment)
    {
        return $this->db_action->updateFeedbackQA($id,$action_plan,$comment);
    }
  function callupdateFeedbackQAKey($key,$value,$username,$dat)
    {
        return $this->db_action->updateFeedbackQAKey($key,$value,$username,$dat);
    }
function generateRandomString()
    {
        $rand=rand(0,200000);
        return $rand;
    }
    function sendIcr($target_file,$pagerange)
{
         $license_code = "08C5C368-90FB-4ED8-A068-0DC39369918B";
        $username =  "ZMAC";
        $url = 'http://www.ocrwebservice.com/restservices/processDocument?gettext=true&outputformat=txt&newline=1';
        $filePath = $target_file;  
        $fp = fopen($filePath, 'r');
        $session = curl_init();
        curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_USERPWD, "$username:$license_code");
        curl_setopt($session, CURLOPT_UPLOAD, true);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($session, CURLOPT_TIMEOUT, 200);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
        curl_setopt($session, CURLOPT_INFILE, $fp);
        curl_setopt($session, CURLOPT_INFILESIZE, filesize($filePath));
        $result = curl_exec($session);

    $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        fclose($fp);    
        if($httpCode == 401) 
    {
           die('Unauthorized request');
        }

    $data = json_decode($result);

        if($httpCode != 200) 
    {
       // OCR error
           die($data->ErrorMessage);
        }
     
return $data;
}
function exploadExted($str):bool
{
    $ret = false;   
    if(strpos($str,".")>-1)
    {
        $my4=explode(".",$str);      
        if(strlen($my4[1])>2)
        {            
            $ret = true;

        }
    }
      return $ret;  
}
function rearrF($arr)
{
$inarr=[];
foreach ($arr as $val) {
    if(strpos($val, ".")>-1 || strpos($val, "/") || strlen($val)==4 || strlen($val)==3)
    {
        array_push($inarr, $val);
    }
}
return $inarr;
}
function getSchemeAmntTurn($xarr)
{
  $countx=count($xarr);
  $scheme=$xarr[$countx-2];
  return $scheme;
}
function getChargedTariffAmountTurn($xarr)
{    
    $tariff1=$xarr[2];
    $charged_amount1=$xarr[1];
      if($this->exploadExted($charged_amount1))
        {         
        $tariff1=substr($charged_amount1,-4);
        $charged_amount1=str_replace($tariff1,"",$charged_amount1);
      
        }
        elseif($this->exploadExted($tariff1))
        {
        $tariff1=substr($tariff1,0,4);        
        }

    $data["tariff"]=$tariff1;
    $data["charged_amount"]=$charged_amount1;    
    return $data;
}
function turnberryOcr($file_name)
{
$p=0;
$provider_name="Provider";
$practice_number="";
$total_charged=0;
$total_scheme=0;
$total_gap=0;
$count=0;
$prccount=0;
$clp="";
$jim=0;
 $doc=file_get_contents($file_name);
$line=explode("\n",$doc);
$line_count=count($line);
foreach($line as $newline){
    $count++;
    $newline=$this->strip_hidden_chars2($newline);     
    $arr=explode(" ", $newline);
    //echo $newline."<hr>";
   //print_r($arr);       
   
    
     if(strpos($newline,"PMB")>-1 && count($arr)>9) 
    {
        $prccount++;
        $clp="p".$prccount;
         $practice_number=substr($arr[8],-7);   
     
    }
    else
    {
          if(strpos($newline,"Benefit")>-1 && (strpos($newline,"Amt")>-1 || strpos($newline,"Charged")>-1 || strpos($newline,"Tariff")>-1))
    {
        $prccount++;
        $clp="p".$prccount;         
         echo $this->ocrTotalRowsProvider($practice_number,"",$clp);
         
    }

    }
 

     if(strpos($newline,"CLAIM NUMBER")>-1) 
    {
         $this->member_name=$arr[0]; 
         $this->member_surname=$arr[1]; 

     
    }
     if(count($arr)>8) 
     {
        $xarr=$this->rearrF($arr);
        //print_r($xarr);
         //echo $newline."<hr>";          
         if(strlen($xarr[0])>7)
         {
        $treat_date=substr($xarr[0],0,8);  
              
        $icd10=$this->case_status;  
        if($this->isValidDate($treat_date,'d/m/y'))
        {                
        $treat_date=$this->dateChangeFormat2($treat_date); 
        $datam=$this->getChargedTariffAmountTurn($xarr);
        $charged_amount=$datam["charged_amount"];        
        $treat_code=$datam["tariff"]; 
        $charged_amount=$this->convertToDouble($charged_amount);
        $scheme_amount=$this->convertToDouble($this->getSchemeAmntTurn($xarr));
        $gap=$charged_amount-$scheme_amount;
        $total_charged+=$charged_amount;
        $total_scheme+=$scheme_amount;
        $total_gap+=$gap;            
        echo $this->ocrRows($count,$practice_number,$treat_code,$treat_date,$icd10,$charged_amount,$scheme_amount,$gap,"turnberryicd",$clp);
        $jim=1;
        } 
}
     
     }
 
if($jim==1 && count($arr)<7)
{
     $total_charged=$this->moneyformat($total_charged);
        $total_scheme=$this->moneyformat($total_scheme);
        $total_gap=$this->moneyformat($total_gap);
           echo $this->ocrTotalRows($practice_number,$total_charged,$total_scheme,$total_gap,$clp."x");
             $total_charged=0;
$total_scheme=0;
$total_gap=0;
$jim=0;
$practice_number="";
}
}
}
function gapriskOcr($file_name)
{
$p=0;
$provider_name="";
$practice_number="";
$total_charged=0;
$total_scheme=0;
$total_gap=0;
$count=0;
$prccount=0;
$clp="";

 $doc=file_get_contents($file_name);
$line=explode("\n",$doc);
foreach($line as $newline){
    $newline=$this->strip_hidden_chars($newline); 
    
    $arr=explode(" ", $newline); 
    if(strpos($newline,"gaprisk")>-1 && strpos($newline,"-")>-1) 
    {
        $namearr=explode("-", $newline);
         $this->member_name=$namearr[1];
     //$this->policy_number="GAP".$this->get_string_between($newline, "GAP", "-");
    }
    if(strpos($newline,"Admission")>-1 && strpos($newline,"Date")>-1) 
    {
     $service_date=$this->strip_hidden_chars($this->get_string_between($newline, "Date", "Auth"));    
     $this->claim_date_entered=$this->dateChangeFormat($service_date);
    }
    if(strpos($newline,"Discharge")>-1 && strpos($newline,"Date")>-1) 
    {
     $end_date=$this->strip_hidden_chars($this->get_string_between($newline, "Date", "Total")); 
     $this->sla=$this->dateChangeFormat($end_date);
    }
    if(strpos($newline,"Professional")>-1 && strpos($newline,"ICD")>-1) 
    {
        $p=1;
           $total_charged=0;
$total_scheme=0;
$total_gap=0;
     $provider_name=$this->strip_hidden_chars($this->get_string_between($newline, "Professional", "ICD")); 
     $this->sla=$this->dateChangeFormat($end_date);
    }
     if(strpos($newline,"Practice")>-1 && strpos($newline,"Nr")>-1 && $p==1) 
    {
        $prccount++;
        $clp="p".$prccount;
    $practice_number=$this->getInt($newline);   
     echo $this->ocrTotalRowsProvider($practice_number,$provider_name,$clp);
     
    }    
    //echo $newline."<hr>";
 if(strpos($newline,"%")>-1 || (strpos($newline,"Not")>-1 && strpos($newline,"paid")>-1 && strpos($newline,"Scheme")>-1) || (strpos($newline,"Not")>-1 && strpos($newline,"PMB")>-1 && strpos($newline,"Covered")>-1))
    {
      $count++;
        $treat_date=substr($arr[0], 0, 10); 
    if($this->isValidDate($treat_date,'d/m/Y'))
    {
        $treat_date=$this->dateChangeFormat($treat_date);      
     if(strlen($arr[0])>12)
        {
  $treat_code=$arr[1];
        $icd10=$arr[2];
        $charged_amount=$this->convertToDouble($arr[3]);
        $scheme_amount=$this->convertToDouble($arr[4]);
        }   
elseif($arr[1]=="0") 
        {
        $treat_code=$arr[3];
        $icd10=$arr[4];
        $charged_amount=$this->convertToDouble($arr[5]);
        $scheme_amount=$this->convertToDouble($arr[6]);
        }
        else{ 
        $treat_code=$arr[2];
        $icd10=$arr[3];
        $charged_amount=$this->convertToDouble($arr[4]);
        $scheme_amount=$this->convertToDouble($arr[5]);
    }
        $gap=$charged_amount-$scheme_amount;
        $total_charged+=$charged_amount;
$total_scheme+=$scheme_amount;
$total_gap+=$gap;
        $this->case_status=$icd10;             
        echo $this->ocrRows($count,$practice_number,$treat_code,$treat_date,$icd10,$charged_amount,$scheme_amount,$gap,"",$clp);
    }
}
     if(strpos($newline,"Totals")>-1)
    {
        $total_charged=$this->moneyformat($total_charged);
        $total_scheme=$this->moneyformat($total_scheme);
        $total_gap=$this->moneyformat($total_gap);
           echo $this->ocrTotalRows($practice_number,$total_charged,$total_scheme,$total_gap,$clp."x");
             $total_charged=0;
$total_scheme=0;
$total_gap=0;
    }
}
}
function ocrRows($count,$practice_number,$treat_code,$treat_date,$icd10,$charged_amount,$scheme_amount,$gap,$icclass="gaprisk",$newclassprac="")
{
    $row="<tr id='$count'><td class='$practice_number $newclassprac'><input class='treat_code' value='$treat_code'></td><td class='$practice_number $newclassprac'><input class='treat_date' value='$treat_date'></td><td class='$practice_number $newclassprac'><input class='icd10 $icclass' value='$icd10'></td><td class='$practice_number $newclassprac'><input class='charged_amount' value='$charged_amount'></td><td class='$practice_number $newclassprac'><input class='scheme_amount' value='$scheme_amount'></td><td class='$practice_number $newclassprac'><input class='gap' value='$gap'></td><td><button class='uk-button uk-button-danger uk-button-small remove' tr='$count'>Remove</button></td></tr>";
    return $row;
}
function ocrTotalRows($practice_number,$total_charged,$total_scheme,$total_gap,$classname)
{
    $total_row="<tr><th colspan='3'><button class='uk-button uk-button-primary uk-button-small addall $classname' practice_number='$practice_number'>Save All</button></td><td><b>$total_charged</b></td><td><b>$total_scheme</b></td><td><b>$total_gap</b></td><td></td></tr>";
    return $total_row;
    }
    function ocrTotalRowsProvider($practice_number,$provider_name,$pracno)
{
    $provider_row="<tr><td colspan='6'><p align='center'>$provider_name <b><input class='prac_main' title='Practice Number' pracno='$pracno' value='$practice_number' placeholder='Enter practice number'></b></p></td></tr>";
    return $provider_row;
    }
function strip_hidden_chars($str)
{
    $chars = array("\r\n", "\n", "\r", "\t", "\0", "\x0B");
    $str = str_replace($chars,"~",$str);
    $str= preg_replace('/\s+/','^',$str);
    $str=str_replace("0~^~",' ',$str);
    $str=str_replace("^~^~",' ',$str);
    $str=str_replace("^",'',$str);
    $str=str_replace("~",'',$str);
    $str=preg_replace('/\s+/', ' ', $str);
    return $str;
}
function strip_hidden_chars2($str)
{
    $chars = array("\r\n", "\n", "\r", "\t", "\0", "\x0B");
    $str = str_replace($chars,"~",$str);
    $str= preg_replace('/\s+/','^',$str);
    $str=str_replace("0~^~",' ',$str);
    $str=str_replace("^~^~",' ',$str);
    $str=str_replace("~^~",' ',$str);
    $str=str_replace("^",'',$str);
    $str=str_replace("~",'',$str);
    $str=preg_replace('/\s+/', ' ', $str);
    return $str;
}
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function dateChangeFormat($date)
{
    if($this->isValidDate($date,'d/m/Y'))
    {
    return date_format(date_create_from_format('d/m/Y', $date), 'Y-m-d');
}
else
{
    return "-";
}
}
function dateChangeFormat2($date)
{
    return date_format(date_create_from_format('d/m/y', $date), 'Y-m-d');
}
function getInt($string)
{
    return preg_replace("/[^0-9]/", '', $string);
}
function convertToDouble($string)
{
    return (double)str_replace(',', '.', $string);
}
function isValidDate($date, $format = 'Y-m-d') {
    $dateTime = DateTime::createFromFormat($format, $date);
    return $dateTime && $dateTime->format($format) === $date;
}
}
?>
