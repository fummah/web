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
try{
    $_SESSION['LAST_ACTIVITY'] = time();
    $username=$control->loggedAs();
    $open=(int)$_POST['open'];
    $claim_id=(int)$_POST['claim_id'];
    $data=$control->viewSingleClaim($claim_id);
    $claim_status = $data["Open"];
    $sys_username = $data["username"];
    if($username==$sys_username || $control->isTopLevel())
    {

    }
    else{
        die("Invalid access");
    }
    $current_claim_id=$claim_id;
    $date_entered = date("Y-m-d H:i:s");
    $consent_dest=$_POST['consent_dest'];
$isreason="";
    $reason_id=0;
    if(strlen($consent_dest)<2 && $open==1)
    {
        die("Please update the destination");
    }
   
    //echo $_POST['notes'];
    $notes=filter_var($_POST['notes'], FILTER_UNSAFE_RAW);
    //echo $notes;
    $current_practice_number="0000000";
    $current_savings_scheme=(double)$_POST['schemesavings'];
    $current_savings_discount=(double)$_POST['discountsavings'];
    $vas=(double)$_POST['vas'];
    $cpt4="";
    $doc_name=validateXss($_POST['doc_name']);
    $xjson=isset($_POST['xjson'])?validateXss($_POST['xjson']):"";
    $sla=isset($_POST['sla'])?(int)$_POST['sla']:0;
    if(strlen($xjson)>4 && $open != 1)
    {
        $rs=$control->viewClaimValidations($claim_id,$xjson);
        if (in_array("0", $rs)) {
            die("Please make sure you tick all boxes on Validation Section before you close the case");
        }
    }
    $pay=validateXss($_POST['pay_doctor']);

    $status="open";
    $claim_id1=0;

    $senderId=$data["senderId"];
    $claim_number=$data["claim_number"];
    $client_id=(int)$data["client_id"];
    $reminder_time="0000-00-00 00:00:00";
    $reminder_status=0;
    if(($client_id==20 || $client_id==21 || $client_id==3 || $client_id==16 || $client_id==15 || $client_id==1 || $client_id==6) && strlen($current_practice_number)<3)
    {
        die("Please select the provider");
    }
    $insert_notes=$control->callInsertNotes($claim_id,$notes,$username,$reminder_time,$reminder_status,$claim_id1,$current_practice_number,$doc_name,$consent_dest,$open);
    if ($open == 1 && $insert_notes) {
        echo "Your notes have been added to the system";
    } else {
        if($open == 0)
        {
            $control->callUpdateClaimKey($claim_id,"Open",0);
            $control->callUpdateClaimKey($claim_id,"date_closed",$date_entered);
        }
        $status="closed";
        echo "Closed";
    }
    $arr=array("savings_scheme"=>$current_savings_scheme,"savings_discount"=>$current_savings_discount,"value_added_savings"=>$vas,"cpt_code"=>$cpt4,"pay_doctor"=>$pay,"isreason"=>$isreason,"decline_reason_id"=>$reason_id);
    foreach ($arr as $key => $value) {
        $c=$control->callUpdateDoctor($claim_id,$current_practice_number,$key,$value);
    }
    if($sla==1 && $insert_notes)
    {
        $sll=$control->viewNoteId($claim_id,$username);
    }
    if((int)$senderId>0 && (int)$senderId!=13)
    {
        
        $url = $control->viewAPIURL($senderId);
        $doctor_data=$control->viewSpecific($claim_id,$current_practice_number);
        $claimid=$doctor_data["claimedline_id"];
        $open=$claim_status==0?$claim_status:$open;
        $api=$control->sendOwlAPI($claim_number,$open,$date_entered,$notes,$current_savings_scheme,$current_savings_discount,$pay,$current_practice_number,$claimid,$url);
        if ($status=="closed")
        {
            foreach($control->viewOtherDoctors($claim_id,$current_practice_number) as $rrow)
            {
                $current_practice_number=$rrow["practice_number"];
                $claimid=$rrow["claimedline_id"];
                if(strlen($rrow["pay_doctor"])<2 || strlen($rrow["pay_doctor"]>3))
                {
                    $pay=$pay=="yes" || $pay=="no"?$pay:"no";
                }
                else{
                    $pay=$rrow["pay_doctor"];
                }
                $current_savings_scheme=(double)$rrow["savings_scheme"];
                $current_savings_discount=(double)$rrow["savings_discount"];
                $notes="Case Closed";
                $api=$control->sendOwlAPI($claim_number,$open,$date_entered,$notes,$current_savings_scheme,$current_savings_discount,$pay,$current_practice_number,$claimid,$url);
            }
        }
        echo $api;
    }

}
catch(Exception $e)
{
    echo("There is an error ".$e->getMessage());
}
