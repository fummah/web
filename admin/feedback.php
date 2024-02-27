<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//require 'vendor/autoload.php';


try{
    session_start();
error_reporting(0);

if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
    
}
else {
die("There is an error");
}
    $_SESSION['LAST_ACTIVITY'] = time();
    $username=$_SESSION['user_id'];
$username1=$_SESSION['fullname'];
    $claim_id=$_SESSION['currentClaimid'];
    date_default_timezone_set('Africa/Johannesburg');
    $date = date("Y-m-d h:i:s");
    $feedback=$_GET['feedback'];
    $feedback=filter_var($feedback, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
 $feedback=nl2br($feedback);
    $curl = curl_init();
    $op=0;
    if($_SESSION['level']=="claims_specialist")
    {
        $op=1;
    }
    require_once('dbconn.php');
    $conn=connection("mca","MCA_admin");
    $sql = $conn->prepare('INSERT INTO feedback(claim_id,description,owner,open) VALUES(:claim,:feedback,:owner,:open)');
    $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
    $sql->bindParam(':feedback', $feedback, PDO::PARAM_STR);
    $sql->bindParam(':owner', $username1, PDO::PARAM_STR);
    $sql->bindParam(':open', $op, PDO::PARAM_STR);
    $num2=$sql->execute();
    if($_SESSION['level']=="claims_specialist")
    {
        $_SESSION['getEmail']=getClientEmail();
    }
    myMailer($feedback);

    if ($num2==1){

        echo "Your feedback have been added to the system";

    } else {

        echo"Feedback not added";

    }
}
catch(Exception $e)
{
    echo("There is an error : ");
}

function myMailer($mess)
{
 $data=getEncrpass();
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $data[0];                 // SMTP username
        $mail->Password = $data[1];  
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom($data[0], 'Med Claim Assist Feedback Mailer');
        $mail->addAddress($_SESSION['getEmail'], 'System User');     // Add a recipient
        $mail->addCC($data[2], 'System Controller');     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Feedback from " . $_SESSION['user_id']."(".$_SESSION['ccNum'].")";
        $mail->Body    = $mess;

        $mail->send();

    } catch (Exception $e) {

    }

}

function getClientEmail()
{
    $xx=$_SESSION['client'];
    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT client_email FROM clients WHERE client_id = :name");
    $stmt->bindParam(':name', $xx, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getEncrpass()
{

    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT notification_email,notification_password,cc1 FROM email_configs");
    $stmt->execute();
    return $stmt->fetch();
}
?>



