<?php
//error_reporting(0);
include ("../../mca/link4.php");
$conn=connection("seamless","seamless");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");

class jv_import_export
{
    public $mess2;
    public $mess3;
    public $username;
    public $password;

    function getClaimHeader($email, $scheme_number, $id_number)
    {
        global $conn;     
        $checkM=$conn->prepare('SELECT a.claim_id,a.claim_number,b.medical_scheme,charged_amnt,scheme_paid,gap,a.Service_Date,a.icd10,a.date_entered FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE b.email=:email OR b.id_number=:id_number OR b.scheme_number=:scheme_number');
        $checkM->bindParam(':email', $email, PDO::PARAM_STR);
        $checkM->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $checkM->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    function getClaimDoctors($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare("SELECT name_initials,surname,a.practice_number,disciplinecode,cpt_code FROM `doctors` as a INNER JOIN doctor_details as b ON a.practice_number=b.practice_number WHERE a.claim_id=:claim_id");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    function getClaimLines($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare("SELECT DISTINCT primaryICDCode,c.ccs_grouper_code,ccs_grouper_desc,a.tariff_code FROM claim_line as a INNER JOIN `coding` as c ON a.primaryICDCode=c.diag_code WHERE a.mca_claim_id=:claim_id");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    function getDctorClaimLines($claim_id,$practice_number)
    {
        try
        {
        global $conn;
        $checkM=$conn->prepare("SELECT DISTINCT primaryICDCode,tariff_code FROM claim_line WHERE mca_claim_id=:claim_id AND practice_number=:practice_number");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
        }
        catch(Exception $e){

        }
    }
     function getTips($tariff_code)
    {
        global $conn;
        $tariff_code = "%".$tariff_code."%";
        $checkM=$conn->prepare("SELECT rule_name FROM `internal_rules` WHERE vals like :tariff_code LIMIT 1");
        $checkM->bindParam(':tariff_code', $tariff_code, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchColumn();
    }
    public function getICD10Details($icd10)
    {
        global $conn2;
        $stmt = $conn2->prepare('SELECT `diag_code`,`pmb_code`,`shortdesc` FROM `Diagnosis` WHERE diag_code=:icd10 UNION SELECT `ICD10_Code`,`pmb`,`ICD10_3_Code_Desc` FROM `diagonisis1`  WHERE ICD10_Code=:icd10');
        $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function isPMB($icd10_code)
    {
        $pmbstatus1=false;
        if($this->getICD10Details($icd10_code)==true)
        {
            $datax=$this->getICD10Details($icd10_code);
            if (strlen($datax["pmb_code"]) > 1) {
                $pmbstatus1=true;
            }
        }
        return $pmbstatus1;
    }
    function getCoding($tarrif,$icd10,$cpt4="")
    {
        global $conn2;
        $arrg=[];
        if(strlen($tarrif)>0 && strlen($icd10)>0) {
            $n="N";
            $stmtx = $conn2->prepare('SELECT Tariff_Code FROM TariffMaster WHERE Procedure_Group = :n AND Tariff_Code=:t');
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
                        $stmt = $conn2->prepare('SELECT * FROM `ClinicalXref` WHERE `xref_type` = :cdpi AND `clinical_code` = :cpt4 AND clinical_xref=:icd10');
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

                    $stmt = $conn2->prepare('SELECT *FROM(SELECT * FROM `ClinicalXref` WHERE `clinical_code` = :tarrif) as a where clinical_xref IN (SELECT clinical_code FROM `ClinicalXref` WHERE `clinical_xref` = :icd10)');
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
    function readFile()
    {
        global $conn;
        $file = file_get_contents('php://input', true);
        $t=json_decode($file,true);
        $this->mess3="";
        if($t === null) {

            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $err=array("status"=>"500","message"=>"Internal Server Error vv");
            echo json_encode($err,true);
            die();
        }
        $this->username = $t["Username"];
        $this->password = $t["Password"];
        $cclient_name="seamless_production";
        $envviro="production";

        if(!$this->validate($this->username,$this->password,$envviro,$cclient_name))
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorised Access', true, 401);
            $err=array("message"=>"Unauthorised User Access","status"=>"401");
            echo json_encode($err,true);
            die();
        }
       
       $array_main= array();
              $email = $t["email"];
                $scheme_number = $t["scheme_number"];
                $id_number = $t["id_number"];  
                $header = $this->getClaimHeader($email, $scheme_number, $id_number);
                $correct = [];    
                    $posscorrect = [];
                foreach($header as $r) { 
                    $tiparr=[];          
                      	$poss=1;
                    $claim_id=$r["claim_id"];
                    $claim_header = array("claim_id"=>$r["claim_id"],"claim_number"=>$r["claim_number"],"medical_scheme"=>$r["medical_scheme"],"charged_amnt"=>$r["charged_amnt"],"date_entered"=>$r["date_entered"],"scheme_paid"=>$r["scheme_paid"],"gap"=>$r["gap"],"Service_Date"=>$r["Service_Date"]);
                    $vaclinix=array();
                    $arx05=[];
                    $inhosparr=[];
                    $vaclini=["N"];
                    $codes3=[];
                    $emegncyarr=[];
                
                        $claim_doctors=$this->getClaimDoctors($claim_id);
                        foreach ($claim_doctors as $doctor_row) {
                            $practice_number = str_pad($doctor_row["practice_number"], 7, '0', STR_PAD_LEFT);                           
                            $disciplinecode = $doctor_row["disciplinecode"];
                            if ($disciplinecode == "010" || $disciplinecode == "10") {
                                array_push($vaclinix, $practice_number);
                            }
                            if ($this->checkMofifier($claim_id, $practice_number, $disciplinecode)) {
                                array_push($arx05, $practice_number);
                            }
                            $descipline_code_array = ["56", "57", "58", "59", "056", "057", "058", "059"];
                
                            if (in_array($disciplinecode,$descipline_code_array)) {
                                array_push($inhosparr, $practice_number);
                            }
                            foreach ($this->getDctorClaimLines($claim_id, $practice_number) as $line_row) {
                                array_push($emegncyarr, $line_row["tariff_code"]);
                                array_push($codes3, $line_row["tariff_code"]);
                                $icd10=$line_row["primaryICDCode"];
                                if ($this->isPMB($icd10)=="Y") {
                                    array_push($vaclini,"Y");
                                }
                                $tr=$this->getTips($line_row["tariff_code"]);
                    	if($tr)
                    	{
                    		array_push($tiparr,$tr);
                    	}
                              
                            }
                        }
                        $tv=count($vaclinix);
                        $tv1=count($arx05);
                        $tv2=count($inhosparr);
                        if(
                            ($vaclini[0]=="Y" && $tv>0) || ($tv1>0) || ($tv2>100) || (count($emegncyarr)>0 && in_array("Y",$vaclini)) || 
                            ((in_array("0614",$emegncyarr) && in_array("0646",$emegncyarr) ) || (in_array("0614",$emegncyarr) && in_array("0637",$emegncyarr))) ||
                            (in_array("0589", $codes3) || in_array("0592", $codes3) || in_array("0593", $codes3))
                        )
                        {
                            $poss=2;
                            array_push($posscorrect,$claim_id);
                        }
                        else
                        {
                            array_push($correct,$claim_id); 
                        }
                        $claim_lines = $this->getClaimLines($claim_id);
                    $inarr = array("claim_header"=>$claim_header,"claim_doctors"=>$this->formatDoctors($claim_doctors),"claim_lines"=>$claim_lines,"tips"=>$tiparr,"poss"=>$poss);    
                    array_push($array_main, $inarr);     
                } 
                if(count($header)<0) 
                {
                    return array("qq"=>array("claim_header"=>[],"claim_doctors"=>[]),"graph"=>array("posscorrect"=>[],"possincorrect"=>[]));
                }
                else{
                    return array("qq"=>$array_main,"graph"=>array("posscorrect"=>$correct,"possincorrect"=>$posscorrect));
                }
    }
    function checkMofifier($claim_id,$practice_number,$practicetype)
    {
        $check = false;
        if ($practicetype != "010" || $practicetype != "10") {
            $check = true;
            $count = 0;
            try {
                foreach ($this->getDctorClaimLines($claim_id,$practice_number) as $row) {
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


    private function formatDoctors($arr)
    {
        if(count($arr)>0)
        {
            $inarr=[];
            foreach($arr as $rr)
            {
                array_push($inarr,$rr["name_initials"]. " ".$rr["surname"]." (".$rr["practice_number"].")");
            }
            return implode(' | ',$inarr);
        }
        else
        {
            return "";
        }
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
   
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n=new jv_import_export();    
    echo json_encode($n->readFile(),true);

}
else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
    $err=array("message"=>"Bad Request","status"=>"400");
    echo json_encode($err,true);
}
?>

