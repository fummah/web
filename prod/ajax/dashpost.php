<?php
//error_reporting(0);
session_start();
define("access",true);
include ("../classes/reportsClass.php");
$role=$_SESSION['level'];
$mcausername=$_SESSION['user_id'];
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$valx=$role=="admin" || $role=="controller"?"1":$mcausername;
$db=new reportsClass();
$identity = (int)validateXss($_POST['identity_number']);
if($identity==1)
{
     try {
        $display_username = validateXss($_POST['name']);
        $surname=validateXss($_POST['surname']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $phone = validateXss($_POST['phone']);
        $fullName = validateXss($display_username . " " . $surname);
        $client_name=validateXss($_POST['client_name']);
        $role = validateXss($_POST['role']);
        $state = 1;
        $all=$client_name."medclaimassist";
        $pass_temp="";
        $pass_temp1="";
        $strnum=strlen($all);
        for($i=0;$i<6;$i++)
        {
            $rand=rand(0,$strnum-1);
            $nchar=$all[$rand];
            $pass_temp.=$nchar;
        }
        for($i=0;$i<4;$i++)
        {
            $rand=rand(0,10);
            $pass_temp1.=$rand;
        }
        $password_open=$pass_temp.$pass_temp1;
        $password = password_hash($password_open, PASSWORD_BCRYPT);
        $username=$role=="claims_specialist"?$display_username:$client_name."_".$display_username;
        $effectiveDate = strtotime("+3 months", strtotime(date("Y-m-d H:i:s")));
        $expiry_date=  date("Y-m-d H:i:s", $effectiveDate);
        $folder = 'second';
        $datetime = date('Y-m-d H:I');
        if(strlen($username)>2 &&  strlen($password)>7 && !empty($role) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($fullName) && strlen($surname)>1) {
            if ($role == "claims_specialist") {
                $nu=$db->addMyInternalUser($username,$email,$state,$datetime,$folder);                
            }
           $nu=$db->addMyUser($username,$password,$role,$state,$email,$phone,$fullName,$display_username,$expiry_date);
            if ($nu == 1) {
                echo "New User successfully added.";

                echo "<hr><b>Username : <u>$display_username</u><br>Password : <u>$password_open</u> </b>";
            } else {
                echo "Failed";
            }
        }
        else
        {
            echo "Incorrect input, please check your details";
        }
    } catch (Exception $re) {
        echo "There is an error";
    }
}
elseif ($identity == 2) {
        $id = (int)$_POST['id'];
        $status = (int)$_POST['status'];
        $state = 0;
        $dd = "8521-01-01 00:00";
        if ($status == 0) {
            $state = 1;
            $dd = date('Y-m-d H:I');
        }
        if($_SESSION['level'] == "admin" && !empty($id)) {
            try {
                
                $b = $db->updateAAAUsers($id,$state,$dd);
                if ($b == 1) {
                    echo "Updated";
                } else {
                    echo "Update Failed";
                }
            } catch (Exception $c) {
                echo "Error : " . $c;
            }
        }
        else{
            echo "Invalid Entry";
        }
    }
    else if ($identity == 3) {
        $email = $_POST['email'];
        $password = validateXss($_POST['password']);
        $cc = validateXss($_POST['cc']);
        $folder = validateXss($_POST['folder']);
        $smtp = validateXss($_POST['smtp']);
        $imap = validateXss($_POST['imap']);
        $notemail = validateXss($_POST['notemail']);
        $notpass = validateXss($_POST['notpass']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && $_SESSION['level'] == "admin") {
            try {
                $b1 = $db->updateEmailConfigs($email,$password,$folder,$smtp,$imap,$cc,$notemail,$notpass);
                if ($b1 == 1) {
                    echo "Updated";
                } else {
                    echo "Update Failed";
                }
            } catch (Exception $c) {
                echo "Error";
            }
        }
        else{
            echo "Invalid entry";
        }
    }

?>