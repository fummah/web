<?php

include("dbconn.php");

error_reporting(0);
if(!isset($_POST['btn']))
{
    $r="<script>location.href = \"index.php\";</script>";
    die($r);
}
$conn=connection("mca","MCA_admin");

$claim_id = (int)validateXss($_POST['claim_id']);
$_SESSION['tempClaim'] = $claim_id;

$selectDetails = $conn->prepare('SELECT a.claim_id,b.member_id,b.first_name, b.surname, b.policy_number, a.claim_number, a.savings_scheme, b.medical_scheme, b.scheme_option, a.date_closed, b.client_id, b.date_entered, 
a.Open, id_number, a.username, a.savings_discount, b.scheme_number,b.email,b.cell,b.telephone,a.pmb,a.icd10,a.charged_amnt,a.scheme_paid,a.gap,a.Service_Date,a.emergency,a.hasDrPaid,a.end_date,a.savings_scheme,a.savings_discount,a.client_gap,a.medication_value,patient_dob,a.fusion_done,a.code_description,a.modifier,a.reason_code,contact_person_email,patient_gender,open_reason
 FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_id=:num');
$selectDetails->bindParam(':num', $claim_id, PDO::PARAM_STR);
$selectDetails->execute();
$ccn = (int)$selectDetails->rowCount();
if ($ccn != 1) {
    die("<script>alert('There is an error');</script>");
}
$details = $selectDetails->fetch();
$_SESSION['docClaimID'] = $claim_id;
$client_id = (int)validateXss($details[10]);
$username = $_SESSION['user_id'];
$user = validateXss($details[14]);

if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller" || $_SESSION["gap_admin"]=="assessor" || $username == $user) {

} else {
    session_unset();
    session_destroy();
    session_write_close();
    die("<script>alert('There is an error');</script>");
}
$member_id = htmlspecialchars($details[1]);
$policy_number = htmlspecialchars($details[4]);
$claim_number = htmlspecialchars($details[5]);
$medical_scheme = htmlspecialchars($details[7]);
$scheme_number = htmlspecialchars($details[16]);
$id_number = htmlspecialchars($details[13]);
$openx = htmlspecialchars($details[12]);
$Service_Date = htmlspecialchars($details[25]);
$end_date = htmlspecialchars($details[28]);
$savings_scheme = htmlspecialchars($details[29]);
$savings_discount = htmlspecialchars($details[30]);
$client_gap = htmlspecialchars($details[31]);
$icd10 = htmlspecialchars($details[21]);
$emergency = htmlspecialchars($details[26]);
$date_closed = htmlspecialchars($details["date_closed"]);
$dbh = connection("mca", "MCA_admin");
$stmt = $dbh->prepare("SELECT client_name FROM clients WHERE client_id = :id");
$stmt->bindParam(':id', $client_id, PDO::PARAM_STR);
$stmt->execute();
$client_name = $stmt->fetchColumn();
$pmb = htmlspecialchars($details[20]);
if ($pmb == 1) {
    $pmb = "Yes";
} else {
    $pmb = "No";
}
if ($Service_Date == "1111-11-11") {
    $Service_Date = "";
}
$stmt = $dbh->prepare("SELECT policy_number,entered_by,date_entered FROM member WHERE policy_number = :policy AND policy_number <>'' AND entered_by<>:user");
$stmt->bindParam(':policy', $policy_number, PDO::PARAM_STR);
$stmt->bindParam(':user', $user, PDO::PARAM_STR);
$stmt->execute();
$nu1 = $stmt->rowCount();
$duplicate = "";
if ($nu1 > 0) {
    $rr = $stmt->fetch();
    $duplicate = "<i><h4 style=\"color: deepskyblue;\">This member was loaded on $rr[2] by $rr[1].</h4></i>";
}


$stmt1 = $dbh->prepare("SELECT claim_number,username,date_entered,Open FROM claim WHERE member_id=:memb AND claim_number<>:claim");
$stmt1->bindParam(':memb', $member_id, PDO::PARAM_STR);
$stmt1->bindParam(':claim', $claim_number, PDO::PARAM_STR);
$stmt1->execute();
$ccount=$stmt1->rowCount();
$otherclaims="";
if($ccount>0)
{
    $myyo="";
    foreach ($stmt1->fetchAll() as $rr)
    {
        $ccm=$rr[0];
        $uus=$rr[1];
        $ddt=$rr[2];
        $ooop=$rr[3];
        $xop="(Open)";
        if($ooop==0)
        {
            $xop="(Closed)";
        }
        $myyo.="<li>$ccm [$uus][$ddt]$xop</li>";
    }
    $otherclaims="<h4 style=\"color: deepskyblue;\"><details><summary>Other Claims ($ccount) (<i style='color: red'>contact the admin if you want to transfer this case</i>)</summary>
<u style='color: green'>$myyo</u></details></h4>";
}
$charged_amnt = htmlspecialchars($details[22]);
$scheme_paid = htmlspecialchars($details[23]);
$gap = htmlspecialchars($details[24]);
$member_name = htmlspecialchars($details[2]);
$member_surname = htmlspecialchars($details[3]);
$memb_telephone = str_replace(" ","",htmlspecialchars($details[19]));
$memb_cell = str_replace(" ","",htmlspecialchars($details[18]));
$memb_email = htmlspecialchars($details[17]);
$scheme_option = htmlspecialchars($details[8]);
$username = validateXss($details[14]);
$open = (int)htmlspecialchars($details[12]);
$medication_value=htmlspecialchars($details[32]);
$patient_dob=htmlspecialchars($details[33]);
$fusion_done=htmlspecialchars($details[34]);
$dosage=htmlspecialchars($details[35]);
$codes=htmlspecialchars($details[36]);
$nappi=htmlspecialchars($details[37]);
$person_email=htmlspecialchars($details[38]);
$patient_gender=htmlspecialchars($details[39]);
$open_reason=htmlspecialchars($details[40]);
$open1 = "Closed";
if ($open == 1) {
    $open1 = "Open";
}
//===============================fuma=============recording logs
$_SESSION['client_idx'] = $client_id;
$_SESSION['policy_number'] = $policy_number;
$_SESSION['claim_number'] = $claim_number;
$_SESSION['medical_scheme'] = $medical_scheme;
$_SESSION['scheme_number'] = $scheme_number;
$_SESSION['id_number'] = $id_number;
$_SESSION['openx'] = $openx;
$_SESSION['Service_Date'] = $Service_Date;
$_SESSION['savings_scheme'] = $savings_scheme;
$_SESSION['savings_discount'] = $savings_discount;
$_SESSION['icd10'] = $icd10;
$_SESSION['emergency'] = $emergency;
$_SESSION['charged_amnt'] = $charged_amnt;
$_SESSION['scheme_paid'] = $scheme_paid;
$_SESSION['gap'] = $gap;
$_SESSION['member_name'] = $member_name;
$_SESSION['member_surname'] = $member_surname;
$_SESSION['memb_telephone'] = $memb_telephone;
$_SESSION['memb_cell'] = $memb_cell;
$_SESSION['memb_email'] = $memb_email;
$_SESSION['scheme_option'] = $scheme_option;
$_SESSION['usernamex'] = $username;
$_SESSION['date_closed'] = $date_closed;
$_SESSION['usernamex'] = $username;
//end

?>