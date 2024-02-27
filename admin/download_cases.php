<?php
session_start();

include_once "dbconn.php";
function getClientName($id)
{
    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT client_name FROM clients WHERE client_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $clientName = $stmt->fetchColumn();
    return $clientName;

}
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
    if (isset($_POST['download'])) {

        $vl = validateXss($_POST['search']);
        $_SESSION['term'] = $vl;
        $val = $vl;
        header("Content-Type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=MCA_Claims.xls");

        $r = $_SESSION['user_id'];
        if($_SESSION['level'] == "gap_cover")
        {

         try {
             echo 'GAP COVER' . "\t" . 'POLICY NUMBER' . "\t" . 'CLAIM NUMBER' . "\t" . 'NAME' . "\t" . 'SURNAME' . "\t" . 'SCHEME' . "\t" . 'OPTION' . "\t" . 'PMB' . "\t" . 'EMERGENCY STATUS' . "\t" . 'TOTAL SAVINGS' . "\t" . 'DATE ENTERED' . "\t" . 'DATE CLOSED' . "\t" . 'USERNAME' . "\t" . 'CASE STATUS' . "\t" . 'GAP VALUE' . "\t" . 'UPLOADED BY' . "\t" . 'DATE UPLOADED' . "\n";
             $conn = connection("mca", "MCA_admin");

             $stmt = $conn->prepare("SELECT client_id FROM clients WHERE client_name = :name");
             $stmt->bindParam(':name', $r, PDO::PARAM_STR);
             $stmt->execute();
             $clientNameID = $stmt->fetchColumn();
             $rrr= $clientNameID;
             $myStmt = "SELECT c.client_id,c.policy_number,a.claim_number,c.first_name,c.surname,c.medical_scheme,c.scheme_option,a.pmb,a.emergency,
(a.savings_scheme + a.savings_discount) AS total,a.date_entered,a.date_closed,
a.username,a.Open,a.gap,j.uploaded_by,j.date FROM documents as j INNER JOIN claim as a ON j.claim_id=a.claim_id INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE c.client_id=:num AND a.Open<>2";
             $sql = $conn->prepare($myStmt);
             $sql->bindParam(':num', $rrr, PDO::PARAM_STR);
           
             $sql->execute();
             $nu = $sql->rowCount();
             echo "0====" . $nu;
             if ($nu > 0) {
                 foreach ($sql->fetchAll() as $row) {
                     $gap = getClientName($row[0]);
                     $policy = $row[1];
                     $claimN = $row[2];
                     $name = $row[3];
                     $surname = $row[4];
                     $scheme = $row[5];
                     $option = $row[6];
                     $pmb = $row[7];
                     $emergency = $row[8];
                     $total = $row[9];
                     $date_entered = $row[10];
                     $date_closed = $row[11];
                     $username = $row[12];
                     $open = $row[13];
                     $gapv = $row[14];
                     $uploaded_by = $row[15];
                     $date_uploaded = $row[16];
                     $pmb1 = "Yes";
                     if ($pmb == "0") {
                         $pmb1 = "No";
                     }
                     $open1 = "Closed";
                     $date_closed1 = $date_closed;
                     if ($open == 1) {
                         $open1 = "Open";
                         $date_closed1 = "Still Open";
                     }
                     $emergency1 = "---";
                     if ($emergency == "1") {
                         $emergency1 = "Yes";
                     } elseif ($emergency == "0") {
                         $emergency1 = "No";
                     } else {
                         $emergency1 = "Not Sure";
                     }

                     echo $gap . "\t" . $policy . "\t" . $claimN . "\t" . $name . "\t" . $surname . "\t" . $scheme . "\t" . $option . "\t" . $pmb1 . "\t" . $emergency1 . "\t" . $total . "\t" . $date_entered . "\t" . $date_closed1 . "\t" . $username . "\t" . $open1 . "\t" . $gapv . "\t" . $uploaded_by . "\t" . $date_uploaded . "\n";

                 }
             } else {
                 echo "<b style='color:red'>No match found</b>";
             }
         }
         catch (Exception $rr)
         {
             echo "There is an error ".$rr->getMessage();
         }
        }
        else {
            echo 'GAP COVER' . "\t" . 'POLICY NUMBER' . "\t" . 'CLAIM NUMBER' . "\t" . 'NAME' . "\t" . 'SURNAME' . "\t" . 'SCHEME' . "\t" . 'OPTION' . "\t" . 'PMB' . "\t" . 'EMERGENCY STATUS' . "\t" . 'TOTAL SAVINGS' . "\t" . 'DATE ENTERED' . "\t" . 'DATE CLOSED' . "\t" . 'USERNAME' . "\t" . 'CASE STATUS' . "\t" . 'GAP VALUE' . "\n";

            $conn = connection("mca", "MCA_admin");
            $r = $_SESSION["user_id"];

            $condition = "";
            $r = $_SESSION['user_id'];
            if ($_SESSION['level'] == "claims_specialist") {
                $condition = "a.username = :num";
            } else if ($_SESSION['level'] == "gap_cover") {
                $dbh = connection("mca", "MCA_admin");
                $stmt = $dbh->prepare("SELECT client_id FROM clients WHERE client_name = :name");
                $stmt->bindParam(':name', $r, PDO::PARAM_STR);
                $stmt->execute();
                $clientNameID = $stmt->fetchColumn();
                $r = $clientNameID;
                $condition = "c.client_id = :num";
            } else if ($_SESSION['level'] == "patient") {
                $condition = "patient_name = :num";
            } else if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller") {
                $condition = "1";
            } else {
                die("Incorrect login");
            }
            try {
                $searched = "%" . $val . "%";
                $myStmt = "SELECT c.client_id,c.policy_number,a.claim_number,c.first_name,c.surname,c.medical_scheme,c.scheme_option,a.pmb,a.emergency,
(a.savings_scheme + a.savings_discount) AS total,a.date_entered,a.date_closed,
a.username,a.Open,a.gap FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE $condition AND a.Open<>2 AND 
(c.first_name like :search OR c.surname like :search OR a.claim_number like :search OR c.medical_scheme like :search OR c.policy_number like :search OR
 b.client_name like :search OR a.username like :search OR c.scheme_number like :search OR a.date_closed LIKE :search) ORDER BY a.date_entered DESC";
                $checkedVal = (int)validateXss($_POST['rad']);

                if ($checkedVal == 2) {
                    $myStmt = "SELECT c.client_id,c.policy_number,a.claim_number,c.first_name,c.surname,c.medical_scheme,c.scheme_option,a.pmb,a.emergency,
(a.savings_scheme + a.savings_discount) AS total,a.date_entered,a.date_closed,
a.username,a.Open,a.gap FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE $condition AND Open=1 AND 
(c.first_name like :search OR c.surname like :search OR a.claim_number like :search OR c.medical_scheme like :search OR c.policy_number like :search OR
 b.client_name like :search OR a.username like :search OR c.scheme_number like :search OR a.date_closed LIKE :search) ORDER BY a.date_entered DESC";
                }
                if ($checkedVal == 3) {
                    $myStmt = "SELECT c.client_id,c.policy_number,a.claim_number,c.first_name,c.surname,c.medical_scheme,c.scheme_option,a.pmb,a.emergency,
(a.savings_scheme + a.savings_discount) AS total,a.date_entered,a.date_closed,
a.username,a.Open,a.gap FROM claim as a INNER JOIN clients as b ON c.client_id=b.client_id WHERE $condition AND Open=0 AND 
(c.first_name like :search OR c.surname like :search OR a.claim_number like :search OR c.medical_scheme like :search OR c.policy_number like :search OR
 b.client_name like :search OR a.username like :search OR c.scheme_number like :search OR a.date_closed LIKE :search) ORDER BY a.date_entered DESC";
                }
                if ($checkedVal == 4) {
                    $myStmt = "SELECT c.client_id,c.policy_number,a.claim_number,c.first_name,c.surname,c.medical_scheme,c.scheme_option,a.pmb,a.emergency,
(a.savings_scheme + a.savings_discount) AS total,a.date_entered,a.date_closed,
a.username,a.Open,a.gap FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE $condition AND pmb=1 AND a.Open<>2 AND 
(c.first_name like :search OR c.surname like :search OR a.claim_number like :search OR c.medical_scheme like :search OR c.policy_number like :search OR
 b.client_name like :search OR a.username like :search OR c.scheme_number like :search OR a.date_closed LIKE :search) ORDER BY a.date_entered DESC";
                }
                if ($checkedVal == 5) {
                    $myStmt = "SELECT c.client_id,c.policy_number,a.claim_number,c.first_name,c.surname,c.medical_scheme,c.scheme_option,a.pmb,a.emergency,
(a.savings_scheme + a.savings_discount) AS total,a.date_entered,a.date_closed,
a.username,a.Open,a.gap FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE $condition AND a.Open<>2 AND pmb=0 AND 
(c.first_name like :search OR c.surname like :search OR a.claim_number like :search OR c.medical_scheme like :search OR c.policy_number like :search OR
 b.client_name like :search OR a.username like :search OR c.scheme_number like :search OR a.date_closed LIKE :search) ORDER BY a.date_entered DESC";
                }
                //$myStmt.=" LIMIT 10";
                $sql = $conn->prepare($myStmt);
                $sql->bindParam(':num', $r, PDO::PARAM_STR);
                $sql->bindParam(':search', $searched, PDO::PARAM_STR);
                $sql->execute();
                $nu = $sql->rowCount();

                if ($nu > 0) {
                    foreach ($sql->fetchAll() as $row) {
                        $gap = getClientName($row[0]);
                        $policy = $row[1];
                        $claimN = $row[2];
                        $name = $row[3];
                        $surname = $row[4];
                        $scheme = $row[5];
                        $option = $row[6];
                        $pmb = $row[7];
                        $emergency = $row[8];
                        $total = $row[9];
                        $date_entered = $row[10];
                        $date_closed = $row[11];
                        $username = $row[12];
                        $open = $row[13];
                        $gapv = $row[14];
                        $pmb1 = "Yes";
                        if ($pmb == "0") {
                            $pmb1 = "No";
                        }
                        $open1 = "Closed";
                        $date_closed1 = $date_closed;
                        if ($open == 1) {
                            $open1 = "Open";
                            $date_closed1 = "Still Open";
                        }
                        $emergency1 = "---";
                        if ($emergency == "1") {
                            $emergency1 = "Yes";
                        } elseif ($emergency == "0") {
                            $emergency1 = "No";
                        } else {
                            $emergency1 = "Not Sure";
                        }

                        echo $gap . "\t" . $policy . "\t" . $claimN . "\t" . $name . "\t" . $surname . "\t" . $scheme . "\t" . $option . "\t" . $pmb1 . "\t" . $emergency1 . "\t" . $total . "\t" . $date_entered . "\t" . $date_closed1 . "\t" . $username . "\t" . $open1 . "\t" . $gapv . "\n";

                    }
                } else {
                    echo "<b style='color:red'>No match found</b>";
                }
            } catch (Exception $f) {
                echo "There is an error " . $f;
            }
        }
    }

}
else
{
    echo "<b style='color:red'>Invalid entry</b>";
}