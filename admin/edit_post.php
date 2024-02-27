<?php
session_start();
error_reporting(0);
?>
    <link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="bootstrap3/js/bootstrap.min.js"></script>
    <script src="jquery/jquery.js"></script>
    <style type="text/css">
        <?php

        include('header.php');
        require_once('dbconn.php');
        $conn=connection("mca","MCA_admin");
            echo "<br><br><br><br><br><br>";
        if(isset($_POST['btn1']))
        {

          if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged']))
              {

        $usernamexx=$_SESSION['user_id'];
        //$claim_id=$_SESSION['tempClaim'];
        $claim_id=validateXss($_POST['claim_id']);
        $client_id=validateXss($_POST['client_id']);
        $policy_number=validateXss($_POST['policy_number']);
        $claim_number=validateXss($_POST['claim_number']);
        $medical_scheme=filter_var($_POST['medical_scheme'], FILTER_SANITIZE_STRING);
        $scheme_number=validateXss($_POST['scheme_number']);
        $id_number=validateXss($_POST['id_number']);
        $Service_Date=validateXss($_POST['Service_Date']);
        $end_date=validateXss($_POST['end_date']);
        $savings_discount=validateXss($_POST['savings_discount']);
        $savings_scheme=validateXss($_POST['savings_scheme']);
        $emergency=validateXss($_POST['emergency']);
        $icd10=validateXss($_POST['icd10']);
        $pmb=validateXss($_POST['pmbx']);
        $client_gap=validateXss($_POST['client_gap']);
        $open_reason=validateXss($_POST['open_reason']);
$d_o_b = validateXss($_POST['d_o_b']);
                $medication_value = validateXss($_POST['medication_value']);
                $fusion_done = validateXss($_POST['fusion_done']);
                $dosage = validateXss($_POST['dosage']);
                $codes = validateXss($_POST['codes']);
                $nappi = validateXss($_POST['nappi']);
                $person_email = validateXss($_POST['person_email']);
$patient_gender = validateXss($_POST['patient_gender']);
        if($pmb=="Yes")
        {
            $pmb=1;
        }
        else
        {
            $pmb=0;
        }
        $charged_amnt=validateXss($_POST['charged_amnt']);
        $scheme_paid=validateXss($_POST['scheme_paid']);
        $gap=validateXss($_POST['gap']);
        $member_name=$_POST['member_name'];
        $member_surname=$_POST['member_surname'];
        $memb_telephone=validateXss($_POST['memb_telephone']);
        $memb_cell=validateXss($_POST['memb_cell']);
        $memb_email=validateXss($_POST['memb_email']);
        if(!filter_var($memb_email,FILTER_VALIDATE_EMAIL) && !empty($memb_email))
    {
      die("Invalid email");
    }
        $scheme_option=validateXss($_POST['scheme_option']);
        $patient_name=validateXss($_POST['patient_name']);
        $owner=validateXss($_POST['owner']);
        $open=validateXss($_POST['open']);

        $patient_name = validateXss($_POST['myPatient']);
        $doc_name_1 = validateXss($_POST['doctors']);
     if (!preg_match("/^([a-zA-Z \'\-]+)$/",$member_name) || !preg_match("/^([a-zA-Z \'\-]+)$/",$member_surname))
        {
            echo "<h3 align=\"center\" class=\"alert alert-danger\">Invalid member name/patient name</b></h3>";
        }
        else
            {

        try{
             $ss=$conn->prepare('SELECT a.username,b.client_name,a.member_id,a.Open,a.date_reopened FROM `claim` as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE a.claim_id=:claim');
            $ss->bindParam(':claim', $claim_id, PDO::PARAM_STR);
            $ss->execute();
            $ccc=$ss->rowCount();
                 if($ccc>0)
            {
                $rrr=$ss->fetch();
                $sys_username=$rrr[0];
                $client=$rrr[1];
               if($usernamexx==$sys_username || $_SESSION['level']=="admin" || $_SESSION['level']=="controller" || $_SESSION["gap_admin"]=="assessor")
               {
    $member_id=$rrr[2];
     $reopened_date=(int)$open!=(int)$rrr[3]?date("Y-m-d H:i:s"):$rrr[4];
        $stmt = $conn->prepare('Update member SET client_id=:client_id,policy_number=:policy_number,medical_scheme=:medical_scheme,scheme_number=:scheme_number,id_number=:id_number,
    first_name=:member_name,surname=:member_surname,telephone=:memb_telephone,cell=:memb_cell,email=:memb_email,scheme_option=:scheme_option WHERE member_id=:num');
        $stmt->bindParam(':num', $member_id, PDO::PARAM_STR);
        $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $stmt->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $stmt->bindParam(':medical_scheme', $medical_scheme, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $stmt->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $stmt->bindParam(':member_name', $member_name, PDO::PARAM_STR);
        $stmt->bindParam(':member_surname', $member_surname, PDO::PARAM_STR);
        $stmt->bindParam(':memb_telephone', $memb_telephone, PDO::PARAM_STR);
        $stmt->bindParam(':memb_cell', $memb_cell, PDO::PARAM_STR);
        $stmt->bindParam(':memb_email', $memb_email, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
        $added_member=$stmt->execute();
        if ($added_member==1)
            {

     $stmt = $conn->prepare('Update claim SET claim_number=:claim_number,Service_Date=:Service_Date,icd10=:icd10,pmb=:pmb,charged_amnt=:charged_amnt,scheme_paid=:scheme_paid,gap=:gap,    username=:username,Open=:open,emergency=:emergency,savings_scheme=:savings_scheme,savings_discount=:savings_discount,end_date=:end_date,client_gap=:client_gap,date_reopened=:date_reopened,medication_value=:medication_value,patient_dob=:patient_dob,fusion_done=:fusion_done,code_description=:code_description,modifier=:modifier,reason_code=:reason_code,contact_person_email=:contact_person_email,patient_gender=:patient_gender,open_reason=:open_reason WHERE claim_id=:num');
       $stmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmt->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $stmt->bindParam(':pmb', $pmb, PDO::PARAM_STR);
        $stmt->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
        $stmt->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
        $stmt->bindParam(':gap', $gap, PDO::PARAM_STR);
        $stmt->bindParam(':username', $owner, PDO::PARAM_STR);
        $stmt->bindParam(':open', $open, PDO::PARAM_STR);
        $stmt->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $stmt->bindParam(':savings_discount', $savings_discount, PDO::PARAM_STR);
        $stmt->bindParam(':savings_scheme', $savings_scheme, PDO::PARAM_STR);
        $stmt->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
 $stmt->bindParam(':medication_value', $medication_value, PDO::PARAM_STR);
        $stmt->bindParam(':patient_dob', $d_o_b, PDO::PARAM_STR);
        $stmt->bindParam(':fusion_done', $fusion_done, PDO::PARAM_STR);
        $stmt->bindParam(':code_description', $dosage, PDO::PARAM_STR);
        $stmt->bindParam(':modifier', $codes, PDO::PARAM_STR);
        $stmt->bindParam(':reason_code', $nappi, PDO::PARAM_STR);
        $stmt->bindParam(':contact_person_email', $person_email, PDO::PARAM_STR);
    $stmt->bindParam(':date_reopened', $reopened_date, PDO::PARAM_STR);
 $stmt->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
 $stmt->bindParam(':open_reason', $open_reason, PDO::PARAM_STR);
        $added_member=$stmt->execute();
        if($added_member==1)
            {
                if($_SESSION['openx']==0 && $open==1)
{
                insertReOpenedCases($claim_id,$open_reason,$usernamexx,$_SESSION['date_closed'],$_SESSION['savings_scheme'],$_SESSION['savings_discount']);
}
            $doc_array=array();
            $pat_array=array();
            $pat_array=explode(",",$patient_name);
            $doc_array=explode(",",$doc_name_1);
            $doc_count=count($doc_array);
            $pat_count=count($pat_array);

            for($i=0;$i<$doc_count;$i++)
            {
                $tt=(int)$doc_array[$i];
                if($tt>0)
                    {
                        addDoctor($doc_array[$i],$claim_id,$usernamexx);
                    }

            }
            for($i=0;$i<$doc_count;$i++)
            {
                if(!empty($pat_array[$i]))
                    {
                        addPatient($claim_id,$pat_array[$i],$usernamexx);
                    }

            }

                ////////////////////////
            $stmtx = $conn->prepare('INSERT INTO logs(claim_id,owner, client_id, policy_number, claim_number, medical_scheme, scheme_number, id_number, Service_Date,icd10_desc, charged_amnt, scheme_paid, gap, member_name, member_surname, savings_scheme, savings_discount, memb_telephone, memb_cell, 
         memb_email, scheme_option, emergency, previous_owner)
         VALUES (:num,:username,:client_id,:policy_number,:claim_number,:medical_scheme,:scheme_number,:id_number,:Service_Date,:icd10_desc,:charged_amnt,:scheme_paid,:gap,:member_name,:member_surname,:savings_scheme,:savings_discount,:memb_telephone,
         :memb_cell,:memb_email,:scheme_option,:emergency,:usernamex)');
        $stmtx->bindParam(':num', $claim_id, PDO::PARAM_STR);
        $stmtx->bindParam(':username', $usernamexx, PDO::PARAM_STR);
        $stmtx->bindParam(':client_id', $_SESSION['client_idx'], PDO::PARAM_STR);
        $stmtx->bindParam(':policy_number', $_SESSION['policy_number'], PDO::PARAM_STR);
        $stmtx->bindParam(':claim_number', $_SESSION['claim_number'], PDO::PARAM_STR);
        $stmtx->bindParam(':medical_scheme', $_SESSION['medical_scheme'], PDO::PARAM_STR);
        $stmtx->bindParam(':scheme_number', $_SESSION['scheme_number'], PDO::PARAM_STR);
        $stmtx->bindParam(':id_number', $_SESSION['id_number'], PDO::PARAM_STR);
        $stmtx->bindParam(':Service_Date', $_SESSION['Service_Date'], PDO::PARAM_STR);
        $stmtx->bindParam(':icd10_desc', $_SESSION['icd10'], PDO::PARAM_STR);
        $stmtx->bindParam(':charged_amnt', $_SESSION['charged_amnt'], PDO::PARAM_STR);
        $stmtx->bindParam(':scheme_paid', $_SESSION['scheme_paid'], PDO::PARAM_STR);
        $stmtx->bindParam(':gap', $_SESSION['gap'], PDO::PARAM_STR);
        $stmtx->bindParam(':member_name', $_SESSION['member_name'], PDO::PARAM_STR);
        $stmtx->bindParam(':member_surname', $_SESSION['member_surname'], PDO::PARAM_STR);
        $stmtx->bindParam(':savings_scheme', $_SESSION['savings_scheme'], PDO::PARAM_STR);
        $stmtx->bindParam(':savings_discount', $_SESSION['savings_discount'], PDO::PARAM_STR);
        $stmtx->bindParam(':memb_telephone', $_SESSION['memb_telephone'], PDO::PARAM_STR);
        $stmtx->bindParam(':memb_cell', $_SESSION['memb_cell'], PDO::PARAM_STR);
        $stmtx->bindParam(':memb_email', $_SESSION['memb_email'], PDO::PARAM_STR);
        $stmtx->bindParam(':scheme_option', $_SESSION['scheme_option'], PDO::PARAM_STR);
        $stmtx->bindParam(':emergency', $_SESSION['emergency'], PDO::PARAM_STR);
        $stmtx->bindParam(':usernamex', $_SESSION['usernamex'], PDO::PARAM_STR);
        $nu=$stmtx->execute();
        if($nu==1)
            {
                echo "<div class=\"alert alert-success\">";

            echo "<h3 align=\"center\" style='color:blue'>You have successfully updated your case</h3>";
$path=(int)$client_id==31?"view_aspen.php":"case_detail.php";

                 echo "<form action='$path' method='post' />";
            echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
            echo "<h4 align=\"center\">Click to continue <input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claim_number\"></h4>";
            echo "</form></div>";
            }
            else{
             echo "<h3 align=\"center\" style='color:red'>Failed to edit</h3>";
            }
            }
            else{

            }
    }

                    }
               else{
                    echo "<h3 align=\"center\" style='color:red'>Invalid entry</h3>";
               }
            }
            else
            {
                echo "<h3 align=\"center\" style='color:red'>Invalid claim</h3>";
            }
        }
        catch(Exception $ee)
        {
        echo "<h3 align=\"center\" style='color:red'>There is an Error $ee</h3>";
        }

        }
        }
        else
            {
                 echo "<h3 align=\"center\" style='color:red'>Invalid entry</h3>";
            }
        }
        ?>
        <hr>
<?php
include('footer.php');
function addDoctor($practice_number,$claim_id,$username)
{
    global $conn;
    $ret = false;
    $practice_number=trim($practice_number,' ');
    if(strlen($practice_number)>3) {
        $practice_number =str_pad( $practice_number, 7, '0', STR_PAD_LEFT);
        $insert = $conn->prepare('  INSERT INTO `doctors`(`claim_id`, `practice_number`,`entered_by`) VALUES (:claim_id,:practice_number,:entered_by)');
        $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insert->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $username, PDO::PARAM_STR);
        $success = $insert->execute();
        if ($success == 1) {
            $ret = true;

        } else {
            $ret = false;
        }

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
function insertReOpenedCases($claim_id,$reason,$entered_by,$date_closed,$last_scheme_savings,$last_discount_savings)
{
    global $conn;
    $logClaim = $conn->prepare('INSERT INTO `reopened_claims`(claim_id,reason,entered_by,date_closed,last_scheme_savings,last_discount_savings) VALUES(:claim_id,:reason,:entered_by,:date_closed,:last_scheme_savings,:last_discount_savings)');
    $logClaim->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $logClaim->bindParam(':reason', $reason, PDO::PARAM_STR);
    $logClaim->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
    $logClaim->bindParam(':date_closed', $date_closed, PDO::PARAM_STR);
    $logClaim->bindParam(':last_scheme_savings', $last_scheme_savings, PDO::PARAM_STR);
    $logClaim->bindParam(':last_discount_savings', $last_discount_savings, PDO::PARAM_STR);
    return (int)$logClaim->execute();
}
?>