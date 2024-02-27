<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
error_reporting(0);
include ("../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$mail = new PHPMailer(true);
require_once('classes/leadClass.php');
$obj=new leadClass();
$obj->mess=true;
$data = json_decode(file_get_contents("php://input"));

$fist_name=ucfirst($obj->validateXss($data->first_name));
$last_name=ucfirst($obj->validateXss($data->last_name));

$contact_number=$data->contact_number;
$email=$data->email;
$medical_name=$obj->validateXss($data->scheme);
$practice_number=$data->practice_number;
$discipline=$data->descipline;
$address=$data->address;
$contact_person=$data->contact_person;
$practice_number=str_pad($practice_number, 7, '0', STR_PAD_LEFT);
$data1=checkNow($discipline);
$displine_type = strtoupper($data1['code']);
$subcode = strtoupper($data1['subcode']);
$disciplinedescr = strtoupper($data1['descr']);
$subdesr = strtoupper($data1['subdescr']);
$password=$obj->generatePassword();
$encryp_pass = password_hash($password, PASSWORD_BCRYPT);
//	admin_name
//$pra=$obj->getDoctorByemail($email);
if((int)$obj->checkProfile1($email)<1)
{
    $subject="Med ClaimAssist login details";
    $body = "Dear " . $fist_name . "<br><br>Welcome and thank you for registering to our service . Your <b>Med ClaimAssist</b> profile has now been created. 
<br><br>The best way to use our services is to login to your account on our web site. <a href='https://medclaimassist.co.za/login'>Click here to login</a><br><br>
Your login details for <b>Med ClaimAssist System</b> are as follows:
<br>Username : " . $email . "<br>Password : " . $password . "<br><br>
Once you have logged in you will be able to change your password to whatever you wish.<br><br>
<a href='https://medclaimassist.co.za/login'>Click here to login</a><br><br>Yours sincerely<br><br>The Medclaim Assist Team";
    //$obj->insertProfile($fist_name,$last_name,"",$email,$contact_number,$address,"doctor",$practice_number,$encryp_pass);
    $obj->sendMailMain($email,$subject,$body);
    echo "Success";
   
        //$obj->insertDoctor($fist_name,$last_name,$contact_number,$discipline,$practice_number,$address,$displine_type,$subcode,$disciplinedescr,$email,$contact_person);
        $obj->insertProvider($fist_name,$last_name,$email,$contact_number,$practice_number,$address,"provider",$encryp_pass,$discipline,$discipline,$subcode,$disciplinedescr);
    

}
else{
    echo "Duplicate";
}



function checkNow($id)
{
    global $conn;
    $data["code"]="";
    $data["subcode"]="";
    $data["descr"]="";
    $data["subdescr"]="";
    $stmt=$conn->prepare('SELECT *FROM disciplinecodes WHERE id=:id');
    $stmt->bindParam(':id',$id,PDO::PARAM_STR);
    $stmt->execute();
    $nu=$stmt->rowCount();
    if($nu>0) {
        $arr = $stmt->fetch();
        $data["code"] = $arr[1];
        $data["subcode"] = $arr[2];
        $data["descr"] = $arr[3];
        $data["subdescr"] = $arr[4];
    }
    return $data;
}
?>