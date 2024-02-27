<?php
session_start();
define("access",true);
if(!isset($_POST["identity_number"]))
{
    die("Invalid access");
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include ("../classes/controls.php");
include ("../templates/claim_templates.php");
$control=new controls();

$identity = (int)$_POST['identity_number'];
if ($identity == 1) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $practice_number = validateXss($_POST['practice_number']);
        if (!empty($practice_number)) {
            $practice_number = str_pad($practice_number, 7, '0', STR_PAD_LEFT);
            if($control->viewDoctor($practice_number)==true) {
                $doctor = $control->viewDoctor($practice_number);
                $doctor_name = $doctor["name_initials"];
                $doctor_surname = $doctor["surname"];
                $contact = "(" . $doctor["tel1code"] . ")" . $doctor["tel1code"];
                $gives_discount = $doctor["gives_discount"];
                $doctor_id = $doctor["doc_id"];
                $doctor_arr=array("doctor_name"=>$doctor_name,"doctor_surname"=>$doctor_surname,"contact"=>$contact,"gives_discount"=>$gives_discount,"doctor_id"=>$doctor_id);
                echo json_encode($doctor_arr);
            }
            else
            {
                echo "Failed to find this Doctor";
            }
        }

    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
else if ($identity == 2) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $scheme_name = filter_var($_POST['medical_scheme'], FILTER_SANITIZE_STRING);
        $scheme_name = htmlspecialchars($scheme_name);
        $scheme_name = my_utf8_decode($scheme_name);
        $scheme_name = trim($scheme_name);
        $countScheme = count($control->viewSchemeOptions($scheme_name));
        if ($countScheme > 0) {
            echo json_encode($control->viewSchemeOptions($scheme_name), JSON_NUMERIC_CHECK);
        }
        else{
            echo "No Scheme Option";
        }

    } catch (Exception $e) {
        echo("There is an error : ".$e->getMessage());
    }
}
else if ($identity == 3) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        echo json_encode($control->viewAllICD10());
    } catch (Exception $e) {
        echo("There is an error : ".$e->getMessage());
    }
}
else if ($identity == 4) {
    try {
        $icd10_code=validateXss($_POST["icd10_code"]);
        if($control->viewICD10Details($icd10_code)==true)
        {
            $data=$control->viewICD10Details($icd10_code);
            $diag_code=$data["diag_code"];
            $pmb_code=$data["pmb_code"];
            $shortdesc=$data["shortdesc"];
            if (strlen($pmb_code) > 1) {
                echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><h4><span uk-icon=\"icon: info\"></span> Diagonisis Code : $diag_code (PMB)</h4><p>PMB Code : $pmb_code</p><p>Description : $shortdesc</p></div>";
            } else {
                echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><h4><span uk-icon=\"icon: close\"></span> Diagonisis Code : $diag_code (Non-PMB)</h4><p>PMB Code : $pmb_code</p><p>Description : $shortdesc</p></div>";
            }
        }
        else
        {
            echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><h4><span uk-icon=\"icon: close\"></span> Invalid ICD10 Code</h4></div>";
        }
    } catch (Exception $e) {
        $error="There is an error : ".$e->getMessage();
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p><span uk-icon=\"icon: close\"></span> $error</p></div>";
    }
}
else if ($identity == 5) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $client_id=(int)$_POST["client_id"];
        if($client_id==31)
        {
            $new_claim = $control->generateClaimNumber($client_id);
            $remove_net=(int)str_replace("ASPEN","",$new_claim);
            $temp_number = $remove_net + 1;
            $pad_number=str_pad($temp_number,5,"0",STR_PAD_LEFT);
            echo "ASPEN" . $pad_number;

        }
        else if($client_id==33)
        {
            $new_claim = $control->generateClaimNumber($client_id);
            $remove_net=(int)str_replace("NET","",$new_claim);
            $temp_number = $remove_net + 1;
            $pad_number=str_pad($temp_number,5,"0",STR_PAD_LEFT);
            echo "NET" . $pad_number;
        }
        else
        {
            $new_claim = $control->generateClaimNumber($client_id);
            $remove_net=(int)str_replace("MCA","",$new_claim);
            $temp_number = $remove_net + 1;
            $pad_number=str_pad($temp_number,5,"0",STR_PAD_LEFT);
            echo "MCA" . $pad_number;

        }
    } catch (Exception $e) {
        echo("There is an error : ".$e->getMessage());
    }
}
else if ($identity == 6) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $claim_number=validateXss($_POST["claim_number"]);
        $client_id=(int)$_POST["client_id"];
        if($control->validateClaim($claim_number,$client_id)>0)
        {
            echo "Duplicate Claim";
        }
        else{
            echo "New Claim";
        }
    } catch (Exception $e) {
        echo("There is an error : ".$e->getMessage());
    }
}
else if ($identity == 7) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $claim_line_id=(int)$_POST["claim_line_id"];
        echo json_encode($control->viewEachClaimLine($claim_line_id));
    } catch (Exception $e) {
        echo("There is an error : ".$e->getMessage());
    }
}
else if ($identity == 8) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $service_date_from = validateXss($_POST['sd']);
        $tariff_code = validateXss($_POST['tc']);
        $primaryICDCode = validateXss($_POST['icd']);
        $clmnline_charged_amnt = (double)$_POST['ca'];
        $clmline_scheme_paid_amnt = (double)$_POST['ss'];
        $gap = (double)$_POST['gap'];
        $msg_code = validateXss($_POST['res']);
        $treatmentDate = validateXss($_POST['trt']);
        $gap_aamount_line = (double)$_POST['act_gap'];
        $myid = validateXss($_POST['myid']);
        $cpt = validateXss($_POST['cpt']);
        $claim_id = (int)$_POST['claim_id'];
        $practice_number = validateXss($_POST['practice_number']);
        $benefit_description = validateXss($_POST['invoice_date']);
        $createdBy="System";
        $pmb="N";
        if($control->viewICD10Details($primaryICDCode)==true) {
            $data = $control->viewICD10Details($primaryICDCode);
            $pmb_code = $data["pmb_code"];
            $pmb_code = strlen($pmb_code);
            if (strlen($pmb_code) > 0 && $pmb_code!=0) {
                $pmb="Y";
            }
        }
        $control->checkDoctorTRCP1($practice_number,$cpt);
        if(!$control->checkDoctorTRCP2($practice_number)) {
            $result = $control->checkAllDoctorsCPT4($claim_id);
            $result1 = (explode("---", $result));
            if (count($result1) > 1) {
                if (strlen($result1[1]) < 2) {
                    //die("Please Enter CPT4");
                }
            }
        }
        if($control->callInsertClaimLine($claim_id,$practice_number,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$gap,$tariff_code,$service_date_from,$primaryICDCode,$pmb,$benefit_description,$gap_aamount_line,$msg_code,$treatmentDate,$createdBy))
        {
            echo "Done!!!";
            $c=$control->callUpdateDoctor($claim_id,$practice_number,"cpt_code",$cpt);
            $control->amountsProcess($claim_id);
 $emergencyarr=explode(",",$control->viewValidationsInd(5));
             if(in_array($tariff_code, $emergencyarr))
                                            {
                                                $control->callUpdateClaimKey($claim_id,"emergency","1");
                                            }
        } else {
            echo "Failed!!!";
        }

    } catch (Exception $e) {
        echo("There is an error : ".$e->getMessage());
    }
}
else if ($identity == 9) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $id = (int)$_POST['practice_number'];
        $tariff_code=validateXss($_POST['tc']);
        $primaryICDCode=validateXss($_POST['icd']);
        $clmnline_charged_amnt=(double)$_POST['ca'];
        $clmline_scheme_paid_amnt=(double)$_POST['ss'];
        $claimline_memberportion=(double)$_POST['gap'];
        $gap_aamount_line=(double)$_POST['act_gap'];
        $pmb="N";
        if($control->viewICD10Details($primaryICDCode)==true) {
            $data = $control->viewICD10Details($primaryICDCode);
            $pmb_code = $data["pmb_code"];
            $pmb_code = strlen($pmb_code);
            if (strlen($pmb_code) > 0 && $pmb_code!=0) {
                $pmb="Y";
            }
        }
        $benefit_description=validateXss($_POST['invoice_date']);
        $msg_code=validateXss($_POST['res']);

        $clmn_line_pmnt_status="";
        $treatmentDate=validateXss($_POST['trt']);
        $cpt=validateXss($_POST['cpt']);
        $claim_id=(int)$_POST['claim_id'];

        $practice_number="";
        $practice_type="";

        $all=$control->checkBenefit($msg_code);
        $lng_msg_dscr=$all["lng"];
        $msg_dscr=$all["shrt"];
        if($control->callEditClaimLine($id,$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$claimline_memberportion,$tariff_code,$primaryICDCode,$pmb,$benefit_description,$msg_code,$clmn_line_pmnt_status,$treatmentDate,$gap_aamount_line,$lng_msg_dscr,$msg_dscr))
        {
            $control->amountsProcess($claim_id);
            $control->callInsertDoctorClaimLogs($claim_id,$practice_number,"claim line",$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$gap_aamount_line,$control->loggedAs());
 $emergencyarr=explode(",",$control->viewValidationsInd(5));
             if(in_array($tariff_code, $emergencyarr))
                                            {
                                                $control->callUpdateClaimKey($claim_id,"emergency","1");
                                            }
            echo "Done!!!";
        }
        else {
            echo "Failed!!!";
        }


    } catch (Exception $e) {
        echo("There is an error : ".$e->getMessage());
    }
}
elseif($identity==10)
{
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $claimline = (int)$_POST["claimline_id"];
        $claim_id=$control->viewClaimIDfromClaimLine($claimline);
$ddata=$control->viewEachClaimLine($claimline);
        $dd=$control->clearClaimLine($claimline);
        if($dd==1)
        {
            
            $practice_number=$ddata["practice_number"];
            $clmnline_charged_amnt=$ddata["clmnline_charged_amnt"];
            $clmline_scheme_paid_amnt=$ddata["clmline_scheme_paid_amnt"];
            $gap_aamount_line=$ddata["gap_aamount_line"];
            $control->callInsertDoctorClaimLogs($claim_id,$practice_number,"claim line",$clmnline_charged_amnt,$clmline_scheme_paid_amnt,$gap_aamount_line,$control->loggedAs());
            $control->amountsProcess($claim_id);
            echo "Deleted";

        }
        else{
            echo "Failed to delete";
        }
    }
    catch (Exception $e){
        echo "There is an error ".$e->getMessage();
    }
}
elseif($identity==11)
{
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    $note_id = (int)$_POST['note_id'];
    try {
        $rowD=$control->viewSingleNote($note_id);
        $id = $rowD[0];
        $claim_id = $rowD[1];
        $desc = $rowD[2];
        $date_entered = $rowD[3];
        $owner = $rowD[4];
        $sys_username = $rowD[5];
        if($control->callInsertNoteLog($id,$claim_id,$desc,$date_entered,$owner))
        {
            if ($control->callDeleteNote($note_id)) {
                echo "Deleted";
            } else {
                echo "Deletion Failed";
            }
        }
        else{
            echo "There is an error";
        }


    } catch (Exception $c) {
        echo "There is an error, try again ".$c;
    }
}

else if ($identity == 13) {
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    $note_id = (int)$_POST['textid'];
    $note = filter_var($_POST['text'], FILTER_SANITIZE_STRING);
    $note=htmlspecialchars($note);
    $note=my_utf8_decode($note);
    $note=trim($note);

    try {
        $rowD=$control->viewSingleNote($note_id);
        $id = $rowD[0];
        $claim_id = $rowD[1];
        $desc = $rowD[2];
        $date_entered = $rowD[3];
        $owner = $rowD[4];
        $sys_username = $rowD[5];
        if($control->callInsertNoteLog($id,$claim_id,$desc,$date_entered,$owner)) {
            if ($control->callUpdateNote($note_id, $note)) {
                echo "<b style='color: green'>Updated!!!</b>";
            } else {
                echo "<b style='color: red'>Failed!!!</b>";
            }
        }
    } catch (Exception $e) {
        echo "<b style='color: red'>There is an error!!!</b>";
    }

}
elseif($identity==14)
{
    if($control->isInternal() || isset($_POST["from_live"]))
    {
    }
    else{
         $json_arr=array("purple_total"=>0,"red_total"=>0,"orange_total"=>0,"green_total"=>0,"next_claim_id"=>0);
        echo json_encode($json_arr);
        die();
    }
    try {
        $val=1;
        $condition=":username";
        if($control->isClaimsSpecialist())
        {
            $condition="username=:username";
            $val=$control->loggedAs();
        }
        elseif ($control->isGapCover())
        {
            $condition="c.client_name=:username";
            $val=$control->loggedAs();
        }
        $nonotes_array=$control->viewNoNotesClaims($condition,$val);
        $notes_array=$control->viewNotesClaims($condition,$val);
        $all_array=array_merge($nonotes_array,$notes_array);
        $purple_arr=array();
        $red_arr=array();
        $orange_arr=array();
        $green_arr=array();
        $all_array_sort=asort($all_array);
        foreach ($all_array as $row)
        {
            $claim_id=$row["claim_id"];
            $date_entered=$row["date_entered"];
            $status_type=$row["status_type"];
            $date_closed=$row["date_closed"];
            $date_reopened=$row["date_reopened"];
            $date_closed=$row["date_closed"]!== null?$row["date_closed"]:"-";
            $date_reopened=$row["date_reopened"]!== null?$row["date_reopened"]:"-";
            if(strlen($date_reopened)<2 && strlen($date_closed)>10)
            {
                $dat0=$control->viewClaimDate($claim_id,$date_reopened,$date_entered);
                $date_entered=$date_entered>$dat0?$date_entered:$dat0;
            }
            $from_date1 = date('Y-m-d', strtotime($date_entered));
            $days=round($control->getWorkingDays($from_date1,$control->todayDate(),$control->holidays()));
            $arr=array("date_entered"=>$date_entered,"claim_id"=>$claim_id,"days"=>$days);
            if($days>2 && $status_type == "No_Notes")
            {
                array_push($purple_arr,$arr);
            }
            elseif($days>2)
            {
                array_push($red_arr,$arr);
            }
            elseif($days==2 && $status_type == "No_Notes")
            {
                array_push($red_arr,$arr);
            }
            elseif ($days==2)
            {
                array_push($orange_arr,$arr);
            }

            else
            {
                array_push($green_arr,$arr);
            }

        }
        $count_purple=count($purple_arr);
        $count_red=count($red_arr);
        $count_orange=count($orange_arr);
        $count_green=count($green_arr);
        $next_claim_id=0;
        if($count_purple>0)
        {
            $next_claim_id=$purple_arr[0]["claim_id"];
        }
        elseif($count_red>0)
        {
            $next_claim_id=$red_arr[0]["claim_id"];
        }
        elseif($count_orange>0)
        {
            $next_claim_id=$orange_arr[0]["claim_id"];
        }
        else
        {
            $next_claim_id=$green_arr[0]["claim_id"];
        }
        $json_arr=array("purple_total"=>$count_purple,"red_total"=>$count_red,"orange_total"=>$count_orange,"green_total"=>$count_green,"next_claim_id"=>$next_claim_id);
        echo json_encode($json_arr);
    }
    catch (Exception $e)
    {
        $json_arr=array("purple_total"=>0,"red_total"=>0,"orange_total"=>0,"green_total"=>0,"next_claim_id"=>0);
        echo json_encode($json_arr);
    }
}
elseif($identity==15)
{
    if(!$control->isInternal())
    {
        $arr=array("claim_number"=>"","full_name"=>"","contact_number"=>"","email"=>"","client_name"=>"","policy_number"=>"","Service_Date"=>"","notice"=>"");
        echo json_encode($arr);
        die();
    }
    try {
        $claim_id=(int)$_POST["claim_id"];
        $data=$control->viewSingleClaim($claim_id);
        $notice=$control->viewTemplate("notice_board");
        $arr=array("claim_number"=>$data["claim_number"],"full_name"=>$data["first_name"]." ".$data["surname"],"contact_number"=>$data["cell"],"email"=>$data["email"],"client_name"=>$data["client_name"],"policy_number"=>$data["policy_number"],"Service_Date"=>$data["Service_Date"],"notice"=>$notice);
        echo json_encode($arr);
    }
    catch (Exception $e)
    {
        $json_arr=array("claim_number"=>"","full_name"=>"","contact_number"=>"","email"=>"");
        echo json_encode($json_arr);
    }
}
elseif($identity==16)
{
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $claim_id=(int)$_POST["claim_id"];
        $arr=$control->viewNotes($claim_id);
        rsort($arr);
        homeNotes($arr,$control,"Notes",1);
    }
    catch (Exception $e)
    {
        echo "There is an error -> ".$e->getMessage();
    }
}
elseif($identity==17)
{
    if(!$control->isInternal())
    {
        die();
    }
    try {
        $role=$control->myRole();
        $mcausername=$control->loggedAs();
        $condition=$control->isTopLevel()?":username":"username=:username";
        $val=$control->isTopLevel()?"1":$mcausername;
        $member_arr=$control->view4DaysMembers($condition,$val);
        $file_arr=$control->viewUpdatedDocs($condition,$val);
        $zer_arr=$control->viewZeroAmounts($condition,$val);
        $json_arr=array("members"=>$member_arr,"files"=>$file_arr,"zeros"=>$zer_arr);
        echo json_encode($json_arr);
    }
    catch (Exception $e)
    {
        $json_arr=array("members"=>"","files"=>"","zeros"=>$e->getMessage());
        echo json_encode($json_arr);
    }
}
elseif($identity==18)
{
    if(!$control->isInternal())
    {
        die("Invalid access");
    }
    try {
        $intervention_desc="This claim was sent for clinical review.";
        $claim_id=(int)$_POST["claim_id"];
        $type="admed";
        $data=$control->viewSingleClaim($claim_id);
        /*
        if((int)$data["client_id"]==6)
        {
            $username1=$data["username"];
        }
        else
        {
            $data_lat=$control->viewLatestUser();
            $username1=$data_lat["username"];
            $type="not_admed";
        }
*/
        $open=1;
        if($control->viewClinicalNote($claim_id,$intervention_desc)>0 && ((int)$data["client_id"]==34 || (int)$data["client_id"]==35))
        {
            $open=4;
        }
        $result=$control->callUpdateClaimKey($claim_id,"Open",$open);
        if($result>0)
        {
            /*
            if($type=="not_admed")
            {
                $control->callUpdateClaimKey($claim_id,"username",$username1);
                $control->callUpdateUser($username1);
            }
            */
            echo"<div class=\"uk-alert-success\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p>Successfully allocated the claim to <b>$username1</b></p>
</div>";
        }
        else{
            echo "";
            echo"<div class=\"uk-alert-success\" uk-alert>
    <a class=\"uk-alert-close\" uk-close></a>
    <p>Failed to allocate the claim</p>
</div>";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error - ".$e->getMessage();
    }
}
elseif($identity==19)
{
    if(!$control->isAdmin())
    {
        die("Invalid access");
    }
    $claim_id = (int)$_POST['claim_id'];
    try {
        if ($control->clearClaim($claim_id)>0) {
            echo "Deleted";
        } else {
            echo "Deletion Failed";
        }
    } catch (Exception $e) {
        echo "There is an error, try again ".$e->getMessage();
    }
}
elseif($identity==20 && $control->isInternal())
{
    try {

        $claim_id=(int)$_POST["claim_id"];
        echo json_encode($control->viewConfirmOptions($claim_id));
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }

}
elseif($identity == 21 && $control->isInternal())
{
    $claim_id=(int)$_POST["claim_id"];
    try {
        $claim_data=$control->viewSingleClaim($claim_id);
        $date_entered=$claim_data["date_entered"];
        $member_contacted=$claim_data["member_contacted"];
        $open=$claim_data["Open"];
        $coding_checked=$claim_data["coding_checked"];
        $date_closed=$claim_data["date_closed"]!== null?$claim_data["date_closed"]:"";
        $date_reopened=$claim_data["date_reopened"]!== null?$claim_data["date_reopened"]:"";
        $client_id=$claim_data["client_id"];
        $current_date=date('Y-m-d H:i:s');
        if($control->myRole()=="claims_specialist")
        {
        $control->callUpdateClaimKey($claim_id,"new",1);
    }
        $control->callUpdateClaimKey($claim_id,"recent_date_time",$current_date);
        if($client_id==1) {
            if(strlen($date_closed)>10)
            {
                $line_data=$control->viewLatestClaimLine($claim_id);
                $claim_linedate=$line_data["date_entered"];
                $date_reopened=$date_reopened>$claim_linedate?$date_reopened:$claim_linedate;
            }
        }
        $date_entered = strlen($date_reopened)>10?$date_reopened:$date_entered;
        $today = date('Y-m-d');
        $from_date1 = date('Y-m-d', strtotime($date_entered));
        $days=$control->getWorkingDays($from_date1,$today,$control->holidays());
        //echo $days."----".$member_contacted."---".$open;
        if($days>=4 && $member_contacted != 1 && $open==1)
        {
            echo "<div class=\"uk-alert-danger nothing1\" uk-alert><a class=\"uk-alert-close\" uk-close> </a><p>Please update the Member/Broker as this case is 4 days (or more). </p>                
                <label><input class=\"uk-checkbox\" id='contactmember' type=\"checkbox\" onclick='showhide(\"contactmember\",\"membspan\")'> <span>Member/Broker Contacted?</span></label><span id='membspan' style='display: none'> <textarea class=\"uk-textarea\"  id='memtxt'></textarea><br><button class=\"uk-button uk-button-primary uk-button-small\"  onclick='updateMember(\"$claim_id\")'>Update</button></span></div>";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error ".$e->getMessage();
    }

}
elseif($identity==22 && $control->isInternal())
{
    try {

        $doc=$_POST["doc"];
        $claim_id=(int)$_POST["claim_id"];
        $txt=$_POST["txt"];
        $nu=$control->callUpdateClaimKey($claim_id,"member_contacted",$doc);
        if($nu==1)
        {
            $control->callInsertNotes($claim_id,$txt);
            echo "Updated";
        }
        else
        {
            echo "Failed";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
elseif($identity==23 && $control->isGapCover())
{
    try {
        $search_term=$_POST["search_term"];
        if($control->viewAllClaims($control->myRole(),0 ,1,$control->loggedAs(),$search_term,1,0)>0) {
            foreach ($control->viewAllClaims($control->myRole(), 0, 1, $control->loggedAs(), $search_term, 0, 0) as $row) {
                $name = htmlspecialchars(strtoupper($row[0] . " " . $row[1]));
                $policy = htmlspecialchars(strtoupper($row[2]));
                $claim_number = htmlspecialchars(strtoupper($row[3]));
                $claim_id = $row["claim_id"];
                echo "<table class='striped uk-table' style='border: 2px solid whitesmoke'><tr><th>Name</th><th>Pol.No</th><th>Claim No</th></tr><tr><td>$name</td><td>$policy</td><td>";
                echo "<form action='case_details.php' method='post'>";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                echo "<button style='background: none;border: none;color: #54bf99;text-decoration: underline;cursor: pointer;' name=\"btn\">$claim_number</button>";
                echo "</form>";
                echo "</td></tr></table>";
            }
        }
        else{
            echo "<p style='color: red'> Claim not found</p>";
        }

    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
elseif($identity==24 && $control->isInternal())
{
    $claim_id=(int)$_POST["claim_id"];
    $vaclinix=array();
    $arx05=[];
    $inhosparr=[];
    $vaclini=["N"];
    $codes3=[];
    $emegncyarr=[];
    $cdarry=[];
    $icu=["1204", "1205","1206","1207","1208","1209","1210"];
    try {
        $count_lines=0;
        foreach ($control->viewClaimDoctor($claim_id) as $doctor_row) {
            $practice_number = str_pad($doctor_row["practice_number"], 7, '0', STR_PAD_LEFT);
            $doctor = $control->viewDoctor($practice_number);
            $disciplinecode = $doctor["disciplinecode"];
            $doctor_cpt4 = $doctor_row["cpt_code"];
            if ($disciplinecode == "010" || $disciplinecode == "10") {
                array_push($vaclinix, $practice_number);
            }
            if ($control->checkMofifier($claim_id, $practice_number, $disciplinecode)) {
                array_push($arx05, $practice_number);
            }
            $descipline_code_array = ["56", "57", "58", "59", "056", "057", "058", "059"];

            if (in_array($disciplinecode,$descipline_code_array)) {
                array_push($inhosparr, $practice_number);
            }
            foreach ($control->viewClaimline($claim_id, $practice_number) as $line_row) {
                array_push($emegncyarr, $line_row["tariff_code"]);
                array_push($codes3, $line_row["tariff_code"]);
                $icd10=$line_row["primaryICDCode"];
                if ($control->isPMB($icd10)=="Y") {
                    array_push($vaclini,"Y");
                }
                $count_lines++;
                $mycoding=$control->viewCoding($line_row["tariff_code"],$icd10,$doctor_cpt4);
                if(count($mycoding)>0 && !in_array($line_row["tariff_code"],$icu))
                {
                    $mxr=array("number"=>$count_lines,"descr"=>implode(";",$mycoding));
                    array_push($cdarry,$mxr);

                }
            }
        }
        validations($claim_id, $control, $vaclinix, $arx05, $inhosparr, $vaclini, $emegncyarr, $codes3,$cdarry);
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }

}
elseif($identity==25 && $control->isInternal())
{
    try {

        $doc=$_POST["doc"];
        $claim_id=(int)$_POST["claim_id"];
        $txt=$_POST["txt"];
        $idx=$_POST["idx"];
        $nu=$control->callUpdateClaimKey($claim_id,"icd10_emergency",$doc);
        if($nu==1)
        {
            $control->callInsertConfirm($claim_id,$txt,$idx);
            echo "Updated";

        }
        else
        {
            echo "Failed";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
elseif($identity==26 && $control->isInternal())
{
    try {
//provider_zf
        $doc=$_POST["doc"];
        $claim_id=(int)$_POST["claim_id"];
        $txt=$_POST["txt"];
        $idx=$_POST["idx"];
        $nu=$control->callUpdateClaimKey($claim_id,"provider_zf",$doc);
        if($nu==1)
        {
            $control->callInsertConfirm($claim_id,$txt,$idx);
            echo "Updated";
        }
        else
        {
            echo "Failed";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
elseif($identity==27 && $control->isInternal())
{
    try {
        $doc=$_POST["doc"];
        $claim_id=(int)$_POST["claim_id"];
        $txt=$_POST["txt"];
        $idx=$_POST["idx"];
        $nu=$control->callUpdateClaimKey($claim_id,"is_atheniest",$doc);
        if($nu==1)
        {
            $control->callInsertConfirm($claim_id,$txt,$idx);
            echo "Updated";
        }
        else
        {
            echo "Failed";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }

}
else if ($identity == 28) {

    $user=$control->loggedAs();
    $url=$_POST["url"];
    try {
        $control->callInsertVisitLogs($user,$url);
        echo "success.==".$_SESSION["level"];

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
else if ($identity == 29) {
    $val = validateXss($_POST['vall']);
    $_SESSION['level'] = $val;
    echo $val;

}else if ($identity == 30) {
    $claim_id = validateXss($_POST['claim_id']);
    $note = $_POST['text'];
    $note = filter_var($_POST['text'], FILTER_SANITIZE_STRING);
    $note=htmlspecialchars($note);
    $note=my_utf8_decode($note);
    $note=trim($note);
    $entered_by=$control->loggedAs();
    $claim_data=$control->viewSingleClaim($claim_id);
    $claim_user=$claim_data["username"];
    $claim_number=$claim_data["claim_number"];
    $user_data=$control->viewUserInformation($claim_user);
    $email=$user_data["email"];
    $subject="Update on claim [$claim_number]";
    $copied_email="shirley@medclaimassist.co.za";
    $body="Hi<br><br>Update on claim : <br><br><b>$note</b><br><br>Medclaim Assist Team";
    try {
        $mail = new PHPMailer(true);
        if ($control->call8days($note,$claim_id,$entered_by) > 0) {
            $email_data=$control->viewEmailCredentils();
            $from_email=$email_data["notification_email"];
            $from_password=$email_data["notification_password"];
            if($claim_user==$entered_by)
            {
                $control->sendEmail($mail,$from_email,"MCA",$from_password,$copied_email,"MCA System User",$subject,$body);
            }
            else
            {
                $control->sendEmail($mail,$from_email,"MCA",$from_password,$email,"MCA System User",$subject,$body,0,"",$copied_email);
            }
            echo "Successfully Added!!!";
        } else {
            echo "Failed";
        }
    } catch (Exception $e) {
        echo "There is an error : ".$e->getMessage();
    }
}
else if ($identity == 31) {
    $arr=array();
    foreach ($control->viewActiveUsers() as $row) {

        $username = $row["username"];
        $mydate = date('Y-m');
        $claims_value = $control->viewClaimValue($mydate, "username=:username", $username);
        $savings = $control->viewMonthlySavings($mydate, "username=:username", $username);

        $perc = $claims_value>0?(int)round(($savings / $claims_value) * 100):0;

        $alignperc = $perc * 10;
        $nummove = $alignperc + 100;
        $arrinn=array("username"=>$username,"horsemove"=>$alignperc,"textmove"=>$nummove);
        array_push($arr,$arrinn);
    }
    echo json_encode($arr,true);
}
else if($identity==32)
{
    try {

        $doc=(int)$_POST["doc"];
        $claim_id=(int)$_POST["claim_id"];
        $txt=$_POST["txt"];
        $nu=$control->callUpdateClaimKey($claim_id,"coding_checked",$doc);
        if($nu==1)
        {
            $control->callInsertConfirm($claim_id,$txt,4);
            echo "Updated";
        }
        else
        {
            echo "Failed";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }

}
else if($identity==33)
{
    $val=1;
    $condition=":username";
    $rolex="other";
    if($control->isClaimsSpecialist())
    {
        $condition="username=:username";
        $val=$control->loggedAs();
        $rolex="cs";
    }
    elseif ($control->isGapCover())
    {
        $condition="c.client_name=:username";
        $val=$control->loggedAs();
    }
    //INNER JOIN clients as c ON b.client_id=c.client_id
 $data=$control->viewOpenNew($condition,$val);
    $open_claims=$data["det"]["open1"];
    $new_claims=$data["det"]["new1"];
    $preassessment=$data["det"]["preassess1"];
    $leads=$control->isGapCover()?0:$control->viewLeads($condition,$val);    
    $qa=$control->viewQA($condition,$val,$rolex);
    $clinical=$data["clinical"];
    $owls_error=$data["owlserror"];
    $new_claims-=$preassessment;
    $sub_rep_arr=array("open_claims"=>$open_claims,"new_claims"=>$new_claims,"leads"=>$leads,"preassessment"=>$preassessment,"qa"=>$qa,"clinical"=>$clinical,"owls_error"=>$owls_error);
    echo json_encode($sub_rep_arr);

}
else if($identity==34)
{
    $savings_date=date("Y-m");
    $val=1;
    $condition=":username";
    if($control->isClaimsSpecialist())
    {
        $condition="username=:username";
        $val=$control->loggedAs();
    }
    elseif ($control->isGapCover())
    {
        $condition="c.client_name=:username";
        $val=$control->loggedAs();
    }
    $savings=$control->viewGetSavings($savings_date,$condition,$val);
    $closed_claims=$control->viewClosedThisMonth($condition,$val);
    $entered_claims=$control->viewEnteredthisClaims($savings_date,$condition,$val);
    $seconds=$control->viewAveragethisClaims($savings_date,$condition,$val);
  
    if($entered_claims<1)
    {
        $average=0;
    }
    else {
     $average = round($seconds);
    }
    $scheme_savings=number_format((double)$savings["scheme_savings"],2,'.',',');
    $discount_savings=number_format((double)$savings["discount_savings"],2,'.',',');
    $total_savings=number_format((double)$savings["sav"],2,'.',',');
    $sub_rep_arr=array("scheme_savings"=>$scheme_savings,"discount_savings"=>$discount_savings,"total_savings"=>$total_savings,"average"=>$average,"closed_cases"=>$closed_claims,"total_cases"=>$entered_claims);
    echo json_encode($sub_rep_arr);

}
else if($identity==35)
{
    try {
        if(strlen($_POST["keyword"])>0) {
            $keyword=$_POST["keyword"];
            $ccount=count($control->viewICD10five($keyword));
            $msg="";
            if($ccount>0)
            {
                $msg="<ul id=\"country-list\" class=\"uk-card uk-card-body uk-card-default\">";
                foreach ($control->viewICD10five($keyword) as $row)
                {
                    $icdcode=$row["diag_code"];
                    $description=$row["shortdesc"];
                    $msg.="<li onClick=\"selectICD('$icdcode')\">$icdcode<br><span style=\"color: #fff; font-size: small\">$description</span></li>";
                }
                $msg.="</ul>";
            }
            echo $msg;
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
else if($identity==36)
{
    $claim_id=(int)$_POST["claim_id"];
    $qa_status=(int)$_POST["qa_status"];
    try {

        if($control->isTopLevel()) {
            if($control->callUpdateClaimKey($claim_id,"quality",$qa_status))
            {
                echo "Updated";
            }
            else
            {
                echo "Failed";
            }
        }
        else
        {
            echo "Invalid Entry";
        }

    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
else if($identity==37) {
    $error_id = (int)$_POST["error_id"];
    $sender_id = (int)$_POST["sender_id"];
    $claim_number = $_POST["claim_number"];
    $url = $control->viewAPIURL($sender_id);

    $data_string=$control->viewOwlsById($error_id);
    $status="Resend - $error_id - ".$control->loggedAs();
 $tt=$control->generalSendAPI($url, $data_string, $claim_number, $status);
    if ($tt=="Success")
    {
        $control->callUpdateErrorOwls($error_id,"failed",2);
        echo $tt;
    }
    else{
        $control->callUpdateErrorOwls($error_id,"failed",2);
        echo $tt;
    }

}
else if($identity==38)
{
    $error_id = (int)$_POST["error_id"];
    $errornote = $_POST["errornote"];
    if(empty($errornote))
    {
        die("Please type your note");
    }
    if($control->callUpdateErrorOwls($error_id,"failed",2))
    {
        $control->callUpdateErrorOwls($error_id,"other_msg",$errornote);
        echo "Successfully updated";
    }
    else
    {
        echo "Failed";
    }
}
else if($identity==39)
{
    echo json_encode($control->viewViewReasons(),true);
}
else if($identity==40)
{

    $dat=$_POST["dates"];
    $status=$_POST["status"];
    $txt="<ul class=\"uk-nav-default uk-nav-divider\" uk-nav><li class=\"uk-active\"><a href=\"#\"><b>Claim Specialists</b></a></li>";
    $condition="1";
    $xcon="";
    if($control->isClaimsSpecialist())
    {
        $condition="username=:username";
    }
    if($status=="0")
    {
        $xcon=" AND controller_action=0 AND cs_action=0 AND completed=0";
    }
    elseif ($status=="1")
    {
        $xcon=" AND controller_action=1 AND completed=0";
    }
    elseif ($status=="2")
    {
        $xcon=" AND controller_action=1 AND cs_action=1 AND completed=1";
    }

    $arr=$control->viewQAFeedBackUsers($dat,$condition,$xcon);
    if(count($arr)>0) {
        foreach ($arr as $users) {
            $xuser = $users["username"];
            $txt .= "<li style='padding-top: 5px' class='uk-text-danger users' data='$xuser'><span uk-icon=\"user\"></span> <a href=\"#\" class='uk-text-danger'><b>$xuser</b></a></li>";
        }
    }
    else
    {
        $txt.="<p>No users</p>";
    }
    echo $txt."</ul>";

}
else if($identity==41)
{
    $dat=$_POST["dates"];
    $username=$_POST["username"];
    $txt="";
    $hiddentag="";
    $arr=$control->viewFeedbackQA($dat,$username);
    if(count($arr)>0) {
        foreach ($arr as $row) {
            $id = $row["id"];
            $claim_id= $row["claim_id"];
            $claim_number= $row["claim_number"];
            $qa_position = $row["qa_position"];
            $improvement_area = $row["improvement_area"];
            $action_plan = $row["action_plan"];
            $controller_action = (int)$row["controller_action"];
            $cs_action = (int)$row["cs_action"];
            $completed = (int)$row["completed"];
            if($controller_action==0)
            {$hiddentag="<span hidden='hidden'>initial controller activex</span>";}
            if($controller_action==1)
            {$hiddentag="<span hidden='hidden'>first controller activex</span>";}
            if($cs_action==1)
            {$hiddentag="<span hidden='hidden'>cs activex</span>";}
            if($completed==1)
            {$hiddentag="<span hidden='hidden'>closed activex</span>";}
            $comment = $row["comment"];
            $txtaction="action_plan".$id;
            $txtcomment="comment".$id;
            $col="";
            if($qa_position=="Failed")
            {
                $col="background-color:pink !important";
            }
            $txt.= " <tr style='$col'><td class='text-primary'>";
            $txt.="<form action='case_details.php' method='post' target='_blank'>";
            $txt.= "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\">";
            $txt.= "<button name=\"btn\" class='linkButton'>$claim_number</button>";
            $txt.="</form>";
            $txt.="</td><td>$improvement_area</td><td><div class=\"uk-margin\">
                        <textarea id='$txtaction' class=\"uk-textarea\" rows=\"5\">$action_plan</textarea></div>
                </td><td><div class=\"uk-margin\"><textarea id='$txtcomment' class=\"uk-textarea\" rows=\"5\">$comment</textarea>
                    </div></td>
                <td><button class=\"uk-button uk-button-primary addnotes\" data='$id'><span uk-icon=\"check\"></span> Save</button></td>
            </tr>";
        }
    }
    else{
        $txt.="<tr><td colspan='5'>No Data</td></tr>";
    }
    echo $txt.$hiddentag;
}
else if($identity==42)
{
    $id=$_POST["id"];
    $action_plan=$_POST["action_plan"];
    $comment=$_POST["comment"];
    echo $control->callupdateFeedbackQA($id,$action_plan,$comment);
}
else if($identity==43)
{
    $dat=$_POST["dates"];
    $username=$_POST["username"];
    $button=$_POST["button"];
    $mail = new PHPMailer(true);
    $email_data=$control->viewEmailCredentils();
    $from_email=$email_data["notification_email"];
    $from_password=$email_data["notification_password"];
    $email=$control->viewUserInformation($username)["email"];

    $subject="QA Feedback Session";
    $body="Hi<br>Your QA Feedback is Ready. You may complete your Action Plan.<br>MCA Team";

    if($button=="controller_action")
    {
        $control->sendEmail($mail,$from_email,"MCA",$from_password,$email,"MCA System User",$subject,$body);
        echo $control->callupdateFeedbackQAKey("controller_action",1,$username,$dat);
    }
    elseif($button=="cs_action"){
        $email="shirley@medclaimassist.co.za";
        $body="Hi<br>$username has completed the QA Feedback session, you may cose it now.<br>MCA Team";

        $control->sendEmail($mail,$from_email,"MCA",$from_password,$email,"MCA System User",$subject,$body);
        echo $control->callupdateFeedbackQAKey("cs_action",1,$username,$dat);
    }
    else
    {
        echo $control->callupdateFeedbackQAKey("completed",1,$username,$dat);
    }

}
else if($identity==44)
{
    $claim_id=(int)$_POST["claim_id"];
    $result=$control->callUpdateClaimKey($claim_id,"quality",2);
  $result=$control->callupdateQAKey($claim_id,"qa_signed",1);
    $result=$control->callupdateQAKey($claim_id,"cs_signed",1);
    echo $result;
}
else if($identity==45)
{
    $arr=$control->viewDeclineReasons();
  echo json_encode($arr,true);

}
else if($identity==46)
{
    $claim_id=(int)$_POST["claim_id"];
    $practice_number=$_POST["practice_number"];
    $key="decline_reason_id";
    $value=(int)$_POST["reason_id"];
    if($control->callUpdateDoctor($claim_id,$practice_number,$key,$value))
    {
        echo "Record Updated";
    }
    else
    {
        echo "Failed"; 
    }

}
else if($identity==47)
{
    $claim_id=(int)$_POST["claim_id"];
    $practice_number=$_POST["practice_number"];
    $charged_amount=(double)$_POST["charged_amount"];
    $scheme_amount=(double)$_POST["scheme_amount"];
    $gap=(double)$_POST["gap"];
    $pred=$control->viewSpecific($claim_id,$practice_number);
    $old_charged=$pred["doc_charged_amount"];
    $old_scheme=$pred["doc_scheme_amount"];
    $old_gap=$pred["doc_gap"];
    if($control->callUpdateDoctor($claim_id,$practice_number,"doc_gap",$gap))
    {
        $control->callUpdateDoctor($claim_id,$practice_number,"doc_scheme_amount",$scheme_amount);
        $control->callUpdateDoctor($claim_id,$practice_number,"doc_charged_amount",$charged_amount);
        $control->callInsertDoctorClaimLogs($claim_id,$practice_number,"doctor",$old_charged,$old_scheme,$old_gap,$control->loggedAs());
        echo "<div class='uk-alert-success' uk-alert><a href class='uk-alert-close' uk-close></a><p>Changes Successfully Saved.</p></div>";
    }
    else
    {
        echo "<div class='uk-alert-danger' uk-alert><a href class='uk-alert-close' uk-close></a><p>Failed to save changes.</p></div>";
    }

}

else if($identity==48)
{
    $claim_id=(int)$_POST["claim_id"];
    $patient_email=$_POST["patient_email"];
    $patient_contact=$_POST["patient_contact"];
    if($control->callUpdatePatient($claim_id,"patient_address",$patient_email))
    {
      $control->callUpdatePatient($claim_id,"patient_contact",$patient_contact);
        echo "<div class='uk-alert-success' uk-alert><a href class='uk-alert-close' uk-close></a><p>Changes Successfully Saved.</p></div>";
    }
    else
    {
        echo "<div class='uk-alert-danger' uk-alert><a href class='uk-alert-close' uk-close></a><p>Failed to save changes.</p></div>";
    }

}
?>