<?php

namespace mcaSessionless;
include ("../../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$conn2 = connection("doc","doctors");
class sessionLessClass
{
    function getDetails($username,$start_date,$end_date)
    {
        global $conn;
        $arr=array();
        $dat=!empty($start_date)?" a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
        $dat2=!empty($start_date)?" date_entered >='".$start_date."' AND date_entered<'".$end_date."' ":" AND 1";
        try {
            $sql = "SELECT DATE_FORMAT(a.date_closed, \"%Y-%m\") as mydate,SUM(savings_discount + savings_scheme) as total, COUNT(claim_id) as total_claim,SUM(charged_amnt - scheme_paid) as claim_value 
FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id 
WHERE $dat AND username=:username AND Open=0 GROUP BY DATE_FORMAT(a.date_closed, \"%Y-%m\")";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $month = $row["mydate"];
                $savings = $row["total"];
                $claim_value=$this->claimValue2($dat2,$username);
                $perc=$claim_value>0?round(($savings/$claim_value)*100):0;
                $claim_value = round($row["claim_value"]);
                $closed = $row["total_claim"];
                $qa=$this->getQA($username,$month);
$mar=array("month"=>$month,"savings_perc"=>$perc,"savings"=>round($savings),"closed"=>$closed,"claim_value"=>$claim_value,"qa"=>$qa);
array_push($arr,$mar);
            }
            return $arr;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }


    }
    function getQA($username,$month)
    {
        global $conn;
        $month="%".$month."%";
        $sql="select (SUM(Case When y.position = 'Passed' Then y.assessment_score End)/COUNT(DISTINCT a.claim_number)) qa_perc
    from (((claim a join web_clients.member b on(a.member_id = b.member_id)) 
    join clients c on(b.client_id = c.client_id)) join 
    (select * from (select *from quality_assurance order by id desc) x group by x.claim_id) y 
    on(a.claim_id = y.claim_id)) where a.quality = 2 and y.qa_signed = 1 and y.cs_signed = 1 and y.date_entered like :month and a.username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->bindParam(':month', $month, \PDO::PARAM_STR);
        $stmt->execute();
        return round($stmt->fetchColumn());
      }
    function aiQA($username,$start_date,$end_date)
    {

        $dat=!empty($start_date)?" date_entered >='".$start_date."' AND date_entered<'".$end_date."' ":" 1";
        try {

                $sql="SELECT data1,data2,data3,data4,data5,sla17,sla19,sla16,sla18,sla20,sla21,sla22,calls1,calls2,calls3,calls4,calls5,calls6,calls7,calls8,calls9,calls10,sla1,sla2,sla3,sla4,sla5,sla6,sla7,sla8,sla9,sla10,sla11,sla12,sla13,sla14,emails1,emails2,emails3,emails4,emails5,emails6,emails7,emails8,emails9
FROM quality_assurance as a inner JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE a.id IN (SELECT MAX(id) AS id FROM quality_assurance WHERE ".$dat." GROUP BY claim_id DESC) AND b.username=:username11";

                $arrz=$this->calcFA($username,$sql);
                return $arrz;
        }
        catch (Exception $e)
        {
            return "Error ->".$e->getMessage();
        }
    }
    function calcFA($username,$sql)
    {
        global $conn;

            $usercv=array();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username11', $username, \PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row)
            {
                $result = array_keys( $row, 0 );
                $usercv = array_merge($usercv, $result);
            }
            $somearry=array_count_values($usercv);
            $arrkeys=array_keys($somearry);

            $realarr1=array();
            for($x=0;$x<count($arrkeys);$x++)
            {
                $mykey=$arrkeys[$x];
                $description=$this->getQADescr($mykey);
                $valuer=(int)$somearry[$mykey];
                $data=array("keyvalue"=>$mykey,"total"=>$valuer,"descr"=>$description);
                array_push($realarr1,$data);
            }
            $tot= array_column($realarr1, 'total');
            array_multisort($tot, SORT_DESC, $realarr1);
            $realarr=array("username"=>$username,"data"=>$realarr1);

        return $realarr;
    }
    function claimValue2($date,$val="1")
    {
        try {
            global $conn;
            $sql="SELECT SUM(charged_amnt-scheme_paid) FROM `claim` WHERE $date AND Open<>2 AND username=:username";
            //echo $sql;
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $val, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getQADescr($qa)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT description FROM qa_descriptions WHERE qa_value=:qa_value");
        $stmt->bindParam(':qa_value', $qa, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
        //qa_descriptions
    }
    function getFullname($username)
    {
        global $conn2;
        $stmt = $conn2->prepare("SELECT fullName FROM `staff_users` WHERE username = :username ");
        $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function format($val)
    {
        $val=number_format($val,2,',',' ');
        return $val;
    }
}