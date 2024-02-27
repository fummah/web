<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../admin/PHPMailer/src/Exception.php';
require '../admin/PHPMailer/src/PHPMailer.php';
require '../admin/PHPMailer/src/SMTP.php';

include ("../../mca/link2.php");
$conn=connection("mca","testing");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");
$conn3=connection("seamless","seamless");

class jv_import_export
{
    public $mess2;
    public $username;
    public $password;
    public $SRef1;
    public $SRef2;
    public $conn;
    public $conn1;
    public $conn2;
    public $conn3;
    public function __construct()
    {
        global $conn;
        global $conn1;
        global $conn2;
        global $conn3;
        $this->conn = $conn;
        $this->conn1 = $conn1;
        $this->conn2 = $conn2;
        $this->conn3 = $conn3;
        $this->SRef1='<SRef Type="S" Num="Unknown"/>';
        $this->SRef2='<SRef Type="F" Num="Unknown"/>';
    }


    function checkMember($conn,$policy_number)
    {
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
    function checkClaim($conn,$claim_number)
    {

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

    function getUsername($conn)
    {
        $stmt=$conn->prepare('SELECT username,email FROM users_information WHERE status=1 ORDER BY datetime ASC LIMIT 1');
        $stmt->execute();
        $row=$stmt->fetch();
        $details['username']=$row['0'];
        $details['email']=$row['1'];

        return $details;
    }
    function updateUsername($conn,$username)
    {
        $date=date('Y-m-d H:i:s');
        $stmt=$conn->prepare('UPDATE users_information SET datetime=:dat WHERE username=:username');
        $stmt->bindParam(':dat', $date, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

    }
    function InsertPatient($conn,$claim_id,$first_name,$surname)
    {
        $patient_name=$first_name." ".$surname;
        $stmt=$conn->prepare('INSERT INTO patient (claim_id, patient_name) VALUES(:claim_id,:patient_name)');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
        $stmt->execute();


    }
    function insertMember($conn,$policyNumber,$productName,$personNumber,$personName,$personSurname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id)
    {
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
    function insertClaim($conn,$claim_number,$member_id,$createdBy1,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number="",$claim_type="")
    {
        $op1=2;
        $insertClaim = $conn->prepare('INSERT INTO claim(claim_number,member_id,entered_by,jv_status,Service_Date,end_date,charged_amnt,scheme_paid,gap,username,recordType,senderId,memberLiability,creationDate,
createdBy,patient_number,client_gap,pmb,icd10,icd10_desc,claim_number1,Open,claim_type) VALUES(:claim_number,:member_id,:entered_by,:jv_status,:Service_Date,:end_date,:charged_amnt,:scheme_paid,:gap,:username,:recordType,:senderId,:memberLiability,:creationDate,:createdBy,:patient_number,:client_gap,:pmb,:icd10,:icd10_desc,:claim_number1,:op1,:claim_type)');
        $insertClaim->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $insertClaim->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $insertClaim->bindParam(':entered_by', $createdBy1, PDO::PARAM_STR);
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
        $insertClaim->bindParam(':op1', $op1, PDO::PARAM_STR);
        $insertClaim->bindParam(':claim_type', $claim_type, PDO::PARAM_STR);
        $cc1 = $insertClaim->execute();
        return $cc1;
    }
    function updateClaim($conn,$claim_id,$start_date,$end_date)
    {
        $stmt=$conn->prepare('UPDATE claim SET Service_Date=:start,end_date=:end1 WHERE claim_id=:id');
        $stmt->bindParam(':id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start_date, PDO::PARAM_STR);
        $stmt->bindParam(':end1', $end_date, PDO::PARAM_STR);
        $vv=$stmt->execute();
        return $vv;
    }
    function updateInvoice($conn,$claim_id,$practice_number,$provider_invoicenumber)
    {
        $stmt=$conn->prepare('UPDATE doctors SET provider_invoicenumber=:provider_invoicenumber WHERE claim_id=:claim_id AND practice_number=:practice_number');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':provider_invoicenumber', $provider_invoicenumber, PDO::PARAM_STR);
        $vv=$stmt->execute();
        return $vv;
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
    function checking($str)
    {
        $mystr=is_array($str)?'':$str;
        return $mystr;
    }
    function readFile()
    {

        $AuthKey="BUWBCRMv25DVFAzuaieXUtTFV2qDhhXi";
        $myArray=array();
        $array_response=array();
        $file = file_get_contents('php://input', true);
        $nn=(int)strpos($file,$AuthKey);

        if($nn<2)
        {

            $array_response["SRef"]=[];
            $array_response["Stat"]="R";
            $array_response["Error"] = ["Code" => 401, "Desc" => "Unauthorized"];
            header($_SERVER['SERVER_PROTOCOL'] . ' Unauthorized', true, 401);
            $this->prnt($array_response);
        }

        $contents = '<Auth_Key>BUWBCRMv25DVFAzuaieXUtTFV2qDhhXi</Auth_Key>';
        $file=str_replace($contents,'',$file);

        $ob= simplexml_load_string($file);
        $json  = json_encode($ob);

        $t=json_decode($json,true);

        if($t === null) {
            $array_response["SRef"]=[];
            $array_response["Stat"]="R";
            $array_response["Error"] = ["Code" => 400, "Desc" => "Bad Request"];
            header($_SERVER['SERVER_PROTOCOL'] . ' Bad Request', true, 400);
            $this->prnt($array_response);
        }

        $claim_number="";
        $r=$t;
        $array_count=1;

        for($i=0;$i<$array_count;$i++) {
            $myArrayLine=array();

            $mess = "";
            $this->mess2 = "";
            $cccv="";
            try {
                $Autstr="<Hdr Type=\"R\">";
                $rstr=(int)strpos($file,$Autstr);
                $typestr=$rstr>0?"R":"C";
                $productName =$this->checking(isset($t['Hdr']['ProductName'])?$t['Hdr']['ProductName']:'');
                $client= $this->checking(isset($t['Hdr']['GAPInsurer'])?$t['Hdr']['GAPInsurer']:'Individual');
                $pos10 = strpos($productName, "Kaelo");
                $pos11 = strpos($productName, "Sanlam");
                $pos12 = strpos($productName, "Western");
                if($pos10>-1) {
                    $client="Kaelo";
                    $this->conn=$this->conn3;
                }
                elseif ($pos11>-1)
                {
                    $client="Sanlam";
                    $this->conn=$this->conn3;
                }
                elseif ($pos12>-1)
                {
                    $client="Western";
                    $this->conn=$this->conn3;
                }
                $this->addObj($this->conn,$file);
                $policyNumber = $this->checking(isset($t['Hdr']['PolicyNumber'])?$t['Hdr']['PolicyNumber']:'');

                $personNumber = $this->checking(isset($t['Cont']['Claim']['Mem']['NID'])?$t['Cont']['Claim']['Mem']['NID']:'');
                $personName = $this->checking(isset($t['Cont']['Claim']['Mem']['Name'])?$t['Cont']['Claim']['Mem']['Name']:'');
                $personSurname = $this->checking(isset($t['Cont']['Claim']['Mem']['Surn'])?$t['Cont']['Claim']['Mem']['Surn']:'');
                $medicalSchemeName = $this->checking(isset($t['Cont']['Claim']['Mem']['MAid']['SchemeName'])?$t['Cont']['Claim']['Mem']['MAid']['SchemeName']:'');

                $medicalSchemeName=$this->checkScheme($this->conn,$medicalSchemeName);
                //$medicalSchemeName=strtolower($medicalSchemeName);
                //$medicalSchemeName=ucwords($medicalSchemeName);

                $medicalSchemeOption = "";
                $medicalSchemeRate = "";
                $medicalSchemeNumber = $this->checking(isset($t['Cont']['Claim']['Mem']['MAid']['MNum'])?$t['Cont']['Claim']['Mem']['MAid']['MNum']:'');
                $claimChargedAmnt = (double)$this->checking(isset($t['Cont']['Claim']['CFin']['Claim'])?$t['Cont']['Claim']['CFin']['Claim']:'');
                $schemePaidAmnt = (double)$this->checking(isset($t['Cont']['Claim']['CFin']['Paid'])?$t['Cont']['Claim']['CFin']['Paid']:'');
                $claimChargedAmnt=$claimChargedAmnt/100;
                $schemePaidAmnt=$schemePaidAmnt/100;
                //$claimCalcAmnt = (double)$r["claimCalcAmount"];
                $claimCalcAmnt=$claimChargedAmnt-$schemePaidAmnt;

                $c_amount=0;
                $s_amount=0;
                $recordType ="";
                $senderId = 10;
                $claim_number=$this->checking(isset($t['Cont']['Claim']['SRef'])?$t['Cont']['Claim']['SRef']:'');
                $array_response["SRef"]=[];
                $this->SRef1='<SRef Type="S" Num="'.$claim_number.'"/>';

                $pnam=$this->checking(isset($t['Cont']['Claim']['Pat']['Name'])?$t['Cont']['Claim']['Pat']['Name']:'');
                $psurname=$this->checking(isset($t['Cont']['Claim']['Pat']['Surn'])?$t['Cont']['Claim']['Pat']['Surn']:'');
                $patient_name=$pnam." ".$psurname;
                $beneficiary_number=$this->checking(isset($t['Cont']['Claim']['Pat']['Dep'])?$t['Cont']['Claim']['Pat']['Dep']:'');
                $creationDate=$this->checking(isset($t['Hdr']['Time'])?$t['Hdr']['Time']:'');


                $createdBy = "System";
                $eventStatus = "";
                $eventDateFrom = $this->checking(isset($t['Hdr']['Time'])?$t['Hdr']['Time']:'');
                $eventDateTo = "";
                $memberLiability=(double)0;
                $patient_number="";
                $switchReference="";
                $client_claim_number ="";
                $username="Faghry";
                $client_gap =(double)0;
                $createdBy = "System";
                $main_pmb=0;
                $main_icd10="";
                $main_icd10_desc="";
                $secicd10="";


                $client_id = $this->checkClient($this->conn,$client);
                $sub_pol=substr($policyNumber,0,4);
                $xx="";
                $continue=true;


                if (isset($t['Cont']['Claim']['Diags']['Diag']["Code"]))
                {
                    $main_icd10=$this->checking($t['Cont']['Claim']['Diags']['Diag']["Code"]);
                }
                else {
                    $arr_icd10 = $t['Cont']['Claim']['Diags']['Diag'];
                    for ($i = 0; $i < count($arr_icd10); $i++) {
                        if ($i == 0) {
                            $main_icd10 = $this->checking($arr_icd10[$i]['Code']);
                        } elseif ($i == 1) {
                            $secicd10 = $this->checking($arr_icd10[$i]['Code']);
                        }
                    }
                }

                $ic10Data=$this->checkPmb($main_icd10);
                $main_pmb=strlen($ic10Data["pmb_code"])>0?1:0;
                $main_icd10_desc=$ic10Data["shortdesc"];
                $member_id=$this->checkMember($this->conn,$policyNumber);

                if(empty($member_id) && $continue) {


                    $cc=$this->insertMember($this->conn,$policyNumber,$productName,$personNumber,$personName,$personSurname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id);

                    if ($cc == 1) {
                        $this->mess2="Member Successfully added";
                        $selectlastmember = $this->conn->prepare("SELECT max(member_id) FROM member WHERE policy_number=:policy_number");
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

                    $claim_id=$this->checkClaim($this->conn,$claim_number);
                    if(empty($claim_id)) {
                        $cc1=$this->insertClaim($this->conn,$claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number,$typestr);
                        if ($cc1 == 1) {
                            $this->mess2="Claim Successfully added";
                            // $this->updateUsername($username);
                            $array_response["Stat"]="U";
                            $selectlastclaim = $this->conn->prepare("SELECT max(claim_id) FROM claim WHERE claim_number=:claim_number");
                            $selectlastclaim->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
                            $selectlastclaim->execute();
                            $claim_id = $selectlastclaim->fetchColumn();
                            $yu=["Type"=>"F","Num"=> $claim_id];
                            $this->InsertPatient($this->conn,$claim_id,$patient_name,"");
                        }
                        else {
                            $claim_id="";
                            $this->mess2="Claim Failed to Load";
                            $yu=["Type"=>"F","Num"=> $claim_id];
                            $array_response["Stat"]="R";
                            $array_response["Error"] = ["Code" => 200, "Desc" => $this->mess2];

                        }


                    }
                    //array_push($array_response["SRef"],$yu);
                    $claim_id=(int)$claim_id;
                    $this->SRef2='<SRef Type="F" Num="'.$claim_id.'"/>';
                    if($claim_id>0)
                        //Doctors Information
                    {
                        $chhh = false;
                        $arr_prac=array();
                        $arr_doc = isset($t['Cont']['Claim']['Docs']['Doc'][0]['PCNS']) ? $t['Cont']['Claim']['Docs']['Doc'] : array($t['Cont']['Claim']['Docs']['Doc']);
                        for ($i = 0; $i < count($arr_doc); $i++) {
                            $chhh = false;
                            $practiceNo = $this->checking(isset($arr_doc[$i]['PCNS'])?$arr_doc[$i]['PCNS']:'');
                            $practiceName = $this->checking(isset($arr_doc[$i]['Name'])?$arr_doc[$i]['Name']:'');
                            $practiceID = $this->checking(isset($arr_doc[$i]['ID'])?$arr_doc[$i]['ID']:'');
                            $practiceNo =str_pad( $practiceNo, 7, '0', STR_PAD_LEFT);
                            $arr_prac1=array("id"=>$practiceID,"practice_number"=>$practiceNo);
                            array_push($arr_prac,$arr_prac1);
                            $providertypedesc = "";

                            if (!$this->checkDoctor($this->conn,$practiceNo)) {

                                $insertDoctor1 = $this->conn->prepare('INSERT INTO doctor_details(name_initials,practice_number,discipline) VALUES(:firstname,:practiceno,:service)');
                                $insertDoctor1->bindParam(':firstname', $practiceName, PDO::PARAM_STR);
                                $insertDoctor1->bindParam(':practiceno', $practiceNo, PDO::PARAM_STR);
                                $insertDoctor1->bindParam(':service', $providertypedesc, PDO::PARAM_STR);
                                $kk = $insertDoctor1->execute();
                                if ($kk == 1) {
                                    $this->mess2 = "Claim Successfully added";
                                } else {
                                    $chhh = false;
                                    $this->mess2 = "Claim Successfully added but doctor failed to load";
                                }
                            }


                            $checkM = $this->conn->prepare('SELECT claim_id,practice_number FROM doctors WHERE claim_id=:claim_id AND practice_number=:practice_number LIMIT 1');
                            $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                            $checkM->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                            $checkM->execute();
                            $cc = $checkM->rowCount();

                            if ($cc > 0) {
                                $chhh = true;
                            }

                            if (!$chhh) {
                                $insertDoctor = $this->conn->prepare('INSERT INTO doctors(claim_id,practice_number) VALUES(:claim_id,:practice_number)');
                                $insertDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                                $insertDoctor->bindParam(':practice_number', $practiceNo, PDO::PARAM_STR);
                                $cc2 = $insertDoctor->execute();
                                if ($cc2 == 1) {
                                    $this->mess2 = "Claim Successfully added";
                                    $chhh = true;
                                } else {
                                    $chhh = false;
                                    $this->mess2 = "Claim Successfully added but doctor failed to load";
                                }
                            }
                            /////
                            ///
                            ///
                        }
                        $unloadeddoc=$this->checking(isset($t['Cont']['Claim']['Cdoc']['ID'])?$t['Cont']['Claim']['Cdoc']['ID']:0);
                        $unloadeddocRole=$this->checking(isset($t['Cont']['Claim']['Cdoc']['Role'])?$t['Cont']['Claim']['Cdoc']['Role']:0);
                        $keyx= (int)array_search($unloadeddoc, array_column($arr_prac, 'id'));
                        $practicex= $arr_prac[$keyx]['practice_number'];
                        if(isset($practicex) && ($unloadeddocRole==5 || $unloadeddocRole==6))
                        {
                            $dis="XXX";
                            $updateDoctor = $this->conn->prepare('UPDATE doctors SET display=:dis WHERE claim_id=:claim_id AND practice_number=:practice_number');
                            $updateDoctor->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                            $updateDoctor->bindParam(':practice_number', $practicex, PDO::PARAM_STR);
                            $updateDoctor->bindParam(':dis', $dis, PDO::PARAM_STR);
                            $updateDoctor->execute();

                        }
                        if($chhh)
                        {

                            $arr_claimline=isset($t['Cont']['Claim']['Line'][0]['LFin']['Claim'])?$t['Cont']['Claim']['Line']:array($t['Cont']['Claim']['Line']);
                            $service_date_from=isset($arr_claimline[0]['SDat'])?$arr_claimline[0]['SDat']:'';
                            $service_date_to = isset($arr_claimline[0]['EDat']) ? $arr_claimline[0]['EDat'] : '';
                            //

                            if(!empty($service_date_from))
                            {
                                $date=date_create($service_date_from);
                                $service_date_from= date_format($date,"Y-m-d");
                            }

                            if(!empty($service_date_to))
                            {
                                $date=date_create($service_date_to);
                                $service_date_to=date_format($date,"Y-m-d");
                            }
                            $service_date_to=$this->validateDate($service_date_to)?$service_date_to:"";
                            $ii=$this->updateClaim($this->conn,$claim_id,$service_date_from,$service_date_to);
                            for($i=0;$i<count($arr_claimline);$i++)
                            {

                                $clmnlineNumber=isset($arr_claimline[$i]['SRef'])?$arr_claimline[$i]['SRef']:'';
                                $provider_invoicenumber=isset($arr_claimline[$i]['Invc'])?$arr_claimline[$i]['Invc']:'--';
                                //['Cont']['Claim']['Line']['SRef']
                                $clmnlineChargedAmnt = (double)$this->checking(isset($arr_claimline[$i]['LFin']['Claim'])?$arr_claimline[$i]['LFin']['Claim']:'');
                                $clmlineSchemePaidAmnt = (double)$this->checking(isset($arr_claimline[$i]['LFin']['Paid'])?$arr_claimline[$i]['LFin']['Paid']:'');
                                $clmnlineChargedAmnt=$clmnlineChargedAmnt/100;
                                $clmlineSchemePaidAmnt=$clmlineSchemePaidAmnt/100;
                                $clmlineCalcAmnt=$clmnlineChargedAmnt-$clmlineSchemePaidAmnt;
                                $memberLiability = (double)0;
                                $benefitDescription ="";
                                $treatmentType = "";
                                $treatmentDate = $service_date_from;

                                $primaryICDCode = "";
                                $primaryICDDescr = "";
                                $modifier="";
                                $tariffCode = $this->checking(isset($arr_claimline[$i]['Trff']['Code'])?$arr_claimline[$i]['Trff']['Code']:'');
                                $type = $this->checking(isset($arr_claimline[$i]['Trff']['Type'])?$arr_claimline[$i]['Trff']['Type']:'');
                                if($type=="M")
                                {
                                    $modifier=$tariffCode;
                                    $tariffCode="";
                                }

                                if(empty($tariffCode))
                                {
                                    $arr_tarrif=isset($arr_claimline[$i]['Trff'][0]['Code'])?$arr_claimline[$i]['Trff']:array($arr_claimline[$i]['Trff']);
                                    for($k=0;$k<count($arr_tarrif);$k++)
                                    {
                                        $type=$arr_claimline[$i]['Trff'][$k]['Type'];
                                        if($type=="M")
                                        {
                                            $modifier=$arr_claimline[$i]['Trff'][$k]['Code'];
                                        }
                                        if($type=="T")
                                        {
                                            $tariffCode=$arr_claimline[$i]['Trff'][$k]['Code'];
                                        }


                                    }
                                }
                                $unit = "";
                                $PMBFlag = "";
                                $clmnLinePmntStatus = "";
                                $creationDate = "";
                                $createdBy = "System";
                                $rej_code = "";
                                $short_msg = $this->checking(isset($arr_claimline[$i]['Trff']['Desc'])?$arr_claimline[$i]['Trff']['Desc']:'');
                                $lon_msg ="";
                                $cptCode = "";
                                $nappiCode = "";
                                $quantity = $this->checking(isset($arr_claimline[$i]['Qnty'])?$arr_claimline[$i]['Qnty']:'');
                                $clmnLinePmntStatusDate = "";
                                $treatmentCodeType = "";
                                $cptDescr ="";
                                $lastUpdateDate = "";
                                $toothNo = "";
                                $mypracID=$this->checking(isset($arr_claimline[$i]['Ldoc']['ID'])?$arr_claimline[$i]['Ldoc']['ID']:'');
                                $mypracRole=$this->checking(isset($arr_claimline[$i]['Ldoc']['Role'])?$arr_claimline[$i]['Ldoc']['Role']:1);
                                $roleArray=[1,2,3,4];
                                if(empty($mypracID))
                                {
                                    $arr_ldoc=isset($arr_claimline[$i]['Ldoc'][0]['ID'])?$arr_claimline[$i]['Ldoc']:array($arr_claimline[$i]['Ldoc']);
                                    for($k=0;$k<count($arr_ldoc);$k++)
                                    {
                                        $temprole=(int)$arr_claimline[$i]['Ldoc'][$k]['Role'];
                                        if($temprole<4)
                                        {
                                            $mypracID=$arr_claimline[$i]['Ldoc'][$k]['ID'];
                                            $mypracRole=$temprole;
                                            break;

                                        }
                                    }

                                }
                                if(!in_array($mypracRole,$roleArray))
                                {
                                    $mypracRole=0;
                                }
                                // print_r($arr_prac);

                                $key= array_search($mypracID, array_column($arr_prac, 'id'));
                                $practice= $arr_prac[$key]['practice_number'];
                                //echo $key."---".$mypracID;
                                //$primaryICDCode=isset($arr_claimline[$i]['LDiag']['ID'])?$arr_claimline[$i]['LDiag']['ID']:'';
                                $primaryICDCode=$main_icd10;
                                if($i==1) {
                                    $secondaryICDCode = $this->checking(isset($arr_claimline[$i]['LDiag']['ID'])?$arr_claimline[$i]['LDiag']['ID']:'');
                                }
                                $ic10Data=$this->checkPmb($primaryICDCode);
                                $PMBFlag=strlen($ic10Data["pmb_code"])>0?"Y":"N";
                                $primaryICDDescr =$ic10Data["shortdesc"];
                                $secondaryICDCode =  $secicd10 ;
                                $secondaryICDDescr = $this->checkPmb($primaryICDCode)["shortdesc"];
                                $reason_code =$this->checking(isset($arr_claimline[$i]['Response']['Code'])?$arr_claimline[$i]['Response']['Code']:'XXX');
                                //echo "======".$reason_code;
                                $reason_description=$this->checking(isset($arr_claimline[$i]['Response']['Description'])?$arr_claimline[$i]['Response']['Description']:'');
                                if ($reason_code=="XXX")
                                {
                                    //['Response']['0']['Code']
                                    //$data['Cont']['Claim']['Line']['Response']['0']['Code']

                                    //$data['Cont']['Claim']['Line']['1']['Response']
                                    //$data['Cont']['Claim']['Line']['1']['Meds']['Response']
                                    $reason_code =$this->checking(isset($arr_claimline[$i]['Meds']['Response']['Code'])?$arr_claimline[$i]['Meds']['Response']['Code']:'YYY');
                                    $reason_description=$this->checking(isset($arr_claimline[$i]['Meds']['Response']['Description'])?$arr_claimline[$i]['Meds']['Response']['Description']:'');
                                }
                                if ($reason_code=="YYY")
                                {
                                    $reason_code =$this->checking(isset($arr_claimline[$i]['Meds']['Response'][0]['Code'])?$arr_claimline[$i]['Meds']['Response'][0]['Code']:'ZZZ');
                                    $reason_description=$this->checking(isset($arr_claimline[$i]['Meds']['Response'][0]['Description'])?$arr_claimline[$i]['Meds']['Response'][0]['Description']:'');

                                }
                                if ($reason_code=="ZZZ")
                                {
                                    $reason_code =$this->checking(isset($arr_claimline[$i]['Response'][0]['Code'])?$arr_claimline[$i]['Response'][0]['Code']:'');
                                    //echo "======".$reason_code;
                                    $reason_description=$this->checking(isset($arr_claimline[$i]['Response'][0]['Description'])?$arr_claimline[$i]['Response'][0]['Description']:'');

                                }

                                $ch=$this->conn->prepare('SELECT jv_claim_line_number FROM claim_line WHERE jv_claim_line_number=:jv_claim_line_number');
                                $ch->bindParam(':jv_claim_line_number', $clmnlineNumber, PDO::PARAM_STR);
                                $ch->execute();
                                $lcc=(int)$ch->rowCount();

                                $mess1="";
                                if ($lcc<1) {

                                    $insertClaimline = $this->conn->prepare('INSERT INTO claim_line(recordType,senderId,jv_claim_line_number,mca_claim_id,practice_number,clmnline_charged_amnt,
clmline_scheme_paid_amnt,gap,memberLiability,benefit_description,treatmentDate,primaryICDCode,primaryICDDescr,tariff_code,modifier,unit,PMBFlag,clmn_line_pmnt_status,creationDate,createdBy,msg_code,
msg_dscr,lng_msg_dscr,treatmentType,secondaryICDCode,secondaryICDDescr,cptCode,nappiCode,quantity,clmn_line_status_date,treatment_code_type,cptDescr,lastUpdateDate,toothNo,switch_reference,reason_code,reason_description,role
) VALUES(:recordType,:senderId,:jv_claim_line_number,:mca_claim_id,:practice_number,:clmnline_charged_amnt,:clmline_scheme_paid_amnt,:gap,:memberLiability,:benefit_description,
:treatmentDate,:primaryICDCode,:primaryICDDescr,:tariff_code,:modifier,:unit,:PMBFlag,:clmn_line_pmnt_status,:creationDate,:createdBy,:msg_code,:msg_dscr,:lng_msg_dscr,:treatmentType,:secondaryICDCode,:secondaryICDDescr,:cptCode,:nappiCode,:quantity,:clmn_line_status_date,:treatment_code_type,:cptDescr,:lastUpdateDate,:toothNo,:switch_reference,:reason_code,:reason_description,:role)');
                                    $insertClaimline->bindParam(':recordType', $recordType, PDO::PARAM_STR);
                                    $insertClaimline->bindParam(':senderId', $senderId, PDO::PARAM_STR);
                                    $insertClaimline->bindParam(':jv_claim_line_number', $clmnlineNumber, PDO::PARAM_STR);
                                    $insertClaimline->bindParam(':mca_claim_id', $claim_id, PDO::PARAM_STR);
                                    $insertClaimline->bindParam(':practice_number', $practice, PDO::PARAM_STR);
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
                                    $insertClaimline->bindParam(':reason_code', $reason_code, PDO::PARAM_STR);
                                    $insertClaimline->bindParam(':reason_description', $reason_description, PDO::PARAM_STR);
                                    $insertClaimline->bindParam(':role', $mypracRole, PDO::PARAM_STR);
                                    $cc3 = $insertClaimline->execute();
                                    if ($cc3 == 1) {
                                        $ttp=$this->updateInvoice($this->conn,$claim_id,$practice,$provider_invoicenumber);

                                        $mess = "Claim Line Successfully added";
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
                $this->mess2 = "There is ana err".$e->getMessage();
            }

            $eacharray=array("claim_number"=>$claim_number,"message"=>$this->mess2,"claimline"=>$myArrayLine);
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
            $this->claimaudit($this->conn,$num,$message);

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
                $this->claimaudit($this->conn,$num,$m,$l);

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

        $this->allaudit($this->conn,(int)$rc,(int)$failed,(int)$succeed);
        $display_array=array("total_processed"=>$rc,"total_succeed"=>$succeed,"total_failed"=>$failed,"claims"=>$claim_array);
        if($failed>0)
        {
            $array_response["Stat"]="R";
            $array_response["Error"] = ["Code" => 200, "Desc" => $this->mess2];
        }
        //print_r($array_response);
        $array_response["DateType"]=date("Ymd");

        $this->prnt($array_response);
        // print_r($array_response);

        //echo json_encode($display_array);

    }
    function array_to_xml($array, &$xml_info) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_info->addChild("v");
                    $this->array_to_xml($value, $subnode);
                }
            }else {
                $xml_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
    function array2xml($data_array){
        $xml_info = new SimpleXMLElement("<?xml version=\"1.0\"?><Resp></Resp>");
        $this->array_to_xml($data_array,$xml_info);
        //$xml_file = $xml_info->asXML('myx.xml');
        $xml_file = $xml_info->asXML();

        return $xml_file;
    }
    function checkDoctor($conn,$number)
    {

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


    function export($conn)
    {


        try {


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

    function claimaudit($conn,$claim_number,$message,$line_number="")
    {
        try {
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
    function allaudit($conn,$total,$failed,$succeed,$status="")
    {
        try {
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
    function checkScheme($conn,$medical_scheme)
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
        elseif ($medical_scheme=="SA BREWERIES MEDICAL AID SOCIETY")
        {
            $medical_scheme="SA Breweries Medical Aid Scheme (SABMAS)";
        }
        elseif ($medical_scheme=="TFG (THE FOSCHINI GROUP) MEDICAL AID SCHEME")
        {
            $medical_scheme="TFG Medical Aid Scheme";
        }
        elseif ($medical_scheme=="UNIVERSITY OF KWAZULU NATAL MEDICAL SCHEME")
        {
            $medical_scheme="University of KwaZulu-Natal Medical Scheme";
        }

        elseif ($medical_scheme=="BANKMED MEDICAL SCHEME")
        {
            $medical_scheme="Bankmed";
        }
        elseif ($medical_scheme=="DISCOVERY HEALTH MEDICAL SCHEME")
        {
            $medical_scheme="Discovery Health Medical Scheme";
        }
        elseif($medical_scheme=="GOVERNMENT EMPLOYEES MEDICAL SCHEME (GEMS EMERALD OPTION)")
        {
            $medical_scheme="Government Employees Medical Scheme (GEMS)";
        }

        elseif($medical_scheme=="LIBERTY HEALTH MEDICAL SCHEME")
        {
            $medical_scheme="Liberty Medical Scheme";
        }
        elseif($medical_scheme=="COMPCARE MEDICAL SCHEME")
        {
            $medical_scheme="Compcare Wellness Medical Scheme";
        }
        elseif($medical_scheme=="SISONKE MEDICAL SCHEME")
        {
            $medical_scheme="Sisonke Health Medical Scheme";
        }
        elseif($medical_scheme=="SASOLMED MEDICAL SCHEME")
        {
            $medical_scheme="Sasolmed";
        }
        elseif($medical_scheme=="OLD MUTUAL STAFF MEDICAL AID SCHEME")
        {
            $medical_scheme="Old Mutual Staff Medical Aid Fund";
        }
        elseif($medical_scheme=="CAMAF")
        {
            $medical_scheme="Chartered Accountants (SA) Medical Aid Fund (CAMAF)";
        }
        elseif($medical_scheme=="BP SOUTHERN AFRICA MEDICAL AID SOCIETY")
        {
            $medical_scheme="BP Medical Aid Society";
        }
        elseif($medical_scheme=="PARMED MEDICAL AID FUND")
        {
            $medical_scheme="Parmed Medical Aid Scheme";
        }
        elseif($medical_scheme=="POLMED")
        {
            $medical_scheme="South African Police Service Medical Scheme (POLMED)";
        }
        elseif($medical_scheme=="ALLIANCE MIDMED MEDICAL SCHEME")
        {
            $medical_scheme="Alliance-Midmed Medical Scheme";
        }
        elseif($medical_scheme=="THEBEMED MEDICAL SCHEME")
        {
            $medical_scheme="Thebemed";
        }
        elseif($medical_scheme=="RHODES UNIVERSITY MEDICAL SCHEME (RU MED)")
        {
            $medical_scheme="Rhodes University Medical Scheme";
        }
        elseif($medical_scheme=="INGWE HEALTH PLAN")
        {
            $medical_scheme="Momentum Health";
        }
        elseif($medical_scheme=="MOMENTUM HEALTH MEDICAL SCHEME")
        {
            $medical_scheme="Momentum Health";
        }
        elseif($medical_scheme=="SEDMED MEDICAL SCHEME")
        {
            $medical_scheme="SEDMED";
        }
        elseif($medical_scheme=="UNIV OF WITWATERSRAND STAFF MEDICAL AID SCHEME")
        {
            $medical_scheme="University of the Witwatersrand Staff Medical Aid Fund";
        }
        elseif($medical_scheme=="LA HEALTH")
        {
            $medical_scheme="LA-Health Medical Scheme";
        }
        elseif($medical_scheme=="DC MED: MBMED OPTION")
        {
            $medical_scheme="MBMed Medical Aid Fund";
        }
        elseif($medical_scheme=="MOTO HEALTHCARE")
        {
            $medical_scheme="Motohealth Care";
        }

        elseif($medical_scheme=="FISHING INDUSTRY MEDICAL SCHEME (FISHMED)")
        {
            $medical_scheme="";
        }

        try
        {



            $stnt = $conn->prepare('SELECT name FROM schemes WHERE name=:nn');
            $stnt->bindParam(':nn', $medical_scheme, PDO::PARAM_STR);
            $stnt->execute();
            if($stnt->rowCount()>0)
            {
                $medical_scheme=$stnt->fetchColumn();
            }
            else
            {
                $medical_scheme="Unknown";
            }
        }
        catch (Exception $r)
        {
            $medical_scheme="Unknown";
        }

        return $medical_scheme;
    }
    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
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
    public function addObj($conn,$obj)
    {
        try {
            $stmt = $conn->prepare('INSERT INTO jv_objects(obj) VALUES(:obj)');
            $stmt->bindParam(':obj', $obj, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (Exception $e)
        {

        }


    }

    function prnt($array_response)
    {
        $array_response["DateType"]=date("Ymd");
        $objs= $this->array2xml($array_response);
        $contentsx = '<SRef/>';
        $rrrr=$this->SRef1.$this->SRef2;
        $filez=str_replace($contentsx,$rrrr,$objs);
        die($filez);
    }
    function checkClient($conn,$name)
    {
        $myname=4;
        try {
            $name=strtolower($name);
            $name=ucfirst($name);
            $stmt = $conn->prepare('SELECT client_id FROM clients WHERE client_name=:nname LIMIT 1');
            $stmt->bindParam(':nname', $name, PDO::PARAM_STR);
            $stmt->execute();
            $nu=$stmt->rowCount();
            if($nu>0)
            {
                $myname=$stmt->fetchColumn();
            }

        }
        catch (Exception $e)
        {

        }
        return $myname;
    }
}
$n=new jv_import_export();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n->readFile();
}
else{
    $array_response["SRef"]=[];
    $array_response["Stat"]="R";
    $array_response["Error"] = ["Code" => 500, "Desc" => "500 Internal Server Error"];

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $n->prnt($array_response);
}
?>

