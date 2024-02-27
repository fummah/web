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
        $checkM=$conn->prepare('SELECT a.claim_id,a.claim_number,b.medical_scheme,charged_amnt,scheme_paid,gap,a.Service_Date,a.icd10 FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE b.email=:email OR b.id_number=:id_number OR b.scheme_number=:scheme_number');
        $checkM->bindParam(':email', $email, PDO::PARAM_STR);
        $checkM->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $checkM->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    function getClaimDoctors($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare("SELECT name_initials,surname,a.practice_number FROM `doctors` as a INNER JOIN doctor_details as b ON a.practice_number=b.practice_number WHERE a.claim_id=:claim_id");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    function getClaimLines($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare("SELECT DISTINCT primaryICDCode,c.ccs_grouper_code,ccs_grouper_desc FROM claim_line as a INNER JOIN `coding` as c ON a.primaryICDCode=c.diag_code WHERE a.mca_claim_id=:claim_id");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
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
                foreach($header as $r) {

                    $claim_id=$r["claim_id"];
                    $claim_header = array("claim_id"=>$r["claim_id"],"claim_number"=>$r["claim_number"],"medical_scheme"=>$r["medical_scheme"],"charged_amnt"=>$r["charged_amnt"],"scheme_paid"=>$r["scheme_paid"],"gap"=>$r["gap"],"Service_Date"=>$r["Service_Date"]);
                    $claim_doctors =  $this->getClaimDoctors($claim_id);  
                    $claim_lines = $this->getClaimLines($claim_id);
                    $inarr = array("claim_header"=>$claim_header,"claim_doctors"=>$this->formatDoctors($claim_doctors),"claim_lines"=>$claim_lines);    
                    array_push($array_main, $inarr);     
                }; 
                if(count($header)<0) 
                {
                    return array(array("claim_header"=>[],"claim_doctors"=>[]));
                }
                else{
                    return $array_main;
                }
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

