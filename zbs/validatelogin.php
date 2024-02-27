<?php
error_reporting(0);
abstract Class Login{
    function loginFunction()
    {
        $message="start connecting...";
        define("access",true);
        if(isset( $_SESSION['user_id'] ))
        {
            $message = 'You are already logged in';
            // echo $message;
        }
        /*** check that both the username, password have been submitted ***/
        if(!isset($_POST['username'], $_POST['password']))
        {
            $message = 'Invalid login';

        }
        /*** check the username is the correct length ***/
        elseif (strlen( $_POST['username']) > 20 || strlen($_POST['username']) < 3)
        {
            $message = 'Invalid login';

        }

        else
        {
            session_start();
            $_SESSION['start_db']=true;
            include("dbconn.php");
            /*** if we are here the data is valid and we can insert it into database ***/
            $valid=validateXss($_POST['validnum']);
            if($valid==99)
            {
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                //$username=strtolower($username);
                //$username=ucfirst($username);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

                /*** now we can encrypt the password ***/
                $md5_password = md5( $password );
                try
                {
                    $dbh=connection("zbs","zbs");

                    /*** set the error mode to excptions ***/
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    /*** prepare the select statement ***/
                    $stmt = $dbh->prepare("SELECT a.user_id,a.username,a.password,a.role,b.group_id,c.group_name,c.parent_groups FROM users as a INNER JOIN locations as b ON a.location_id=b.location_id INNER JOIN `groups` as c ON b.group_id=c.group_id WHERE a.username=:username AND a.status=1");
                    /*** bind the parameters ***/
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    /*** execute the prepared statement ***/
                    $stmt->execute();

                    /*** check for a result ***/
                    $role = $stmt->fetch();

                    /*** if we have no result then fail boat ***/
                    if($role == false)
                    {
                        $message = 'Login Failed';
                        unset($_SESSION['start_db']);
                        session_unset();
                        session_destroy();
                        session_write_close();

                    }
                    /*** if we do have a result, all is well ***/
                    else {
                        if ($role["password"] == $md5_password || password_verify($password, $role["password"])) {
                            // session_start();
                            $username=$role["username"];
                            $_SESSION['role'] = $role["role"];
                            $_SESSION['change'] = true;
                            $_SESSION['user_id'] = $role["user_id"];
                            $_SESSION['username'] = $role["username"];
                            $_SESSION['group_id'] = $role["group_id"];
                            $_SESSION['group_name'] = $role["group_name"];
                            $_SESSION['parent_groups'] = $role["parent_groups"];
                            $_SESSION['admin_main'] = true;
                            $_SESSION['logxged'] = true;
                            $message="Success";
                        }
                        else{
                            $message = 'Incorrect login details';
                            unset($_SESSION['start_db']);
                            session_unset();
                            session_destroy();
                            session_write_close();
                        }
                    }


                }
                catch(Exception $e)
                {
                    /*** if we are here, something has gone wrong with the database ***/
                    $message = 'We are unable to process your request. Please try again later'.$e;
                    unset($_SESSION['start_db']);
                    session_unset();
                    session_destroy();
                    session_write_close();
                }
            }
            else
            {
                $message="invalid entry";
            }

        }
        echo $message;
    }
}

Class mainClass extends Login
{

}
$log=new mainClass();
$log->loginFunction();
?>