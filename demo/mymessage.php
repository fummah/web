<?php
session_start();
error_reporting(0);
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

if($gap=="Individual")
{
    $gap="Med ClaimAssist";
}
$scheme=filter_var($data['medical_scheme'], FILTER_SANITIZE_STRING);
$userName=filter_var($data['username'], FILTER_SANITIZE_STRING);
$mess="
Dear $first_name

 <br><br>

We refer to your Gap Cover claim sent to $gap.

 <br><br>

Our Claims Specialist will be dealing with $scheme, on your behalf in finalising this claim. The Scheme requires that the attached consent form be completed. This will allow us to contact your doctors to obtain details regarding your Prescribed Minimum Benefit (PMB) claim and have discussions with your Medical Scheme to support payment in line with PMB legislation.

 <br><br>

We have completed the fields (on the form) which pertain to us and request that you complete the fields which pertain to you and return it to us as soon as possible. When returning please ensure that the subject line of this email is not changed/edited as this is generated specifically for your claim.

 <br><br>

Be assured that we have your best interest at heart and will oversee the processing of the claim, in its entirety as quickly as possible.

 <br><br>

Please do not hesitate to us contact us, should you have any queries or require further assistance.

 <br><br>

Kind regards <br>

$userName";
?>

<div style="width: 60%; margin-right: auto;margin-left: auto;position: relative;padding: 20px">
    <?php
    echo $mess;
    ?>
</div>
