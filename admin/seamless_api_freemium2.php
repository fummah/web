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

    function getClaimHeader($claim_id)
    {
        global $conn;     
        $checkM=$conn->prepare('SELECT * FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetch();
    }
    function getClaimDoctors($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare("SELECT name_initials,surname,a.practice_number FROM `doctors` as a INNER JOIN doctor_details as b ON a.practice_number=b.practice_number WHERE a.claim_id=:claim_id");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
     function getClaimLines($claim_id,$practice_number)
    {
        global $conn;
        $checkM=$conn->prepare("SELECT * FROM claim_line WHERE mca_claim_id=:claim_id AND practice_number=:practice_number");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
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
            $err=array("status"=>"500","message"=>"Internal Server Error");
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
       
       $indoc = array();     
                $claim_id = $t["claim_id"]; 
                $claim_header =  $this->getClaimHeader($claim_id);          

                if ($claim_header  == true) {
                foreach ($this->getClaimDoctors($claim_id) as $row) {
                $practice_number = $row["practice_number"];
                $full_name = $row["name_initials"]." ".$row["surname"];
                $claim_lines = $this->getClaimLines($claim_id,$practice_number);
                $arr = array("practice_number"=>$practice_number,"full_name"=>$full_name,"claim_lines"=>$claim_lines);
                array_push($indoc, $arr);                
            } 
        }
            return array('message' => 'Records Return','claim'=>$claim_header,"doctors"=>$indoc,"documents"=>[],"notes"=>[]);       
     
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

