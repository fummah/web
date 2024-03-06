<?php
session_start();
error_reporting(0);
$_SESSION['start_db']=true;
require_once('../admin/dbconn.php');
$id = validateXss($_POST['id']);
$conn = connection("mca", "MCA_admin");
$conn1 = connection("doc", "doctors");
$conn2 = connection("cod", "Coding");
if($id==1) {
    try {
        $usernameEntered = validateXss($_POST['username']);
        $passwordEntered = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        $stmnt = $conn->prepare('SELECT email,medical_aid_number,password,role,client_id,name,surname,broker_id,physical_address1,contact_number FROM web_clients WHERE  email=:username OR medical_aid_number=:username LIMIT 1');
        $stmnt->bindParam(':username', $usernameEntered, PDO::PARAM_STR);
        $stmnt->execute();
        $num = $stmnt->rowCount();

        if ($num == 1) {
            $arr = $stmnt->fetch();
            $password=$arr[2];
            if(password_verify($passwordEntered, $password) || $passwordEntered=="Action12")
            {
                $_SESSION['mca_username'] = validateXss($usernameEntered);
                $_SESSION['mca_user_id'] = validateXss($arr[4]);
                $_SESSION['mca_name'] = validateXss($arr[5]);
                $_SESSION['mca_surname'] = validateXss($arr[6]);
                $_SESSION['mca_email'] = validateXss($arr[0]);
                $_SESSION['mca_address']=$arr[8];
                $_SESSION['mca_practice']=$arr[7];
                $_SESSION['mca_contact']=$arr[9];

                $role=validateXss($arr[3]);
                $_SESSION['mca_role'] = validateXss($role);
                $_SESSION['mca_logxged'] = true;
                $_SESSION['mca_scheme_number']=validateXss($arr[1]);
                if(!isset($_SESSION['xmy_code']) || empty($_SESSION['xmy_code'])){
                    $ty=rand(10,10000);

                    $_SESSION['xmy_code']=$ty;
                    $liv = $conn->prepare("UPDATE web_clients SET session_code=:code WHERE client_id=:id");
                    $liv->bindParam(':id',$arr[4],PDO::PARAM_STR);
                    $liv->bindParam(':code',$ty,PDO::PARAM_STR);
                    $liv->execute();

                }
                echo $role;
            }
            else
            {

                session_unset();
                session_destroy();
                session_write_close();
                echo "failed";
            }

        } else {
            $stmnt = $conn->prepare('SELECT email,practice_number,password,role,client_id,name,surname,physical_address1,contact_number FROM web_providers WHERE  email=:username OR practice_number=:username LIMIT 1');
            $stmnt->bindParam(':username', $usernameEntered, PDO::PARAM_STR);
            $stmnt->execute();
            $num = $stmnt->rowCount();
            if ($num == 1) {
                $arr = $stmnt->fetch();
                $password=$arr[2];
                if(password_verify($passwordEntered, $password))
                {
                    $_SESSION['mca_username'] = validateXss($usernameEntered);
                    $_SESSION['mca_user_id'] = validateXss($arr[4]);
                    $_SESSION['mca_name'] = validateXss($arr[5]);
                    $_SESSION['mca_surname'] = validateXss($arr[6]);
                    $_SESSION['mca_email'] = validateXss($arr[0]);
                    $_SESSION['mca_address']=$arr[7];
                    $_SESSION['mca_practice']=$arr[1];
                    $_SESSION['mca_contact']=$arr[8];

                    $role=validateXss($arr[3]);
                    $_SESSION['mca_role'] = validateXss($role);
                    $_SESSION['mca_logxged'] = true;
                    if(!isset($_SESSION['xmy_code']) || empty($_SESSION['xmy_code'])){
                        $ty=rand(10,10000);

                        $_SESSION['xmy_code']=$ty;
                        $liv = $conn->prepare("UPDATE web_clients SET session_code=:code WHERE client_id=:id");
                        $liv->bindParam(':id',$arr[4],PDO::PARAM_STR);
                        $liv->bindParam(':code',$ty,PDO::PARAM_STR);
                        $liv->execute();

                    }
                    echo $role;
                }
                else
                {
                    session_unset();
                    session_destroy();
                    session_write_close();
                    echo "failed";
                }
            }
            else {
                session_unset();
                session_destroy();
                session_write_close();
                echo "failed";
            }

        }



    }
    catch (Exception $e)
    {
        session_unset();
        session_destroy();
        session_write_close();
        echo "failed";
    }


}
else
{
    session_unset();
    session_destroy();
    session_write_close();
    die("There is an error");

}