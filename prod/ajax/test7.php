<?php

require_once('../dbconn1.php');
$id=1031;
$conn = connection("mca", "MCA_admin");
$conn1 = connection("mca1", "MCA_admin");
require_once('../classes/leadClass.php');
$obj=new leadClass();
$email="janette@jbmedia.co.za";
$leadarr=$obj->getLead($email);
print_r($obj->getLead($email));

echo count($leadarr);
?>