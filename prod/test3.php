<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
 
try {
    $mail->SMTPDebug = 2;                                      
    $mail->isSMTP();                                           
    $mail->Host       = 'mail.thevotersvoices.com';                   
    $mail->SMTPAuth   = true;                            
    $mail->Username   = 'info@thevotersvoices.com';                
    $mail->Password   = 'UREi?UKUFgN5';                       
     $mail->SMTPSecure = 'tsl';            //Enable implicit TLS encryption
    $mail->Port       = 465; 
 
    $mail->setFrom('info@thevotersvoices.com', 'Name');          
    $mail->addAddress('tendai@medclaimassist.co.za');
    //$mail->addAddress('receiver2@gfg.com', 'Name');
      
    $mail->isHTML(true);                                 
    $mail->Subject = 'Subject';
    $mail->Body    = 'HTML message body in <b>bold</b> ';
    $mail->AltBody = 'Body in plain text for non-HTML mail clients';
    $mail->send();
    echo "Mail has been sent successfully!";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
 
?>