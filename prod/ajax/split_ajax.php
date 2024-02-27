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
    try {
        $claim_id = (int)$_POST['claim_id'];
        $row=$control->viewSplitSingle($claim_id);
        $loyalty_number=$row["loyalty_number"];
        $membership_number=$row["membership_number"];
        $beneficiary_name=$row["beneficiary_name"];
        $date_entered=$row["date_entered"];
        $statusx=$row["status"];
        $date_closed=$row["date_closed"];
        $closed_by=$row["closed_by"];
        $arr_hos=array();
        foreach ($control->viewHospitalNames($claim_id) as $rx)
        {
            $hospital_name=$rx["hospital_name"];
            $status=$rx["status"];
    $arrline=[];
            $arrline_data=$control->viewSplitClaimlinesDoctor($claim_id,$hospital_name);
            foreach($arrline_data as $line)
            {
               $errors=explode(",",$line['medicalschemerejectioncode']);
               $error_list="";
               $coo=0;
               foreach ($errors as $error)
               {
                $myerror=(int)trim($error);
                if($myerror>0)
                {
                if($coo>2)
                {
                    break;
                }                
                $myerror_description=$control->viewErrorDetails($myerror)["CLIENT_MESSAGE"];
                
                    $error_list.="<br><br>$myerror ---- ".$myerror_description;
                }  
                $coo++;              
               }

                $iinerliner=array("id"=>$line['id'],"procedurecode"=>$line['procedurecode'],"amountcharged"=>$line['amountcharged'],"medicalschemerateinput"=>$line['medicalschemerateinput'],"medicalschemepaidinput"=>$line['medicalschemepaidinput'],"duplicate_claim"=>$line['duplicate_claim'],"copayment"=>$line['copayment'],"file_name"=>$line['file_name'],"servicedate"=>$line['servicedate'],"icdcode"=>$line['icdcode'],"errors"=>$error_list);
                array_push($arrline,$iinerliner);
            }
            $inarr=array("hospital_name"=>$hospital_name,"status"=>$status,"claim_lines"=>$arrline);
            array_push($arr_hos,$inarr);
        }


        $arr=array("loyalty_number"=>$loyalty_number,"claim_id"=>$claim_id,"membership_number"=>$membership_number,"beneficiary_name"=>$beneficiary_name,
            "date_entered"=>$date_entered,"date_closed"=>$date_closed,"closed_by"=>$closed_by,"status"=>$statusx,"hospital_lines"=>$arr_hos);

        echo json_encode($arr,true);

    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
elseif ($identity == 2)
{
    $claim_id=(int)$_POST["claim_id"];
    $claim_number=$_POST["claim_number"];
    $note=$_POST["note"];
    $status="completed";
    $date_closed=date("Y-m-d H:i:s");
    if($control->callCloseSplit($claim_id,$status,$date_closed,$note,$claim_number))
    {
        echo "The claim successfully closed";
    }
    else
    {
        echo "Failed to close the claim";
    }
}
elseif ($identity == 3)
{
    $startdate=$_POST["startdate"];
    $enddate=$_POST["enddate"];
    $val=$_POST["val"];

    echo json_encode($control->viewSplitTotals($startdate,$enddate,$val),true);
}
elseif ($identity == 4)
{
    try {
        $claim_id = (int)$_POST['claim_id'];
        $row=$control->viewSwitchSingle($claim_id);
        $policy_number=$row["policy_number"];
        $membership_number=$row["scheme_number"];
        $first_name=$row["first_name"];
        $surname=$row["surname"];
        $date_entered=$row["date_entered"];
        $beneficiary_name=$first_name." ".$surname;
        $arr_hos=array();
        foreach ($control->viewSwitchDoctors($claim_id) as $rx)
        {
            $practice_number=$rx["practice_number"];
            $name_initials=$rx["name_initials"];
            $surname=$rx["surname"];
            $provider_invoicenumber=$rx["provider_invoicenumber"];
            $doc_name=$name_initials." ".$surname;
            $arrline_data=$control->viewSwitcClaims($claim_id,$practice_number);
            $inarr=array("practice_number"=>$practice_number,"provider_invoicenumber"=>$provider_invoicenumber,"practice_name"=>$doc_name,"claim_lines"=>$arrline_data);
            array_push($arr_hos,$inarr);
        }


        $arr=array("policy_number"=>$policy_number,"claim_id"=>$claim_id,"membership_number"=>$membership_number,"beneficiary_name"=>$beneficiary_name,
            "date_entered"=>$date_entered,"hospital_lines"=>$arr_hos);

        echo json_encode($arr,true);

    }
    catch (Exception $e)
    {
        echo "There is an error : ".$e->getMessage();
    }
}
elseif ($identity == 5)
{
    $claim_id=(int)$_POST["claim_id"];
    $claim_number=$_POST["claim_number"];
    $note=$_POST["note"];

    $date_closed=date("Y-m-d H:i:s");
    if($control->callCloseSwitchClaim($claim_id,$date_closed,$claim_number))
    {
        echo "The claim successfully closed";
    }
    else
    {
        echo "Failed to close the claim";
    }
}
?>