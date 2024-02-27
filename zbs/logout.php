<?php
session_start();unset($_SESSION['my_id']);
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['level']);
unset($_SESSION['role']);
unset($_SESSION['logxged']);
unset($_SESSION['change']);
unset($_SESSION['name']);
unset($_SESSION['surname']);
session_unset();
session_destroy();
session_write_close();
header("Location: index.php");

?>

   