<?php
if(!defined('access')) {
    die('Access not permited');
}
include("dbconn.php");
$con_main_db = connection("mca", "MCA_admin");
$con_doc_db = connection("doc","doctors");
$con_code_db = connection("cod", "Coding");
$con_seamless_db = connection("seamless", "seamless");
$conn=$con_main_db;
class DBConnect
{
    public $conn;
    public $conn1;
    public $conn2;
    public $conn3;
    public $claim_id;
    public function __construct()
    {
        global $con_main_db;
        global $con_doc_db;
        global $con_code_db;
        global $con_seamless_db;
        $this->conn = $con_main_db;
        $this->conn1 = $con_doc_db;
        $this->conn2 = $con_code_db;
        $this->conn3 = $con_seamless_db;
    }
    public function selectAllClaims($pageLimit, $setLimit, $condition, $search_value, $username, $count,$role)
    {
        $suuuarr=["admin","controller"];
        $limits = "";
        $fields = "COUNT(a.claim_id)";
        if ($count == 0) {
            $limits = "ORDER BY a.date_entered DESC LIMIT $pageLimit , $setLimit";
            $fields = "b.first_name, b.surname, b.policy_number, a.claim_number, a.savings_scheme, b.medical_scheme, b.scheme_option, a.date_closed, b.client_id, a.date_entered, 
a.Open, a.claim_id, a.username, a.savings_discount, b.scheme_number,a.sla,c.client_name,a.pmb,a.charged_amnt,a.scheme_paid,a.entered_by,a.emergency,a.quality";
        }
        $sql = "SELECT $fields FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id $condition AND (claim_type<>'R' OR claim_type is null) $limits";
        //echo $role.$sql;
        $stmt = $this->conn->prepare($sql);

        if(!in_array($role,$suuuarr))
        {
            $stmt->bindParam(':num', $username, PDO::PARAM_STR);
        }
        if (strlen($search_value) > 0) {
            $stmt->bindParam(':search', $search_value, PDO::PARAM_STR);
        }
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }
    public function selectAllDoctors($pageLimit, $setLimit, $condition, $search_value, $count)
    {
        $limits = "";
        $fields = "COUNT(doc_id)";
        if ($count == 0) {
            $limits = "ORDER BY doc_id  DESC LIMIT $pageLimit , $setLimit";
            $fields = "*";
        }
        $sql = "SELECT $fields FROM doctor_details $condition $limits";
        //echo $sql;
        $stmt = $this->conn->prepare($sql);
        if(strlen($search_value)>0)
        {
            $stmt->bindParam(':search', $search_value, PDO::PARAM_STR);
        }
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }

    public function selectClientID($client_name)
    {
        $stmt = $this->conn->prepare("SELECT client_id FROM clients WHERE client_name = :name");
        $stmt->bindParam(':name', $client_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getClaimHeader($claim_id)
    {
        $stmt = $this->conn->prepare("SELECT a.claim_id,a.member_id,a.claim_number,a.Service_Date,a.pmb,a.icd10,a.icd10_desc,a.charged_amnt,a.scheme_paid,a.gap,a.Open,a.new,
       a.savings_scheme,a.savings_discount,a.date_closed,a.emergency,a.username,a.end_date,a.entered_by,a.date_entered,a.recordType,a.senderId,a.createdBy,a.client_gap,a.patient_number,
       a.claim_number1,a.member_contacted,a.claim_type,a.date_reopened,a.coding_checked,a.is_atheniest,a.provider_zf,a.icd10_emergency,a.category_type,a.saoa,a.sla,a.sla_note, 
       a.quality,a.medication_value,a.patient_dob,a.patient_idnumber,a.fusion_done,a.contact_person_email,a.code_description,a.reason_code,a.modifier,a.savings_catergory,a.patient_gender,a.tarrif_0614,a.tarrif_0614x,a.preassessor,
       a.open_reason,a.eightdays,b.consent_descr,b.client_id,b.policy_number,b.first_name,b.surname,b.email,b.cell,b.telephone,b.id_number,b.scheme_number,b.medical_scheme,b.scheme_option,
       b.broker,c.client_name,d.patient_name,c.client_email FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id LEFT JOIN patient as d ON a.claim_id=d.claim_id WHERE a.claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getClaimDoctors($claim_id)
    {
        $stmt = $this->conn->prepare("SELECT practice_number,savings_scheme,savings_discount,doc_name,doc_charged_amount,doc_scheme_amount,doc_gap,pay_doctor,cpt_code, 
        display,claimedline_id,treatement_date,provider_invoicenumber,value_added_savings FROM doctors WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function insertConfirm($claim_id,$notes,$option_id)
    {

        $stmt = $this->conn->prepare('INSERT INTO confirm_options(claim_id,notes,option_id) VALUES(:claim,:notes,:option_id)');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
        $stmt->bindParam(':option_id', $option_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function getLatestClaimLine($claim_id)
    {
        $stmt = $this->conn->prepare('SELECT date_entered FROM `claim_line` WHERE mca_claim_id=:claim_id ORDER BY id DESC LIMIT 1');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getEmailCredentils()
    {
        $stmt = $this->conn->prepare("SELECT notification_email,notification_password,cc,cc1 FROM email_configs");
        $stmt->execute();
        return $stmt->fetch();
    }
    function getOpenClaims($condition=":username",$val="1")
    {
        try {
            $stmt = $this->conn->prepare('SELECT count(*) FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.Open=1 AND '.$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getLeads($condition,$val)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM lead WHERE status=0 AND $condition");
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getQA($condition,$val1,$rolex)
    {
        if($rolex=="cs")
        {
            $spxy1 = $this->conn->prepare("SELECT DISTINCT a.claim_id FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id WHERE qa_signed=1 AND cs_signed=0 AND quality=2 AND qa_signed=1 AND cs_signed=0 AND quality=2 AND $condition");
            $spxy1->bindParam(':username', $val1, PDO::PARAM_STR);
            $spxy1->execute();
            return $spxy1->rowCount();
        }
        else{
            $spxy1 = $this->conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE quality=1 AND $condition");
            $spxy1->bindParam(':username', $val1, PDO::PARAM_STR);
            $spxy1->execute();
            return $spxy1->fetchColumn();
        }

    }
    function getClinicalReview($condition,$val1)
    {
        $note="This claim was sent for clinical review.";
        $spx = $this->conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN intervention as k ON a.claim_id=k.claim_id INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE Open=4 AND k.intervention_desc=:nnot AND $condition");
        $spx->bindParam(':username', $val1, PDO::PARAM_STR);
        $spx->bindParam(':nnot', $note, PDO::PARAM_STR);
        $spx->execute();
        return $spx->fetchColumn();
    }
    function getPreassessment($condition=":username",$val=1)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE Open=5 AND $condition");
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getNewClaims($condition=":username",$val="1")
    {
        try {
            $stmt = $this->conn->prepare("SELECT count(*) FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.Open=1 AND new=0 AND ".$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getReopenedClaimsPerClient($date,$condition,$val)
    {
        try {
            $se="%".$date."%";
            $stmt =$this->conn->prepare("SELECT k.claim_id, MAX(k.id) AS id,a.username,last_scheme_savings,last_discount_savings,a.savings_scheme,a.savings_discount
FROM`reopened_claims` as k INNER JOIN claim as a ON k.claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE k.reopened_date like :dd AND a.Open=0 AND k.date_closed not like :dd AND k.date_closed<:dat AND ".$condition."
GROUP BY k.claim_id");

            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(":dat",$date,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function reopenedCases($date,$condition,$val)
    {
        $arr=$this->getReopenedClaimsPerClient($date,$condition,$val);
        $last_scheme_savings=0.00;
        $last_discount_savings=0.00;
        $savings_scheme=0.00;
        $savings_discount=0.00;
        foreach ($arr as $row)
        {
            $last_scheme_savings+=(double)$row["last_scheme_savings"];
            $last_discount_savings+=(double)$row["last_discount_savings"];

        }

        $data["last_scheme_savings1"]=$last_scheme_savings;
        $data["last_discount_savings1"]=$last_discount_savings;

        return $data;
    }
    function getSavings($date,$condition,$val)
    {
        try {
            $arr=$this->reopenedCases($date,$condition,$val);
            $se="%".$date."%";
            $stmt = $this->conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as sav,a.charged_amnt,a.scheme_paid,SUM(a.savings_scheme) as scheme_savings,SUM(a.savings_discount) as discount_savings FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.Open=0 AND a.date_closed LIKE :dd AND $condition");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            $res= $stmt->fetch();
            $data["scheme_savings"]=(double)$res["scheme_savings"]-(double)$arr["last_scheme_savings1"];
            $data["discount_savings"]=(double)$res["discount_savings"]-(double)$arr["last_discount_savings1"];
            $data["sav"]=$data["scheme_savings"]+$data["discount_savings"];
            $data["charged_amnt"]=$res["scheme_paid"];
            $data["scheme_paid"]=$res["scheme_paid"];
            return $data;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getClosedThisMonth($condition,$val)
    {
        $from = date('Y-m').'%';
        $stmt = $this->conn->prepare('SELECT COUNT(Open) as allOpen FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_closed LIKE :dat AND a.Open=0 AND '.$condition);
        $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getEnteredthisClaims($date,$condition,$val)
    {
        try {
            $se="%".$date."%";
            $stmt = $this->conn->prepare("SELECT count(*) FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_entered like :dd AND a.Open<>2 AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAveragethisClaims($date,$condition,$val)
    {
        try {
            $date="%".$date."%";
            $sql1="SELECT SUM(TIMESTAMPDIFF(SECOND,a.date_entered,a.date_closed))/(COUNT(claim_id) * 86400) as total_time FROM claim as a INNER JOIN member as b 
ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_closed LIKE :dat AND a.Open=0 AND a.Open=0 AND a.claim_id not in (SELECT claim_id FROM reopened_claims) AND $condition";
            $stmt = $this->conn->prepare($sql1);
            $stmt->bindParam(':dat', $date, PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getClinicalNote($claim_id,$intervention_desc)
    {
        global $conn;
        $sql = $conn->prepare('SELECT owner FROM intervention WHERE intervention_desc=:intervention_desc AND claim_id=:claim_id');
        $sql->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $sql->bindParam(':intervention_desc', $intervention_desc, PDO::PARAM_STR);
        $sql->execute();
        return $sql->rowCount();
    }
    function getDisciplinecodes()
    {
        $stmt=$this->conn->prepare('SELECT *FROM disciplinecodes');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getDisciplinecode($displine_id)
    {
        $stmt=$this->conn->prepare('SELECT *FROM disciplinecodes WHERE id=:id');
        $stmt->bindParam(':id',$displine_id,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getOpenNew($condition=":username",$val="Wanda")
    {
        $note="This claim was sent for clinical review.";
        try {
         
            $stmt = $this->conn->prepare('SELECT SUM(CASE WHEN a.Open=1 THEN 1 ELSE 0 END) as open1,SUM(CASE WHEN a.new=0 THEN 1 ELSE 0 END) as new1,SUM(CASE WHEN a.Open=5 THEN 1 ELSE 0 END) as preassess1 FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE (a.Open=1 OR a.Open=5) AND '.$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();    
             
            $data["det"]=$stmt->fetch();
            $stmt = $this->conn->prepare('SELECT COUNT(k.id) as tot FROM `jarvis_files` as k INNER JOIN claim as a ON k.claim_number=a.claim_number INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE k.failed=7 AND date_time>"2022-11-06" AND '.$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            $data["owlserror"]=$stmt->fetchColumn();            
            $spx = $this->conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN intervention as k ON a.claim_id=k.claim_id INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE Open=4 AND k.intervention_desc=:nnot AND $condition");
            $spx->bindParam(':username', $val, PDO::PARAM_STR);
            $spx->bindParam(':nnot', $note, PDO::PARAM_STR);
            $spx->execute();
            $data["clinical"] = $spx->fetchColumn();
            return $data;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getSubscription($email)
    {
        $broker_name="";
        $fbStmt = $this->conn->prepare("SELECT broker_id FROM `web_clients` where email=:email");
        $fbStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $fbStmt->execute();
        if($fbStmt->rowCount()>0)
        {
            $broker_id=(int)$fbStmt->fetchColumn();

            $fbStmt1 = $this->conn->prepare('SELECT CONCAT(name," ",surname) as fullname FROM `web_clients` where client_id=:broker_id');
            $fbStmt1->bindParam(':broker_id', $broker_id, PDO::PARAM_STR);
            $fbStmt1->execute();
            if($fbStmt1->rowCount()>0)
            {
                $broker_name=$fbStmt1->fetchColumn();
            }
            else{
                $broker_name="No Broker";
            }
        }

        return $broker_name;
    }
    public function getSpecific($claim_id,$practice_number)
    {
        $stmt = $this->conn->prepare("SELECT practice_number,savings_scheme,savings_discount,doc_name,doc_charged_amount,doc_scheme_amount,doc_gap,pay_doctor,cpt_code, 
        display,claimedline_id,treatement_date,provider_invoicenumber FROM doctors WHERE practice_number=:practice_number AND claim_id=:claim_id");
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getZeroAmounts($condition=":username",$val="1")
    {
        $stmt = $this->conn->prepare('SELECT DISTINCT claim_number,mca_claim_id as claim_id FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id
where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId is null AND a.date_entered>"2020-05-28" AND '.$condition);
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function get4DaysMembers($condition=":username",$val="1")
    {
        $stmt = $this->conn->prepare('SELECT a.claim_number,a.claim_id,a.member_contacted,TIMESTAMPDIFF(day,a.date_entered,NOW()) as period FROM claim as a INNER JOIN  member as b ON a.member_id=b.member_id WHERE a.Open=1 AND '.$condition.' GROUP BY a.claim_id having period>=8 AND (a.member_contacted<>1 OR a.member_contacted IS NULL)');
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getUpdatedDocs($condition=":username",$val="1")
    {
        $stmt = $this->conn->prepare('SELECT b.claim_number,a.claim_id FROM `documents` as a inner join claim as b on a.claim_id=b.claim_id where additional_doc=1 AND '.$condition);
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getClaimLines($claim_id,$practice_number)
    {
        $stmt = $this->conn->prepare("SELECT PMBFlag,tariff_code,clmline_scheme_paid_amnt,clmnline_charged_amnt,primaryICDCode,primaryICDDescr,id,benefit_description,
       msg_code,msg_dscr,lng_msg_dscr,clmn_line_pmnt_status,treatmentDate,modifier,reason_code,reason_description,gap_aamount_line,practice_number FROM claim_line WHERE mca_claim_id=:claim_id AND practice_number=:practice_number");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getClaimLine($claim_line_id)
    {
        $stmt = $this->conn->prepare("SELECT PMBFlag,tariff_code,clmline_scheme_paid_amnt,clmnline_charged_amnt,primaryICDCode,primaryICDDescr,id,benefit_description,
       msg_code,msg_dscr,lng_msg_dscr,clmn_line_pmnt_status,treatmentDate,modifier,reason_code,reason_description,gap_aamount_line,service_date_from,cptCode,practice_number FROM claim_line WHERE id=:id");
        $stmt->bindParam(':id', $claim_line_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getNotes($claim_id)
    {
        $stmt = $this->conn->prepare("SELECT intervention_id,intervention_desc,date_entered,owner,practice_number,doc_name, consent_destination FROM intervention WHERE claim_id=:claim_id OR claim_id1=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getConsent($scheme,$owner)
    {
        $stmt = $this->conn->prepare("SELECT doc_name FROM consent WHERE scheme=:scheme AND owner=:owner");
        $stmt->bindParam(':scheme', $scheme, PDO::PARAM_STR);
        $stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getFeedback($claim_id)
    {
        $stmt = $this->conn->prepare("SELECT feedback_id as intervention_id,description as intervention_desc,date_entered,owner FROM feedback WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getClinicalNotes($claim_id)
    {
        $stmt = $this->conn->prepare("SELECT description,date_entered,owner FROM clinical_notes WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getDoctorDetails($practice_number)
    {
        $practice_number="%".$practice_number."%";
        $stmt = $this->conn->prepare("SELECT name_initials,surname,tel1code,telephone,physad1,disciplinecode,doc_id,signed,gives_discount,fixed_discount,discount_effective_date FROM doctor_details WHERE practice_number like :practice_number");
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getDoctorDetailsUsingId($doctor_id)
    {
        $stmt = $this->conn->prepare("SELECT doc_id,name_initials,surname,telephone,admin_name,gives_discount,discipline,practice_number,disciplinecode,sub_disciplinecode,sub_disciplinecode_description,disciplinecode_id,email,dr_value,days_number,signed,date_joined,date_entered,entered_by FROM doctor_details WHERE doc_id=:doc_id LIMIT 1");
        $stmt->bindParam(':doc_id', $doctor_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getOtherDoctors($claim_id,$practice_number)
    {
        $stmt=$this->conn->prepare('SELECT practice_number,claimedline_id,pay_doctor,savings_scheme,savings_discount FROM doctors WHERE claim_id=:id AND practice_number<>:prac');
        $stmt->bindParam(':id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':prac', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();

    }
    public function getDoctorDiscount($practice_number)
    {
        $stmt=$this->conn->prepare('SELECT dr_value,discount_perc,discount_value,days_number,id FROM discount_details WHERE practice_number=:practice_number AND status=1');
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getDoctorNotes($doctor_id)
    {
        $stmt=$this->conn->prepare('SELECT description,date_entered,entered_by FROM doctor_notes WHERE doctor_id=:doctor_id');
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDoctorLogs($practice_number)
    {
        $stmt=$this->conn->prepare('SELECT practice_number,gives_discount,updated_date,changed_by FROM doctor_details_log WHERE practice_number=:practice_number ORDER BY updated_date DESC');
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getTRCP($trcp,$cpt_code)
    {
        $stmt = $this->conn2->prepare('SELECT *FROM ClinicalXref WHERE clinical_code=:xref AND xref_type=:typ');
        $stmt->bindParam(':xref', $cpt_code, PDO::PARAM_STR);
        $stmt->bindParam(':typ', $trcp, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function getClaimDocuments($claim_id)
    {
        $stmt = $this->conn->prepare("SELECT doc_id,randomNum,doc_description,additional_doc,doc_type,doc_size,date FROM documents WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getClaimSpecialist($username)
    {
        $stmt = $this->conn1->prepare("SELECT email,phone,fullName,email_password FROM staff_users WHERE username=:username LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getOtherUsers($name)
    {
        $stmt = $this->conn1->prepare('SELECT email FROM `staff_users` WHERE (fullname=:num OR other_name=:num) AND length(fullname)>1' );
        $stmt->bindParam(':num', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getUserById($id)
    {
        $stmt = $this->conn1->prepare('SELECT session_code FROM staff_users WHERE user_id=:id');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getLatestClaim($entered_by)
    {
        $stmt=$this->conn->prepare('SELECT MAX(claim_id) FROM claim WHERE entered_by=:entered_by');
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getLatestMember($entered_by)
    {
        $stmt=$this->conn->prepare('SELECT MAX(member_id) FROM member WHERE entered_by=:entered_by');
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getValidUsers()
    {
        $stmt=$this->conn1->prepare('SELECT DISTINCT username FROM staff_users where state=1 and (role="claims_specialist" or role="admin" or role="controller")');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getClients()
    {
        $stmt=$this->conn->prepare('select client_name, min(client_id) as client_id from clients group by client_name ORDER BY client_name ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getClaimSchemes()
    {
        $stmt=$this->conn->prepare('SELECT DISTINCT name FROM schemes ORDER BY name ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getTotalClaimLine($claim_id)
    {
        $stmt=$this->conn->prepare("SELECT SUM(clmnline_charged_amnt),SUM(clmline_scheme_paid_amnt),SUM(gap),SUM(gap_aamount_line) FROM claim_line WHERE mca_claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getRejectionCodes($remark_code)
    {
        $stmt = $this->conn->prepare('SELECT *from rejection_codes WHERE remark_code=:remark_code');
        $stmt->bindParam(':remark_code', $remark_code, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getClaimIDfromClaimLine($claimline_id)
    {
        $stmt = $this->conn->prepare('SELECT mca_claim_id FROM claim_line WHERE id=:claimline_id');
        $stmt->bindParam(':claimline_id', $claimline_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getClaimDate($claim_id,$date_reopened,$date_entered)
    {
        $stmts = $this->conn->prepare('SELECT date_entered FROM `claim_line` WHERE mca_claim_id=:claim_id ORDER BY id DESC LIMIT 1');
        $stmts->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmts->execute();
        $ytd = $stmts->rowCount()>0?$stmts->fetchColumn():"";
        $date_reopened=$date_reopened>$ytd?$date_reopened:$ytd;
        $date_entered = strlen($date_reopened)>10?$date_reopened:$date_entered;
        return $date_entered;
    }

    public function getSingleNote($id)
    {
        $stmt = $this->conn->prepare('SELECT a.intervention_id,a.claim_id,a.intervention_desc,a.date_entered,a.owner,b.username FROM intervention as a INNER JOIN claim as b ON a.claim_id=b.claim_id WHERE intervention_id = :num');
        $stmt->bindParam(':num', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function get8days($claim_id)
    {
        $stmt = $this->conn->prepare('SELECT description,date_entered,entered_by FROM `eightdays` WHERE claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function checkClient($client_name)
    {
        $sql = $this->conn->prepare('SELECT reporting_client_id FROM clients WHERE client_name=:client_name LIMIT 1');
        $sql->bindParam(':client_name', $client_name, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch();

    }

    public function getAllICD10()
    {
        $stmt=$this->conn2->prepare('SELECT DISTINCT diag_code FROM Diagnosis');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getSchemeOptions($scheme_name)
    {
        $stmt=$this->conn->prepare('SELECT DISTINCT b.option_name FROM schemes as a inner join scheme_options as b on a.id=b.scheme_id WHERE a.name = :scheme_name');
        $stmt->bindParam(':scheme_name', $scheme_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getConfirmOptions($claim_id)
    {
        $stmt=$this->conn->prepare("SELECT option_id,notes FROM confirm_options WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getLatestNote($owner)
    {
        $stmt = $this->conn->prepare("SELECT MAX(intervention_id) FROM intervention WHERE owner=:owner");
        $stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getICD10Details($icd10)
    {
        $stmt = $this->conn2->prepare('SELECT `diag_code`,`pmb_code`,`shortdesc` FROM `Diagnosis` WHERE diag_code=:icd10 UNION SELECT `ICD10_Code`,`pmb`,`ICD10_3_Code_Desc` FROM `diagonisis1`  WHERE ICD10_Code=:icd10');
        $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getGeneratedClaimNumber($client_id)
    {
        $stmt = $this->conn->prepare('SElECT a.claim_number FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=:client_id ORDER BY claim_number DESC LIMIT 1');
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getClaimNumber($claim_number,$client_id)
    {
        $stmt = $this->conn->prepare('SElECT a.claim_number FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=:client_id AND a.claim_number=:claim_number');
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function getMember($policy_number,$id_number,$client_id)
    {
        $stmt=$this->conn->prepare('SELECT member_id,entered_by FROM member WHERE ((policy_number=:policy_number AND policy_number<>"") OR (id_number=:id_number AND id_number<>"")) AND client_id=:client_id');
        $stmt->bindParam(':policy_number',$policy_number,PDO::PARAM_STR);
        $stmt->bindParam(':id_number',$id_number,PDO::PARAM_STR);
        $stmt->bindParam(':client_id',$client_id,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    //Insert start here
    public function insertMember($client_id,$policy_number,$first_name,$surname,$email,$cell,$telephone,$id_number,$scheme_number,$medical_scheme,$scheme_option,$account_number,$entered_by)
    {
        $stmt=$this->conn->prepare('INSERT INTO `member`(`client_id`, `policy_number`, `first_name`, `surname`, `email`, `cell`, `telephone`, `id_number`, `scheme_number`,
 `medical_scheme`, `scheme_option`, `account_number`,`entered_by`) VALUES (:client_id,:policy_number,:first_name,:surname,:email,:cell,:telephone,:id_number,:scheme_number,:medical_scheme,
 :scheme_option,:account_number,:entered_by)');
        $stmt->bindParam(':client_id',$client_id,PDO::PARAM_INT);
        $stmt->bindParam(':policy_number',$policy_number,PDO::PARAM_STR);
        $stmt->bindParam(':first_name',$first_name,PDO::PARAM_STR);
        $stmt->bindParam(':surname',$surname,PDO::PARAM_STR);
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);
        $stmt->bindParam(':cell',$cell,PDO::PARAM_STR);
        $stmt->bindParam(':telephone',$telephone,PDO::PARAM_STR);
        $stmt->bindParam(':id_number',$id_number,PDO::PARAM_STR);
        $stmt->bindParam(':scheme_number',$scheme_number,PDO::PARAM_STR);
        $stmt->bindParam(':medical_scheme',$medical_scheme,PDO::PARAM_STR);
        $stmt->bindParam(':scheme_option',$scheme_option,PDO::PARAM_STR);
        $stmt->bindParam(':account_number',$account_number,PDO::PARAM_STR);
        $stmt->bindParam(':entered_by',$entered_by,PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function insertAPILog($claim_number,$description,$description_sec,$failed,$status="")
    {

        $stmt=$this->conn->prepare('INSERT INTO `jarvis_files`(`claim_number`, `desciption`,`desciption1`,`failed`,`status`) VALUES (:claim_number,:desciption,:desciption1,:failed,:status)');
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmt->bindParam(':desciption', $description, PDO::PARAM_STR);
        $stmt->bindParam(':desciption1', $description_sec, PDO::PARAM_STR);
        $stmt->bindParam(':failed', $failed, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        return $stmt->execute();

    }
    function insertClinicalNotes($claim_id,$txtclinicalnote,$username,$open)
    {
        $stmt = $this->conn->prepare('INSERT INTO clinical_notes(claim_id,description,owner,open) VALUES(:claim,:desc,:owner,:open)');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':desc', $txtclinicalnote, PDO::PARAM_STR);
        $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
        $stmt->bindParam(':open', $open, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function insertDoctorClaimLogs($claim_id,$practice_number,$type,$charged_amount,$scheme_amount,$gap_amount,$entered_by)
    {
        $stmt = $this->conn->prepare('INSERT INTO `doctor_logs`(`claim_id`,`practice_number`,`type`,`charged_amount`,`scheme_amount`,`gap_amount`,`entered_by`) VALUES(:claim_id,:practice_number,:type,:charged_amount,:scheme_amount,:gap_amount,:entered_by)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':charged_amount', $charged_amount, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_amount', $scheme_amount, PDO::PARAM_STR);
        $stmt->bindParam(':gap_amount', $gap_amount, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        return $stmt->execute();
    }
    //INSERT INTO `doctor_logs`(`id`, `claim_id`, `practice_number`, `type`, `charged_amount`, `scheme_amount`, `gap_amount`, `entered_by`, `date_entered`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]')
    public function insertClaim($member_id,$claim_number,$Service_Date,$icd10,$pmb,$charged_amnt,$scheme_paid,$gap,$username,$emergency,$entered_by,$end_date,$client_gap,$category_type,$medication_value,$patient_dob,$fusion_done,$code_description,$modifier,$reason_code,$person_email,$patient_gender,$sub_category)
    {
        $stmt = $this->conn->prepare('INSERT INTO `claim`(`member_id`,`claim_number`, `Service_Date`, `icd10`, `pmb`, `charged_amnt`,`scheme_paid`, `gap`,`username` ,`emergency`, `entered_by`,`end_date`,`client_gap`,`category_type`,medication_value,patient_dob,fusion_done,code_description,modifier,reason_code,contact_person_email,patient_gender) 
VALUES (:member_id,:claim_number, :Service_Date,:icd10,:pmb, :charged_amnt, :scheme_paid, :gap,:username, :emergency, :entered_by,:end_date,:client_gap,:category_type,:medication_value,:patient_dob,:fusion_done,:code_description,:modifier,:reason_code,:contact_person_email,:patient_gender)');
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmt->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
        $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $stmt->bindParam(':pmb', $pmb, PDO::PARAM_STR);
        $stmt->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
        $stmt->bindParam(':gap', $gap, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        $stmt->bindParam(':category_type', $category_type, PDO::PARAM_STR);
        $stmt->bindParam(':medication_value', $medication_value, PDO::PARAM_STR);
        $stmt->bindParam(':patient_dob', $patient_dob, PDO::PARAM_STR);
        $stmt->bindParam(':fusion_done', $fusion_done, PDO::PARAM_STR);
        $stmt->bindParam(':code_description', $code_description, PDO::PARAM_STR);
        $stmt->bindParam(':modifier', $modifier, PDO::PARAM_STR);
        $stmt->bindParam(':reason_code', $reason_code, PDO::PARAM_STR);
        $stmt->bindParam(':contact_person_email', $person_email, PDO::PARAM_STR);
        $stmt->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
        //$stmt->bindParam(':sub_category_id', $sub_category, PDO::PARAM_STR);
        return (int)$stmt->execute();

    }
    function insertDoctorDiscount($dr_value,$discount_perc,$discount_value,$days_number,$practice_number,$username)
    {
        $stmt=$this->conn->prepare('INSERT INTO discount_details(dr_value,discount_perc,discount_value,days_number,practice_number,entered_by) VALUES (:dr_value,:discount_perc,:discount_value,:days_number,:practice_number,:entered_by)');
        $stmt->bindParam(':dr_value',$dr_value,PDO::PARAM_STR);
        $stmt->bindParam(':discount_perc',$discount_perc,PDO::PARAM_STR);
        $stmt->bindParam(':discount_value',$discount_value,PDO::PARAM_STR);
        $stmt->bindParam(':days_number',$days_number,PDO::PARAM_STR);
        $stmt->bindParam(':practice_number',$practice_number,PDO::PARAM_STR);
        $stmt->bindParam(':entered_by',$username,PDO::PARAM_STR);
        return $stmt->execute();

    }
    public function insertClaimDoctor($practice_number,$claim_id,$entered_by)
    {
        $practice_number=trim($practice_number,' ');
        $practice_number =str_pad( $practice_number, 7, '0', STR_PAD_LEFT);
        $stmt = $this->conn->prepare('  INSERT INTO `doctors`(`claim_id`, `practice_number`,`entered_by`) VALUES (:claim_id,:practice_number,:entered_by)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function insertClosedLogs($claim_id,$scheme,$discount,$username)
    {
        $logClaim = $this->conn->prepare('INSERT INTO `closed_cases_logs`(claim_id,savings_scheme,savings_discount,closed_by) VALUES(:claim_id,:savings_scheme,:savings_discount,:closed_by)');
        $logClaim->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $logClaim->bindParam(':savings_scheme', $scheme, PDO::PARAM_STR);
        $logClaim->bindParam(':savings_discount', $discount, PDO::PARAM_STR);
        $logClaim->bindParam(':closed_by', $username, PDO::PARAM_STR);
        return (int)$logClaim->execute();
    }
    function insertReOpenedCases($claim_id,$reason,$entered_by,$date_closed,$last_scheme_savings,$last_discount_savings)
    {
        $logClaim = $this->conn->prepare('INSERT INTO `reopened_claims`(claim_id,reason,entered_by,date_closed,last_scheme_savings,last_discount_savings) VALUES(:claim_id,:reason,:entered_by,:date_closed,:last_scheme_savings,:last_discount_savings)');
        $logClaim->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $logClaim->bindParam(':reason', $reason, PDO::PARAM_STR);
        $logClaim->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $logClaim->bindParam(':date_closed', $date_closed, PDO::PARAM_STR);
        $logClaim->bindParam(':last_scheme_savings', $last_scheme_savings, PDO::PARAM_STR);
        $logClaim->bindParam(':last_discount_savings', $last_discount_savings, PDO::PARAM_STR);
        return (int)$logClaim->execute();
    }
    public function insert8days($note,$claim_id,$entered_by)
    {
        $stmt = $this->conn->prepare('INSERT INTO `eightdays`(`description`,`claim_id`,`entered_by`) VALUES(:description,:claim_id,:entered_by)');
        $stmt->bindParam(':description', $note, PDO::PARAM_STR);
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function insertDoctorDetails($name_initials,$surname,$telephone,$admin_name,$practice_number,$gives_discount,$discipline,$displine_type,$subcode,$subdesr,$displine_id,$email,$dr_value,$days_number,$signed,$date_joined,$discount_v,$discount_perc,$discount_value,$username)
    {
        $stmt = $this->conn->prepare('Insert INTO doctor_details(name_initials,surname,telephone,admin_name,practice_number,gives_discount,discipline,disciplinecode,sub_disciplinecode,
sub_disciplinecode_description,disciplinecode_id,email,dr_value,days_number,signed,date_joined,discount_perc,discount_v,rand_perc,entered_by) VALUES (:name1,:surname,:telephone,
:admin,:practice_number,:discount,:disc,:disciplinecode,:sub_disciplinecode,:sub_disciplinecode_description,:disciplinecode_id,:email,:dr_value,:days_number,:signed,:date_joined,:discount_perc,:discount_v,:rand_perc,:entered_by)');
        //SELECT doc_id,name_initials,surname,telephone,admin_name,gives_discount,discipline,practice_number
        $stmt->bindParam(':name1', $name_initials, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $stmt->bindParam(':admin', $admin_name, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':discount', $gives_discount, PDO::PARAM_STR);
        $stmt->bindParam(':disc', $discipline, PDO::PARAM_STR);
        $stmt->bindParam(':disciplinecode', $displine_type, PDO::PARAM_STR);
        $stmt->bindParam(':sub_disciplinecode', $subcode, PDO::PARAM_STR);
        $stmt->bindParam(':sub_disciplinecode_description', $subdesr, PDO::PARAM_STR);
        $stmt->bindParam(':disciplinecode_id', $displine_id, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':dr_value', $dr_value, PDO::PARAM_STR);
        $stmt->bindParam(':days_number', $days_number, PDO::PARAM_STR);
        $stmt->bindParam(':signed', $signed, PDO::PARAM_STR);
        $stmt->bindParam(':date_joined', $date_joined, PDO::PARAM_STR);
        $stmt->bindParam(':discount_v', $discount_v, PDO::PARAM_STR);
        $stmt->bindParam(':discount_perc', $discount_perc, PDO::PARAM_STR);
        $stmt->bindParam(':rand_perc', $discount_value, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $username, PDO::PARAM_STR);
        return $stmt->Execute();
    }
    public function insertNoteLog($id,$claim_id,$desc,$date_entered,$owner)
    {
        $stmtI = $this->conn->prepare('INSERT INTO delete_logs(intervention_id, claim_id, description,intervention_date,owner) VALUES(:id,:claim_id,:note,:date_entered,:owner)');
        $stmtI->bindParam(':id', $id, PDO::PARAM_STR);
        $stmtI->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmtI->bindParam(':note', $desc, PDO::PARAM_STR);
        $stmtI->bindParam(':date_entered', $date_entered, PDO::PARAM_STR);
        $stmtI->bindParam(':owner', $owner, PDO::PARAM_STR);
        return $stmtI->execute();
    }
    public function insertClaimLine($claim_id,$practice_number,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$gap,$tariff_code,$service_date_from,$primaryICDCode,$pmb,$benefit_description,$gap_aamount_line,$msg_code,$treatmentDate,$createdBy)
    {
        $stmt = $this->conn->prepare('INSERT INTO `claim_line`(`service_date_from`,`mca_claim_id`, `practice_number`, `clmnline_charged_amnt`, `clmline_scheme_paid_amnt`, `gap`, `tariff_code`, `primaryICDCode`,`PMBFlag`,`benefit_description`,`gap_aamount_line`,`msg_code`,`treatmentDate`,`createdBy`) VALUES (:service_date_from,:mca_claim_id,:practice_number,:clmnline_charged_amnt,:clmline_scheme_paid_amnt,:gap,:tariff_code,:primaryICDCode,:PMBFlag,:benefit_description,:gap_aamount_line,:msg_code,:treatmentDate,:createdBy)');
        $stmt->bindParam(':mca_claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':clmnline_charged_amnt', $clmnline_charged_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':clmline_scheme_paid_amnt', $clmline_scheme_paid_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':gap', $gap, PDO::PARAM_STR);
        $stmt->bindParam(':tariff_code', $tariff_code, PDO::PARAM_STR);
        $stmt->bindParam(':service_date_from', $service_date_from, PDO::PARAM_STR);
        $stmt->bindParam(':primaryICDCode', $primaryICDCode, PDO::PARAM_STR);
        $stmt->bindParam(':PMBFlag', $pmb, PDO::PARAM_STR);
        $stmt->bindParam(':benefit_description', $benefit_description, PDO::PARAM_STR);
        $stmt->bindParam(':gap_aamount_line', $gap_aamount_line, PDO::PARAM_STR);
        $stmt->bindParam(':msg_code', $msg_code, PDO::PARAM_STR);
        $stmt->bindParam(':treatmentDate', $treatmentDate, PDO::PARAM_STR);
        $stmt->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
 public function getTemplate($template_name)
    {
        $stmt = $this->conn->prepare("SELECT description FROM basic_templates WHERE template_name=:template_name");
        $stmt->bindParam(':template_name', $template_name, PDO::PARAM_STR);        
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function insertNotes($claim_id,$intervention_desc,$username,$reminder_time,$reminder_status,$claim_id1,$current_practice_number,$doc_name,$consent_dest,$status=1)
    {
        $clamm="--";
        $stmt = $this->conn->prepare('INSERT INTO intervention(claim_id,intervention_desc,owner,reminder_time,reminder_status,claim_id1,practice_number,doc_name,consent_destination,claim_number,status) VALUES(:claim,:notes,:owner,:reminder_time,:reminder_status,:claim_id1,:practice_number,:doc_name,:consent_destination,:claim_number,:status)');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':notes', $intervention_desc, PDO::PARAM_STR);
        $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
        $stmt->bindParam(':reminder_time', $reminder_time, PDO::PARAM_STR);
        $stmt->bindParam(':reminder_status', $reminder_status, PDO::PARAM_STR);
        $stmt->bindParam(':claim_id1', $claim_id1, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $current_practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':doc_name', $doc_name, PDO::PARAM_STR);
        $stmt->bindParam(':consent_destination', $consent_dest, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $clamm, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        return (int)$stmt->execute();
    }
    public function insertFeedback($claim_id,$feedback,$username,$open)
    {
        $stmt = $this->conn->prepare('INSERT INTO feedback(claim_id,description,owner,open) VALUES(:claim,:feedback,:owner,:open)');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);
        $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
        $stmt->bindParam(':open', $open, PDO::PARAM_STR);
        return $stmt->execute();
    }
function getAPIURL($sender_id)
    {
        $stmt =$this->conn->prepare("SELECT api_url FROM external_apis WHERE sender_id=:sender_id");
        $stmt->bindParam(':sender_id', $sender_id, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function insertDocuments($claim_id,$description,$size,$type,$random_number,$uploaded_by)
    {
        $stmt = $this->conn->prepare('INSERT INTO documents(claim_id,doc_description,doc_size,doc_type,randomNum,uploaded_by) VALUES(:claim,:description,:size,:type,:rand,:uploaded_by)');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':size', $size, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':rand', $random_number, PDO::PARAM_STR);
        $stmt->bindParam(':uploaded_by', $uploaded_by, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    public function insertPatient($claim_id,$patient_name,$username)
    {
        $stmt = $this->conn->prepare('  INSERT INTO `patient`(`claim_id`, `patient_name`,`entered_by`) VALUES (:claim_id,:patient_name,:entered_by)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $username, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    public function insertVisitLogs($user,$url)
    {
        $stmt = $this->conn1->prepare('INSERT INTO user_visit_logs(username, url) VALUES (:username,:url)');
        $stmt->bindParam(':username', $user, PDO::PARAM_STR);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function insertAspenClaim($claim_id)
    {
        $stmt = $this->conn->prepare('  INSERT INTO `aspen_checklist`(`claim_id`) VALUES (:claim_id)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function getClaimValidations($claim_id,$str)
    {
        $stmt=$this->conn->prepare('SELECT '.$str.' FROM claim where claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getTarrifDesc($tariff)
    {
        $fbStmt = $this->conn2->prepare("SELECT Description FROM `TariffMaster` WHERE `Tariff_Code`=:tarrif");
        $fbStmt->bindParam(':tarrif', $tariff, PDO::PARAM_STR);
        $fbStmt->execute();
        if($fbStmt->rowCount()>0)
        {
            return $fbStmt->fetchColumn();
        }
        else{
            return "";
        }
    }
    function getIcd10Desc($icd10)
    {
        $stmt = $this->conn->prepare("SELECT shortdesc FROM `diagnosis` WHERE `diag_code`=:icd");
        $stmt->bindParam(':icd', $icd10, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            return $stmt->fetchColumn();
        }
        else{
            return "";
        }
    }
    //INSERT INTO `escalations_logs`(`id`, `claim_id`, `escalation`, `date_entered`, `entered_by`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')
    function insertEscalationsLogs($claim_id,$escalation,$entered_by)
    {
        $stmt = $this->conn->prepare('INSERT INTO `escalations_logs`(`claim_id`, `escalation`, `entered_by`) VALUES (:claim_id,:escalation,:entered_by)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':escalation', $escalation, PDO::PARAM_STR);
        $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function insertAspen($claim_id)
    {
        $stmt = $this->conn->prepare('  INSERT INTO `aspen_checklist`(`claim_id`) VALUES (:claim_id)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function insertDoctorLogs($doctor_id,$username)
    {
        $stmt = $this->conn->prepare('INSERT INTO doctor_details_log(doc_id, name_initials, surname, telephone, tel1code, tel2, tel2code, 
admin_name, gives_discount, discipline, practice_number, gender, lat, lon, physad1, physsuburb, town, date_entered, disciplinecode, sub_disciplinecode, 
group_disciplinecode, sub_disciplinecode_description, disciplinecode_id, email, days_number, dr_value, signed, date_joined,entered_by,changed_by) SELECT doc_id, name_initials,
 surname, telephone, tel1code, tel2, tel2code, admin_name, gives_discount, discipline, practice_number, gender, lat, lon, physad1, physsuburb, town, 
 date_entered, disciplinecode, sub_disciplinecode, group_disciplinecode, sub_disciplinecode_description, disciplinecode_id, email, days_number, dr_value,
  signed, date_joined,entered_by,"'.$username.'" FROM doctor_details WHERE doc_id=:doctor_id');
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function insertClaimLogs($claim_id,$username,$client_id,$policy_number,$claim_number,$medical_scheme,$scheme_number,$id_number,$Service_Date,$icd10_desc,$charged_amnt,$scheme_paid,$gap,$member_name,$member_surname,$savings_scheme,$savings_discount,$memb_telephone,$memb_cell,$memb_email,$scheme_option,$emergency,$previous_owner)
    {
        $stmtx = $this->conn->prepare('INSERT INTO logs(claim_id,owner, client_id, policy_number, claim_number, medical_scheme, scheme_number, id_number, Service_Date,icd10_desc, charged_amnt, scheme_paid, gap, member_name, member_surname, savings_scheme, savings_discount, memb_telephone, memb_cell, 
         memb_email, scheme_option, emergency, previous_owner)
         VALUES (:claim_id,:username,:client_id,:policy_number,:claim_number,:medical_scheme,:scheme_number,:id_number,:Service_Date,:icd10_desc,:charged_amnt,:scheme_paid,:gap,:member_name,:member_surname,:savings_scheme,:savings_discount,:memb_telephone,
         :memb_cell,:memb_email,:scheme_option,:emergency,:previous_owner)');
        $stmtx->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmtx->bindParam(':username', $username, PDO::PARAM_STR);
        $stmtx->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $stmtx->bindParam(':policy_number',$policy_number, PDO::PARAM_STR);
        $stmtx->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmtx->bindParam(':medical_scheme', $medical_scheme, PDO::PARAM_STR);
        $stmtx->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $stmtx->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $stmtx->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
        $stmtx->bindParam(':icd10_desc', $icd10_desc, PDO::PARAM_STR);
        $stmtx->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
        $stmtx->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
        $stmtx->bindParam(':gap', $gap, PDO::PARAM_STR);
        $stmtx->bindParam(':member_name', $member_name, PDO::PARAM_STR);
        $stmtx->bindParam(':member_surname', $member_surname, PDO::PARAM_STR);
        $stmtx->bindParam(':savings_scheme', $savings_scheme, PDO::PARAM_STR);
        $stmtx->bindParam(':savings_discount', $savings_discount, PDO::PARAM_STR);
        $stmtx->bindParam(':memb_telephone', $memb_telephone, PDO::PARAM_STR);
        $stmtx->bindParam(':memb_cell', $memb_cell, PDO::PARAM_STR);
        $stmtx->bindParam(':memb_email', $memb_email, PDO::PARAM_STR);
        $stmtx->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
        $stmtx->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $stmtx->bindParam(':previous_owner', $previous_owner, PDO::PARAM_STR);
        return $stmtx->execute();
    }

    //Edit Functions

    function editMember($member_id,$client_id,$policy_number,$medical_scheme,$scheme_number,$id_number,$member_name,$member_surname,$memb_telephone,$memb_cell,$memb_email,$scheme_option)
    {
        $stmt = $this->conn->prepare('Update member SET client_id=:client_id,policy_number=:policy_number,medical_scheme=:medical_scheme,scheme_number=:scheme_number,id_number=:id_number,
    first_name=:member_name,surname=:member_surname,telephone=:memb_telephone,cell=:memb_cell,email=:memb_email,scheme_option=:scheme_option WHERE member_id=:member_id');
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $stmt->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $stmt->bindParam(':medical_scheme', $medical_scheme, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $stmt->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $stmt->bindParam(':member_name', $member_name, PDO::PARAM_STR);
        $stmt->bindParam(':member_surname', $member_surname, PDO::PARAM_STR);
        $stmt->bindParam(':memb_telephone', $memb_telephone, PDO::PARAM_STR);
        $stmt->bindParam(':memb_cell', $memb_cell, PDO::PARAM_STR);
        $stmt->bindParam(':memb_email', $memb_email, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function editClaim($claim_id,$claim_number,$Service_Date,$end_date,$icd10,$pmb,$charged_amnt,$scheme_paid,$memberportion,$owner,$open,$emergency,$savings_discount,$savings_scheme,$client_gap,$medication_value,$d_o_b,$fusion_done,$dosage,$codes,$nappi,$person_email,$reopened_date,$patient_gender,$open_reason)
    {
        $stmt = $this->conn->prepare('Update claim SET claim_number=:claim_number,Service_Date=:Service_Date,icd10=:icd10,pmb=:pmb,charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap,    username=:username,Open=:open,emergency=:emergency,savings_scheme=:savings_scheme,savings_discount=:savings_discount,end_date=:end_date,client_gap=:client_gap,date_reopened=:date_reopened,medication_value=:medication_value,patient_dob=:patient_dob,fusion_done=:fusion_done,code_description=:code_description,modifier=:modifier,reason_code=:reason_code,contact_person_email=:contact_person_email,patient_gender=:patient_gender,open_reason=:open_reason WHERE claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmt->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $stmt->bindParam(':pmb', $pmb, PDO::PARAM_STR);
        $stmt->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
        $stmt->bindParam(':gap', $memberportion, PDO::PARAM_STR);
        $stmt->bindParam(':username', $owner, PDO::PARAM_STR);
        $stmt->bindParam(':open', $open, PDO::PARAM_STR);
        $stmt->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $stmt->bindParam(':savings_discount', $savings_discount, PDO::PARAM_STR);
        $stmt->bindParam(':savings_scheme', $savings_scheme, PDO::PARAM_STR);
        $stmt->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        $stmt->bindParam(':medication_value', $medication_value, PDO::PARAM_STR);
        $stmt->bindParam(':patient_dob', $d_o_b, PDO::PARAM_STR);
        $stmt->bindParam(':fusion_done', $fusion_done, PDO::PARAM_STR);
        $stmt->bindParam(':code_description', $dosage, PDO::PARAM_STR);
        $stmt->bindParam(':modifier', $codes, PDO::PARAM_STR);
        $stmt->bindParam(':reason_code', $nappi, PDO::PARAM_STR);
        $stmt->bindParam(':contact_person_email', $person_email, PDO::PARAM_STR);
        $stmt->bindParam(':date_reopened', $reopened_date, PDO::PARAM_STR);
        $stmt->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
        $stmt->bindParam(':open_reason', $open_reason, PDO::PARAM_STR);
        return (int) $stmt->execute();
    }
    function editClaimLine($id,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$claimline_memberportion,$tariff_code,$primaryICDCode,$pmb,$benefit_description,$msg_code,$clmn_line_pmnt_status,$treatmentDate,$gap_aamount_line,$lng_msg_dscr,$msg_dscr)
    {
        $stmt = $this->conn->prepare("UPDATE claim_line SET clmnline_charged_amnt=:clmnline_charged_amnt,clmline_scheme_paid_amnt=:clmline_scheme_paid_amnt,gap=:gap,tariff_code=:tariff_code,primaryICDCode=:primaryICDCode,PMBFlag=:PMBFlag,benefit_description=:benefit_description,clmn_line_pmnt_status=:clmn_line_pmnt_status,treatmentDate=:treatmentDate,msg_code=:msg_code,lng_msg_dscr=:lng_msg_dscr,msg_dscr=:msg_dscr,gap_aamount_line=:gap_aamount_line WHERE id=:id");
        $stmt->bindParam(':id',$id , PDO::PARAM_STR);
        $stmt->bindParam(':clmnline_charged_amnt', $clmnline_charged_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':clmline_scheme_paid_amnt', $clmline_scheme_paid_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':gap', $claimline_memberportion, PDO::PARAM_STR);
        $stmt->bindParam(':tariff_code', $tariff_code, PDO::PARAM_STR);
        $stmt->bindParam(':primaryICDCode', $primaryICDCode, PDO::PARAM_STR);
        $stmt->bindParam(':PMBFlag', $pmb, PDO::PARAM_STR);
        $stmt->bindParam(':benefit_description', $benefit_description, PDO::PARAM_STR);
        $stmt->bindParam(':msg_code', $msg_code, PDO::PARAM_STR);
        $stmt->bindParam(':clmn_line_pmnt_status', $clmn_line_pmnt_status, PDO::PARAM_STR);
        $stmt->bindParam(':treatmentDate', $treatmentDate, PDO::PARAM_STR);
        $stmt->bindParam(':gap_aamount_line', $gap_aamount_line, PDO::PARAM_STR);
        $stmt->bindParam(':lng_msg_dscr', $lng_msg_dscr, PDO::PARAM_STR);
        $stmt->bindParam(':msg_dscr', $msg_dscr, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
	 function getAvailableQAFeedback($dat,$claim_id)
    {
        $stmt =$this->conn->prepare("SELECT claim_id FROM qa_feedback WHERE month_entered like :dat AND claim_id=:claim_id");
        $stmt->bindParam(':dat', $dat, \PDO::PARAM_STR);
        $stmt->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function updateAmounts($claim_id,$charged_amnt,$scheme_paid,$gap,$client_gap)
    {
        $stmt=$this->conn->prepare("UPDATE claim SET charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap,client_gap=:client_gap WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
        $stmt->bindParam(':gap', $gap, PDO::PARAM_STR);
        $stmt->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        return (int)$stmt->execute();

    }
    function updateNote($note_id,$desc)
    {
        $stmt1 = $this->conn->prepare('UPDATE intervention SET intervention_desc=:note WHERE intervention_id=:id');
        $stmt1->bindParam(':note', $desc, PDO::PARAM_STR);
        $stmt1->bindParam(':id', $note_id, PDO::PARAM_STR);
        return $stmt1->execute();
    }
    function getNoNotesClaims($condition,$val)
    {
        $stmt = $this->conn->prepare('SELECT a.date_entered,a.claim_id,"No_Notes" as status_type,a.username,c.client_name,a.date_closed,a.date_reopened,"" as descr,b.medical_scheme FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id where Open=1 AND a.claim_id not in (SELECT claim_id FROM intervention) AND '.$condition);
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();

    }
    function getNotesClaims($condition,$val)
    {
        $sql='SELECT x.date_entered,x.claim_id,"With_Notes" as status_type,y.username,y.client_name,y.date_closed,y.date_reopened,x.intervention_desc as descr,y.medical_scheme FROM intervention as x INNER JOIN (SELECT MAX(a.intervention_id) AS most_recent_claim,b.username,c.client_name,b.date_closed,b.date_reopened,j.medical_scheme FROM intervention as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as j ON b.member_id=j.member_id INNER JOIN clients as c ON j.client_id=c.client_id WHERE b.Open=1 AND '.$condition.' GROUP BY a.claim_id) y ON y.most_recent_claim = x.intervention_id';

        //echo $sql;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    ///Delete
    function deleteClaimLine($claimline)
    {
        $stmt = $this->conn->prepare('DELETE FROM claim_line WHERE id=:claimline');
        $stmt->bindParam(':claimline', $claimline, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function deleteClaim($claim_id)
    {
        $stmt = $this->conn->prepare('Delete FROM claim WHERE claim_id = :claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function deleteNote($note_id)
    {
        $stmt = $this->conn->prepare('Delete FROM intervention WHERE intervention_id = :note_id');
        $stmt->bindParam(':note_id', $note_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function deletePatient($claim_id)
    {
        $stmt = $this->conn->prepare('Delete FROM patient WHERE claim_id = :claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function updateDoctor($claim_id,$practice_number,$key,$value)
    {
        $stmt=$this->conn->prepare('UPDATE doctors SET '.$key.'=:val WHERE claim_id=:claim_id AND practice_number=:practice_number');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function getLatestUser()
    {
        $stmt = $this->conn->prepare('SELECT username,email FROM users_information WHERE status=1 ORDER BY datetime ASC LIMIT 1');
        $stmt->execute();
        return $stmt->fetch();
    }
    function getActiveUsers()
    {
        $stmt = $this->conn->prepare('SELECT username,email FROM users_information WHERE status=1 OR username="Shirley" ORDER BY username ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getUserInformation($username)
    {
        $stmt = $this->conn->prepare('SELECT username,email FROM users_information WHERE username=:username LIMIT 1');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getClaimValue($date,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(charged_amnt-scheme_paid) FROM `claim` WHERE date_entered like :dd AND Open<>2 AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function getMonthlySavings($date,$condition=":username",$val="1")
    {
        try {

            $arr=$this->reopenedCases($date,$condition,$val);
            $se="%".$date."%";
            $stmt = $this->conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as sav,a.charged_amnt,a.scheme_paid,SUM(a.savings_scheme) as savings_scheme,SUM(a.savings_discount) as savings_discount FROM `claim` as a  WHERE Open=0 AND a.date_closed LIKE :dd AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            $res= $stmt->fetch();
            $scheme_savings=(double)$res["savings_scheme"]-(double)$arr["last_scheme_savings1"];
            $discount_savings=(double)$res["savings_discount"]-(double)$arr["last_discount_savings1"];
            return $scheme_savings+$discount_savings;
        }
        catch (Exception $e)
        {
            //return $e->getMessage();
            echo $e->getMessage();
        }
    }
    function updateClaimKey($claim_id,$key,$value,$condition="")
    {
        //echo "UPDATE claim SET $key=:val WHERE claim_id=:claim_id.$condition";
        $stmt=$this->conn->prepare('UPDATE claim SET '.$key.'=:val WHERE claim_id=:claim_id'.$condition);
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, PDO::PARAM_STR);
        return (int)$stmt->execute();

    }

    function updateDocumentsKey($doc_id,$key,$value)
    {
        $stmt=$this->conn->prepare('UPDATE documents SET '.$key.'=:val WHERE doc_id=:doc_id');
        $stmt->bindParam(':doc_id', $doc_id, PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function updateDoctorDetailsKey($doc_id,$key,$value)
    {
        $stmt=$this->conn->prepare('UPDATE doctor_details SET '.$key.'=:val WHERE doc_id=:doc_id');
        $stmt->bindParam(':doc_id', $doc_id, PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function updateMemberKey($member_id,$key,$value)
    {
        $stmt=$this->conn->prepare('UPDATE member SET '.$key.'=:val WHERE member_id=:member_id');
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, PDO::PARAM_STR);
        return (int)$stmt->execute();
    }
    function updateSla($claim_id,$note_id)
    {
        $stmt=$this->conn->prepare("UPDATE claim SET sla=1,sla_note=:note WHERE sla<>1 AND claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':note', $note_id, PDO::PARAM_STR);
        return $stmt->execute();

    }
    function updateUser($username)
    {
        $date=date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare('UPDATE users_information SET datetime=:dat WHERE username=:user1');
        $stmt->bindParam(':user1', $username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $date, PDO::PARAM_STR);
        return $stmt->execute();

    }
    function getCoding($tarrif,$icd10,$cpt4="")
    {
        $arrg=[];
        if(strlen($tarrif)>0 && strlen($icd10)>0) {
            $n="N";
            $stmtx = $this->conn2->prepare('SELECT Tariff_Code FROM TariffMaster WHERE Procedure_Group = :n AND Tariff_Code=:t');
            $stmtx->bindParam(':n', $n, PDO::PARAM_STR);
            $stmtx->bindParam(':t', $tarrif, PDO::PARAM_STR);
            $stmtx->execute();
            $ccx = (int)$stmtx->rowCount();
            if($ccx<1) {
                $confirm = "0";
                $xref = "TRCP";
                $xref1 = "CPDI";


                try {
                    if(strlen($cpt4)>0)
                    {
                        $stmt = $this->conn2->prepare('SELECT * FROM `ClinicalXref` WHERE `xref_type` = :cdpi AND `clinical_code` = :cpt4 AND clinical_xref=:icd10');
                        $stmt->bindParam(':cdpi', $xref1, PDO::PARAM_STR);
                        $stmt->bindParam(':cpt4', $cpt4, PDO::PARAM_STR);
                        $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
                        $stmt->execute();
                        $cc1 = (int)$stmt->rowCount();
                        if ($cc1 < 1) {
                            $confirm = "77";
                            $mess="Mismatch on CPT4 code";
                            array_push($arrg,$mess);

                        }
                    }

                    $stmt = $this->conn2->prepare('SELECT *FROM(SELECT * FROM `ClinicalXref` WHERE `clinical_code` = :tarrif) as a where clinical_xref IN (SELECT clinical_code FROM `ClinicalXref` WHERE `clinical_xref` = :icd10)');
                    $stmt->bindParam(':tarrif', $tarrif, PDO::PARAM_STR);
                    $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
                    $stmt->execute();
                    $cc = (int)$stmt->rowCount();
                    if ($cc < 1) {
                        $confirm = "77";
                        $mess="Mismatch on Tariff and ICD10 codes";
                        array_push($arrg,$mess);
                    }

                } catch (Exception $e) {
                    $arrg=[];
                }
            }
            return $arrg;
        }
        else{
            $arrg=["Please complete tarrif code or ICD10 code"];
            return $arrg;
        }

    }
    function updateMm($claim_id,$descr)
    {

        $st=$this->conn->prepare("select member_id from claim where claim_id=:cn");
        $st->bindParam(':cn', $claim_id, PDO::PARAM_STR);
        $st->execute();
        $member_id=$st->fetchColumn();

        $sqlx = $this->conn->prepare("UPDATE member SET consent_descr=:descr WHERE member_id=:policy");
        $sqlx->bindParam(':policy', $member_id, PDO::PARAM_STR);
        $sqlx->bindParam(':descr', $descr, PDO::PARAM_STR);
        $sqlx->execute();
    }
    public function getICD10five($keyword)
    {
        $keyword="%".$keyword."%";
        $stmt = $this->conn->prepare('SELECT DISTINCT diag_code,shortdesc FROM diagnosis WHERE diag_code like :keyword OR shortdesc like :keyword ORDER BY diag_code LIMIT 5');
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getReopenedClaim($claim_id)
    {
        $stmt = $this->conn->prepare('SELECT reopened_date FROM `reopened_claims` WHERE claim_id=:claim_id ORDER BY id DESC LIMIT 1');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
   function getValidations()
    {
        $stmt =$this->conn->prepare("SELECT id,rule_name,description FROM internal_rules ORDER BY id ASC");        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getViewReasons()
    {
        $stmt=$this->conn->prepare('SELECT *FROM view_claim_reasons');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getErrorOwls($condition,$val1)
    {
        $stmt = $this->conn->prepare('SELECT k.id,a.claim_id,k.claim_number,a.username,a.senderId,a.date_entered,k.date_time,c.client_name,k.desciption,k.desciption1 FROM `jarvis_files` as k INNER JOIN claim as a ON k.claim_number=a.claim_number INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE k.failed=7 AND date_time>"2022-11-06" AND '.$condition);
        $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getOwlsById($id)
    {
        $stmt = $this->conn->prepare('SELECT desciption1 FROM `jarvis_files` WHERE id=:id LIMIT 1');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    function updateErrorOwls($issue_id,$key,$value,$condition="")
    {
        //echo "UPDATE claim SET $key=:val WHERE claim_id=:claim_id.$condition";
        $stmt=$this->conn->prepare('UPDATE jarvis_files SET '.$key.'=:val WHERE id=:issue_id'.$condition);
        $stmt->bindParam(':issue_id', $issue_id, PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, PDO::PARAM_STR);
        return (int)$stmt->execute();

    }
    public function getAllSplitClaimsDoctors($status,$pageLimit, $setLimit, $condition, $search_value, $count)
    {
        $limits = "";
        $fields = "COUNT(*)";
        if ($count == 0) {
            $limits = "ORDER BY a.id DESC LIMIT $pageLimit , $setLimit";
            $fields = " DISTINCT a.id as claim_id,b.id as member_id,loyalty_number,a.file_name,membership_number,beneficiary_name,beneficiary_scheme_join_date,beneficiary_id_number,beneficiary_date_of_birth,co_payment,discharge_date,admission_date,procedure_date,a.claim_number";
        }
        $sql = "SELECT $fields FROM split_claim as a 
    INNER JOIN split_member as b ON a.split_member_id=b.id $condition AND (a.status=:status) $limits";
        //echo $role.$sql;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        if (strlen($search_value) > 0) {
            $stmt->bindParam(':search', $search_value, PDO::PARAM_STR);
        }
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchColumn();
        }
    }

    public function getSplitSingle($claim_id)
    {
        $stmt = $this->conn->prepare('SELECT a.id as claim_id,b.id as member_id,a.date_entered,loyalty_number,membership_number,beneficiary_name,beneficiary_scheme_join_date,beneficiary_id_number,beneficiary_date_of_birth,co_payment,discharge_date,admission_date,procedure_date,a.status,a.date_closed,a.closed_by FROM split_claim as a 
    INNER JOIN split_member as b ON a.split_member_id=b.id WHERE a.id=:claim_id LIMIT 1');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getSplitClaimlinesDoctor($claim_id,$hospital_name)
    {
        $stmt = $this->conn->prepare('SELECT id,servicedate,icdcode,procedurecode,amountcharged,medicalschemerateinput,medicalschemepaidinput,duplicate_claim,copayment,file_name,medicalschemerejectioncode FROM `split_claim_line` WHERE split_claim_id=:split_claim_id AND hospital_name=:hospital_name');
        $stmt->bindParam(':split_claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':hospital_name', $hospital_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
   public function getErrorDetails($error_code)
    {
        $stmt = $this->conn->prepare('SELECT CLIENT_MESSAGE FROM `sanlam_error_codes` WHERE CODE=:CODE');
        $stmt->bindParam(':CODE', $error_code, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getSplitCopayments($claim_id)
    {
        $stmt = $this->conn->prepare('SELECT DISTINCT copayment FROM `split_claim_line` WHERE split_claim_id=:split_claim_id');
        $stmt->bindParam(':split_claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function closeSplit($claim_id,$status,$date_closed,$note,$claim_number,$closed_by)
    {
        $stmt = $this->conn->prepare('UPDATE `split_claim` SET status=:status, date_closed=:date_closed,note=:note,claim_number=:claim_number,closed_by=:closed_by WHERE id=:id');
        $stmt->bindParam(':id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':date_closed', $date_closed, PDO::PARAM_STR);
        $stmt->bindParam(':note', $note, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmt->bindParam(':closed_by', $closed_by, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function getSplitFiles()
    {
        $stmt = $this->conn->prepare('SELECT * FROM `split_files`');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getHospitalNames($split_claim_id)
    {
        $stmt = $this->conn->prepare('SELECT hospital_name,status FROM `split_doctors` WHERE split_claim_id=:split_claim_id');
        $stmt->bindParam(':split_claim_id', $split_claim_id, PDO::PARAM_STR);
        //$stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getSwitchDoctors($claim_id)
    {
        $stmt = $this->conn3->prepare('SELECT k.claim_id,k.practice_number,j.name_initials,j.surname,k.provider_invoicenumber FROM `doctors` as k INNER JOIN doctor_details as j ON k.practice_number=j.practice_number WHERE k.claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        //$stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getSplitTotals($startdate,$enddate,$val)
    {
        $condition="WHERE 1 ";
        if($val!="all")
        {
            $condition="WHERE b.status='$val'";
        }
        if(!empty($startdate))
        {
            $condition.= "AND b.date_entered>=:dat1 AND b.date_entered<=:dat2";
        }

        $sql="SELECT COUNT(DISTINCT a.id) as tot_lines,COUNT(DISTINCT a.split_claim_id) as tot_claims,COUNT(DISTINCT a.hospital_name) as tot_hos FROM split_claim_line as a INNER JOIN split_claim as b ON a.split_claim_id=b.id ".$condition;

        $stmt = $this->conn->prepare($sql);
        if(!empty($startdate))
        {
            $stmt->bindParam(':dat1', $startdate, PDO::PARAM_STR);
            $stmt->bindParam(':dat2', $enddate, PDO::PARAM_STR);
        }
        $stmt->execute();
        return array("totals"=>$stmt->fetchAll(),"graph1"=>$this->getSplitGraph1($startdate,$enddate,$val),"graph2"=>$this->getSplitGraph2($startdate,$enddate,$val));

    }
    public function getSplitGraph1($startdate,$enddate,$val)
    {
        $condition="WHERE 1 ";
        if($val!="all")
        {
            $condition="WHERE status='$val'";
        }
        if(!empty($startdate))
        {
            $condition.= "AND date_entered>=:dat1 AND date_entered<=:dat2";
        }

        $sql="SELECT closed_by,COUNT(*) as tot FROM split_claim ".$condition." GROUP BY closed_by";

        $stmt = $this->conn->prepare($sql);
        if(!empty($startdate))
        {
            $stmt->bindParam(':dat1', $startdate, PDO::PARAM_STR);
            $stmt->bindParam(':dat2', $enddate, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll();

    }
    public function getSplitGraph2($startdate,$enddate,$val)
    {
        $condition="WHERE 1 ";
        if($val!="all")
        {
            $condition="WHERE status='$val'";
        }
        if(!empty($startdate))
        {
            $condition.= "AND date_entered>=:dat1 AND date_entered<=:dat2";
        }
        $arr_user=[];
        foreach ($this->getSplitGraph1($startdate,$enddate,$val) as $row)
        {
            $username=$row["closed_by"];
            if($username==null)
            {
                $sql="SELECT DATE_FORMAT(date_entered, '%Y-%m') as mdat,COUNT(*) as tot,closed_by FROM split_claim ".$condition." AND closed_by is null GROUP BY DATE_FORMAT(date_entered, '%Y-%m')";
                $stmt = $this->conn->prepare($sql);
            }
            else{
                $sql="SELECT DATE_FORMAT(date_entered, '%Y-%m') as mdat,COUNT(*) as tot,closed_by FROM split_claim ".$condition." AND closed_by=:closed_by GROUP BY DATE_FORMAT(date_entered, '%Y-%m')";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':closed_by', $username, PDO::PARAM_STR);
            }

            if(!empty($startdate))
            {
                $stmt->bindParam(':dat1', $startdate, PDO::PARAM_STR);
                $stmt->bindParam(':dat2', $enddate, PDO::PARAM_STR);
            }
            $stmt->execute();
            array_push($arr_user,$stmt->fetchAll());
        }
        return $arr_user;

    }
    function getSplitFileClaims($filename)
    {
        $st=$this->conn->prepare("SELECT a.id as claim_id,b.id as member_id,loyalty_number,a.file_name,membership_number,beneficiary_name,
       beneficiary_scheme_join_date,beneficiary_id_number,beneficiary_date_of_birth,co_payment,discharge_date,admission_date,procedure_date,a.claim_number
 FROM split_claim as a INNER JOIN split_member as b ON a.split_member_id=b.id WHERE a.file_name=:filename");
        $st->bindParam(':filename', $filename, PDO::PARAM_STR);
        $st->execute();
        return $st->fetchAll();
    }

    public function getSeamlessClaims($pageLimit, $setLimit, $condition, $search_value, $count)
    {
        $limits = "";
        $fields = "DISTINCT a.claim_id,SUM(k.clmnline_charged_amnt-k.clmline_scheme_paid_amnt) as mygap";
        if ($count == 0) {
            $limits = "ORDER BY a.claim_id DESC LIMIT $pageLimit , $setLimit";
            $fields = " DISTINCT a.claim_id,a.claim_number,a.Service_Date,b.scheme_number,b.first_name,b.surname,b.policy_number,
            b.id_number,b.medical_scheme,b.scheme_option,a.date_entered,a.end_date,SUM(k.clmnline_charged_amnt-k.clmline_scheme_paid_amnt) as mygap";
        }
        $sql = "SELECT $fields FROM claim_line as k inner JOIN claim as a ON k.mca_claim_id=a.claim_id  
    INNER JOIN member as b ON a.member_id=b.member_id $condition GROUP BY a.claim_id HAVING mygap>0 $limits";
        //echo $role.$sql;
        $stmt = $this->conn3->prepare($sql);
        if (strlen($search_value) > 0) {
            $stmt->bindParam(':search', $search_value, PDO::PARAM_STR);
        }
        $stmt->execute();
        if ($count == 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->rowCount();
        }
    }
    public function getSwitcClaims($claim_id,$practice_number)
    {
        $stmt = $this->conn3->prepare("SELECT *,DATE_FORMAT(treatmentDate,'%Y/%m/%d') as treatmentDate1 FROM claim_line WHERE mca_claim_id=:claim_id AND practice_number=:practice_number");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getSwitchSingle($claim_id)
    {
        $stmt = $this->conn3->prepare('SELECT a.claim_id,b.member_id,a.date_entered,b.policy_number,b.scheme_number,b.first_name,b.surname,b.medical_scheme,b.id_number FROM claim as a 
    INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_id=:claim_id LIMIT 1');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function closeSwitchClaim($claim_id,$createdBy,$date_closed,$claim_number)
    {
        $stmt = $this->conn3->prepare('UPDATE `claim` SET createdBy=:createdBy,Open=0, date_closed=:date_closed,claim_number1=:claim_number WHERE claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
        $stmt->bindParam(':date_closed', $date_closed, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        return $stmt->execute();
    }
 function getPendingQAUsers($dat)
    {

        $sql =$this->conn->prepare("SELECT *FROM `users_information` WHERE username not in (SELECT username from qa_feedback where month_entered like :dat) AND active=1 AND username<>'Shirley'");
        $sql->bindParam(':dat', $dat, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchAll();

    }
    function getAutoFail($claim_id,$fields)
    {
        $sql = $this->conn->prepare('SELECT '.$fields.' FROM `quality_assurance` WHERE claim_id=:claim_id ORDER BY id DESC LIMIT 1');
        $sql->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch();
    }
    function getOtherQA($claim_id,$fields)
    {
        $sql = $this->conn->prepare('SELECT '.$fields.' FROM `quality_assurance` WHERE claim_id=:claim_id ORDER BY id DESC LIMIT 1');
        $sql->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch();
    }
    function getQADescr($qa)
    {
        $stmt =$this->conn->prepare("SELECT description FROM qa_descriptions WHERE qa_value=:qa_value");
        $stmt->bindParam(':qa_value', $qa, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
        //qa_descriptions
    }
    function checkQACSQADescr($dat)
    {
        $stmt =$this->conn->prepare("SELECT *FROM `users_information` WHERE username not in (SELECT username from qa_feedback where month_entered like :dat) AND active=1 AND username<>'Shirley'");
        $stmt->bindParam(':dat', $dat, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
    function getQAClaims($dat,$username)
    {
        $stmt =$this->conn->prepare("SELECT DISTINCT a.claim_number,a.claim_id,k.position FROM `claim` as a INNER JOIN quality_assurance as k ON a.claim_id=k.claim_id WHERE a.date_closed like :dat and Open=0 AND quality=2 AND a.username=:username");
        $stmt->bindParam(':dat', $dat, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function insertQAFeedback($claim_id,$month_entered,$qa_position,$username,$improvement_area)
    {
        $stmt = $this->conn->prepare('INSERT INTO qa_feedback(claim_id,month_entered,qa_position,username,improvement_area) VALUES(:claim_id,:month_entered,:qa_position,:username,:improvement_area)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':month_entered', $month_entered, PDO::PARAM_STR);
        $stmt->bindParam(':qa_position', $qa_position, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':improvement_area', $improvement_area, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function getQAFeedBackUsers($dat,$username,$condition="1",$status="")
    {

        $stmt =$this->conn->prepare("SELECT DISTINCT username FROM qa_feedback WHERE month_entered LIKE :dat AND ".$condition.$status);
        $stmt->bindParam(':dat', $dat, \PDO::PARAM_STR);

        if($condition<>"1")
        {
            $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getFeedbackQA($dat,$username)
    {
        $stmt =$this->conn->prepare("SELECT q.id,a.claim_number,q.claim_id,q.username,q.month_entered,q.qa_position,q.improvement_area,action_plan,comment,controller_action,cs_action,completed FROM qa_feedback as q INNER JOIN claim as a ON q.claim_id=a.claim_id WHERE q.month_entered LIKE :dat AND q.username=:username ORDER BY q.qa_position DESC");
        $stmt->bindParam(':dat', $dat, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function updateFeedbackQA($id,$action_plan,$comment)
    {
        $stmt =$this->conn->prepare("UPDATE qa_feedback SET action_plan=:action_plan,comment=:comment WHERE id=:id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->bindParam(':action_plan', $action_plan, \PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, \PDO::PARAM_STR);
        return $stmt->execute();

    }
    function updateFeedbackQAKey($key,$value,$username,$dat)
    {
        $stmt =$this->conn->prepare("UPDATE qa_feedback SET ".$key."=:value WHERE username=:username AND month_entered=:dat");
        $stmt->bindParam(':value', $value, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->bindParam(':dat', $dat, \PDO::PARAM_STR);
        return $stmt->execute();

    }
   function getFeedbackQADates()
    {
        $stmt =$this->conn->prepare("SELECT DISTINCT month_entered FROM qa_feedback ORDER BY month_entered DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
  function updateQAKey($claim_id,$key,$value)
    {
        $stmt =$this->conn->prepare("UPDATE quality_assurance SET ".$key."=:value WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, \PDO::PARAM_STR);       
        return $stmt->execute();
    }
function getPendingSplitClaims($status,$fields,$row,$rowperpage,$columnIndex,$columnName,$columnSortOrder,$searchValue)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(id) as num FROM split_claim WHERE status=:status');
$stmt->bindParam(':status', $status, PDO::PARAM_STR);  
        $stmt->execute();
      $totalRecords=$stmt->fetchColumn();
$search="%".$searchValue."%";

      if($searchValue != ''){
   $stmt = $this->conn->prepare('SELECT COUNT(a.id) as num FROM split_claim as a 
    INNER JOIN split_member as b ON a.split_member_id=b.id WHERE status=:status AND (loyalty_number like :search OR membership_number like :search OR beneficiary_name like :search OR beneficiary_name like :search OR beneficiary_scheme_join_date like :search OR co_payment like :search OR discharge_date like :search OR admission_date like :search OR procedure_date like :search OR beneficiary_id_number like :search)');
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);  
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);  
        $stmt->execute();
      $totalRecordwithFilter=$stmt->fetchColumn();
      /////
       $stmt = $this->conn->prepare('SELECT '.$fields.' FROM split_claim as a 
    INNER JOIN split_member as b ON a.split_member_id=b.id WHERE status=:status AND (loyalty_number like :search OR membership_number like :search OR beneficiary_name like :search OR beneficiary_name like :search OR beneficiary_scheme_join_date like :search OR co_payment like :search OR discharge_date like :search OR admission_date like :search OR procedure_date like :search OR beneficiary_id_number like :search) order by '.$columnName.' '.$columnSortOrder.' limit '.$row.','.$rowperpage);
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);  
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);  
        $stmt->execute();
      $empRecords=$stmt->fetchAll();
}

else{
  $totalRecordwithFilter=$totalRecords;
   $stmt = $this->conn->prepare('SELECT '.$fields.' FROM split_claim as a 
    INNER JOIN split_member as b ON a.split_member_id=b.id WHERE status=:status order by '.$columnName.' '.$columnSortOrder.' limit '.$row.','.$rowperpage);
   $stmt->bindParam(':status', $status, PDO::PARAM_STR); 
     $stmt->execute();
      $empRecords=$stmt->fetchAll();
}


## Fetch records
$data = array();
foreach ($empRecords as $row) {
   $claim_id=$row['claim_id'];
   $filename=$row['file_name'];
   $filename=$row['file_name'];
   $co_payment=implode(' | ', array_map(function ($entry) {
                return $entry['copayment'];
            }, $this->getSplitCopayments($claim_id)));

            $hospital_name="";
            foreach ($this->getHospitalNames($claim_id) as $x)
            {
                $hospital_name.=$x["hospital_name"]." <span style='color: red !important;'>|</span> ";
            }
   $data[] = array( 
      "claim_id"=>$row['claim_id'],
      "loyalty_number"=>"<span id='$claim_id' title='$filename' style='color: blue; cursor: pointer' onclick='openModal(\"$claim_id\")'>".$row['loyalty_number']."</span>",    
      "member_id"=>$row['member_id'],
      "beneficiary_name"=>$row['beneficiary_name'],
      "membership_number"=>$row['membership_number'],
      "beneficiary_scheme_join_date"=>$row['beneficiary_scheme_join_date'],
      "beneficiary_id_number"=>$row['beneficiary_id_number'],
      "beneficiary_date_of_birth"=>$row['beneficiary_date_of_birth'],
      "procedure_date"=>$row['procedure_date'],
      "admission_date"=>$row['admission_date'],
      "discharge_date"=>$row['discharge_date'],
      "hospital_name"=>$hospital_name,
      "co_payment"=>$co_payment
   );
}

$all["totalRecords"]=$totalRecords;
$all["totalRecordwithFilter"]=$totalRecordwithFilter;
$all["data"]=$data;
return $all;
    }
}
