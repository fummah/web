<?php
session_start();
error_reporting(0);
?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <style type="text/css">
        <!--
        .tab { margin-left: 200px; }
        .linkButton {
            background: none;
            border: none;
            color: #0066ff;
            text-decoration: underline;
            cursor: pointer;
        }
        -->

        #me{
            width: 80%;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>

<br>
<?php
//error_reporting(0);
//echo "Test 1";
class ourFunctions
{
    public $claim_id;
    public $avl;
    function addClaim($member_id,$claim_number,$Service_Date,$icd10,$pmb,$charged_amnt,$scheme_paid,$gap,$username,$emergency,$entered_by,$end_date,$client_gap,$medication_value,$patient_dob,$fusion_done,$code_description,$modifier,$reason_code,$person_email,$patient_gender)
    {
        global $conn;
        $ret=false;
        $insert = $conn->prepare('INSERT INTO `claim`(`member_id`,`claim_number`, `Service_Date`, `icd10`, `pmb`, `charged_amnt`,`scheme_paid`, `gap`,`username` ,`emergency`, `entered_by`,`end_date`,`client_gap`,medication_value,patient_dob,fusion_done,code_description,modifier,reason_code,contact_person_email,patient_gender) 
VALUES (:member_id,:claim_number, :Service_Date,:icd10,:pmb, :charged_amnt, :scheme_paid, :gap,:username, :emergency, :entered_by,:end_date,:client_gap,:medication_value,:patient_dob,:fusion_done,:code_description,:modifier,:reason_code,:contact_person_email,:patient_gender)');
        $insert->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $insert->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $insert->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
        $insert->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $insert->bindParam(':pmb', $pmb, PDO::PARAM_STR);
        $insert->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
        $insert->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
        $insert->bindParam(':gap', $gap, PDO::PARAM_STR);
        $insert->bindParam(':username', $username, PDO::PARAM_STR);
        $insert->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $insert->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $insert->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        $insert->bindParam(':medication_value', $medication_value, PDO::PARAM_STR);
        $insert->bindParam(':patient_dob', $patient_dob, PDO::PARAM_STR);
        $insert->bindParam(':fusion_done', $fusion_done, PDO::PARAM_STR);
        $insert->bindParam(':code_description', $code_description, PDO::PARAM_STR);
        $insert->bindParam(':modifier', $modifier, PDO::PARAM_STR);
        $insert->bindParam(':reason_code', $reason_code, PDO::PARAM_STR);
        $insert->bindParam(':contact_person_email', $person_email, PDO::PARAM_STR);
        $insert->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
        $success = $insert->execute();
        if($success==1)
        {
            $checkClaim=$conn->prepare('SELECT MAX(claim_id) FROM claim WHERE entered_by=:entered_by');
            $checkClaim->bindParam(':entered_by', $username, PDO::PARAM_STR);
            $checkClaim->execute();
            $this->claim_id=$checkClaim->fetchColumn();
            $ret=true;

        }
        else{
            $ret=false;
        }
        return $ret;
    }
    function addAspen($claim_id)
    {
        global $conn;
        $insert = $conn->prepare('  INSERT INTO `aspen_checklist`(`claim_id`) VALUES (:claim_id)');
        $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insert->execute();
    }
    function addDoctor($practice_number,$claim_id,$username)
    {
        global $conn;
        $ret=false;
        $practice_number=trim($practice_number,' ');
        $practice_number =str_pad( $practice_number, 7, '0', STR_PAD_LEFT);
        $insert = $conn->prepare('  INSERT INTO `doctors`(`claim_id`, `practice_number`,`entered_by`) VALUES (:claim_id,:practice_number,:entered_by)');
        $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insert->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $username, PDO::PARAM_STR);
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
    function addPatient($claim_id,$patient_name,$username)
    {
        global $conn;
        $ret=false;
        $insert = $conn->prepare('  INSERT INTO `patient`(`claim_id`, `patient_name`,`entered_by`) VALUES (:claim_id,:patient_name,:entered_by)');
        $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insert->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $username, PDO::PARAM_STR);
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
    function success($claim_id,$claim_number)
    {
        $path=(int)$client_id==31?"view_aspen.php":"case_detail.php";
        echo"<div class=\"alert alert-success\" id=\"me\">";
        echo "<h2 align='center'>You have successfully added a claim into MCA System </h2>";
        echo $this->avl;
        echo "<h4 align='center'> <form action='$path' method='post' />";
        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
        echo "<h4 align='center'>Click <button type=\"submit\" class=\"btn btn-info\" name=\"btn\">$claim_number</button> to continue.</h4>";
        echo "</form></h4><hr>";

        echo "</div>";


    }
}


//////////////////Exec
if(isset($_POST['btn']))
{
    include_once "dbconn.php";
    $conn = connection("mca", "MCA_admin");
    try
    {

        include("header.php");

        echo("<br><br><br><br>");
        if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller")
        {
//echo "Test 2";
            $username = $_SESSION['user_id'];
            $cid = validateXss($_POST['claim_number']);
//echo "<li>";


            $sql = $conn->prepare("SELECT claim_number,username FROM claim WHERE claim_number=:num");
            $sql->bindParam(':num', $cid, PDO::PARAM_STR);
            $sql->execute();
            $nu = $sql->rowCount();
            if ($nu > 0)
            {
                //echo "Test 666";
                foreach ($sql->fetchAll() as $row1) {

                    $us = $row1[1];
                    echo "<h3 align=\"center\" class=\"alert alert-danger\">This Claim Number($cid) is already in the system under <b>($us)</b></h3>";
                }
            }elseif (!preg_match("/^([a-zA-Z \'\-]+)$/", $_POST['member_name']) || !preg_match("/^([a-zA-Z \'\-]+)$/", $_POST['member_surname']) || !preg_match("/^([a-zA-Z \'\-]+)$/", $_POST['member_surname'])) {
                echo "<h3 align=\"center\" class=\"alert alert-danger\">Invalid member name/patient name</b></h3>";
                // echo "Test Pattern";
            }
            else
            {
//echo "Test 4";
//echo "<li>";
//$user_id=$_POST['user_id'];
                $memb_avail=false;
                $client_id = validateXss($_POST['client_id']);
                $policy_number = validateXss($_POST['policy_number']);
                $member_name = $_POST['member_name'];
                $member_surname = $_POST['member_surname'];
                $memb_email = validateXss($_POST['memb_email']);
                $memb_cell = validateXss($_POST['memb_cell']);
                $memb_telephone = validateXss($_POST['memb_telephone']);
                $id_number = validateXss($_POST['id_number']);
                $scheme_number = validateXss($_POST['scheme_number']);
                $medical_scheme = filter_var($_POST['medical_scheme'],FILTER_SANITIZE_STRING);
                $scheme_option = validateXss($_POST['scheme_option']);
                $d_o_b = validateXss($_POST['d_o_b']);
                $medication_value = validateXss($_POST['medication_value']);
                $fusion_done = validateXss($_POST['fusion_done']);
                $dosage = validateXss($_POST['dosage']);
                $codes = validateXss($_POST['codes']);
                $nappi = validateXss($_POST['nappi']);
                $person_email = validateXss($_POST['person_email']);
                $account_number="";

                if(!filter_var($memb_email,FILTER_VALIDATE_EMAIL) && !empty($memb_email))
                {
                    die("Invalid email");
                }
                $doc_name_1 = validateXss($_POST['doctors']);
                $Service_Date = validateXss($_POST['Service_Date']);
                $end_date = validateXss($_POST['end_date']);
                $icd10 = validateXss($_POST['icd10']);
                $pmb = validateXss($_POST['pmb']);
                $charged_amnt = validateXss($_POST['charged_amnt']);
                $scheme_paid = validateXss($_POST['scheme_paid']);
                $client_gap = validateXss($_POST['client_gap']);
                $gap = validateXss($_POST['gap']);
                $claim_number = validateXss($_POST['claim_number']);
                $emergency = validateXss($_POST['emergency']);
                $entered_by = validateXss($_POST['entered_by']);
                $patient_name = validateXss($_POST['myPatient']);
                $patient_gender = validateXss($_POST['patient_gender']);

                $checkMember_stmt=$conn->prepare('SELECT member_id,entered_by FROM member WHERE ((policy_number=:policy_number AND policy_number<>"") 
OR (id_number=:id_number AND id_number<>"")) AND client_id=:client_id LIMIT 1');
                $checkMember_stmt->bindParam(':policy_number',$policy_number,PDO::PARAM_STR);
                $checkMember_stmt->bindParam(':id_number',$id_number,PDO::PARAM_STR);
                $checkMember_stmt->bindParam(':client_id',$client_id,PDO::PARAM_STR);

                $checkMember_stmt->execute();
                $count=$checkMember_stmt->rowCount();
                $our=new ourFunctions();
                if($count>0)
                {
                    $entered_with=$checkMember_stmt->fetch();
                    $member_id=$entered_with[0];
                    $namee=$entered_with[1];
                    $our->avl="<p align='center' style='color: red;font-weight: bolder'>$member_name $member_surname was already loaded by $namee</p>";

                    if($our->addClaim($member_id,$claim_number,$Service_Date,$icd10,$pmb,$charged_amnt,$scheme_paid,$gap,$username,$emergency,$username,$end_date,$client_gap,$medication_value,$d_o_b,$fusion_done,$dosage,$codes,$nappi,$person_email,$patient_gender))
                    {
                        $claim_id=$our->claim_id;
                        $doc_array=array();
                        $pat_array=array();
                        $pat_array=explode(",",$patient_name);
                        $doc_array=explode(",",$doc_name_1);
                        $doc_count=count($doc_array);
                        $pat_count=count($pat_array);
                        for($i=0;$i<$doc_count;$i++)
                        {
                            if(!empty($doc_array[$i])) {
                                $our->addDoctor($doc_array[$i], $claim_id, $username);
                            }
                        }
                        for($i=0;$i<$doc_count;$i++)
                        {
                            if(!empty($pat_array[$i])) {
                                $our->addPatient($claim_id, $pat_array[$i], $username);
                            }
                        }
                        if($client_id==31)
                        {
                            $our->addAspen($claim_id);
                        }
                        $our->success($claim_id,$claim_number);
                    }

                }
                else{
                    $insertMember=$conn->prepare('INSERT INTO `member`(`client_id`, `policy_number`, `first_name`, `surname`, `email`, `cell`, `telephone`, `id_number`, `scheme_number`,
 `medical_scheme`, `scheme_option`, `account_number`,`entered_by`) VALUES (:client_id,:policy_number,:first_name,:surname,:email,:cell,:telephone,:id_number,:scheme_number,:medical_scheme,
 :scheme_option,:account_number,:entered_by)');
                    $insertMember->bindParam(':client_id',$client_id,PDO::PARAM_INT);
                    $insertMember->bindParam(':policy_number',$policy_number,PDO::PARAM_STR);
                    $insertMember->bindParam(':first_name',$member_name,PDO::PARAM_STR);
                    $insertMember->bindParam(':surname',$member_surname,PDO::PARAM_STR);
                    $insertMember->bindParam(':email',$memb_email,PDO::PARAM_STR);
                    $insertMember->bindParam(':cell',$memb_cell,PDO::PARAM_STR);
                    $insertMember->bindParam(':telephone',$memb_telephone,PDO::PARAM_STR);
                    $insertMember->bindParam(':id_number',$id_number,PDO::PARAM_STR);
                    $insertMember->bindParam(':scheme_number',$scheme_number,PDO::PARAM_STR);
                    $insertMember->bindParam(':medical_scheme',$medical_scheme,PDO::PARAM_STR);
                    $insertMember->bindParam(':scheme_option',$scheme_option,PDO::PARAM_STR);
                    $insertMember->bindParam(':account_number',$account_number,PDO::PARAM_STR);
                    $insertMember->bindParam(':entered_by',$username,PDO::PARAM_STR);

                    $result=$insertMember->execute();
                    if($result==1)
                    {
                        $checkClaim=$conn->prepare('SELECT MAX(member_id) FROM member WHERE entered_by=:entered_by');
                        $checkClaim->bindParam(':entered_by', $username, PDO::PARAM_STR);
                        $checkClaim->execute();
                        $member_id=$checkClaim->fetchColumn();
                        if($our->addClaim($member_id,$claim_number,$Service_Date,$icd10,$pmb,$charged_amnt,$scheme_paid,$gap,$username,$emergency,$username,$end_date,$client_gap,$medication_value,$d_o_b,$fusion_done,$dosage,$codes,$nappi,$person_email,$patient_gender))
                        {
                            $claim_id=$our->claim_id;
                            $doc_array=array();
                            $pat_array=array();
                            $pat_array=explode(",",$patient_name);
                            $doc_array=explode(",",$doc_name_1);
                            $doc_count=count($doc_array);
                            $pat_count=count($pat_array);
                            for($i=0;$i<$doc_count;$i++)
                            {
                                if(!empty($doc_array[$i])) {
                                    $our->addDoctor($doc_array[$i], $claim_id, $username);
                                }
                            }
                            for($i=0;$i<$doc_count;$i++)
                            {
                                if(!empty($pat_array[$i])) {
                                    $our->addPatient($claim_id, $pat_array[$i], $username);
                                }
                            }
                            if($client_id==31)
                            {
                                $our->addAspen($claim_id);
                            }
                            $our->success($claim_id,$claim_number);
                        }
                    }
                    else
                    {
                        echo "<span class='alert alert-danger'>Failed to create member</span>";
                    }
                }


            }
        }
        else
        {
            echo "<span class='alert alert-danger'>Invalid Entry.</span>";
        }
    }
    catch
    (Exception $e)
    {
        echo "<span class=''>$e.</span>";
    }
    echo("<hr>");
}

else
{
    $r="<script>location.href = \"add_new_case.php\";</script>";
    die($r);
}

?>

<?php
include('footer.php');
?>                                                