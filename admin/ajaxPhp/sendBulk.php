<?php

ini_set('max_execution_time', 300);
//require 'vendor/autoload.php';
//error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
session_start();
$_SESSION['start_db']=true;
//DB Connection
require_once('../dbconn1.php');
$identity = validateXss($_GET['identity']);
$conn = connection("mca", "MCA_admin");
$conn1=connection("doc","doctors");
//End

if($identity==1)
{
    $way=(int)$_GET["way"];
    $limit=(int)$_GET["limit"];
    $subject="Communication Request";
    $mail_arr=$way==1?getMembers($limit,$subject):$_GET["users"];
    $count=count($mail_arr);
    $entered_by="System";
    //$subject=htmlspecialchars($_GET["subject"]);
    //$body=nl2br(htmlspecialchars($_GET["body"]));
    $body="This is the email";
    if(strlen($subject)<4  ||  strlen($body)<4)
    {
        die("<span class='uk-alert-danger'>Invalid input!!!</span>");
    }
    if($count>0) {
        echo "<div id=\"table-wrapper\"><div id=\"table-scroll\"><table class='uk-table uk-table-small uk-table-striped text-muted'><thead><tr><th>Email Address</th><th>Valid Email?</th><th>Sent?</th><th>Saved?</th></tr></thead>";
        for ($i = 0; $i < $count; $i++) {
            $num=$i+1;
            $email = $mail_arr[$i];
            //$name=getUsers($email)["fullName"];

            //$xt="<br><br><a href='https://medclaimassist.co.za/response.php?email=$email&subject=$subject'>Click here</a> to give feedback.";
            $body = myBody($subject,$email);
            $status = (int)sendMail($email,$subject,$body);
            $vemail=filter_var($email, FILTER_VALIDATE_EMAIL)?"<span uk-icon=\"icon: check\" class='uk-icon-button uk-margin-small-right' style='color: green'></span>":"<span class='uk-icon-button uk-margin-small-right' uk-icon=\"icon: close\" style='color: red'></span>";
            $semail=(int)addEmails($email, $subject, $body, $entered_by, $status)==1?"<span class='uk-icon-button uk-margin-small-right' uk-icon=\"icon: check\" style='color: green'></span>":"<span class='uk-icon-button uk-margin-small-right' uk-icon=\"icon: close\" style='color: red'></span>";
            $memail=$status==1?"<span class='uk-icon-button uk-margin-small-right' uk-icon=\"icon: check\" style='color: green'></span>":"<span class='uk-icon-button uk-margin-small-right' uk-icon=\"icon: close\" style='color: red'></span>";
            //$memail=9;
            echo "<tr><td>$num. $email</td><td>$vemail</td><td>$semail</td><td>$memail</td></tr>";
        }
        echo "</table></div></div>";
    }
    else{
        die("<span class='uk-alert-danger'>No emails selected!!!</span>");
    }

}
elseif ($identity==2)
{
    $email=$_GET["email"];
    $subject=$_GET["subject"];
    $body=$_GET["body"];
    if(strlen($body)<2)
    {
        die("<div class='alert alert-danger'>Invalid input</div>");
    }
    if(select($email,$subject)>0)
    {
        $dat=date("Y-m-d H:i:s");
        $upd=update($email,$subject,$body,$dat)?"<div class='alert alert-success'>The response successfully sent!!!</div>":"<div class='alert alert-danger'>Invalid Inputs</div>";
        echo $upd;
        /*if(strlen(selectVal($email,$subject))>0)
        {
            echo "<div class='alert alert-danger'>Already updated</div>";
        }
        else
        {

        }
        */
    }
    else
    {
        echo "<div class='alert alert-danger'>Invalid Inputs</div>";
    }
}
function myBody($subject,$email)
{
    return "Hello,<br><br>You are receiving this email as a once-off from Med ClaimAssist (www.medclaimassist.co.za).  At one time or another over the past few years, we worked on one or more of your healthcare claims. 
<br><br>
We would like to send you emails from time to time on subjects of interest to you around healthcare insurance and medical aids that we believe will, at least, be interesting to you and could even help you when claiming from your medical aid or gap cover insurer.
<br><br>
If you would like to receive emails from us from time to time then <a href='https://medclaimassist.co.za/response.php?email=$email&subject=$subject'>click here</a> and you will be added to our mailing list.  If you do not want to receive any further emails from us then you need do nothing further.  Simply delete this mail and you will never hear from us again. 
<br><br>
In terms of the recently implemented Protection of Personal Information (POPI) Act, we need to inform you that we hold certain personal information about you as a result of our working on your claim.  We do not do anything with the information once we have finished working on your claim and we take the highest level of care possible to protect your information.  We are required to keep your information for a set number of years in terms of financial services legislation and regulations.<br><br>

Once again, if you would like to be added to our email list and receive useful and interesting information from us in future then <a href='https://medclaimassist.co.za/response.php?email=$email&subject=$subject'>click here</a>.<br><br>Yours sincerely<br>The Med ClaimAssist Team";

}
function sendMail($to_email,$subject,$body)
{
    $vval=0;
    if (filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        //$data=getEncrpass();

        $mail = new PHPMailer(true);                                          // Set mailer to use SMTP
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'info@medclaimassist.co.za';                 // SMTP username
        $mail->Password = 'P@ssw0rd2020!';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('info@medclaimassist.co.za', 'Med ClaimAssist');
        $mail->addAddress($to_email, $to_email);     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
//$mail->send();

        if (!$mail->send()) {
            $vval = 0;

        } else {
            $vval = 1;
        }
    }
    return $vval;
}

function getEmails($limit)
{
    global $conn;
    $arr=[];
    //$stmt=$conn->prepare('SELECT email FROM member ORDER BY member_id LIMIT '.$limit);
    $stmt=$conn->prepare('SELECT email FROM users_information ORDER BY id LIMIT '.$limit);
    $stmt->execute();
    foreach($stmt->fetchAll() as $row)
    {
        array_push($arr,$row[0]);
    }
    return $arr;
}//users_information
function addEmails($email,$subject,$body,$entered_by,$status)
{
    global $conn;
    $stmt=$conn->prepare('INSERT INTO bulk_emails(email,subject,body,entered_by,status) VALUES(:email,:subject,:body,:entered_by,:status)');
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':body', $body, PDO::PARAM_STR);
    $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    return $stmt->execute();
}
function update($email,$subject,$response,$ret)
{
    global $conn;
    $stmt=$conn->prepare('UPDATE bulk_emails SET response=:resp,response_time=:ret WHERE email=:email AND subject=:subject');
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':resp', $response, PDO::PARAM_STR);
    $stmt->bindParam(':ret', $ret, PDO::PARAM_STR);
    return $stmt->execute();
}
function select($email,$subject)
{
    global $conn;
    $stmt=$conn->prepare('SELECT email FROM bulk_emails WHERE email=:email AND subject=:subject');
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount();
}
function selectVal($email,$subject)
{
    global $conn;
    $stmt=$conn->prepare('SELECT response FROM bulk_emails WHERE email=:email AND subject=:subject');
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}
function getEncrpass()
{
    global $conn;
    $data["email"]="";
    $data["password"]="";
    $stmt = $conn->prepare("SELECT notification_email,notification_password FROM email_configs");
    $stmt->execute();
    foreach ($stmt->fetchAll() as $row)
    {
        $data["email"]=$row[0];
        $data["password"]=$row[1];
    }

    return $data;
}
function getUsers($email)
{
    global $conn1;
    $stmt = $conn1->prepare("SELECT fullName,username FROM staff_users WHERE email=:email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $data["fullName"]=$stmt->fetch()[0];
    $data["username"]=$stmt->fetch()[1];
    return $data;
}
function getMembers($limit,$subject)
{
    global $conn;
    $arr=[];
    $stmt = $conn->prepare("SELECT DISTINCT email FROM member where email NOT IN(SELECT email FROM bulk_emails where subject =:subject) AND LENGTH(email)>4 LIMIT ".$limit);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    //$stmt->bindParam(':body', $body, PDO::PARAM_STR);
    $stmt->execute();
    foreach($stmt->fetchAll() as $row)
    {
        array_push($arr,$row[0]);
    }
    return $arr;

}