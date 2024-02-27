<?php
error_reporting(0);
abstract Class Login{
    function loginFunction()
    {
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
        /*** check the password is the correct length ***/

        /*** check the username has only alpha numeric characters ***/
        elseif (ctype_alnum($_POST['username']) != true)
        {
            /*** if there is no match ***/
            $message = "Incorrect username or password";


        }
        /*** check the password has only alpha numeric characters ***/

        else
        {
            session_start();
            $_SESSION['start_db']=true;
            include("dbconn.php");
            /*** if we are here the data is valid and we can insert it into database ***/
            $valid=validateXss($_POST['valid']);
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
                    $dbh=connection("doc","doctors");
                    /*** $message = a message saying we have connected ***/

                    /*** set the error mode to excptions ***/
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    /*** prepare the select statement ***/
                    $stmt = $dbh->prepare("SELECT role,role_value,user_id,expiry_date,password,fullName,username,mygap,environment FROM staff_users 
                    WHERE (username = :username OR temp_user=:username) AND state=1");
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
                        if ($role[4] == $md5_password || password_verify($password, $role[4])) {
                            // session_start();
                            $username=$role[6];
                            $date1 = date("Y-m-d h:i:s");
                            $dat2 = date($role[3]);
                            $_SESSION['my_id'] = $role[2];
                            $_SESSION['change'] = true;
                            if ($dat2 < $date1) {
                                $message = "<span style='color: orange'>Your password has expired please <a href='mca_change_pass.php' style='color: green'>Click Here</a> to change</span>";
                            } else {
                                ///Update password.
                                if(!isset($_SESSION['my_code']) || empty($_SESSION['my_code'])){
                                    $ty=rand(10,10000);
                                    $_SESSION['my_code']=$ty;
                                    $liv = $dbh->prepare("UPDATE staff_users SET session_code=:code WHERE user_id=:id");
                                    $liv->bindParam(':id',$role[2],PDO::PARAM_STR);
                                    $liv->bindParam(':code',$ty,PDO::PARAM_STR);
                                    $liv->execute();

                                }

                                if($username=="KaeloXelus")
                                {
                                    $username="Kaelo";
                                }
                                if($username=="Turnberry1")
                                {
                                    $username="Turnberry";
                                }
                                $pos = strpos($username, "Western");
                                if ($pos === false) {

                                } else {
                                    $username="Western";
                                }

                                $pos = strpos($username, "Kaelo");
                                if ($pos === false) {

                                } else {
                                    $username="Kaelo";
                                }
                                $pos1 = strpos($username, "Turnberry");
                                if ($pos1 === false) {

                                } else {
                                    $username="Turnberry";
                                }
                                $pos2 = strpos($username, "Insuremed");
                                if ($pos2 === false) {

                                } else {
                                    $username="Gaprisk_administrators";
                                }
                                $posx = strpos($username, "Gaprisk");
                                if ($posx === false) {

                                } else {
                                    $username="Gaprisk_administrators";
                                }
                                   $postt = strpos($username, "Total_risk_administrators");
                                if ($postt === false) {

                                } else {
                                    $username="Total_risk_administrators";
                                }
                                $pos8 = strpos($username, "Medway");
                                if ($pos8 === false) {

                                } else {
                                    $username="Medway";
                                }
                                $pos9 = strpos($username, "Zestlife");
                                if ($pos9 === false) {

                                } else {
                                    $username="Zestlife";
                                }
                                $pos10 = strpos($username, "Admed");
                                if ($pos10 === false) {

                                } else {
                                    $username="Admed";
                                }
   $pos11 = strpos($username, "Cinagi");
                                if ($pos11 === false) {

                                } else {
                                    $username="Cinagi";
                                }
                                /*** set the session user_id variable ***/
                                //$_SESSION['id'] = $user_id;
                                $username = ucfirst($username);
                                $_SESSION['user_id'] = $username;
                                $_SESSION['myUsername'] = $username;
                                $_SESSION['level'] = $role[0];
                                $_SESSION["gap_admin"]=$role[7];
$_SESSION["ennvironment"]=$role[8];
                                if($role[0]=="admin")
                                {
                                    $_SESSION['mca_admin']=true;
                                }
                                $_SESSION['role_value'] = $role[1];
                                $_SESSION['fullname'] = $role[5];
                                $_SESSION['logxged'] = true;
                                $_SESSION['sitePath'] = "https://medclaimassist.co.za/admin";
                                //$_SESSION['sitePath'] = "localhost/medclaimassist";
                                $_SESSION['LAST_ACTIVITY'] = time();
      $ip_address=$this->get_IP_address();
                                $ennvironment="Production";
                                $liv1 = $dbh->prepare("INSERT INTO `logs`(`username`, `fullname`, `ip_address`,`ennvironment`) VALUES (:username,:fullname,:ip_address,:ennvironment)");
                                $liv1->bindParam(':username',$username,PDO::PARAM_STR);
                                $liv1->bindParam(':fullname',$role[5],PDO::PARAM_STR);
                                $liv1->bindParam(':ip_address',$ip_address,PDO::PARAM_STR);
                                $liv1->bindParam(':ennvironment',$ennvironment,PDO::PARAM_STR);
                                $liv1->execute();
                                $message = "Success";
                            }
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
                    $message = 'We are unable to process your request. Please try again later';
                    unset($_SESSION['start_db']);
                    session_unset();
                    session_destroy();
                    session_write_close();
                }
            }

        }
        echo $message;
    }
    function get_IP_address()
    {
        foreach (array('HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                    $IPaddress = trim($IPaddress); // Just to be safe

                    if (filter_var($IPaddress,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                        !== false) {

                        return $IPaddress;
                    }
                }
            }
        }
    }
}

Class mainClass extends Login
{

}
$log=new mainClass();
$log->loginFunction();
?>