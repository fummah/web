                                                    <?php
error_reporting(0);
session_start();
include ("../../mca/link3.php");
$conn=connection("mca","MCA_admin");
$conn1=connection("doc","doctors");
class my_api
{
    public $mess2;
    public $nnum;
    function export($claim_id)
    {
        global $conn;

        try {
            $st="Sent from MCA";
            $st3="KOS";
            global $conn;
            $selectDetails = $conn->prepare('SELECT recordType,senderId,claim_number as eventNumber,jv_status as eventStatus,NOW() as eventStatusDate,Service_Date as eventDateFrom,end_date as eventDateTo,
charged_amnt as claimChargedAmnt,scheme_paid as schemePaidAmnt,gap as claimCalcAmnt,memberLiability,client_gap,savings_scheme,savings_discount FROM claim where claim_id=:num');
            $selectDetails->bindParam(':num', $claim_id, PDO::PARAM_STR);
            //$selectDetails->bindParam(':kk', $st3, PDO::PARAM_STR);

            $selectDetails->execute();
            $ccc=$selectDetails->rowCount();
            if($ccc>0) {
                $this->nnum=true;
                $all = array();
                foreach ($selectDetails->fetchAll() as $r) {
                    $myArrayx = array();

                    $this->updateOwner($claim_id);
                    $selectDetails1 = $conn->prepare('SELECT jv_claim_line_number as clmnlineNumber,treatmentDate,treatmentType,clmnline_charged_amnt as clmnlineChargedAmnt,clmline_scheme_paid_amnt as clmlineSchemePaidAmnt,
 gap as clmlineCalcAmnt,memberLiability,benefit_description as benefitDescription,PMBFlag,clmn_line_pmnt_status as clmnLinePmntStatus,NOW() as clmnLinePmntStatusDate,clmnLinePmntStatusBy,msg_code as jarvismsgCode,msg_dscr as jarvisshrtMsgDscr,lng_msg_dscr as jarvislngMsgDscr FROM claim_line where mca_claim_id=:num');
                    $selectDetails1->bindParam(':num', $claim_id, PDO::PARAM_STR);
                    $selectDetails1->execute();

                    foreach ($selectDetails1->fetchAll(PDO::FETCH_ASSOC) as $r1) {
                        array_push($myArrayx, $r1);

                    }
                    $claim_number=$r[2];
                    $dd=explode("_",$claim_number);
                    $cn=count($dd);
                    if($cn>1)
                    {
                        $claim_number=$dd[1];
                    }
                    $gap_amnt=$r["client_gap"];
                    $scheme_savings=$r["savings_scheme"];
                    $discount_savings=$r["savings_discount"];
                    $approvedAmnt=$gap_amnt-$scheme_savings-$discount_savings;
                    $testArray = array("recordType" => $r[0], "senderId" => "MCA", "eventNumber" => $claim_number, "eventStatus" => $r[3], "eventStatusDate" => $r[4], "eventDateFrom" => $r[5], "eventDateTo" => $r[6], "claimChargedAmnt" => $r[7], "schemePaidAmnt" => $r[8],
                        "claimCalcAmnt" => $r[9], "memberLiability" => $r[10],"approvedAmount"=>$approvedAmnt, "claimLine" => $myArrayx);

                    array_push($all, $testArray);
                }
                $f = json_encode($all);
                $dataList = substr($f, 1, -1);
                $this->mess2= $dataList;
                //echo "ygr";

            }
            else
            {
                $this->mess2= "Record not found";
                $this->nnum=false;
            }

        }
        catch (Exception $r)
        {
            $this->mess2= "There is an error";
            $this->nnum=false;
        }

        return $this->mess2;

    }

    public function updateClaim($claim_id)
    {
        global $conn;
        try {
            $dat = date('Y-m-d H:i:s');
            $stmt = $conn->prepare('UPDATE claim_line SET clmn_line_status_date=:dat WHERE mca_claim_id=:claim');
            $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $rr=$stmt->execute();

        }
        catch (Exception $ff)
        {

        }
    }
    private function updateOwner($claim_id)
    {
        $username=$_SESSION['user_id'];
        global $conn;
        try {
            $dat = date('Y-m-d H:i:s');
            $stmt = $conn->prepare('UPDATE claim_line SET clmnLinePmntStatusBy=:owner WHERE mca_claim_id=:claim');
            $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
            $rr=$stmt->execute();

        }
        catch (Exception $ff)
        {

        }

    }
    public function addObj($obj,$status)
    {
        try {
            global $conn;
            $stmt = $conn->prepare('INSERT INTO jv_objects(obj,status) VALUES(:obj,:status)');
            $stmt->bindParam(':obj', $obj, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (Exception $e)
        {

        }


    }
}

$myapi=new my_api();
//$claim_id=(int)$_GET["claim_id"];
$claim_id=18421;
echo "Fuma";
$data=$myapi->export($claim_id);
if($myapi->mess2=="Record not found")
{
    echo $myapi->mess2;
}
elseif ($myapi->mess2=="There is an error")
{
    echo "<span style='color: red'>(Error in sending to KO please report to systems admin)</span>";
}
else {
    $data_string = $data;
    $ch = curl_init('https://www.mcajarvis.co.za/jarvisapi/mca');
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
        echo "<span style='color: red'>(cURL Error #:)</span>";
    } else {

        //echo $result;
        $rr= json_decode($result, true);
        $status=$rr["status"];
        $stInfo=$rr["statusInfo"];
        if($status==200)
        {
            $myapi->updateClaim($claim_id);
            echo "<span style='color: green'>(Object sent to KO)</span>";

        }
        else
        {

            echo "<span style='color: red'>(Object failed to sent to KO. KO Error=: $stInfo)</span>";
        }
        $myapi->addObj($data_string,$status);
    }
}
?>                                                