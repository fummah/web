<?php
header("Access-Control-Allow-Origin: *");
error_reporting(0);
include ("../../mca/link2.php");
$conn=connection("mca","testing");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");

class jv_import_export
{

    function fetchBTC()
    {
        global $conn;
        $claim_id="";
        $checkM=$conn->prepare('SELECT description FROM scheme_options WHERE id=1 LIMIT 1');
        $checkM->execute();
        $btc=$checkM->fetchColumn();
        return $btc;
    }


}
$n=new jv_import_export();
$json=array("btc"=>$n->fetchBTC());
echo json_encode($json,true);
//print_r($json);
?>

