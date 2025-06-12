<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer();
     
      $mail->isSMTP();    
      $mail->SMTPDebug = 2;                                  // Set mailer to use SMTP
      $mail->Host = 'smtp0001.neo.space';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = "marketing@islandpros.net";                 // SMTP username
      $mail->Password = "qazwsxedc1!";                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to
      $mail->setFrom("marketing@islandpros.net", "Test Fuma");
      $mail->addAddress("fummah3@gmail.com", "Dziva");     // Add a recipient
      
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = "Test from Island prs";
      $mail->Body = "Receive and forget";
      

      if (!$mail->send()) {
          echo "Send!!";
      }
      else {
          echo "Failed";
      }
?>