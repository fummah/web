<?php
error_reporting(0);
include ("../../mca/link2.php");
$conn=connection("seamless","seamless");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");
//$conn3=connection("seamless","seamless");

class jv_import_export
{
    public $mess2;
    public $mess3;
    public $username;
    public $password;

    function checkMember($policy_number)
    {
        global $conn;
        $member_id="";
        $checkM=$conn->prepare('SELECT member_id FROM member WHERE policy_number=:policy_number LIMIT 1');
        $checkM->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $checkM->execute();
        $cc=$checkM->rowCount();
        if($cc>0)
        {
            $this->mess2="Duplicate Member";
            $member_id=$checkM->fetchColumn();
        }
        return $member_id;

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
    function checkClaim1($claim_number)
    {
        global $conn;
        $try=false;
        $checkM=$conn->prepare('SELECT a.claim_id FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_number=:claim_number AND b.client_id=30 LIMIT 1');
        $checkM->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $checkM->execute();
        $cc=$checkM->rowCount();
        if($cc>0)
        {

            $try=true;
        }
        return $try;
    }
    function updateClaim($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare('UPDATE claim SET Open=2 WHERE claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
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
        try {


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
        catch (Exception $e)
        {
            return "Error : ".$e->getMessage();
        }
    }
    function insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy1,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number="",$patient_dob="",$patient_gender="")
    {
        global  $conn;
        $open=2;
        $insertClaim = $conn->prepare('INSERT INTO claim(claim_number,member_id,entered_by,jv_status,Service_Date,end_date,charged_amnt,scheme_paid,gap,username,recordType,senderId,memberLiability,creationDate,
createdBy,patient_idnumber,client_gap,pmb,icd10,icd10_desc,claim_number1,patient_dob,patient_gender,Open) VALUES(:claim_number,:member_id,:entered_by,:jv_status,:Service_Date,:end_date,:charged_amnt,:scheme_paid,:gap,:username,:recordType,:senderId,:memberLiability,:creationDate,:createdBy,:patient_number,:client_gap,:pmb,:icd10,:icd10_desc,:claim_number1,:patient_dob,:patient_gender,:open1)');
        $insertClaim->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $insertClaim->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $insertClaim->bindParam(':entered_by', $createdBy, PDO::PARAM_STR);
        $insertClaim->bindParam(':jv_status', $eventStatus, PDO::PARAM_STR);
        $insertClaim->bindParam(':Service_Date', $eventDateFrom, PDO::PARAM_STR);
        $insertClaim->bindParam(':end_date', $eventDateTo, PDO::PARAM_STR);
        $insertClaim->bindParam(':charged_amnt', $claimChargedAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':scheme_paid', $schemePaidAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':gap', $claimCalcAmnt, PDO::PARAM_STR);
        $insertClaim->bindParam(':recordType', $recordType, PDO::PARAM_STR);
        $insertClaim->bindParam(':senderId', $senderId, PDO::PARAM_STR);
        $insertClaim->bindParam(':memberLiability', $memberLiability, PDO::PARAM_STR);
        $insertClaim->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
        $insertClaim->bindParam(':creationDate', $creationDate, PDO::PARAM_STR);
        $insertClaim->bindParam(':username', $username, PDO::PARAM_STR);
        $insertClaim->bindParam(':patient_number', $patient_number, PDO::PARAM_STR);
        $insertClaim->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        $insertClaim->bindParam(':pmb', $main_pmb, PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10', $main_icd10, PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10_desc', $main_icd10_desc, PDO::PARAM_STR);
        $insertClaim->bindParam(':claim_number1', $client_claim_number, PDO::PARAM_STR);
        $insertClaim->bindParam(':patient_dob', $patient_dob, PDO::PARAM_STR);
        $insertClaim->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
        $insertClaim->bindParam(':open1', $open, PDO::PARAM_STR);
        $cc1 = $insertClaim->execute();
        return $cc1;
    }
    function updateClaim1($claim_id,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc)
    {
        global  $conn;
        $insertClaim = $conn->prepare('UPDATE claim SET Service_Date=:Service_Date,end_date=:end_date,charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap,client_gap=:client_gap,pmb=:pmb,icd10=:icd10,icd10_desc=:icd10_desc WHERE claim_id=:claim_id');

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
    function readFile()
    {

        global $conn;
        $myArray=array();
        $resultArray=array();


        $file = "[".file_get_contents('php://input', true)."]";
        //$file = file_get_contents('php://input', true);
        //$data = json_decode(file_get_contents('php://input'), true);

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


        if(!$this->validate($this->username,$this->password))
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorised Access', true, 401);
            $err=array("status"=>"401","message"=>"Unauthorised Access");
            echo json_encode($err,true);
            die();
        }
        $this->addObj($file);
        $array_count=count($r);

        for($i=0;$i<$array_count;$i++) {
            $myArrayLine=array();
            $mess = "";
            $this->mess2 = "Test1";
            $cccv="";

            $claim_number = $r[$i]["ClaimNumber"];
            $xccomstatus="Failed";
            $todaydate=date("Y-m-d H:i:s");
            try {
                //member

                $GAPinsurer = $r[$i]["GAPinsurer"];
                $policy_number = $r[$i]["PolicyNumber"];
                $product_name = $r[$i]["ProductName"];

                $personNumber = $r[$i]["MemberId"];
                $policyholder_name = $r[$i]["MemberName"];
                $policyholder_surname = $r[$i]["MemberSurname"];
                $product_code="";
                $patient_name=$r[$i]["PatientName"];
                $patient_surname=$r[$i]["PatientSurname"];
                $patient_idnumber=$r[$i]["PatientId"];
                $patient_dob=$r[$i]["PatientDOB"];
                $patient_gender=$r[$i]["PatientGender"];
                $beneficiary_number="";
                $cell_number="";
                $telephone_number="";
                $email_address="";
                $medicalSchemeName = $r[$i]["SchemeName"];
                $medicalSchemeName=$this->checkScheme($medicalSchemeName);
                $medicalSchemeName=strtolower($medicalSchemeName);
                $medicalSchemeName=ucwords($medicalSchemeName);
                $medicalSchemeOption = $r[$i]["SchemeOption"];
                $medicalSchemeRate = $r[$i]["SchemeRate"];
                $medicalSchemeNumber = $r[$i]["SchemeNumber"];
                $DateOfService = $r[$i]["DateOfService"];
                $PrimaryICDCode = $r[$i]["PrimaryICDCode"];
                $claimChargedAmnt = (double)$r[$i]["PaidAmount"];
                $schemePaidAmnt = (double)$r[$i]["SchemePaidAmount"];
                $client_gap = 0;
                $owner="MedEDI";
                $claimCalcAmnt=(double)$r[$i]["Gap"];
                $recordType = "";
                $senderId = 1;
                $createdBy = "System";
                $eventStatus = "";
                $memberLiability=0.0;
                $patient_number=$patient_idnumber;
                $switchReference="";
                $client_claim_number ="";
                //$details=$this->getUsername();
                //$username=$details['username'];
                $username="Faghry";
                $doctors=$r[$i]["Providers"];
                $continue=true;
                $client_id = 16;
                $pos10 = strpos($product_name, "Kaelo");
                $pos11 = strpos($product_name, "Sanlam");
                if($pos10>-1) {
                    $client_id = 3;
                }
                elseif ($pos11>-1)
                {
                    $client_id = 15;
                }


                $member_id=$this->checkMember($policy_number);
                //echo "-->".$member_id;

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

                    $claim_id=(int)$this->checkClaim($claim_number,$client_id);

                    if($claim_id<1) {
                        $cc1=$this->insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$DateOfService,null,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy,"",$username,$patient_number,$client_gap,null,$PrimaryICDCode,"",$client_claim_number,$patient_dob,$patient_gender);
                        if ($cc1 == 1) {
                            $this->mess2="Claim Successfully added";
                            $xccomstatus="success";
                            // $this->updateUsername($username);

                            $selectlastclaim = $conn->prepare("SELECT max(claim_id) FROM claim WHERE claim_number=:claim_number");
                            $selectlastclaim->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
                            $selectlastclaim->execute();
                            $claim_id = $selectlastclaim->fetchColumn();
                            $this->InsertPatient($claim_id,$patient_name,$patient_surname);

                        }
                        else {
                            $this->mess2="Claim Failed to Load";
                            $claim_id="";
                        }
                    }
                    else
                    {
                        $xccomstatus="success";
                        $this->mess3=" (Information updated)";
                    }
                    $claim_id=(int)$claim_id;

                    if($claim_id>0)
                        //Doctors Information
                    {

                        $countdoctors=count($doctors);

                        for ($d=0;$d<$countdoctors;$d++)
                        {
                            $chhh=false;
                            $practiceNo = $doctors[$d]["ProviderPracticeNumber"];
                            $pracno_1=str_pad($practiceNo, 7, '0', STR_PAD_LEFT);
                            $practiceNo=$pracno_1;
                            $practiceName = $doctors[$d]["TreatingDrName"];
                            $treatingDrNumber = $doctors[$d]["TreatingDrNumber"];
                            $claimedline_id = $doctors[$d]["ClaimLines"];
                            $doc_gap = (double)0;
                            $benefitDescription ="";
                            $providertypedesc = "";
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

                            $checkM=$conn->prepare('SELECT claim_id,practice_number FROM doctors WHERE claim_id=:claim_id AND practice_number=:practice_number LIMIT 1');
                            $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                            $checkM->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                            $checkM->execute();
                            $cc=$checkM->rowCount();

                            if($cc>0)
                            {
                                $chhh=true;
                            }

                            if(!$chhh) {
                                $insertDoctor = $conn->prepare('INSERT INTO doctors(claim_id,practice_number,claimedline_id,doc_gap,treating_drnumber) VALUES(:claim_id,:practice_number,:claimedline_id,:doc_gap,:treating_drnumber)');
                                $insertDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':claimedline_id', $claimedline_id, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':doc_gap', $doc_gap, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':treating_drnumber', $treatingDrNumber, PDO::PARAM_STR);
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
                                $insertDoctor = $conn->prepare('UPDATE doctors SET doc_gap=:doc_gap WHERE claim_id=:claim_id AND practice_number=:practice_number');
                                $insertDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':doc_gap', $doc_gap, PDO::PARAM_STR);
                                $cc2 = $insertDoctor->execute();
                            }

                            if($chhh)
                            {
                                $claimLine = $doctors[$d]["ClaimLines"];
                                $countLine=count($claimLine);


                                for($j=0;$j<$countLine;$j++) {
                                    $clmnlineNumber = (int)$claimLine[$j]["ClaimlineNumber"];

                                    $clmnlineChargedAmnt = (double)$claimLine[$j]["ClaimLineChargedAmount"];
                                    $clmlineSchemePaidAmnt = (double)$claimLine[$j]["ClaimLineSchemePaidAmount"];
                                    $gap_amount_line = (double)$claimLine[$j]["ClaimlineMemberportion"];
                                    $clmlineCalcAmnt=$clmnlineChargedAmnt-$clmlineSchemePaidAmnt;
                                    $treatment_code_description = $claimLine[$j]["CodeDescription"];
                                    //$primaryICDCode = $claimLine[$j]["ICDCode"];
                                    $primaryICDCode = $PrimaryICDCode;
                                    $primaryICDDescr = $claimLine[$j]["ICDDescription"];
                                    $tariffCode = $claimLine[$j]["Code"];
                                    $treatmentDate=$DateOfService;
                                    $eventDateTo=$claimLine[$j]["ClaimLineGapAmount"];
                                    $Codetype=$claimLine[$j]["CodeType"];
                                    $rejCode="";
                                    $reCodedescr="";
                                    /*
                                    $rrcodes=$claimLine[$j]["RejectionReasons"];
                                    for($q=0;$q<count($rrcodes);$q++)
                                    {
                                        $rejCode.=$rrcodes[$q]["RejCode"].";";
                                        $reCodetype.=$rrcodes[$q]["RejCodeType"].";";
                                        $reCodedescr.=$rrcodes[$q]["RejCodeDescription"].";";
                                    }
                                    */
                                    $ServiceEndDate=$claimLine[$j]["ServiceEndDate"];
                                    $ServiceStartDate=$claimLine[$j]["ServiceStartDate"];

//echo $clmnlineChargedAmnt."--".$gap_amount_line."--".$clmlineSchemePaidAmnt."--".$treatment_code_description."--".$primaryICDCode."--".$primaryICDDescr."--".$treatmentDate;
                                    $ic10Data=$this->checkPmb($primaryICDCode);
                                    $main_pmb=strlen($ic10Data["pmb_code"])>0?1:0;
                                    $PMBFlag =$main_pmb>0?"Y":"N";

                                    $selectClaimline = $conn->prepare('SELECT mca_claim_id FROM claim_line WHERE primaryICDCode=:icd AND tariff_code=:tariff_code AND treatmentDate=:treatmentDate AND treatmentType=:treatmentType AND mca_claim_id=:mca_claim_id AND practice_number=:practice_number');
                                    $selectClaimline->bindParam(':icd', $primaryICDCode, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':tariff_code', $tariffCode, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':treatmentDate', $treatmentDate, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':treatmentType', $Codetype, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':mca_claim_id', $claim_id, PDO::PARAM_STR);
                                    $selectClaimline->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);

                                    $selectClaimline->execute();
                                    $lcc=(int)$selectClaimline->rowCount();
                                    //echo $lcc."*******";
                                    $mess1="";
                                    if ($lcc<1) {
//echo $clmnlineChargedAmnt."--".$gap_amount_line."--".$clmlineSchemePaidAmnt."--".$treatment_code_description."--".$primaryICDCode."--".$primaryICDDescr."--".$treatmentDate."++".$practiceNo."--".$claim_id;

                                        try {
                                            $insertClaimline = $conn->prepare('INSERT INTO claim_line(mca_claim_id,practice_number,clmnline_charged_amnt,clmline_scheme_paid_amnt,gap,primaryICDCode,
primaryICDDescr,treatmentDate,eventDateTo,tariff_code,msg_dscr,PMBFlag,reason_code,msg_code,reason_description,service_date_from,service_date_to,lng_msg_dscr,treatmentType,jv_claim_line_number) VALUES(:mca_claim_id,:practice_number,:clmnline_charged_amnt,:clmline_scheme_paid_amnt,:gap,:primaryICDCode,:primaryICDDescr,:treatmentDate,:eventDateTo,:tariff_code,:msg_dscr,:PMBFlag,:reason_code,:msg_code,:reason_description,:service_date_from,:service_date_to,:lng_msg_dscr,:treatmentType,:jv_claim_line_number)');
                                            $insertClaimline->bindParam(':mca_claim_id', $claim_id, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':clmnline_charged_amnt', $clmnlineChargedAmnt, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':clmline_scheme_paid_amnt', $clmlineSchemePaidAmnt, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':gap', $gap_amount_line, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':primaryICDCode', $primaryICDCode, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':primaryICDDescr', $primaryICDDescr, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':treatmentDate', $treatmentDate, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':eventDateTo', $eventDateTo, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':tariff_code', $tariffCode, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':msg_dscr', $treatment_code_description, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':PMBFlag', $PMBFlag, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':reason_code', $mess1, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':msg_code', $rejCode, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':reason_description', $reCodedescr, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':service_date_from', $ServiceEndDate, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':service_date_to', $ServiceStartDate, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':lng_msg_dscr', $reCodedescr, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':treatmentType', $Codetype, PDO::PARAM_STR);
                                            $insertClaimline->bindParam(':jv_claim_line_number', $clmnlineNumber, PDO::PARAM_STR);

                                            $cc3 = $insertClaimline->execute();
                                            //echo $cc3;
                                            if ($cc3 == 1) {
                                                $xccomstatus = "success";
                                                $mess = "Successfully added";
                                                $this->mess3 = " with additional information";
                                                $this->updateClaim($claim_id);

                                            } else {
                                                $mess = "There is an error";

                                            }
                                        }
                                        catch(Exception $e)
                                        {
                                            $this->mess3="Failed : ".$e->getMessage();


                                        }
                                    }
                                    else{
                                        $this->updateCaimline($claim_id, $practiceNo, $treatmentDate, $primaryICDCode, $primaryICDDescr, $tariffCode, $clmnlineChargedAmnt, $clmlineSchemePaidAmnt, $clmlineCalcAmnt, $treatmentType);
                                        $mess = "Duplicate Claim Line";

                                    }

                                    $eachLine=array("lineNumber"=>$clmnlineNumber,"message"=>$mess);
                                    array_push($myArrayLine,$eachLine);
                                }

                            } else {
                                $this->mess2 = "The doctor not loaded";
                            }
                        }
                    } else {
                        $this->mess2 = "The claim not loaded";
                    }
                } else {
                    $this->mess2 = "The member not loaded";
                }

                //$stmnt=$conn->prepare('INSERT INTO member VALUES()');

            } catch (Exception $e) {
                $this->mess2 = $e->getMessage();
                //echo $this->mess2;
            }
            finally
            {

                $myarr=array("claim_number"=>$claim_number,"status"=>$xccomstatus,"descr"=>$this->mess2,"date_entered"=>$todaydate);
                array_push($resultArray,$myarr);
                echo json_encode($myarr,true);
            }

            $eacharray=array("claim_number"=>$claim_number,"message"=>$this->mess2,"claimline"=>$myArrayLine);
            array_push($myArray,$eacharray);
            //echo json_encode($myarr,true);
        }

        $rc=count($myArray);
        if($rc<1)
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $err=array("status"=>"500","message"=>"There is nothing processed");
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

        $this->allaudit((int)$rc,(int)$failed,(int)$succeed);
        $display_array=array("total_processed"=>$rc,"total_succeed"=>$succeed,"total_failed"=>$failed,"claims"=>$claim_array);
        //echo json_encode($resultArray,true);
        //print_r($display_array);
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
    function allaudit($total,$failed,$succeed,$status="")
    {
        try {
            global $conn;
            $stnt = $conn->prepare('INSERT INTO `jarvis_files`(`status`,`total`, `succeed`,`failed`) VALUES (:status,:total,:succeed,:failed)');
            $stnt->bindParam(':status', $status, PDO::PARAM_STR);
            $stnt->bindParam(':total', $total, PDO::PARAM_STR);
            $stnt->bindParam(':succeed', $succeed, PDO::PARAM_STR);
            $stnt->bindParam(':failed', $failed, PDO::PARAM_STR);
            $stnt->execute();
        }
        catch (Exception $r)
        {
            $this->mess2=$r->errorMessage();
        }
    }
    function checkScheme($medical_scheme)
    {

        if($medical_scheme=="ANGLO MEDICAL SCHEME (AMS)")
        {
            $medical_scheme="Anglo Medical Scheme";
        }
        elseif ($medical_scheme=="GOLDEN ARROWS EMPLOYEES MEDICAL BENEFIT FUND")
        {
            $medical_scheme="Golden Arrow Employees Medical Benefit Fund";
        }
        elseif ($medical_scheme=="DISCOVERY")
        {
            $medical_scheme="Discovery Health Medical Scheme";
        }
        elseif ($medical_scheme=="LIBERTY")
        {
            $medical_scheme="Liberty Medical Scheme";
        }
        elseif ($medical_scheme=="LIBERTY HEALTH MEDICAL SCHEME")
        {
            $medical_scheme="Liberty Medical Scheme";
        }
        elseif ($medical_scheme=="HEALTH SQUARED MEDICAL SCHEME")
        {
            $medical_scheme="Health Squared";
        }
        elseif ($medical_scheme=="OPTIMUM MEDICAL SCHEME (OPMED)")
        {
            $medical_scheme="Opmed";
        }
        elseif ($medical_scheme=="RESOLUTION HEALTH")
        {
            $medical_scheme="Resolution Health Medical Scheme";
        }
        elseif ($medical_scheme=="SOUTH AFRICAN BREWERIES MEDICAL SCHEME")
        {
            $medical_scheme="SA Breweries Medical Aid Scheme (SABMAS)";
        }
        elseif ($medical_scheme=="TFG (THE FOSCHINI GROUP) MEDICAL AID SCHEME")
        {
            $medical_scheme="TFG Medical Aid Scheme";
        }
        elseif ($medical_scheme=="UNIVERSITY OF KWA-ZULU NATAL MEDICAL SCHEME")
        {
            $medical_scheme="University of KwaZulu-Natal Medical Scheme";
        }
        elseif ($medical_scheme=="BONITAS")
        {
            $medical_scheme="Bonitas Medical Fund";
        }
        else{
            $medical_scheme="Unknown";
        }
        return $medical_scheme;
    }


    public function validate($username,$password)
    {
        global $conn1;
        $stmt=$conn1->prepare('select username,password from staff_users where username=:user1 AND role_value=2');
        $stmt->bindParam(':user1', $username, PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc==1)
        {
            $vv=$stmt->fetch();
            $pass=$vv[1];
            if(password_verify($password,$pass))
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

        return true;
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

