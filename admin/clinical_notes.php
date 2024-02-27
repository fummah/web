<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//require 'vendor/autoload.php';


try{
    session_start();
    //error_reporting(0);
    require_once('dbconn.php');
    $conn=connection("mca","MCA_admin");
    if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {

    }
    else {
        die("There is an error");
    }

    $_SESSION['LAST_ACTIVITY'] = time();
    $username=$_SESSION['user_id'];
    $claim_id=$_SESSION['currentClaimid'];
    date_default_timezone_set('Africa/Johannesburg');
    $date = date("Y-m-d h:i:s");
    $txtclinicalnote=$_GET['txtclinicalnote'];
    $ref1=(int)$_GET['ref1'];

    $txtclinicalnote=filter_var($txtclinicalnote, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $txtclinicalnote=nl2br($txtclinicalnote);
    $dd=getDet($claim_id);
    $claim_number=$dd[0];
    $csemail=$dd[1];
    $op=0;

    $sql = $conn->prepare('INSERT INTO clinical_notes(claim_id,description,owner,open) VALUES(:claim,:desc,:owner,:open)');
    $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
    $sql->bindParam(':desc', $txtclinicalnote, PDO::PARAM_STR);
    $sql->bindParam(':owner', $username, PDO::PARAM_STR);
    $sql->bindParam(':open', $op, PDO::PARAM_STR);
    $num2=$sql->execute();

    if ($num2==1){
        // echo $txtclinicalnote."------".$claim_number."------".$csemail;

        if($ref1==1)
        {
            $txtclinicalnote.="";
            updateClaim($claim_id,1);
        }
        insertNotes($claim_id,$txtclinicalnote,$username);
        myMailer($txtclinicalnote,$claim_number,$csemail);
        echo "Your note have been added to the system";

    } else {

        echo"Note not added";

    }
}
catch(Exception $e)
{
    echo("There is an error : ");
}

function myMailer($mess,$claim_number,$email)
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
        $mail->Password = $data[1];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom($data[0], 'MCA');
        $mail->addAddress($email, 'System User');     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Clinical Notes for Claim Number ($claim_number)";
        $mail->Body    = "Hi<br><br>".$mess."<br><br>MCA Mailer";

        $mail->send();

    } catch (Exception $e) {

    }

}


function getEncrpass()
{
    global $conn;
    $stmt = $conn->prepare("SELECT notification_email,notification_password FROM email_configs");
    $stmt->execute();
    return $stmt->fetch();
}
function getDet($claim_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT a.claim_number,b.email FROM claim as a INNER JOIN users_information as b ON a.username=b.username WHERE a.claim_id=:claim_id");
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch();
}
function updateClaim($claim_id,$op)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE claim SET Open=:op WHERE claim_id=:claim_id");
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':op', $op, PDO::PARAM_STR);
    $stmt->execute();

}
function insertNotes($claim_id,$notes,$username)
{
    global $conn;
    $stmt = $conn->prepare('INSERT INTO intervention(claim_id,intervention_desc, owner) VALUES(:claim,:notes,:owner)');
    $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
    $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
    $stmt->execute();
}
?>



