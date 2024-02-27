<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);
error_reporting(0);
session_start();
if((isset($_SESSION['mca_logxged']) && !empty($_SESSION['mca_logxged'])) || (isset($_SESSION['logxged']) && !empty($_SESSION['logxged']))) {
    require_once('../dbconn1.php');
    $id=validateXss($_GET['id']);
    $conn = connection("mca", "MCA_admin");
    $conn1 = connection("doc", "doctors");
    $conn2 = connection("cod", "Coding");
    /*** $message = a message saying we have connected ***/

    /*** set the error mode to excptions ***/

    if($id==2) {
        try {
            if($_SESSION['mca_role']=="broker" || isset($_SESSION['mca_admin'])) {
                $first_name = validateXss(ucwords($_GET['first_name']));
                $last_name = validateXss(ucwords($_GET['last_name']));
                $dob = validateXss($_GET['dob']);
                $id_num = validateXss($_GET['id_num']);
                $email = validateXss($_GET['email']);
                $phone = validateXss($_GET['phone']);
                $medical_scheme = validateXss($_GET['medical_scheme']);
                $scheme_option = validateXss($_GET['scheme_option']);
                $aid_id = validateXss($_GET['aid_id']);
                $addr_1 = validateXss($_GET['addr_1']);
                $addr_2 = validateXss($_GET['addr_2']);
                $role = validateXss("client");
                $val = validateXss($_GET['myVal']);
                $account_holder= validateXss($_GET['account_holder']);
                $account_number = validateXss($_GET['account_number']);
                $bank_name = validateXss($_GET['bank_name']);
                $branch_code = validateXss($_GET['branch_code']);
                $entered_by = "";
                $broker_id = "";
                if(isset($_SESSION['mca_admin']) && $val=="admin")
                {
                    $role = "broker";
                    $aid_id = $first_name . $last_name . $email;
                    $entered_by = "Admin";
                    $broker_id = "Admin";
                }
                else if($_SESSION['mca_role']=="broker" && $val=="broker")
                {
                    $entered_by = $_SESSION['mca_username'];
                    $broker_id = $_SESSION['mca_user_id'];
                }

                else
                {
                    echo"<script>alert('Invalid entry');</script>";
                    exit;
                }

                $xpassword = generatePassword();
                $encryp_pass = password_hash($xpassword, PASSWORD_BCRYPT);

                $password = $encryp_pass;
                $mess = "";
                $stmnt = $conn->prepare('INSERT INTO web_clients(name, surname, id_number, dob, email, contact_number, medical_scheme, scheme_option, medical_aid_number, 
physical_address1, physical_address2, role, broker_id, entered_by, password,account_holder,account_number,bank_name,branch_code) VALUES(:name, :surname, :id_number, :dob, :email, :contact_number, :medical_scheme, :scheme_option, :medical_aid_number, 
:physical_address1, :physical_address2, :role, :broker_id, :entered_by, :password,:account_holder,:account_number,:bank_name,:branch_code)');
                $stmnt->bindParam(':name', $first_name, PDO::PARAM_STR);
                $stmnt->bindParam(':surname', $last_name, PDO::PARAM_STR);
                $stmnt->bindParam(':id_number', $id_num, PDO::PARAM_STR);
                $stmnt->bindParam(':dob', $dob, PDO::PARAM_STR);
                $stmnt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmnt->bindParam(':contact_number', $phone, PDO::PARAM_STR);
                $stmnt->bindParam(':medical_scheme', $medical_scheme, PDO::PARAM_STR);
                $stmnt->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
                $stmnt->bindParam(':medical_aid_number', $aid_id, PDO::PARAM_STR);
                $stmnt->bindParam(':physical_address1', $addr_1, PDO::PARAM_STR);
                $stmnt->bindParam(':physical_address2', $addr_2, PDO::PARAM_STR);
                $stmnt->bindParam(':role', $role, PDO::PARAM_STR);
                $stmnt->bindParam(':broker_id', $broker_id, PDO::PARAM_STR);
                $stmnt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
                $stmnt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmnt->bindParam(':account_holder', $account_holder, PDO::PARAM_STR);
                $stmnt->bindParam(':account_number', $account_number, PDO::PARAM_STR);
                $stmnt->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
                $stmnt->bindParam(':branch_code', $branch_code, PDO::PARAM_STR);
                $num = $stmnt->execute();
                if ($num == 1) {
                    sendMail($email, $first_name, $xpassword);
                    $data=getEncrpass();
                    $mess = "<span class='alert alert-success'>Record Successfully added</span>";
                } else {
                    $mess = "<span class='alert alert-danger'>Failed to create.</span>";
                }
            }
            else
            {
                $mess="<span class='alert alert-danger'>Invalid access</span>";
            }
        }
        catch (Exception $e)
        {
            $mess="<span class='alert alert-danger'>There is an Error.</span>";
        }
        echo $mess;
    }
    else if($id==11)
    {

        if(isset($_SESSION['mca_logxged']) && !empty($_SESSION['mca_logxged']) && isset($_SESSION['mca_username']) && !empty($_SESSION['mca_username']) && isset($_SESSION['mca_scheme_number']) && !empty($_SESSION['mca_scheme_number']) && isset($_SESSION['mca_role']) && !empty($_SESSION['mca_role'])) {

            echo $_SESSION['mca_role'];
        }
        else
        {
            echo "login";
        }
    }

    else if($id==4)
    {

        echo "Invalid URL";
    }
    else if ($id==5)
    {

        try {

            $sys_broker_id="JJJ";
            $myId = $_SESSION['mca_user_id'];
            $array = array();
            $client_id = validateXss($_GET['client_id']);
            $stmnt = $conn->prepare('SELECT *FROM web_clients WHERE client_id=:id');
            $stmnt->bindParam(':id', $client_id, PDO::PARAM_STR);
            $stmnt->execute();

            $result = $stmnt->fetchAll();
            $ccc=$stmnt->rowCount();

            if($ccc>0) {
                $sys_broker_id=$result[0][13];
                if($client_id==$myId || $sys_broker_id==$myId) {
                    $json = json_encode($result, JSON_NUMERIC_CHECK);
                    echo $json;
                }
                else{
                    echo "Error";
                }
            }
            else
            {
                echo "Error";
            }
        } catch (Exception $e) {
            echo "Data Error";
        }

    }
    elseif($id==7)
    {
        $oldpass=$_GET["old_password"];
        $newpass=$_GET["new_password"];
        $mail=$_SESSION['mca_email'];
        try {
            $usernameEntered = validateXss($_POST['username']);
            $passwordEntered = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            $stmnt = $conn->prepare('SELECT email,password,client_id FROM web_providers WHERE email=:username LIMIT 1');
            $stmnt->bindParam(':username', $mail, PDO::PARAM_STR);
            $stmnt->execute();
            $num = $stmnt->rowCount();
            if ($num == 1) {
                $arr = $stmnt->fetch();
                $password = $arr[1];
                if (password_verify($oldpass, $password) && strlen($newpass)>6) {
                    $encryp_pass = password_hash($newpass, PASSWORD_BCRYPT);
                    $stmnt = $conn->prepare('UPDATE web_providers SET password=:passw WHERE email=:username LIMIT 1');
                    $stmnt->bindParam(':username', $mail, PDO::PARAM_STR);
                    $stmnt->bindParam(':passw', $encryp_pass, PDO::PARAM_STR);
                    $stmnt->execute();
                    echo "<span style='color:green'>Password Successfully updated</span>";
                }
                else{
                    echo "<span style='color:red'>Invalid password</span>";
                }
            }
            else
            {
                echo "<span style='color:red'>Something went wrong</span>";
            }
        }
        catch (Exception $e)
        {
            echo "<span style='color:red'>Error : </span>".$e->getMessage();
        }
    }
    elseif($id==8)
    {
        $txt=$_GET["feedback"];
        $mail1=$_SESSION['mca_email'];
        try {
            $stmnt = $conn->prepare("INSERT INTO web_feedback(email,feedback) VALUES(:email,:feedback)");
            $stmnt->bindParam(':email', $mail1, PDO::PARAM_STR);
            $stmnt->bindParam(':feedback', $txt, PDO::PARAM_STR);
            $nu=$stmnt->execute();
            if($nu==1)
            {
                sendFeedback("david@medclaimassist.co.za","David",$txt,$mail1);
                echo "<span style='color:green'>Feedback successfully sent</span>";
            }
            else{
                echo "<span style='color:red'>Something went wrong</span>";
            }
        }
        catch (Exception $e)
        {
            echo "<span style='color:red'>Error : </span>".$e->getMessage();
        }
    }
	   elseif($id==9)
    {
        $client_id=(int)$_GET["client_id"];
       
        try {
            $stmnt = $conn->prepare("INSERT INTO web_clients_deactivated(client_id, name, surname, id_number, dob, email, contact_number, medical_scheme, scheme_option, medical_aid_number, physical_address1, physical_address2, role, broker_id, entered_by, date_entered, password, temp_code, temp_code_time, coun, session_code, account_holder, account_number, bank_name, branch_code, subscription_rate, status) 
SELECT client_id, name, surname, id_number, dob, email, contact_number, medical_scheme, scheme_option, medical_aid_number, physical_address1, physical_address2, role, broker_id, entered_by, date_entered, password, temp_code, temp_code_time, coun, session_code, account_holder, account_number, bank_name, branch_code, subscription_rate, status FROM web_clients WHERE  client_id=:client_id");
            $stmnt->bindParam(':client_id', $client_id, PDO::PARAM_STR);           
            $nu=$stmnt->execute();
            if($nu==1)
            {
                echo "Member successfully deactivated";
				deleteDeactivated($client_id);
            }
            else{
                echo "Failed";
            }
        }
        catch (Exception $e)
        {
            echo "There is an error : ".$e->getMessage();
        }
    }
}
else
{
    echo "Invalid Access";
}
///Functions here
function generatePassword()
{
    $arr1 = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    $arr2 = ['R','S', 'B', '4', 'T', 'N'];
    $random = rand(0, 25);
    $random1 = rand(0, 5);
    $random2 = rand(0, 9);
    $password = $random . ucfirst($arr1[$random]) . $random1 . $arr2[$random1] . $random2;
    return $password;
}
//send mail function
function sendMail($mymail,$name,$password){
    global $mail;
    $data=getEncrpass();
    // Passing `true` enables exceptions
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
        $mail->setFrom($data[0], 'Med ClaimAssist');
        $mail->addAddress($mymail, 'System User');     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Med ClaimAssist login details";
        $mail->Body = "Dear ".$name."<br><br>You have been added as a client to the Med ClaimAssist system - welcome and thank you for subscribing to our service. 
<br><br>The best way to use our services is to login to your account on our web site. <a href='https://medclaimassist.co.za/login/'>Click here to login</a><br><br>
Your login details for Med ClaimAssist System are as follows : 
<br>Username : ".$mymail."<br>Password : ".$password."<br><br>
Once you have logged in you will be able to change your password to whatever you wish.<br><br>
The service allows you to log your requests for assistance after login, and to track where we are in resolving your problem.  The direct contact details of the Claims Specialist assigned to helping you will be provided through the web site on each request that you log - please don't  hesitate to make contact should you need any feedback, further information, or clarification.
<br><br>
<a href='https://medclaimassist.co.za/login/'>Click here to login</a><br><br>Yours sincerely<br>The Med ClaimAssist Team";
        if((int)$_SESSION['mca_user_id']==697)
        {
            $mail->Body = "Dear ".$name."<br><br>As a valued client of Angela Van Breda at AVB Financial Solutions, you now have access to Med ClaimAssist! Our unique system and experienced team are here to assist you with all of the below:
<br><br><ul>
<li>Health Claims queries: Claiming, identifying and correcting errors on doctors' invoices and medical scheme processing errors</li>
<li>Gap Cover Claims: Identifying claim shortfalls immediately and informing you of your options and assistance with the claims</li>
<li>Chronic Medicine Benefit Authorisation</li>
<li>Assistance with Pre-authorisation for procedures and investigations</li>
<li>Information on benefit usage and benefit impact on claims payments</li>
</ul><br><br>The best way to use our services is to login to your account on our web site. <a href='https://medclaimassist.co.za/login/'>Click here to login</a><br><br>
Your login details for Med ClaimAssist System are as follows : 
<br>Username : ".$mymail."<br>Password : ".$password."<br><br>
Once you have logged in you will be able to change your password to whatever you wish.<br><br>
You will be able to log all requests on the website, track your request and get regular real time feedback on your query and/or claim.
<br><br>The direct contact details of the Claims Specialist assigned to helping you will be provided through the web site on each request that you log - please don't hesitate to make contact should you need any feedback, further information, or clarification.<br><br>
<br>Alternatively, if you are struggling, you can contact Angelique from AVB Financial Solutions on 021 674 5030 or <a href = \"mailto: health@avb.co.za\">health@avb.co.za</a> for assistance.
<a href='https://medclaimassist.co.za/login/'>Click here to login</a><br><br>Yours sincerely<br>The Med ClaimAssist Team and Angela Van Breda";
        }
        //$mail->AddAttachment('documents/' . getConsentName($scheme));
        //$mail->send();

        if (!$mail->send()) {
            //echo "Mailer Error: ";
        } else {
        }
    }

    catch (Exception $e)
    {

    }
}

function sendFeedback($toemail,$name,$notes,$namefrom){
    global $mail;
    $data=getEncrpass();
    // Passing `true` enables exceptions
    try {

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $data[0];                 // SMTP username
        $mail->Password = $data[1];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                  // TCP port to connect to

        //Recipients
        $mail->setFrom($data[0], 'Med ClaimAssist');
        $mail->addAddress($toemail, $name);     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "ICD10 code feedback";
        $mail->Body = "Hi ".$name."<br><br>You have received below feeback from ICD10 code lookup. <br><br><b>$notes</b><br><b>From : $namefrom</b><br><br>Yours sincerely<br>The Med ClaimAssist Team";

        //$mail->AddAttachment('documents/' . getConsentName($scheme));
        //$mail->send();

        if (!$mail->send()) {
            //echo "Mailer Error: ";
        } else {
        }
    }

    catch (Exception $e)
    {
 echo "Error -> ".$e->getMessage();
    }
}
function getEncrpass()
{

    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT notification_email,notification_password FROM email_configs");
    $stmt->execute();
    return $stmt->fetch();
}
function deleteDeactivated($client_id)
{

    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("DELETE FROM web_clients WHERE client_id=:client_id");
	 $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
    $stmt->execute();    
}
?>