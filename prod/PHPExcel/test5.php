<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

session_start();

require_once 'Classes/PHPExcel.php';

include ("../../../mca/link.php");

$errorArray="<b style='color: green'>Succeed</b>";
$total=0;
$failed=0;
$succeed=0;

?>


<?php
//error_reporting(0);

function generatePassword()
{
    $arr1 = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    $arr2 = ['4', 't', 's', '9', '7', 'Q'];
    $random = rand(0, 25);
    $random1 = rand(0, 5);
    $random2 = rand(0, 9);
    $password = $random . ucfirst($arr1[$random]) . $random1 . $arr2[$random1] . $random2;
    return $password;
}
function sendMail($mymail,$name,$password){
    $mail = new PHPMailer(true);
    // Passing `true` enables exceptions
    try {


        //Server settings
        //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'notifications@medclaimassist.co.za';                 // SMTP username
        $mail->Password = 'W3$TviewOct17';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('notifications@medclaimassist.co.za', 'Med ClaimAssist');
        $mail->addAddress($mymail, $name);     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Med ClaimAssist login details";
        $mail->Body = "Dear ".$name."<br><br>You have been added as a client to the Med ClaimAssist system – welcome and thank you for subscribing to our service. 
<br><br>The best way to use our services is to login to your account on our web site. <a href='https://medclaimassist.co.za/login/'>Click here to login</a><br><br>
Your login details for Med ClaimAssist System are as follows : 
<br>Username : ".$mymail."<br>Password : ".$password."<br><br>
Once you have logged in you will be able to change your password to whatever you wish.<br><br>
The service allows you to log your requests for assistance after login, and to track where we are in resolving your problem.  The direct contact details of the Claims Specialist assigned to helping you will be provided through the web site on each request that you log – please don’t hesitate to make contact should you need any feedback, further information, or clarification.
<br><br>
<a href='https://medclaimassist.co.za/login/'>Click here to login</a><br><br>Yours sincerely<br>The Med ClaimAssist Team";
        //$mail->AddAttachment('documents/' . getConsentName($scheme));
        //$mail->send();

        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
        }
    }

    catch (Exception $e)
    {
echo "There is an error";
    }
}
try {
    $conn = connection("mca", "MCA_admin");
    $f = $conn->prepare('SELECT DISTINCT email,name FROM web_clients WHERE client_id=183 OR client_id=207');
    $f->execute();
    foreach ($f->fetchAll() as $d) {
        $mymail = $d[0];
        $name = $d[1];
        $password = generatePassword();
    
       $en_pass = password_hash($password, PASSWORD_BCRYPT);
        $f = $conn->prepare('UPDATE web_clients SET password=:pass WHERE email=:email');
        $f->bindParam(':email', $mymail, PDO::PARAM_STR);
        $f->bindParam(':pass',$en_pass, PDO::PARAM_STR);
        $nu = $f->execute();
        if ($nu == 1) {
           
            sendMail($mymail, $name, $password);
            echo $mymail."<br>";

        } else {
            echo "Error<br>";

        }
      
    }
}
catch (Exception $fg)
{
    echo $fg;
}
?>