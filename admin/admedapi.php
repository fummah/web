<?php
error_reporting(0);
include ("../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");

class jv_import_export
{
    public $mess2;
    public $mess3;
    public $username;
    public $password;

    function checkMember($policy_number,$client_id)
    {
        global $conn;
        $member_id="";
        $checkM=$conn->prepare('SELECT member_id FROM member WHERE policy_number=:policy_number AND client_id=:client_id LIMIT 1');
        $checkM->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $checkM->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $checkM->execute();
        $cc=$checkM->rowCount();
        if($cc>0)
        {
            $this->mess2="Duplicate Member";
            $member_id=$checkM->fetchColumn();
        }
        return $member_id;

    }
    function insertReOpenedCases($claim_id,$reason,$entered_by,$date_closed,$last_scheme_savings,$last_discount_savings)
    {
        global $conn;
        $logClaim = $conn->prepare('INSERT INTO `reopened_claims`(claim_id,reason,entered_by,date_closed,last_scheme_savings,last_discount_savings) VALUES(:claim_id,:reason,:entered_by,:date_closed,:last_scheme_savings,:last_discount_savings)');
        $logClaim->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $logClaim->bindParam(':reason', $reason, PDO::PARAM_STR);
        $logClaim->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $logClaim->bindParam(':date_closed', $date_closed, PDO::PARAM_STR);
        $logClaim->bindParam(':last_scheme_savings', $last_scheme_savings, PDO::PARAM_STR);
        $logClaim->bindParam(':last_discount_savings', $last_discount_savings, PDO::PARAM_STR);
        return (int)$logClaim->execute();
    }
    function checkClaim($claim_number,$client_id)
    {
        global $conn;
        $claim_id="";
        $checkM=$conn->prepare('SELECT a.claim_id FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_number=:claim_number AND b.client_id=:client_id LIMIT 1');
        $checkM->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $checkM->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $checkM->execute();
        $cc=$checkM->rowCount();
        if($cc>0)
        {
            $this->mess2="Duplicate Claim";
            $claim_id=$checkM->fetchColumn();
        }
        return $claim_id;
    }
    function getClaim($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT a.claim_id,a.Open,a.savings_scheme,a.savings_discount,a.date_closed FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id LEFT JOIN patient as d ON a.claim_id=d.claim_id WHERE a.claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
      function updateClaimKey($claim_id,$key,$value,$condition="")
    {
        global $conn;
        try
        {
        $stmt=$conn->prepare('UPDATE claim SET '.$key.'=:val WHERE claim_id=:claim_id'.$condition);
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, PDO::PARAM_STR);
        $stmt->execute();
    }
    catch(Exception $ex)
    {

    }
    }
    function getValidations($id)
    {
        global $conn;
        try
        {
        $stmt =$conn->prepare("SELECT vals FROM internal_rules WHERE id=:id"); 
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);       
        $stmt->execute();
        return $stmt->fetchColumn();
        }
    catch(Exception $ex)
    {
return "";
    }
    }
    function updateClaim($claim_id)
    {
        global $conn;
        $date_reopened=date("Y-m-d H:i:s");
        $checkM=$conn->prepare('UPDATE claim SET Open=1,date_reopened=:date_reopened WHERE claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':date_reopened', $date_reopened, PDO::PARAM_STR);
        $checkM->execute();
        $checkM->rowCount();

    }
    function getUsername()
    {
        global $conn;


        $stmt=$conn->prepare('SELECT username,email FROM users_information WHERE status=1 ORDER BY datetime ASC LIMIT 1');
        $stmt->execute();
        $row=$stmt->fetch();
        $details['username']=$row['0'];
        $details['email']=$row['1'];

        return $details;
    }
    function updateUsername($username)
    {
        global $conn;
        $date=date('Y-m-d H:i:s');
        $stmt=$conn->prepare('UPDATE users_information SET datetime=:dat WHERE username=:username');
        $stmt->bindParam(':dat', $date, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

    }
    function InsertPatient($claim_id,$first_name,$surname)
    {
        global $conn;
        $patient_name=$first_name." ".$surname;
        $stmt=$conn->prepare('INSERT INTO patient (claim_id, patient_name) VALUES(:claim_id,:patient_name)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
        $stmt->execute();


    }
    function InsertFeedback($claim_id,$description,$owner)
    {

        global $conn;
        $stmt=$conn->prepare('INSERT INTO `feedback`(`claim_id`, `description`, `owner`) VALUES(:claim_id,:description,:owner)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
        $stmt->execute();


    }
    function insertMember($policyNumber,$productName,$personNumber,$personName,$personSurname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id,$product_code,$benefitiary_number,$cell_number,$telephone,$email)
    {
        global $conn;
        $insertMember = $conn->prepare('INSERT INTO member(policy_number,productName,id_number,first_name,surname,medical_scheme,scheme_option,medicalSchemeRate,scheme_number,client_id,product_code,beneficiary_number,cell,telephone,email) VALUES
(:policy_number,:productName,:id_number,:first_name,:surname,:medical_scheme,:scheme_option,:medicalSchemeRate,:scheme_number,:client_id,:product_code,:beneficiary_number,:cell,:telephone,:email)');
        $insertMember->bindParam(':policy_number', $policyNumber, PDO::PARAM_STR);
        $insertMember->bindParam(':productName', $productName, PDO::PARAM_STR);
        $insertMember->bindParam(':id_number', $personNumber, PDO::PARAM_STR);
        $insertMember->bindParam(':first_name', $personName, PDO::PARAM_STR);
        $insertMember->bindParam(':surname', $personSurname, PDO::PARAM_STR);
        $insertMember->bindParam(':medical_scheme', $medicalSchemeName, PDO::PARAM_STR);
        $insertMember->bindParam(':scheme_option', $medicalSchemeOption, PDO::PARAM_STR);
        $insertMember->bindParam(':medicalSchemeRate', $medicalSchemeRate, PDO::PARAM_STR);
        $insertMember->bindParam(':scheme_number', $medicalSchemeNumber, PDO::PARAM_STR);
        $insertMember->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $insertMember->bindParam(':product_code', $product_code, PDO::PARAM_STR);
        $insertMember->bindParam(':beneficiary_number', $benefitiary_number, PDO::PARAM_STR);
        $insertMember->bindParam(':cell', $cell_number, PDO::PARAM_STR);
        $insertMember->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $insertMember->bindParam(':email', $email, PDO::PARAM_STR);
        $cc = $insertMember->execute();
        return $cc;
    }
    function insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy1,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number="",$patient_idnumber="")
    {
        global  $conn;
        $insertClaim = $conn->prepare('INSERT INTO claim(claim_number,member_id,entered_by,jv_status,Service_Date,end_date,charged_amnt,scheme_paid,gap,username,senderId,memberLiability,creationDate,
createdBy,patient_number,client_gap,pmb,icd10,icd10_desc,claim_number1,patient_idnumber) VALUES(:claim_number,:member_id,:entered_by,:jv_status,:Service_Date,:end_date,:charged_amnt,:scheme_paid,:gap,:username,:senderId,:memberLiability,:creationDate,:createdBy,:patient_number,:client_gap,:pmb,:icd10,:icd10_desc,:claim_number1,:patient_idnumber)');
        $insertClaim->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $insertClaim->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $insertClaim->bindParam(':entered_by', $createdBy, PDO::PARAM_STR);
        $insertClaim->bindParam(':jv_status', $eventStatus, PDO::PARAM_STR);
        $insertClaim->bindParam(':Service_Date', $eventDateFrom, PDO::PARAM_STR);
        $insertClaim->bindParam(':end_date', $eventDateTo, PDO::PARAM_STR);
        $insertClaim->bindParam(':charged_amnt', $claimChargedAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':scheme_paid', $schemePaidAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':gap', $claimCalcAmnt, PDO::PARAM_STR);
        //$insertClaim->bindParam(':recordType', $recordType, PDO::PARAM_STR);
        $insertClaim->bindParam(':senderId', $senderId, PDO::PARAM_STR);
        $insertClaim->bindParam(':memberLiability', $memberLiability, PDO::PARAM_STR);
        $insertClaim->bindParam(':createdBy', $createdBy1, PDO::PARAM_STR);
        $insertClaim->bindParam(':creationDate', $creationDate, PDO::PARAM_STR);
        $insertClaim->bindParam(':username', $username, PDO::PARAM_STR);
        $insertClaim->bindParam(':patient_number', $patient_number, PDO::PARAM_STR);
        $insertClaim->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        $insertClaim->bindParam(':pmb', $main_pmb, PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10', $main_icd10, PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10_desc', $main_icd10_desc, PDO::PARAM_STR);
        $insertClaim->bindParam(':claim_number1', $client_claim_number, PDO::PARAM_STR);
        $insertClaim->bindParam(':patient_idnumber', $patient_idnumber, PDO::PARAM_STR);
        $cc1 = $insertClaim->execute();
        return $cc1;
    }
    function updateClaim1($claim_id,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$senderid=6)
    {
        global  $conn;
        $insertClaim = $conn->prepare('UPDATE claim SET Service_Date=:Service_Date,end_date=:end_date,charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap,client_gap=:client_gap,pmb=:pmb,icd10=:icd10,icd10_desc=:icd10_desc,senderId=:senderid WHERE claim_id=:claim_id');

        $insertClaim->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insertClaim->bindParam(':Service_Date', $eventDateFrom, PDO::PARAM_STR);
        $insertClaim->bindParam(':end_date', $eventDateTo, PDO::PARAM_STR);
        $insertClaim->bindParam(':charged_amnt', $claimChargedAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':scheme_paid', $schemePaidAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':gap', $claimCalcAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        $insertClaim->bindParam(':pmb', $main_pmb, PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10', $main_icd10, PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10_desc', $main_icd10_desc, PDO::PARAM_STR);
        $insertClaim->bindParam(':senderid', $senderid, PDO::PARAM_STR);
        $cc1 = $insertClaim->execute();
        return $cc1;
    }

    function updateCaimline($claim_id,$practiceNo,$treatmentDate,$primaryICDCode,$primaryICDDescr,$tariffCode,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,$treatmentType)
    {
        global  $conn;
        $insertClaimline = $conn->prepare('UPDATE claim_line SET clmnline_charged_amnt=:clmnline_charged_amnt,clmline_scheme_paid_amnt=:clmline_scheme_paid_amnt,gap=:gap WHERE primaryICDCode=:primaryICDCode AND tariff_code=:tariff_code AND treatmentDate=:treatmentDate AND treatmentType=:treatmentType AND mca_claim_id=:mca_claim_id AND practice_number=:practice_number');
        $insertClaimline->bindParam(':mca_claim_id', $claim_id, PDO::PARAM_STR);
        $insertClaimline->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmnline_charged_amnt', $clmnlineChargedAmnt, PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmline_scheme_paid_amnt', $clmlineSchemePaidAmnt, PDO::PARAM_STR);
        $insertClaimline->bindParam(':gap', $clmlineCalcAmnt, PDO::PARAM_STR);
        $insertClaimline->bindParam(':treatmentDate', $treatmentDate, PDO::PARAM_STR);
        $insertClaimline->bindParam(':primaryICDCode', $primaryICDCode, PDO::PARAM_STR);
        $insertClaimline->bindParam(':tariff_code', $tariffCode, PDO::PARAM_STR);
        $insertClaimline->bindParam(':treatmentType', $treatmentType, PDO::PARAM_STR);
        $cc3 = $insertClaimline->execute();
        return $cc3;
    }
    function checkPmb($icd10)
    {
        global $conn2;
        $mess1["pmb_code"]="";
        $mess1["shortdesc"]="";
        $mess="";
        $stmt = $conn2->prepare('SELECT `diag_code`,`pmb_code`,`shortdesc` FROM `Diagnosis` WHERE diag_code=:num UNION SELECT `ICD10_Code`,`pmb`,`ICD10_3_Code_Desc` FROM `diagonisis1`  WHERE ICD10_Code=:num');
        $stmt->bindParam(':num', $icd10, PDO::PARAM_STR);
        $stmt->execute();
        $nu=$stmt->rowCount();
        if($nu>0) {

            $row=$stmt->fetch();
            $pmbCode= $row[1];
            if($pmbCode=="")
            {
                $mess="N";
            }
            else
            {
                $mess="Y";
            }
            $mess1["pmb_code"]=$mess;
            $mess1["shortdesc"]=$row[2];
        }
        return $mess1;
    }
    function updateAmount($claim_id,$charged_amnt,$scheme_paid,$gap)
    {
        try {

            global $conn;
            $stmt = $conn->prepare('SELECT charged_amnt,scheme_paid,gap FROM claim WHERE claim_id=:claim_id');
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->execute();
            $a = $stmt->fetch();
            $charged_amnt += (double)$a[0];
            $scheme_paid += (double)$a[1];
            $gap += (double)$a[2];

            $checkM = $conn->prepare('UPDATE claim SET charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap WHERE claim_id=:claim_id');
            $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $checkM->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
            $checkM->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
            $checkM->bindParam(':gap', $gap, PDO::PARAM_STR);
            $checkM->execute();
        }
        catch (Exception $e)
        {

        }


    }
    function readFile()
    {
        $charged_amntx=0;$scheme_paidx=0;$gapx=0;
        global $conn;
        $myArray=array();
        $resultArray=array();

        // $file = file_get_contents('mytext_received.txt', true);
        $file = file_get_contents('php://input', true);
        //$data = json_decode(file_get_contents('php://input'), true);
        //echo $file;
        //$dataList = substr($f, 1, -1);

        $t=json_decode($file,true);
        $this->mess3="";
        if($t === null) {

            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $err=array("status"=>"500","message"=>"Internal Server Error");
            echo json_encode($err,true);
            die();

        }
        $r=$t;
        $this->username = $r[0]["Username"];
        $this->password = $r[0]["Password"];
        $cclient_name="admed_production";
        $envviro="production";

        if(!$this->validate($this->username,$this->password,$envviro,$cclient_name))
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorised Access', true, 401);
            $err=array("status"=>"401","message"=>"Unauthorised Access");
            echo json_encode($err,true);
            die();
        }
        $this->addObj($file);
        $array_count=count($r);

        for($i=1;$i<$array_count;$i++) {
            $myArrayLine=array();
            $mess = "";
            $this->mess2 = "";
            $cccv="";

            $claim_number = $r[$i]["claim_number"];
            $xccomstatus="Failed";
            $todaydate=date("Y-m-d H:i:s");
            try {
                //member

                $policy_number = $r[$i]["policy_number"];
                $product_name = $r[$i]["product_name"];
                $personNumber = "";
                $policyholder_name = $r[$i]["policyholder_name"];
                $policyholder_surname = $r[$i]["policyholder_surname"];
                $product_code=$r[$i]["product_code"];
                $patient_name=$r[$i]["patient_name"];
                $patient_surname=$r[$i]["patient_surname"];
                $patient_idnumber=$r[$i]["patient_idnumber"];
                $beneficiary_number=$r[$i]["beneficiary_number"];
                $cell_number=$r[$i]["cell_number"];
                $telephone_number=$r[$i]["telephone_number"];
                $email_address=$r[$i]["email_address"];
                $medicalSchemeName = $r[$i]["medical_scheme"];
                $medicalSchemeName=$this->checkScheme($medicalSchemeName);
                $medicalSchemeName=strtolower($medicalSchemeName);
                $medicalSchemeName=ucwords($medicalSchemeName);
                $medicalSchemeOption = $r[$i]["medical_scheme_option"];
                $medicalSchemeRate = "";
                $medicalSchemeNumber = $r[$i]["medical_scheme_number"];
                $incident_date_start = empty($r[$i]["incident_date_start"])?null:$r[$i]["incident_date_start"];
                $incident_date_end = empty($r[$i]["incident_date_end"])?null:$r[$i]["incident_date_end"];
                $claimChargedAmnt = (double)$r[$i]["charged_amount"];
                $schemePaidAmnt = (double)$r[$i]["schemepaid_amount"];

                $owner=$this->username;
                $claimCalcAmnt=$claimChargedAmnt-$schemePaidAmnt;
                $c_amount=0;
                $s_amount=0;
                $recordType = "";
                $senderId = 6;
                $createdBy = "System";
                $createdByU = $r[$i]["last_worked_on_user"];
                $creationDate = $r[$i]["date_sent"];
                $eventStatus = "";
                $eventDateFrom = $incident_date_start;
                $eventDateTo = $incident_date_end;
                $memberLiability=0.0;
                $patient_number="";
                $switchReference="";
                $client_claim_number ="";
                $details=$this->getUsername();
                $emergencyarr=explode(",",$this->getValidations(5));
                $username=$details['username'];
                $client_gap =(double)$r[$i]["gap_amount"];
                //$note_arr=$r[$i]["notes"];
                //print_r($note_arr);
                $doctors=$r[$i]["claimedline"];

                $main_icd10=$r[$i]["icd10"];
                $main_icd10_desc=$r[$i]["icd10_description"];

                $ic10Data=$this->checkPmb($main_icd10);
                $main_pmb=strlen($ic10Data["pmb_code"])>0?1:0;

                $continue=true;
                $client_id = 6;
                $xopen=1;
                $xclosed_date="";
                $xscheme_savings="";
                $xdiscount_savings="";
                $member_id=$this->checkMember($policy_number,$client_id);
                if(empty($member_id) && $continue) {
                    $cc=$this->insertMember($policy_number,$product_name,$personNumber,$policyholder_name,$policyholder_surname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id,$product_code,$beneficiary_number,$cell_number,$telephone_number,$email_address);

                    if ($cc == 1) {
                        $this->mess2="Member Successfully added";
                        $selectlastmember = $conn->prepare("SELECT max(member_id) FROM member WHERE policy_number=:policy_number");
                        $selectlastmember->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
                        $selectlastmember->execute();//Claim data
                        $member_id = $selectlastmember->fetchColumn();
                    }
                    else {
                        $this->mess2="There is an error";
                        $member_id="";
                    }
                }
                $member_id=(int)$member_id;
                if($member_id>0)
                {

                    $claim_id=$this->checkClaim($claim_number,$client_id);

                    if(empty($claim_id)) {
                        $cc1=$this->insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdByU,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number,$patient_idnumber);
                        if ($cc1 == 1) {
                            $this->mess2="Claim Successfully added";
                            $xccomstatus="success";
                            $this->updateUsername($username);

                            $selectlastclaim = $conn->prepare("SELECT max(claim_id) FROM claim WHERE claim_number=:claim_number");
                            $selectlastclaim->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
                            $selectlastclaim->execute();
                            $claim_id = $selectlastclaim->fetchColumn();
                            $this->InsertPatient($claim_id,$patient_name,$patient_surname);
							/*
                            $countnotes=count($note_arr);

                            for ($dn=0;$dn<$countnotes;$dn++)
                            {
                                $descriptionx=$note_arr[$dn]["note"];
                                $this->InsertFeedback($claim_id,$descriptionx,$owner);
                            }

*/
                        }
                        else {
                            $this->mess2="Claim Failed to Load";
                            $claim_id="";
                        }
                    }
                    else
                    {
                        $claim_data=$this->getClaim($claim_id);
                        $xopen=(int)$claim_data["Open"];

                        $xclosed_date=$claim_data["date_closed"];
                        $xscheme_savings=$claim_data["savings_scheme"];
                        $xdiscount_savings=$claim_data["savings_discount"];
                        $xccomstatus="success";
                        $this->mess3=" (Information updated)";
                        $this->updateClaim1($claim_id,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc);
                        /*
						$countnotes=count($note_arr);

                        for ($dn=0;$dn<$countnotes;$dn++)
                        {
                            $descriptionx=$note_arr[$dn]["note"];
                            $this->InsertFeedback($claim_id,$descriptionx,$owner);
                        }
						*/
                    }
                    $claim_id=(int)$claim_id;
                    if($claim_id>0)
                        //Doctors Information
                    {

                        $countdoctors=count($doctors);

                        for ($d=0;$d<$countdoctors;$d++)
                        {
                            $chhh=false;
                            $practiceNo = $doctors[$d]["provider_number"];

                            $pracno_1=str_pad($practiceNo, 7, '0', STR_PAD_LEFT);
                            $practiceNo=$pracno_1;
                            $practiceName = $doctors[$d]["fullname"];
                            $claimedline_id = $doctors[$d]["claimedline_id"];
                            $SP_total_charged_amount = (double)$doctors[$d]["sp_total_charged_amount"];
                            $SP_total_scheme_paid = (double)$doctors[$d]["sp_total_scheme_paid"];
                            $doc_gap = (double)$doctors[$d]["gap_amount"];
                            $benefitDescription =$doctors[$d]["benefit_tiers"];
                            $provider_invoicenumber =$doctors[$d]["provider_invoicenumber"];
                            $providertypedesc = "";

                            //echo $practiceNo;
                            if (!$this->checkDoctor($practiceNo)) {

                                $insertDoctor1 = $conn->prepare('INSERT INTO doctor_details(name_initials,practice_number,discipline) VALUES(:firstname,:practiceno,:service)');
                                $insertDoctor1->bindParam(':firstname', $practiceName, PDO::PARAM_STR);
                                $insertDoctor1->bindParam(':practiceno',  $pracno_1, PDO::PARAM_STR);
                                $insertDoctor1->bindParam(':service', $providertypedesc, PDO::PARAM_STR);
                                $kk=$insertDoctor1->execute();
                                if ($kk==1)
                                {
                                    $this->mess2="Claim Successfully added";
                                }
                                else{
                                    $chhh=false;
                                    $this->mess2="Claim Successfully added but doctor failed to load";
                                }
                            }

                            $checkM=$conn->prepare('SELECT claim_id,practice_number,doc_gap FROM doctors WHERE claim_id=:claim_id AND practice_number=:practice_number LIMIT 1');
                            $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                            $checkM->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                            $checkM->execute();
                            $cc=$checkM->rowCount();

                            if($cc>0)
                            {
                                $chhh=true;
                                $doc_gaparr=$checkM->fetch();
                                //$doc_gap+=(double)$doc_gaparr[2];
                            }
                            if(!$chhh) {
                                $insertDoctor = $conn->prepare('INSERT INTO doctors(claim_id,practice_number,claimedline_id,doc_gap,provider_invoicenumber,doc_charged_amount,doc_scheme_amount) VALUES(:claim_id,:practice_number,:claimedline_id,:doc_gap,:provider_invoicenumber,:SP_total_charged_amount,:SP_total_scheme_paid)');
                                $insertDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':claimedline_id', $claimedline_id, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':doc_gap', $doc_gap, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':provider_invoicenumber', $provider_invoicenumber, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':SP_total_charged_amount', $SP_total_charged_amount, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':SP_total_scheme_paid', $SP_total_scheme_paid, PDO::PARAM_STR);

                                $cc2 = $insertDoctor->execute();
                                if ($cc2 == 1) {
                                    $this->mess2="Claim Successfully added";
                                    $chhh=true;
                                }
                                else {
                                    $chhh=false;
                                    $this->mess2="Claim Successfully added but doctor failed to load";
                                }

                            }
                            else
                            {
                                $insertDoctor = $conn->prepare('UPDATE doctors SET doc_gap=:doc_gap,claimedline_id=:claimedline_id WHERE claim_id=:claim_id AND practice_number=:practice_number');
                                $insertDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':doc_gap', $doc_gap, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':claimedline_id', $claimedline_id, PDO::PARAM_STR);
                                $cc2 = $insertDoctor->execute();
                            }
                            if($chhh)
                            {

                                $claimLine = $doctors[$d]["claimline"];
                                $countLine=count($claimLine);

                                for($j=0;$j<$countLine;$j++) {
                                    $clmnlineNumber = 0;

                                    $clmnlineChargedAmnt = (double)$claimLine[$j]["claimline_charge_amount"];
                                    $clmlineSchemePaidAmnt = (double)$claimLine[$j]["claimline_schemepaid_amount"];
                                    $gap_amount_line = (double)$claimLine[$j]["gap_amount_line"];
                                    //$clmlineCalcAmnt = (double)$claimLine[$j]["clmlineCalcAmnt"];
                                    $clmlineCalcAmnt=$clmnlineChargedAmnt-$clmlineSchemePaidAmnt;
                                    //$clmlineCalcAmnt=$gap_amount_line;
                                    $memberLiability = 0.0;
                                    $benefitDescription =$benefitDescription;
                                    $treatmentType = $claimLine[$j]["treatment_type"];
                                    $treatmentDate = $claimLine[$j]["treatment_date"];
                                    $treatmentDate=strlen($treatmentDate)>1?$treatmentDate:$eventDateFrom;
                                    $treatment_code = $claimLine[$j]["treatment_code"];


                                    $claimline_rejection_reason = $claimLine[$j]["claimline_rejection_reason"];
                                    $treatment_code_description = $claimLine[$j]["treatment_code_description"];
                                    $claimline_schemerate_amount = $claimLine[$j]["claimline_schemerate_amount"];
                                    $treatment_linked_treatmentcodes = $claimLine[$j]["treatment_linked_treatmentcodes"];
                                    $secondaryICDCode = "";
                                    $secondaryICDDescr = "";
                                    $primaryICDCode = $claimLine[$j]["icd10"];
                                    $primaryICDDescr = $claimLine[$j]["icd10_description"];
                                    $tariffCode = $claimLine[$j]["treatment_code"];
                                    $modifier = "";
                                    $primaryICDCode=strlen($primaryICDCode)>1?$primaryICDCode:$main_icd10;
                                    $primaryICDDescr=strlen($primaryICDDescr)>1?$primaryICDDescr:$main_icd10_desc;
                                    $ic10Data=$this->checkPmb($primaryICDCode);
                                    $main_pmb=strlen($ic10Data["pmb_code"])>0?1:0;
                                    $unit = "";
                                    $PMBFlag =$main_pmb>0 && $ic10Data["pmb_code"]!="0"?"Y":"N";
                                    $clmnLinePmntStatus = "";

                                    $rej_code = $claimline_rejection_reason;
                                    $short_msg = $treatment_code_description;
                                    $lon_msg = "";
                                    $cptCode = "";
                                    $nappiCode = "";
                                    $quantity = "";
                                    $clmnLinePmntStatusDate = "";
                                    $treatmentCodeType = $treatment_code;
                                    $cptDescr = "";
                                    $lastUpdateDate = "";
                                    $toothNo = "";
                                    $selectClaimline = $conn->prepare('SELECT mca_claim_id FROM claim_line WHERE primaryICDCode=:icd AND tariff_code=:tariff_code AND treatmentDate=:treatmentDate AND treatmentType=:treatmentType AND clmnline_charged_amnt=:clmnline_charged_amnt AND clmline_scheme_paid_amnt=:clmline_scheme_paid_amnt AND gap_aamount_line=:gap_aamount_line AND mca_claim_id=:mca_claim_id AND practice_number=:practice_number');
                                    $selectClaimline->bindParam(':icd', $primaryICDCode, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':tariff_code', $tariffCode, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':treatmentDate', $treatmentDate, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':treatmentType', $treatmentType, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':mca_claim_id', $claim_id, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':clmnline_charged_amnt', $clmnlineChargedAmnt, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':clmline_scheme_paid_amnt', $clmlineSchemePaidAmnt, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':gap_aamount_line', $gap_aamount_line, PDO::PARAM_STR);
                                    $selectClaimline->execute();
                                    $lcc=(int)$selectClaimline->rowCount();
                                    $mess1="";

                                    if ($lcc<1) {
                                        $charged_amntx+=$clmnlineChargedAmnt;
                                        $scheme_paidx+=$clmlineSchemePaidAmnt;
                                        $gapx+=$clmlineCalcAmnt;

                                        $insertClaimline = $conn->prepare('INSERT INTO claim_line(recordType,senderId,jv_claim_line_number,mca_claim_id,practice_number,clmnline_charged_amnt,
clmline_scheme_paid_amnt,gap,memberLiability,benefit_description,treatmentDate,primaryICDCode,primaryICDDescr,tariff_code,modifier,unit,PMBFlag,clmn_line_pmnt_status,creationDate,createdBy,msg_code,
msg_dscr,lng_msg_dscr,treatmentType,secondaryICDCode,secondaryICDDescr,cptCode,nappiCode,quantity,clmn_line_status_date,treatment_code_type,cptDescr,lastUpdateDate,toothNo,switch_reference,gap_aamount_line) VALUES(:recordType,:senderId,:jv_claim_line_number,:mca_claim_id,:practice_number,:clmnline_charged_amnt,:clmline_scheme_paid_amnt,:gap,:memberLiability,:benefit_description,
:treatmentDate,:primaryICDCode,:primaryICDDescr,:tariff_code,:modifier,:unit,:PMBFlag,:clmn_line_pmnt_status,:creationDate,:createdBy,:msg_code,:msg_dscr,:lng_msg_dscr,:treatmentType,:secondaryICDCode,:secondaryICDDescr,:cptCode,:nappiCode,:quantity,:clmn_line_status_date,:treatment_code_type,:cptDescr,:lastUpdateDate,:toothNo,:switch_reference,:gap_aamount_line)');
                                        $insertClaimline->bindParam(':recordType', $recordType, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':senderId', $senderId, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':jv_claim_line_number', $clmnlineNumber, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':mca_claim_id', $claim_id, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':clmnline_charged_amnt', $clmnlineChargedAmnt, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':clmline_scheme_paid_amnt', $clmlineSchemePaidAmnt, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':gap', $clmlineCalcAmnt, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':memberLiability', $memberLiability, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':benefit_description', $benefitDescription, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':treatmentDate', $treatmentDate, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':primaryICDCode', $primaryICDCode, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':primaryICDDescr', $primaryICDDescr, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':tariff_code', $tariffCode, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':modifier', $modifier, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':unit', $unit, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':PMBFlag', $PMBFlag, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':clmn_line_pmnt_status', $clmnLinePmntStatus, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':creationDate', $creationDate, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':msg_code', $rej_code, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':msg_dscr', $short_msg, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':lng_msg_dscr', $lon_msg, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':treatmentType', $treatmentType, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':secondaryICDCode', $secondaryICDCode, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':secondaryICDDescr', $secondaryICDDescr, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':cptCode', $cptCode, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':nappiCode', $nappiCode, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':quantity', $quantity, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':clmn_line_status_date', $clmnLinePmntStatusDate, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':treatment_code_type', $treatmentCodeType, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':cptDescr', $cptDescr, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':lastUpdateDate', $lastUpdateDate, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':toothNo', $toothNo, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':switch_reference', $switchReference, PDO::PARAM_STR);
                                        $insertClaimline->bindParam(':gap_aamount_line', $gap_amount_line, PDO::PARAM_STR);
                                        $cc3 = $insertClaimline->execute();
                                        if ($cc3 == 1) {
                                            $xccomstatus="success";
                                            $mess = "Successfully added";
                                            $this->mess3=" with additional information";
                                            if($xopen==0)
                                            {
                                                $this->insertReOpenedCases($claim_id,"New Claim Lines",$cclient_name,$xclosed_date,$xscheme_savings,$xdiscount_savings);
                                                $xopen=1;
                                            }
                                            $this->updateClaim($claim_id);
                                            if(in_array($tariffCode, $emergencyarr))
                                            {
                                                $this->updateClaimKey($claim_id,"emergency","1");
                                            }

                                        } else {
                                            $mess = "There is an error";
                                        }
                                    }
                                    else{
                                        $this->updateCaimline($claim_id,$practiceNo,$treatmentDate,$primaryICDCode,$primaryICDDescr,$tariffCode,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,$treatmentType);
                                        $mess = "Duplicate Claim Line";

                                    }
                                    $eachLine=array("lineNumber"=>$clmnlineNumber,"message"=>$mess);
                                    array_push($myArrayLine,$eachLine);
                                }

                            } else {
                                $this->mess2 = "The doctor not loaded";
                            }
                        }
                        $this->updateAmount($claim_id,$charged_amntx,$scheme_paidx,$gapx);
                    } else {
                        $this->mess2 = "The claim not loaded";
                    }
                } else {
                    $this->mess2 = "The member not loaded";
                }

                //$stmnt=$conn->prepare('INSERT INTO member VALUES()');

            } catch (Exception $e) {
                $this->mess2 = $e->getMessage();
            }
            finally
            {
                //$xccomstatus=$this->mess2;

                $myarr=array("claim_number"=>$claim_number,"status"=>$xccomstatus,"descr"=>$this->mess2.$this->mess3,"date_entered"=>$todaydate);
                array_push($resultArray,$myarr);
            }

            $eacharray=array("claim_number"=>$claim_number,"message"=>$this->mess2,"claimline"=>$myArrayLine);
            array_push($myArray,$eacharray);
        }

        $rc=count($myArray);
        if($rc<1)
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $err=array("status"=>"500","message"=>"Internal Server Error");
            echo json_encode($err,true);
            die();
        }

        $claim_array=array();
        $succeed=0;
        $failed=0;
        for($x=0;$x<$rc;$x++)
        {

            $num=$myArray[$x]["claim_number"];
            $message=$myArray[$x]["message"];
            $line=$myArray[$x]["claimline"];
            $this->claimaudit($num,$message);

            if($message=="Claim Successfully added")
            {
                $succeed++;

            }
            else{
                $failed++;
            }

            $xsucceed=0;
            $xfailed=0;
            $rd=count($line);
            $claim_line_array=array();
            for ($y=0;$y<count($line);$y++)
            {

                $l=$line[$y]["lineNumber"];
                $m=$line[$y]["message"];
                $in_array=array("claim_line_number"=>$l,"claim_line_message"=>$m);
                array_push($claim_line_array,$in_array);
                $this->claimaudit($num,$m,$l);

                if($m=="Successfully added")
                {
                    $xsucceed++;

                }
                else{
                    $xfailed++;
                }
            }
            $in_claim=array("event_number"=>$num,"claim_message"=>$message,"claim_line"=>$claim_line_array);
            array_push($claim_array,$in_claim);
        }

        $display_array=array("total_processed"=>$rc,"total_succeed"=>$succeed,"total_failed"=>$failed,"claims"=>$claim_array);
        $arres=json_encode($resultArray,true);
        echo $arres;
        $this->allaudit((int)$rc,(int)$failed,(int)$succeed,5,$arres);
    }
    function checkDoctor($number)
    {

        global $conn;
        $check=false;
        try {
            $stmt = $conn->prepare('SELECT practice_number FROM doctor_details WHERE practice_number=:num');
            $stmt->bindParam(':num', $number, PDO::PARAM_STR);
            $stmt->execute();
            $ccc = $stmt->rowCount();
            if ($ccc > 0) {
                $check = true;
            }
        }
        catch (Exception $e)
        {
            $check=false;
        }
        return $check;
    }


    function export()
    {
        global $conn;

        try {

            global $conn;
            $selectDetails = $conn->prepare('SELECT recordType,senderId,claim_number as eventNumber,jv_status as eventStatus,eventStatusDate,Service_Date as eventDateFrom,end_date as eventDateTo,
charged_amnt as claimChargedAmnt,scheme_paid as schemePaidAmnt,gap as claimCalcAmnt,memberLiability FROM claim LIMIT 2');
            $selectDetails->execute();
            $all=array();
            foreach ($selectDetails->fetchAll() as $r)
            {
                $myArrayx=array();
                $selectDetails1=$conn->prepare('SELECT jv_claim_line_number as clmnlineNumber,treatmentDate,treatmentType,clmnline_charged_amnt as clmnlineChargedAmnt,clmline_scheme_paid_amnt as clmlineSchemePaidAmnt,
 gap as clmlineCalcAmnt,memberLiability,benefit_description as benefitDescription,PMBFlag,clmn_line_pmnt_status as clmnLinePmntStatus,clmn_line_status_date as clmnLinePmntStatusDate,clmnLinePmntStatusBy FROM claim_line LIMIT 2');
                $selectDetails1->execute();

                foreach ($selectDetails1->fetchAll() as $r1) {
                    array_push($myArrayx,$r1);

                }

                $testArray=array( "recordType"=> $r[0], "senderId"=> $r[1] ,"eventNumber"=> $r[2], "eventStatus"=> $r[3], "eventStatusDate"=> $r[4], "eventDateFrom"=> $r[5] ,"eventDateTo"=> $r[6], "claimChargedAmnt"=>$r[7], "schemePaidAmnt"=> $r[8],
                    "claimCalcAmnt"=> $r[9], "memberLiability"=>$r[10],"claimLine"=>$myArrayx);

                array_push($all,$testArray);
            }
            $f=json_encode($all);
            $file = 'mytext_return.txt';

            file_put_contents($file, $f);
            //var_dump($all);




        }
        catch (Exception $r)
        {
            echo "There is an error";
        }




    }
    function test()
    {
        $file = file_get_contents('mytext_received.txt', true);
        // echo $file;
        $t=json_decode($file,true);
        //$personName = $t["claimLine"][0]["benefitDescription"];
        // echo $personName;
        var_dump($t);
        //$cc=count($t);
        //echo $cc;

    }

    function claimaudit($claim_number,$message,$line_number="")
    {
        try {
            global $conn;
            $stnt = $conn->prepare('INSERT INTO `error_claim`(`claim_number`,`line_number`, `message`) VALUES (:claim_number,:line_number, :message)');
            $stnt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
            $stnt->bindParam(':line_number', $line_number, PDO::PARAM_STR);
            $stnt->bindParam(':message', $message, PDO::PARAM_STR);
            $stnt->execute();
        }
        catch (Exception $r)
        {
            $this->mess2=$r->errorMessage();
        }
    }
    function allaudit($total,$failed,$succeed,$status="",$desciption1="")
    {
        try {
            global $conn;
            $ip_address=$this->get_IP_address();
            $url= $_SERVER['HTTP_HOST'];
            $url.= $_SERVER['REQUEST_URI'];
            $stnt = $conn->prepare('INSERT INTO `jarvis_files`(`status`,`total`, `succeed`,`failed`,`desciption`,`desciption1`,`ip_address`) VALUES (:status,:total,:succeed,:failed,:desciption,:desciption1,:ip_address)');
            $stnt->bindParam(':status', $status, PDO::PARAM_STR);
            $stnt->bindParam(':total', $total, PDO::PARAM_STR);
            $stnt->bindParam(':succeed', $succeed, PDO::PARAM_STR);
            $stnt->bindParam(':failed', $failed, PDO::PARAM_STR);
            $stnt->bindParam(':desciption', $url, PDO::PARAM_STR);
            $stnt->bindParam(':desciption1', $desciption1, PDO::PARAM_STR);
            $stnt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
            $stnt->execute();
        }
        catch (Exception $r)
        {
            $this->mess2=$r->errorMessage();
        }
    }
    function get_IP_address()
    {
        foreach (array('HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                    $IPaddress = trim($IPaddress); // Just to be safe

                    if (filter_var($IPaddress,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                        !== false) {

                        return $IPaddress;
                    }
                }
            }
        }
    }
    function checkScheme($medical_scheme)
    {
        global $conn;

        $stmt=$conn->prepare('SELECT name FROM schemes WHERE name=:name1');
        $stmt->bindParam(':name1', $medical_scheme, PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc<1)
        {
            $stmt=$conn->prepare('SELECT original_name FROM schemes_owl WHERE duplicate_name=:name1');
            $stmt->bindParam(':name1', $medical_scheme, PDO::PARAM_STR);
            $stmt->execute();
            $ccx=$stmt->rowCount();
            if($ccx>0)
            {
                $medical_scheme=$stmt->fetchColumn();
            }
            else{
                $medical_scheme="Unknown";
            }

        }
        return $medical_scheme;

    }


    public function validate($username,$password,$enviro,$othername)
    {
        global $conn1;
        $stmt=$conn1->prepare('select username,password,environment,other_name from staff_users where username=:user1 AND role_value=1');
        $stmt->bindParam(':user1', $username, PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc==1)
        {
            $vv=$stmt->fetch();
            $pass=$vv["password"];
            if(password_verify($password,$pass) && $enviro==$vv["environment"] && $othername==$vv["other_name"])
            {
                $ret=true;
            }
            else{
                $ret=false;
            }

        }
        else{
            $ret=false;
        }

        return $ret;
    }
    public function xuser($user)
    {
        global $conn;
        $user1=substr($user, 0, -1);
        $name="Naomi";
        $stmt=$conn->prepare('SELECT username FROM users_information WHERE username=:user1');
        $stmt->bindParam(':user1', $user1, PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc>0)
        {
            $name=$stmt->fetchColumn();
        }
        return $name;
    }
    public function addObj($obj)
    {
        try {
            global $conn;
            $stmt = $conn->prepare('INSERT INTO jv_objects(obj) VALUES(:obj)');
            $stmt->bindParam(':obj', $obj, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (Exception $e)
        {

        }


    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n=new jv_import_export();
    $n->readFile();

}
else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
    $err=array("status"=>"400","message"=>"Bad Request");
    echo json_encode($err,true);
}
?>

