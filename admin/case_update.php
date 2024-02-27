<?php
error_reporting(0);
session_start();
require_once('dbconn.php');
$conn=connection("mca","MCA_admin");
if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {

    try{

        $_SESSION['LAST_ACTIVITY'] = time();
        $username=$_SESSION['user_id'];
        $open=validateXss($_GET['open']);
        $claim_id=validateXss($_GET['claim_id']);
        $current_claim_id=$claim_id;
        date_default_timezone_set('Africa/Johannesburg');
        $date = date("Y-m-d h:i:s");
        $notes=$_GET['notes'];
        $consent_dest=$_GET['consent_dest'];
        if(strlen($consent_dest)<2 && $open==1)
        {
            die("Please update the destination");
        }
        $notes=filter_var($notes, FILTER_SANITIZE_STRING);

        $reminder_time=validateXss($_GET['remin']);;
        $reminder_status=validateXss($_GET['remSt']);
        //$practice_number,$savings_scheme,$savings_discount
        $current_practice_number=validateXss($_GET['practice_number']);
        $current_savings_scheme=(double)validateXss($_GET['schemesavings']);
        $current_savings_discount=(double)validateXss($_GET['discountsavings']);
        $cpt4=validateXss($_GET['cpt4']);
        $doc_name=validateXss($_GET['doc_name']);
        $xjson=isset($_GET['xjson'])?validateXss($_GET['xjson']):"";
        $sla=isset($_GET['sla'])?(int)$_GET['sla']:0;
        if(strlen($xjson)>4 && $open != 1)
        {
            $rs=getClaimDetails($claim_id,$xjson);
            if (in_array("0", $rs)) {
                die("Please make sure you tick all boxes on Validation Section before you close the case");
            }
        }

        $curl = curl_init();
        $ope="";
        $closed="";
        $pay=validateXss($_GET['pay_doctor']);
        $status="open";
        $claim_number="";
        $senderId="";
        $claim_id1=(int)"";
        $sql = $conn->prepare('SELECT Open,date_closed,username,senderId,claim_number FROM claim WHERE claim_id=:claim');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->execute();
        $nu=$sql->rowCount();
        $sys_username="";
        if($nu>0) {
            foreach ($sql->fetchAll() as $row) {
                $ope = $row[0];
                $closed = $row[1];
                $sys_username = $row[2];
                $senderId=$row[3];
                $claim_number=$row[4];
            }

            if ($_SESSION['level'] == "admin" || $_SESSION['user_id'] == $sys_username || $_SESSION['user_id'] == "FumaTendai") {
                if(($_SESSION['client']==20 || $_SESSION['client']==21 || $_SESSION['client']==3 || $_SESSION['client']==16 || $_SESSION['client']==6 || $_SESSION['client']==15) && strlen($current_practice_number)<3)
                {
                    die("Please select the provider");
                }
                if ($ope == 0) {
                    //$date = $closed;
                }
                if($senderId=="KOS")
                {
                    $claim_number=validateXss($_GET['claim_number']);
                    if(empty($claim_number))
                    {
                        die("Failed, please add linked claim number");
                    }
                    $original_id=(int)checkClaimId($claim_number);
                    if($original_id>0)
                    {
                        $claim_id1=(int)$claim_id;
                        $claim_id=$original_id;
                    }
                }

                $stmt = $conn->prepare('INSERT INTO intervention(claim_id,intervention_desc,owner,reminder_time,reminder_status,claim_id1,practice_number,doc_name,consent_destination) VALUES(:claim,:notes,:owner,:reminder_time,:reminder_status,:claim_id1,:practice_number,:doc_name,:consent_destination)');
                $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
                $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
                $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
                $stmt->bindParam(':reminder_time', $reminder_time, PDO::PARAM_STR);
                $stmt->bindParam(':reminder_status', $reminder_status, PDO::PARAM_STR);
                $stmt->bindParam(':claim_id1', $claim_id1, PDO::PARAM_STR);
                $stmt->bindParam(':practice_number', $current_practice_number, PDO::PARAM_STR);
                $stmt->bindParam(':doc_name', $doc_name, PDO::PARAM_STR);
                $stmt->bindParam(':consent_destination', $consent_dest, PDO::PARAM_STR);

                $ccc= $stmt->execute();

                if ($open == 1 && $ccc) {

                    updateDoctorSavings($current_claim_id,$current_practice_number,$current_savings_scheme,$current_savings_discount,$cpt4,$pay);
                    echo "Your notes have been added to the system";

                } else {
                    updateDoctorSavings($current_claim_id,$current_practice_number,$current_savings_scheme,$current_savings_discount,$cpt4,$pay);

                    $status="closed";
                    echo "Closed";

                }
                if($sla==1 && $ccc)
                {
                    getnoteId($claim_id);
                }

                if($senderId==23 || $senderId==24 || $senderId==6)
                {
                    $claimid=getDoctor($claim_id,$current_practice_number);
                    $open=$ope==0?$ope:$open;
                    sendBack($claim_number,$open,$date,$notes,$current_savings_scheme,$current_savings_discount,$pay,$current_practice_number,$claimid,$senderId);

                    if ($status=="closed")
                    {
                        foreach(getotherDoctor($claim_id,$current_practice_number) as $rrow)
                        {
                            $current_practice_number=$rrow[0];
                            $claimid=$rrow[1];
                            if(strlen($rrow[2])<2 || strlen($rrow[2]>3))
                            {
                                $pay=$pay=="yes" || $pay=="no"?$pay:"no";
                            }
                            else{
                                $pay=$rrow[2];
                            }
                            $current_savings_scheme=(double)$rrow[3];
                            $current_savings_discount=(double)$rrow[4];
                            $notes="Case Closed";
                            sendBack($claim_number,$open,$date,$notes,$current_savings_scheme,$current_savings_discount,$pay,$current_practice_number,$claimid,$senderId);
                        }
                    }
                }

            }
            else
            {
                echo "Invalid entry";
            }
        }
        else
        {
            echo "claim not found";
        }
    }
    catch(Exception $e)
    {
        echo("There is an error ".$e->getMessage());
    }
}
else
{
    echo "Invalid Access";
}

function checkClaimId($claim_number)
{
    global  $conn;
    $mynumber="---";
    $stmt=$conn->prepare('select claim_id from claim where claim_number=:num');
    $stmt->bindParam(':num', $claim_number, PDO::PARAM_STR);
    $stmt->execute();
    $cc=$stmt->rowCount();
    if($cc>0)
    {

        $mynumber= $stmt->fetchColumn();
    }

    return $mynumber;
}
function updateDoctorSavings($claim_id,$practice_number,$savings_scheme,$savings_discount,$cpt4,$pay)
{
    global  $conn;
    $stmt=$conn->prepare('UPDATE doctors SET savings_scheme=:scheme,savings_discount=:discount,cpt_code=:cpt_code,pay_doctor=:pay WHERE claim_id=:id AND practice_number=:prac');
    $stmt->bindParam(':id', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':prac', $practice_number, PDO::PARAM_STR);
    $stmt->bindParam(':scheme', $savings_scheme, PDO::PARAM_STR);
    $stmt->bindParam(':discount', $savings_discount, PDO::PARAM_STR);
    $stmt->bindParam(':cpt_code', $cpt4, PDO::PARAM_STR);
    $stmt->bindParam(':pay', $pay, PDO::PARAM_STR);
    $stmt->execute();

}

function updateDoctor($claim_id,$practice_number,$cpt4)
{
    global  $conn;
    $stmt=$conn->prepare('UPDATE doctors SET cpt_code=:cpt_code WHERE claim_id=:id AND practice_number=:prac');
    $stmt->bindParam(':id', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':prac', $practice_number, PDO::PARAM_STR);
    $stmt->bindParam(':cpt_code', $cpt4, PDO::PARAM_STR);
    $stmt->execute();

}


function sendBack($claim_number,$status,$date_entered,$intervention_description,$scheme_savings,$discount_savings,$pay_provider,$practice_number,$claimid,$sender_id=23)
{
    $status=$status==1?"open":"closed";
    $pay_provider=$pay_provider=="yes" || $pay_provider=="no"?$pay_provider:"no";
    $myarr=array("claim_number"=>$claim_number,"status"=>$status,"date_entered"=>$date_entered,"intervention_description"=>$intervention_description,
        "scheme_savings"=>$scheme_savings,"discount_savings"=>$discount_savings,"pay_provider"=>$pay_provider,"provider_number"=>$practice_number,"claimedline_id"=>$claimid);
    $sendobj=json_encode($myarr);


    $data_string = $sendobj;
     $ch = curl_init('https://kaelo.onowls.com/owls/external/external.php?method=medclaimassist&server=live&username=externalmedclaimassist&password=r4Xr3MNJHcN4FNKF3JWAdYKBKdvgwKuMTAPUQDNBC97VbgSHX6');
    if($sender_id==24)
    {
   //$ch = curl_init('https://zesttest.onowls.com/owls/external/external.php?method=medclaimassist&server=live&username=externalmedclaimassist&password=Zest4Life99!');
   $ch = curl_init('https://zest.onowls.com/owls/external/external.php?method=medclaimassist&server=live&username=externalmedclaimassist&password=Cjksd%23R9cj31');
    }
    if($sender_id==6)
        {
            $url = 'https://admed.onowls.com/owls/external/external.php?method=medclaimassist2&server=live&username=externalmedclaimassist&password=e%3D5%3DFauc6dHGA%23%5DN5U%21VTy%3F%2A8MEZ2h';

        }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// where to post
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'authKey:e15c4c44-2ea3-4bc7-bc5d-5b7555bb9c63',
            'Content-Type", "application/raw',
            'Content-Type: application/json')
    );

    $result = curl_exec($ch);

    $err = curl_error($ch);

    curl_close($ch);

    if ($err) {
        echo "<ul><li><span style='color: red'>(Connection error (connecting to the client) #:)</span></li></ul>";
    } else {
        $ffailed=0;
        //$respons= json_decode($result, true);
        $pos8 = strpos($result, "success");
        if ($pos8 === false) {
            $ffailed=7;
            echo "<ul><li><span style='color: red'> (Failed to sent to Client)<hr>Object received : $result</span></li></ul>";
        } else {
            echo "<ul><li><span style='color: green'> (Response sent to Client)</span></li></ul>";
        }

        insertLog($claim_number,$result,$data_string,$ffailed);

    }

}
function getDoctor($claim_id,$practice_number)
{
    global  $conn;
    $stmt=$conn->prepare('SELECT claimedline_id FROM doctors WHERE claim_id=:id AND practice_number=:prac');
    $stmt->bindParam(':id', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':prac', $practice_number, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();

}
function getotherDoctor($claim_id,$practice_number)
{
    global  $conn;
    $stmt=$conn->prepare('SELECT practice_number,claimedline_id,pay_doctor,savings_scheme,savings_discount FROM doctors WHERE claim_id=:id AND practice_number<>:prac');
    $stmt->bindParam(':id', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':prac', $practice_number, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();

}
function insertLog($claim_number,$description,$description1,$failed)
{
    global  $conn;
    $stmt=$conn->prepare('INSERT INTO `jarvis_files`(`claim_number`, `desciption`,`desciption1`,`failed`) VALUES (:claim_number,:desciption,:desciption1,:failed)');
    $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
    $stmt->bindParam(':desciption', $description, PDO::PARAM_STR);
    $stmt->bindParam(':desciption1', $description1, PDO::PARAM_STR);
    $stmt->bindParam(':failed', $failed, PDO::PARAM_STR);
    $stmt->execute();

}
function getClaimDetails($claim_id,$str)
{
    global  $conn;
    $stmtf=$conn->prepare('SELECT '.$str.' FROM claim where claim_id=:claim_id');
    $stmtf->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmtf->execute();
    return $stmtf->fetch();
}
function getnoteId($claim_id)
{
    global  $conn;
    $stmt = $conn->prepare("SELECT MAX(intervention_id) FROM intervention");
    $stmt->execute();
    $intv=$stmt->fetchColumn();

    $stmupd=$conn->prepare("UPDATE claim SET sla=1,sla_note=:note WHERE sla<>1 AND claim_id=:claim_id");
    $stmupd->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmupd->bindParam(':note', $intv, PDO::PARAM_STR);
    $stmupd->execute();
}