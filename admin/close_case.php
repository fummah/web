<?php

require_once('dbconn.php');
$conn = connection("mca", "MCA_admin");

try {
    session_start();
    if (isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {


        $username = $_SESSION['user_id'];
        date_default_timezone_set('Africa/Johannesburg');
        $curl = curl_init();
        $claim_id = validateXss($_GET['claim_id']);
        $scheme = validateXss($_GET['scheme']);
        $discount = validateXss($_GET['discount']);
        $catergory=validateXss($_GET['catergory']);
        $ffpo=(double)$discount+$scheme;
        if(strlen($catergory)<3 && $ffpo<1)
        {
            die("Please select the catergory");
        }
        if($ffpo>1)
        {
            $catergory="";
        }

        date_default_timezone_set('Africa/Johannesburg');
        $date = date("Y-m-d H:i:s");
        $val = 0;
        $val = 0;
        $query = "";
        $open = "";
        $sender = "KOS";
        $stx="Sent from MCA";
        $sql = $conn->prepare('SELECT Open,username,senderId FROM claim WHERE claim_id=:claim');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->execute();
        $sys_username="";
        $nu = $sql->rowCount();
        if ($nu > 0) {
            foreach ($sql->fetchAll() as $row) {
                $open = $row[0];
                $sys_username = $row[1];
                $sender=$row[2];
            }
            if($_SESSION['level'] == "admin" || $username==$sys_username) {
                if ($open == 0) {
                    $query = 'Update claim SET savings_scheme=:scheme, savings_discount=:discount,Open=:num,jv_status=:js,savings_catergory=:savings_catergory WHERE claim_id=:claim';
                    $stmt = $conn->prepare($query);
                } else {
                    $query = 'Update claim SET savings_scheme=:scheme, savings_discount=:discount,Open=:num,date_closed=:dat,jv_status=:js,savings_catergory=:savings_catergory WHERE claim_id=:claim';
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':dat', $date, PDO::PARAM_STR);
                }
                $stmt->bindParam(':discount', $discount, PDO::PARAM_STR);
                $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
                $stmt->bindParam(':num', $val, PDO::PARAM_STR);
                $stmt->bindParam(':scheme', $scheme, PDO::PARAM_STR);
                $stmt->bindParam(':js', $stx, PDO::PARAM_STR);
                $stmt->bindParam(':savings_catergory', $catergory, PDO::PARAM_STR);
                $ccc = $stmt->execute();
                if ($ccc == 1) {
                    echo "Savings Updated Successfully";
                    $logClaim = $conn->prepare('INSERT INTO `closed_cases_logs`(claim_id,savings_scheme,savings_discount,closed_by) VALUES(:claim_id,:savings_scheme,:savings_discount,:closed_by)');
                    $logClaim->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                    $logClaim->bindParam(':savings_scheme', $scheme, PDO::PARAM_STR);
                    $logClaim->bindParam(':savings_discount', $discount, PDO::PARAM_STR);
                    $logClaim->bindParam(':closed_by', $username, PDO::PARAM_STR);
                    $logClaim->execute();
                    if($sender=="KOS") {
                        $claim_number = validateXss($_GET['claim_number']);
                        $original_claim_id = checkClaimId($claim_number);
                        $updateNote = $conn->prepare('UPDATE intervention SET claim_id=:cm,claim_id1=:cm1 WHERE claim_id=:num');
                        $updateNote->bindParam(':num', $claim_id, PDO::PARAM_STR);
                        $updateNote->bindParam(':cm', $original_claim_id, PDO::PARAM_STR);
                        $updateNote->bindParam(':cm1', $claim_id, PDO::PARAM_STR);
                        $nnum = $updateNote->execute();

                        $mySav = getIdentity($original_claim_id);
                        $sch = $mySav["scheme_savings"];
                        $disc = $mySav["discount_savings"];
                        $dat="";
                        $po=1;
                        $oop=$mySav["open"];
                        $xl=ccl($claim_number);
                        if($oop=="No" && $xl=="No")
                        {
                            $dat=date("Y-m-d H:i:s");
                            $po=0;
                        }
                        $updateClaim = $conn->prepare('UPDATE claim SET savings_scheme=:ss,savings_discount=:sd,Open=:op,date_closed=:dat WHERE claim_id=:num');
                        $updateClaim->bindParam(':num', $original_claim_id, PDO::PARAM_STR);
                        $updateClaim->bindParam(':ss', $sch, PDO::PARAM_STR);
                        $updateClaim->bindParam(':sd', $disc, PDO::PARAM_STR);
                        $updateClaim->bindParam(':dat', $dat, PDO::PARAM_STR);
                        $updateClaim->bindParam(':op', $po, PDO::PARAM_STR);
                        $updateClaim->execute();

                    }
                } else {
                    echo "Failed to update";
                }
            }
            else
            {
                echo "Invalid Access";
            }

        }
        else
        {
            echo "Claim not found";
        }
    }
    else
    {
        echo "Invalid Access";
    }
}
catch
(Exception $e)
{
    echo("There is an error");
}


function checkClaimId($claim_number)
{
    global  $conn;
    $mynumber="---";
    $stmt=$conn->prepare('select claim_id from claim where claim_number=:num');
    $stmt->bindParam(':num', $claim_number, PDO::PARAM_STR);
    $stmt->execute();
    $cc=$stmt->rowCount();
    if($cc>0)
    {

        $mynumber= $stmt->fetchColumn();
    }

    return $mynumber;
}

function getIdentity($claim_id)
{
    global  $conn;
    $scheme_savings=0;
    $discount_savings=0;
    $try="No";


    $stmt=$conn->prepare('select DISTINCT claim_id1 from intervention where claim_id=:num');
    $stmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    $cc=$stmt->rowCount();

    if($cc>0)
    {

        foreach ($stmt->fetchAll() as $row)
        {
            $myclaim=(int)$row[0];

            if($myclaim>0)
            {
                $stmt1=$conn->prepare('select savings_scheme,savings_discount,Open from claim where claim_id=:num');
                $stmt1->bindParam(':num', $myclaim, PDO::PARAM_STR);
                $stmt1->execute();
                $savings=$stmt1->fetch();
                $oop=$savings[2];
                if($oop==0) {
                    $scheme_savings += $savings[0];
                    $discount_savings += $savings[1];
                }
                else{
                    $try="Yes";
                }

            }

        }
    }
    $mySav["scheme_savings"]=$scheme_savings;
    $mySav["discount_savings"]=$discount_savings;
    $mySav["open"]=$try;
    return $mySav;
}

function ccl($claim_number)
{
    global  $conn;
    $try="No";
    $stmtf=$conn->prepare('select claim_id,Open from claim where claim_number1=:num');
    $stmtf->bindParam(':num', $claim_number, PDO::PARAM_STR);
    $stmtf->execute();
    $ccf=$stmtf->rowCount();
    if($ccf>0)
    {
        foreach ($stmtf->fetchAll() as $rowf)
        {
            $cc=$rowf[0];
            $oop=$rowf[1];
            if($oop==0)
            {

            }
            else
            {
                $try="Yes";
            }
        }
    }
    return $try;
}

?>