<?php
session_start();
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