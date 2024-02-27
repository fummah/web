<?php
if(!defined('access')) {
    die('Access not permited');
}
function claim_header($data,$control,$username)
{
    $claim_id=$data["claim_id"];
    $control->claim_number=$data["claim_number"];
    $policy_number=$data["policy_number"];
    $control->owner_name=$data["username"];
    $control->client_name=$data["client_name"];

    if($username==$control->owner_name || $control->isTopLevel() || $username==$control->client_name || $control->otherRole()=="assessor")
    {
    }
    else{
        if($username=="Kaelo" && ($control->client_name=="Sanlam" || $control->client_name=="KaeloVAP"))
        {

        }
        elseif ($username=="Insuremed" || $username=="Gaprisk_administrators")
        {

        }
        elseif ($username=="Western" && $control->client_name=="KaeloVAP")
        {

        }
        else
        {
            session_unset();
            session_destroy();
            session_write_close();
            die("Invalid access");
        }
    }
    $date_entered=$data["date_entered"];
    $control->claim_date_entered=$date_entered;
    $created_by=$data["createdBy"];
    $control->member_name=$data["first_name"];
    $control->member_surname=$data["surname"];
    $full_name=$control->member_name." ".$control->member_surname;
    $id_number=$data["id_number"];
    $control->member_email=$data["email"];
    $telephone=$data["telephone"];
    $cell=$data["cell"];
    $patient=$data["patient_name"];
    $patient_idnumber=$data["patient_idnumber"];
    $control->medical_scheme=$data["medical_scheme"];
    $scheme_option=$data["scheme_option"];
    $control->member_number=$data["scheme_number"];
    $control->consent_description=$data["consent_descr"];
    //$pmb=(int)$data["pmb"];
    $emergency=(int)$data["emergency"];
    $icd10=$data["icd10"];
    $date_closed=$data["date_closed"];
    $open=(int)$data["Open"];
    $quality=(int)$data["quality"];
    $control->header_chargedamount=$data["charged_amnt"];
    $control->header_schemeamount=$data["scheme_paid"];
    $control->header_gapamount=$data["client_gap"];
    $control->header_memberportion=$data["gap"];
    $control->header_scheme_savings=$data["savings_scheme"];
    $control->header_discount_savings=$data["savings_discount"];
    $control->header_scheme_savings=$control->moneyformat($data["savings_scheme"]);
    $control->header_discount_savings=$control->moneyformat($data["savings_discount"]);
    $control->case_status=$open;
    $control->sla=(int)$data["sla"];
    $start_date=$data["Service_Date"];
    $end_date=$data["end_date"];
    $myemail=$control->member_email;

    $user=$control->viewUser($control->owner_name);
    $owner_name=$user["fullName"];
    $owner_email=$user["email"];
    $owner_contact=$user["phone"];
    $current_status="<span style='color: red' class='uk-text-small'>$date_entered / Open</span>";
    $date_reopened_status="";
    if(strlen($date_closed)>3 && $open!=0)
    {
        $date_reopened="";
        $redata=$control->viewReopenedClaim($claim_id);
        $date_reopened=$redata==true?$redata["reopened_date"]:"";
        $date_reopened_status=strlen($date_reopened)>3?"<span style='color: red'><br>[ Date Reopened : $date_reopened ]</span>":"";
    }
    $control->qa_disabled="yes";
    $control->clinical_disabled="yes";
    if($open==0)
    {
        $current_status="<span class='uk-text-small' style='color: grey'>$date_entered / $date_closed</span>";
        if($control->isTopLevel())
        {
            $control->qa_disabled="no";
        }
    }
    if($open==3)
    {
        $current_status="<span class='uk-text-small' style='color: orange'>$date_entered / On Hold</span>";
    }
    if($open==4)
    {
        $current_status="<span class='uk-text-small' style='color: darkolivegreen'>$date_entered / Clinical Review</span>";
    }
    if($open==5)
    {
        $current_status="<span class='uk-text-small' style='color: yellowgreen'>$date_entered / Pre-Assessement</span>";
    }
    if($control->isInternal() && $open==1)
    {
        $control->clinical_disabled="no";
    }
    $blinkx="";
    $individual1="";
    $brokertxt ="";
    $insurer="";
    if($control->client_name=="Individual")
    {
        $brokerArr=$control->viewSubscription($control->member_email);
        //print_r($brokerArr);
        $brokername=$brokerArr["fullname"];
        if(strlen($brokername)>1)
        {
            $individual1="";
            $insurer="<b style='color:#54bf99' title='insurer'> / ".$brokerArr["insurer"]."</b>";
            $brokertxt = "Broker Name : $brokername <hr>Unlimited queries and assistance whilst the subscription is active including:<ul><li>Investigating and sorting out short or non-payment of claims.</li><li>Assistance with pre-authorisations for procedures.</li><li>Assistance with registration on chronic disease programmes.</li><li>Information on the benefits in your chosen benefit option and the implications for the payment of claims.</li></ul>-Pro-active monitoring of invoices by doctors and the medical aid's responses to, in many instances, detect and start resolving problems before you are even aware of them.<br>-If you have gap cover, assistance with submitting claims to the gap insurer";
            $blinkx="blinkx";
        }
        else
        {

            $individual1="<h3 align=\"center\" style=\"color:purple\">Please don't forget to send email to Finance to invoice the claim together with member email and contact number</h3>";
        }
        //$myclaim_number=" <input class='w3-border-blue w3-round w3-small' type='text' style='border: none;padding: 5px' placeholder='linked claim number' id='claim_number' value='$claim_number1'><button class='w3-btn w3-white w3-border w3-border-blue w3-round w3-tiny' onclick='updateClaimNumber(\"$claim_id\")'>Update</button>";
    }
    echo "<input type='hidden' id='emmrg' value='$emergency'/>";
    $emergency_status=$emergency==1?"<span style='color: limegreen !important;'>Emergency</span>":"<span style='color: orange !important;'>Non-Emergency</span>";
    $icd10_status=$control->isPMB($icd10)=="Y"?"<span style='color: green'><span uk-icon=\"check\"></span> [$icd10] PMB, $emergency_status</span>":"<span style='color: red'><span uk-icon=\"close\"></span> [$icd10] Non-PMB, $emergency_status</span>";
    $qa_status="";

    if($quality==1 && $control->isInternal())
    {
        $qa_status="<form style='display: inline' method='post' action='quality_assurance.php' onsubmit=\"return createTarget(this.target)\" target=\"formtarget\"><input type='hidden' name='claim_id' value='$claim_id'><button name='quality_name' style='background-color: #54bc9c; border-radius: 5px' class='uk-button-small uk-button uk-button-danger'> Assessment</button></form>";
    }
    elseif ($quality==2 && $control->isInternal())
    {
        $qa_status="<form style='display: inline' method='post' action='quality_assurance.php' onsubmit=\"return createTarget(this.target)\" target=\"formtarget\"><input type='hidden' name='claim_id' value='$claim_id'><button name='quality_name' style='background-color: #54bc9c; border-radius: 5px' class='uk-button-small uk-button uk-button-danger'> View QA</button></form>";
    }
    echo "<div class=\"row\" style=\"margin-bottom: 3px !important;padding-top: 10px !important;box-shadow: 1px 1px 5px 2px white;border: 1px solid #e6e6e6; font-size: 14px !important; border-radius: 5px\">
            <div class=\"col-md-10\">
                <div class=\"row rowdetails\">
                    <div class=\"col-md-4\">
                        <b>$icd10_status</b>
                    </div> <div class=\"col-md-4\">
                        Claim Number : <b>$control->claim_number</b> $qa_status
                    </div>
                    <div class=\"col-md-4\">
                        Date Opened/Closed : <b style=\"color: red\">$current_status $date_reopened_status</b>
                    </div>
                </div>
                <div class=\"row rowdetails\">
                    <div class=\"col-md-4\">
                    Policy Number : <b>$policy_number</b>                 
                       
                    </div>
                    <div class=\"col-md-4 $blinkx\">
                         Client Name : <b uk-tooltip=\"title: $brokertxt; pos: top-right\" style='color: #00b3ee'>".$control->client_name.$insurer."</b>
                    </div>
                    <div class=\"col-md-4\">
                        Created By : <b>$created_by</b>
                    </div>
                </div>
                <div class=\"row rowdetails\">
                    <div class=\"col-md-4\">
                        Full Name : <b>$full_name</b>
                    </div>
                    <div class=\"col-md-4\">
                        ID Number : <b>$id_number</b>
                    </div>
                    <div class=\"col-md-4\">
                        Email : <b><a href=\"mailto:$myemail\">$control->member_email</a></b>
                    </div>
                </div>
                <div class=\"row rowdetails\">
                    <div class=\"col-md-4\">
                        Contact Number(s) : <b>$cell / $telephone</b>
                    </div>
                    <div class=\"col-md-4\">
                        Incident Date : From <b>$start_date</b> To <b>$end_date</b>
                    </div>
                    <div class=\"col-md-4\">
                        Patient(s) : <b>$patient [$patient_idnumber]</b>
                    </div>
                </div>
                <div class=\"row rowdetails\">
                    <div class=\"col-md-4\">
                        Scheme Name : <b>$control->medical_scheme</b>
                    </div>
                    <div class=\"col-md-4\">
                        Scheme Option : <b>$scheme_option</b>
                    </div>
                    <div class=\"col-md-4\">
                        Member Number : <b>$control->member_number</b>
                    </div>
                </div>
            </div>
           
            <div class=\"col-md-2\">
                <div>
                  <div class='uk-placeholder'> 
                        <p>$owner_name</p>
                        <p><a href=\"$owner_email\" style=\"word-wrap: break-word;\"> $owner_email</a></p>
                        <p>$owner_contact</p>
                    </div>
                </div>
            </div>
        </div>";
}

function doctor_line($doctor_arr,$claim_id,$control)
{
    $countdoctor = count($doctor_arr);
    $grandtototal_doctor_charged=0;
    $grandtototal_doctor_scheme=0;
    $grandtototal_doctor_gap=0;
    $grandtototal_doctor_memberportion=0;
    $count_lines=0;
    $docdis="none";

    $cdarry=[];
    $icu=["1204", "1205","1206","1207","1208","1209","1210"];
    $control->validatedDoctors="";
    if ($countdoctor > 0) {
        echo "<div class=\"row uk-animation-slide-bottom-medium\">
            <div class=\"col-md-12\">
              <p align='center' style='padding-top:5px'><span class=\"uk-badge\" style='background-color: #54bf99 !important;'>$countdoctor</span> <b style='color: #54bf99'>Doctor(s) </b></p>
       <table class=\"table w3-card w3-table-all uk-text-small w3-animate-zoom\" border=\"1\" style=\"width: 100%;font-size:14px\" align=\"center\" id=\"myD1\">
                    <thead><tr><th>No.</th><th>CPT4</th><th>Inv.Dat</th><th>Modifier</th><th>Res. Code</th><th>Treat.Date</th><th>PMB?</th><th>Tarif.C</th><th>ICD10</th><th>Chrgd Amt</th>
                        <th>Sch. Amt</th><th>Memb.Port</th><th>GAP</th><th>Calc</th><th></th></tr></thead>";

        foreach ($doctor_arr as $doctor_row) {
            $practice_number=str_pad($doctor_row["practice_number"], 7, '0', STR_PAD_LEFT);
            $control->validatedDoctors.=$practice_number.",";
            $doctor_schemesavings=(double)$doctor_row["savings_scheme"];
            $doctor_discountsavings=(double)$doctor_row["savings_discount"];
            $value_added_savings=(double)$doctor_row["value_added_savings"];
            $doctor_cpt4=$doctor_row["cpt_code"];
            $schemesavingsid="scd".$practice_number;
            $discsavingsid="dsd".$practice_number;
            $vasid="vas".$practice_number;
            $pay_doctor="pyd".$practice_number;
            $doctor=$control->viewDoctor($practice_number);
            $doctor_name=$doctor["name_initials"];
            $doctor_surname=$doctor["surname"];
            $doctor_id=$doctor["doc_id"];
            $doctor_telephone=$doctor["telephone"];
            $fixed_discount=(int)$doctor["fixed_discount"];
            $decline_reason_id=$doctor_row["decline_reason_id"];
            $description=$doctor_row["description"];
            $isreason=$doctor_row["isreason"];
            $discount_effective_date=$doctor["discount_effective_date"]." 00:00:00";
            $doctor_invoicenumber=$doctor_row["provider_invoicenumber"];
            $doctor_chargedamnt=$control->moneyformat($doctor_row["doc_charged_amount"]);
            $doctor_schemeamnt=$control->moneyformat($doctor_row["doc_scheme_amount"]);
            $doctor_gapamnt=$control->moneyformat($doctor_row["doc_gap"]);
            $doctor_fullname=$doctor_name." ".$doctor_surname;
            $doctor_savings_display="Scheme Savings : ".$doctor_schemesavings." Discount Savings : ".$doctor_discountsavings." VAS : ".$value_added_savings;
            $doctor_color="";
            if(empty($doctor_name) && empty($doctor_surname))
            {
                $doctor_color="text-danger";
            }
            $new_row="new".$practice_number;
            $zestbtn="";
            $zestx="";


            if($fixed_discount==1 && $control->claim_date_entered>$discount_effective_date && $control->client_name=="Zestlife")
            {
                $zestx="<span style='color: red;font-size: 10px'>Please do not request for discount on this doctor<br></span>";
                $zestbtn="hidden";
            }
            $doctor_showform="";
            $doctor_actions="";
            if($control->isInternal())
            {
                $doctor_showform="<form style='display: inline' action=\"edit_doctor.php\" method=\"post\" target=\"print_popup\" onsubmit=\"window.open('edit_doctor.php','print_popup','width=1000,height=800');\"><input type=\"hidden\" name=\"doc_id\" value=\"$doctor_id\"> <button class=\" $doctor_color\" style='background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;' name=\"doctor_edit_btn\" title=\"edit doctor\"><span></span><span uk-icon='pencil' title='edit this doctor'></span></button></form>";
                $doctor_actions="<span onclick=\" myClaimLine('$practice_number','$claim_id','doctor')\" style='cursor: pointer' class=\"uk-icon-button\" uk-icon=\"icon: plus-circle\" title='add new claim line for this doctor'></span> | <span onclick='highlitDoctor(\"$practice_number\",\"$doctor_fullname\",\"\")'><a href=\"#notes_section\" class=\"uk-icon-button\" uk-icon=\"icon: comment\" title='Select this doctor' onclick='highlitDoctor(\"$practice_number\",\"$doctor_fullname\",\"$zestbtn\")'></a></span> | $zestx<span> $doctor_showform <span id='$schemesavingsid' hidden>$doctor_schemesavings</span><span id='$discsavingsid' hidden>$doctor_discountsavings</span><span id='$vasid' reason_id='$decline_reason_id' description='$description' isreason='$isreason hidden>$value_added_savings</span><span id='$pay_doctor' hidden>$pay_doctor</span></span> | </span>";
                $docdis="block";
            }
            echo"<tbody id=\"$practice_number\" style='font-size: 14px !important;'>";
            echo"<tr id='$new_row'><td colspan=\"15\" style=\"font-weight: bolder; text-align: center; color: deepskyblue\">
                    <span class=\"uk-text-meta\" style=\"border-bottom: 1px solid whitesmoke; padding: 10px\">$doctor_actions<span style=\"color: black\" title=\"$doctor_savings_display\"><b>[$practice_number]</b> $doctor_fullname ($doctor_telephone) [$doctor_invoicenumber]</span></span>
                    </td>
                    </tr>";

            $claim_line_arr=$control->viewClaimline($claim_id,$practice_number);
            $countclaimline = count($claim_line_arr);
            $total_claimline_charged=0;
            $total_claimline_scheme=0;
            $total_claimline_gap=0;
            $total_claimline_memberportion=0;
            $total_claimline_calc=0;
            if ($countclaimline > 0) {

                foreach ($claim_line_arr as $line_row) {
                    $count_lines++;
                    $claim_line_id=$line_row["id"];
                    $icd10_code=$line_row["primaryICDCode"];
                    $pmb_status=$control->isPMB($icd10_code)=="Y"?"<span style='color:green' uk-icon='check'></span>":"<span style='color: red' uk-icon='close'></span>";
                    $benefit_description=strlen($line_row['benefit_description'])>10?"":$line_row['benefit_description'];
                    $reason_code=strlen($line_row['msg_code'])>1?$line_row['msg_code']:$line_row['reason_code'];
                    $clmnline_charged_amnt=(double)$line_row["clmnline_charged_amnt"];
                    $clmline_scheme_paid_amnt=(double)$line_row["clmline_scheme_paid_amnt"];
                    $claimline_gap=(double)$line_row["gap_aamount_line"];
                    $tariff_code=$line_row["tariff_code"];
                    $rejection_description=$line_row["lng_msg_dscr"];
                    $treatment_date=$line_row["treatmentDate"];
                    $modifier=$line_row["modifier"];
                    $total_claimline_charged+=$clmnline_charged_amnt;
                    $total_claimline_scheme+=$clmline_scheme_paid_amnt;
                    $total_claimline_gap+=$claimline_gap;
                    $claimline_memberportion=$clmnline_charged_amnt-$clmline_scheme_paid_amnt;
                    $claimline_calc=$claimline_memberportion-$claimline_gap;
                    $total_claimline_memberportion+=$claimline_memberportion;
                    $total_claimline_calc+=$claimline_calc;
                    $grandtototal_doctor_charged+=$clmnline_charged_amnt;
                    $grandtototal_doctor_scheme+=$clmline_scheme_paid_amnt;
                    $grandtototal_doctor_gap+=$claimline_gap;
                    $grandtototal_doctor_memberportion+=$claimline_memberportion;

                    $check_charged_amnt=$clmnline_charged_amnt>0?"":"uk-text-danger";
                    $check_scheme_amount=$clmline_scheme_paid_amnt>0?"":"uk-text-danger";
                    $check_charged_amnt_msg=$clmnline_charged_amnt>0?"":"uk-tooltip=\"title: Please check if this amount is correct\"";
                    $check_scheme_amount_msg=$clmline_scheme_paid_amnt>0?"":"uk-tooltip=\"title: Please check if this amount is correct\"";
                    $doctorrow_tempcode="main".$claim_line_id;
                    $clmnline_charged_amnt=$control->moneyformat($clmnline_charged_amnt);
                    $clmline_scheme_paid_amnt=$control->moneyformat($clmline_scheme_paid_amnt);
                    $claimline_gap=$control->moneyformat($claimline_gap);
                    $claimline_memberportion=$control->moneyformat($claimline_memberportion);
                    $claimline_calc=$control->moneyformat($claimline_calc);
                    $tariff_description=$control->viewTariffDesc($tariff_code);
                    $icd10_description=$control->viewIcd10Desc($icd10_code);
                    $mycx1="";
                    $mycoding=$control->viewCoding($tariff_code,$icd10_code,$doctor_cpt4);
                    if(count($mycoding)>0 && !in_array($tariff_code,$icu))
                    {

                        $mycx1="<a href='' onclick=\"window.open('code_lookup.php?tariff=$tariff_code&icd10=$icd10_code','popup','width=1100,height=700'); return false;\"><span uk-tooltip=\"title: There is a possible diagnosis to procedure mismatch.\" style='color:red !important;'>**</span></a>";
                        $mxr=array("number"=>$count_lines,"descr"=>implode(";",$mycoding));
                        array_push($cdarry,$mxr);

                    }
                    echo "<tr class=\"doc_fclass $practice_number\" id=\"$doctorrow_tempcode\">
                        <td><span class=\"uk-badge\" style='background-color: #54bf99'>$count_lines</span> </td><td>$doctor_cpt4 </td><td title=\"$benefit_description\">$benefit_description</td><td>$modifier</td><td uk-tooltip=\"title:$rejection_description\" aria-expanded=\"false\">$reason_code</td>  
                        
                        <td>$treatment_date</td><td>$pmb_status</td><td uk-tooltip=\"title: $tariff_description \" title=\"\" aria-expanded=\"false\">$tariff_code$mycx1</td><td uk-tooltip=\"title: $icd10_description\" title=\"\" aria-expanded=\"false\"><div>[$icd10_code]</div></td><td class=\"$check_charged_amnt\" $check_charged_amnt_msg>$clmnline_charged_amnt</td><td class=\"$check_scheme_amount\" $check_scheme_amount_msg>$clmline_scheme_paid_amnt</td><td class=\"text-success\">$claimline_memberportion</td><td class=\"text-success\">$claimline_gap</td><td class=\"uk-text-warning\">$claimline_calc</td><td>";
                    if($control->isInternal()) {
                        echo "<span onclick=\"myClaimLine('$claim_line_id','$claim_id','claim_line')\" style=\"cursor: pointer;\"><span uk-icon=\"pencil\" class=\"uk-icon-button\"></span></span>  <span uk-icon=\"trash\" style='cursor: pointer' onclick=\"deleteLIne('$claim_line_id')\" class=\"uk-icon-button\"></span>";
                    }
                    echo"</td></tr>";
                }
            }
            echo "</tbody>";
            $total_claimline_charged=$control->moneyformat($total_claimline_charged);
            $total_claimline_scheme=$control->moneyformat($total_claimline_scheme);
            $total_claimline_gap=$control->moneyformat($total_claimline_gap);
            $total_claimline_memberportion=$control->moneyformat($total_claimline_memberportion);
            $total_claimline_calc=$control->moneyformat($total_claimline_calc);
            echo "<tr><th></th><th colspan=\"8\"></th><th>$total_claimline_charged</th><th>$total_claimline_scheme</th><th>$total_claimline_memberportion</th><th>$total_claimline_gap</th><th>$total_claimline_calc</th></tr>";
            echo "<tr class='text-info'><th></th><th colspan=\"7\"></th><th>[$doctor_chargedamnt]</th><th>[$doctor_schemeamnt]</th><th></th><th>[$doctor_gapamnt]</th>
            <th><a style='display:$docdis' claim_id='$claim_id' practice_number='$practice_number' gap='$doctor_gapamnt' charged='$doctor_chargedamnt' scheme='$doctor_schemeamnt' class='uk-margin-small-right text-info gapr' uk-icon='pencil'></a> 
            </th><th><a  practice_number='$practice_number' gap='$doctor_gapamnt' class='uk-margin-small-right text-info uk-icon-button' id='calculator' uk-icon='settings'></a></th></tr>";

        }
        $header_calc=(double)$control->header_memberportion-(double)$control->header_gapamount;
        $header_chargedamount=$control->moneyformat($control->header_chargedamount);
        $header_schemeamount=$control->moneyformat($control->header_schemeamount);
        $header_gapamount=$control->moneyformat($control->header_gapamount);
        $header_memberportion=$control->moneyformat($control->header_memberportion);


        $header_calc=$control->moneyformat($header_calc);

        if($grandtototal_doctor_charged!=$header_chargedamount)
        {
            $header_chargedamount="<span style='color: #aa7700'>$header_chargedamount</span>";
        }
        if($grandtototal_doctor_scheme!=$header_schemeamount)
        {
            $header_schemeamount="<span style='color: #aa7700'>$header_schemeamount</span>";
        }
        if($grandtototal_doctor_gap!=$header_gapamount)
        {
            $header_gapamount="<span style='color: #aa7700'>$header_gapamount</span>";
        }
        if($grandtototal_doctor_memberportion!=$header_memberportion)
        {
            $header_memberportion="<span style='color: #aa7700'>$header_memberportion</span>";
        }
        echo"</tfoot><tr style='background-color:#d1ece4'><th>Totals :</th><th colspan=\"8\">Scheme Savings : <span class='uk-badge'>$control->header_scheme_savings</span> Discount Savings : <span class='uk-badge'>$control->header_discount_savings</span></th><th>$header_chargedamount</th><th>$header_schemeamount</th><th>$header_memberportion</th><th>$header_gapamount</th><th>$header_calc</th></tr>
                    </tfoot></table>
            </div>
        </div>";
    }
}

function notes_temp($notes_arr,$control,$type="",$loop=0)
{
    $ccount=0;
    if(count($notes_arr)>0) {
        date_default_timezone_set('Africa/Johannesburg');
        foreach ($notes_arr as $row) {
            if($loop==1 && $ccount==2)
            {
                break;
            }
            $ccount++;
            $note_date_entered = htmlspecialchars($row["date_entered"]);
            $note = htmlspecialchars_decode($row["intervention_desc"]);
            $note_id = htmlspecialchars($row["intervention_id"]);
            $deletedRow = $note_id . "q";
            $ccId = $deletedRow . "x";
            $from_date = date('Y-m-d', strtotime($note_date_entered));
            $today = date('Y-m-d');
            $datetime1 = strtotime($from_date);
            $datetime2 = strtotime($today);
            $secs = $datetime2 - $datetime1;// == <seconds between the two times>
            $days = $secs / 86400;
            $days = round($days);
            $note_owner = $row["owner"];
            $doctor_details = "";
            $note_background = "floralwhite";
            $shownotebuttons="";
            if($type=="Notes")
            {
                $doctor_details = "Doctor : [".$row["practice_number"]."] [".$row["doc_name"]."]";
                $note_background = "honeydew";


                if(($control->isTopLevel() || $days<1) && $control->isInternal())
                {
                    $shownotebuttons=" <li><a href=\"#edit_note\" uk-icon=\"icon: pencil\" title='edit' onclick='editNoteModal(\"$note_id\")' uk-toggle></a></li>
                                        <li><span style='cursor: pointer' uk-icon=\"icon: trash\" title='delete' onclick='delete_note(\"$note_id\")'></span></li>";

                }

            }
            echo "<div class=\"uk-grid-medium uk-flex-middle uk-grid uk-grid-stack\" uk-grid=\"\" style=\"background-color:$note_background; padding-bottom: 12px!important;padding-top: 12px!important; border-radius: 10px; margin-left: 4px; border: 1px solid darkseagreen;\">
                            <div class=\"uk-width-expand uk-first-column\">

                                <ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">
                                    <li><a href=\"#\" id=\"mytime\" style='color: cadetblue !important;'>$note_date_entered</a></li>
                                    <li><a href=\"#\" style='color: cadetblue !important;'>$note_owner</a></li>
                                    <li><a href=\"#\" style='color: #54bf99 !important;'>$doctor_details</a></li>
                                    <li><i><a href=\"#\">$days Days Ago</a></i></li>
                                    
                                   $shownotebuttons
                                </ul>
                                <div id='$note_id' class=\"uk-comment-body\">
                                    <p id='$note_id'>".nl2br($note)."</p>
                                </div>
                                <div style='color: lightblue'></div>
                            </div>

                        </div>";
        }
    }
    else
    {
        echo "  <div class=\"uk-comment-body\" style=\"background-color: whitesmoke; padding: 10px; border-radius: 10px; color: red\">
                                    <span class='nothing'>No $type</span>
                                </div>";
    }
}
function claim_buttons_temp($control)
{
    viewDocuments($control->viewDocuments($control->claim_id));
    if ($control->isInternal()) {
        if($control->isTopLevel() || $control->case_status!=0)
        {
            $mesg="mymessage.php?claim_id=".$control->claim_id;
            echo "<form style='display: inline; padding: 5px' action='edit_case.php' id='vv' method='post'><input type=\"hidden\" name=\"claim_id\" value=\"$control->claim_id\"><button class=\"uk-button uk-button-primary uk-button-small\" style=\"background-color: #54bf99;\"><span uk-icon=\"pencil\"></span> Edit Claim</button></form>";
            if($control->case_status==1) {

                echo "<span onclick='sendConsent(\"$control->claim_id\",\"$control->consent_description\")' title='$control->consent_description'><button class=\"uk-button uk-button-primary uk-button-small\" style=\"background-color: #54bf99;\"><span id='consentID'>Send Consent</span></button></span>
                          ";

                echo "<div class=\"uk-inline\" style='padding-right: 8px;'>";
                echo "<span> <button class='uk-button uk-button-default uk-button-small' style=\"border: 1px solid #54bf99;\">View Consent</button></span>";
                echo "<div uk-dropdown>";
                echo "<a href='consent_forms.php' onclick=\"window.open('consent_forms.php','popup','width=1100,height=700'); return false;\" title='Click to view Consent Forms'><button class=\"uk-button uk-button-default uk-button-small\" style=\"border: 1px solid #54bf99;\"> Consent Forms</button></a>";
                echo "<div><p></p></div><a href='$mesg' class='uk-button uk-button-default uk-button-small' style=\"border: 1px solid #54bf99;\" onclick=\"window.open('$mesg','popup','width=800,height=600'); return false;\" title='Click to view'>View Message</a>";
                echo "</div>";
                echo "</div>";
            }
        }
    }
}
function claim_notetext_temp($control)
{
    $case_status=$control->case_status;
    $sla=$control->sla;
    ?>
    <div class="input-field col s12">
        <textarea class="materialize-textarea" data-length="10000" id="intervention_desc" name="intervention_desc" placeholder='Type your note here' onkeyup="valid()"></textarea>

    </div>
    <div style="display: none; padding-left: 10px;" id="doc_detail1"><br>
        <div class="row">
            <div class="col-md-6"><span style="color:#53C099; font-weight: bolder" id="doc_name"> </span></div>
            <div class="col-md-6" id="doc_practiceno" style="color:#53C099;"></div>
        </div>
        <div class="row">
            <div class="col-md-4">Scheme Savings<input type="number" title="Scheme savings" id="doc_schemesavings" placeholder="Scheme" class="form-control"></div>
            <div class="col-md-4">Discount Savings<input type="number" title="Discount savings" id="doc_discountsavings" placeholder="Discount" class="form-control"></div>
            <div class="col-md-4">VAS<input type="number" title="Value Added savings" id="doc_vas" placeholder="VAS" class="form-control"></div>
        </div>
      <div class="row">
            
            <div class="col-md-6">Pay Provider? : <label>
                    <input type="radio" id="pay_doctor1" name="pay_doctor" value="yes">
                    <span>Yes</span>
                </label>
                <label>
                    <input type="radio" id="pay_doctor2" name="pay_doctor" value="no">
                    <span>No</span>
                </label>
            </div>
            <div class="col-md-6" style="border-left:1px solid #20c997">Scheme Declined? : <label>
                    <input type="radio" id="scheme_declined1" name="scheme_declined" value="yes">
                    <span>Yes</span>
                </label>
                <label>
                    <input type="radio" id="scheme_declined2" name="scheme_declined" value="no">
                    <span>No</span>
                </label>
            </div>
        </div>

    </div>
    <div class="input-field col s12 te" style="display:none"><select id="reason" class="reason"></select><label>Select Decline Reason</label></div>
   
    <?php
    echo "<div class=\"input-field col s12\">
                                    <select id=\"consent_dest\">
                                        <option value=\"\">Select Destination</option>
                                        <option value=\"Provider\">Provider</option>
                                        <option value=\"Medical aid scheme\">Medical aid scheme</option>
                                        <option value=\"Medical aid scheme and Provider\">Medical aid scheme and Provider</option>
                                        <option value=\"Medical aid scheme\">Member</option>
                                    </select>
                                    <label>Select</label>
                                </div>";
    echo " <div class=\"\">
                            
                                Close Case?
                                <p id='not_closed'>
                                    <label>
                                        <input type=\"radio\" id=\"open\" name=\"Open\" value=\"1\" checked />
                                        <span>No</span>
                                    </label>
                                </p>";
    if ($case_status != 0) {
        echo"<p><label><input type=\"radio\" id=\"close\" name=\"Open\" value=\"0\"/><span>Yes</span></label></p>";
    }
    else{
        echo "<b style='color: red'>Case Closed</b><br>";
    }
    echo"<button class=\"uk-button uk-button-primary uk-button-small\" style=\"background-color: #54bf99;\" id=\"addNotes\" onclick=\"addNotes('$control->claim_id;','$sla')\" disabled=\"true\"><span uk-icon=\"check\"></span> Add Note</button>
                            <div style=\"display: none; padding: 12px\" id=\"meshow\">Please wait...</div>
                            </div>";
}
function claimtabs($control,$clinical_number=0)
{
    echo "<ul class=\"tabs\" style=\"color: #0b8278 !important; border-bottom: 1px solid #0b8278 !important\">
                <li class=\"tab\" onclick=\"openTab('notes_tab')\"><a class=\"notes_tab inaction\" style=\"color: #0b8278;\">Notes</a></li>
                <li class=\"tab\" onclick=\"openTab('feedback_tab')\"><a class='feedback_tab' style=\"color: #0b8278;\">Feedback</a></li>";
    if($control->isInternal())
    {
        echo "<li class=\"tab\" onclick=\"openTab('validations_tab')\"><a class='validations_tab' style=\"color: #0b8278;\">Validations</a></li>";
        if($control->case_status==4 || $clinical_number>0) {
            echo "<li class=\"tab\" onclick=\"openTab('clinical_tab')\"><a class='clinical_tab' style=\"color: #0b8278;\">Clinical Review</a></li>";
        }
        if($control->validate8days && $control->isInternal()) {
            echo "<li class=\"tab\" onclick=\"openTab('days8_tab')\"><a class='days8_tab' style=\"color: #0b8278;\">8 Days</a></li>";
        }
    }
    echo " </ul>";
}
function feedbackOptions($control)
{
    echo " <select id=\"fxd\"><option value=\"\">Select Reason</option>
                            <option value=\"-\">Not listed below</option>                            
                            <option value=\"No feedback note for more than 2 days\">No feedback note for more than 2 days</option>
                            <option value=\"Claim Number incorrect\">Claim Number incorrect</option>
                            <option value=\"Policy Number Incorrect\">Policy Number Incorrect</option>
                            <option value=\"Wrong Doctor mentioned in note\">Wrong Doctor mentioned in note</option>
                            <option value=\"Gap value in case doesn't match ours\">Gap value in case doesn't match ours</option>
                            <option value=\"Doctor Disputing discount\">Doctor Disputing discount</option>
                        </select><label for='fxd'> Select Reason</label>";
    echo"<p><textarea rows=\"8\" placeholder=\"add feedback here...\" style=\"border-color: #0b8278; border-radius: 5px\" cols=\"80\" id=\"feedback_desc\" name=\"feedback_desc\" onkeyup=\"valid1()\"></textarea></p>";
    echo"<button class=\"uk-button uk-button-primary uk-button-small\" onclick=\"addFeedback('$control->claim_id')\" id=\"addFeedback\"><span><span uk-icon=\"mail\"></span> Send Feedback</span> </button>
                        <span style=\"color: green;font-weight: bolder; display: none\" id=\"feedbackShow\">Sending, please wait...</span>
                        <div id=\"alert1\" class=\"alert\" style=\"display: none; width: 60%;\"></div>";
}
function viewDocuments($doc_arr)
{
    $count_files=count($doc_arr);
    if ($count_files > 0) {

        echo "<div class=\"uk-inline\" style='padding-right: 8px;'>";
        echo "<span style='color:#54bc9c; cursor: pointer' height='25' width='25' uk-icon=\"cloud-download\" class=\"uk-icon\"></span>";
        echo "<div uk-dropdown>";
        foreach ($doc_arr as $row1) {
            $id = htmlspecialchars($row1["doc_id"]);
            $random_number = htmlspecialchars($row1["randomNum"]);
            $file_name = htmlspecialchars($row1["doc_description"]);
            $link_description = "../../mca/documents/" . $random_number . $file_name;
            $addition_document=(int)$row1["additional_doc"];
            $file_color="";
            if($addition_document==1)
            {
                $file_color="style='color: red'";
            }

            echo "<form action='view_file.php' method='post' target=\"print_popup\" onsubmit=\"window.open('#','print_popup','width=1000,height=800');\"><input type=\"hidden\" name=\"my_doc\" value=\"$link_description\" />
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" $file_color name=\"doc\" value=\"$file_name\">

</form>";
        }
        echo "</div>";
        echo "</div>";
    } else {
        echo "<span class='uk-text-meta purple-text'> No Files </span>";
    }
}
function displayDocuments($doc_arr){
    $count_files=count($doc_arr);
    if ($count_files > 0) {
        foreach ($doc_arr as $row1) {
            $random_number = htmlspecialchars($row1["randomNum"]);
            $file_name = htmlspecialchars($row1["doc_description"]);
            $link_description = "../../mca/documents/" . $random_number . $file_name;
            $size = round($row1["doc_size"]/1024);
            echo"<span uk-icon=\"file\"></span> <form style='display: inline' action='view_file.php' method='post' target='_blank'/><input type=\"hidden\" name=\"my_doc\" value=\"$link_description\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$file_name\"></form> $size<b style='color:#54bc9c'>KB</b><br>";
        }

    }
}

function uploadFiles($file,$temp_file,$file_name,$file_type,$file_size,$path,$random_number,$control,$uploaded_by)
{
    if (isset($file) && is_file($temp_file))  {

        $allowedExts= ['jpeg','jpg','png',"pdf","doc","docx","xlsx","xls","txt","PDF","PNG","msg","MSG","eml","EML","zip","ZIP","TIF","tif","tiff","TIFF"];
        $fileExtensions = ['jpeg','jpg','png',"pdf",'TIF','tif',"vnd.openxmlformats-officedocument.spreadsheetml.sheet","vnd.openxmlformats-officedocument.wordprocessingml.document","vnd.ms-excel","msword","vnd.oasis.opendocument.text","application/pdf","PDF","PNG","msg","MSG","octet-stream","eml","EML","application/octet-stream","message/rfc822","rfc822","x-zip-compressed"];
        $temp = explode(".", $_FILES["file"]["name"]);
        $presentExtention = end($temp);
        $type = basename($file_type);
        $nname = basename($file_name);
        $fileExtension = basename($file_type);
        $nux=substr_count($nname, '.');
        if(in_array($presentExtention,$allowedExts) && strlen($nname)<100 && $nux==1 && $file_size >0) {
            if (in_array($fileExtension, $fileExtensions) && ($file_size < 20000000)) {
                $target = $path . $random_number . basename($file_name);
                if (move_uploaded_file($temp_file, $target)) {
                    $size = basename($file_size);
                    $nname=filter_var($nname, FILTER_SANITIZE_STRING);
                    $control->callInsertDocuments($control->claim_id,$nname,$size,$type,$random_number,$uploaded_by);
                    echo "<div class=\"uk-alert-success\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p>Your file has been uploaded.</p></div>";
                } else {
                    echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p>Sorry, Failed to upload.</p></div>";
                }
            } else {
                echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p>Sorry, incorrect file, failed to upload( $fileExtension )</p></div>";
            }
        }
        else {
            echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p>Sorry, incorrect file, failed to upload_$nname ($fileExtension)</p></div>";
        }

    }
    else
    {
        echo "<div class=\"uk-alert-danger\" uk-alert><a class=\"uk-alert-close\" uk-close></a><p>No file uploaded.</p></div>";
    }
}
function preAssessment($control)
{
    echo "<p align='center' id='hideme'><label><input class=\"uk-checkbox\" type=\"checkbox\" onclick='promotrClaim(\"$control->claim_id\")'> <span>Promote this claim?</span></label></p><p align='center' id='prinfo'></p>";
    echo "<hr><p align='center'><a href='preassessed.php'> <button name='btn' class=\"uk-button uk-button-secondary\"><span uk-icon=\"list\"></span> Other Claims</button></a></p>";

}
function clinicalReview($control,$username)
{
    ?>
    <h4 class="uk-text-large" align="center">Clinical Review</h4>
    <div class="clinicalnotesDiv">
        <?php
        foreach ($control->viewClinicalNotes($control->claim_id) as $row1) {

            $desc=htmlspecialchars_decode($row1[0]);
            $dateE=htmlspecialchars($row1[1]);
            $owner=htmlspecialchars($row1[2]);
            $color="";
            if($owner==$username)
            {
                $owner="You";
                $color="#3e8f3e";
            }

            echo"<h4 class=\"feedbackHeader\"> <b style='color: $color'>$owner</b> posted on <i style=\"color: #0d92e1\">$dateE</i>";
            echo"</h4>";
            echo "<p class='feedbackParagraph'>$desc</p>";

        }
        ?>
    </div>

    <p align="center"><textarea rows="8" class="uk-textarea" placeholder="clinical notes here..." style="width: 80%; border-color: #54bc9c;" cols="80" id="cnotes" name="cnotes"></textarea></p>

    <p align="center">
        <label style="margin-bottom: 20px"><input name="refback" id="refback" class="uk-checkbox" type="checkbox"> <span>Refer back?</span></label><br>
        <button class="uk-button uk-button-primary" id="addclinicalNotes" onclick="addclinicalNotes('<?php echo $control->claim_id;?>')"><span uk-icon="comment"></span> Post </button>
        <span style="color: green;font-weight: bolder; display: none" id="clinicalShow">Sending, please wait...</span>
        <span align="center" id="clinicalAlert" class="alert" style="display: none; width: 60%; font-weight: bolder;"></span></p>
    <?php
}
function savingsModal($control)
{
    ?>
    <div id="close_case_modal" uk-modal="stack: true">
        <div class="uk-modal-dialog uk-modal-body">
            <h4 align="center" class="modal-title" style="color:red">You have selected to close the case</h4>
            <?php
            $individual1=$control->client_name=="Individual"?"<h3 align=\"center\" style=\"color:purple\">Please don't forget to send email to Finance to invoice the claim together with member email and contact number</h3>":"";
            echo $individual1;
            ?>
            If this is correct please enter the savings in the appropriate area below and click confirm:
            <hr>
            <input type="hidden" id="claim_id" name="claim_id" value=$claim_id>
            <input type="hidden" id="Open" name="Open" value=0>
            <input type="hidden" id="user" name="user" value=$username>
            <input type="hidden" id="allmydoc" name="allmydoc" value="<?php echo $control->validatedDoctors;?>">
            <span><b>Scheme Savings</b></span>
            <input type="number" id="savings_scheme" class="form-control zeroc" name="savings_scheme" value="" min="1">
            <br>

            <span><b>Discount Savings</b></span>
            <input type="number" id="savings_discount" class="form-control zeroc" name="savings_discount" value="" min="1">
            <br>
            <span><b>Value Added Savings</b></span>
            <input type="number" id="vas_savings" class="form-control zeroc" name="vas_savings" value="" min="1">
            <br>
            <span style="color: #0a2b1d; display: none" id="spanzero"> <span><b>Select Catergory :</b></span>
                    <select name="zerosavings" id="zerosavings">
                        <option value="">Select Category</option>
                        <option value="Voluntary use of a non dsp">Voluntary use of a non dsp</option>
                        <option value="High escalation where claim is late, member wants claim paid">High escalation where claim is late, member wants claim paid</option>
                        <option value="Member already paid claim">Member already paid claim</option>
                        <option value="Claim older than 30 days">Claim older than 30 days</option>
                        <option value="Planned procedure , auth prior to the procedure">Planned procedure , auth prior to the procedure</option>
                    </select> </span>
            <p align="center" style="display: none; color:blue; font-weight: bolder;" id="modAlert"></p>
            <p style="display: none; color: green" id="modShow">Please wait...</p>
            <?php
            $admed="block";
            if($control->client_name=="Admed")
            {
                $admed="none";
                echo "<p id='xxadmed1' align=\"center\"><label onclick='admedcheck()' class='uk-text uk-text-danger'><input id='xxadmed' class=\"uk-checkbox\" type=\"checkbox\"> <span>Did you close the case on MAGPI?</span></label></p>";
            }

            ?>
            <p class="uk-text-right" id='xxadmed2' style="display: <?php echo $admed?>" align="center">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="button"  onclick="addSavings('<?php echo  $control->claim_id;?>')">Close the Case and Record Savings</button>
            </p>
            <span id="resultText"></span>
        </div>
    </div>
    <?php
}
function consentEmail($member_name,$gap,$scheme,$userName,$control)
{
    $name=strtolower($member_name);
    $kaeloclients=["Kaelo","Western","Sanlam"];
    $x=ucwords($name);
    $gap=$gap=="Individual"?"MedClaim Assist":$gap;
    $template_name=in_array($gap, $kaeloclients)?"kaelo_consent_form":"basic_consent_form";  
    $mess=$control->viewTemplate($template_name);
    $variables = array("gap"=>$gap,"scheme"=>$scheme,"username"=>$userName);
foreach($variables as $key => $value){
    $mess = str_replace('{'.strtoupper($key).'}', $value, $mess);
}
    return $mess;
}
function validations($claim_id,$control,$vaclinix,$arx05,$inhosparr,$vaclini,$emegncyarr,$codes3,$cdarry=array())
{
    $rule_arr=[];
    $data=$control->viewSingleClaim($claim_id);
    $is_atheniest=(int)$data["is_atheniest"]==1?"checked":"";
    $provider_zf=(int)$data["provider_zf"]==1?"checked":"";
    $icd10_emergency=(int)$data["icd10_emergency"]==1?"checked":"";
    $tarrif_0614=(int)$data["tarrif_0614"]==1?"checked":"";
    $saoa=(int)$data["saoa"]==1?"checked":"";
    $emergency2=(int)$data["emergency"];
    $coding_checked=(int)$data["coding_checked"]==1?"checked":"";
    $allvalidationsArray=$control->viewValidations();
 
    echo "<table class=\"uk-table uk-table-divider\"><thead><tr><th style=\"width: 20%\">Number</th><th style=\"width: 20%\">Rules</th><th>Description</th><th>Confirm</th></tr></thead> <tbody>";

    $tv=count($vaclinix);
    $tv1=count($arx05);
    $tv2=count($inhosparr);
    if($vaclini[0]=="Y" && $tv>0)
    {
        $rule_name=$allvalidationsArray[0]["rule_name"];
        $rule_description=$allvalidationsArray[0]["description"];
        array_push($rule_arr,"is_atheniest");
        ?>
        <tr>
            <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td><td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> <?php echo $rule_name;?><br>
                    <?php
                    for ($i=0;$i<$tv;$i++)
                    {
                        echo"<ul><li>$vaclinix[$i]</li></ul>";
                    }
                    $is_atheniestxx="is_atheniest";$membspan1xx="membspan1";
                    ?>
                </div></td>
            <td>  <div class="uk-card uk-card-default uk-card-body">
                  <?php echo $rule_description;?>
                </div></td>
            <td><label><input id="is_atheniest" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $is_atheniestxx;?>','<?php echo $membspan1xx;?>')"  <?php echo $is_atheniest;?>> <span>Confirm?</span></label> <span id='membspan1' style="display: none"> <textarea class="uk-textarea" id='memtxt1'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="is_atheniest('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan1'></ul></td>
        </tr>
        <?php
    }
    if($tv1>0)
    {
        array_push($rule_arr,"provider_zf");
          $rule_name=$allvalidationsArray[1]["rule_name"];
        $rule_description=$allvalidationsArray[1]["description"];
        ?>
        <tr><td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
            <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> <?php echo $rule_name;?><br>
                    <?php
                    for ($i=0;$i<$tv1;$i++)
                    {
                        echo"<ul><li>$arx05[$i]</li></ul>";
                    }
                    $zpf="provider_zf";$zpf1="membspan2";
                    ?>
                </div></td><td> <div class="uk-card uk-card-default uk-card-body">
                    <?php echo $rule_description;?>
                </div></td><td><label><input id="provider_zf" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $zpf;?>','<?php echo $zpf1;?>')" <?php echo $provider_zf;?>> <span>Confirm?</span></label><span id='membspan2' style="display: none"> <textarea class="uk-textarea" id='memtxt2'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="provider_zf('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan2'><ul></ul></td></tr>
        <?php
    }
    if($tv2>100)
    {
          $rule_name=$allvalidationsArray[2]["rule_name"];
        $rule_description=$allvalidationsArray[2]["description"];
        ?>
        <tr>
            <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
            <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> <?php echo $rule_name;?>
                    <br>
                    <?php
                    for ($i=0;$i<$tv2;$i++)
                    {
                        echo"<ul><li>$inhosparr[$i]</li></ul>";
                    }
                    ?>
                </div></td><td><div class="uk-card uk-card-default uk-card-body">
                   <?php echo $rule_description;?>
                </div></td>
            <td><label><input class="uk-checkbox" type="checkbox"> Confirm?</label></td>
        </tr>
        <?php
    }
    $dbemergency=explode(",",$allvalidationsArray[4]["vals"]);
      //$dbemergency=["0011","0145","0146","0001","415","0147"];
print_r($dbemergency);
    $emegncyarr=array_intersect($dbemergency,$emegncyarr);

  if(count($emegncyarr)>0 && in_array("Y",$vaclini))
    {
        array_push($rule_arr,"icd10_emergency");
        $rule_name=$allvalidationsArray[4]["rule_name"];
        $rule_description=$allvalidationsArray[4]["description"];
        $icdzf="icd10_emergency";$icdzf1="membspan3";
        ?>
        <tr>
            <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
            <td><div class="uk-text-danger uk-card uk-card-default uk-card-body">  <?php echo $rule_name;?>


                </div></td>
            <td>  <div class="uk-card uk-card-default uk-card-body"> <?php echo $rule_description;?>

                </div></td>
            <td><label><input id="icd10_emergency" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $icdzf;?>','<?php echo $icdzf1;?>')" <?php echo $icd10_emergency;?>> <span>Updated?</span></label><span id='membspan3' style="display: none"> <textarea class="uk-textarea" id='memtxt3'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="icd10_emergency('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan3'></ul></td>
        </tr>

        <?php
    }
    if((in_array("0614",$emegncyarr) && in_array("0646",$emegncyarr) ) || (in_array("0614",$emegncyarr) && in_array("0637",$emegncyarr)))
    {

        $icdzf="tarrif_0614";$icdzf1="membspan7";
         $rule_name=$allvalidationsArray[5]["rule_name"];
        $rule_description=$allvalidationsArray[5]["description"];
        ?>
        <tr>
            <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
            <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> <?php echo $rule_name;?>


                </div></td>
            <td>  <div class="uk-card uk-card-default uk-card-body">
                  <?php echo $rule_description;?>

                </div></td>
            <td><label><input id="tarrif_0614" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $icdzf;?>','<?php echo $icdzf1;?>')" <?php echo $tarrif_0614;?>> <span>Updated?</span></label><span id='membspan7' style="display: none"> <textarea class="uk-textarea" id='memtxt7'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="tarrif_0614('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan7'></ul></td>
        </tr>
        <?php
    }
    if (in_array("0589", $codes3) || in_array("0592", $codes3) || in_array("0593", $codes3))
    {
        $lassaoa="saoa"; $lassaoa1="membspan5";
         $rule_name=$allvalidationsArray[3]["rule_name"];
        $rule_description=$allvalidationsArray[3]["description"];
        ?>
        <tr>
            <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
            <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> <?php echo $rule_description;?>

                </div></td>
            <td>  <div class="uk-card uk-card-default uk-card-body">
                    <?php echo $rule_description;?>
                </div></td>
            <td><label><input id="saoa" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $lassaoa;?>','<?php echo $lassaoa1;?>')" <?php echo $saoa;?>> <span style="position: relative !important;">Confirm?</span></label><span id='membspan5' style="display: none"> <textarea class="uk-textarea" id='memtxt5'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="saoa('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan5'></ul></td>
        </tr>
        <?php
    }
    $arr = array();
    foreach ($cdarry as $key => $item) {
        $arr[$item['descr']][$key] = $item;
    }
    ksort($arr, SORT_NUMERIC);

    foreach ($arr as $mmr) {
        $ght="";
        $fd = "";
        array_push($rule_arr,"coding_checked");
        foreach ($mmr as $mmrx) {
            $num = $mmrx["number"];
            $dsc = $mmrx["descr"];
            $ght .= "<span class=\"uk-badge\">$num</span> ";

        }
        foreach (explode(";",$dsc) as $cc)
        {
            $fd .= "<ul><li>$cc</li></ul>";
        }
        $codpx="codingcptcodingcpt";$codpx1="membspan4";
        echo "   <tr>
   <td>$ght</td>
                        <td><div class=\"uk-text-danger uk-card uk-card-default uk-card-body\"> $fd</div></td>
                        <td>  <div class=\"uk-card uk-card-default uk-card-body\">
                             If the claimed ICD10 code is not in the list returned then there is a possible diagnosis to procedure mismatch.
                             <h4 align=\"center\"> <form method=\"post\" action=\"http://greenwest.co.za/clinical/index.php\" target=\"_blank\"><button name=\"clinical\" class=\"uk-button uk-button-primary\">Code look up</button></form></h4>

                             
                            </div></td>
                        <td><label><input id='codingcptcodingcpt' style='opacity: 200 !important; position: relative !important;' class=\"uk-checkbox\" type=\"checkbox\" onclick='showhide(\"$codpx\",\"$codpx1\")' $coding_checked> Confirm?</label><span id='membspan4' style=\"display: none\"> <textarea class=\"uk-textarea\" id='memtxt4'></textarea><button class=\"uk-button uk-button-primary uk-button-small\"  onclick='updateCoding(\"$claim_id\")'>Update</button></span><ul id='myspan4'></ul></td>
                    </tr>";

    }

    echo "</tbody></table>";
    $xjson=implode(",",$rule_arr);
    echo "<input type=\"hidden\" id=\"xjson\" value=\"$xjson\">";
}
function escalation()
{
    ?>

    <div id="modal-container" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <form action="case_details.php" method="post">
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">Reason for viewing this case</h2>
                </div>
                <div class="uk-modal-body">
                    <div class="input-field col s12">
                        <select class="escl" name="source" id="source" REQUIRED>

                        </select>
                        <label>Select</label>
                    </div>
                    <div class="input-field col s12">
                        <select class="escl" name="catergory" id="catergory" REQUIRED>

                        </select>
                        <label>Select</label>
                    </div>
                    <div class="input-field col s12">
                        <select class="escl" name="sub_catergory" id="sub_catergory" REQUIRED>


                        </select>
                        <label>Select</label>
                    </div>

                    <p class="uk-text-danger">Please make sure you select all the above selections</p>
                </div>
                <div class="uk-modal-footer uk-text-right">

                    <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
                    <input type="hidden" name="claim_id" id="claim_id" value="" />
                    <button class="uk-button uk-button-primary" name="btn">Proceed</button>
                </div>
            </form>
        </div>
    </div>

    <?php
}
function my8days($control)
{

    echo "<p align='center'><u>Notes</u></p>";
    echo "<div class='div8' style='padding-left: 20px'>";
    foreach($control->view8days($control->claim_id) as $row8)
    {
        $description=$row8["description"];
        $date_entered=$row8["date_entered"];
        $entered_by=$row8["entered_by"];
        echo "<hr><div class=\"uk-grid-medium uk-flex-middle uk-grid uk-grid-stack\" uk-grid=\"\" style='background-color: white'>
                            <div class=\"uk-width-expand uk-first-column\">
                                <ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">
                                    <li>Posted By : <span style='color: green'> $entered_by</span></li><li><span>$date_entered</span></li>
                                </ul>
                                <div class=\"uk-comment-body\" style=\"background-color: whitesmoke; padding: 10px; border-radius: 10px\">
                                    <p>" . nl2br($description) . "</p>
                                </div>                              
                            </div>                            
                        </div>";
    }
    echo "<p align=\"center\"><textarea rows=\"8\" class=\"uk-textarea\" placeholder=\"type your note\" style=\"width: 80%; border-color: #54bc9c;\" cols=\"80\" id=\"eightnotes\" name=\"eightnotes\"></textarea></p>

    <p align=\"center\">
    <button class='uk-button uk-button-primary' onclick='insert8days(\"$control->claim_id\")'><span uk-icon=\"comment\"></span> Post</button>
         <span id=\"eightShow\" style=\"color: green;font-weight: bolder; display: none\">Sending, please wait...</span>
        <span align=\"center\" id=\"eightAlert\" class=\"alert\" style=\"display: none; width: 60%; font-weight: bolder;\"></span></p>";
    echo "</div>";
}
function homeNotes($notes_arr,$control,$type="",$loop=0)
{
    $ccount=0;
    if(count($notes_arr)>0) {
        date_default_timezone_set('Africa/Johannesburg');
        foreach ($notes_arr as $row) {
            if($loop==1 && $ccount==1)
            {
                break;
            }
            $ccount++;
            $note_date_entered = htmlspecialchars($row["date_entered"]);
            $note = htmlspecialchars_decode($row["intervention_desc"]);
            $from_date = date('Y-m-d', strtotime($note_date_entered));
            $today = date('Y-m-d');
            $datetime1 = strtotime($from_date);
            $datetime2 = strtotime($today);
            $secs = $datetime2 - $datetime1;// == <seconds between the two times>
            $days = $secs / 86400;
            $days = round($days);
            $note_owner = $row["owner"];
            $doctor_details = "";
            $note_background = "floralwhite";
            $shownotebuttons="";
            if($type=="Notes")
            {
                $doctor_details = "Doctor Details : {".$row["practice_number"]."} {".$row["doc_name"]."}";
                $note_background = "snow";
                if(($control->isTopLevel() || $days<1) && $control->isInternal())
                {
                    $shownotebuttons=" <li><a href=\"#edit_note\" uk-icon=\"icon: pencil\" title='edit' onclick='' uk-toggle></a></li>
                                        <li><span style='cursor: pointer' uk-icon=\"icon: trash\" title='delete' onclick=''></span></li>";

                }

            }
            echo "<div class=\"uk-grid-medium uk-flex-middle uk-grid uk-grid-stack\" uk-grid=\"\" style='background-color: $note_background'>
                            <div class=\"uk-width-expand uk-first-column\">
                                <ul class=\"uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top\">
                                    <li><a href=\"#\" id=\"mytime\">$note_date_entered</a></li>
                                    <li><a href=\"#\">$note_owner</a></li>
                                    <li><i><a href=\"#\">$days Days Ago</a></i></li>
                                   $shownotebuttons
                                </ul>
                                <div id='' class=\"uk-comment-body\" style=\"background-color: whitesmoke; padding: 10px; border-radius: 10px\">
                                    <p id='' class='uk-text-normal'>".nl2br($note)."</p>
                                </div>
                                <div style='color: lightblue'>$doctor_details</div>
                            </div>
                        </div>";
        }
    }
    else
    {
        echo "  <div class=\"uk-comment-body\" style=\"background-color: whitesmoke; padding: 10px; border-radius: 10px; color: red\">
                                    <span class='nothing'>No $type</span>
                                </div>";
    }
}
?>