<?php
session_start();
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../admin/PHPMailer/src/Exception.php';
require '../admin/PHPMailer/src/PHPMailer.php';
require '../admin/PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);
if($_SESSION['mca_role']!="client")
{

    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    $r="<script>location.href = \"https://medclaimassist.co.za/login/\";</script>";
    die($r);
}
require_once('../admin/dbconn.php');
$conn = connection("mca", "MCA_admin");
$txt="";
$disable="";
$results="";
$scheme_number=$_SESSION['mca_scheme_number'];
function sendMail($mymail,$name,$claim){
    global $mail;
    // Passing `true` enables exceptions
    try {


        //Server settings
        //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'new_case@medclaimassist.co.za';                 // SMTP username
        $mail->Password = 'N3w_c@s310';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('new_case@medclaimassist.co.za', 'New Case MCA');
        $mail->addAddress($mymail, 'System User');     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "New Claim loaded - ".$claim;
        $mail->Body = "Hi ".$name."<br><br> You have received a new claim<br><br>Thank you.";
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
function getUsername()
{
    global $conn;
    $details['username']="";
    $details['email']="";

    $stmt=$conn->prepare('SELECT username,email FROM users_information WHERE status=1 ORDER BY datetime ASC LIMIT 1');
    $stmt->execute();
    $row=$stmt->fetch();
    $details['username']=$row['0'];
    $details['email']=$row['1'];

    return $details;
}
function checkScheme($medical_scheme)
{
    global $conn;

    $stmt=$conn->prepare('SELECT name FROM schemes WHERE name=:name1');
    $stmt->bindParam(':name1', $medical_scheme, PDO::PARAM_STR);
    $stmt->execute();
    $cc=$stmt->rowCount();
    if($cc<1)
    {
        $stmt=$conn->prepare('SELECT original_name FROM schemes_owl WHERE duplicate_name=:name1');
        $stmt->bindParam(':name1', $medical_scheme, PDO::PARAM_STR);
        $stmt->execute();
        $ccx=$stmt->rowCount();
        if($ccx>0)
        {
            $medical_scheme=$stmt->fetchColumn();
        }
        else{
            $medical_scheme="Unknown";
        }

    }
    return $medical_scheme;

}
function getBroker($id)
{
    global $conn;
    $stmt=$conn->prepare('SELECT name,surname FROM web_clients WHERE client_id=:iid');
    $stmt->bindParam(':iid', $id, PDO::PARAM_STR);
    $stmt->execute();
    $row=$stmt->fetch();
    $fullname=$row[0]." ".$row[1];
    return $fullname;
}
function fileUpload($claim_id,$username)
{
    if(isset($_FILES['file_array']))
    {
        global $results;
        $allowedExts= ['jpeg','jpg','png',"pdf","doc","docx","xlsx","xls","txt","PDF","PNG","msg","MSG","eml","EML","zip","ZIP","JPEG"];
        $fileExtensions = ['jpeg','jpg','png',"pdf","PDF","DOC","DOCX","XLSX","XLS","image/jpeg","image/PNG","application/pdf","vnd.openxmlformats-officedocument.spreadsheetml.sheet","image/png","vnd.ms-excel","msword","vnd.oasis.opendocument.text","vnd.openxmlformats-officedocument.spreadsheetml.sheet","image/png","application/vnd.openxmlformats-officedocument.wordprocessingml.document","vnd.openxmlformats-officedocument.wordprocessingml.document","vnd.ms-excel","msword","vnd.oasis.opendocument.text","application/pdf","PDF","PNG","msg","MSG","octet-stream","eml","EML","application/octet-stream","message/rfc822","rfc822","x-zip-compressed"];



        $name_array=$_FILES['file_array']["name"];
        // $name_array=filter_var($name_array, FILTER_SANITIZE_STRING);
        $type_array=$_FILES['file_array']["type"];
        $temp_array=$_FILES['file_array']["tmp_name"];
        $size_array=$_FILES['file_array']["size"];
        $error_array=$_FILES['file_array']["error"];

        for($i=0;$i<count($temp_array);$i++) {
            if ($i < 5) {
                if (!empty($temp_array[$i])) {
                    $rand = rand(10, 10000);
                    $name_arr = $name_array[$i];
                    $temp = explode(".", $name_arr);
                    $presentExtention = end($temp);
                    $fileExtension = $type_array[$i];
                    $nux = substr_count($name_arr, '.');
                    if (in_array($presentExtention, $allowedExts) && strlen($name_arr) < 100 && $nux == 1) {
                        if (in_array($fileExtension, $fileExtensions) && $size_array[$i] < 50000000 && $size_array[$i]>0) {

                            if (move_uploaded_file($temp_array[$i], "../../mca/documents/" . $rand . $name_array[$i])) {

                                $description = filter_var($name_array[$i], FILTER_SANITIZE_STRING);
                                $size = $size_array[$i];
                                $type = $type_array[$i];
                                $desc = "../admin/documents/" . $rand . $description;
                                DBaddfiles($description, $size, $type, $rand, $claim_id, $username);

                                //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
                                $results .="<span style='color: green;font-weight: bolder' class='glyphicon glyphicon-ok'></span> <form action='view_doc.php' method='post' target=\"print_popup\" onsubmit=\"window.open('view_doc.php','print_popup','width=1000,height=800');\"/>
<input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$description\">
</form><br>";

                            } else {
                                $results .= "<span style='color: red;font-weight: bolder' class='glyphicon glyphicon-remove'> Error -> </span> " . $name_array[$i] . "<br>";
                            }
                        } else {
                            $results .= "<span style='color: red;font-weight: bolder' class='glyphicon glyphicon-remove'> Error -> </span> " . $name_array[$i] . "<br>";
                        }
                    } else {
                        $results .= "<span style='color: red;font-weight: bolder' class='glyphicon glyphicon-remove'> Error -> </span> " . $name_array[$i] . "<br>";
                    }
                }

            }
            else
            {
                echo "You have exceed the number of files required";
                break;
            }
        }
    }
}

function DBaddfiles($description,$size,$type,$rand,$claim_id,$username)
{
    global $conn;
    $sql = $conn->prepare('INSERT INTO documents(claim_id,doc_description,doc_size,doc_type,randomNum,uploaded_by) VALUES(:claim,:description,:size,:type,:rand,:uploaded_by)');
    $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
    $sql->bindParam(':description', $description, PDO::PARAM_STR);
    $sql->bindParam(':size', $size, PDO::PARAM_STR);
    $sql->bindParam(':type', $type, PDO::PARAM_STR);
    $sql->bindParam(':rand', $rand, PDO::PARAM_STR);
    $sql->bindParam(':uploaded_by', $username, PDO::PARAM_STR);
    $sql->execute();

}
function addClaim($member_id,$claim_number,$username)
{

    global $conn;
    $ret=false;
    $insert = $conn->prepare('INSERT INTO `claim`(`member_id`,`claim_number`,`username`) VALUES (:member_id,:claim_number,:username)');
    $insert->bindParam(':member_id', $member_id, PDO::PARAM_STR);
    $insert->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
    $insert->bindParam(':username', $username, PDO::PARAM_STR);

    $success = $insert->execute();
    if($success==1)
    {
        $ret=true;
    }
    else{
        $ret=false;
    }
    return $ret;
}
?>
<html>
<head>
    <title>
        Client
    </title>
    <link rel="stylesheet" href="../admin/bootstrap3/css/bootstrap.min.css">

    <script src="../admin/bootstrap3/js/bootstrap.min.js"></script>
    <script src="../admin/jquery/jquery.min.js"></script>
    <script src="../admin/js/jquery-1.12.4.js"></script>

    <link href="../admin/w3/w3.css" rel="stylesheet" />

    <style>
        .bttn{
            background-attachment:scroll;
            background-clip:border-box;
            background-color:rgb(102, 204, 153);
            background-image:none;
            background-origin:padding-box;
            background-position-x:50%;
            background-position-y:50%;
            background-repeat-x:;
            background-repeat-y:;
            background-size:cover;
            border-bottom-color:rgb(102, 204, 153);
            border-bottom-left-radius:100px;
            border-bottom-right-radius:100px;
            border-bottom-style:solid;
            border-bottom-width:4px;
            border-image-outset:0px;
            border-image-repeat:stretch;
            border-image-slice:100%;
            border-image-source:none;
            border-image-width:1;
            border-left-color:rgb(102, 204, 153);
            border-left-style:solid;
            border-left-width:16px;
            border-right-color:rgb(102, 204, 153);
            border-right-style:solid;
            border-right-width:16px;
            border-top-color:rgb(102, 204, 153);
            border-top-left-radius:100px;
            border-top-right-radius:100px;
            border-top-style:solid;
            border-top-width:4px;
            box-shadow:rgba(0, 0, 0, 0.3) 0px 2px 18px 0px;
            box-sizing:border-box;
            color:rgb(255, 255, 255);
            cursor:pointer;
            display:inline-block;
            font-family:Montserrat, Helvetica, Arial, Lucida, sans-serif;
            font-size:14px;
            font-weight:500;
            height:50.375px;
            letter-spacing:1px;
            line-height:23.8px;
            margin-bottom:0px;
            margin-left:0px;
            margin-right:0px;
            margin-top:0px;
            outline-color:rgb(255, 255, 255);
            outline-style:none;
            outline-width:0px;
            padding-bottom:4.2px;
            padding-left:14px;
            padding-right:14px;
            padding-top:4.2px;
            position:relative;
            text-align:center;
            text-decoration-color:rgb(255, 255, 255);
            text-decoration-line:none;
            text-decoration-style:solid;
            text-size-adjust:100%;
            transition-delay:0s;
            transition-duration:0.3s;
            transition-property:all;
            transition-timing-function:ease;
            vertical-align:baseline;
            width:15%;
            -webkit-font-smoothing:

        }
        .hd{
            background-attachment:scroll;
            background-clip:border-box;
            background-color:rgba(0, 0, 0, 0);
            background-image:none;
            background-origin:padding-box;
            background-position-x:0%;
            background-position-y:0%;
            background-repeat-x:;
            background-repeat-y:;
            background-size:auto;
            border-bottom-color:rgb(51, 51, 51);
            border-bottom-style:none;
            border-bottom-width:0px;
            border-image-outset:0px;
            border-image-repeat:stretch;
            border-image-slice:100%;
            border-image-source:none;
            border-image-width:1;
            border-left-color:rgb(51, 51, 51);
            border-left-style:none;
            border-left-width:0px;
            border-right-color:rgb(51, 51, 51);
            border-right-style:none;
            border-right-width:0px;
            border-top-color:rgb(51, 51, 51);
            border-top-style:none;
            border-top-width:0px;
            box-sizing:border-box;
            color:rgb(51, 51, 51);

            font-family:Montserrat, Helvetica, Arial, Lucida, sans-serif;
            font-size:20px;
            font-weight:500;
            height:auto;
            line-height:30px;
            margin-bottom:0px;
            margin-left:0px;
            margin-right:0px;
            margin-top:0px;
            outline-color:rgb(51, 51, 51);
            outline-style:none;
            outline-width:0px;
            overflow-wrap:break-word;
            padding-bottom:10px;
            padding-left:0px;
            padding-right:0px;
            padding-top:0px;
            text-align:center;
            text-size-adjust:100%;
            vertical-align:baseline;
            width:auto;
            -webkit-font-smoothing:antialiased;
        }

        .subm{
            background-attachment:scroll;
            background-clip:border-box;
            background-color:rgb(102, 204, 153);
            background-image:none;
            background-origin:padding-box;
            background-position-x:50%;
            background-position-y:50%;
            background-repeat-x:;
            background-repeat-y:;
            background-size:cover;
            border-bottom-color:rgb(102, 204, 153);
            border-bottom-left-radius:3px;
            border-bottom-right-radius:3px;
            border-bottom-style:solid;
            border-bottom-width:2px;
            border-image-outset:0px;
            border-image-repeat:stretch;
            border-image-slice:100%;
            border-image-source:none;
            border-image-width:1;
            border-left-color:rgb(102, 204, 153);
            border-left-style:solid;
            border-left-width:2px;
            border-right-color:rgb(102, 204, 153);
            border-right-style:solid;
            border-right-width:2px;
            border-top-color:rgb(102, 204, 153);
            border-top-left-radius:3px;
            border-top-right-radius:3px;
            border-top-style:solid;
            border-top-width:2px;
            box-sizing:border-box;
            color:rgb(255, 255, 255);
            cursor:pointer;
            display:inline-block;
            font-family:Montserrat, Helvetica, Arial, Lucida, sans-serif;
            font-size:18px;
            font-weight:500;
            height:50px;
            line-height:30px;
            margin-bottom:0px;
            margin-left:0px;
            margin-right:0px;
            margin-top:0px;
            outline-color:rgb(255, 255, 255);
            outline-style:none;
            outline-width:0px;
            padding-bottom:6px;
            padding-left:20px;
            padding-right:20px;
            padding-top:6px;
            position:relative;
            text-align:center;
            text-decoration-color:rgb(255, 255, 255);
            text-decoration-line:none;
            text-decoration-style:solid;
            text-size-adjust:100%;
            transition-delay:0s;
            transition-duration:0.3s;
            transition-property:all;
            transition-timing-function:ease;
            vertical-align:baseline;
            width:223.969px;
            -webkit-font-smoothing:antialiased;
        }

    </style>

    <script type="text/javascript">
        //<![CDATA[
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
        //]]>
        function onLoad() {
            document.getElementById('loadingmsg').style.display = 'block';
        }
    </script>



</head>

<body>

<div class="row">
    <div class="col-sm-12">
        <?php require_once ("myHeader.php")?>
    </div>
</div>


<hr>
<?php

if(isset($_SESSION['mca_logxged']) && !empty($_SESSION['mca_logxged']) && $_SESSION['mca_role']=="client") {


    if (isset($_POST['upload_now']) && !empty($_POST['area1'])) {
        $dat = date('Y-m-d H:i:s');
        try {
            $stmt = $conn->prepare('SELECT *FROM web_clients WHERE client_id=:id');
            $stmt->bindParam(':id', $_SESSION['mca_user_id'], PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            $ccc=$stmt->rowCount();
            if($ccc>0) {
                $sys_id=$row[0];
                $name = $row[1];
                $surname = $row[2];
                $id_number = $row[3];
                $email = $row[5];
                $contact_number = $row[6];
                $medical_scheme = $row[7];
                $scheme_option = $row[8];
                $medical_aid_number = $row[9];
                $broker_id = $row[13];
                $brokername=getBroker($broker_id);
                if($_SESSION['mca_user_id']==$sys_id) {
                    $client_id = 4;
                    $open = 1;

                    //check duplicate
                    $scheme_number = $_SESSION['mca_scheme_number'];
                    $member_email=$_SESSION['mca_email'];
                    $dup = $conn->prepare('SELECT a.Open FROM claim as a inner join member as b ON a.member_id=b.member_id WHERE b.email=:number AND a.Open=1 AND b.client_id=4');
                    $dup->bindParam(':number', $member_email, PDO::PARAM_STR);
                    $dup->execute();
                    $opendup = $dup->rowCount();
                    if($opendup<1) {
                        //Generate claim Number

                        $stmt1 = $conn->prepare('SElECT a.claim_number FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=4 ORDER BY a.claim_number DESC LIMIT 1');
                        $stmt1->execute();
                        $row1 = $stmt1->fetch();
                        $newClaim = $row1[0];
                        $str = "1" . substr($newClaim, 3);
                        $str = $str + 1;
                        $finalG = substr($str, 1);
                        $claim_number = "MCA" . $finalG;
                        $policy_number = "-------------";
                        $username = getUsername();
                        $fullname = $name . " " . $surname;
                        $descr = filter_var($_POST['area1'], FILTER_SANITIZE_STRING);
                        $txt = $descr;

                        $checkMember_stmt=$conn->prepare('SELECT member_id,entered_by FROM member WHERE ((email=:email AND email<>"") AND (id_number=:id_number AND id_number<>"")) AND client_id=4 LIMIT 1');
                        $checkMember_stmt->bindParam(':email',$member_email,PDO::PARAM_STR);
                        $checkMember_stmt->bindParam(':id_number',$id_number,PDO::PARAM_STR);
                        //$checkMember_stmt->bindParam(':scheme_number',$scheme_number,PDO::PARAM_STR);
                        $checkMember_stmt->execute();
                        $count=$checkMember_stmt->rowCount();
                        $member_id=0;
                        if($count>0) {
                            $entered_with = $checkMember_stmt->fetch();
                            $member_id = $entered_with[0];

                        }
                        else {
                            $medical_scheme=checkScheme($medical_scheme);
                            $insertMember=$conn->prepare('INSERT INTO `member`(`client_id`, `policy_number`, `first_name`, `surname`, `email`, `telephone`, `id_number`, `scheme_number`,
 `medical_scheme`, `scheme_option`,broker) VALUES (:client_id,:policy_number,:first_name,:surname,:email,:telephone,:id_number,:scheme_number,:medical_scheme,
 :scheme_option,:broker)');
                            $insertMember->bindParam(':client_id',$client_id,PDO::PARAM_INT);
                            $insertMember->bindParam(':policy_number',$policy_number,PDO::PARAM_STR);
                            $insertMember->bindParam(':first_name',$name,PDO::PARAM_STR);
                            $insertMember->bindParam(':surname',$surname,PDO::PARAM_STR);
                            $insertMember->bindParam(':email',$member_email,PDO::PARAM_STR);
                            $insertMember->bindParam(':telephone',$contact_number,PDO::PARAM_STR);
                            $insertMember->bindParam(':id_number',$id_number,PDO::PARAM_STR);
                            $insertMember->bindParam(':scheme_number',$medical_aid_number,PDO::PARAM_STR);
                            $insertMember->bindParam(':medical_scheme',$medical_scheme,PDO::PARAM_STR);
                            $insertMember->bindParam(':scheme_option',$scheme_option,PDO::PARAM_STR);
                            $insertMember->bindParam(':broker',$brokername,PDO::PARAM_STR);
                            $result=$insertMember->execute();

                            if($result==1) {
                                $checkClaim=$conn->prepare('SELECT MAX(member_id) FROM member WHERE first_name=:first_name');
                                $checkClaim->bindParam(':first_name', $name, PDO::PARAM_STR);
                                $checkClaim->execute();
                                $ccc=$checkClaim->rowCount();

                                if($ccc>0) {
                                    $member_id=$checkClaim->fetchColumn();
                                }
                                else {
                                    $member_id=0;
                                }

                            }

                        }
                        $member_id=(int)$member_id;

                        if($member_id>0) {
                            $myusername=$username['username'];
                            if(addClaim($member_id,$claim_number,$myusername))
                            {
                                $stmt1 = $conn->prepare('UPDATE users_information SET datetime =:dat WHERE username=:user');
                                $stmt1->bindParam(':dat', $dat, PDO::PARAM_STR);
                                $stmt1->bindParam(':user', $username['username'], PDO::PARAM_STR);
                                $stmt1->execute();
                                $stmt2 = $conn->prepare('SELECT claim_id FROM claim WHERE claim_number=:claim ORDER BY claim_id DESC LIMIT 1');
                                $stmt2->bindParam(':claim', $claim_number, PDO::PARAM_STR);
                                $stmt2->execute();
                                $claim_id = $stmt2->fetchColumn();
                                $insertFeedback = $conn->prepare('INSERT INTO feedback(claim_id,description,owner) VALUES (:id,:descr,:owner)');
                                $insertFeedback->bindParam(':id', $claim_id, PDO::PARAM_STR);
                                $insertFeedback->bindParam(':descr', $descr, PDO::PARAM_STR);
                                $insertFeedback->bindParam(':owner', $fullname, PDO::PARAM_STR);
                                $insertFeedback->execute();
                                sendMail($username['email'], $username['username'], $claim_number);
                                fileUpload($claim_id, $fullname);

                                $disable = "disabled";
                                ?>
                                <p class="alert alert-success alert-dismissible" align="center"
                                   style="padding-right:25px;padding-left: 25px">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Success!</strong> New Claim created, will get back to you shortly.
                                </p>
                                <?php
                            }
                            else
                            {
                                ?>
                                <p class="alert alert-danger alert-dismissible" align="center"
                                   style="padding-right:25px;padding-left: 25px">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Failed!</strong> No claim was created.
                                </p>


                                <?php
                            }
                        }
                        else {
                            ?>
                            <p class="alert alert-danger alert-dismissible" align="center"
                               style="padding-right:25px;padding-left: 25px">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Failed!</strong> No claim was created.
                            </p>


                            <?php
                        }
                    }
                    else
                    {
                        ?>
                        <p class="alert alert-danger alert-dismissible" align="center"
                           style="padding-right:25px;padding-left: 25px">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Failed!</strong> You still have an open claim.
                        </p>
                        <?php
                    }
                }
                else
                {
                    echo "Invalid access";
                }
            }
            else{
                echo "Invalid Account";
            }

        } catch (Exception $e) {
            ?>
            <p class="alert alert-danger alert-dismissible" align="center"
               style="padding-right:25px;padding-left: 25px">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>There is an error!!!</strong> No claim created.
            </p>
            <?php

        }
    } else {
        $scheme_number = $_SESSION['mca_scheme_number'];
        $member_email=$_SESSION['mca_email'];
        $stmt = $conn->prepare('SELECT a.Open FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.email=:number AND a.Open=1');
        $stmt->bindParam(':number', $member_email, PDO::PARAM_STR);
        $stmt->execute();
        $open = $stmt->rowCount();

        if ($open > 0) {
            $disable = "disabled";
            ?>
            <p class="alert alert-warning alert-dismissible" align="center"
               style="padding-right:25px;padding-left: 25px">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Warning!</strong> You still have open claim and you can't add a new one, please <a
                        href="client_claims.php"><b>View your Claims</b></a> to check the progress.
            </p>
            <?php
        }

    }
    $member_email=$_SESSION['mca_email'];
    $stmtx = $conn->prepare('SELECT a.Open FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.email=:number');
    $stmtx->bindParam(':number', $member_email, PDO::PARAM_STR);
    $stmtx->execute();
    $openx = $stmtx->rowCount();
    ?>


    <p align="center">
        <button class="w3-btn bttn"><a href="client_claims.php" style="text-decoration: none"><span style="color:white">View your Claims</span>
                <span class="badge" style="color: mediumseagreen; background-color: white"><?php echo $openx; ?></span></a>
        </button>
    </p>

    <h3 align="center" class="hd">Log a Query/Claim</h3>
    <div class="container" >

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data"
              onsubmit="onLoad()">
            <div class="row">
                <div class="col-sm-2">

                </div>
                <div class="col-sm-10">
                    <textarea name="area1"  placeholder="Type your Query here..." <?php echo $disable; ?> cols="70"
                              style="width: 100%;color: grey; padding: 20px; border-color: lightblue;border-radius:20px;border-bottom-style:solid;border-bottom-width:7px;outline: none;" rows="10"
                              REQUIRED><?php echo $txt; ?></textarea>
                </div>
            </div>

            <div class="row" style="padding-top: 20px">
                <div class="col-sm-2 hidden-xs">

                </div>
                <div class="col-sm-4" style="font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;color: dimgray; padding-bottom: 10px">Upload File
                    <input type="file" name="file_array[]" <?php echo $disable; ?> multiple="multiple"
                           class="w3-btn w3-white w3-border w3-green w3-round-large">
                    <?php
                    if (isset($_POST['upload_now']) && !empty($_POST['area1'])) {
                        echo $results;
                    }

                    ?>
                </div>

                <div class="col-sm-6 hidden-sm hidden-xs" <?php echo $disable; ?> hidden>
                    <p>Drag your files on to the drop zone below</p>
                    <article>
                        <div id="holder">
                        </div>
                        <p id="upload" class="hidden"><label>Drag & drop not supported, but you can still upload via
                                this input field:<br><input type="file" name="file_array[]"></label></p>
                        <p id="filereader">File API & FileReader API not supported</p>
                        <p id="formdata">XHR2's FormData is not supported</p>
                        <p id="progress">XHR2's upload progress isn't supported</p>

                    </article>
                    <script type="application/javascript" src="../admin/js/drag_drop.js"></script>
                </div>
            </div>


            <div class="col-sm-2">

            </div>
            <div class="col-sm-6">

                <button type="submit" name="upload_now" style="background-color:rgb(102, 204, 153);color:white; border-radius: 10px; font-weight: 500" class="w3-btn subm"
                        id="btn1" <?php echo $disable; ?>><span class="glyphicon glyphicon-ok-circle"
                                                                style="color:white"> </span> Submit</button>
                <br><span id='loadingmsg' style='display: none;color: red;font-weight: bolder'>please wait...</span>
            </div>
        </form>
    </div>
    <hr>
    <?php
    include('footer.php');
}
else{
    echo "No access";
}

?>
</body>