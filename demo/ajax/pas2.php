<?php
session_start();
error_reporting(0);

if(isset($_SESSION['change']) && !empty($_SESSION['change'])) {
    require_once('../dbconn1.php');
    $identity = validateXss($_POST['identity']);
    $conn = connection("mca", "MCA_admin");
    $conn1 = connection("doc", "doctors");
    $conn2 = connection("cod", "Coding");

     if ($identity == 4) {
        $id = validateXss($_POST['id']);
        $passU = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
        $mess = "";

        try {
            $check = $conn1->prepare("SELECT user_id,old_pass FROM staff_users WHERE user_id = :num");
            $check->bindParam(':num', $id, PDO::PARAM_STR);
            $check->execute();
            $nnu = $check->rowCount();
            if ($nnu > 0) {
                $rr = $check->fetch();
                $oldpass = $rr[1];
                if(($_SESSION['level'] == "admin" || $_SESSION['my_id']==$rr[0]) && strlen($passU)>7)
                {
                    $pass = password_hash($passU, PASSWORD_BCRYPT);
                    $str = checkDuplicate($passU, $oldpass);
                    if ($str == "duplicate") {
                        $mess = "Please choose a new password";
                    } else {
                        $date1 = date("Y-m-d h:i:s");
                        $date = date_create($date1);
                        date_add($date, date_interval_create_from_date_string("120 days"));
                        $stmt = $conn1->prepare('UPDATE staff_users SET password=:pass,expiry_date=:ex,old_pass=:old WHERE user_id = :num');
                        $stmt->bindParam(':num', $id, PDO::PARAM_STR);
                        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                        $stmt->bindParam(':ex', date_format($date, "Y-m-d h:i:s"), PDO::PARAM_STR);
                        $stmt->bindParam(':old', $str, PDO::PARAM_STR);
                        $b = $stmt->execute();

                        if ($b == 1) {
                            $mess = "Password Updated";
                        } else {
                            $mess = "Update Failed";
                        }
                    }
                }
                else
                {
                    $mess = "Incorrect entry".$_SESSION['level']."===".$passU;
                }
            } else {
                $mess = "There is an Error";
            }

        } catch (Exception $c) {
            $mess = "Error";
        }
        echo $mess;
    }

     }
else
{
$mess = "Error";
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