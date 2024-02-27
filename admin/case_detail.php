<?php
session_start();
error_reporting(0);
$individual1="<br>";
?>

<div class="midx" style="width: 30%; position: fixed;left: 37%;color: white;font-weight: bolder; top: 40%; background-color: #0f0f0f">
    <h5 align="center">loading, please wait...</h5>

</div>

<p>
    <head>

        <title>Case Details</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.simple-dtpicker.js"></script>
        <link type="text/css" href="js/jquery.simple-dtpicker.css" rel="stylesheet" />
        <link href="w3/w3.css" rel="stylesheet" />
        <link href="css/ribbon.css" rel="stylesheet" />
        <link href="css/dropdown.css" rel="stylesheet" />
        <script src="js/notes.js"></script>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="uikit/css/uikit.min.css" />
        <script src="uikit/js/uikit.min.js"></script>
        <script src="uikit/js/uikit-icons.min.js"></script>
        <title>Med ClaimAssist: Case Detail</title>

        <style>


            .notificationxxx:hover {
                background: red;
            }

            .notificationxxx .badge {
                position: absolute;
                top: -7px;
                right: -7px;
                padding: 5px 10px;
                border-radius: 50%;
                background-color: red;
                color: white;
            }

            @-webkit-keyframes blinker {
                from {opacity: 1.0;}
                to {opacity: 0.0;}
            }
            .blinkx{
                text-decoration: blink;
                -webkit-animation-name: blinker;
                -webkit-animation-duration: 0.6s;
                -webkit-animation-iteration-count:infinite;
                -webkit-animation-timing-function:ease-in-out;
                -webkit-animation-direction: alternate;
            }
        </style>
    </head>


<p style="color: white">

    <?php

    include 'header.php';
    //session_start();
    echo("<br><hr class='uk-divider-icon'>");
    $username=$_SESSION['user_id'];
    if(isset($_POST['btn']))
    {
    require_once('dbconn.php');
    require_once('classes/functionsClass.php');
    $conn=connection("mca","MCA_admin");
    $conn1=connection("doc","doctors");
    $conn2=connection("cod","Coding");
    $claim_id=validateXss($_POST['claim_id']);
    $record_index=$claim_id;
    $val=1;
    $_SESSION["currentClaimid"]=$claim_id;
    $selectDetails=$conn->prepare('SELECT a.claim_id,b.member_id,b.first_name, b.surname, b.policy_number, a.claim_number, a.savings_scheme, b.medical_scheme, b.scheme_option, a.date_closed, b.client_id, b.date_entered, 
a.Open, id_number, a.username, a.savings_discount, b.scheme_number,b.email,b.cell,b.telephone,a.pmb,a.icd10,a.charged_amnt,a.scheme_paid,a.gap,a.Service_Date,a.emergency,a.hasDrPaid,a.end_date,a.senderId,a.patient_number,
a.client_gap,a.claim_number1,a.date_entered,b.consent_descr,a.cpt_code,b.broker,a.createdBy,coding_checked,is_atheniest,provider_zf,icd10_emergency,a.date_reopened,a.sla,a.saoa,a.patient_idnumber,a.quality,tarrif_0614
 FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_id=:num');
    $selectDetails->bindParam(':num', $claim_id, PDO::PARAM_STR);
    $selectDetails->execute();
    $ccn=(int)$selectDetails->rowCount();
    if($ccn!=1)
    {
        die("There is an error");
    }
    $details=$selectDetails->fetch();
    $client = htmlspecialchars($details[10]);
    $_SESSION['client']=$client;
    $user=htmlspecialchars($details[14]);
    $gapz=getClientName($client);
    if($username==$gapz || $_SESSION['level']=="admin" || $_SESSION['level']=="controller" || $_SESSION["gap_admin"]=="assessor" || $username==$user || $username=="FumaTendai")
    {

    }
    else{
        if($username=="Kaelo" && ($client==15 || $client==27))
        {

        }
        elseif (($username=="Insuremed" && $client==26) || $username=="Gaprisk_administrators")
        {

        }
        elseif ($username=="Western" && $client==27)
        {

        }
        else
        {
            session_unset();
            session_destroy();
            session_write_close();
            die("There is an error.");
        }
    }
    if($_SESSION['level']=="claims_specialist" || $_SESSION['level']=="admin") {
        date_default_timezone_set('Africa/Johannesburg');
        $current_date=date('Y-m-d H:i:s');
        $stmt = $conn->prepare('Update claim SET new=:new,recent_date_time=:dat WHERE claim_id=:claim');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':new', $val, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $current_date, PDO::PARAM_STR);
        $stmt->execute();

        $stmt1 = $conn->prepare('UPDATE feedback SET open=1 WHERE claim_id=:num');
        $stmt1->bindParam(':num', $claim_id, PDO::PARAM_STR);
        $stmt1->execute();
    }
    ?>


<div class="w3-card w3-animate-zoom w3-card w3-border-blue alert" style="width: 98%;background-color: white;position: relative;margin-right: auto;margin-left: auto;">
    <div style="width: 99%;relative;margin-right: auto;margin-left: auto;">
        <?php


        $first_name = htmlspecialchars($details[2]);
        $member_id = htmlspecialchars($details[1]);
        $surname=htmlspecialchars($details[3]);
        $option=htmlspecialchars($details[8]);
        $date_closed=htmlspecialchars($details[9]);
        $date_entered=htmlspecialchars($details[11]);
        $id_number=htmlspecialchars($details[13]);
        $policy_number=htmlspecialchars($details[4]);
        $claim_number=htmlspecialchars($details[5]);
        $scheme=htmlspecialchars($details[7]);
        $member_no=htmlspecialchars($details[16]);
        $memb_email=htmlspecialchars($details[17]);
        $memb_phone=htmlspecialchars($details[19]);
        $client_id=htmlspecialchars($details[10]);
        $memb_cell=htmlspecialchars($details[18]);
        $pmb=htmlspecialchars($details[20]);
        $icd_10=htmlspecialchars($details[21]);
        $charged=number_format(htmlspecialchars($details[22]),2,'.',' ');
        $scheme_paid=number_format(htmlspecialchars($details[23]),2,'.',' ');
        $gap=number_format(htmlspecialchars($details[24]),2,'.',' ');
        $incident_date=htmlspecialchars($details[25]);
        $ownerName=htmlspecialchars($details[14]);
        $emergency1=htmlspecialchars($details[26]);
        $oppen=htmlspecialchars($details[12]);
        $savings_scheme=number_format(htmlspecialchars($details['savings_scheme']),2,'.',' ');
        $savings_disc=number_format(htmlspecialchars($details['savings_discount']),2,'.',' ');
        $stagg="1";
        $hasDr=htmlspecialchars($details[27]);
        $end_date=htmlspecialchars($details[28]);
        $senderId=htmlspecialchars($details[29]);
        $patient_number=htmlspecialchars($details[30]);
        $client_gap=number_format(htmlspecialchars($details[31]),2,'.',' ');
        $claim_number1=htmlspecialchars($details[32]);
        $claim_date_entered=htmlspecialchars($details[33]);
        $consent_descr=htmlspecialchars($details[34]);
        $cpt_code=htmlspecialchars($details[35]);
        $brokername="";
        $createdBy=htmlspecialchars($details[37]);
        $createdByEmail=getEmail($createdBy);
        $coding_checked=(int)$details[38]==1?"checked":"";
        $is_atheniest=(int)$details[39]==1?"checked":"";
        $provider_zf=(int)$details[40]==1?"checked":"";
        $icd10_emergency=(int)$details[41]==1?"checked":"";
        $tarrif_0614=(int)$details[47]==1?"checked":"";
        $date_reopened=htmlspecialchars($details[42]);
        $sla=isset($_POST['sla'])?(int)$_POST['sla']:(int)$details[43];
        $saoa=(int)$details[44]==1?"checked":"";
        $patient_idnumber="[".htmlspecialchars($details[45])."]";
        $quality=(int)$details[46];
        $gd_dis="";
        if($details[31]>$details[24])
        {
            $gd_dis="uk-alert-danger";
        }
        $mycn="";

        $cllx="w3-border-blue";
        if(!empty($consent_descr))
        {

            $cllx="w3-border-red notificationxxx";
            $mycn="<span class=\"badge\">$consent_descr[0]</span>";
        }
        $savs="";
        $patient=$patient_number;
        $oStatus="<b style='color: red'><u>(Open Case) [ $claim_date_entered ]</u></b>";
        $disabled="";
        $hhhh="";
        $doc=$conn->prepare('SELECT patient_name FROM patient WHERE claim_id=:num');
        $doc->bindParam(':num', $claim_id, PDO::PARAM_STR);
        $doc->execute();
        $doc_num=$doc->rowCount();
        $myclaim_number="";
        $brokertxt="";
        $blinkx="";
        if($client_id==4)
        {
            $brokername=getSubscription($memb_email);
            if(strlen($brokername)>1)
            {
                $individual1="";
                $brokertxt = "Broker Name : $brokername <hr>Unlimited queries and assistance whilst the subscription is active including:<ul><li>Investigating and sorting out short or non-payment of claims.</li><li>Assistance with pre-authorisations for procedures.</li><li>Assistance with registration on chronic disease programmes.</li><li>Information on the benefits in your chosen benefit option and the implications for the payment of claims.</li></ul>-Pro-active monitoring of invoices by doctors and the medical aid's responses to, in many instances, detect and start resolving problems before you are even aware of them.<br>-If you have gap cover, assistance with submitting claims to the gap insurer";
                $blinkx="blinkx";
            }
            else
            {

                $individual1="<h3 align=\"center\" style=\"color:purple\">Please don't forget to send email to Finance to invoice the claim together with member email and contact number</h3>";
            }
            //$myclaim_number=" <input class='w3-border-blue w3-round w3-small' type='text' style='border: none;padding: 5px' placeholder='linked claim number' id='claim_number' value='$claim_number1'><button class='w3-btn w3-white w3-border w3-border-blue w3-round w3-tiny' onclick='updateClaimNumber(\"$claim_id\")'>Update</button>";
        }
        if($doc_num>0)
        {
            foreach ($doc->fetchAll() as $j)
            {
                $p=$j[0];
                $patient.="($p)";
            }

        }
        $ql="";
        if($quality==1 && $_SESSION['level']!="gap_cover")
        {
            $ql="<form method='post' action='quality_assurance.php' onsubmit=\"return createTarget(this.target)\" target=\"formtarget\"><input type='hidden' name='claim_id' value='$claim_id'><button name='quality_name' class='uk-button uk-button-danger'> Assess</button></form>";
        }
        elseif ($quality==2 && $_SESSION['level']!="gap_cover")
        {
            $ql="<form method='post' action='quality_assurance.php' onsubmit=\"return createTarget(this.target)\" target=\"formtarget\"><input type='hidden' name='claim_id' value='$claim_id'><button name='quality_name' class='uk-button uk-button-danger'> View Assessment</button></form>";
        }
        if($oppen==0)
        {
            $oStatus="<b style='color:#b9bbbe'><u>(Case Closed) <br> $date_closed</u> | $ql</b>";
            $disabled="disabled";
            $hhhh="hidenow";
            $savs="Scheme Savings : <b class=\"uk-badge\">$savings_scheme</b> Discount Savings : <b class=\"uk-badge\">$savings_disc</b>";
        }
        elseif($oppen==4)
        {
            $oStatus="<b style='color: dodgerblue'><u>(Clinical Review) [ $claim_date_entered ]</u></b>";
            $disabled="disabled";
            $hhhh="hidenow";
            $savs="Scheme Savings : <b class=\"uk-badge\">$savings_scheme</b> Discount Savings : <b class=\"uk-badge\">$savings_disc</b>";
        }
        if(strlen($date_closed)>2 && $oppen==1)
        {
            $opd=$date_reopened;

            if(strlen($date_reopened)<2)
            {

                if(strlen($date_reopened)>10)
                {

                    $opd=$date_reopened>myreopen($claim_id)?$date_reopened:myreopen($claim_id);
                }
                else
                {

                    $opd=myreopen($claim_id);
                }

            }

            $oStatus.="<br><b style='color: red'>[Date Reopened : $opd]</b>";
        }
        $emergency2=$emergency1;
        if($emergency1=="1")
        {
            $emergency1="<b style='color: green'>(Emergency)</b>";
        }
        elseif ($emergency1=="0")
        {
            $emergency1="<b style='color: orange'>(Not Emergency)</b>";
        }
        else{
            $emergency1="";
        }
        echo "<input type='hidden' id='emmrg' value='$emergency2'/>";
        $_SESSION['ccNum']=$claim_number;
        if ($_SESSION['level'] != "claims_specialist") {
            getDetails($ownerName);
        }
        echo "<span class=\"tab\" style='border-bottom: groove; border-bottom-color:lightgrey;border-bottom-width: 0.5px;'>";
        echo " <div class=\"row uk-card uk-text-meta\">

            <div class=\"col-sm-3\">";
        //jvLinked($claim_number);
        $vaclini=["N"];
        $vaclinix=array();
        if(getPMBStatus($icd_10)=="Y"){
            echo "<font color=\"green\">";
            $vaclini=["Y"];
            echo "PMB".$emergency1;
            echo "<br>";
            echo "ICD10 code is ";
            echo $icd_10;

            echo "</font>";
        } else {
            echo "<font color=\"red\">";

            echo "Non-PMB".$emergency1;
            echo "<br>";
            echo "ICD10 code is ";
            echo $icd_10;

            echo "</font>";
        }
        echo "</div><div class=\"col-sm-3\"><u><span style='cursor: pointer' class='$blinkx' onclick='hideSection(\"memb\")'>Client Name : <b uk-tooltip=\"title: $brokertxt; pos: top-right\" style='color: #00b3ee'>".$gapz."</b></span></u></div>";
        echo "<div class=\"col-sm-3\">$oStatus</div>";
        echo "<div class=\"col-sm-3\"><b>Created By : <span style='color: #0d92e1'>$createdBy $createdByEmail</span></b></div></div>";
        echo"

       
        <span class='memb'>
        <div class=\"row \">
<table class='w3-card-4 w3-panel uk-card uk-text-small' border='0' width='98%'  style='margin-left:15px; padding: 15px' ><tr>
<td style='padding-left:10px;padding-top:10px'>
 <div class=\"col - sm - 4\">
                Name : <b>$first_name $surname</b>
            </div>
   </td>
   <td>
   <div class=\"col - sm - 4\">
                Policy Number : <b>$policy_number</b>
            </div>
   </td>
   <td>
   <div class=\"col - sm - 4\">
                ID Number : <b>$id_number</b>
            </div>
   </td>
   </tr>
   <tr>
<td style='padding-left:10px;padding-top:4px'>
 <div class=\"col - sm - 4\">
                Email: <b><a href=\"mailto:$memb_email\">$memb_email</a></b>
            </div>
   </td>
   <td>
     <div class=\"col - sm - 4\">
                Telephone : <b>$memb_phone</b>
            </div>
   </td>
   <td>
   <div class=\"col - sm - 4\">
                Cell Number : <b>$memb_cell</b>
            </div>
   </td>
   </tr>
   
    <tr style='border-bottom: double;border-bottom-color:white;border-bottom-width: 0.5px;width: 100%'>
<td style='padding-left:10px;padding-top:4px'>
 <div class=\"col - sm - 4\">
                Medical Scheme : <b>$scheme</b>
            </div>
   </td>
   <td>
     <div class=\"col - sm - 4\">
               Scheme Option : <b>$option</b>
            </div>
   </td>
   <td>
   <div class=\"col - sm - 4\">
                Member Number : <b>$member_no</b>
            </div>
   </td>
   </tr>
      <tr>
<td style='padding-left:10px;padding-top:4px; padding-bottom: 5px'>
 <div class=\"col - sm - 4\">
             Claim Number: <b style='color:black; font-size: large'>$claim_number</b> $myclaim_number
            </div>
   </td>
   <td>
     <div class=\"col - sm - 4\">
                Incident Date From : <b>$incident_date</b> To : <b>$end_date</b>
            </div>
   </td>
   <td>
   <div class=\"col - sm - 4\">
              Patient(s) : 
              <b>$patient $patient_idnumber</b>
            </div>
   </td>
   </tr>
   </table>           
           
       </div>
        </span>
   ";

        $allmydoc="";
        $aicd10="";
        $doc=$conn->prepare('SELECT DISTINCT practice_number,savings_scheme,savings_discount,doc_charged_amount,doc_scheme_amount,doc_gap,cpt_code,pay_doctor,provider_invoicenumber FROM doctors WHERE claim_id=:num AND display is null');
        $doc->bindParam(':num', $claim_id, PDO::PARAM_STR);
        $doc->execute();
        $doc_num=$doc->rowCount();
        ?>

        <div class="row">
            <div class="col-sm-12">
                <u><i><b><span onclick='hideSection("doc")' class='uk-badge'><?php echo $doc_num;?></span> <span class="uk-text-primary" style="color: #0f0f0f"> Doctor(s)</span></b></i></u><br>
                <span class="doc">
            <?php

            if($doc_num>0)
            {
                $trrk="ooo";
                echo "<table class='table w3-card w3-table-all uk-text-small' border=\"1\" style=\"width: 100%;font-size:14px\" align=\"center\" id=\"myD1\">
    <thead>
    <tr>
        <th>
            Doctor Details
        </th>
        
         <th>
          CPT4
        </th>";
                if($_SESSION['level']!="gap_cover") {
                    $trrk="";
                    ?>

                    <th>
           Inv.Dat
        </th>
                    <th>
            Modifier
        </th>
                    <th>
            Res. Code
        </th>
                    <th>
            Treat.Date
        </th>
                    <?php
                }


                echo" <th>
           PMB?
        </th>
        <th>
            Tarif.C
        </th>
        <th>
            ICD10
        </th>
        <th>
            Chrgd Amt
        </th>
        <th>
            Sch. Amt
        </th>
        <th>
           Memb.Port
        </th>
         <th>
           GAP 
        </th>";
            if($_SESSION['level']!="gap_cover") {
                echo"<th>Calc</th>";
    }
    echo"<th>
        
    </th>

</tr>
</thead>";
            $mycount=0;
            $cdarry=[];
            $arx05=[];
            $inhosparr=[];
            $emegncyarr=[];
            $codes3=[];
            if($_SESSION['level']!="gap_cover") {
                $ffd = "9";
                $ffd1 = "8";
            }
            $tot_doc_gap=0;
            $tot_doc_scheme=0;
            $tot_doc_charged=0;
            foreach ($doc->fetchAll() as $rx)
            {
                $pracno_1=$rx[0];
                $pracno_2=$rx[0];

                $allmydoc.=$pracno_1.",";
                $pracno_1=str_pad($pracno_1, 7, '0', STR_PAD_LEFT);
                $searched = "%".$pracno_1 ."%";
                $doc1=$conn->prepare('SELECT name_initials,surname,tel1code,telephone,physad1,disciplinecode,doc_id,signed,fixed_discount,discount_effective_date FROM doctor_details WHERE practice_number like :num');

                //name_initials,surname,telephone,gives_discount,discipline,practice_number,physad1,town,tel1code,tel2code,tel2,doc_id
                $doc1->bindParam(':num',$searched , PDO::PARAM_STR);
                $doc1->execute();
                $doc1_count=$doc1->rowCount();
                if($doc1_count>0)
                {
                    $ddx=$doc1->fetch();
                    $doc_fullname=$ddx[0]." ".$ddx[1];
                    $tit=$ddx[4];
                    $disciplinecode=$ddx[5];
                    $fixed_discount=(int)$ddx["fixed_discount"];
                    $discount_effective_date=$ddx["discount_effective_date"]." 00:00:00";
                    if($disciplinecode=="010" || $disciplinecode=="10")
                    {
                        array_push($vaclinix,$pracno_1);
                    }
                    $descipline_code_array=["56","57","58","59","056","057","058","059"];
                    if(in_array($disciplinecode,$descipline_code_array))
                    {
                        array_push($inhosparr,$pracno_1);

                    }
                    $theid=$ddx[6];
                    $signed=(int)$ddx[7]==1?"green":"black";
                    $docname=$ddx[0];
                    $docsurname=$ddx[1];
                    $doccolor="w3-white w3-border w3-border-black";
                    if(empty($docname) && empty($docsurname))
                    {
                        $doccolor="w3-red w3-border w3-border-red";
                    }


                    if(checkMofifier($claim_id,$pracno_1,$disciplinecode))
                    {
                        array_push($arx05,$pracno_1);

                    }
                    $mypr=$pracno_2;
                    $schemesavingsid="scd".$pracno_1;
                    $discsavingsid="dsd".$pracno_1;
                    $pay_doctor="pyd".$pracno_1;
                    $doc_schemesavings=$rx[1];
                    $doc_discountsavings=$rx[2];
                    $doc_pay=$rx[7];
                    $provider_invoicenumber=$rx[8];
                    $claimL=$conn->prepare('SELECT PMBFlag,tariff_code,clmline_scheme_paid_amnt,clmnline_charged_amnt,gap,primaryICDCode,primaryICDDescr,id,benefit_description,msg_code,msg_dscr,lng_msg_dscr,clmn_line_pmnt_status,treatmentDate,modifier,reason_code,reason_description,gap_aamount_line,modifier_name,modifier_charged,modifier_claimable FROM claim_line WHERE mca_claim_id=:num AND practice_number=:num1');
                    $claimL->bindParam(':num',$claim_id , PDO::PARAM_STR);
                    $claimL->bindParam(':num1',$mypr , PDO::PARAM_STR);
                    $claimL->execute();
                    $calClaimLine=$claimL->rowCount();
                    echo "<tbody id='$pracno_1'>";
                    $mycpt4=$rx[6];
                    $cpt4=checkCPT4($mycpt4,$claim_id,$pracno_1,$disciplinecode);
                    $docSav1="Scheme Savings : ".$doc_schemesavings." Discount Savings : ".$doc_discountsavings;
                    $zestx="";
                    $zestbtn="";
                    //echo $pracno_1."--".$claim_date_entered."---".$client_id;
                    if($fixed_discount==1 && $claim_date_entered>$discount_effective_date && $client==1)
                    {
                        $zestx="<span style='color: red;font-size: 10px'>Please do not request for discount on this doctor<br></span>";
                        $zestbtn="hidden";
                    }
                    $xdx="<form action=\"edit_doctor.php\" method=\"post\" target=\"print_popup\" onsubmit=\"window.open('edit_doctor.php','print_popup','width=1000,height=800');\"><input type=\"hidden\" name=\"doc_id\" value=\"$theid\"><button class=\"btn badge $doccolor\" name=\"btn\" title=\"edit doctor\"><span></span><span uk-icon='pencil'></span></button> </form>";

                    $yesd="<button title='$docSav1' class='btn badge w3-white w3-border w3-border-black' onclick='highlitDoctor(\"$pracno_1\",\"$doc_fullname\",\"$zestbtn\")'><span uk-icon='user'></span> Note</button> <span title='$tit' onclick='$trrk myClaimLine(\"$pracno_1\",\"$claim_id\")' style='cursor: pointer;color: $signed'><b>[</b>$pracno_1<b>]</b> $ddx[0] $ddx[1]($ddx[2]$ddx[3]) [$provider_invoicenumber]</span><span style='display: none'> <span id='$schemesavingsid'>$doc_schemesavings</span><span id='$discsavingsid'>$doc_discountsavings</span><span id='$pay_doctor'>$doc_pay</span></span> $xdx $zestx";


                    if($calClaimLine>0)
                    {
                        $doc_gap=0;
                        $doc_scheme=0;
                        $doc_charged=0;
                        $gap_tot=0;
                        $calc_tot=0;

                        $ccnt=0;
                        foreach ($claimL->fetchAll() as $cln)
                        {
                            $mycount++;
                            $mid=$cln['id'];
                            $myx1="main".$mid;
                            $myx2="txt".$mid;
                            $ic="ic".$mid;
                            $tar="tar".$mid;
                            $char="char".$mid;
                            $sch="sch".$mid;
                            $ga="ga".$mid;
                            $ga1="ga1".$mid;
                            $bt="bt".$mid;
                            $ben="be".$mid;
                            $rej="re".$mid;
                            $sus="su".$mid;
                            $trt="tr".$mid;
                            $cpt44="cpt".$mid;
                            $pmbf=getPMBStatus($cln[5]);
                            array_push($emegncyarr,$cln[1]);
                            array_push($codes3,$cln[1]);

                            if($pmbf=="Y")
                            {
                                $pmbf="<b style='color:green'>Yes</b>" ;
                                if(strlen($icd_10)<2)
                                {
                                    $vaclini=["Y"];
                                }
                            }
                            else{
                                $pmbf="<b style='color:red'>No</b>" ;
                            }
                            $allicd="";
                            $arr_icd=explode (",", $cln[5]);
                            $arr_icd1=explode (",", $cln[6]);
                            $tarifdisc=getTarrifDesc($cln[1]);
                            $icddisc=getIcd10Desc($cln[5]);
                            for($ii=0;$ii<count($arr_icd);$ii++)
                            {
                                $allicd.="<div>[$arr_icd[$ii]]</div>";
                            }
                            $modname=$cln[18];
                            $ffd="5";
                            $ffd1="4";
                            $glyphicon="";
                            $arr_mod=$cln[14];
                            $reason_code=$cln[15];
                            $reason_description=$cln[16];

                            $doc_scheme+=$cln[2];
                            $doc_charged+=$cln[3];
                            $tot_doc_gap+=$cln[4];
                            $tot_doc_scheme+=$cln[2];
                            $tot_doc_charged+=$cln[3];
                            $benefit=strlen($cln['benefit_description'])>10?"":$cln['benefit_description'];
                            $rejCode=strlen($cln['msg_code'])>1?$cln['msg_code']:$cln['reason_code'];

                            $rejDescr=$cln['lng_msg_dscr'];
                            $clmn_line_pmnt_status=$cln['clmn_line_pmnt_status'];
                            $treatmentDate=$cln['treatmentDate'];
                            $msg_dscr=$cln['msg_dscr'];
                            $stts=$clmn_line_pmnt_status;
                            $amountcheck=(double)$cln[3]>0?"":"uk-text-danger";
                            $amountcheck1=(double)$cln[2]>0?"":"uk-text-danger";
                            $amountX=(double)$cln[3]>0?"":"uk-tooltip=\"title: Please check if this amount is correct\"";
                            $amountXX=(double)$cln[2]>0?"":"uk-tooltip=\"title: Please check if this amount is correct\"";
                            $tt_gap=$client==1?0:(double)$cln[17];
                            //$member_portion=(double)$cln[4]>0?$cln[4]:(double)$cln[3]-(double)$cln[2];
                            $member_portion=(double)$cln[3]-(double)$cln[2];
                            $doc_gap+=$member_portion;
                            //$calc=calcDoctor($pracno_1,$benefit,(double)$member_portion,$tt_gap);
                            $calc=round(($member_portion-$tt_gap),2);
                            $gap_tot+=$tt_gap;
                            $calc_tot+=$calc;

                            $keee="";
                            if($_SESSION['level']=="admin" || $_SESSION['level']=="controller" || $_SESSION['level']=="claims_specialist")
                            {
                                $keee="<span uk-icon='trash'  onclick='deleteLIne(\"$mid\")'></span>";
                            }
                            $ccnt++;
                            if($ccnt>1)
                            {
                                $yesd="";
                                $docSav1="";
                            }
                            echo "<span id='$pracno_1'></span>
<tr class='w3-hover-white doc_class $pracno_1' id='$myx1'>
<td width='25%'>$yesd <span class=\"uk-badge\">$mycount</span></td>

<td> $mycpt4</td>";
                            if($_SESSION['level']!="gap_cover") {
                                $ffd="9";
                                $ffd1="8";
                                $glyphicon="<span uk-icon='pencil'></span>";
                                echo"<td title='$benefit'>$benefit</td>
<td uk-tooltip=\"title: $modname\">$arr_mod</td>
<td uk-tooltip=\"title:$reason_description\">$rejCode</td>
<td>$treatmentDate</td>";
                            }
                            echo"<td>$pmbf</td><td uk-tooltip=\"title: $tarifdisc\">$cln[1]</td><td uk-tooltip=\"title: $icddisc\">$allicd</td><td class='$amountcheck' $amountX>$cln[3]</td><td class='$amountcheck1' $amountXX>$cln[2]</td><td class='text-success'>$member_portion</td><td class='text-info'>$tt_gap</td>";
if($_SESSION['level']!="gap_cover") {
echo"<td class='text-danger'>$calc</td><td><span onclick='showEnteries(\"$mid\")' style='cursor: pointer;'>$glyphicon</span>  $keee</td>";
}
                        echo"</tr>
<tr id='$myx2' style='display: none'>
<td style='width: 20%'>$yesd</td>

<td><input type=\"text\" style='width: 80px;' title=\"CPT4\" id='$cpt44' value='$mycpt4' class=\"form-control\"></td>
  <td><input type='date' style='width: 100px' class='ben form-control' id='$ben' value='$benefit'/></td>
<td><select style='width: 80px' id='$sus'  class=\"form-control\"><option value='$stts'>$stts</option></select></td>
<td><select style='width: 80px' class='form-control' id='$rej'><option value='$rejCode'>$rejCode</option><option value='001 - copayment'>001 - copayment</option><option value='002- Prosthesis'>002- Prosthesis</option><option value='003 - Casualty'>003 - Casualty</option><option value='004 - Benefit Exclusion'>004 - Benefit Exclusion</option><option value='005 - Rejected'>005 - Rejected</option><option value='006 - Oncology'>006 - Oncology</option><option value='007 - Materials'>007 - Materials</option></select></td>
 <td><input type='date' style='width: 120px' id='$trt' value='$treatmentDate' class=\"form-control\"></td>
 <td>$pmbf</td><td><input style='width: 100px' id='$tar' value='$cln[1]' class=\"form-control\"></td><td><input style='width: 120px' id='$ic' value='$cln[5]' class=\"form-control\"></td><td><input style='width: 80px' id='$char' value='$cln[3]' class=\"form-control\"></td><td><input style='width: 100px' id='$sch' value='$cln[2]' class=\"form-control\"></td><td><input style='width: 100px' type='number' id='$ga' value='$cln[4]' class=\"form-control\"></td><td><input style='width: 100px' type='number' id='$ga1' value='$cln[17]' class=\"form-control\"></td><td><button id='$bt' onclick='editStuff(\"$mid\",\"$claim_id\",\"$pracno_1\",\"$disciplinecode\")' class='w3-btn w3-white w3-border w3-border-blue w3-round-large'>Save</button></td>
</tr>
</span> ";

                             $dddc_charged = "<div style='color: #0d92e1'>[" . number_format($rx[3], 2, '.', ',') . "]</div>";
                                $dddc_scheme = "<div style='color: #0d92e1'>[" . number_format($rx[4], 2, '.', ',') . "]</div>";
                                $dddc_gap = "<div style='color: #0d92e1'>[" . number_format($rx[5], 2, '.', ',') . "]</div>";

                            $xx=number_format($doc_charged,2,'.',' ');
                            $yy=number_format($doc_scheme,2,'.',' ');
                            $zz=number_format($doc_gap,2,'.',' ');
                            $ggap=number_format($gap_tot,2,'.',' ');
                            $aalc=number_format($calc_tot,2,'.',' ');
                            if($ccnt==$calClaimLine)
                            {
                                $practyp="practyp".$pracno_1;
                                echo "<tr style=\"border-bottom-color: black; border-bottom: groove; font-weight: bolder\">
<td colspan=\"$ffd\"><span style='color: red' id='$practyp'>$mo1</span>$cpt4</td>
 <td>$xx $dddc_charged</td><td>$yy $dddc_scheme</td><td>$zz</td><td>$ggap $dddc_gap</td>";
                                if($_SESSION['level']!="gap_cover") {
                                    echo"<td> $aalc</td>";
                                    }
echo"</tr> ";
                            }
                        }

                    }
                    else{
                        echo "<span id='$pracno_1'></span><tr class='w3-hover-white doc_class $pracno_1'>
<td>$yesd</td> <td>---</td>";
                        if($_SESSION['level']!="gap_cover") {
                            ?>

                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <td>---</td>
                            <?php
                        }


                        echo"<td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
</tr></span> ";
                    }

                    echo "</tbody>";
                }

            }
            $tot_doc_gap=number_format($tot_doc_gap,2,'.',' ');
            $tot_doc_charged=number_format($tot_doc_charged,2,'.',' ');
            $tot_doc_scheme=number_format($tot_doc_scheme,2,'.',' ');

            if($tot_doc_gap!=$gap)
            {
                $gap="<span style='color: #aa7700'>$gap</span>";
            }
            if($tot_doc_scheme!=$scheme_paid)
            {
                $scheme_paid="<span style='color: #aa7700'>$scheme_paid</span>";
            }
            if($tot_doc_charged!=$charged)
            {
                $charged="<span style='color: #aa7700'>$charged</span>";
            }
            echo "<tfoot>
<tr>
    <th>Totals :</th><th colspan='$ffd1'>$savs</th><th>$charged</th><th>$scheme_paid</th><th>$gap</th><th colspan='2' class='$gd_dis'>$client_gap</th></tr>
        </tfoot></table>";

        }
            ?>
            </span>
            </div>




        </div>

        <?php


        echo "</span>";
        echo"<div style='' class='alert-info'>";

        echo"<h5 align='center' id='dis'></h5>";
        echo"</div>";

        if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist"|| $_SESSION['level'] == "controller") {
            echo "<form action='edit_case.php' id='vv' method='post' />";
            echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
            echo "<button name='btn' class=\"uk-button uk-button-primary\" $disabled><span uk-icon=\"pencil\"></span> Edit Case</button>";
            if($oppen!=5) {
                echo " <span onclick='sendConsent(\"$memb_email\",\"$scheme\",\"$first_name\",\"$ownerName\",\"$gapz\",\"$claim_number\",\"$surname\",\"$member_no\",\"$consent_descr\")' id='consentID' title='$consent_descr'><span class=\"uk-button uk-button-primary $hhhh $cllx\"><span uk-icon=\"mail\"></span> Send Consent $mycn</span></span>";
                if(!empty($scheme)) {
                    echo " <div class=\"dropdown\" title='Files'>";
                    echo " <span class='hidden-md hidden-sm hidden-xs'><span class=\"uk-button uk-button-primary\"><span uk-icon=\"list\"></span> View Consent</span></span>";

                    echo "<div class=\"dropdown-content\">";

                    $mesg="mymessage.php?name=".$first_name."&gap=".$gapz."&scheme=".$scheme."&username=".$username;
                    echo "<a href='consent_forms.php' onclick=\"window.open('consent_forms.php','popup','width=1100,height=700'); return false;\" title='Click to view'>My Consent Forms</a>";

                    echo "<a href='$mesg' onclick=\"window.open('$mesg','popup','width=800,height=600'); return false;\" title='Click to view'>View Message</a>";

                    echo "</div>";
                    echo "</div>";
                }
            }
            echo "</form>";

        }files($record_index);
        if($oppen!=5) {
        echo "<ul class=\"nav nav-tabs\">";
        echo "<li class=\"active\"><a data-toggle=\"tab\" href=\"#home\"><b style='color: #00b3ee'>Notes</b></a></li>";
        echo"<li><a data-toggle=\"tab\" href=\"#menu1\"><b style='color: #00b3ee'><span>Feedback</span></b></a></li>";
        if($_SESSION['level']=="claims_specialist" || $_SESSION['level']=="admin" || $_SESSION['level']=="controller") {
            echo"<li><a data-toggle=\"tab\" href=\"#menu2\"><b style='color: #00b3ee'><span>Validation</span></b></a></li>";
            if($oppen==4)
            {
                echo "<li><a data-toggle=\"tab\" href=\"#clinical_notes\"><b style='color: #00b3ee'><span>Clinical Notes</span></b></a></li>";
            }
        }

        echo"</ul>";
        ?>
        <div class="tab-content" style="border-bottom: groove; border-bottom-color: #bce8f1; padding-bottom: 5px">
            <div id="home" class="tab-pane fade in active">
                <?php

                echo "<table border='1' class='uk-table' id=\"t01\" width='100%'>";
                echo "<tr align='center'>";
                echo "<th>";
                echo "Date/Time";
                echo "<th width='65%'>";
                echo "Notes";
                echo "<th>";
                echo "Days since note";
                echo "</tr>";

                $counter=0;
                feedbackAdd();
                $nnt=$conn->prepare('SELECT *FROM intervention WHERE claim_id=:num OR claim_id1=:num');
                $nnt->bindParam(':num', $claim_id, PDO::PARAM_STR);
                $nnt->execute();

                foreach($nnt->fetchAll() as $result_2){
                    $date_time=htmlspecialchars($result_2[4]);
                    $note=htmlspecialchars_decode($result_2[3]);
                    $record_index=htmlspecialchars($result_2[0]);
                    $remSt=htmlspecialchars($result_2[7]);
                    $remin=htmlspecialchars($result_2[6]);
                    $deletedRow=$record_index."q";
                    $myApn="";
                    $ccId=$deletedRow."x";
                    date_default_timezone_set('Africa/Johannesburg');
                    $from_date = date('Y-m-d', strtotime($date_time));
                    $today=date('Y-m-d');
                    $datetime1 = strtotime($from_date);
                    $datetime2 = strtotime($today);
                    $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                    $days = $secs / 86400;
                    $days=round($days);
                    $edit="";
                    $delt="";
                    $oown=$result_2[5];
                    $pracNote=$result_2[9];
                    $docNote=$result_2[10];
                    if($days==0 && $_SESSION['level'] != "gap_cover")
                    {
                        //$edit="<span style=\"float: right; color: #00b3ee; padding: 5px;cursor: pointer;\" class=\"glyphicon glyphicon-pencil\" title=\"Edit Note\" onclick='myModal(\"$note\",\"$record_index\")'></span>";
                        if ($_SESSION['level'] == "claims_specialist") {
                            $myID="$record_index"."x";
                            $delt = "<span style=\"float:right;color:red;cursor: pointer\" title='Delete Notes' id=\"$record_index\" class=\"glyphicon glyphicon-trash\" onclick=\"delete1('$record_index')\"></span><span style=\"color:purple;display: none\" id=\"$myID\">deleting...</span>";
                        }
                    }
                    if($remSt==1) {
                        $myApn = "<br><b style='color: #0d92e1'>Reminder at :  ". $remin ."</b><br><button class='btn alert-success pxc' onclick='updateReminder($record_index)'>Done!!</button><span id='remId'></span>";
                    }
                    echo "<tr class='uk-text-small' id=\"$deletedRow\" title='$oown'>";
                    echo "<td>";
                    echo $date_time;
                    echo "</td>";
                    echo "<td class='w3-leftbar w3-border-blue uk-comment uk-comment-primary'>";
                    echo "<div id=\"$ccId\" style='padding: 5px'>".nl2br($note)."</div>".$myApn.$delt."<span style=\"float: right; color: grey;\" class=\"glyphicon glyphicon-duplicate\"> $pracNote [$docNote]</span>";
                    echo "</td>";
                    echo "<td align='center'>";
                    if($days==0 && $_SESSION['level'] != "gap_cover")
                    {
                        echo"<span style=\"float:left;color: #00b3ee; padding: 5px;cursor: pointer;\" class=\"glyphicon glyphicon-pencil\" title=\"Edit Note\" onclick='noteModal(\"$note\",\"$record_index\")'></span>";

                    }
                    echo $days;
                    echo "</td>";

                    if($_SESSION['level']=="admin" && $oppen!=0) {
                        echo "<td>";
                        $myID="$record_index"."x";
                        echo "<span style=\"color:purple;display: none;\" id=\"$myID\">deleting...</span>";
                        echo "<span style=\"color:red;cursor: pointer\" title='Delete Notes' id=\"$record_index\" class=\"glyphicon glyphicon-trash\" onclick=\"delete1('$record_index')\"></span>";
                        echo "</td>";
                    }
                    echo "</tr>";

                    $counter ++;
                }

                echo "</table>";


                if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist") {

                    echo "<p class=\"\">";
                    echo "<strong style='color:red'><i>Enter your notes in the form below.  ";
                    echo "<u>";
                    echo "Remember that these notes will make up part of a report to the client.";
                    echo "</u>";
                    echo "<span> Please keep them clear, concise and do not include notes to yourself or reminders. <span style='color: forestgreen'>Remember to select the \"Close Case? (Yes)\" option if you are closing the case.</span></i></strong>";


                    echo "</p>";

                    ?>

                    <hr class="uk-divider-icon">
                    <div class="" style="border-top: double; border-top-color: grey">
                        <div class="row">
                            <div class="col-sm-8">
                                  <textarea class="uk-textarea" rows="8" cols="100" style="width:100%; border-color: #53C099;border-width: 3px; border-radius: 10px" id="intervention_desc"
                                            name="intervention_desc" onkeyup="valid()"></textarea>
                            </div>
                            <div class="col-sm-2">



                                Close Case?<br>
                                <input class="w3-radio" type="radio" id="open" name="Open" value="1" checked> No <br>
                                <?php if($oppen!=0)
                                {
                                    ?>
                                    <input class="w3-radio" type="radio" id="close" name="Open" value="0""> Yes <br>
                                    <?php
                                }
                                else{
                                    echo "<b style='color: red'>Case Closed</b><br>";
                                }
                                ?>
                                <div class="uk-margin">

                                    <select class="uk-select" id="consent_dest">
                                        <option value="">[Select Destination]</option>
                                        <option value="Provder">Provder</option>
                                        <option value="Medical aid scheme">Medical aid scheme</option>
                                        <option value="Medical aid scheme and Provider">Medical aid scheme and Provider</option>
                                        <option value="Medical aid scheme">Member</option>
                                    </select>

                                </div>
                                <input class="w3-check" type="checkbox" id="reminder" onclick="vbv()"><span style="color: #0d92e1"> Set Reminder</span>
                                <span style="display: none" id="reminder1"> <br><input type="text" id="date10" name="date10" style="width: 400px; border-color: #3C510C;font-weight: bolder"></span>
                                <br>
                                <br>
                                <div id="alert" class="alert alert-danger"
                                     style="display: none; width: 100%; font-weight: bolder;"></div>
                                <button class="btn" style="background-color: #53C099;color: white" id="addNotes" onclick="addNotes('<?php echo $claim_id;?>','<?php echo $sla;?>')" disabled="true")"><span class="glyphicon glyphicon-plus-sign"></span> Add Notes</button>
                                <br>
                                <span style="color: green;font-weight: bolder; display: none" id="meshow">Please wait...</span>
                            </div>
                            <div class="col-sm-2 w3-animate-zoom" style="display: none" id="doc_detail1">
                                <u> <span style="color:#53C099; font-weight: bolder" id="doc_name"> </span></u>(Savings)
                                <span style="display: none" id="doc_practiceno"></span>
                                <input type="number" title="Scheme savings" id="doc_schemesavings" placeholder="Scheme" class="form-control">
                                <br>
                                <input type="number" title="Discount savings" id="doc_discountsavings" placeholder="Discount" class="form-control">
                                <br>
                                <span style="color: #00aa88">Pay Provider? :</span> <input type="radio" id="pay_doctor1" name="pay_doctor" value="yes"> Yes <input type="radio" id="pay_doctor2" name="pay_doctor" value="no"> No

                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div id="menu1" class="tab-pane fade">

                <?php
                if($_SESSION['level'] == "claims_specialist" && sumFeedback()<1)
                {
                    echo"<h4 style=\"color: red;\" align=\"center\"><b>No Feedback</b></h4>";
                }
                else{
                    ?>
                    <h4 style="color: red;" align="center"><u><b>Feedback</b></u></h4>
                    <div class="feedbackDiv">
                        <?php
                        feedback();

                        ?>
                    </div>
                    <p align="center">
                        <select id="fxd" class="form-control" style="width: 400px; border-color: #3C510C;font-weight: bolder">
                            <option value="-">Not listed below</option>
                            <option value="No feedback note for more than 2 days">No feedback note for more than 2 days</option>
                            <option value="Claim Number incorrect">Claim Number incorrect</option>
                            <option value="Policy Number Incorrect">Policy Number Incorrect</option>
                            <option value="Wrong Doctor mentioned in note">Wrong Doctor mentioned in note</option>
                            <option value="Gap value in case doesn't match ours">Gap value in case doesn't match ours</option>
                            <option value="Doctor Disputing discount">Doctor Disputing discount</option>

                        </select></p>
                    <p align="center"><textarea rows="8" placeholder="add feedback here..."
                                                style="width: 80%; border-color: #00b3ee; border-width: 3px; border-radius: 10px" cols="80"
                                                style="width:100%; border-color: green" id="feedback_desc" name="feedback_desc"
                                                onkeyup="valid1()"></textarea></p>

                    <p align="center">
                        <button class="btn w3-btn w3-white w3-border w3-border-blue w3-round-large" id="addFeedback"><span class=" w3-round-large glyphicon glyphicon-send"> Send</span> </button>
                        <span style="color: green;font-weight: bolder; display: none" id="feedbackShow">Sending, please wait...</span><span
                            align="center" id="alert1" class="alert" style="display: none; width: 60%; font-weight: bolder;"></span></p>

                    <?php
                }
                ?>
            </div>
            <div id="clinical_notes" class="tab-pane fade">

                <h4 class="uk-text-large" align="center">Clinical Notes</h4>
                <div class="clinicalnotesDiv">
                    <?php
                    clinicalNotes();
                    ?>
                </div>

                <p align="center"><textarea rows="8" class="uk-textarea" placeholder="clinical notes here..." style="width: 80%; border-color: #00b3ee;" cols="80" id="cnotes" name="cnotes"></textarea></p>

                <p align="center">
                    <label style="margin-bottom: 20px"><input name="refback" id="refback" class="uk-checkbox" type="checkbox"> Refer back?</label><br>
                    <button class="uk-button uk-button-primary" id="addclinicalNotes"><span uk-icon="comment"></span> Post </button>
                    <span style="color: green;font-weight: bolder; display: none" id="clinicalShow">Sending, please wait...</span>
                    <span align="center" id="clinicalAlert" class="alert" style="display: none; width: 60%; font-weight: bolder;"></span></p>

            </div>
            <?php
            $rule_arr=[];
            if($_SESSION['level']=="claims_specialist" || $_SESSION['level']=="admin" || $_SESSION['level']=="controller") {
            ?>
            <div id="menu2" class="tab-pane fade">
                <br>
                <h4 align="center"><u>Clinical Rules</u></h4>
                <div style="position: relative;width: 90%;margin-right: auto;margin-left: auto">

                    <table class="uk-table uk-table-divider">
                        <thead>
                        <tr>
                            <th style="width: 20%">Number</th>
                            <th style="width: 20%">Rules</th>
                            <th>Description</th>
                            <th>Confirm</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $tv=count($vaclinix);
                        $tv1=count($arx05);
                        $tv2=count($inhosparr);

                        if($vaclini[0]=="Y" && $tv>0)
                        {
                            array_push($rule_arr,"is_atheniest");
                            ?>


                            <tr>
                                <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
                                <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> PMB claim and there is an Anaesthetist. On Practice Number :
                                        <br>
                                        <?php
                                        for ($i=0;$i<$tv;$i++)
                                        {
                                            echo"<ul><li>$vaclinix[$i]</li></ul>";
                                        }
                                        $is_atheniestxx="is_atheniest";$membspan1xx="membspan1";
                                        ?>
                                    </div></td>
                                <td>  <div class="uk-card uk-card-default uk-card-body">
                                        If the ICD10 code is for a PMB and there is an anaesthetist on the claim, then the scheme must be approached to settle at least the anaesthetist bill in full. This is whether the case was an emergency or not. The schemes do not have anaesthetist DSPs. The discussion with the scheme must include requesting proof that, at the time of authorisation, the patient was informed that the anaesthetist was a non-DSP and provided with contact details for a DSP alternative.
                                    </div></td>
                                <td><label><input id="is_atheniest" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $is_atheniestxx;?>','<?php echo $membspan1xx;?>')"  <?php echo $is_atheniest;?>> Did you approach the scheme to settle at least the anaesthetist bill in full?</label><hr>  <span id='membspan1' style="display: none"> <textarea class="uk-textarea" id='memtxt1'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="is_atheniest('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan1'></ul></td>
                            </tr>



                            <?php
                        }


                        if($tv1>0)
                        {
                            array_push($rule_arr,"provider_zf");
                            ?>
                            <tr>
                                <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
                                <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> The provider may not have applied modifier 0005 properly. Please check the invoice and call the provider if needed. On Practice Number :
                                        <br>
                                        <?php
                                        for ($i=0;$i<$tv1;$i++)
                                        {
                                            echo"<ul><li>$arx05[$i]</li></ul>";
                                        }
                                        $zpf="provider_zf";$zpf1="membspan2";
                                        ?>
                                    </div></td>
                                <td>  <div class="uk-card uk-card-default uk-card-body">
                                        Modifier 0005 decreases the price chargeable for the second and subsequent procedures performed under the same anaesthetic.
                                    </div></td>
                                <td><label><input id="provider_zf" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $zpf;?>','<?php echo $zpf1;?>')" <?php echo $provider_zf;?>> Confirm?</label><span id='membspan2' style="display: none"> <textarea class="uk-textarea" id='memtxt2'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="provider_zf('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan2'><ul></ul></td>
                            </tr>



                            <?php
                        }
                        if($tv2>100)
                        {
                            ?>
                            <tr>
                                <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
                                <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> Please make sure CPT4 code(s) is/are there on the below practice Number (s) :
                                        <br>
                                        <?php
                                        for ($i=0;$i<$tv2;$i++)
                                        {
                                            echo"<ul><li>$inhosparr[$i]</li></ul>";
                                        }
                                        ?>
                                    </div></td>
                                <td>  <div class="uk-card uk-card-default uk-card-body">
                                        There is a hospital provider on this claim - ensure that the hospital procedure code(s) - the CPT4 codes, align with those of the medical doctor.
                                    </div></td>
                                <td><label><input class="uk-checkbox" type="checkbox"> Confirm?</label></td>
                            </tr>



                            <?php
                        }
                        $emegncyarr=array_intersect(["0011","0145","0146","0001","415"],$emegncyarr);

                        if(count($emegncyarr)>0 && $vaclini[0]=="Y" && $emergency2!=1)
                        {
                            array_push($rule_arr,"icd10_emergency");
                            $icdzf="icd10_emergency";$icdzf1="membspan3";
                            ?>
                            <tr>
                                <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
                                <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> ICD10 code indicates a PMB diagnosis and at least one of the procedures codes on the invoice is an emergency: the claim/case should read PMB/Emergency.


                                    </div></td>
                                <td>  <div class="uk-card uk-card-default uk-card-body">
                                        Create rule logic for emergency procedures,if the the ICD10 code indicates PMB yes and has one of the procedures codes then the claim/case should read PMB yes emergency, the procedure codes are 0011,0145,0146,0001,415

                                    </div></td>
                                <td><label><input id="icd10_emergency" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $icdzf;?>','<?php echo $icdzf1;?>')" <?php echo $icd10_emergency;?>> Updated?</label><span id='membspan3' style="display: none"> <textarea class="uk-textarea" id='memtxt3'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="icd10_emergency('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan3'></ul></td>
                            </tr>

                            <?php
                        }
                        if((in_array("0614",$emegncyarr) && in_array("0646",$emegncyarr) ) || (in_array("0614",$emegncyarr) && in_array("0637",$emegncyarr)))
                        {

                            $icdzf="tarrif_0614";$icdzf1="membspan7";
                            ?>
                            <tr>
                                <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
                                <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> Contact the provider to request reversal. If the provider declines to reverse contact the scheme to request reversal. Contact the member to inform them that the member is not liable.


                                    </div></td>
                                <td>  <div class="uk-card uk-card-default uk-card-body">
                                        <ul><li>0614 (debridement of large joint) cannot be charged with 0646 (total knee replacement)</li>
                                            <li>0614 (debridement of a large joint) cannot be charged with 0637 (total hip replacement)</li></ul>

                                    </div></td>
                                <td><label><input id="tarrif_0614" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $icdzf;?>','<?php echo $icdzf1;?>')" <?php echo $tarrif_0614;?>> Updated?</label><span id='membspan7' style="display: none"> <textarea class="uk-textarea" id='memtxt7'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="tarrif_0614('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan7'></ul></td>
                            </tr>



                            <?php
                        }
                        if (in_array("0589", $codes3) || in_array("0592", $codes3) || in_array("0593", $codes3))
                        {
                            $lassaoa="saoa"; $lassaoa1="membspan5";
                            ?>
                            <tr>
                                <td><span class="uk-icon-button uk-margin-small-right" uk-icon="check"></span></td>
                                <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> The item may only be coded with joint procedures in cases where tenosynovitis is present and indicated (SAOA September 2019). The provider must motivate for this code to be accepted if other joint procedures are coded.

                                    </div></td>
                                <td>  <div class="uk-card uk-card-default uk-card-body">
                                        Codes 0589, 0592, and 0593 cannot be routinely claimed on joint replacements, fractures and other procedures.
                                    </div></td>
                                <td><label><input id="saoa" class="uk-checkbox" type="checkbox" onclick="showhide('<?php echo $lassaoa;?>','<?php echo $lassaoa1;?>')" <?php echo $saoa;?>> Have you received motivation from the provider if there were other joint procedures present on claim?</label><span id='membspan5' style="display: none"> <textarea class="uk-textarea" id='memtxt5'></textarea><button class="uk-button uk-button-primary uk-button-small"  onclick="saoa('<?php echo $claim_id;?>')">Update</button></span><ul id='myspan5'></ul></td>
                            </tr>



                            <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }
    $xjson=implode(",",$rule_arr);
    ?>
    </div>
    <input type="hidden" id="xjson" value="<?php echo $xjson;?>">
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 align="center" class="modal-title" style="color: green"><b style="color:red">You have selected to close the case</b>
                        <?php
                        echo $individual1;
                        ?>
                        If this is correct please enter the savings in the appropriate area below and click confirm:
                </div></h4>
                <div class="modal-body">
                    <input type="hidden" id="claim_id" name="claim_id" value=$claim_id>
                    <input type="hidden" id="Open" name="Open" value=0>
                    <input type="hidden" id="user" name="user" value=$username>
                    <input type="hidden" id="allmydoc" name="allmydoc" value="<?php echo $allmydoc;?>">
                    <span><b>Savings as a result of the scheme paying - numbers only</b></span>
                    <input type="number" id="savings_scheme" class="form-control zeroc" name="savings_scheme" value="" min="1">
                    <br>

                    <span><b>Savings as a result of the doctor giving a discount - numbers only</b></span>
                    <input type="number" id="savings_discount" class="form-control zeroc" name="savings_discount" value="" min="1">
                    <br>
                    <span style="color: #0a2b1d; display: none" id="spanzero"> <span><b>Select Catergory :</b></span>
                    <select name="zerosavings" id="zerosavings" class="form-control">
                        <option value="">Select Category</option>
                        <option value="Voluntary use of a non dsp">Voluntary use of a non dsp</option>
                        <option value="High escalation where claim is late, member wants claim paid">High escalation where claim is late, member wants claim paid</option>
                        <option value="Member already paid claim">Member already paid claim</option>
                        <option value="Claim older than 30 days">Claim older than 30 days</option>
                        <option value="Planned procedure , auth prior to the procedure">Planned procedure , auth prior to the procedure</option>
                    </select> </span>
                    <p align="center" style="display: none; color:blue; font-weight: bolder;" id="modAlert"></p>
                    <p style="display: none; color: green" id="modShow">Please wait...</p>
                    <div class="modal-footer">

                        <?php
                        $admed="block";
                        if($client==6)
                        {
                            $admed="none";
                            echo "<p id='xxadmed1' align=\"center\"><label onclick='admedcheck()' class='uk-text uk-text-danger'><input id='xxadmed' class=\"w3-check\" type=\"checkbox\"> Did you close the case on MAGPI?</label></p>";
                        }
                        ?>
                        <p id='xxadmed2' style="display: <?php echo $admed?>" align="center"><button class="btn btn-info" value="" id="addSavings" onclick="addSavings('<?php echo $claim_id;?>')">Close the Case and Record Savings</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div id="myModal1" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="background-color: lightgrey">
                <div class="modal-header">
                    <button type="button" style="color: red" class="close" data-dismiss="modal">&times;</button>

                    <p align="center">
                             <textarea id="editField" rows="8" placeholder="add feedback here..."
                                       style="width: 80%; border-color: #00b3ee; border-width: 3px; border-radius: 10px" cols="100"
                             ></textarea>
                        <input type="hidden" id="hiddenTextbox"/><br>
                        <span id="resultText"></span>
                    </p>
                    <p align="center">  <button onclick="updateText()" class="w3-btn w3-blue w3-border w3-border-blue w3-round-large"><span style="color: black; font-weight: bolder;" class="glyphicon glyphicon-pencil"> Edit</span></button>
                    </p>
                </div></h4>
                <div class="modal-body">
                </div>

            </div>
        </div>
    </div>
    </p>
    <?php
    }
    else {
        if ($_SESSION['level'] != "gap_cover") {
            echo "<p align='center' id='hideme'><label><input class=\"uk-checkbox\" type=\"checkbox\" onclick='promotrClaim(\"$claim_id\")'> Promote this claim?</label></p><p align='center' id='prinfo'></p>";
            echo "<hr><p align='center'><a href='preassessed.php'> <button name='btn' class=\"uk-button uk-button-secondary\"><span uk-icon=\"list\"></span> Other Claims</button></a></p>";
        }
    }
    }
    else
    {
        ?>
        <script type="text/javascript">
            location.href = "index.php"
        </script>

        <?php
    }
    ?>

    <hr>
    <input style="display: none" type="text" id="tt" name="tt">
    <input style="display: none" type="text" id="tt1" name="tt1">
    <?php
    include('footer.php');
    ?>
</div>
</div>
<span class="not"  style="bottom: 0px; position: fixed;left: 0px;"></span>
<script>
    $(document).ready(function () {
        chekconfirm('<?php echo $claim_id;?>');
        checkDates('<?php echo $claim_id;?>');
    });
</script>
</body>
</html>
