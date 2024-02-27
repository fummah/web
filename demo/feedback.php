<?php
session_start();
define("access",true);
if(!isset($_POST['claim_id']))
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
$mail = new PHPMailer(true);
    //$_SESSION['LAST_ACTIVITY'] = time();
    try{
        $username=$control->loggedAs();
    $claim_id=(int)$_POST['claim_id'];
     $claim_data=$control->viewSingleClaim($claim_id);
        $sys_username = $claim_data["username"];
     $claim_number=$claim_data["claim_number"];
     $client_email=$claim_data["client_email"];
     $user_data = $control->viewUser($sys_username);
    $role="claims_specialist";
    $sentoemail=$claim_data["email"];
    $date = date("Y-m-d H:i:s");
    $feedback=$_POST['feedback'];
    $feedback=filter_var($feedback, FILTER_UNSAFE_RAW);
    $feedback=nl2br($feedback);
    $op=0;
    if($role=="claims_specialist")
    {
        $op=1;
    }
   $call=$control->callInsertFeedback($claim_id,$feedback,$username,$op);
    if($role=="claims_specialist")
    {
        $sentoemail=$client_email;
    }
    $email_date=$control->viewEmailCredentils();
    $from_email=$email_date['notification_email'];
    $from_password=$email_date['notification_password'];
    $copy_email=$email_date['cc1'];
    $subject="Feedback from " . $username."(".$claim_number.")";
    if($control->sendEmail($mail,$from_email,"Med Claim Assist Feedback Mailer",$from_password,$sentoemail,"MedClaim Assist System User",$subject,$feedback,0,"",$copy_email))
                        {
                            echo "Your feedback have been added to the system";
                            }
     else {

        echo"Feedback not added";

    }
}
catch(Exception $e)
{
    echo("There is an error : ");
}

?>



