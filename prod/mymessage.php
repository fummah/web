<?php
session_start();
//error_reporting(0);
define("access",true);
if(!isset($_GET["claim_id"]))
{
    die("Invalid access");
}
include ("classes/controls.php");
include ("templates/claim_templates.php");
$control=new controls();
?>
<style>
    div{
        -webkit-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
        -moz-box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
        box-shadow: -4px -8px 29px 9px rgba(0,0,0,0.75);
    }
</style>
<?php
$claim_id=(int)$_GET["claim_id"];
$data=$control->viewSingleClaim($claim_id);
$first_name=strtolower(filter_var($data['first_name'], FILTER_SANITIZE_STRING));
$first_name=ucwords($first_name);
$arr = explode(' ',trim($first_name));
$first_name = $arr[0];

$gap=filter_var($data['client_name'], FILTER_SANITIZE_STRING);
$scheme=filter_var($data['medical_scheme'], FILTER_SANITIZE_STRING);
$userName=filter_var($data['username'], FILTER_SANITIZE_STRING);
$mess=consentEmail($first_name,$gap,$scheme,$userName,$control);
?>

<div style="width: 60%; margin-right: auto;margin-left: auto;position: relative;padding: 20px">
    <?php
    echo $mess;
    ?>
</div>
