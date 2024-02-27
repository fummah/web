<?php
define("access",true);
include "../classes/analysisClass.php";
use mcaAPI\analysisClass as myAPI;
$api= new myAPI();
if(!isset($_POST["identity_number"]))
{
    die("Invalid access");
}
$identity=(int)$_POST["identity_number"];
if($identity==1)
{
    $data=array("main"=>$api->mainVal(),"sub"=>$api->selectorVal());
echo json_encode($data,true);
}
elseif($identity==2)
{
    $start_date=$_POST["start_date"];
    $end_date=$_POST["end_date"];
    $open=$_POST["open"];
    $section=$_POST["section"];
    $sec_section=$_POST["sec_section"];
    $top_section=$_POST["top_section"];
    $typ=$_POST["typ"];
    $checkboxes=json_decode($_POST["checkBoxes"],true);
    $final_section=$_POST["final_section"];
    
    echo json_encode($api->getSchemeSummary($start_date,$end_date,$open,$section,$sec_section,$top_section,$typ,$checkboxes,$final_section),true);
}

?>

