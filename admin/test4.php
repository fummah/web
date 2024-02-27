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

       
        $ob= simplexml_load_string($file);
        $json  = json_encode($ob);

        $t=json_decode($json,true);


        $claim_number="";
        $r=$t;
        $array_count=1;

        for($i=0;$i<$array_count;$i++) {
            $myArrayLine=array();

            $mess = "";
            $this->mess2 = "";
            $cccv="";
            try {
        
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

                        $arr_doc = isset($t['Cont']['Claim']['Docs']['Doc'][0]['PCNS']) ? $t['Cont']['Claim']['Docs']['Doc'] : array($t['Cont']['Claim']['Docs']['Doc']);
                        for ($i = 0; $i < count($arr_doc); $i++) {
                            $chhh = false;
                            $practiceNo = $this->checking(isset($arr_doc[$i]['PCNS'])?$arr_doc[$i]['PCNS']:'');
                            $practiceName = $this->checking(isset($arr_doc[$i]['Name'])?$arr_doc[$i]['Name']:'');
                            $practiceID = $this->checking(isset($arr_doc[$i]['ID'])?$arr_doc[$i]['ID']:'');
                            $provider_invoicenumber=isset($t['Cont']['Claim']['Line'][0]['Invc'])?$t['Cont']['Claim']['Line'][0]['Invc']:"-";
                            echo "====".provider_invoicenumber;


               }

            } catch (Exception $e) {
                $this->mess2 = "There is ana err".$e->getMessage();
            }

      

		}
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

