<?php
//error_reporting(0);
include ("../../mca/link4.php");
$conn=connection("seamless","seamless");
$conn1=connection("doc","doctors");
$conn2=connection("cod","Coding");

class jv_import_export
{
    public $mess2;
    public $mess3;
    public $username;
    public $password;

    function getClaimHeader($policy_number,$id_number,$product_name,$scheme_number,$icd10,$incident_date)
    {
        global $conn;
        $range1 = date_create($incident_date);
        date_sub($range1, date_interval_create_from_date_string("1 days"));
        $range1=date_format($range1, "Y-m-d");
        $range2 = date_create($incident_date);
        date_add($range2, date_interval_create_from_date_string("1 days"));
        $range2=date_format($range2, "Y-m-d");
//echo $policy_number."--".$id_number."--".$scheme_number."-".$range1."--".$range2;
        $checkM=$conn->prepare('SELECT *FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id LEFT JOIN patient as p ON a.claim_id=p.claim_id WHERE b.policy_number=:policy_number AND b.id_number=:id_number AND b.scheme_number=:scheme_number AND (a.Service_Date BETWEEN :date1 AND :date2) LIMIT 1');
        $checkM->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
        $checkM->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $checkM->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
        $checkM->bindParam(':date1', $range1, PDO::PARAM_STR);
        $checkM->bindParam(':date2', $range2, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetch();
    }
    function checkAdditionalDoctors($adoct,$claim_id)
    {
        global $conn;
        $checkM=$conn->prepare("SELECT * FROM `doctors` as a INNER JOIN doctor_details as b ON a.practice_number=b.practice_number WHERE a.claim_id=:claim_id AND a.practice_number NOT IN $adoct");
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }

    function findDoctor($claim_id,$practice_number)
    {
        global $conn;
        $checkM=$conn->prepare('SELECT * FROM `doctors` WHERE claim_id=:claim_id AND practice_number=:practice_number LIMIT 1');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetch();
    }
    function findClaimLineNew($claim_id,$practice_number)
    {
        global $conn;
        $checkM=$conn->prepare('SELECT * FROM `claim_line` WHERE mca_claim_id=:claim_id AND practice_number=:practice_number');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    function getAllClaimLines($claim_id)
    {
        global $conn;
        $checkM=$conn->prepare('SELECT * FROM `claim_line` WHERE mca_claim_id=:claim_id');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetchAll();
    }
    function findClaimLine($claim_id,$practice_number,$icd10,$treatment_code,$treatment_date)
    {
        global $conn;
        $range1 = date_create($treatment_date);
        date_sub($range1, date_interval_create_from_date_string("1 days"));
        $range1=date_format($range1, "Y-m-d");
        $range2 = date_create($treatment_date);
        date_add($range2, date_interval_create_from_date_string("1 days"));
        $range2=date_format($range2, "Y-m-d");
        $checkM=$conn->prepare('SELECT mca_claim_id,practice_number,primaryICDCode,tariff_code,treatmentDate,treatment_code_type,primaryICDDescr,clmnline_charged_amnt,msg_dscr,reason_description,reason_code,clmline_scheme_paid_amnt,gap FROM `claim_line` WHERE mca_claim_id=:claim_id AND practice_number=:practice_number AND treatmentDate BETWEEN :date1 AND :date2 LIMIT 1');
        $checkM->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $checkM->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $checkM->bindParam(':date1', $range1, PDO::PARAM_STR);
        $checkM->bindParam(':date2', $range2, PDO::PARAM_STR);
        $checkM->execute();
        return $checkM->fetch();
    }
//,,,claimline_schemerate_amount,
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
        $cclient_name="seamless_production";
        $envviro="production";

        if(!$this->validate($this->username,$this->password,$envviro,$cclient_name))
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorised Access', true, 401);
            $err=array("message"=>"Unauthorised User Access","status"=>"401");
            echo json_encode($err,true);
            die();
        }
        $this->addObj($file);
        $array_count=count($r);

        for($i=1;$i<$array_count;$i++) {
            $myArrayLine=array();
            $mess = "";
            $this->mess2 = "";
            $cccv="";

            $claim_number = $r[$i]["claim_number"];
            $xccomstatus="Failed";
            $todaydate=date("Y-m-d H:i:s");
            try {

                $policy_number = $r[$i]["policy_number"];
                $product_name = $r[$i]["product_name"];
                $today_date=date("Y-m-d H:i:s");
                $policyholder_name = $r[$i]["policyholder_name"];
                $policyholder_surname = $r[$i]["policyholder_surname"];
                $product_code = $r[$i]["product_code"];
                $patient_name = $r[$i]["patient_name"];
                $patient_surname = $r[$i]["patient_surname"];
                $patient_idnumber = $r[$i]["patient_idnumber"];
                $policyholder_idnumber = $r[$i]["policyholder_idnumber"];
                $beneficiary_number = $r[$i]["beneficiary_number"];
                $cell_number = $r[$i]["cell_number"];
                $telephone_number = $r[$i]["telephone_number"];
                $email_address = $r[$i]["email_address"];
                $medicalSchemeName = $r[$i]["medical_scheme"];
                $medicalSchemeOption = $r[$i]["medical_scheme_option"];
                $hospital_fullname = $r[$i]["medical_scheme_option"];
                $icd10_description = $r[$i]["icd10_description"];
                $hospital_provider_number = $r[$i]["hospital_provider_number"];
                $icd10 = $r[$i]["icd10"];
                $medicalSchemeRate = "";
                $medicalSchemeNumber = $r[$i]["medical_scheme_number"];
                $incident_date_start = $r[$i]["incident_date_start"];
                $incident_date_end = $r[$i]["incident_date_end"];
                $claimChargedAmnt = (double)$r[$i]["charged_amount"];
                $schemePaidAmnt = (double)$r[$i]["schemepaid_amount"];
                $senderId = 25;
                $createdBy = "System";
                $result="";
                $creationDate = $r[$i]["date_sent"];
                /* $claim_header_arr = array(
                     "claim_number" => $claim_number, "patient_name" => $patient_name, "product_code" => $product_code, "product_name" => $product_name,
                     "email_address" => $email_address, "policy_number" => $policy_number, "charged_amount" => $claimChargedAmnt, "medical_scheme" => $medicalSchemeName, "patient_surname" => $patient_surname,
                     "patient_idnumber" => $patient_idnumber, "telephone_number" => $telephone_number, "hospital_fullname" => $hospital_fullname, "icd10_description" => $icd10_description, "incident_date_end" => $incident_date_end,
                     "policyholder_name" => $policyholder_name, "schemepaid_amount" => $schemePaidAmnt, "beneficiary_number" => $beneficiary_number, "incident_date_start" => $incident_date_start, "policyholder_surname" => $policyholder_surname,
                     "medical_scheme_number" => $medicalSchemeNumber, "medical_scheme_option" => $hospital_fullname, "policyholder_idnumber" => $policyholder_idnumber, "hospital_provider_number" => $hospital_provider_number,
                     "icd10" => $icd10, "date_sent" => $creationDate, "cell_number" => $cell_number
                 );
 */
                if ($this->getClaimHeader($policy_number, $patient_idnumber, $product_name, $medicalSchemeNumber, $icd10, $incident_date_start) == true) {
                    $claim_data = $this->getClaimHeader($policy_number, $patient_idnumber, $product_name, $medicalSchemeNumber, $icd10, $incident_date_start);
                    $claim_numberx=$claim_data["claim_number"];
                    $sender_dx=(int)$claim_data["senderId"];
                    $claim_header_arr = array(
                        "claim_number" => $claim_number, "patient_name" => $patient_name, "product_code" => $claim_data["product_code"], "product_name" => $claim_data["productName"],
                        "email_address" => $claim_data["email"], "policy_number" => $claim_data["policy_number"], "charged_amount" => $claim_data["charged_amnt"], "medical_scheme" => $claim_data["medical_scheme"], "patient_surname" => $patient_surname,
                        "patient_idnumber" => $claim_data["patient_number"], "telephone_number" => $claim_data["telephone"], "hospital_fullname" => "", "icd10_description" => $claim_data["icd10_desc"], "incident_date_end" => $claim_data["end_date"],
                        "policyholder_name" => $claim_data["first_name"], "schemepaid_amount" => $claim_data["scheme_paid"], "beneficiary_number" => $claim_data["beneficiary_number"], "incident_date_start" => $claim_data["Service_Date"], "policyholder_surname" => $claim_data["surname"],
                        "medical_scheme_number" => $claim_data["scheme_number"], "medical_scheme_option" => $claim_data["scheme_option"], "policyholder_idnumber" => $claim_data["id_number"], "hospital_provider_number" => "",
                        "icd10" => $claim_data["icd10"], "date_sent" => $today_date, "cell_number" => $claim_data["cell"],"last_worked_on_user"=>"Medclaim Assist","gap_amount"=>$claim_data["gap"],"status"=>"Closed","intervention_description"=>"Seamless Lodge Claim","scheme_savings"=>0,"discount_savings"=>0,"pay_provider"=>"No","claimedline_id"=>""
                    );
                    $claim_id = $claim_data["claim_id"];
                    $doctors = $r[$i]["claimedline"];
                    $countdoctors = count($doctors);
                    $kaelodoctorarr = array();
                    $adoctor="(";
                    for ($d = 0; $d < $countdoctors; $d++) {
                        $practice_number = $doctors[$d]["provider_number"];
                        $adoctor.="\"$practice_number\",";
                        $practiceName = $doctors[$d]["fullname"];
                        $claimedline_id = $doctors[$d]["claimedline_id"];
                        $provider_invoicenumber = $doctors[$d]["provider_invoicenumber"];
                        $doctor_arr = array("fullname" => $practiceName, "claimedline_id" => $claimedline_id, "provider_number" => $practice_number, "provider_invoicenumber" => $provider_invoicenumber);
                        if ($this->findDoctor($claim_id, $practice_number) == true) {
                            $claimLine = $doctors[$d]["claimline"];
                            $countLine = count($claimLine);
                            $claimlinearr = array();
                            for ($j = 0; $j < $countLine; $j++) {
                                $clmnlineChargedAmnt = (double)$claimLine[$j]["claimline_charge_amount"];
                                $clmlineSchemePaidAmnt = (double)$claimLine[$j]["claimline_schemepaid_amount"];
                                $claimlineicd10 = $claimLine[$j]["icd10"];
                                $claimline_id = $claimLine[$j]["claimline_id"];
                                $treatment_code = $claimLine[$j]["treatment_code"];
                                $treatment_date = $claimLine[$j]["treatment_date"];
                                $treatment_type = $claimLine[$j]["treatment_type"];
                                $icd10_description = $claimLine[$j]["icd10_description"];
                                $claimline_rejection_reason = $claimLine[$j]["claimline_rejection_reason"];
                                $treatment_code_description = $claimLine[$j]["treatment_code_description"];
                                $claimline_schemerate_amount = $claimLine[$j]["claimline_schemerate_amount"];
                                $mismatch = array();
                                $mcaloarr = array();

                                $kaeloarr = array("icd10" => $claimlineicd10, "claimline_id" => $claimline_id, "treatment_code" => $treatment_code, "treatment_date" => $treatment_date, "treatment_type" => $treatment_type,
                                    "icd10_description" => $icd10_description, "claimline_charge_amount" => $clmnlineChargedAmnt, "claimline_rejection_reason" => $claimline_rejection_reason, "treatment_code_description" => $treatment_code_description,
                                    "claimline_schemepaid_amount" => $clmlineSchemePaidAmnt, "claimline_schemerate_amount" => $claimline_schemerate_amount);
                                if ($this->findClaimLine($claim_id, $practice_number, $icd10, $treatment_code, $treatment_date) == true) {
                                    $claimline_arr = $this->findClaimLine($claim_id, $practice_number, $icd10, $treatment_code, $treatment_date);
                                    $treatment_code_type = $claimline_arr["treatment_code_type"];
                                    $primaryICDDescr = $claimline_arr["primaryICDDescr"];
                                    $clmnline_charged_amnt = (double)$claimline_arr["clmnline_charged_amnt"];
                                    $msg_dscr = $claimline_arr["msg_dscr"];
                                    $reason_description = $claimline_arr["reason_description"];
                                    $reason_code = $claimline_arr["reason_code"];
                                    $clmline_scheme_paid_amnt = (double)$claimline_arr["clmline_scheme_paid_amnt"];
                                    $gap = $claimline_arr["gap"];
                                    $mcaloarr = array("icd10" => $claimlineicd10, "claimline_id" => $claimline_id, "treatment_code" => $treatment_code, "treatment_date" => $treatment_date, "treatment_type" => $treatment_code_type,
                                        "icd10_description" => $primaryICDDescr, "claimline_charge_amount" => $clmnline_charged_amnt, "claimline_rejection_reason" => $reason_description, "treatment_code_description" => $msg_dscr,
                                        "claimline_schemepaid_amount" => $clmline_scheme_paid_amnt, "claimline_schemerate_amount" => 0, "reason_code" => $reason_code
                                    );
                                    if ($clmnlineChargedAmnt != $clmnline_charged_amnt) {
                                        array_push($mismatch, "claimline_charge_amount");
                                    }
                                    if ($clmlineSchemePaidAmnt != $clmline_scheme_paid_amnt) {
                                        array_push($mismatch, "claimline_schemepaid_amount");
                                    }
                                    if ($treatment_type != $treatment_code_type) {
                                        array_push($mismatch, "treatment_type");
                                    }
                                    if ($icd10_description != $primaryICDDescr) {
                                        array_push($mismatch, "icd10_description");
                                    }
                                    if ($claimline_rejection_reason != $reason_description) {
                                        array_push($mismatch, "claimline_rejection_reason");
                                    }
                                    if ($treatment_code_description != $msg_dscr) {
                                        array_push($mismatch, "claimline_charge_amount");
                                    }
                                    if ($clmnlineChargedAmnt != $clmnline_charged_amnt) {
                                        array_push($mismatch, "treatment_code_description");
                                    }
                                }
                                else{
                                    $mcaloarr="The claim line not found on MCA";
                                }
                                $arr_claim_line = array("kaelo_claimline" => $kaeloarr, "mca_claimline" => $mcaloarr, "mismatch_fields" => $mismatch);
                                array_push($claimlinearr, $arr_claim_line);
                            }
                            $doctor_arr["claimline"]=$claimlinearr;
                            //array_push($doctor_arr, array("claimline" => "doctor not found"));
                        } else {

                            $doctor_arr["claimline"]="practice number : ".$practice_number." not found on MCA";
                            //array_push($doctor_arr, array("claimline" => $claimlinearr));
                        }
                        array_push($kaelodoctorarr, $doctor_arr);

                    }
                    $adoctor.=")";
                    $adoctor=str_replace(",)",")",$adoctor);
                    $inmcadoctors=array();
                    if($sender_dx==10)
                    {
                        $arrmca = $this->checkAdditionalDoctors($adoctor, $claim_id);
                           $biil_doc=$this->getBillingDoctor($claim_numberx);
                            $practice_number = $biil_doc["practice_number"];
                            $practiceName = $biil_doc["doctor_name"];
                            $claimedline_id = "";
                            $provider_invoicenumber = "";
                            $claimlinearr = array();
                            foreach ($this->getAllClaimLines($claim_id) as $rowz) {
                                $claimlineicd10 = $rowz["primaryICDCode"];
                                $claimline_id = $rowz["id"];
                                $treatment_code = $rowz["tariff_code"];
                                $treatment_date = $rowz["treatmentDate"];
                                $treatment_code_type = $rowz["treatment_code_type"];
                                $primaryICDDescr = $rowz["primaryICDDescr"];
                                $clmnline_charged_amnt = (double)$rowz["clmnline_charged_amnt"];
                                $msg_dscr = $rowz["msg_dscr"];
                                $reason_description = $rowz["reason_description"];
                                //$treatment_code=str_pad($treatment_code,5,"0",STR_PAD_LEFT);
                                $reason_code = $rowz["reason_code"];
                                $clmline_scheme_paid_amnt = (double)$rowz["clmline_scheme_paid_amnt"];
                                $gap = $rowz["gap"];
                                $mcaloarr = array("icd10" => $claimlineicd10, "claimline_id" => "", "treatment_code" => $treatment_code, "treatment_date" => $treatment_date, "treatment_type" => $treatment_code_type,
                                    "icd10_description" => $primaryICDDescr, "claimline_charge_amount" => $clmnline_charged_amnt, "claimline_rejection_reason" => $reason_description, "treatment_code_description" => $msg_dscr,
                                    "claimline_schemepaid_amount" => $clmline_scheme_paid_amnt, "claimline_schemerate_amount" => 0, "reason_code" => $reason_code
                                );
                                $arr_claim_line = array("kaelo_claimline" => null, "mca_claimline" => $mcaloarr, "mismatch_fields" => null);
                                array_push($claimlinearr, $arr_claim_line);
                            }

                            $inmcadoctors = array("fullname" => $practiceName, "claimedline_id" => "", "provider_number" => $practice_number, "provider_invoicenumber" => $provider_invoicenumber);
                            $inmcadoctors["claimline"] = $claimlinearr;
                            array_push($kaelodoctorarr, $inmcadoctors);



                        $claim_header_arr["claimedline"] = $kaelodoctorarr;
                        //array_push($claim_header_arr, array("claimedline" => $kaelodoctorarr));
                    }
                    else {
                        $arrmca = $this->checkAdditionalDoctors($adoctor, $claim_id);
                        foreach ($arrmca as $rowx) {
                            //getBillingDoctor($claim_number)
                            $practice_number = $rowx["practice_number"];
                            $practiceName = $rowx["name_initials"] . " " . $rowx["surname"];
                            $claimedline_id = $rowx["doc_id"];
                            $provider_invoicenumber = "";
                            $claimlinearr = array();
                            foreach ($this->findClaimLineNew($claim_id, $practice_number) as $rowz) {
                                $claimlineicd10 = $rowz["primaryICDCode"];
                                $claimline_id = $rowz["id"];
                                $treatment_code = $rowz["tariff_code"];
                                $treatment_date = $rowz["treatmentDate"];
                                $treatment_code_type = $rowz["treatment_code_type"];
                                $primaryICDDescr = $rowz["primaryICDDescr"];
                                $clmnline_charged_amnt = (double)$rowz["clmnline_charged_amnt"];
                                $msg_dscr = $rowz["msg_dscr"];
                                $reason_description = $rowz["reason_description"];
                                //$treatment_code=str_pad($treatment_code,5,"0",STR_PAD_LEFT);
                                $reason_code = $rowz["reason_code"];
                                $clmline_scheme_paid_amnt = (double)$rowz["clmline_scheme_paid_amnt"];
                                $gap = $rowz["gap"];
                                $mcaloarr = array("icd10" => $claimlineicd10, "claimline_id" => "", "treatment_code" => $treatment_code, "treatment_date" => $treatment_date, "treatment_type" => $treatment_code_type,
                                    "icd10_description" => $primaryICDDescr, "claimline_charge_amount" => $clmnline_charged_amnt, "claimline_rejection_reason" => $reason_description, "treatment_code_description" => $msg_dscr,
                                    "claimline_schemepaid_amount" => $clmline_scheme_paid_amnt, "claimline_schemerate_amount" => 0, "reason_code" => $reason_code
                                );
                                $arr_claim_line = array("kaelo_claimline" => null, "mca_claimline" => $mcaloarr, "mismatch_fields" => null);
                                array_push($claimlinearr, $arr_claim_line);
                            }

                            $inmcadoctors = array("fullname" => $practiceName, "claimedline_id" => "", "provider_number" => $practice_number, "provider_invoicenumber" => $provider_invoicenumber);
                            $inmcadoctors["claimline"] = $claimlinearr;
                            array_push($kaelodoctorarr, $inmcadoctors);
                        }


                        $claim_header_arr["claimedline"] = $kaelodoctorarr;
                        //array_push($claim_header_arr, array("claimedline" => $kaelodoctorarr));
                    }
                } else {
                    //$claim_header_arr["claimedline"]="claim not found";
                    $claim_header_arr=array("claim_number"=>$claim_number,"intervention_description"=>"No additional details found","date_sent"=>$today_date,"status"=>"Closed","scheme_savings"=>0,"discount_savings"=>0,"pay_provider"=>"No","claimedline_id"=>"");
                    //array_push($claim_header_arr, "claim not found");

                }
                $result=json_encode($claim_header_arr);
            }
            catch (Exception $e)
            {
                $claim_header_arr=array("description"=>"error : ".$e->getMessage());
                $result=json_encode($claim_header_arr,true);
            } finally {
                $rresp="Auto Response to OWLS";
                $url = 'https://medclaimassist.co.za/admin/seamless_post.php';
                $ra=$this->postCurlRequest($url, $result, true);
                $this->allaudit(1,1,1,5,$rresp,$result);
                echo $result;
                //sleep(10);
            }
        }
    }
    function postCurlRequest($url, $post_array, $check_ssl=true) {
        $cmd = "curl -L -X POST -H 'Content-Type: application/json'";
        $cmd.= " -d '" . $post_array . "' '" . $url . "'";

        if (!$check_ssl){
            $cmd.= "'  --insecure"; // this can speed things up, though it's not secure
        }
        $cmd .= " > /dev/null 2>&1 &"; // don't wait for response

        // echo $cmd;die;

        exec($cmd, $output, $exit);
        return $exit == 0;
    }
    function allaudit($total,$failed,$succeed,$status="",$desciption="",$desciption1="")
    {
        try {
            global $conn;
            $ip_address=$this->get_IP_address();
            $stnt = $conn->prepare('INSERT INTO `jarvis_files`(`status`,`total`, `succeed`,`failed`,`desciption`,`desciption1`,`ip_address`) VALUES (:status,:total,:succeed,:failed,:desciption,:desciption1,:ip_address)');
            $stnt->bindParam(':status', $status, PDO::PARAM_STR);
            $stnt->bindParam(':total', $total, PDO::PARAM_STR);
            $stnt->bindParam(':succeed', $succeed, PDO::PARAM_STR);
            $stnt->bindParam(':failed', $failed, PDO::PARAM_STR);
            $stnt->bindParam(':desciption', $desciption, PDO::PARAM_STR);
            $stnt->bindParam(':desciption1', $desciption1, PDO::PARAM_STR);
            $stnt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
            $stnt->execute();
        }
        catch (Exception $r)
        {
            $this->mess2=$r->errorMessage();
        }
    }
    function get_IP_address()
    {
        foreach (array('HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                    $IPaddress = trim($IPaddress); // Just to be safe

                    if (filter_var($IPaddress,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                        !== false) {

                        return $IPaddress;
                    }
                }
            }
        }
    }
    function checkScheme($medical_scheme)
    {
        global $conn;

        $stmt=$conn->prepare('SELECT name FROM schemes WHERE name=:name1');
        $stmt->bindParam(':name1', $medical_scheme, PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc<1)
        {
            $stmt=$conn->prepare('SELECT original_name FROM schemes_owl WHERE duplicate_name=:name1');
            $stmt->bindParam(':name1', $medical_scheme, PDO::PARAM_STR);
            $stmt->execute();
            $ccx=$stmt->rowCount();
            if($ccx>0)
            {
                $medical_scheme=$stmt->fetchColumn();
            }
            else{
                $medical_scheme="Unknown";
            }

        }
        return $medical_scheme;
    }
    function getBillingDoctor($claim_number)
    {
        global $conn;
        $claim_number="%".$claim_number."%";
        $stmt=$conn->prepare("SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(txt, '<PCNS>', -1), '</PCNS>', 1) as practice_number,SUBSTRING_INDEX(SUBSTRING_INDEX(txt, '<Name>', -1), '</Name>', 1) as doctor_name 
FROM(SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(obj, '<Prov>', -1), '</Prov>', 1) as txt FROM `jv_objects` WHERE obj LIKE :claim_number) as a");
        $stmt->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function validate($username,$password,$enviro,$othername)
    {
        global $conn1;
        $stmt=$conn1->prepare('select username,password,environment,other_name from staff_users where username=:user1 AND role_value=1');
        $stmt->bindParam(':user1', $username, PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc==1)
        {
            $vv=$stmt->fetch();
            $pass=$vv["password"];
            if(password_verify($password,$pass) && $enviro==$vv["environment"] && $othername==$vv["other_name"])
            {
                $ret=true;
            }
            else{
                $ret=false;
            }

        }
        else{
            $ret=false;
        }

        return $ret;
    }
    public function xuser($user)
    {
        global $conn;
        $user1=substr($user, 0, -1);
        $name="Naomi";
        $stmt=$conn->prepare('SELECT username FROM users_information WHERE username=:user1');
        $stmt->bindParam(':user1', $user1, PDO::PARAM_STR);
        $stmt->execute();
        $cc=$stmt->rowCount();
        if($cc>0)
        {
            $name=$stmt->fetchColumn();
        }
        return $name;
    }
    public function addObj($obj)
    {
        try {
            global $conn;
            $stmt = $conn->prepare('INSERT INTO jv_objects(obj) VALUES(:obj)');
            $stmt->bindParam(':obj', $obj, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (Exception $e)
        {

        }


    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $n=new jv_import_export();
    $n->readFile();

}
else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
    $err=array("message"=>"Bad Request","status"=>"400");
    echo json_encode($err,true);
}
?>

