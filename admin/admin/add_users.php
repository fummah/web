<?php
session_start();
if($_SESSION['level'] == "admin" && isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {

    include_once "../dbconn1.php";
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
                $conn1 = connection("mca", "MCA_admin");
                $sql1 = $conn1->prepare('INSERT INTO users_information(username,email,status,datetime,folder_name) VALUES(:username,:email,:status,:datetime,:folder)');
                $sql1->bindParam(':username', $username, PDO::PARAM_STR);
                $sql1->bindParam(':email', $email, PDO::PARAM_STR);
                $sql1->bindParam(':status', $state, PDO::PARAM_STR);
                $sql1->bindParam(':datetime', $datetime, PDO::PARAM_STR);
                $sql1->bindParam(':folder', $folder, PDO::PARAM_STR);
                $sql1->execute();
            }
            $conn = connection("doc", "doctors");
            $sql = $conn->prepare('INSERT INTO staff_users(username,password,role,state,email,phone,fullName,temp_user,expiry_date) VALUES(:username,:password,:role,:state,:email,:phone,:fullName,:temp_user,:expiry_date)');
            $sql->bindParam(':username', $username, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->bindParam(':role', $role, PDO::PARAM_STR);
            $sql->bindParam(':state', $state, PDO::PARAM_STR);
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':phone', $phone, PDO::PARAM_STR);
            $sql->bindParam(':fullName', $fullName, PDO::PARAM_STR);
            $sql->bindParam(':temp_user', $display_username, PDO::PARAM_STR);
            $sql->bindParam(':expiry_date', $expiry_date, PDO::PARAM_STR);
            $nu = $sql->execute();
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
else
{
    echo "There is an error";
}
?>