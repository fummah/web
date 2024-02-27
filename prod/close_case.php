<?php
session_start();
define("access",true);
if(!isset($_POST['claim_id']))
{
    die("Invalid access");
}
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
try {

    $username = $control->loggedAs();
    $claim_id = (int)$_POST['claim_id'];
    $data=$control->viewSingleClaim($claim_id);
    $claim_status = $data["Open"];
    $sys_username = $data["username"];
    if($username==$sys_username || $control->isTopLevel())
    {

    }
    else{
        die("Invalid access");
    }
    $scheme = (double)$_POST['scheme'];
    $discount = (double)$_POST['discount'];
    $vas = (double)$_POST['vas'];
    $catergory=validateXss($_POST['catergory']);
    $ffpo=$discount+$scheme;
    if(strlen($catergory)<3 && $ffpo<1)
    {
        die("Please select the catergory");
    }
    if($ffpo>1)
    {
        $catergory="";
    }
    $date = date("Y-m-d H:i:s");


    $senderId=$data["senderId"];
    $claim_number=$data["claim_number"];
    $client_id=(int)$data["client_id"];
    $arr=array("savings_scheme"=>$scheme,"savings_discount"=>$discount,"value_added_savings"=>$vas,"Open"=>0,"date_closed"=>$date,"jv_status"=>"","savings_catergory"=>$catergory);
    foreach ($arr as $key => $value) {
        $ccc=$control-> callUpdateClaimKey($claim_id,$key,$value);

    }
    if ($ccc == 1) {
        echo "Savings Updated Successfully";
        $control->callInsertClosedLogs($claim_id,$scheme,$discount,$username);
    } else {
        echo "Failed to update";
    }
}
catch(Exception $e)
{
    echo("There is an error -> ".$e->getMessage());
}


?>                                                                        