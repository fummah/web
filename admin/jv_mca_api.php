<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../admin/PHPMailer/src/Exception.php';
require '../admin/PHPMailer/src/PHPMailer.php';
require '../admin/PHPMailer/src/SMTP.php';

include ("../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$conn1=connection("doc","doctors");

class jv_import_export
{
    public $mess2;
    public $username;
    public $password;
    function sendMail($tomail,$subject,$body){
        $mail = new PHPMailer(true);
        // Passing `true` enables exceptions
        try {


            //Server settings
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'new_case@medclaimassist.co.za';                 // SMTP username
            $mail->Password = 'N3w_c@s310';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('new_case@medclaimassist.co.za', 'New Case MCA');
            $mail->addAddress($tomail, 'System User');     // Add a recipient

            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            //$mail->AddAttachment('documents/' . getConsentName($scheme));
            //$mail->send();

            if (!$mail->send()) {
                $this->mess2="Email Error";
            } else {
            }
        }

        catch (Exception $e)
        {

        }
    }
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
    function checkClaim($claim_number)
    {
        global $conn;
        $claim_id="";
        $checkM=$conn->prepare('SELECT claim_id FROM claim WHERE claim_number=:claim_number LIMIT 1');
        $checkM->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
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
        $checkM=$conn->prepare('SELECT claim_id FROM claim WHERE claim_number=:claim_number LIMIT 1');
        $checkM->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $checkM->execute();
        $cc=$checkM->rowCount();
        if($cc>0)
        {

            $try=true;
        }
        return $try;
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
    function insertMember($policyNumber,$productName,$personNumber,$personName,$personSurname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id)
    {
        global $conn;
        $insertMember = $conn->prepare('INSERT INTO member(policy_number,productName,id_number,first_name,surname,medical_scheme,scheme_option,medicalSchemeRate,scheme_number,client_id) VALUES
(:policy_number,:productName,:id_number,:first_name,:surname,:medical_scheme,:scheme_option,:medicalSchemeRate,:scheme_number,:client_id)');
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
        $cc = $insertMember->execute();
        return $cc;
    }
    function insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy1,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number="")
    {
        global  $conn;
        $insertClaim = $conn->prepare('INSERT INTO claim(claim_number,member_id,entered_by,jv_status,Service_Date,end_date,charged_amnt,scheme_paid,gap,username,recordType,senderId,memberLiability,creationDate,
createdBy,patient_number,client_gap,pmb,icd10,icd10_desc,claim_number1) VALUES(:claim_number,:member_id,:entered_by,:jv_status,:Service_Date,:end_date,:charged_amnt,:scheme_paid,:gap,:username,:recordType,:senderId,:memberLiability,:creationDate,:createdBy,:patient_number,:client_gap,:pmb,:icd10,:icd10_desc,:claim_number1)');
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
        $cc1 = $insertClaim->execute();
        return $cc1;
    }
    function readFile()
    {

        global $conn;
        $myArray=array();

        // $file = file_get_contents('mytext_received.txt', true);
        $file = file_get_contents('php://input', true);
        //$data = json_decode(file_get_contents('php://input'), true);
        //echo $file;
        //$dataList = substr($f, 1, -1);
        $this->addObj($file);
        $t=json_decode($file,true);

        if($t === null) {
            die("JSON cannot be decoded");
        }
        $r=$t;
        $array_count=1;

        for($i=0;$i<$array_count;$i++) {
            $myArrayLine=array();
            $mess = "";
            $this->mess2 = "";
            $cccv="";
            $eventNumber = (int)$r["eventNumber"];
            try {
                //member
                $this->username = $r["username"];
                $this->password = $r["password"];
                if(!$this->validate($this->username,$this->password))
                {

                    die("Invalid Request");
                }
                $policyNumber = $r["policyNumber"];
                $productName = $r["productName"];
                $personNumber = "";
                $personName = $r["personName"];
                $personSurname = $r["personSurname"];
                $medicalSchemeName = $r["medicalSchemeName"];
                $medicalSchemeName=$this->checkScheme($medicalSchemeName);
                $medicalSchemeName=strtolower($medicalSchemeName);
                $medicalSchemeName=ucwords($medicalSchemeName);
                $medicalSchemeOption = $r["medicalSchemeOption"];
                $medicalSchemeRate = $r["medicalSchemeRate"];
                $medicalSchemeNumber = $r["medicalSchemeNumber"];
                $claimLine = $r["claimLine"];
                $claimChargedAmnt = (double)$r["claimChargedAmnt"];
                $schemePaidAmnt = (double)$r["schemePaidAmnt"];
                //$claimCalcAmnt = (double)$r["claimCalcAmount"];
                $claimCalcAmnt=$claimChargedAmnt-$schemePaidAmnt;
                $claimLine = $r["claimLine"];
                $countLinex=count($claimLine);
                $c_amount=0;
                $s_amount=0;
                $recordType = $r["recordType"];
                $senderId = $r["senderId"];

                $creationDate = $r["creationDate"];
                $createdBy = $r["createdBy"];
                $eventStatus = $r["eventStatus"];
                $eventDateFrom = $r["eventDateFrom"];
                $eventDateTo = $r["eventDateTo"];
                $memberLiability=$r["memberLiability"];
                $patient_number=$r["personNumber"];
                $switchReference=$r["switchReference"];
                $client_claim_number =$r["clientClaimNumber"];
                $details=$this->getUsername();
                // $username=$details['username'];
             $username=$this->xuser($createdBy);
                $email=$details['email'];
                $client_gap =$r["clientGapAmount"];
                $createdBy = "System";
                $claim_number="JV_".$eventNumber;

                $main_pmb1=$r["claimLine"][0]["pmbFlag"];
                $main_pmb=0;
                if($main_pmb1=="Y")
                {
                    $main_pmb=1;
                }
                $main_icd10=$r["claimLine"][0]["primaryICDCode"];
                $main_icd10_desc=$r["claimLine"][0]["primaryICDDescr"];
                for($k=0;$k<$countLinex;$k++)
                {
                    $clmnlineChargedAmnt = (double)$claimLine[$k]["clmnlineChargedAmnt"];
                    $clmlineSchemePaidAmnt = (double)$claimLine[$k]["clmlineSchemePaidAmnt"];
                    $c_amount+=$clmnlineChargedAmnt;
                    $s_amount+=$clmlineSchemePaidAmnt;

                }
                $continue=true;
                if($claimChargedAmnt!=$c_amount)
                {
                    $this->mess2=("Incorrect total amount");
                    $continue=true;
                }
                if($schemePaidAmnt!=$s_amount)
                {
                    $continue=true;
                    $this->mess2=("Incorrect total amount");
                }

                $client_id = 7;
                $sub_pol=substr($policyNumber,0,4);
                $xx="";
                if($sub_pol=="AMBL" || $sub_pol=="TKGD" || $sub_pol=="GAPT")
                {
                    $client_id = 2;
                }
                if(!$this->checkClaim1($client_claim_number) && !empty($client_claim_number))
                {
                    $member_idx=$this->checkMember($policyNumber);

                    if(empty($member_idx))
                    {

                        $kk=$this->insertMember($policyNumber,$productName,$personNumber,$personName,$personSurname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id);
                        $selectlastmember = $conn->prepare("SELECT max(member_id) FROM member WHERE policy_number=:policy_number");
                        $selectlastmember->bindParam(':policy_number', $policyNumber, PDO::PARAM_STR);
                        $selectlastmember->execute();
                        $member_idx = $selectlastmember->fetchColumn();
                    }
$ty="";
$ty1="";
$xx=$this->insertClaim($client_claim_number,$member_idx,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$ty,$ty1,$memberLiability,$createdBy,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc);

                }
                $member_id=$this->checkMember($policyNumber);

                if(empty($member_id) && $continue) {


                    $cc=$this->insertMember($policyNumber,$productName,$personNumber,$personName,$personSurname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id);

                    if ($cc == 1) {
                        $this->mess2="Member Successfully added";
                        $selectlastmember = $conn->prepare("SELECT max(member_id) FROM member WHERE policy_number=:policy_number");
                        $selectlastmember->bindParam(':policy_number', $policyNumber, PDO::PARAM_STR);
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

                    $claim_id=$this->checkClaim($claim_number);

                    if(empty($claim_id)) {
                       $cc1=$this->insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number);
                        if ($cc1 == 1) {
                            $this->mess2="Claim Successfully added";
                            // $this->updateUsername($username);

                            $selectlastclaim = $conn->prepare("SELECT max(claim_id) FROM claim WHERE claim_number=:claim_number");
                            $selectlastclaim->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
                            $selectlastclaim->execute();
                            $claim_id = $selectlastclaim->fetchColumn();
                            $this->InsertPatient($claim_id,$personName,$personSurname);
                        }
                        else {
                            $this->mess2="Claim Failed to Load";
                            $claim_id="";
                        }
                    }
                    $claim_id=(int)$claim_id;
                    if($claim_id>0)
                        //Doctors Information
                    {
                        $chhh=false;
                        $practiceNo = $r["practiceNo"];
                        $practiceName = $r["practiceName"];
                        $providertypedesc = $r["providertypedesc"];
                        //echo $practiceNo;
                        if (!$this->checkDoctor($practiceNo)) {
                            global $conn1;
 $pracno_1=str_pad($practiceNo, 7, '0', STR_PAD_LEFT);
                            $insertDoctor1 = $conn1->prepare('INSERT INTO person(firstname,practiceno,service) VALUES(:firstname,:practiceno,:service)');
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
                            $insertDoctor = $conn->prepare('INSERT INTO doctors(claim_id,practice_number) VALUES(:claim_id,:practice_number)');
                            $insertDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                            $insertDoctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
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
                        if($chhh)
                        {
                            $claimLine = $r["claimLine"];

                            $countLine=count($claimLine);

                            for($j=0;$j<$countLine;$j++) {
                                $clmnlineNumber = (int)$claimLine[$j]["clmnlineNumber"];
                                $clmnlineChargedAmnt = (double)$claimLine[$j]["clmnlineChargedAmnt"];
                                $clmlineSchemePaidAmnt = (double)$claimLine[$j]["clmlineSchemePaidAmnt"];
                                //$clmlineCalcAmnt = (double)$claimLine[$j]["clmlineCalcAmnt"];
                                $clmlineCalcAmnt=$clmnlineChargedAmnt-$clmlineSchemePaidAmnt;
                                $memberLiability = $claimLine[$j]["memberLiability"];
                                $benefitDescription =$claimLine[$j]["benefitDescription"];
                                $treatmentType = $claimLine[$j]["treatmentType"];
                                $treatmentDate = $claimLine[$j]["treatmentDate"];
                                $secondaryICDCode = $claimLine[$j]["secondaryICDCode"];
                                $secondaryICDDescr = $claimLine[$j]["secondaryICDDescr"];
                                $primaryICDCode = $claimLine[$j]["primaryICDCode"];
                                $primaryICDDescr = $claimLine[$j]["primaryICDDescr"];
                                $tariffCode = $claimLine[$j]["tariffCode"];
                                $modifier = $claimLine[$j]["modifier"];
                                $modifier =implode(",",$modifier);
                                $unit = $claimLine[$j]["unit"];
                                $PMBFlag = $claimLine[$j]["pmbFlag"];
                                $clmnLinePmntStatus = $claimLine[$j]["clmnLinePmntStatus"];
                                $creationDate = $claimLine[$j]["creationDate"];
                                $createdBy = $claimLine[$j]["createdBy"];
                                $rej_code = $claimLine[$j]["jarvismsgCode"];
                                $short_msg = $claimLine[$j]["jarvisshrtMsgDscr"];
                                $lon_msg = $claimLine[$j]["jarvislngMsgDscr"];
                                $cptCode = $claimLine[$j]["cptCode"];
                                $nappiCode = $claimLine[$j]["nappiCode"];
                                $quantity = $claimLine[$j]["quantity"];
                                $clmnLinePmntStatusDate = $claimLine[$j]["clmnLinePmntStatusDate"];
                                $treatmentCodeType = $claimLine[$j]["treatmentCodeType"];
                                $cptDescr = $claimLine[$j]["cptDescr"];
                                $lastUpdateDate = $claimLine[$j]["lastUpdateDate"];
                                $toothNo = $claimLine[$j]["toothNo"];

                                $ch=$conn->prepare('SELECT jv_claim_line_number FROM claim_line WHERE jv_claim_line_number=:jv_claim_line_number');
                                $ch->bindParam(':jv_claim_line_number', $clmnlineNumber, PDO::PARAM_STR);
                                $ch->execute();
                                $lcc=$ch->rowCount();
                                $mess1="";
                                if ($lcc<1) {

                                    $insertClaimline = $conn->prepare('INSERT INTO claim_line(recordType,senderId,jv_claim_line_number,mca_claim_id,practice_number,clmnline_charged_amnt,
clmline_scheme_paid_amnt,gap,memberLiability,benefit_description,treatmentDate,primaryICDCode,primaryICDDescr,tariff_code,modifier,unit,PMBFlag,clmn_line_pmnt_status,creationDate,createdBy,msg_code,
msg_dscr,lng_msg_dscr,treatmentType,secondaryICDCode,secondaryICDDescr,cptCode,nappiCode,quantity,clmn_line_status_date,treatment_code_type,cptDescr,lastUpdateDate,toothNo,switch_reference
) VALUES(:recordType,:senderId,:jv_claim_line_number,:mca_claim_id,:practice_number,:clmnline_charged_amnt,:clmline_scheme_paid_amnt,:gap,:memberLiability,:benefit_description,
:treatmentDate,:primaryICDCode,:primaryICDDescr,:tariff_code,:modifier,:unit,:PMBFlag,:clmn_line_pmnt_status,:creationDate,:createdBy,:msg_code,:msg_dscr,:lng_msg_dscr,:treatmentType,:secondaryICDCode,:secondaryICDDescr,:cptCode,:nappiCode,:quantity,:clmn_line_status_date,:treatment_code_type,:cptDescr,:lastUpdateDate,:toothNo,:switch_reference)');
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

                                    $cc3 = $insertClaimline->execute();
                                    if ($cc3 == 1) {
                                        $mess = "Successfully added";
                                    } else {
                                        $mess = "There is an error";
                                    }
                                }
                                else{
                                    $mess = "Duplicate Claim Line";

                                }
                                $eachLine=array("lineNumber"=>$clmnlineNumber,"message"=>$mess);
                                array_push($myArrayLine,$eachLine);
                            }

                        } else {
                            $this->mess2 = "The doctor not loaded";
                        }
                    } else {
                        $this->mess2 = "The claim not loaded";
                    }
                } else {
                    $this->mess2 = "The member not loaded";
                }

                //$stmnt=$conn->prepare('INSERT INTO member VALUES()');

            } catch (Exception $e) {
                $this->mess2 = $e->errorMessage();
            }

            $eacharray=array("claim_number"=>$eventNumber,"message"=>$this->mess2,"claimline"=>$myArrayLine);
            array_push($myArray,$eacharray);
        }

        $rc=count($myArray);
        if($rc<1)
        {
            die("There is nothing processed");
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

        print_r($display_array);
    }
    function checkDoctor($number)
    {

        global $conn1;
        $check=false;
        try {
            $stmt = $conn1->prepare('SELECT practiceno FROM person WHERE practiceno=:num');
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
        return $medical_scheme;
    }
    public function validate($username,$password)
    {
        global $conn1;

        $stmt=$conn1->prepare('select username,password from staff_users where username=:user1 AND role_value=1');
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
    if($n->validate($n->username,$n->password))
    {

    }
    else
    {
        echo "Invalid Request";


    }
}
else{
    echo "Request Error";
}
?>

                                                                                                