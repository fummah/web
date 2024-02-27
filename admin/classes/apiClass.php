<?php
namespace mcaAPI;
include ("../../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");
class apiClass
{
    function __constructor()
    {
        if (!defined('access')) {
            die('Access not permited');
        }
    }

    function getClients($status = 1)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT DISTINCT reporting_client_id as client_id,client_name as obj_name FROM clients WHERE reporting_status=:status ORDER BY client_name');
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getUsers($status = 1)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT DISTINCT username as obj_name FROM users_information WHERE status=:status ORDER BY username');
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getTopLevel($columns,$hierach,$clients,$users,$start_date,$end_date,$other_fields=array())
    {

        global $conn;
        $columns=json_decode($columns);
        $count=count($columns);
        if(!empty($start_date))
        {
            $date = new \DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        $arrf=$this->groupTotals();
        $hierach_arr=$this->topFields();
        //print_r($other_fields);
        $dat=!empty($start_date)?" x.date_entered >='".$start_date."' AND x.date_entered<'".$end_date."' ":"1";
        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" AND x.username IN ('".$users_em."')":" AND 1";
        $vol1=!empty($clients)?" AND z.client_name IN ('".$clients_em."')":" AND 1";
        $xtrafield="";
        if(count($other_fields)>0)
        {
            foreach ($other_fields as $key => $val)
            {

                $id = array_search($key, array_column($hierach_arr, 'field_name'));
                $extra=$hierach_arr[$id]["table"].".".$key;
                $xtrafield.=" AND ".$extra." IN ('".$val."')";
            }
        }
        $all=$dat.$vol.$vol1.$xtrafield;
        $id = array_search($hierach, array_column($hierach_arr, 'field_name'));
        $active_field=$hierach_arr[$id]["table"].".".$hierach;
        $str=$active_field;
        $orderby=$active_field;

        foreach ($columns as $coms)
        {
            $id = array_search($coms, array_column($arrf, 'field_name'));
            $table=$arrf[$id]["table"];
            $type=$arrf[$id]["type"];
            $dist=$type=="COUNT" || $type=="SUM"?"DISTINCT ":"";
            $str.=",$type($dist".$table.".".$coms.") as $coms";
        }
        if($count>0)
        {
            $id = array_search($columns[0], array_column($arrf, 'field_name'));
            $orderby=$arrf[$id]["table"].".".$columns[0];
        }
        $sql="SELECT ".$str." 
           FROM claim_line as k 
           INNER JOIN claim as x ON k.mca_claim_id=x.claim_id 
           INNER JOIN doctor_details as d ON k.practice_number=d.practice_number 
           INNER JOIN member as y ON x.member_id=y.member_id 
           INNER JOIN clients as z ON y.client_id=z.client_id 
           INNER JOIN coding as j ON k.primaryICDCode=j.diag_code 
           WHERE ".$all."
           GROUP BY $active_field ORDER BY ".$orderby." DESC";
        //echo "==".$active_field."--->".$sql."<hr>";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    function groupTotals()
    {
        $arr=array(
            array("ui_name"=>"Charged Amount","field_name"=>"clmnline_charged_amnt","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Scheme Amount","field_name"=>"clmline_scheme_paid_amnt","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Gap","field_name"=>"gap","status"=>"checked","type"=>"SUM","table"=>"k"),
            array("ui_name"=>"Claims","field_name"=>"claim_id","status"=>"checked","type"=>"COUNT","table"=>"x"),
            array("ui_name"=>"Claim Lines","field_name"=>"id","status"=>"","type"=>"COUNT","table"=>"k"),
            array("ui_name"=>"Scheme Savings","field_name"=>"savings_scheme","status"=>"","type"=>"SUM","table"=>"x"),
            array("ui_name"=>"Discount Savings","field_name"=>"savings_discount","status"=>"","type"=>"SUM","table"=>"x")

        );
        return $arr;
    }
    public function moneyformat($val)
    {
        return number_format($val,2,'.',',');
    }
    function topFields()
    {
        $arr=array(
            array("ui_name"=>"CCS Group","field_name"=>"ccs_grouper_desc","status"=>"checked","table"=>"j"),
            array("ui_name"=>"Section","field_name"=>"section_desc","status"=>"","table"=>"j"),
            array("ui_name"=>"Medical Scheme","field_name"=>"medical_scheme","status"=>"","table"=>"y"),
            array("ui_name"=>"Discipline Code","field_name"=>"disciplinecode","status"=>"","table"=>"d"),
            array("ui_name"=>"ICD10 Code","field_name"=>"primaryICDCode","table"=>"k"),
            array("ui_name"=>"Tarrif Code","field_name"=>"tariff_code","table"=>"k"),
            array("ui_name"=>"Claim Number","field_name"=>"claim_number","table"=>"x")

        );
        return $arr;
    }
    function checkMember($policy_number,$client_id)
    {
        global $conn;
        $member_id="";
        $checkM=$conn->prepare('SELECT member_id FROM member WHERE policy_number=:policy_number AND client_id=:client_id LIMIT 1');
        $checkM->bindParam(':policy_number', $policy_number, \PDO::PARAM_STR);
        $checkM->bindParam(':client_id', $client_id, \PDO::PARAM_STR);
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
        $checkM->bindParam(':claim_number', $claim_number, \PDO::PARAM_STR);
        $checkM->bindParam(':client_id', $client_id, \PDO::PARAM_STR);
        $checkM->execute();
        $cc=$checkM->rowCount();
        if($cc>0)
        {
            $this->mess2="Duplicate Claim";
            $claim_id=$checkM->fetchColumn();
        }
        return $claim_id;
    }
    function checkClaimWithPolicy($policy_number,$client_id)
    {
        global $conn;
        $checkM=$conn->prepare('SELECT a.claim_id,a.claim_number,a.username FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE b.policy_number=:policy_number AND b.client_id=:client_id AND a.username IN(SELECT username FROM `users_information` WHERE active=1) ORDER BY a.claim_id DESC LIMIT 1');
        $checkM->bindParam(':policy_number', $policy_number, \PDO::PARAM_STR);
        $checkM->bindParam(':client_id', $client_id, \PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetch();
    }
    function updateClaim($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare('UPDATE claim SET Open=1 WHERE claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
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
        $stmt->bindParam(':dat', $date, \PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();

    }
    function InsertPatient($claim_id,$first_name,$surname)
    {
        global $conn;
        $patient_name=$first_name." ".$surname;
        $stmt=$conn->prepare('INSERT INTO patient (claim_id, patient_name) VALUES(:claim_id,:patient_name)');
        $stmt->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $stmt->bindParam(':patient_name', $patient_name, \PDO::PARAM_STR);
        $stmt->execute();


    }
    function InsertFeedback($claim_id,$description,$owner)
    {

        global $conn;
        $stmt=$conn->prepare('INSERT INTO `feedback`(`claim_id`, `description`, `owner`) VALUES(:claim_id,:description,:owner)');
        $stmt->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, \PDO::PARAM_STR);
        $stmt->bindParam(':owner', $owner, \PDO::PARAM_STR);
        $stmt->execute();


    }
    function insertMember($policyNumber,$productName,$personNumber,$personName,$personSurname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id,$product_code,$benefitiary_number,$cell_number,$telephone,$email)
    {
        global $conn;
        $insertMember = $conn->prepare('INSERT INTO member(policy_number,productName,id_number,first_name,surname,medical_scheme,scheme_option,medicalSchemeRate,scheme_number,client_id,product_code,beneficiary_number,cell,telephone,email) VALUES
(:policy_number,:productName,:id_number,:first_name,:surname,:medical_scheme,:scheme_option,:medicalSchemeRate,:scheme_number,:client_id,:product_code,:beneficiary_number,:cell,:telephone,:email)');
        $insertMember->bindParam(':policy_number', $policyNumber, \PDO::PARAM_STR);
        $insertMember->bindParam(':productName', $productName, \PDO::PARAM_STR);
        $insertMember->bindParam(':id_number', $personNumber, \PDO::PARAM_STR);
        $insertMember->bindParam(':first_name', $personName, \PDO::PARAM_STR);
        $insertMember->bindParam(':surname', $personSurname, \PDO::PARAM_STR);
        $insertMember->bindParam(':medical_scheme', $medicalSchemeName, \PDO::PARAM_STR);
        $insertMember->bindParam(':scheme_option', $medicalSchemeOption, \PDO::PARAM_STR);
        $insertMember->bindParam(':medicalSchemeRate', $medicalSchemeRate, \PDO::PARAM_STR);
        $insertMember->bindParam(':scheme_number', $medicalSchemeNumber, \PDO::PARAM_STR);
        $insertMember->bindParam(':client_id', $client_id, \PDO::PARAM_STR);
        $insertMember->bindParam(':product_code', $product_code, \PDO::PARAM_STR);
        $insertMember->bindParam(':beneficiary_number', $benefitiary_number, \PDO::PARAM_STR);
        $insertMember->bindParam(':cell', $cell_number, \PDO::PARAM_STR);
        $insertMember->bindParam(':telephone', $telephone, \PDO::PARAM_STR);
        $insertMember->bindParam(':email', $email, \PDO::PARAM_STR);
        $cc = $insertMember->execute();
        return $cc;
    }
    function insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdBy1,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number="",$patient_idnumber="")
    {
        global  $conn;
        $insertClaim = $conn->prepare('INSERT INTO claim(claim_number,member_id,entered_by,jv_status,Service_Date,end_date,charged_amnt,scheme_paid,gap,username,senderId,memberLiability,creationDate,
createdBy,patient_number,client_gap,pmb,icd10,icd10_desc,claim_number1,patient_idnumber) VALUES(:claim_number,:member_id,:entered_by,:jv_status,:Service_Date,:end_date,:charged_amnt,:scheme_paid,:gap,:username,:senderId,:memberLiability,:creationDate,:createdBy,:patient_number,:client_gap,:pmb,:icd10,:icd10_desc,:claim_number1,:patient_idnumber)');
        $insertClaim->bindParam(':claim_number', $claim_number, \PDO::PARAM_STR);
        $insertClaim->bindParam(':member_id', $member_id, \PDO::PARAM_STR);
        $insertClaim->bindParam(':entered_by', $createdBy, \PDO::PARAM_STR);
        $insertClaim->bindParam(':jv_status', $eventStatus, \PDO::PARAM_STR);
        $insertClaim->bindParam(':Service_Date', $eventDateFrom, \PDO::PARAM_STR);
        $insertClaim->bindParam(':end_date', $eventDateTo, \PDO::PARAM_STR);
        $insertClaim->bindParam(':charged_amnt', $claimChargedAmnt, \PDO::PARAM_STR);
        $insertClaim->bindParam(':scheme_paid', $schemePaidAmnt, \PDO::PARAM_STR);
        $insertClaim->bindParam(':gap', $claimCalcAmnt, \PDO::PARAM_STR);
        //$insertClaim->bindParam(':recordType', $recordType, \PDO::PARAM_STR);
        $insertClaim->bindParam(':senderId', $senderId, \PDO::PARAM_STR);
        $insertClaim->bindParam(':memberLiability', $memberLiability, \PDO::PARAM_STR);
        $insertClaim->bindParam(':createdBy', $createdBy1, \PDO::PARAM_STR);
        $insertClaim->bindParam(':creationDate', $creationDate, \PDO::PARAM_STR);
        $insertClaim->bindParam(':username', $username, \PDO::PARAM_STR);
        $insertClaim->bindParam(':patient_number', $patient_number, \PDO::PARAM_STR);
        $insertClaim->bindParam(':client_gap', $client_gap, \PDO::PARAM_STR);
        $insertClaim->bindParam(':pmb', $main_pmb, \PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10', $main_icd10, \PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10_desc', $main_icd10_desc, \PDO::PARAM_STR);
        $insertClaim->bindParam(':claim_number1', $client_claim_number, \PDO::PARAM_STR);
        $insertClaim->bindParam(':patient_idnumber', $patient_idnumber, \PDO::PARAM_STR);
        $cc1 = $insertClaim->execute();
        return $cc1;
    }
    function updateClaim1($claim_id,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc)
    {
        global  $conn;
        $insertClaim = $conn->prepare('UPDATE claim SET Service_Date=:Service_Date,end_date=:end_date,charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap,client_gap=:client_gap,pmb=:pmb,icd10=:icd10,icd10_desc=:icd10_desc WHERE claim_id=:claim_id');

        $insertClaim->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $insertClaim->bindParam(':Service_Date', $eventDateFrom, \PDO::PARAM_STR);
        $insertClaim->bindParam(':end_date', $eventDateTo, \PDO::PARAM_STR);
        $insertClaim->bindParam(':charged_amnt', $claimChargedAmnt, \PDO::PARAM_STR);
        $insertClaim->bindParam(':scheme_paid', $schemePaidAmnt, \PDO::PARAM_STR);
        $insertClaim->bindParam(':gap', $claimCalcAmnt, \PDO::PARAM_STR);
        $insertClaim->bindParam(':client_gap', $client_gap, \PDO::PARAM_STR);
        $insertClaim->bindParam(':pmb', $main_pmb, \PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10', $main_icd10, \PDO::PARAM_STR);
        $insertClaim->bindParam(':icd10_desc', $main_icd10_desc, \PDO::PARAM_STR);
        $cc1 = $insertClaim->execute();
        return $cc1;
    }

    function updateCaimline($claim_id,$practiceNo,$treatmentDate,$primaryICDCode,$primaryICDDescr,$tariffCode,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,$treatmentType)
    {
        global  $conn;
        $insertClaimline = $conn->prepare('UPDATE claim_line SET clmnline_charged_amnt=:clmnline_charged_amnt,clmline_scheme_paid_amnt=:clmline_scheme_paid_amnt,gap=:gap WHERE primaryICDCode=:primaryICDCode AND tariff_code=:tariff_code AND treatmentDate=:treatmentDate AND treatmentType=:treatmentType AND mca_claim_id=:mca_claim_id AND practice_number=:practice_number');
        $insertClaimline->bindParam(':mca_claim_id', $claim_id, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':practice_number', $practiceNo, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmnline_charged_amnt', $clmnlineChargedAmnt, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmline_scheme_paid_amnt', $clmlineSchemePaidAmnt, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':gap', $clmlineCalcAmnt, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':treatmentDate', $treatmentDate, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':primaryICDCode', $primaryICDCode, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':tariff_code', $tariffCode, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':treatmentType', $treatmentType, \PDO::PARAM_STR);
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
        $stmt->bindParam(':num', $icd10, \PDO::PARAM_STR);
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
            $stmt->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
            $stmt->execute();
            $a = $stmt->fetch();
            $charged_amnt += (double)$a[0];
            $scheme_paid += (double)$a[1];
            $gap += (double)$a[2];

            $checkM = $conn->prepare('UPDATE claim SET charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap WHERE claim_id=:claim_id');
            $checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
            $checkM->bindParam(':charged_amnt', $charged_amnt, \PDO::PARAM_STR);
            $checkM->bindParam(':scheme_paid', $scheme_paid, \PDO::PARAM_STR);
            $checkM->bindParam(':gap', $gap, \PDO::PARAM_STR);
            $checkM->execute();
        }
        catch (\Exception $e)
        {

        }


    }
    function checkDoctor($number)
    {

        global $conn;
        $check=false;
        try {
            $stmt = $conn->prepare('SELECT practice_number FROM doctor_details WHERE practice_number=:num');
            $stmt->bindParam(':num', $number, \PDO::PARAM_STR);
            $stmt->execute();
            $ccc = $stmt->rowCount();
            if ($ccc > 0) {
                $check = true;
            }
        }
        catch (\Exception $e)
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
        }
        catch (\Exception $r)
        {
            echo "There is an error";
        }
    }

    function claimaudit($claim_number,$message,$line_number="")
    {
        try {
            global $conn;
            $stnt = $conn->prepare('INSERT INTO `error_claim`(`claim_number`,`line_number`, `message`) VALUES (:claim_number,:line_number, :message)');
            $stnt->bindParam(':claim_number', $claim_number, \PDO::PARAM_STR);
            $stnt->bindParam(':line_number', $line_number, \PDO::PARAM_STR);
            $stnt->bindParam(':message', $message, \PDO::PARAM_STR);
            $stnt->execute();
        }
        catch (\Exception $r)
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
            $stnt->bindParam(':status', $status, \PDO::PARAM_STR);
            $stnt->bindParam(':total', $total, \PDO::PARAM_STR);
            $stnt->bindParam(':succeed', $succeed, \PDO::PARAM_STR);
            $stnt->bindParam(':failed', $failed, \PDO::PARAM_STR);
            $stnt->bindParam(':desciption', $url, \PDO::PARAM_STR);
            $stnt->bindParam(':desciption1', $desciption1, \PDO::PARAM_STR);
            $stnt->bindParam(':ip_address', $ip_address, \PDO::PARAM_STR);
            $stnt->execute();
        }
        catch (\Exception $r)
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
        $stmt->bindParam(':name1', $medical_scheme, \PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc<1)
        {
            $stmt=$conn->prepare('SELECT original_name FROM schemes_owl WHERE duplicate_name=:name1');
            $stmt->bindParam(':name1', $medical_scheme, \PDO::PARAM_STR);
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


    public function validate($username,$password,$other_name,$environment)
    {
        global $conn1;
        //echo "username,password,enviro,othername";
        //echo "$username,$password,$enviro,$othername";
        $stmt=$conn1->prepare('select username,password,other_name,environment from staff_users where username=:user1 AND role_value=1');
        $stmt->bindParam(':user1', $username, \PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc==1)
        {

            $vv=$stmt->fetch();
            $pass=$vv[1];
            //echo "=Other name = $other_name - ".$vv[2]."-Environme = - $environment ---".$vv[3]."=-Password-= $password --".$pass;
            if(password_verify($password,$pass)  && $other_name==$vv[2] && $environment==$vv[3])
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
        $stmt->bindParam(':user1', $user1, \PDO::PARAM_STR);
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
            $stmt->bindParam(':obj', $obj, \PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (\Exception $e)
        {

        }


    }
    public function getLatestClaim($claim_number)
    {
        $claim_id=0;
        try {
            global $conn;
            $selectlastclaim = $conn->prepare("SELECT max(claim_id) FROM claim WHERE claim_number=:claim_number");
            $selectlastclaim->bindParam(':claim_number', $claim_number, \PDO::PARAM_STR);
            $selectlastclaim->execute();
            $claim_id = $selectlastclaim->fetchColumn();
        }
        catch (\Exception $e)
        {
            $claim_id=0;
        }
        return $claim_id;
    }
    public function insertDoctor($practiceName,$pracno_1,$providertypedesc)
    {
        $inserted=0;
        $entered_by="Medway_production";
        try {
            global $conn;
            $insertDoctor1 = $conn->prepare('INSERT INTO doctor_details(name_initials,practice_number,discipline,entered_by) VALUES(:firstname,:practiceno,:service,:entered_by)');
            $insertDoctor1->bindParam(':firstname', $practiceName, \PDO::PARAM_STR);
            $insertDoctor1->bindParam(':practiceno',  $pracno_1, \PDO::PARAM_STR);
            $insertDoctor1->bindParam(':service', $providertypedesc, \PDO::PARAM_STR);
            $insertDoctor1->bindParam(':entered_by', $entered_by, \PDO::PARAM_STR);
            $inserted=$insertDoctor1->execute();
        }
        catch (\Exception $e)
        {
            $inserted=0;
        }
        return $inserted;
    }
    public function getClaimDoctor($claim_id,$practiceNo)
    {

        global $conn;
        $checkM=$conn->prepare('SELECT claim_id,practice_number,doc_gap FROM doctors WHERE claim_id=:claim_id AND practice_number=:practice_number LIMIT 1');
        $checkM->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $checkM->bindParam(':practice_number', $practiceNo, \PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetch();

    }
    public function insertClaimDoctor($claim_id,$practiceNo,$claimedline_id,$doc_gap,$provider_invoicenumber)
    {

        global $conn;
        $entered_by="Medway_production";
        $insertDoctor = $conn->prepare('INSERT INTO doctors(claim_id,practice_number,claimedline_id,doc_gap,provider_invoicenumber,entered_by) VALUES(:claim_id,:practice_number,:claimedline_id,:doc_gap,:provider_invoicenumber,:entered_by)');
        $insertDoctor->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $insertDoctor->bindParam(':practice_number', $practiceNo, \PDO::PARAM_STR);
        $insertDoctor->bindParam(':claimedline_id', $claimedline_id, \PDO::PARAM_STR);
        $insertDoctor->bindParam(':doc_gap', $doc_gap, \PDO::PARAM_STR);
        $insertDoctor->bindParam(':provider_invoicenumber', $provider_invoicenumber, \PDO::PARAM_STR);
        $insertDoctor->bindParam(':entered_by', $entered_by, \PDO::PARAM_STR);
        return  $insertDoctor->execute();

    }
        function updateClaimKey($claim_id,$key,$value,$condition="")
    {
        global $conn;
        try
        {
        $stmt=$conn->prepare('UPDATE claim SET '.$key.'=:val WHERE claim_id=:claim_id'.$condition);
        $stmt->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $stmt->bindParam(':val', $value, \PDO::PARAM_STR);
        $stmt->execute();
    }
    catch(\Exception $ex)
    {

    }
    }
    function getValidationsInd($id)
    {
        global $conn;
        try
        {
        $stmt =$conn->prepare("SELECT vals FROM internal_rules WHERE id=:id"); 
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);       
        $stmt->execute();
        return $stmt->fetchColumn();
        }
    catch(\Exception $ex)
    {
return "";
    }
    }
    public function updateClaimDoctor($claim_id,$practiceNo,$doc_gap)
    {

        global $conn;
        $insertDoctor = $conn->prepare('UPDATE doctors SET doc_gap=:doc_gap WHERE claim_id=:claim_id AND practice_number=:practice_number');
        $insertDoctor->bindParam(':claim_id', $claim_id, \PDO::PARAM_STR);
        $insertDoctor->bindParam(':practice_number', $practiceNo, \PDO::PARAM_STR);
        $insertDoctor->bindParam(':doc_gap', $doc_gap, \PDO::PARAM_STR);
        return  $insertDoctor->execute();

    }
    public function checkClaimLine($primaryICDCode,$tariffCode,$treatmentDate,$treatmentType,$claim_id,$practiceNo,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,$jv_claim_line_number="")
    {

        global $conn;
        $selectClaimline = $conn->prepare('SELECT mca_claim_id FROM claim_line WHERE primaryICDCode=:icd AND tariff_code=:tariff_code AND treatmentDate=:treatmentDate AND treatmentType=:treatmentType AND clmnline_charged_amnt=:clmnline_charged_amnt AND clmline_scheme_paid_amnt=:clmline_scheme_paid_amnt AND gap=:gap AND mca_claim_id=:mca_claim_id AND practice_number=:practice_number AND jv_claim_line_number=:jv_claim_line_number');
        $selectClaimline->bindParam(':icd', $primaryICDCode, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':tariff_code', $tariffCode, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':treatmentDate', $treatmentDate, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':treatmentType', $treatmentType, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':mca_claim_id', $claim_id, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':practice_number', $practiceNo, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':clmnline_charged_amnt', $clmnlineChargedAmnt, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':clmline_scheme_paid_amnt', $clmlineSchemePaidAmnt, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':gap', $clmlineCalcAmnt, \PDO::PARAM_STR);
        $selectClaimline->bindParam(':jv_claim_line_number', $jv_claim_line_number, \PDO::PARAM_STR);
        $selectClaimline->execute();
        return  (int)$selectClaimline->rowCount();

    }
    public  function addClaimLine($recordType,$senderId,$clmnlineNumber,$claim_id,$practiceNo,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,
                                  $memberLiability,$benefitDescription,$treatmentDate,$primaryICDCode,$primaryICDDescr,$tariffCode,$modifier,$unit,$PMBFlag,$clmnLinePmntStatus,$creationDate,$createdBy,$rej_code,$short_msg,$lon_msg,
                                  $treatmentType,$secondaryICDCode,$secondaryICDDescr,$cptCode,$nappiCode,$quantity,$clmnLinePmntStatusDate,$treatmentCodeType,$cptDescr,$lastUpdateDate,$toothNo,$switchReference,$gap_amount_line
    )
    {
        global $conn;
        $insertClaimline = $conn->prepare('INSERT INTO claim_line(recordType,senderId,jv_claim_line_number,mca_claim_id,practice_number,clmnline_charged_amnt,
clmline_scheme_paid_amnt,gap,memberLiability,benefit_description,treatmentDate,primaryICDCode,primaryICDDescr,tariff_code,modifier,unit,PMBFlag,clmn_line_pmnt_status,creationDate,createdBy,msg_code,
msg_dscr,lng_msg_dscr,treatmentType,secondaryICDCode,secondaryICDDescr,cptCode,nappiCode,quantity,clmn_line_status_date,treatment_code_type,cptDescr,lastUpdateDate,toothNo,switch_reference,gap_aamount_line
) VALUES(:recordType,:senderId,:jv_claim_line_number,:mca_claim_id,:practice_number,:clmnline_charged_amnt,:clmline_scheme_paid_amnt,:gap,:memberLiability,:benefit_description,
:treatmentDate,:primaryICDCode,:primaryICDDescr,:tariff_code,:modifier,:unit,:PMBFlag,:clmn_line_pmnt_status,:creationDate,:createdBy,:msg_code,:msg_dscr,:lng_msg_dscr,:treatmentType,:secondaryICDCode,:secondaryICDDescr,:cptCode,:nappiCode,:quantity,:clmn_line_status_date,:treatment_code_type,:cptDescr,:lastUpdateDate,:toothNo,:switch_reference,:gap_aamount_line)');
        $insertClaimline->bindParam(':recordType', $recordType, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':senderId', $senderId, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':jv_claim_line_number', $clmnlineNumber, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':mca_claim_id', $claim_id, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':practice_number', $practiceNo, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmnline_charged_amnt', $clmnlineChargedAmnt, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmline_scheme_paid_amnt', $clmlineSchemePaidAmnt, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':gap', $clmlineCalcAmnt, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':memberLiability', $memberLiability, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':benefit_description', $benefitDescription, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':treatmentDate', $treatmentDate, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':primaryICDCode', $primaryICDCode, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':primaryICDDescr', $primaryICDDescr, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':tariff_code', $tariffCode, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':modifier', $modifier, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':unit', $unit, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':PMBFlag', $PMBFlag, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmn_line_pmnt_status', $clmnLinePmntStatus, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':creationDate', $creationDate, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':createdBy', $createdBy, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':msg_code', $rej_code, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':msg_dscr', $short_msg, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':lng_msg_dscr', $lon_msg, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':treatmentType', $treatmentType, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':secondaryICDCode', $secondaryICDCode, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':secondaryICDDescr', $secondaryICDDescr, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':cptCode', $cptCode, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':nappiCode', $nappiCode, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':quantity', $quantity, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':clmn_line_status_date', $clmnLinePmntStatusDate, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':treatment_code_type', $treatmentCodeType, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':cptDescr', $cptDescr, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':lastUpdateDate', $lastUpdateDate, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':toothNo', $toothNo, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':switch_reference', $switchReference, \PDO::PARAM_STR);
        $insertClaimline->bindParam(':gap_aamount_line', $gap_amount_line, \PDO::PARAM_STR);
        return $insertClaimline->execute();
    }
}