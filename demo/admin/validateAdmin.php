<?php

if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {

if($_SESSION['level']=="admin" || $_SESSION['level']=="claims_specialist" || $_SESSION['level']=="controller")
{

}
else{

unset($_SESSION['my_id']);
unset($_SESSION['user_id']);
unset($_SESSION['myUsername']);
unset($_SESSION['level']);
unset($_SESSION['role_value']);
unset($_SESSION['logxged']);
unset($_SESSION['sitePath']);
unset($_SESSION['LAST_ACTIVITY']);
unset($_SESSION['logged_in']);
session_unset();
session_destroy();
session_write_close();
   $r="<script>location.href = \"../login.html\";</script>";
    die($r);

}

}
else
{
unset($_SESSION['my_id']);
unset($_SESSION['user_id']);
unset($_SESSION['myUsername']);
unset($_SESSION['level']);
unset($_SESSION['role_value']);
unset($_SESSION['logxged']);
unset($_SESSION['sitePath']);
unset($_SESSION['LAST_ACTIVITY']);
unset($_SESSION['logged_in']);
session_unset();
session_destroy();
session_write_close();
$r="<script>location.href = \"../login.html\";</script>";
    die($r);
}
?>
