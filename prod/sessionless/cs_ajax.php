<?php
include "sessionLessClass.php";
$temp = new mcaSessionless\sessionLessClass();
$identity=(int)$_POST["identity_number"];
if($identity==1)
{
    $username=$_POST["username"];
    $start_date=$_POST["start_date"];
    $end_date=$_POST["end_date"];
         $arr=$temp->getDetails($username,$start_date,$end_date);
    echo json_encode($arr,true);
}
