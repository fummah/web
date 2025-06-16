<?php
define("access",true);
include "../classes/apiClass.php";
use mcaAPI\apiClass as myAPI;
$api= new myAPI();
if(!isset($_POST["identity_number"]))
{
    die("Invalid access");
}
$identity=(int)$_POST["identity_number"];
$start_date=$_POST["start_date"];
$end_date=$_POST["end_date"];
$medical_scheme=isset($_POST["medical_scheme"])?$_POST["medical_scheme"]:"";
$txtup=isset($_POST["txtup"])?$_POST["txtup"]:"";
$txtstatus=isset($_POST["txtstatus"])?$_POST["txtstatus"]:"";
$scheme_sql="";
if(!empty($medical_scheme))
{
    $medical_scheme_array=array_map('strval', $medical_scheme);
    $medical_em = implode("','",$medical_scheme_array);
    $scheme_sql=!empty($medical_scheme)?" AND b.medicalaid IN ('".$medical_em."')":"";
}

$individual="";
$claimstatus="";
if($txtup=="Top 10"){$txtup=" DESC LIMIT 10";}
elseif ($txtup=="Bottom 10"){$txtup=" ASC LIMIT 10";}
else
{
    $individual=$txtup;
    $txtup="";
}
if($txtstatus=="Rejected Claims"){$claimstatus="Claim Rejected";}
elseif ($txtstatus=="Approved Claims"){$claimstatus="Claim Approved and Paid";}


if($identity==1)
{
echo json_encode($api->getClients(),true);
}
elseif($identity==2)
{
    echo json_encode($api->getUsers(),true);
}
