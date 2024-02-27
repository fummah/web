<?php
session_start();
error_reporting(0);

if(isset($_SESSION['mca_logxged']) && !empty($_SESSION['mca_logxged'])) {
    require_once('../dbconn1.php');
    $identity = validateXss($_POST['identity']);
    $conn = connection("mca", "MCA_admin");
    $conn1 = connection("doc", "doctors");
    $conn2 = connection("cod", "Coding");

if($identity==4)
    {
        try {
            $myId = $_SESSION['mca_user_id'];
            $old_pass = filter_var($_POST['old'], FILTER_SANITIZE_STRING);
            $olld=$old_pass;
            $old_pass=password_hash($old_pass, PASSWORD_BCRYPT);
            $new_pass = filter_var($_POST['new1'], FILTER_SANITIZE_STRING);
            $neew=$new_pass;
            $new_pass = password_hash($new_pass, PASSWORD_BCRYPT);
            $stmnt = $conn->prepare('SELECT client_id FROM web_clients WHERE client_id=:id');
            $stmnt->bindParam(':id', $myId, PDO::PARAM_STR);
            $stmnt->execute();
            $cou = $stmnt->rowCount();
            if(strlen($olld)<6 || strlen($neew)<8)
            {
                echo "Invalid password";
            }
            else {

                if ($cou > 0) {
                    $sys_id = $stmnt->fetchColumn();
                    if ($sys_id == $myId) {
                        $stmnt = $conn->prepare('UPDATE web_clients SET password=:new WHERE client_id=:id');
                        $stmnt->bindParam(':id', $myId, PDO::PARAM_STR);
                        $stmnt->bindParam(':new', $new_pass, PDO::PARAM_STR);
                        $d = $stmnt->execute();
                        if ($d == 1) {
                            echo "Password Updated";
                        } else {
                            echo "Password Failed to update";
                        }
                    } else {
                        echo "Invalid access";
                    }

                } else {
                    echo "Incorrect Old Password";
                }
            }
        }
        catch (Exception $e)
        {
            echo "There is an Error";
        }
    }

     }

function getClaimId($claimNum)
{
    global $conn;
    $cNum="";

    $sql = $conn->prepare('SELECT claim_id FROM claim WHERE claim_number=:claim');
    $sql->bindParam(':claim', $claimNum, PDO::PARAM_STR);
    $sql->execute();
    $nu = $sql->rowCount();

    if ($nu > 0) {

        foreach ($sql->fetchAll() as $row) {
            $cNum=$row[0];
        }
    }
    else{
        $cNum="Invalid Code";
    }
    return $cNum;
}
function checkDuplicate($input,$oldpass)
{
    $hash = password_hash($input, PASSWORD_BCRYPT);
    $arr = array_reverse(explode(',',$oldpass));
    $rr1 = array();
    $h=array();
    $count = 0;
    $mess = "";
    for ($i = 0; $i < count($arr); $i++) {
        if ($i < 6) {
            if (password_verify($input,$arr[$i])) {
                $count = 1;
            }

            else {

                array_push($rr1, $arr[$i]);

            }

        }
    }
    if ($count == 0) {
        $h=array_reverse($rr1);
        array_push($h,$hash);
        $mess = implode(',', $h);
    } else {
        $mess = "duplicate";
    }

    return $mess;
}

function checkOwner($x_id,$idd)
{
    global $conn;
    $ret=false;

    if($idd==1)
    {
        $stmt1 = $conn->prepare('SELECT b.username FROM intervention as a INNER JOIN claim as b ON a.claim_id=b.claim_id WHERE intervention_id = :num');
        $stmt1->bindParam(':num', $x_id, PDO::PARAM_STR);
        $stmt1->execute();
        $count=$stmt1->rowCount();
        if($count>0)
        {
            $sys_username=$stmt1->fetchColumn();
            if($_SESSION['level'] == "admin" || $_SESSION['user_id']==$sys_username)
            {
                $ret=true;
            }

        }
        else{
            $ret=false;
        }

    }
    else if ($idd==2)
    {
        $stmt1 = $conn->prepare('SELECT username FROM claim WHERE claim_id = :num');
        $stmt1->bindParam(':num', $x_id, PDO::PARAM_STR);
        $stmt1->execute();
        $count=$stmt1->rowCount();
        if($count>0)
        {
            $sys_username=$stmt1->fetchColumn();
            if($_SESSION['level'] == "admin" || $_SESSION['user_id']==$sys_username)
            {
                $ret=true;
            }
        }
        else{
            $ret=false;
        }

    }
    else
    {
        $ret=false;
    }


    return $ret;
}
?>