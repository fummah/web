<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

session_start();
if(isset($_SESSION['level']) || !empty($_SESSION['level']))
{
    if($_SESSION['level']!="admin")
    {
        die("Access Denied");
    }

}
else if(isset($_SESSION['mca_role']) || !empty($_SESSION['mca_role']))
{
    if($_SESSION['mca_role']!="broker")
    {
        die("Access Denied");
    }

}
else
{
    die("Access Denied");
}
require_once 'Classes/PHPExcel.php';

include ("../../../mca/link2.php");

$errorArray="<b style='color: green'>Success</b>";
$total=0;
$failed=0;
$succeed=0;

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../bootstrap3/css/bootstrap.min.css">
    <script src="../jquery/jquery.min.js"></script>
    <script src="../bootstrap3/js/bootstrap.min.js"></script>

    <script src="../js/jquery-1.12.4.js"></script>
    <style>
        .b{
            width:300px;
            border-color:#00b3ee;
        }
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(../images/Preloader_2.gif) center no-repeat #fff;
        }

    </style>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script>
        //paste this code under the head tag or in a separate js file.
        // Wait for window load
        $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");

        });

        function onLoad() {
            document.getElementById('loadingmsg').style.display = 'block';
            document.getElementById('myMain').style.display = 'none';
        }
    </script>
</head>

<body>
<div class="se-pre-con"></div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="onLoad()">

    <table align="center">
        <caption><h4 style="color: mediumseagreen" align="center">Upload Web Clients</h4></caption>
        <tr>
            <td><input type="file" id="myFile" name="myFile" class="form-control b"></td>
            <td><button class="btn btn-info" name="upload" id="upload"> <span id="myMain"><span style="color: white" class="glyphicon glyphicon-open"></span><b> Upload Now</b></span><span id='loadingmsg' style='display: none;color: red;font-weight: bolder'>please wait...</span></button>

            </td>
        </tr></table>
</form>

</body>
</html>
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
        $mail->addAddress($mymail, $name);     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Med ClaimAssist login details";

        $mail->Body = "Dear ".$name."<br><br>You have been added as a client to the Med ClaimAssist system - welcome and thank you for subscribing to our service. 
<br><br>The best way to use our services is to login to your account on our web site. <a href='https://medclaimassist.co.za/login/'>Click here to login</a><br><br>
Your login details for Med ClaimAssist System are as follows : 
<br>Username : ".$mymail."<br>Password : ".$password."<br><br>
Once you have logged in you will be able to change your password to whatever you wish.<br><br>
The service allows you to log your requests for assistance after login, and to track where we are in resolving your problem.  The direct contact details of the Claims Specialist assigned to helping you will be provided through the web site on each request that you log - please don't hesitate to make contact should you need any feedback, further information, or clarification.
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
            //echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
        }
    }

    catch (Exception $e)
    {

    }
}
function retrieveData($fileName)
{

    $tmfname=$fileName;
    $excelReader=PHPExcel_IOFactory::createReaderForFile($tmfname);

    $excelObj=$excelReader->load($tmfname);
    $worksheet=$excelObj->getActiveSheet();
    $lastRow=$worksheet->getHighestRow();
    $ll_arr['xx'][]=array("name"=>"",
        "email"=>"",
        "password"=>"");

    echo"<table class='table table-striped' align='center' width='90%'>";

    for($row=2;$row<=$lastRow;$row++)
    {
        $name=$worksheet->getCell('A'.$row)->getValue();

        $surname=$worksheet->getCell('B'.$row)->getValue();
        $id_number=$worksheet->getCell('C'.$row)->getValue();
        $dob=$worksheet->getCell('D'.$row)->getValue();
        $email=$worksheet->getCell('E'.$row)->getValue();
        $contact_number=$worksheet->getCell('F'.$row)->getValue();
        $medical_scheme=$worksheet->getCell('G'.$row)->getValue();
        $scheme_option=$worksheet->getCell('H'.$row)->getValue();
        $medical_aid_number=$worksheet->getCell('I'.$row)->getValue();
        $physical_address1=$worksheet->getCell('J'.$row)->getValue();
        $physical_address2=$worksheet->getCell('K'.$row)->getValue();
        $entered_by=$worksheet->getCell('L'.$row)->getValue();
        $account_holder=$worksheet->getCell('M'.$row)->getValue();
        $account_number=$worksheet->getCell('N'.$row)->getValue();
        $bank_name=$worksheet->getCell('O'.$row)->getValue();
        $branch_code=$worksheet->getCell('P'.$row)->getValue();
        if(getUseremail($email))

        {
            continue;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {


            $name=filter_var($name, FILTER_SANITIZE_STRING);
            $surname=filter_var($surname, FILTER_SANITIZE_STRING);
            $id_number=filter_var($id_number, FILTER_SANITIZE_STRING);
            $dob=filter_var($dob, FILTER_SANITIZE_STRING);
            $email=filter_var($email, FILTER_SANITIZE_STRING);
            $contact_number=filter_var($contact_number, FILTER_SANITIZE_STRING);
            $medical_scheme=filter_var($medical_scheme, FILTER_SANITIZE_STRING);
            $scheme_option=filter_var($scheme_option, FILTER_SANITIZE_STRING);
            $medical_aid_number=filter_var($medical_aid_number, FILTER_SANITIZE_STRING);
            $physical_address1=filter_var($physical_address1, FILTER_SANITIZE_STRING);
            $physical_address2=filter_var($physical_address2, FILTER_SANITIZE_STRING);
            $entered_by=filter_var($entered_by, FILTER_SANITIZE_STRING);
            $account_holder=filter_var($account_holder, FILTER_SANITIZE_STRING);
            $account_number=filter_var($account_number, FILTER_SANITIZE_STRING);
            $bank_name=filter_var($bank_name, FILTER_SANITIZE_STRING);
            $branch_code=filter_var($branch_code, FILTER_SANITIZE_STRING);

            /*echo "Fumaaa".$surname;
            $conn = cnn("mca", "MCA_admin");
            $f=$conn->prepare('SELECT *FROM web_clients');
            $f->execute();
            $r=$f->fetch();
            echo "Mugove".$r[2];
            */

            $xpassword=generatePassword();

            if(insertDB($name, $surname, $id_number, $dob, $email, $contact_number, $medical_scheme, $scheme_option, $medical_aid_number, $physical_address1, $physical_address2, $entered_by,$xpassword,$account_holder,$account_number,$bank_name,$branch_code))
            {

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if (in_array($email, $ll_arr))
                    {

                    }
                    else
                    {
                        sendMail($email,$name,$xpassword);
                        $single=array(
                            "name"=>$name,
                            "email"=>$email,
                            "password"=>$xpassword
                        );
                        $ll_arr['xx'][]= $single;
                    }


                }
            }
            echo "<tr><td>";
            echo $name;
            echo "</td><td>";
            echo $surname;
            echo "</td><td>";
            echo $id_number;
            echo "</td><td>";
            echo $dob;
            echo "</td><td>";
            echo $email;
            echo "</td><td>";
            echo $contact_number;
            echo "</td><td>";
            echo $medical_scheme;
            echo "</td><td>";
            echo $scheme_option;
            echo "</td><td>";
            echo $medical_aid_number;
            echo "</td><td>";
            echo $physical_address1;
            echo "</td><td>";
            echo $physical_address2;
            echo "</td><td>";
            echo $entered_by;
            echo "</td><td>";
            echo $GLOBALS['errorArray'];
            echo "</td></tr>";
        } else {
            echo("Invalid Row");
        }

    }
    $tot=$GLOBALS['total'];
    $suc=$GLOBALS['succeed'];
    $fal=$GLOBALS['failed'];
    echo "<caption><h4 align=\"center\">Total : <span style='color:deepskyblue'>$tot</span> --  Success : <span style='color: green'>$suc</span> -- Failed : <span style='color: red'>$fal</span></h4></caption>";
    echo"</table>";

    foreach ($ll_arr["xx"] as $link)
    {
        if (!empty($link["email"]))
        {
            $myMail=$link["email"];
            $myName=$link["name"];
            $myPassword=$link["password"];
            // echo $link["name"]."=========".$link["email"]."===================".$link["password"]."<br>";
            //sendMail($myMail,$myName,$myPassword);
        }

    }
}
function getEncrpass()
{

    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT notification_email,notification_password FROM email_configs");
    $stmt->execute();
    return $stmt->fetch();
}

function getUseremail($email)
{
    $xca=false;
    try{
        $conn = cnn("mca", "MCA_admin");
        $sql = $conn->prepare('SELECT *from web_clients WHERE email=:email');
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        $nu = $sql->rowCount();

        if ($nu >0) {

            $xca=true;

        }

    }
    catch (Exception $e)
    {
        $xca=true;
    }

    return $xca;
}

function insertDB($name, $surname, $id_number, $dob, $email, $contact_number, $medical_scheme, $scheme_option, $medical_aid_number, $physical_address1, $physical_address2, $entered_by,$pass,$account_holder,$account_number,$bank_name,$branch_code)
{

    $value=false;
    $GLOBALS['total']+=1;

    $encryp_pass = password_hash($pass, PASSWORD_BCRYPT);
    $password=$encryp_pass;
    $role="client";
    $myR=$_SESSION['mca_role'];
    $broker_id=0;
    if($myR=="broker")
    {
        $broker_id=$_SESSION['mca_user_id'];
    }

    try {

        $conn = cnn("mca", "MCA_admin");

        $sql = $conn->prepare('INSERT INTO web_clients(name, surname, id_number, dob, email, contact_number, medical_scheme, scheme_option, 
medical_aid_number, physical_address1, physical_address2, entered_by,role,broker_id,password,account_holder,account_number,bank_name,branch_code) 
VALUES (:name, :surname, :id_number, :dob, :email, :contact_number, :medical_scheme, :scheme_option, 
:medical_aid_number, :physical_address1, :physical_address2, :entered_by,:role,:broker_id,:password,:account_holder,:account_number,:bank_name,:branch_code)');
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':surname', $surname, PDO::PARAM_STR);
        $sql->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $sql->bindParam(':dob', $dob, PDO::PARAM_STR);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
        $sql->bindParam(':medical_scheme', $medical_scheme, PDO::PARAM_STR);
        $sql->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
        $sql->bindParam(':medical_aid_number', $medical_aid_number, PDO::PARAM_STR);
        $sql->bindParam(':physical_address1', $physical_address1, PDO::PARAM_STR);
        $sql->bindParam(':physical_address2', $physical_address2, PDO::PARAM_STR);
        $sql->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $sql->bindParam(':password', $password, PDO::PARAM_STR);
        $sql->bindParam(':role', $role, PDO::PARAM_STR);
        $sql->bindParam(':broker_id', $broker_id, PDO::PARAM_STR);
        $sql->bindParam(':account_holder', $account_holder, PDO::PARAM_STR);
        $sql->bindParam(':account_number', $account_number, PDO::PARAM_STR);
        $sql->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
        $sql->bindParam(':branch_code', $branch_code, PDO::PARAM_STR);
        $nu = $sql->execute();

        if ($nu == 1) {

            $GLOBALS['errorArray']="<b style='color: green'>Success</b>";
            $GLOBALS['succeed']+=1;
            $value= true;

        }
        else{
            $GLOBALS['errorArray']="<b style='color: red'>Failed</b>";
            $GLOBALS['failed']+=1;
            $value= false;
        }

    }
    catch (Exception $e)
    {
        $rre=$e->getMessage();
        $GLOBALS['errorArray']="<b style='color: red'>Error : $e->$rre</b>";
        $GLOBALS['failed']+=1;
        $value= false;
        echo "error";
    }
    return $value;

}
if(isset($_POST['upload'])) {
    if($_SESSION['level'] == "admin" || $_SESSION['mca_role']=="broker") {
        if (isset($_FILES["myFile"]) && is_file($_FILES['myFile']['tmp_name'])) {
            $allowedExts = ["xlsx", "xls", "txt"];
            $fileExtensions = ["vnd.ms-excel", "vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.oasis.opendocument.spreadsheet", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];
            $temp = explode(".", $_FILES["myFile"]["name"]);
            $presentExtention = end($temp);

            $target = "";
            $size = basename($_FILES['myFile']['size']);
            $type = basename($_FILES['myFile']['type']);
            $nname = basename($_FILES['myFile']['name']);
            $nux = substr_count($nname, '.');
            echo "<p align='center'>";
            if (in_array($presentExtention, $allowedExts) && $size < 10000000 && strlen($nname) < 100 && $nux == 1 && $size > 0) {
                if (in_array($type, $fileExtensions)) {
                    $target = $target . basename($_FILES['myFile']['name']);

                    if (move_uploaded_file($_FILES['myFile']['tmp_name'], $target)) {

                        echo "<span class=\"notice\" style=\"color: green\">Your file has been uploaded.</span>";

                        retrieveData($target);

                    } else {
                        echo "<span class=\"notice\" style=\"color: red\">Sorry, Failed to upload.</span>";
                    }
                } else {
                    echo "<span class=\"notice\" style=\"color: red\">Please select an excel file</span>";
                }
            } else {
                echo "<span class=\"notice\" style=\"color: red\">Please select valid file</span>";
            }

            echo "</p>";
        }
        else{
            echo "<span class=\"notice\" style=\"color: red\">Please select valid file</span>";
        }
    }
    else{
        echo "Invalid access";
    }

}
?>

