<?php
//error_reporting(0);
define("access",true);
include "../classes/apiClass.php";
use mcaAPI\apiClass as myAPI;
class jv_import_export extends myAPI
{
    public $mess2;
    public $mess3;
    public $username;
    public $password;

    function readFile()
    {
        $charged_amntx=0;$scheme_paidx=0;$gapx=0;
        global $conn;
        $myArray=array();
        $resultArray=array();
        $file = file_get_contents('php://input', true);
        $t=json_decode($file,true);
        $this->mess3="";
        if($t === null) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $err=array("status"=>"500","message"=>"Internal Server Error");
            echo json_encode($err,true);
            die();
        }
        $r=$t;
        $this->username = $r[0]["Username"];
        $this->password = $r[0]["Password"];
        $cclient_name="medway_production";
        $envviro="production";
        if(!$this->validate($this->username,$this->password,$cclient_name,$envviro))
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorised Access', true, 401);
            $err=array("status"=>"401","message"=>"Unauthorised Access");
            echo json_encode($err,true);
            die();
        }
        $this->addObj($file);
        $array_count=count($r);

        for($i=1;$i<$array_count;$i++) {
            $myArrayLine=array();
            $this->mess2 = "";     $claim_number = $r[$i]["claim_number"];
            $xccomstatus="Failed";
            $todaydate=date("Y-m-d H:i:s");
            try {
                $policy_number = $r[$i]["policy_number"];
                $product_name = $r[$i]["product_name"];
                $personNumber = "";
                $policyholder_name = $r[$i]["policyholder_name"];
                $policyholder_surname = $r[$i]["policyholder_surname"];
                $product_code=$r[$i]["product_code"];
                $patient_name=$r[$i]["patient_name"];
                $patient_surname=$r[$i]["patient_surname"];
                $patient_idnumber=$r[$i]["patient_idnumber"];
                $beneficiary_number=$r[$i]["beneficiary_number"];
                $cell_number=$r[$i]["cell_number"];
                $telephone_number=$r[$i]["telephone_number"];
                $email_address=$r[$i]["email_address"];
                $medicalSchemeName = $r[$i]["medical_scheme"];
                $medicalSchemeName=$this->checkScheme($medicalSchemeName);
                $medicalSchemeName=strtolower($medicalSchemeName);
                $medicalSchemeName=ucwords($medicalSchemeName);
                $medicalSchemeOption = $r[$i]["medical_scheme_option"];
                $medicalSchemeRate = "";
                $medicalSchemeNumber = $r[$i]["medical_scheme_number"];
                $incident_date_start = empty($r[$i]["incident_start_date"])?null:$r[$i]["incident_start_date"];
                $incident_date_end = empty($r[$i]["incident_date_end"])?null:$r[$i]["incident_date_end"];
                $claimChargedAmnt = (double)$r[$i]["charged_amount"];
                $schemePaidAmnt = (double)$r[$i]["schemepaid_amount"];
                $hospital_fullname = $r[$i]["hospital_fullname"];
                $hospital_provider_number = $r[$i]["hospital_provider_number"];
                $owner="Medway";
                $claimCalcAmnt=$claimChargedAmnt-$schemePaidAmnt;
                $recordType = "";
                $senderId = 13;
                $createdBy = "System";
                $createdByU = $r[$i]["last_worked_on_user"];
                $creationDate = $r[$i]["date_sent"];
                $eventStatus = "";
                $eventDateFrom = $incident_date_start;
                $eventDateTo = $incident_date_end;
                $memberLiability=0.0;
                $patient_number="";
                $switchReference="";
                $client_claim_number ="";
                $client_id = 5;
                $details=$this->checkClaimWithPolicy($policy_number,$client_id);
                $emergencyarr=explode(",",$this->getValidations(5));
                if($details==true)
                {
                    $username=$details['username'];
                }
                else
                {
                    $details=$this->getUsername();
                    $username=$details['username'];
                }

                $client_gap =(double)$r[$i]["gap_amount"];
                $note_arr=$r[$i]["notes"];
                //print_r($note_arr);
                $doctors=$r[$i]["claimedline"];
                $main_icd10=$r[$i]["icd10"];
                $main_icd10_desc=$r[$i]["icd10_description"];

                $ic10Data=$this->checkPmb($main_icd10);
                $main_pmb=strlen($ic10Data["pmb_code"])>0?1:0;
                $continue=true;

                $member_id=$this->checkMember($policy_number,$client_id);
                if(strlen($hospital_provider_number)>4)
                {
                    $hosparr=array("claimedline_id"=>"","fullname"=>$hospital_fullname,"provider_number"=>$hospital_provider_number,"provider_invoicenumber"=>"",
                        "gap_amount"=>"","insurance_productoption_id"=>"","benefit_tiers"=>"","claimline"=>array()  );
                    array_push($doctors,$hosparr);
                }
                if(strpos($main_icd10,";")>-1)
                {
                    $icdarr=explode(";",$main_icd10);
                    $main_icd10=$icdarr[0];
                    //$secondaryICDCode=$icdarr[1];
                }
                if(empty($member_id) && $continue) {


                    $cc=$this->insertMember($policy_number,$product_name,$personNumber,$policyholder_name,$policyholder_surname,$medicalSchemeName,$medicalSchemeOption,$medicalSchemeRate,$medicalSchemeNumber,$client_id,$product_code,$beneficiary_number,$cell_number,$telephone_number,$email_address);

                    if ($cc == 1) {
                        $this->mess2="Member Successfully added";
                        $selectlastmember = $conn->prepare("SELECT max(member_id) FROM member WHERE policy_number=:policy_number");
                        $selectlastmember->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
                        $selectlastmember->execute();//Claim data
                        $member_id = $selectlastmember->fetchColumn();
                    }
                    else {
                        $this->mess2="There is an error";
                        $member_id="";
                    }
                }
                $member_id=(int)$member_id;
                if($member_id>0)
                {

                    $claim_id=$this->checkClaim($claim_number,$client_id);

                    if(empty($claim_id)) {
                        $cc1=$this->insertClaim($claim_number,$member_id,$createdBy,$eventStatus,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$recordType,$senderId,$memberLiability,$createdByU,$creationDate,$username,$patient_number,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc,$client_claim_number,$patient_idnumber);
                        if ($cc1 == 1) {
                            $this->mess2="Claim Successfully added";
                            $xccomstatus="success";
                            $this->updateUsername($username);
                            $claim_id =$this->getLatestClaim($claim_number);
                            $this->InsertPatient($claim_id,$patient_name,$patient_surname);
                            $countnotes=count($note_arr);
                            for ($dn=0;$dn<$countnotes;$dn++)
                            {
                                $descriptionx=$note_arr[$dn]["note"];
                                $this->InsertFeedback($claim_id,$descriptionx,$owner);
                            }

                        }
                        else {
                            $this->mess2="Claim Failed to Load";
                            $claim_id="";
                        }
                    }
                    else
                    {
                        $xccomstatus="success";
                        $this->mess3=" (Information updated)";
                        $this->updateClaim1($claim_id,$eventDateFrom,$eventDateTo,$claimChargedAmnt,$schemePaidAmnt,$claimCalcAmnt,$client_gap,$main_pmb,$main_icd10,$main_icd10_desc);
                        $countnotes=count($note_arr);

                        for ($dn=0;$dn<$countnotes;$dn++)
                        {
                            $descriptionx=$note_arr[$dn]["note"];
                            $this->InsertFeedback($claim_id,$descriptionx,$owner);
                        }
                    }
                    $claim_id=(int)$claim_id;
                    if($claim_id>0)
                        //Doctors Information
                    {

                        $countdoctors=count($doctors);

                        for ($d=0;$d<$countdoctors;$d++)
                        {
                            $chhh=false;
                            $practiceNo = $doctors[$d]["provider_number"];

                            $pracno_1=str_pad($practiceNo, 7, '0', STR_PAD_LEFT);
                            $practiceNo=$pracno_1;
                            $practiceName = $doctors[$d]["fullname"];
                            $claimedline_id = 0;
                            $doc_gap = (double)$doctors[$d]["gap_amount"];
                            $benefitDescription ="";
                            $provider_invoicenumber =$doctors[$d]["provider_invoicenumber"];


                            $providertypedesc = "";
                            //echo $practiceNo;
                            if (!$this->checkDoctor($practiceNo)) {
                                $kk=$this->insertDoctor($practiceName,$pracno_1,$providertypedesc);
                                if ($kk==1)
                                {
                                    $this->mess2="Claim Successfully added";
                                }
                                else{
                                    $chhh=false;
                                    $this->mess2="Claim Successfully added but doctor failed to load";
                                }
                            }
                            $data_claim_doc=$this->getClaimDoctor($claim_id,$practiceNo);
                            if($data_claim_doc==true)
                            {
                                $chhh=true;
                                $doc_gaparr=$data_claim_doc;
                            }
                            if(!$chhh) {
                                $cc2=$this->insertClaimDoctor($claim_id,$practiceNo,$claimedline_id,$doc_gap,$provider_invoicenumber);
                                if ($cc2 == 1) {
                                    $this->mess2="Claim Successfully added";
                                    $chhh=true;
                                }
                                else {
                                    $chhh=false;
                                    $this->mess2="Claim Successfully added but doctor failed to load";
                                }
                            }
                            else
                            {
                                $this->updateClaimDoctor($claim_id,$practiceNo,$doc_gap);
                            }
                            if($chhh)
                            {
                                $claimLine = $doctors[$d]["claimline"];
                                $countLine=count($claimLine);
                                for($j=0;$j<$countLine;$j++) {
                                    $clmnlineNumber = $claimLine[$j]["claimline_id"];
                                    $clmnlineChargedAmnt = (double)$claimLine[$j]["claimline_charge_amount"];
                                    $clmlineSchemePaidAmnt = (double)$claimLine[$j]["claimline_schemepaid_amount"];
                                    $gap_amount_line = (double)$claimLine[$j]["gap_amount_line"];
                                    $clmlineCalcAmnt=$clmnlineChargedAmnt-$clmlineSchemePaidAmnt;
                                    $memberLiability = 0.0;
                                    $treatmentType = $claimLine[$j]["treatment_type"];
                                    $treatmentDate = $claimLine[$j]["treatment_date"];
                                    $treatmentDate=strlen($treatmentDate)>1?$treatmentDate:$eventDateFrom;
                                    $treatment_code = $claimLine[$j]["treatment_code"];
                                    $claimline_rejection_reason = $claimLine[$j]["claimline_rejection_reason"];
                                    $treatment_code_description = $claimLine[$j]["treatment_code_description"];
                                    $secondaryICDCode = "";
                                    $secondaryICDDescr = "";
                                    $primaryICDCode = $claimLine[$j]["icd10"];
                                    $primaryICDDescr = $claimLine[$j]["icd10_description"];
                                    $tariffCode = $claimLine[$j]["treatment_code"];
                                    $modifier = "";
                                    $primaryICDCode=strlen($primaryICDCode)>1?$primaryICDCode:$main_icd10;
                                    $primaryICDDescr=strlen($primaryICDDescr)>1?$primaryICDDescr:$main_icd10_desc;
                                    $ic10Data=$this->checkPmb($primaryICDCode);
                                    $main_pmb=strlen($ic10Data["pmb_code"])>0?1:0;
                                    $unit = "";
                                    $PMBFlag =$main_pmb>0 && $ic10Data["pmb_code"]!="0"?"Y":"N";
                                    $clmnLinePmntStatus = "";
                                    if(strpos($primaryICDCode,";")>-1)
                                    {
                                        $icdarr=explode(";",$primaryICDCode);
                                        $primaryICDCode=$icdarr[0];
                                        $secondaryICDCode=$icdarr[1];
                                    }
                                    $rej_code = $claimline_rejection_reason;
                                    $short_msg = $treatment_code_description;
                                    $lon_msg = "";
                                    $cptCode = "";
                                    $nappiCode = "";
                                    $quantity = "";
                                    $clmnLinePmntStatusDate = "";
                                    $treatmentCodeType = $treatment_code;
                                    $cptDescr = "";
                                    $lastUpdateDate = "";
                                    $toothNo = "";
                                    $lcc=$this->checkClaimLine($primaryICDCode,$tariffCode,$treatmentDate,$treatmentType,$claim_id,$practiceNo,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,$clmnlineNumber);
                                    if ($lcc<1) {
                                        $charged_amntx+=$clmnlineChargedAmnt;
                                        $scheme_paidx+=$clmlineSchemePaidAmnt;
                                        $gapx+=$clmlineCalcAmnt;
                                        $cc3=$this->addClaimLine($recordType,$senderId,$clmnlineNumber,$claim_id,$practiceNo,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,
                                            $memberLiability,$benefitDescription,$treatmentDate,$primaryICDCode,$primaryICDDescr,$tariffCode,$modifier,$unit,$PMBFlag,$clmnLinePmntStatus,$creationDate,$createdBy,$rej_code,$short_msg,$lon_msg,
                                            $treatmentType,$secondaryICDCode,$secondaryICDDescr,$cptCode,$nappiCode,$quantity,$clmnLinePmntStatusDate,$treatmentCodeType,$cptDescr,$lastUpdateDate,$toothNo,$switchReference,$gap_amount_line
                                        );
                                        if ($cc3 == 1) {
                                            $xccomstatus="success";
                                            $mess = "Successfully added";
                                            $this->mess3=" with additional information";
                                            $this->updateClaim($claim_id);
                                              if(in_array($tariffCode, $emergencyarr))
                                            {
                                                $this->updateClaimKey($claim_id,"emergency","1");
                                            }

                                        } else {
                                            $mess = "There is an error";
                                        }
                                    }
                                    else{
                                        $this->updateCaimline($claim_id,$practiceNo,$treatmentDate,$primaryICDCode,$primaryICDDescr,$tariffCode,$clmnlineChargedAmnt,$clmlineSchemePaidAmnt,$clmlineCalcAmnt,$treatmentType);
                                        $mess = "Duplicate Claim Line";

                                    }
                                    $eachLine=array("lineNumber"=>$clmnlineNumber,"message"=>$mess);
                                    array_push($myArrayLine,$eachLine);
                                }

                            } else {
                                $this->mess2 = "The doctor not loaded";
                            }
                        }
                        $this->updateAmount($claim_id,$charged_amntx,$scheme_paidx,$gapx);
                    } else {
                        $this->mess2 = "The claim not loaded";
                    }
                } else {
                    $this->mess2 = "The member not loaded";
                }
            } catch (Exception $e) {
                $this->mess2 = $e->getMessage();
            }
            finally
            {
                $myarr=array("claim_number"=>$claim_number,"status"=>$xccomstatus,"descr"=>$this->mess2.$this->mess3,"date_entered"=>$todaydate);
                array_push($resultArray,$myarr);
            }

            $eacharray=array("claim_number"=>$claim_number,"message"=>$this->mess2,"claimline"=>$myArrayLine);
            array_push($myArray,$eacharray);
        }

        $rc=count($myArray);
        if($rc<1)
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $err=array("status"=>"500","message"=>"Internal Server Error");
            echo json_encode($err,true);
            die();
        }

        $claim_array=array();
        $succeed=0;
        $failed=0;
        for($x=0;$x<$rc;$x++)
        {
            $num=$myArray[$x]["claim_number"];
            $message=$myArray[$x]["message"];
            $line=$myArray[$x]["claimline"];
            $this->claimaudit($num,$message);
            if($message=="Claim Successfully added")
            {
                $succeed++;
            }
            else{
                $failed++;
            }

            $xsucceed=0;
            $xfailed=0;
            $rd=count($line);
            $claim_line_array=array();
            for ($y=0;$y<count($line);$y++)
            {
                $l=$line[$y]["lineNumber"];
                $m=$line[$y]["message"];
                $in_array=array("claim_line_number"=>$l,"claim_line_message"=>$m);
                array_push($claim_line_array,$in_array);
                $this->claimaudit($num,$m,$l);

                if($m=="Successfully added")
                {
                    $xsucceed++;
                }
                else{
                    $xfailed++;
                }
            }
            $in_claim=array("event_number"=>$num,"claim_message"=>$message,"claim_line"=>$claim_line_array);
            array_push($claim_array,$in_claim);
        }
        $display_array=array("total_processed"=>$rc,"total_succeed"=>$succeed,"total_failed"=>$failed,"claims"=>$claim_array);
        $arres=json_encode($resultArray,true);
        echo $arres;
        $this->allaudit((int)$rc,(int)$failed,(int)$succeed,5,$arres);
    }
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n=new jv_import_export();
    $n->readFile();
}
else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
    $err=array("status"=>"400","message"=>"Bad Request");
    echo json_encode($err,true);
}
?>

