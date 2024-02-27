<?php
if(!isset($_POST['claim_id']))
{
    die("Invalid access");
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
//error_reporting(0);
session_start();
define("access",true);
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    $claim_id = (int)$_POST['claim_id'];
    $username=$control->loggedAs();
    $claim_data=$control->viewSingleClaim($claim_id);
    $claims_specialist = $claim_data["username"];
    if($username==$claims_specialist || $control->isTopLevel())
    {

    }
    else{
        die("Invalid access");
    }
    $claim_number=$claim_data["claim_number"];
    $member_email = $claim_data["email"];
    $scheme_name = $claim_data["medical_scheme"];
    $member_name = $claim_data["first_name"];
    $member_surname = $claim_data["surname"];
    $gap_cover_name = $claim_data["client_name"];
    $member_full_name = ucwords($member_name . " " . $member_surname);
    $member_number = $claim_data["scheme_number"];
    $date = date("Y-m-d H:i:s");
    $reminder_time = '0000-00-00 00:00:00';
    $reminder_status = 0;
    $data = $control->viewUser($claims_specialist);
    if (empty($member_email)) {
        die("Noo Email Address");
    } elseif ($control->viewConsent($scheme_name,$claims_specialist)==false) {
        die("No Consent Form found");
    } elseif (empty($data['email_password'])) {
        die("Email Address not configured, plaese contact system administrator");
    }

    else {
        if (filter_var($member_email, FILTER_VALIDATE_EMAIL)) {
            $notes = "Consent sent.";
            $claim_specialist_password=$control->decryptIt($data['email_password']);
            $subject = $member_full_name . " - " . $claim_number . " - M/A " . $member_number;
            $arrv = explode(' ',trim($member_name));
            $first_name = $arrv[0];
            $body = consentEmail($first_name, $gap_cover_name, $scheme_name, $claims_specialist);
            $consent_data=$control->viewConsent($scheme_name,$claims_specialist);
            $attachment_path="../../mca/schemes/" . $claims_specialist.$consent_data["doc_name"];
            if($control->sendEmail($mail,$data['email'],$data['fullName'],$claim_specialist_password,$member_email,$member_name,$subject,$body,1,$attachment_path))
            {
                if($control->callInsertNotes($claim_id,$notes,$claims_specialist,$reminder_time,$reminder_status,0,"","",""))
                {
                    echo "Consent sent!";
                    $desc="Consent sent - ".$scheme_name;
                    $member_id=$claim_data["member_id"];
                    $descr=$claims_specialist."_".date("Y-m-d H:i:s");
                    $updatemember=$control->callUpdateMemberKey($member_id,"consent_descr",$descr);
                    $logs=$control->callInsertNoteLog(0,$claim_id,$desc,$date,$claims_specialist);
                    //$control->callUpdateMm($claim_id,$descr);
                }
                else
                {
                    echo "Failed to update notes";
                }

            }
            else
            {
                echo "Failed to send consent";
            }
        }
        else
        {
            echo "Invalid email address";
        }
    }

} catch (Exception $e) {
    echo "There is an error ".$e;
}