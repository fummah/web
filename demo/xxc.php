<?php

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
   

   
            if($control->sendEmail($mail,"new_case@medclaimassist.co.za","tendai","Action*6","tendai@medclaimassist.co.za","Tendai","Subject test","Test"))
            {
               
echo "Sent!!";
            }
            else
            {
                echo "Failed to send consent";
            }
       

} catch (Exception $e) {
    echo "There is an error ".$e;
}