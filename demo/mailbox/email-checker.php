<?php
session_start();
$_SESSION['start_db'] = true;
include_once ("email.php");
$conn=connection("mca","MCA_admin");
$obj=new email();
$data=$obj->getEncrpass();
$hostname1=@'smtp.gmail.com';
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = @$data[2];
$password = @$data[3];
// Call the function to read emails and reply
$obj->readAndReply($hostname, $username, $password);

?>