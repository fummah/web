<?php
session_start();
define("access",true);
require_once('../dbconn1.php');
$conn=connection("mca","MCA_admin");
if (isset($_POST['download']))
{
    if($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller" ) {
        $id = validateXss($_POST['client_id']);
        $dat = validateXss($_POST['dat']);
        $date = date_create($dat);
        date_add($date, date_interval_create_from_date_string("1 days"));
        $myD = date_format($date, "Y-m-d");
        $gapName = getClientName($id) . "_" . $myD;
        $dat = $dat . '%';
        header("Content-Type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=$gapName.xls");

        $sql = $conn->prepare("SELECT c.policy_number as POLICY_NUMBER,a.claim_number as CLAIM_NUMBER,date_format(b.date_entered, \"%Y/%m/%d\"),b.intervention_desc as 
INERVENTION_DESCRIPTION,a.Open,a.savings_scheme as SCHEME_SAVINGS,a.savings_discount as DISCOUNT_SAVINGS FROM claim as a INNER JOIN intervention as b ON a.claim_id=b.claim_id INNER JOIN member as c 
ON a.member_id=c.member_id WHERE b.date_entered LIKE :dat AND c.client_id=:id");
        $sql->bindParam(':id', $id, PDO::PARAM_STR);
        $sql->bindParam(':dat', $dat, PDO::PARAM_STR);
        $sql->execute();
        $nu = $sql->rowCount();
        if ($nu > 0) {
            echo '<table border="1"><tr><th>Policy Number</th><th>Claim Number</th><th>Date Entered</th><th>Intervention Description</th><th>Open</th><th>Scheme Savings</th><th>Discount Savings</th></tr>';

            foreach ($sql->fetchAll() as $row) {
                $gap = getClientName($id);
                $policy = $row[0];
                $claimN = $row[1];
                $date_entered = $row[2];
                $desc = $row[3];
                $open = $row[4];
                $scheme_savings = $row[5];
                $discount_savings = $row[6];

                echo '<tr><td>' . $policy . "</td><td>" . $claimN . "</td><td>" . $date_entered . "</td><td>" . $desc . "</td><td>" . $open . "</td><td>" . $scheme_savings . "</td><td>" . $discount_savings . "</td></tr>";

            }
        } else {
            echo "No match found";
        }
    }
    else{
        echo "There is an error";
    }
}

elseif(isset($_POST['txt1']))
{
  header("Content-Type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=zero_amounts.xls");
  $username=$_POST["username"];
  $sql = $conn->prepare('SELECT DISTINCT mca_claim_id,claim_number,username,b.date_entered FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId is null AND a.date_entered>"2020-05-28"');
  if(!empty($username))
  {
    $sql = $conn->prepare('SELECT DISTINCT mca_claim_id,claim_number,username,b.date_entered FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND username=:username AND b.senderId is null AND a.date_entered>"2020-05-28"');
    $sql->bindParam(':username', $username, PDO::PARAM_STR);
  }
  $sql->execute();
  $nu = $sql->rowCount();
  if ($nu > 0) {
    echo '<table border="1"><tr><th>Claim ID</th><th>Claim Number</th><th>Username</th></tr>';

    foreach ($sql->fetchAll() as $row) {
      $claim_id = $row[0];
      $claim_number = $row[1];
      $username = $row[2];


      echo '<tr><td>' . $claim_id . "</td><td>" . $claim_number . "</td><td>" . $username . "</td></tr>";

    }
  } else {
    echo "No match found";
  }
}
elseif(isset($_POST['txt2']))
{
  header("Content-Type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=contacted_members.xls");
  $sql = $conn->prepare('SELECT b.first_name,b.surname,a.claim_number,b.policy_number,a.date_entered,a.member_contacted,NOW() as time,TIMESTAMPDIFF(day,a.date_entered,NOW()) as period,a.username,a.claim_id 
FROM claim as a INNER JOIN  member as b ON a.member_id=b.member_id WHERE a.Open=1 GROUP BY a.claim_id having period>=6');
  $sql->execute();
  $nu = $sql->rowCount();
  if ($nu > 0) {
    echo '<table border="1"><tr><th>First Name</th><th>Surname</th><th>Claim Number</th><th>Policy Number</th><th>Date Entered</th><th>Username</th><th>Member Contacted?</th></tr>';

    foreach ($sql->fetchAll() as $row) {
      $contacted=$row[5]==1?"Yes":"No";



      echo '<tr><td>' . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2]. "</td><td>" . $row[3]. "</td><td>" . $row[4]. "</td><td>" . $row[8]. "</td><td>" . $contacted . "</td></tr>";

    }
  } else {
    echo "No match found";
  }
}
elseif(isset($_POST['txt3']))
{
  header("Content-Type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=Broker_Report.xls");

  $txt=$_POST["txt11"];
  $t=json_decode($txt,true);
  $nu=count($t);
  if ($nu > 0) {
    echo "<table border=\"1\"><tr><th>Full Name</th><th>Email</th><th>Broker Name</th><th>Subscription</th><th>First Date</th><th>Amount(Zar)</th></tr>";
    foreach ($t as $row) {
      $name=$row["name"];
      $email=$row["email"];
      $broker=$row["broker"];
      $subscription=$row["subscription"];
      $date=$row["date"];
      $amount=$row["amount"];
      $arrx=array("name"=>$name,"email"=>$email,"broker"=>$broker,"subscription"=>$subscription,"date"=>$date,"amount"=>$amount);
      echo '<tr><td>' . $name. "</td><td>" . $email . "</td><td>" . $broker. "</td><td>" . $subscription. "</td><td>" . $date. "</td><td>" . $amount. "</td></tr>";

    }
    echo "</table>";
  } else {
    echo "No match found";
  }
}
elseif(isset($_POST['txt4']))
{
  header("Content-Type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=Invoices.xls");

  $txt=$_POST["txt12"];
  $t=json_decode($txt,true);
  $nu=count($t);
  if ($nu > 0) {
    echo "<table border=\"1\"><tr><th>Full Name</th><th>Email</th><th>Broker Name</th><th>Subscription</th><th>Transaction Date</th><th>Amount(Zar)</th></tr>";
    foreach ($t as $row) {
      $name=$row["name"];
      $email=$row["email"];
      $broker=$row["broker"];
      $subscription=$row["subscription"];
      $date=$row["date"];
      $amount=$row["amount"];
      $arrx=array("name"=>$name,"email"=>$email,"broker"=>$broker,"subscription"=>$subscription,"date"=>$date,"amount"=>$amount);
      echo '<tr><td>' . $name. "</td><td>" . $email . "</td><td>" . $broker. "</td><td>" . $subscription. "</td><td>" . $date. "</td><td>" . $amount. "</td></tr>";

    }
    echo "</table>";
  } else {
    echo "No match found";
  }
}
function getClientName($id)
{

    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT client_name FROM clients WHERE client_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $clientName = $stmt->fetchColumn();
    return $clientName;

}
?>
