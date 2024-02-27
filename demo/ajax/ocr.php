<?php
session_start();
define("access",true);
include ("../classes/controls.php");
include ("../templates/claim_templates.php");
$control=new controls();
$username=$control->loggedAs();
if($_SERVER['REQUEST_METHOD']=="POST")
{
    $content = trim(file_get_contents("php://input"));
    $details_arr=json_decode($content,true);
    $identity=$details_arr["identity_number"];
    if($identity==1)
    {
        $claim_id=(int)$details_arr["claim_id"];
        $practice_number=$details_arr["practice_number"];
        $practice_number =str_pad( $practice_number, 7, '0', STR_PAD_LEFT);
        $practice_name="";
        $client_id=(int)$details_arr["client_id"];
        $doc_charged_amount=0;
        $doc_scheme_amount=0;
        $doc_gap=0;
        
        if($control->viewSpecific($claim_id,$practice_number)==false)
        {
        if($control->viewDoctor($practice_number)==false)
        {
            $control->callInsertDoctorDetails($practice_name,"","","",$practice_number,"","","","","","","","","","","","","","",$username);            
        }
       $control->callInsertClaimDoctor($practice_number, $claim_id, $username);
}
        if($claim_id>0) {
            $ret=0;
            foreach ($details_arr["data"] as $row) {
                $res_code = "";
                $treatment_date = $row["treatment_date"];
                $icd10_code = strlen($row["icd10_code"])>1?$row["icd10_code"]:$row["icd10_header"];
                $pmb=$control->isPMB($icd10_code)?"Y":"N";
                $procedure_code=$row["procedure_code"];
                $charged_amnt = (double)$row["charged_amnt"];
                $scheme_chrged = (double)$row["scheme_chrged"];
                $gap = (double)$row["gap"];
                $doc_charged_amount+=$charged_amnt;
                $doc_scheme_amount+=$scheme_chrged;
                $doc_gap+=$gap;
                $res_descr = "";
                if($control->callInsertClaimLine($claim_id,$practice_number,$charged_amnt,$scheme_chrged,$gap,$procedure_code,$treatment_date,$icd10_code,$pmb,$res_descr,0.0,$res_code,$treatment_date,$username,$res_descr,$res_code,$res_descr))
                {
                    $ret=1;
                }

            }
            if($ret==1)
            {
                $ret=1;
                $doc_arr=array("doc_charged_amount"=>$doc_charged_amount,"doc_scheme_amount"=>$doc_scheme_amount,"doc_gap"=>$doc_gap);
                foreach ($doc_arr as $key => $value) {
                $control->callUpdateDoctor($claim_id,$practice_number,$key,$value);       
    }  
    //$cc=json_encode($doc_arr);
                echo "Claim lines successfully added";
                $control->amountsProcess($claim_id);
            } else {
                echo "Failed!!!";
            }
        }
        else
        {
            echo "Invalid Claim Number";
        }
    }
    elseif($identity==2)
    {
        $row=$details_arr["data"];
        $claim_id=(int)$row["claim_id"];
        $member_id=(int)$row["member_id"];
        $policy_number=$row["policy_number"];
        $client_name=$row["client_name"];
        $member_name=$row["member_name"];
        $member_surname=$row["member_surname"];
        $id_number=$row["id_number"];
        $icd10=$row["icd10"];
        $member_telephone=$row["contact_number"];
        $member_email=$row["email"];
        $start_date=$row["start_date"];
        $end_date=$row["end_date"];
        $patient_name=$row["patient_name"];
        $claim_number=$row["claim_number"];
        $medical_scheme=$row["medical_scheme"];
        $scheme_option=$row["scheme_option"];
        $scheme_number=$row["member_number"];
        $c=0;       
        try {
$pmb=$control->isPMB($icd10)?1:0;
        $member_arr = array('policy_number' => $policy_number,'first_name'=>$member_name,'surname'=>$member_surname,'id_number'=>$id_number,'cell'=>$member_telephone,'email'=>$member_email,'medical_scheme'=>$medical_scheme,'scheme_option'=>$scheme_option,'scheme_number'=>$scheme_number);
        $claim_arr = array('icd10' => $icd10,'Service_Date'=>$start_date,'claim_number'=>$claim_number,'end_date'=>$end_date,'pmb'=>$pmb);
          foreach ($member_arr as $key => $value) {
    $c=$control->callUpdateMemberKey($member_id,$key,$value);       
    }  
  foreach ($claim_arr as $key => $value) {
    $c=$control->callUpdateClaimKey($claim_id,$key,$value);       
    }
    if(strlen($patient_name)>0)
            {
                $control->callDeletePatient($claim_id);
                $patres = $control->callInsertPatient($claim_id, $patient_name, $username);
            }
            if($c>0)
            {
                echo "Claim Successfully saved.";
                //$control->callUpdateClaimKey($claim_id,"Open",1); 
            }
            else
            {
              echo "failed";  
            }
            /*
            if ($control->validateMember($policy_number, $id_number, $client_id) == true) {
                $member_data = $control->validateMember($policy_number, $id_number, $client_id);
                $olduser = $member_data["entered_by"];
                $member_id = $member_data["member_id"];
                echo "<h4 align='center' style='color: red;font-weight: bolder'>$member_name $member_surname was already loaded by $olduser</h4>";
            } else {
                if ($control->callInsertMember($client_id, $policy_number, $member_name, $member_surname, $member_email, $member_telephone, $member_telephone, $id_number, $scheme_number, $medical_scheme, $scheme_option, "", $username)) {
                    $member_id = $control->viewLatestMember($username);
                } else {
                    die("<h4 align='center' style='color: red;font-weight: bolder'>There is an loading the member</h4>");
                }

            }
            if ($member_id > 0) {
                if ($control->validateClaim($claim_number, $client_id) > 0) {
                    die("<h4 align='center' style='color: red;font-weight: bolder'>This is a duplicate claim</h4>");
                } else {
                    $claim_id = 0;
                    if ($control->callInsertClaim($member_id, $claim_number, $start_date, $icd10, $pmb, 0.0, 0.0, 0.0, $username, 0, $username, $end_date, 0.0, "", "", "", "", "", "", "", "", "", "")) {
                        $claim_id = $control->viewLatestClaim($username);
                        $patres = $control->callInsertPatient($claim_id, $patient_name, $username);
                        echo "<div class=\"uk-alert-success\" uk-alert style='width: 50%; margin-right: auto;margin-left: auto;position: relative'><a class=\"uk-alert-close\" uk-close></a><h3 align='center'>New Claim Successfully added.</h3>";

                        echo "<h4 align='center'> <form action='case_details.php' method='post'>";
                        echo "<input type=\"hidden\" class='go_claim' name=\"claim_id\" id=\"claim_id\" value=\"$claim_id\">";
                        echo "<h4 align='center'><button type=\"submit\" class=\"uk-button-primary uk-button-small\" name=\"btn\">View Claim</button></h4>";
                        echo "</form></h4>";
                        echo "<h4 align='center'> <form action='edit_case.php' method='post'>";
                        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\">";
                        echo "<h4 align='center'><button type=\"submit\" class=\"uk-button-primary uk-button-small\" name=\"btn\">Edit Claim</button></h4>";
                        echo "</form></h4><hr>";

                        echo "</div>";
                    } else {
                        die("<h4 align='center' style='color: red;font-weight: bolder'>There is an error loading the claim</h4>");
                    }

                }
            } else {
                die("<h4 align='center' style='color: red;font-weight: bolder'>Failed to find this member</h4>");
            }
            */
        }
        catch (Exception $e)
        {
            die("There is an Error -> ".$e->getMessage());
        }
    }

    elseif($identity==3)
    {
        $json_res=$details_arr["data"]["res_codes"];
        $icd10=$details_arr["data"]["icd10"];
        $msg="<table>";
        $msg.="<thead>";
        $msg.="<tr style='background-color: #0b8278 !important; color: white'>";
        $msg.="<td>Health Service</td>";
        $msg.="<td>Benefit</td>";
        $msg.="<td>Limit</td>";
        $msg.="<td>Medical scheme explanation</td>";
        $msg.="<td>MCA grouper code</td>";
        $msg.="<td>MCA grouper code description</td>";
        $msg.="<td>Medical aid scheme</td>";
        $msg.="<td>Procedure code</td>";
        $msg.="<td>Practice type</td>";
        $msg.="</tr>";
        $msg.="</thead>";
        $msg.="<tbody>";

        foreach ($control->viewValidRulesCOding($icd10) as $rro)
        {
            $mca_grouper_code=$rro["ccs_grouper_code"];
            $mca_grouper_code_description=$rro["ccs_grouper_desc"];
            $msg .= "<tr>";
            $msg .= "<td> <span class='uk-badge uk-badge-danger'>$icd10</span><hr></td>";
            $msg .= "<td></td>";
            $msg .= "<td></td>";
            $msg .= "<td></td>";
            $msg .= "<td>$mca_grouper_code</td>";
            $msg .= "<td>$mca_grouper_code_description</td>";
            $msg .= "<td></td>";
            $msg .= "<td></td>";
            $msg .= "<td></td>";
            $msg .= "</tr>";
        }
        foreach ($json_res as $row)
        {
            $res_code=$row["res_code"];
            //$res_descr=$row["descr"];
            foreach ($control->viewValidRules($res_code,"","") as $row1)
            {
                $health_service=$row1["health_service"];
                $benefit=$row1["benefit"];
                $limit=$row1["limit"];
                $medical_scheme_explanation=$row1["medical_scheme_explanation"];
                $mca_grouper_code=$row1["mca_grouper_code"];
                $mca_grouper_code_description=$row1["mca_grouper_code_description"];
                $medical_aid_scheme=$row1["medical_aid_scheme"];
                $procedure_code=$row1["procedure_code"];
                $practice_type=$row1["practice_type"];
                $medical_scheme_reason_code=$row1["medical_scheme_reason_code"];
                $msg.="<tr>";
                $msg.="<td><span class='uk-badge uk-badge-danger'>$medical_scheme_reason_code</span><hr>$health_service</td>";
                $msg.="<td>$benefit</td>";
                $msg.="<td>$limit</td>";
                $msg.="<td>$medical_scheme_explanation</td>";
                $msg.="<td>$mca_grouper_code</td>";
                $msg.="<td>$mca_grouper_code_description</td>";
                $msg.="<td>$medical_aid_scheme</td>";
                $msg.="<td>$procedure_code</td>";
                $msg.="<td>$practice_type</td>";
                $msg.="</tr>";

            }

        }
        $msg.="</tbody>";
        $msg.="</table>";

        echo $msg;
    }
    elseif ($identity==4)
    {
        $tru=false;
        $member_number=$details_arr["member_number"];
        $data=$details_arr["data"];
        $msg="<table>";
        $msg.="<thead style='background-color: lightblue !important;'><tr><th>Duplicate Lines</th><th>Claim Number</th></tr></thead>";
        foreach ($data as $row)
        {
            $line_id =  $row["id"];
            $res_code =  $row["res_code"];
            $treatment_date = $row["treatment_date"];
            $procedure_code = $row["procedure_code"];
            $icd10_code = $row["icd10_code"];
            $charged_amnt = $row["charged_amnt"];
            $scheme_chrged = $row["scheme_chrged"];
            foreach ($control->viewDuplicateLines($member_number,$treatment_date,$res_code,$charged_amnt,$scheme_chrged) as $row1)
            {
                $tru=true;
                $claim_id=$row1["claim_id"];
                $claim_number=$row1["claim_number"];
                $msg.="<tr><td>$line_id</td><td><form action='case_details.php' method='post' target=\"_blank\"><input type='hidden' name='claim_id' value='$claim_id'/><button name='btn' style='color:#54bc9c'>$claim_number</button></form></td></tr>";
            }
        }
        $msg.="</table>";
        if($tru)
        {
            echo $msg;
        }
    }
}

?>