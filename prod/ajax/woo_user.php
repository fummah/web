<?php
session_start();
define("access",true);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include ("../classes/controls.php");
$control=new controls();
if (!$control->isInternal())
{
    die("Invalid entry");
}
$mail = new PHPMailer(true);

$id=validateXss($_GET['id']);
$conn = connection("mca", "MCA_admin");
$conn1 = connection("mca1", "MCA_admin");
require_once('../classes/leadClass.php');
$obj=new leadClass();
/*** $message = a message saying we have connected ***/

/*** set the error mode to excptions ***/
//echo "<script>alert('Error : x');</script>";
if($id==1) {
    try {
        // echo "<script>alert('Error : x1');</script>";
        $order_id=(int)$_GET["order_id"];
        $xpassword = generatePassword();
        $encryp_pass = password_hash($xpassword, PASSWORD_BCRYPT);
        $password = $encryp_pass;
        $first_name=getValue($order_id,"_billing_first_name");
        $last_name=getValue($order_id,"_billing_last_name");
        $email=getValue($order_id,"_billing_email");
        $broker_id=isset($_SESSION["broker_id"])?$_SESSION["broker_id"]:"System";
        $entered_by="System";
        $role="client";
        //$id_num=getValue($order_id,"id_number");
        $id_num="";
        $phone=getValue($order_id,"_billing_phone");
        //$medical_scheme=getValue($order_id,"billing_medical_scheme");
        $medical_scheme="Unknown";
        //$scheme_option=getValue($order_id,"billing_scheme_option");
        $scheme_option="";
        //$aid_id=getValue($order_id,"billing_scheme_number");
$aid_id="";
        $addr_1="";
        //$aid_id=getValue($order_id,"billing_scheme_number");
        $addr_1="";
        //$branch_code=getValue($order_id,"_billing_postcode");
        $branch_code="";
        //echo "<script>alert('Error : x2');</script>";
        $st=$conn->prepare("SELECT client_id FROM web_clients WHERE email=:email");
        $st->bindParam(':email', $email, PDO::PARAM_STR);
        $st->execute();
        //Name, Surname, email address and contact number
        if($st->rowCount()>0)
        {
            sendMail($email,$first_name,$xpassword,2);
        }
        else
        {
            // echo "<script>alert('Error : x3');</script>";
            $stmnt = $conn->prepare('INSERT INTO web_clients(name, surname, id_number,email, contact_number, medical_scheme, scheme_option, medical_aid_number, 
physical_address1, role, broker_id, entered_by, password,branch_code) VALUES(:name, :surname, :id_number, :email, :contact_number, :medical_scheme, :scheme_option, :medical_aid_number, 
:physical_address1, :role, :broker_id, :entered_by, :password,:branch_code)');
            $stmnt->bindParam(':name', $first_name, PDO::PARAM_STR);
            $stmnt->bindParam(':surname', $last_name, PDO::PARAM_STR);
            $stmnt->bindParam(':id_number', $id_num, PDO::PARAM_STR);
            $stmnt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmnt->bindParam(':contact_number', $phone, PDO::PARAM_STR);
            $stmnt->bindParam(':medical_scheme', $medical_scheme, PDO::PARAM_STR);
            $stmnt->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
            $stmnt->bindParam(':medical_aid_number', $aid_id, PDO::PARAM_STR);
            $stmnt->bindParam(':physical_address1', $addr_1, PDO::PARAM_STR);
            $stmnt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmnt->bindParam(':broker_id', $broker_id, PDO::PARAM_STR);
            $stmnt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmnt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmnt->bindParam(':branch_code', $branch_code, PDO::PARAM_STR);
            $num = $stmnt->execute();
            if($num>0)
            {
                sendMail($email,$first_name,$xpassword,1);
            }

        }
        $leadarr=$obj->getLead($email);

        if(count($leadarr)>1)
        {
            $lead_id=$leadarr[0];
            $charged_amnt=$leadarr[2];
            $username=$leadarr[1];
//echo "<script>alert($username.$lead_id.'..Error :-'.$email);</script>";
            if($obj->addClaim($first_name,$last_name,$email,$phone,$medical_scheme,$aid_id,$username,$charged_amnt,$lead_id))
            {

            }
        }

    }
    catch (Exception $e)
    {
        //echo "<script>alert('Error : '.$e);</script>";
sendMail1("tendai@medclaimassist.co.za","There is an error on promoting new Lead".$e->getMessage(),$email);
    }
    finally
    {

    }
}
elseif($id==2)
{
    try {

        $claim_id=(int)$_GET["claim_id"];
        $author=$_GET["author"];
        $notes=$_GET["notes"];
        $username=$_SESSION["user_id"];
        $claim_number=getClaimNumber($claim_id);
        if($_SESSION["level"]=="claims_specialist")
        {
            $email=getQAEmail($claim_id);
        }
        else{
            $email=getUserEmail($claim_id);
        }
        echo insertQANotes($claim_id,$notes,$username);
        $body="<u>Notes</u><br><br>$notes<br><br>MCA Mailer";
        sendMail1($email,$body,$claim_number);
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
elseif($id==3)
{
    try {
        $claim_id=(int)$_GET["claim_id"];
        $upd=(int)$_GET["upd"];
        echo "uppp===".$upd;
        $dt=date("Y-m-s H:i:s");
        $username=$_SESSION["user_id"];
        $notes="Claims Specialist accepted the outcome.";
        $claim_number=getClaimNumber($claim_id);
        if($_SESSION["level"]=="claims_specialist")
        {
            $email=getQAEmail($claim_id);
        }
        else{
            $email=getUserEmail($claim_id);
        }

        $stmupd=$conn->prepare("UPDATE quality_assurance SET cs_signed=:signed,cs_date=:cs_date WHERE claim_id=:claim_id");
        $stmupd->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmupd->bindParam(':signed', $upd, PDO::PARAM_STR);
        $stmupd->bindParam(':cs_date', $dt, PDO::PARAM_STR);
        $rt=$stmupd->execute();
        if($rt==1)
        {
            echo "Successfully updated";
            insertQANotes($claim_id,$notes,$username);
            $body="<u>Notes</u><br><br>$notes<br><br>MCA Mailer";
            sendMail1($email,$body,$claim_number);
        }
        else
        {
            echo "Failed to update";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}

///Functions here
function insertQANotes($claim_id,$notes,$username)
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO qa_notes(claim_id,notes, entered_by) VALUES(:claim,:notes,:owner)');
    $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
    $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
    return $stmt->execute();
}
function generatePassword()
{
    $arr1 = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    $arr2 = ['R','S', 'B', '4', 'T', 'N'];
    $random = rand(0, 25);
    $random1 = rand(0, 5);
    $random2 = rand(0, 9);
    $password = $random . ucfirst($arr1[$random]) . $random1 . $arr2[$random1] . $random2;
    return $password;
}
function sendMail($mymail,$name,$xpassword,$num){
    global $mail;
    $data=getEncrpass();
    $from=$data[0];
    $username=$data[0];
    $password=$data[1];
    $title="Medclaim Assist";

    // Passing `true` enables exceptions
    try {


        //Server settings
        //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $username;                 // SMTP username
        $mail->Password = $password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom($from,$title);
        $mail->addAddress($mymail, 'User');     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Med ClaimAssist login details";
        //$mail->AddEmbeddedImage('insuremed1.png', 'logo_2u');
        if($num==1) {
            $bbody = "Dear " . $name . "<br><br>Welcome and thank you for registering to our service . Your <b>Med ClaimAssist</b> profile has now been created. 
<br><br>The best way to use our services is to login to your account on our web site. <a href='https://medclaimassist.co.za/login'>Click here to login</a><br><br>
Your login details for <b>Med ClaimAssist System</b> are as follows:
<br>Username : " . $mymail . "<br>Password : " . $xpassword . "<br><br>
Once you have logged in you will be able to change your password to whatever you wish.<br><br>
The service allows you to log your claim requests for assistance after login, and to track where we are in resolving your enquiry. The direct contact details of the Claims Specialist assigned to helping you will be provided through the web site on each request that you log - please don't hesitate to make contact should you need any feedback, further information, or clarification. This service will be provided by Med ClaimAssist.
<br><br>
<a href='https://medclaimassist.co.za/login'>Click here to login</a><br><br>Yours sincerely<br><br>The Medclaim Assist Team";
        }
        else
        {
            $bbody="Dear " . $name . "<br><br>You already have Medclaim Assist account, you may click the link below and login. <br><br><a href='https://medclaimassist.co.za/login'>Click here to login</a><br><br>Yours sincerely<br><br>The Medclaim Assist Team";

        }
        $mail->Body = $bbody;
        //$mail->AddAttachment('documents/' . getConsentName($scheme));
        //$mail->send();

        if (!$mail->send()) {
            //echo "Mailer Error: ";
        } else {
        }
    }

    catch (Exception $e)
    {

    }
}
function sendMail1($mymail,$body,$claim_number){
    global $mail;
    $data=getEncrpass();
    $from=$data[0];
    $username=$data[0];
    $password=$data[1];
    // Passing `true` enables exceptions
    try {                                // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $username;                 // SMTP username
        $mail->Password = $password;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                   // TCP port to connect to
        //Recipients
        $mail->setFrom($from,"User");
        $mail->addAddress($mymail, 'User');    // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "QA Update ($claim_number)";
        $mail->Body = $body;
        if (!$mail->send()) {
            //echo "Mailer Error: ";
        } else {
        }
    }

    catch (Exception $e)
    {

    }
}
function getUserEmail($claim_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT email FROM claim as a INNER JOIN users_information as b ON a.username=b.username WHERE a.claim_id=:claim_id");
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}
function getQAEmail($claim_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT email FROM quality_assurance as a INNER JOIN users_information as b ON a.entered_by=b.username WHERE a.claim_id=:claim_id");
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}
function getEncrpass()
{
    global $conn;
    $stmt = $conn->prepare("SELECT notification_email,notification_password FROM email_configs");
    $stmt->execute();
    return $stmt->fetch();
}
function getValue($order_id,$val)
{
    global $conn1;
    $stmt = $conn1->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=:post_id AND meta_key=:meta_key");
    $stmt->bindParam(':post_id', $order_id, PDO::PARAM_STR);
    $stmt->bindParam(':meta_key', $val, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount()>0?$stmt->fetchColumn():"";
}
function getClaimNumber($claim_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT claim_number FROM claim WHERE claim_id=:claim_id");
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>