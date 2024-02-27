<?php
session_start();
//error_reporting(0);
define("access",true);
if(!isset($_POST["claim_id"]))
{
    die("Invalid access");
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
if(!$control->isInternal())
{
    die("Invalid access");
}
$mail = new PHPMailer(true);
try{
    $_SESSION['LAST_ACTIVITY'] = time();
    $username=$control->loggedAs();
    $claim_id=(int)$_POST["claim_id"];
    date_default_timezone_set('Africa/Johannesburg');
    $date = date("Y-m-d h:i:s");
    $txtclinicalnote=$_POST['txtclinicalnote'];
    $ref1=(int)$_POST['ref1'];
    $txtclinicalnote=filter_var($txtclinicalnote, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $txtclinicalnote=nl2br($txtclinicalnote);
    $cliam_data=$control->viewSingleClaim($claim_id);
    $owner=$cliam_data["username"];
    $user_data=$control->viewUser($owner);
    $owner_email=$user_data["email"];
    $claim_number=$cliam_data["claim_number"];
    $op=0;
    if ($control->callInsertClinicalNotes($claim_id,$txtclinicalnote,$username,$op)==1){
           if($ref1==1)
        {
            $txtclinicalnote.="";
            $control->callUpdateClaimKey($claim_id,"Open",1);
        }
        if($ref1==77)
        {
            $owner_email="david@medclaimassist.co.za";
            $control->callUpdateClaimKey($claim_id,"Open",4);
            $control->callInsertNotes($claim_id,"This claim was sent for clinical review.",$username,"0000-00-00 00:00:00",0,"","","","");
        }
        else
        {
            $control->callInsertNotes($claim_id,$txtclinicalnote,$username,"0000-00-00 00:00:00",0,"","","","");

        }
        $subject="Clinical Notes for Claim Number ($claim_number)";
        $body="Hi<br><br>".$txtclinicalnote."<br><br>MCA Mailer";
        $email_data=$control->viewEmailCredentils();
        $from_email=$email_data["notification_email"];
        $from_password=$email_data["notification_password"];      
        $control->sendEmail($mail,$from_email,"MCA",$from_password,$owner_email,"MCA System User",$subject,$body);
        echo "Your note have been added to the system";

    } else {

        echo"Note not added";

    }
}
catch(Exception $e)
{
    echo("There is an error : ".$e->getMessage());
}
?>



