<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//require 'vendor/autoload.php';
error_reporting(0);
session_start();
//DB Connection
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
    require_once('dbconn.php');
    $identity = validateXss($_GET['identity']);
    $conn = connection("mca", "MCA_admin");
    $conn1 = connection("doc", "doctors");
    $conn2 = connection("cod", "Coding");
//End
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        if($_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "admin")
        {
            $email = validateXss($_GET['email']);
            $scheme = htmlspecialchars($_GET['scheme']);
            $urName = validateXss($_GET['urName']);
            $arr = explode(' ',trim($urName));
            $urName = ucwords($arr[0]);
            $spName = validateXss($_GET['spName']);
            //$spName="FumaTendai";
            $gap = validateXss($_GET['gap']);
            $claim_id = $_SESSION['currentClaimid'];
            $claim_number = validateXss($_GET['claim_number']);
            $notes = "Consent sent.";
            $fulname = ucwords($urName . " " . $_GET['surname']);
            $member_number = validateXss($_GET['member_no']);
            date_default_timezone_set('Africa/Johannesburg');
            $date = date("Y-m-d h:i:s");
            $reminder_time = '0000-00-00 00:00:00';
            $reminder_status = 0;
            $data = getEmail($spName);
//echo $data['email']."---------------".decryptIt("sElMpk5q07efihwhGpjxYg==");
            if (empty($email)) {
                echo "Noo Email Address";

            } elseif (empty(getConsentDetails($scheme, $spName))) {
                echo "No Consent Form found";
            } elseif (empty($data['password'])) {
                echo "Email Address not configured, plaese contact system administrator";
            }

            else {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    //Server settings
                    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = $data['email'];                 // SMTP username
                    $mail->Password = decryptIt($data['password']);                           // SMTP password
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to

                    //Recipients

                    $mail->setFrom($data['email'], $data['fullname']);
                    $mail->addAddress($email, $urName);     // Add a recipient

                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = $fulname . " - " . $claim_number . " - M/A " . $member_number;
                    $mail->Body = emailString($urName, $gap, $scheme, $data['fullname']);
                    $mail->AddAttachment('../../mca/schemes/' . getConsentDetails($scheme, $spName));
                    //$mail->send();

                    if (!$mail->send()) {
                        echo "There is an error";

                    } else {
                        $stmt = $conn->prepare('INSERT INTO intervention(claim_id,intervention_desc, date_entered,owner,reminder_time,reminder_status) VALUES(:claim,:notes,:dat,:owner,:reminder_time,:reminder_status)');
                        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
                        $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
                        $stmt->bindParam(':dat', $date, PDO::PARAM_STR);
                        $stmt->bindParam(':owner', $spName, PDO::PARAM_STR);
                        $stmt->bindParam(':reminder_time', $reminder_time, PDO::PARAM_STR);
                        $stmt->bindParam(':reminder_status', $reminder_status, PDO::PARAM_STR);
                        $xx = $stmt->execute();
                        if ($xx == 1) {
                            echo "Consent sent!";
                            $desc="Consent sent - ".$scheme;
                            consentLog($claim_id,$desc,$spName);

                            $descr=$spName."_".date("Y-m-d H:i:s");
                            updateMm($claim_id,$descr);

                        } else {
                            echo "Consent sent but no note was made!";
                        }
                    }
                }
                else
                {
                    echo "Invalid email address";
                }
            }

        }
        else
        {
            echo "Invalid entry";
        }
    } catch (Exception $e) {
        echo "There is an error ".$e;
    }
}
else
{
    echo "Invalid entry";
}

function getConsentName($scheme_name)
{
    global $conn;
    $sql1 = $conn->prepare("SELECT description FROM schemes WHERE name=:name LIMIT 1");
    $sql1->bindParam(':name', $scheme_name, PDO::PARAM_STR);
    $sql1->execute();
    $nu1 = $sql1->rowCount();
    $name="";
    if ($nu1 > 0) {
        foreach ($sql1->fetchAll() as $row1) {
            $name=$row1[0];
        }
    }
    return $name;
}

function getEmail($username)
{
//DB Connection
    global $conn1;
    $sql1 = $conn1->prepare("SELECT email,fullName,phone,email_password FROM staff_users WHERE username=:num LIMIT 1");
    $sql1->bindParam(':num', $username, PDO::PARAM_STR);
    $sql1->execute();
    $nu1 = $sql1->rowCount();

    $out['email'] = "";
    $out['fullname'] = "";
    $out['phone'] = "";

    if ($nu1 > 0) {
        foreach ($sql1->fetchAll() as $row1) {
            $out['email'] = $row1[0];
            $out['fullname'] = $row1[1];
            $out['phone'] = $row1[2];
            $out['password'] = $row1[3];
        }
    }
    return $out;
}
function emailString($member_name,$gap,$scheme,$userName)
{
    $name=strtolower($member_name);
    $x=ucwords($name);
    if($gap=="Individual")
    {
        $gap="MedClaim Assist";
    }
    $mess="
Dear $x

 <br><br>

We refer to your Gap Cover claim sent to $gap.

 <br><br>

Our Claims Specialist will be dealing with $scheme, on your behalf in finalising this claim. The Scheme requires that the attached consent form be completed. This will allow us to contact your doctors to obtain details regarding your Prescribed Minimum Benefit (PMB) claim and have discussions with your Medical Scheme to support payment in line with PMB legislation.

 <br><br>

We have completed the fields (on the form) which pertain to us and request that you complete the fields which pertain to you and return it to us as soon as possible. When returning please ensure that the subject line of this email is not changed/edited as this is generated specifically for your claim.

 <br><br>

Be assured that we have your best interest at heart and will oversee the processing of the claim, in its entirety as quickly as possible.

 <br><br>

Please do not hesitate to contact us, should you have any queries or require further assistance.

 <br><br>

Kind regards <br>

$userName";

    return $mess;
}
function decryptIt( $q ) {
    $cryptKey="MCA201734X$";
    $qDecoded=openssl_decrypt($q,"AES-128-ECB",$cryptKey);
    return( $qDecoded );
}
function getConsentDetails($scheme,$owner)
{
    $del="";
    global  $conn;
    $stmt = $conn->prepare("SELECT doc_name FROM consent WHERE scheme = :scheme AND owner=:owner");
    $stmt->bindParam(':scheme', $scheme, PDO::PARAM_STR);
    $stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount()>0)
    {
        $del=$owner.$stmt->fetchColumn();
    }

    return $del;

}
function consentLog($claim_id,$desc,$owner)
{
    global $conn;
    $sql1 = $conn->prepare("INSERT INTO delete_logs(claim_id,description,owner) VALUES(:claim_id,:description,:owner)");
    $sql1->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $sql1->bindParam(':description', $desc, PDO::PARAM_STR);
    $sql1->bindParam(':owner', $owner, PDO::PARAM_STR);
    $sql1->execute();


}
function updateMm($claim_id,$descr)
{
    global $conn;
    $st=$conn->prepare("select member_id from claim where claim_id=:cn");
    $st->bindParam(':cn', $claim_id, PDO::PARAM_STR);
    $st->execute();
    $member_id=$st->fetchColumn();

    $sqlx = $conn->prepare("UPDATE member SET consent_descr=:descr WHERE member_id=:policy");
    $sqlx->bindParam(':policy', $member_id, PDO::PARAM_STR);
    $sqlx->bindParam(':descr', $descr, PDO::PARAM_STR);
    $sqlx->execute();
}
?>



