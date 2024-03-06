<?php
session_start();
define("access",true);
$_SESSION["logxged"] = true;
$_SESSION['start_db']=true;
$_SESSION['role']="ordinary";
$_SESSION['group_id']=1;
include ("classes/DBConnect.php");
$db=new DBConnect();
/*
foreach($db->getOpenFunerals() as $row)
{
    $funeral_id = (int)$row["funeral_id"];
    $amount_paid = (double)$row["amount_paid"];
    $group_id = (int)$row["group_id"];
    $transaction_name = $row["funeral_name"];
    foreach($db->getWithBal($amount_paid,$group_id,$funeral_id) as $rowIn)
    {
        $member_id = $rowIn["member_id"];
        $account_balance = (double)$rowIn["account_balance"];
        $new_balance = $account_balance-$amount_paid;
        $db->editDiff("account_balance",$new_balance,"member_id",$member_id,"members");
        $db->addRegister($member_id,$funeral_id,"System","paid");
        $db->insertTranctions($member_id,-1*$amount_paid,"System",$transaction_name,$funeral_id);
    }
}
*/
echo "Done!!";