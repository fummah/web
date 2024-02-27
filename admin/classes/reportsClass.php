<?php
$_SESSION['start_db']=true;
require_once "../dbconn1.php";
$conn=connection("mca","MCA_admin");
$conn1=connection("doc","doctors");
$conn3=connection("seamless","seamless");
class reportsClass
{
    public $sql1="SELECT b.claim_id,b.claim_number,intervention_id FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";
    public $sql2="SELECT DISTINCT a.claim_id,b.claim_number FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";
    public $sql3="SELECT claim_id,claim_number FROM `claim` WHERE `recent_date_time` >= :dat AND username=:username AND Open<>2";
    public $sql4="SELECT DISTINCT claim_id,claim_number FROM `logs` WHERE `date` >= :dat AND owner=:username";

    function allDoctors()
    {
        try {
            global $conn;
            $stmt = $conn->prepare("SELECT COUNT(*) FROM doctor_details");
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function newDoctors()
    {
        try {
            global $conn;
            $se="%".date("Y-m")."%";
            $stmt = $conn->prepare("SELECT COUNT(*) FROM doctor_details WHERE date_entered like :dd");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function withdiscountDoctors()
    {
        try {
            global $conn;
            $se="Yes";
            $stmt = $conn->prepare("SELECT COUNT(*) FROM doctor_details WHERE gives_discount = :dd");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function withoutdiscountDoctors()
    {
        try {
            global $conn;

            $se="No";
            $stmt = $conn->prepare("SELECT COUNT(*) FROM doctor_details WHERE gives_discount = :dd");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    //
    function topclaimsDoctors()
    {
        try {
            global $conn;

            $se="No";
            $stmt = $conn->prepare("SELECT CONCAT(b.name_initials,\" \",b.surname),a.practice_number,a.total FROM(SELECT practice_number,count(practice_number) as 
total FROM `doctors` GROUP BY practice_number ORDER BY total DESC LIMIT 5) as a LEFT JOIN doctor_details as b ON a.practice_number=b.practice_number");
            //$stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function chargedamtDoc()
    {
        try {
            global $conn;

            $se="%".date("Y-m")."%";
            $stmt = $conn->prepare("SELECT CONCAT(b.name_initials,\" \",b.surname),a.practice_number,a.tott 
FROM(SELECT practice_number,SUM(clmnline_charged_amnt) as tott FROM `claim_line` WHERE date_entered like :dd GROUP BY practice_number 
ORDER BY tott DESC LIMIT 4) as a LEFT JOIN doctor_details as b ON a.practice_number=b.practice_number");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function totdamtDoc()
    {
        try {
            global $conn;
            $se="%".date("Y-m")."%";
            $stmt = $conn->prepare("SELECT CONCAT(b.name_initials,\" \",b.surname),a.practice_number,a.totalc 
FROM(SELECT practice_number,SUM(clmline_scheme_paid_amnt) as totalc FROM `claim_line` WHERE date_entered like :dd GROUP BY practice_number 
ORDER BY totalc DESC LIMIT 4) as a LEFT JOIN doctor_details as b ON a.practice_number=b.practice_number");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function previousDoc($practice_number,$date)
    {
        try {
            global $conn;
            $se="%".$date."%";

            $stmt = $conn->prepare("SELECT SUM(clmline_scheme_paid_amnt) as totalc,SUM(clmnline_charged_amnt) as tott FROM `claim_line` WHERE date_entered like :dd AND practice_number = :prac");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(":prac",$practice_number,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return (int)$e->getMessage();
        }
    }

    function myClients($id)
    {
        try {
            global $conn;
            $se="%".date("Y-m")."%";
            $stmt = $conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as sav FROM `claim` as a inner join member as b on a.member_id=b.member_id 
 WHERE Open=0 AND a.date_closed LIKE :dd AND b.client_id=:id ORDER BY sav DESC");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(":id",$id,PDO::PARAM_STR);
            $stmt->execute();
            $total=$stmt->rowCount()>0?$stmt->fetchColumn():0;
            return $total;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }


    function client_savings()
    {
        try {
            global $conn;
            $arr=array();
            $stmt = $conn->prepare("SELECT DISTINCT client_name,reporting_client_id,target FROM `clients` WHERE reporting_status=1 OR client_id=4 ORDER BY client_name ASC");
            $stmt->execute();

            foreach ($stmt->fetchAll() as $row)
            {
                $client_name=$row[0];
                $id=$row[1];
                $target=$row[2];
                $amount=$this->myClients($id);
                $local_array=array("client_name"=>$client_name,"savings"=>$amount,"client_id"=>$id,"target"=>$target);
                array_push($arr,$local_array);

            }
            array_multisort(array_map(function($element) {
                return $element['savings'];
            }, $arr), SORT_DESC, $arr);
            return $arr;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getClientsWithSavings($date)
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as savings,b.client_id,c.client_name,c.base_fee,c.threshold,c.threshold1 FROM `claim` as a INNER JOIN member as b on a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id
 WHERE Open=0 AND a.date_closed LIKE :dd GROUP BY c.client_name ORDER BY savings DESC");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function savingsChange($id,$date)
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as sav FROM `claim` as a inner join member as b on a.member_id=b.member_id 
 WHERE Open=0 AND a.date_closed LIKE :dd AND b.client_id=:id");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(":id",$id,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function savingsPerClient($id,$date)
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as sav FROM `claim` as a inner join member as b on a.member_id=b.member_id 
 WHERE Open=0 AND a.date_closed LIKE :dd AND b.client_id=:id");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(":id",$id,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function savings($date)
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as sav,a.charged_amnt,a.scheme_paid FROM `claim` as a  WHERE Open=0 AND a.date_closed LIKE :dd");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getReopenedPerClient($client,$date)
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(last_scheme_savings+last_discount_savings) 
FROM `reopened_claims` as k INNER JOIN claim as a ON k.claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id 
    INNER JOIN clients as c ON b.client_id=c.client_id WHERE k.reopened_date like :dd AND a.Open=0 AND k.date_closed not like :dd AND k.date_closed<:dat AND c.client_name=:client_name");
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(":dat",$date,PDO::PARAM_STR);
            $stmt->bindParam(":client_name",$client,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getReopenedClaimsPerClient($client,$date,$username="")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $typ="c.client_name";
            if(!empty($username))
            {
                $typ="a.username";
                $client=$username;
            }
            $stmt = $conn->prepare("SELECT k.claim_id, MAX(k.id) AS id,a.claim_number,a.username,a.date_entered,k.date_closed,last_scheme_savings+last_discount_savings AS first_savings,k.reopened_date,a.date_closed as final_date_closed,a.savings_scheme+a.savings_discount as final_savings,c.client_name
FROM`reopened_claims` as k INNER JOIN claim as a ON k.claim_id=a.claim_id INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE k.reopened_date like :dd AND a.Open=0 AND k.date_closed not like :dd AND k.date_closed<:dat AND $typ=:client_name
GROUP BY k.claim_id");

            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(":dat",$date,PDO::PARAM_STR);
            $stmt->bindParam(":client_name",$client,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function reopenedCases($client,$date,$username="")
    {
        $arr=$this->getReopenedClaimsPerClient($client,$date,$username);
        $total1=0.00;
        $total2=0.00;
        foreach ($arr as $row)
        {
            $firstsavings=(double)$row["first_savings"];
            $lastsavings=(double)$row["final_savings"];
            $total1+=$firstsavings;
            $total2+=$lastsavings;
        }
        $vari=$total2-$total1;
        $data["total1"]=$total1;
        $data["vari"]=$vari;
        return $data;
    }
    function openClaims($condition=":username",$val="1")
    {
        try {
            global $conn;
            $stmt = $conn->prepare('SELECT count(*) FROM `claim` as a  WHERE Open=1 AND '.$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function openthisClaims($date,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT count(*) FROM `claim` as a WHERE date_entered like :dd AND Open=1 AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function closedthisClaims($date,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT count(*) FROM `claim` as a WHERE date_closed like :dd AND Open=0 AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function newClaims($condition=":username",$val="1")
    {
        try {
            global $conn;
            $stmt = $conn->prepare("SELECT count(*) FROM `claim` WHERE Open=1 AND new=0 AND ".$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getSingleReopened($claim_id)
    {
        global $conn;
        $fbStmt = $conn->prepare("SELECT reopened_date FROM `reopened_claims` WHERE claim_id=:claim_id ORDER BY id DESC LIMIT 1");
        $fbStmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $fbStmt->execute();
        return $fbStmt->fetchColumn();
    }
    function closedDate($condition=":username",$val="1",$dat="")
    {
        global $conn;
        $from = date('Y-m').'%';
        if(!empty($dat))
        {
            $from = "%".$dat."%";
        }
        $fbStmt = $conn->prepare("SELECT date_entered,date_closed,claim_id FROM claim WHERE date_closed LIKE :dat AND Open=0 AND ".$condition);
        $fbStmt->bindParam(':dat', $from, PDO::PARAM_STR);
        $fbStmt->bindParam(':username', $val, PDO::PARAM_STR);
        $fbStmt->execute();
        $nu1 = $fbStmt->rowCount();
        $totDays=0;
        if ($nu1 > 0) {
            foreach ($fbStmt->fetchAll() as $row1) {
                $d1 = $row1[0];
                $d2 = $row1[1];
                $claim_id=$row1[2];
                $ddat=$this->getSingleReopened($claim_id);
                $datetime1 = $ddat?strtotime($ddat):strtotime($d1);
                $datetime2 = strtotime($d2);
                $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                $days = $secs / 86400;
                $totDays += $days;
            }
        }
        return $totDays;
    }
    function closedThisMonth($condition=":username",$val="1")
    {

        global $conn;
        $from = date('Y-m').'%';
        $stmt = $conn->prepare('SELECT COUNT(Open) as a FROM claim WHERE date_closed LIKE :dat AND Open=0 AND '.$condition);
        $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
        $stmt->bindParam(':username', $val, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function usersCase($condition=":username",$val="1")
    {
        try {

            global $conn;
            $stmt = $conn->prepare('SELECT username,count(username) as total FROM claim WHERE Open=1 AND '.$condition.' GROUP BY username');
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function clientsCase($condition=":username",$val="1")
    {
        try {

            global $conn;
            $stmt = $conn->prepare('SELECT c.client_name,count(c.client_name) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id 
INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.Open=1 AND '.$condition.' GROUP BY c.client_name');
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function trend_claims($condition=":username",$val="1")
    {
        global $conn;

        try {

            $first_day=date('Y-m-01');
            $stmt =  $conn->prepare('select DATE_FORMAT(date_entered, \'%Y-%m\') AS claim_date, COUNT(date_entered) as total FROM claim WHERE Open<>2 AND '.$condition.' GROUP BY  DATE_FORMAT(date_entered, \'%Y - %m\')
ORDER BY claim_date DESC LIMIT 7');
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo("There is an error.".$e);
        }
    }

    function clients()
    {
        global $conn;

        try {

            $stmt =  $conn->prepare('SELECT c.client_name FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id 
INNER JOIN clients as c ON b.client_id=c.client_id WHERE Open<>2 GROUP BY c.client_name');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function users()
    {
        global $conn;

        try {

            $stmt =  $conn->prepare('SELECT username FROM claim WHERE Open<>2 GROUP BY username');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function clients_trend_claims($condition=":username",$val="1")
    {
        global $conn;
        $array=array();
        try {
            foreach ($this->trend_claims($condition,$val) as $row)
            {
                $month=$row[0];
                $first_day="%".$month."%";
                $stmt =  $conn->prepare('SELECT c.client_name,count(c.client_name) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id 
INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_entered like :first_day AND Open<>2 AND '.$condition.' GROUP BY c.client_name');
                $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
                $stmt->bindParam(':username', $val, PDO::PARAM_STR);
                $stmt->execute();
                $aa=array("claim_date"=>$month,"cont"=> $stmt->fetchAll());
                array_push($array,$aa);
            }
            return $array;

        } catch (Exception $e) {
            echo("There is an error.");
        }
    }

    function users_trend_claims($condition=":username",$val="1")
    {
        global $conn;
        $array=array();
        try {
            foreach ($this->trend_claims() as $row)
            {
                $month=$row[0];
                $first_day="%".$month."%";
                $stmt =  $conn->prepare('SELECT username as client_name,count(username) as total FROM claim WHERE date_entered like :first_day AND Open<>2 AND '.$condition.' GROUP BY username');
                $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
                $stmt->bindParam(':username', $val, PDO::PARAM_STR);
                $stmt->execute();
                $fd=$stmt->fetchAll();
                array_push($fd,array(0=>"System",1=>"0","client_name"=>"System","total"=>"0"));
                $aa=array("claim_date"=>$month,"cont"=> $fd);
                array_push($array,$aa);
            }
            return $array;

        } catch (Exception $e) {
            echo("There is an error.".$e);
        }
    }
    function selectAllusers($condition=":username",$val="1")
    {
        try {
            global $conn;
            $stmt = $conn->prepare('SELECT DISTINCT a.username FROM claim as a INNER JOIN users_information as b ON a.username=b.username WHERE (b.status=1 OR b.email="wanda@medclaimassist.co.za") AND Open<>2 AND '.$condition);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function selectuser($username,$dat,$sql)
    {
        global $conn;
        try {

            $stmt =  $conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();

        } catch (Exception $e) {
            return "There is an error.";
        }
    }

    function currentSavings($dat,$client="",$condition=":username",$val1="1")
    {
        global $conn;
        $val=!empty($client)?"AND c.client_name='".$client."'":"AND 1";
        $val = $val." AND ".$condition;
        try {
            $stmt =  $conn->prepare('SELECT SUM(savings_scheme+savings_discount) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE date_closed like :dat AND Open=0 '.$val);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();

        } catch (Exception $e) {
            return "There is an error.";
        }
    }

    function currentChargedAmnt($dat,$client="",$condition=":username",$val1="1")
    {
        $val=!empty($client)?"AND c.client_name='".$client."'":"AND 1";
        $val=$val." AND ".$condition;
        global $conn;
        try {
            $stmt =  $conn->prepare('SELECT SUM(charged_amnt) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_entered like :dat AND a.Open<>2 '.$val);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return "There is an error.";
        }
    }
    function currentSchemeAmnt($dat,$client="",$condition=":username",$val1="1")
    {
        global $conn;
        $val=!empty($client)?"AND c.client_name='".$client."'":"AND 1";
        $val=$val." AND ".$condition;
        try {
            $stmt =  $conn->prepare('SELECT SUM(scheme_paid) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_entered like :dat AND Open<>2 '.$val);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return "There is an error.";
        }
    }
    function updatedDoctors()
    {
        global $conn;
        try {
            $stmt =  $conn->prepare('SELECT COUNT(*) as total FROM doctor_details WHERE gives_discount="Yes" OR gives_discount="NO"');
            $stmt->execute();
            return $stmt->fetchColumn();

        } catch (Exception $e) {
            return "There is an error.";
        }
    }
    function getrealDoctors($period=0)
    {
        global $conn;
        try {
            $arrMonth=array();
            $arrDiscount=array();
            $arrNoDiscount=array();

            for($j=0;$j<2;$j++) {
                $val="Yes";
                if($j==1)
                {
                    $val="No";
                }

                $dd=date("Y-m-d");
                for ($i = 0; $i < $period; $i++) {
                    $d = new DateTime( $dd );
                    if($i==0)
                    {
                        $month= date( 'Y-m' );
                    }
                    else{
                        $d->modify( '-1 month' );
                        $month= $d->format( 'Y-m' );

                    }
                    $dd=$d->format("Y-m-d");
                    //$month = date("Y-m", strtotime("-$i months"));
                    $dat = "%" . $month . "%";
                    $stmt = $conn->prepare('SELECT COUNT(*) as total FROM doctor_details WHERE gives_discount=:val AND date_entered like :dat');
                    $stmt->bindParam(':val', $val, PDO::PARAM_STR);
                    $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
                    $stmt->execute();
                    if($j==1)
                    {
                        array_push($arrNoDiscount, $stmt->fetchColumn());
                    }
                    else{
                        array_push($arrDiscount, $stmt->fetchColumn());
                        array_push($arrMonth, $month);
                    }

                }
            }
            return array("months"=>$arrMonth,"discount"=>$arrDiscount,"non"=>$arrNoDiscount);

        } catch (Exception $e) {
            return "There is an error.";
        }
    }
    function savingsTrend($period=1,$condition=":username",$val1="1")
    {
        global $conn;
        $period--;
        try {
            $arrMonth=array();
            $schemeSavings=array();
            $discountSavings=array();

            for($j=0;$j<2;$j++) {
                $val="SELECT SUM(savings_scheme) FROM claim WHERE Open=0 AND date_closed like :dat AND ".$condition;
                if($j==1)
                {
                    $val="SELECT SUM(savings_discount) FROM claim WHERE Open=0 AND date_closed like :dat AND ".$condition;
                }
                $zarr=array_reverse($this->day_rr($period,0));


                for($i=0;$i<count($zarr);$i++)
                {
                    $month=$zarr[$i];
                    $dat = "%" . $month . "%";
                    $dat = "%" . $month . "%";
                    $stmt = $conn->prepare($val);
                    $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
                    $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
                    $stmt->execute();
                    if($j==1)
                    {
                        array_push($discountSavings, $stmt->fetchColumn());
                    }
                    else{
                        array_push($schemeSavings, $stmt->fetchColumn());
                        array_push($arrMonth, $month);
                    }

                }
            }
            return array("months"=>$arrMonth,"scheme"=>$schemeSavings,"discount"=>$discountSavings);

        } catch (Exception $e) {
            return "There is an error.";
        }
    }
    function savingspercentageTrend($period=1,$condition=":username",$val1="1")
    {
        global $conn;
        $period--;
        try {
            $arrMonth=array();
            $schemeSavings=array();
            $discountSavings=array();

            for($j=0;$j<2;$j++) {
                $val="SELECT SUM(savings_scheme) FROM claim WHERE Open=0 AND date_closed like :dat AND ".$condition;
                if($j==1)
                {
                    $val="SELECT SUM(savings_discount) FROM claim WHERE date_closed AND Open<>2 like :dat AND ".$condition;
                }
                $zarr=array_reverse($this->day_rr($period,0));


                for($i=0;$i<count($zarr);$i++)
                {
                    $month=$zarr[$i];
                    $dat = "%" . $month . "%";
                    $stmt = $conn->prepare($val);
                    $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
                    $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
                    $stmt->execute();
                    $sav=(double)$stmt->fetchColumn();
                    $claim_value=(double)$this->currentChargedAmnt($dat,"",$condition,$val1)-(double)$this->currentSchemeAmnt($dat,"",$condition,$val1);
                    $savtot=$claim_value>0?round(($sav/$claim_value)*100):0;
                    //$savtot=22;
                    if(is_nan($savtot))
                    {
                        $savtot=0;
                    }

                    if($j==1)
                    {
                        array_push($discountSavings,$savtot);
                    }
                    else{
                        array_push($schemeSavings, $savtot);
                        array_push($arrMonth, $month);
                    }

                }
            }
            return array("months"=>$arrMonth,"scheme"=>$schemeSavings,"discount"=>$discountSavings);

        } catch (Exception $e) {
            return "There is an error.";
        }
    }
    function trend_savings($condition=":username",$val1="1")
    {
        global $conn;
        $array=array();
        try {
            foreach ($this->trend_claims($condition,$val1) as $row)
            {
                $month=$row[0];
                $first_day="%".$month."%";
                $stmt =  $conn->prepare('SELECT c.client_name,SUM(a.savings_scheme+a.savings_discount) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id 
INNER JOIN clients as c ON b.client_id=c.client_id WHERE Open=0 AND a.date_closed like :first_day AND '.$condition.' GROUP BY c.client_name');
                $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
                $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
                $stmt->execute();
                $aa=array("claim_date"=>$month,"cont"=> $stmt->fetchAll());
                array_push($array,$aa);
            }
            return $array;

        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function trend_users($condition=":username",$val1="1")
    {
        global $conn;
        $array=array();

        try {
            foreach ($this->trend_claims($condition,$val1) as $row)
            {
                $month=$row[0];
                $first_day="%".$month."%";
                $stmt =  $conn->prepare('SELECT username as client_name,SUM(savings_scheme+savings_discount) as total FROM claim WHERE Open=0 AND date_closed like :first_day AND '.$condition.' GROUP BY username');
                $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
                $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
                $stmt->execute();
                $fd=$stmt->fetchAll();
                array_push($fd,array(0=>"System",1=>"0.00","client_name"=>"System","total"=>"0.00"));
                $aa=array("claim_date"=>$month,"cont"=> $fd);
                array_push($array,$aa);
            }
            return $array;

        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function analysisCase($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1")
    {
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        $op="Open=1";
        $dat=!empty($start_date)?" AND a.date_entered >='".$start_date."' AND a.date_entered<'".$end_date."' ":" AND 1";
        if($typ=="closed")
        {
            $dat=!empty($start_date)?" AND a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
            $op="Open=0";
        }
        elseif ($typ=="all")
        {
            $op="Open<>2";
        }
        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" AND a.username IN ('".$users_em."')":" AND 1";
        $vol1=!empty($clients)?" AND c.client_name IN ('".$clients_em."')":" AND 1";
        $all=$op.$vol.$vol1.$dat." AND ".$condition;

        $sql="SELECT c.client_name,COUNT(c.client_name) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id WHERE ".$all." GROUP BY c.client_name";
        if($val=="users")
        {
            $sql="SELECT a.username as client_name,COUNT(a.username) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id WHERE ".$all." GROUP BY a.username";
        }
        try {

            global $conn;
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

            //return $sql;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function analysisSavings($val="",$clients="",$users="",$start_date="",$end_date="",$condition=":username",$val1="1")
    {
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        $dat=!empty($start_date)?" AND a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" AND a.username IN ('".$users_em."')":" AND 1";
        $vol1=!empty($clients)?" AND c.client_name IN ('".$clients_em."')":" AND 1";
        $all=$vol.$vol1.$dat." AND ".$condition;
        $sql="SELECT c.client_name,SUM(a.savings_scheme + a.savings_discount) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id WHERE Open=0 AND 1 ".$all." GROUP BY c.client_name";
        if($val=="users")
        {
            $sql="SELECT a.username as client_name,SUM(a.savings_scheme + a.savings_discount) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id WHERE Open=0 AND 1 ".$all." GROUP BY a.username";
        }
        try {

            global $conn;
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

            //return $sql;
        }
        catch (Exception $e)
        {
            return $e->getMessage().$sql;
        }
    }
    function reports($period=12,$client="Admed",$current=1)
    {
        global $conn;
        $period--;

        try {
            $arrMonth=array();
            $tot_claims=0;$totdis=0;$totscheme=0;$tottot=0;$totcharged=0;$totperc=0;$totaver=0;$totreffered=0;

            $zarr=array_reverse($this->day_rr($period,$current));
            for($i=0;$i<count($zarr);$i++)
            {
                $month=$zarr[$i];
                $dat = "%" . $month . "%";

                $sql="SELECT SUM(savings_discount) as discount, SUM(savings_scheme) as scheme,SUM(savings_discount + savings_scheme) as total,
COUNT(claim_id) as total_claim,SUM(charged_amnt-scheme_paid) as charged FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_closed 
LIKE :dat AND client_name=:name AND Open=0";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
                $stmt->bindParam(':name', $client, PDO::PARAM_STR);
                $stmt->execute();
                $row=$stmt->fetch();
                $discount=$row[0];
                $scheme=$row[1];
                $total_savings=$row[2];
                $claims=$row[3];
                $charged=$row[4];
                $perc=$charged>0?($total_savings/$charged)*100:0;


                $totdis+=$discount;$totscheme+=$scheme;$tottot+=$total_savings;
                $s=$conn->prepare("SELECT COUNT(a.claim_id) as total_claims FROM claim as a INNER JOIN member as b 
ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_entered LIKE :dat AND client_name=:name AND a.Open<>2");
                $s->bindParam(':dat', $dat, PDO::PARAM_STR);
                $s->bindParam(':name', $client, PDO::PARAM_STR);
                $s->execute();
                $referredclaims=(int)$s->fetchColumn();
                $totreffered+=$referredclaims;

                $sql1="SELECT SUM(TIMESTAMPDIFF(SECOND,a.date_entered,a.date_closed))/(COUNT(claim_id) * 86400) as total_time FROM claim as a INNER JOIN member as b 
ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id
    WHERE a.date_closed LIKE :dat AND client_name=:name AND a.Open=0 AND a.claim_id not in (SELECT claim_id FROM reopened_claims)";
                $stmt = $conn->prepare($sql1);
                $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
                $stmt->bindParam(':name', $client, PDO::PARAM_STR);
                $stmt->execute();
                $days=round($stmt->fetchColumn());

                $average=$claims>0?$days:0;
                $average=is_nan($average)?0:$average;
                $tot_claims+=$claims;$totcharged+=$charged;$totperc+=$perc;$totaver+=$average;
                $localarr=array("month"=>$month,"claims"=>$claims,"discount"=>$discount,"scheme"=>$scheme,"total_savings"=>$total_savings,"charged"=>$charged,"percentage"=>round($perc,1),"average"=>round($average),"total_referred"=>$referredclaims);
                array_push($arrMonth,$localarr);
            }
            //$totperc+=$perc;$totaver+=$average;
            $localarr=array("month"=>"","claims"=>$tot_claims,"discount"=>$totdis,"scheme"=>$totscheme,"total_savings"=>$tottot,"charged"=>$totcharged,"percentage"=>round(($totperc/$period),1),"average"=>round($totaver/$period),"total_referred"=>$totreffered);
            array_push($arrMonth,$localarr);
            return $arrMonth;

        } catch (Exception $e) {
            return "There is an error.".$e;
        }
    }
    function weekly($id)
    {
        global $conn;

        try {
            $dat2= date('Y-m-d',strtotime('last sunday'));
            $date = new DateTime($dat2);
            $date->modify('-6 day');
            $dat1=$date->format('Y-m-d');
            $datx = new DateTime($dat2);
            $datx->modify('+1 day');
            $dat3=$datx->format('Y-m-d');

            $stmt =  $conn->prepare('SELECT claim_number,a.date_entered FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.date_entered >= :dat1 AND a.date_entered < :dat2 AND b.client_id=:id AND a.Open<>2 ORDER BY claim_id ASC');
            $stmt->bindParam(':dat1', $dat1, PDO::PARAM_STR);
            $stmt->bindParam(':dat2', $dat3, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
            //print_r($stmt->fetchAll());

        } catch (Exception $e) {
            //return $e->getMessage();
        }
    }
    function calcPerc($dat,$client="",$condition=":username",$val="1")
    {
        $currsav=$this->currentSavings($dat,$client,$condition,$val);
        $osav=$this->currentChargedAmnt($dat,$client,$condition,$val)-$this->currentSchemeAmnt($dat,$client,$condition,$val);
        return $osav>0?($currsav/$osav)*100:0;
    }
    function format($val)
    {
        $val=number_format($val,2,',',' ');
        return $val;
    }
    function copayments()
    {
        try {
            global $conn;
            $stmt = $conn->prepare("SELECT count(a.username) as total,a.username FROM (SELECT DISTINCT mca_claim_id,claim_number,username,b.date_entered FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id where (clmnline_charged_amnt=\"0.00\" OR clmline_scheme_paid_amnt=\"0.00\") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId is null AND a.date_entered>\"2020-05-28\") as a GROUP BY a.username ORDER BY total DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function insertTarget($month,$target,$entered_by,$closed_target,$entered_target)
    {
        try {
            global $conn;
            $stmt = $conn->prepare("INSERT INTO target(month,savings_target,entered_by,closed_cases_target,entered_cases_target) VALUES(:month,:target,:entered_by,:closed_target,:entered_target)");
            $stmt->bindParam(':month', $month, PDO::PARAM_STR);
            $stmt->bindParam(':target', $target, PDO::PARAM_STR);
            $stmt->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt->bindParam(':closed_target', $closed_target, PDO::PARAM_STR);
            $stmt->bindParam(':entered_target', $entered_target, PDO::PARAM_STR);
            return $stmt->execute();

        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function showTarget()
    {
        try {
            global $conn;
            $stmt = $conn->prepare("SELECT *FROM target ORDER BY date_entered DESC LIMIT 1");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function weeklyTarget($string,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $dat1= date('Y-m-d',strtotime('last sunday'));
            $dat3=date('Y-m-d');
            $dat3= date('Y-m-d', strtotime($dat3. ' + 1 days'));

            $stmt =  $conn->prepare($string.' AND '.$condition);
            $stmt->bindParam(':dat1', $dat1, PDO::PARAM_STR);
            $stmt->bindParam(':dat2', $dat3, PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function monthlyTarget($string,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $dat=date("Y-m")."%";
            $stmt =  $conn->prepare($string." AND ".$condition);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function dailyTarget($string,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $dat=date("Y-m-d")."%";
            $stmt =  $conn->prepare($string." AND ".$condition);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getSpecialists($xrole="",$xusername="")
    {
        try
        {
            global $conn1;
            $role="claims_specialist";
            $sql="SELECT username FROM staff_users WHERE role=:role AND state=1";
            if($xrole=="claims_specialist" && strlen($xusername)>1)
            {
                $sql="SELECT username FROM staff_users WHERE role=:role AND state=1 AND username=\"$xusername\"";
            }
            $stmt =  $conn1->prepare($sql);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function targets($target)
    {
        try
        {
            $arr=array();
            foreach ($this->getSpecialists() as $row)
            {
                $username=$row[0];
                $savings=(double)$this->monthlyTarget("username=:username",$username);
                $percentage=$target>0?round(($savings/$target)*100):0;
                $myarr=array("username"=>$username,"savings"=>$savings,"percentage"=>$percentage);
                array_push($arr,$myarr);

            }
            return $arr;

        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function showExcelKPI($username,$datee,$str)
    {
        try {
            global $conn;
            $datee=$datee."%";
            $stmt = $conn->prepare($str);
            $stmt->bindParam(':closed', $datee, PDO::PARAM_STR);
            $stmt->bindParam(':user1', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function selectDates($from,$to)
    {
        try {
            global $conn;
            $stmt = $conn->prepare("select DATE_FORMAT(date_entered, \"%Y-%m\")
from claim WHERE date_entered >= :fr AND date_entered <= :to1 AND Open<>2
group by DATE_FORMAT(date_entered, \"%Y-%m-01\")");
            $stmt->bindParam(':fr', $from, PDO::PARAM_STR);
            $stmt->bindParam(':to1', $to, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function selectData($dat,$client,$status)
    {
        $opp=$status=="a.date_entered"?"(1,3,4,5)":"(0)";
        try {
            global $conn;
            $dat=$dat."%";
            $myname="xxx";
            if($client=="Kaelo")
            {
                $myname="Sanlam";
            }
            elseif ($client=="Gaprisk_administrators" || $client=="Insuremed")
            {
                $myname="Insuremed";
            }
            $stmt = $conn->prepare("SELECT b.policy_number as Policy_Number, a.claim_number as Claim_Number,
a.savings_scheme as Savings_By_Scheme,a.savings_discount as Savings_By_Discount,(a.savings_scheme + a.savings_discount) as Total_Savings,
a.date_closed as Date_Closed,a.date_entered as Date_Entered,(a.charged_amnt-a.scheme_paid) as Value_Of_Claims,a.Service_Date,a.end_date,a.createdBy as Created_By,a.username FROM `claim` as a 
INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id Where $status LIKE :dat 
AND (c.client_name=:client_name OR c.client_name=:client1) AND a.Open IN $opp");
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->bindParam(':client_name', $client, PDO::PARAM_STR);
            $stmt->bindParam(':client1', $myname, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function casesWorkedOn($date,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT Distinct claim_id FROM `intervention` where date_entered like :dd AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->rowCount();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function enteredthisClaims($date,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT count(*) FROM `claim` as a WHERE date_entered like :dd AND Open<>2 AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function claimValue($date,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(charged_amnt-scheme_paid) FROM `claim` WHERE date_entered like :dd AND Open<>2 AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenStatus($date)
    {
        //SELECT b.status, COUNT(b.status) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id WHERE c.client_id=31 AND a.date_entered LIKE "%2021-05%" GROUP BY b.status
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT b.status, COUNT(*) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id WHERE c.client_id=31 AND a.date_entered LIKE :dat GROUP BY b.status");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenValue($status,$value,$date)
    {
        try {
            global $conn;
            $una=strlen($status)<2?"(b.status IS NULL OR b.status=:status)":"b.status=:status";
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT COUNT(*) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id WHERE c.client_id=31 AND a.date_entered LIKE :dat AND $una AND a.medication_value=:med");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":med",$value,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenGender($status,$date)
    {
        try {
            global $conn;
            $una=strlen($status)<2?"(b.status IS NULL OR b.status=:status)":"b.status=:status";
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT a.patient_gender, COUNT(*) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id WHERE c.client_id=31 AND a.date_entered AND $una AND (a.patient_gender is not null OR a.patient_gender<>\"\") AND a.date_entered LIKE :dat GROUP BY a.patient_gender ORDER BY a.patient_gender ASC");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenGenderValue($status,$value,$patient_gender,$date)
    {
        try {
            global $conn;
            $una=strlen($status)<2?"(b.status IS NULL OR b.status=:status)":"b.status=:status";
            $una1=strlen($patient_gender)<2?"(a.patient_gender IS NULL OR a.patient_gender=:patient_gender)":"a.patient_gender=:patient_gender";
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT COUNT(*) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id WHERE c.client_id=31 AND a.date_entered LIKE :dat AND $una AND a.medication_value=:med AND $una1");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":med",$value,PDO::PARAM_STR);
            $stmt->bindParam(":patient_gender",$patient_gender,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenScheme($status,$patient_gender,$date)
    {
        try {
            global $conn;
            $una=strlen($status)<2?"(b.status IS NULL OR b.status=:status)":"b.status=:status";
            $una1=strlen($patient_gender)<2?"(a.patient_gender IS NULL OR a.patient_gender=:patient_gender)":"a.patient_gender=:patient_gender";
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT c.medical_scheme, COUNT(*) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id WHERE c.client_id=31 AND a.date_entered AND $una AND a.date_entered LIKE :dat AND $una1 GROUP BY c.medical_scheme");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":patient_gender",$patient_gender,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenSchemeValue($status,$value,$patient_gender,$scheme,$date)
    {
        try {
            global $conn;
            $una=strlen($status)<2?"(b.status IS NULL OR b.status=:status)":"b.status=:status";
            $una1=strlen($patient_gender)<2?"(a.patient_gender IS NULL OR a.patient_gender=:patient_gender)":"a.patient_gender=:patient_gender";
            $una2=strlen($scheme)<2?"(c.medical_scheme IS NULL OR c.medical_scheme=:scheme)":"c.medical_scheme=:scheme";
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT COUNT(*) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id WHERE c.client_id=31 AND a.date_entered LIKE :dat AND $una AND a.medication_value=:med AND $una1 AND $una2");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":med",$value,PDO::PARAM_STR);
            $stmt->bindParam(":patient_gender",$patient_gender,PDO::PARAM_STR);
            $stmt->bindParam(":scheme",$scheme,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenIcd10($status,$patient_gender,$scheme,$date)
    {
        try {
            global $conn;
            $una=strlen($status)<2?"(b.status IS NULL OR b.status=:status)":"b.status=:status";
            $una1=strlen($patient_gender)<2?"(a.patient_gender IS NULL OR a.patient_gender=:patient_gender)":"a.patient_gender=:patient_gender";
            $una2=strlen($scheme)<2?"(c.medical_scheme IS NULL OR c.medical_scheme=:scheme)":"c.medical_scheme=:scheme";
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT k.shortdesc, COUNT(*) FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN diagnosis as k ON a.icd10=k.diag_code WHERE c.client_id=31 AND a.date_entered LIKE :dat AND $una AND $una1 AND $una2 GROUP BY k.shortdesc");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":patient_gender",$patient_gender,PDO::PARAM_STR);
            $stmt->bindParam(":scheme",$scheme,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getAspenIcd10Value($status,$value,$patient_gender,$scheme,$date,$icd10)
    {
        try {
            global $conn;
            $una=strlen($status)<2?"(b.status IS NULL OR b.status=:status)":"b.status=:status";
            $una1=strlen($patient_gender)<2?"(a.patient_gender IS NULL OR a.patient_gender=:patient_gender)":"a.patient_gender=:patient_gender";
            $una2=strlen($scheme)<2?"(c.medical_scheme IS NULL OR c.medical_scheme=:scheme)":"c.medical_scheme=:scheme";
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT a.claim_id,a.claim_number FROM `claim` as a INNER JOIN aspen_checklist as b ON a.claim_id=b.claim_id INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN diagnosis as k ON a.icd10=k.diag_code WHERE c.client_id=31 AND a.date_entered LIKE :dat AND $una AND a.medication_value=:med AND $una1 AND $una2 AND k.shortdesc=:icd10");
            $stmt->bindParam(":dat",$se,PDO::PARAM_STR);
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":med",$value,PDO::PARAM_STR);
            $stmt->bindParam(":patient_gender",$patient_gender,PDO::PARAM_STR);
            $stmt->bindParam(":scheme",$scheme,PDO::PARAM_STR);
            $stmt->bindParam(":icd10",$icd10,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getWorkingDaysx($startDate,$endDate,$holidays){
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);


        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)

            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        //We subtract the holidays
        foreach($holidays as $holiday){
            $myholiday=date("Y")."-";
            $time_stamp=strtotime($myholiday.$holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }

    function savingsMain($date,$condition=":username",$val="1")
    {
        try {
            global $conn;
            $se="%".$date."%";
            $stmt = $conn->prepare("SELECT SUM(a.savings_scheme + a.savings_discount) as sav,a.charged_amnt,a.scheme_paid FROM `claim` as a  WHERE Open=0 AND a.date_closed LIKE :dd AND ".$condition);
            $stmt->bindParam(":dd",$se,PDO::PARAM_STR);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function day_rr($month_number,$strtfrom)
    {
        $mnarr=[];
        for($x=$month_number; $x>=$strtfrom;$x--){

            $datt= date('Y-m', strtotime(date('Y-m')." -" . $x . " month"));
            array_push($mnarr,$datt);

        }
        return$mnarr;
    }

    function analysisQA($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1")
    {
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }

        $dat=!empty($start_date)?" AND a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
        $op="";
        $op1=" AND a.quality=1";
        if($typ=="pending")
        {
            $op1=" AND y.qa_signed=1 AND y.cs_signed=0 AND quality=2";
            $dat=!empty($start_date)?" AND y.date_entered >='".$start_date."' AND y.date_entered<'".$end_date."' ":" AND 1";
            $op=" INNER JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";
        }
        elseif ($typ=="completed")
        {
            $op1=" AND a.quality=2 AND y.qa_signed=1 AND y.cs_signed=1";
            $dat=!empty($start_date)?" AND y.date_entered >='".$start_date."' AND y.date_entered<'".$end_date."' ":" AND 1";
            $op=" INNER JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";
        }
        elseif ($typ=="all")
        {
            $op1=" AND (a.quality=2 OR a.quality=1)";
            $dat=!empty($start_date)?" AND a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
        }
        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" a.username IN ('".$users_em."')":" 1";
        $vol1=!empty($clients)?" AND c.client_name IN ('".$clients_em."')":" AND 1";
        $all=$vol.$vol1.$op1.$dat." AND ".$condition;

        $sql="SELECT c.client_name,COUNT(c.client_name) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all." GROUP BY c.client_name";
        if($val=="users")
        {
            $sql="SELECT a.username as client_name,COUNT(a.username) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all." GROUP BY a.username";
        }
        try {
//echo $sql;
            global $conn;
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

            //return $sql;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function marksQA($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1")
    {
        global $conn;
        $array=array();
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }

        $op1=" AND a.quality=2 AND y.qa_signed=1 AND y.cs_signed=1";
        $dat=!empty($start_date)?" AND y.date_entered >='".$start_date."' AND y.date_entered<'".$end_date."' ":" AND 1";
        $op=" INNER JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";

        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" a.username IN ('".$users_em."')":" 1";
        $vol1=!empty($clients)?" AND c.client_name IN ('".$clients_em."')":" AND 1";
        $all=$vol.$vol1.$op1.$dat." AND ".$condition;
        $sql="SELECT c.client_name,COUNT(c.client_name) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all." GROUP BY c.client_name";

        $sql1="SELECT y.position as client_name,COUNT(y.position) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all." AND c.client_name=:useer GROUP BY y.position";
        if($val=="users")
        {
            $sql="SELECT a.username as client_name,COUNT(a.username) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all." GROUP BY a.username";
            $sql1="SELECT y.position as client_name,COUNT(y.position) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all." AND a.username=:useer GROUP BY y.position";
        }

        try {
            foreach ($this->getMarks($sql) as $row)
            {
                $month=$row[0];
                $stmt =  $conn->prepare($sql1);
                $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
                $stmt->bindParam(':useer', $month, PDO::PARAM_STR);
                $stmt->execute();
                $fd=$stmt->fetchAll();
                array_push($fd,array(0=>"Error",1=>"0","client_name"=>"Error","total"=>"0"));
                $aa=array("claim_date"=>$month,"cont"=> $fd);
                array_push($array,$aa);
            }
            //echo $sql1;
            return $array;

        } catch (Exception $e) {
            echo("There is an error.".$e);
        }
    }

    function getMarks($query,$val="1")
    {
        global $conn;

        try {

            $stmt =  $conn->prepare($query);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo("There is an error.".$e);
        }
    }


    function marksQATrend($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1")
    {
        global $conn;
        $array=array();
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }

        $dat=!empty($start_date)?" AND a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
        $op=" LEFT JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";
        $op1=" AND a.quality=1";
        $ad="a.date_closed";
        if($typ=="pending")
        {
            $op1=" AND y.qa_signed=1 AND y.cs_signed=0 AND quality=2";
            $dat=!empty($start_date)?" AND y.date_entered >='".$start_date."' AND y.date_entered<'".$end_date."' ":" AND 1";
            $ad="y.date_entered";
            $op=" INNER JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";
        }
        elseif ($typ=="completed")
        {
            $op1=" AND a.quality=2 AND y.qa_signed=1 AND y.cs_signed=1";
            $dat=!empty($start_date)?" AND y.date_entered >='".$start_date."' AND y.date_entered<'".$end_date."' ":" AND 1";
            $ad="y.date_entered";
            $op=" INNER JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";
        }
        elseif ($typ=="all")
        {
            $op1=" AND (a.quality=2 OR a.quality=1)";
            $dat=!empty($start_date)?" AND a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
            $ad="a.date_closed";
        }
        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" a.username IN ('".$users_em."')":" 1";
        $vol1=!empty($clients)?" AND c.client_name IN ('".$clients_em."')":" AND 1";
        $all=$vol.$vol1.$op1.$dat." AND ".$condition;

        $sql="select DATE_FORMAT($ad, '%Y-%m') AS claim_date, COUNT($ad) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE $all GROUP BY  DATE_FORMAT($ad, '%Y - %m') ORDER BY claim_date DESC LIMIT 7";

        $sql1="SELECT y.position as client_name,COUNT(y.position) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE $all AND $ad LIKE :first_day GROUP BY y.position";
        if($val=="users")
        {
            $sql1="SELECT y.position as client_name,COUNT(y.position) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE $all AND $ad LIKE :first_day GROUP BY y.position";

        }

        try {
            foreach ($this->trend_claimsQA($sql,$val1) as $row)
            {
                $month=$row[0];
                $first_day="%".$month."%";

                $stmt =  $conn->prepare($sql1);
                $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
                $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
                $stmt->execute();
                $fd=$stmt->fetchAll();

                array_push($fd,array(0=>"Error",1=>"0","client_name"=>"Error","total"=>"0"));
                $aa=array("claim_date"=>$month,"cont"=> $fd);
                array_push($array,$aa);
            }

            return $array;

        } catch (Exception $e) {
            echo("There is an error.".$e);
        }
    }
    function trend_claimsQA($query,$val1="1")
    {
        global $conn;
        try {
            $stmt =  $conn->prepare($query);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo("There is an error.".$e);
        }
    }

    function getPercTotals($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1",$user="Western")
    {
        global $conn;
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }

        $dat=!empty($start_date)?" AND a.date_entered >='".$start_date."' AND a.date_entered<'".$end_date."' ":" AND 1";

        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);
        $vol=!empty($users)?" a.username IN ('".$users_em."')":" 1";
        $vol1=!empty($clients)?" AND c.client_name IN ('".$clients_em."')":" AND 1";
        $all=$vol.$vol1.$dat." AND ".$condition;

        $sql="SELECT COUNT(a.claim_id) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id WHERE $all AND c.client_name=:userr AND recordType IS NULL";
        if($val=="users")
        {
            $sql="SELECT COUNT(a.claim_id) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id WHERE $all AND a.username=:userr AND recordType IS NULL";
        }
        try {

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->bindParam(':userr', $user, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();

            //return $sql;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function kpiPerc($month="",$condition="",$username="")
    {
        global $conn;

        try {

            $dat = "%" . $month . "%";

            $sql="SELECT SUM(charged_amnt-scheme_paid) as charged FROM claim WHERE date_closed LIKE :dat AND $condition AND Open=0";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $charged=$stmt->fetchColumn();
            //$perc=($total_savings/$charged)*100;
            //$perc=is_nan($perc)?0:$perc;
            //$perc=is_infinite($perc)?0:$perc;

            return $charged;

        } catch (Exception $e) {
            return "There is an error.".$e;
        }
    }

    function web_brokers($broker_name)
    {
        global $conn;
        try {
            if(strlen($broker_name)>1)
            {
                $stmt = $conn->prepare("select b.name,b.surname,a.name as broker_name,b.email,b.contact_number,b.medical_scheme,b.date_entered from web_clients as a RIGHT JOIN web_clients as b ON a.client_id=b.broker_id WHERE a.name=:broker_name");
                $stmt->bindParam(':broker_name', $broker_name, PDO::PARAM_STR);
            }
            else
            {
                $stmt = $conn->prepare("select b.name,b.surname,a.name as broker_name,b.email,b.contact_number,b.medical_scheme,b.date_entered from web_clients as a RIGHT JOIN web_clients as b ON a.client_id=b.broker_id");
            }
            $stmt->execute();
            return $stmt->fetchAll();

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }
    }
    function webClients($role,$broker_id="",$count=1)
    {
        global $conn;
        $condition="";
        if((int)$broker_id>0)
        {
            $condition=" AND b.broker_id=".$broker_id;
        }
        try {
            $stmt = $conn->prepare("select b.client_id,b.name,b.surname,b.email,b.contact_number,b.medical_scheme,b.date_entered,a.name as broker_name from web_clients as a RIGHT JOIN web_clients as b ON a.client_id=b.broker_id WHERE b.role=:role AND b.status=1 $condition");
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->execute();
            if($count==1)
            {
                return $stmt->rowCount();
            }
            else
            {
                return $stmt->fetchAll();
            }


        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }
    }
    function switchProd($client_id,$month)
    {
        global $conn;
        $dat = "%" . $month . "%";
        try {
            $stmt = $conn->prepare("SELECT * FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE client_id=:client_id AND (senderId=10 OR senderId=1) AND a.date_entered LIKE :dd");
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
            $stmt->bindParam(':dd', $dat, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }
    function getSwitchCHF($client,$stat=0,$month="")
    {
        global $conn3;
        $clientor="--";
        $clientor1="--";
        $clientor2="--";
        $clientor3="--";
        $clientor4="--";
        if($client=="Kaelo")
        {
            $client="Kaelo Gap";
            $clientor1="MedExpense";
            $clientor2="Centriq Cancer";
            $clientor3="Dis-Chem Health";
            $clientor4="OLD Dis-Chem Health - Western National";
        }
        elseif ($client=="Western")
        {
            $client="Western Gap Care";
            $clientor="Western Gap";

        }
        elseif ($client=="Sanlam")
        {
            $client="Sanlam Gap";
        }
        $date = date("Y-m-d");
        $policy_cancellationdate = date('Y-m-d', strtotime($date. '  -6 months'));
        $month=$month."-28";

        try {
            $fields=$stat==0?"COUNT(*)":"*";
            $stmt = $conn3->prepare("SELECT $fields FROM `chf` WHERE date_entered < :month AND (ProductName=:ProductName OR ProductName=:ProductName1 
			OR ProductName=:ProductName2 OR ProductName=:ProductName3 OR ProductName=:ProductName4 OR ProductName=:ProductName5) AND (policy_cancellationdate>:policy_cancellationdate OR policy_cancellationdate is null OR policy_cancellationdate = '')");
            $stmt->bindParam(':ProductName', $client, PDO::PARAM_STR);
            $stmt->bindParam(':ProductName1', $clientor, PDO::PARAM_STR);
            $stmt->bindParam(':ProductName2', $clientor1, PDO::PARAM_STR);
            $stmt->bindParam(':ProductName3', $clientor2, PDO::PARAM_STR);
            $stmt->bindParam(':ProductName4', $clientor3, PDO::PARAM_STR);
            $stmt->bindParam(':ProductName5', $clientor4, PDO::PARAM_STR);
            $stmt->bindParam(':month', $month, PDO::PARAM_STR);
            $stmt->bindParam(':policy_cancellationdate', $policy_cancellationdate, PDO::PARAM_STR);
            $stmt->execute();
            if($stat==0)
            {
                return $stmt->fetchColumn();
            }
            else{
                return $stmt->fetchAll();
            }

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }
    function cinagiClaims($client_id,$month)
    {
        global $conn;
        $dat = "%" . $month . "%";
        try {
            $stmt = $conn->prepare("SELECT a.claim_number,a.date_entered,a.Open,a.date_closed,a.claim_type,a.savings_discount,a.savings_scheme,username FROM `claim` as a INNER join member as b on a.member_id=b.member_id where b.client_id=:client_id AND a.date_entered LIKE :dd");
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
            $stmt->bindParam(':dd', $dat, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }

    function switchSeamless($client_id,$month)
    {
        global $conn3;
        $dat = "%" . $month . "%";
        try {
            $stmt = $conn3->prepare("SELECT * FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE client_id=:client_id AND (senderId=10 OR senderId=1 OR senderId=11) AND a.date_entered LIKE :dd");
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
            $stmt->bindParam(':dd', $dat, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }
    function getCHF($client_name)
    {
        global $conn3;
        $client_name = "%" . $client_name . "%";
        try {
            $stmt = $conn3->prepare("SELECT COUNT(policy_number) FROM `chf` WHERE product_name LIKE :product_name");
            $stmt->bindParam(':product_name', $client_name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }
    function getAllSwitch($client_id,$month,$all="c.client_name=:client_name AND",$ext="")
    {
        global $conn;
        global $conn3;
        $dat = "%" . $month . "%";
        $query="SELECT a.claim_number,a.Service_Date,a.icd10,a.charged_amnt,a.scheme_paid,a.gap,a.client_gap,b.policy_number,b.first_name,b.surname,b.id_number,b.scheme_number,b.medical_scheme,b.scheme_option,c.client_name,a.senderId,a.date_entered FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE $all (senderId=10 OR senderId=1 OR senderId=11) AND a.date_entered LIKE :dd ".$ext;
        try {
            $stmt = $conn3->prepare($query);
            $stmt->bindParam(':dd', $dat, PDO::PARAM_STR);
            if($all=="c.client_name=:client_name AND")
            {
                $stmt->bindParam(':client_name', $client_id, PDO::PARAM_STR);
            }
            $stmt->execute();
            $arr=$stmt->fetchAll();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':dd', $dat, PDO::PARAM_STR);
            if($all=="c.client_name=:client_name AND")
            {
                $stmt->bindParam(':client_name', $client_id, PDO::PARAM_STR);
            }
            $stmt->execute();
            $arr1=$stmt->fetchAll();
            return array_merge($arr,$arr1);

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }
    function switchSeamlessClaims($client_id,$month,$all="client_id=:client_id AND")
    {
        global $conn3;
        $dat = "%" . $month . "%";
        try {
            $stmt = $conn3->prepare("");
            $stmt->bindParam(':dd', $dat, PDO::PARAM_STR);
            if($all=="client_id=:client_id AND")
            {
                $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll();

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }
    function switchProdClaims($client_id,$month,$all="client_id=:client_id AND")
    {
        global $conn;
        $dat = "%" . $month . "%";
        try {
            $stmt = $conn->prepare("SELECT a.claim_number,a.Service_Date,a.icd10,a.charged_amnt,a.scheme_paid,a.client_gap,b.policy_number,b.first_name,b.surname,b.id_number,b.scheme_number,b.medical_scheme,b.scheme_option,c.client_name,a.senderId,a.date_entered FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.member_id=c.client_id WHERE $all (senderId=10 OR senderId=1) AND a.date_entered LIKE :dd");
            $stmt->bindParam(':dd', $dat, PDO::PARAM_STR);
            if($all=="client_id=:client_id AND")
            {
                $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll();

        } catch (Exception $e) {
            return "There is an error : ".$e->getMessage();
        }

    }

    function aiQA($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1")
    {
        global $conn;
        $arrz=array();
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        $dat=!empty($start_date)?" date_entered >='".$start_date."' AND date_entered<'".$end_date."' ":" 1";

        $users_array=array_map('strval', explode(',', $users));
        $clients_array=array_map('strval', explode(',', $clients));
        $users_em = implode("','",$users_array);
        $clients_em = implode("','",$clients_array);

        try {

            $sql="SELECT data1,data2,data3,data4,data5,sla17,sla19,sla16,sla18,sla20,sla21,sla22,calls1,calls2,calls3,calls4,calls5,calls6,calls7,calls8,calls9,calls10,sla1,sla2,sla3,sla4,sla5,sla6,sla7,sla8,sla9,sla10,sla11,sla12,sla13,sla14,emails1,emails2,emails3,emails4,emails5,emails6,emails7,emails8,emails9
FROM quality_assurance as a inner JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE a.id IN (SELECT MAX(id) AS id FROM quality_assurance WHERE ".$dat." GROUP BY claim_id DESC) AND d.client_name=:username11";

            if($val=="users")
            {
                $sql="SELECT data1,data2,data3,data4,data5,sla17,sla19,sla16,sla18,sla20,sla21,sla22,calls1,calls2,calls3,calls4,calls5,calls6,calls7,calls8,calls9,calls10,sla1,sla2,sla3,sla4,sla5,sla6,sla7,sla8,sla9,sla10,sla11,sla12,sla13,sla14,emails1,emails2,emails3,emails4,emails5,emails6,emails7,emails8,emails9
FROM quality_assurance as a inner JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE a.id IN (SELECT MAX(id) AS id FROM quality_assurance WHERE ".$dat." GROUP BY claim_id DESC) AND b.username=:username11";

                $users_array=strlen($users_array[0])>1?$users_array:$this->getUser();
                $arrz=$this->calcFA($users_array,$sql,$val1);

            }
            else{
                $clients_array=strlen($clients_array[0])>1?$clients_array:$this->getClient();
                $arrz=$this->calcFA($clients_array,$sql,$val1);
            }

            return json_encode($arrz);
        }
        catch (Exception $e)
        {
            return "Error ->".$e->getMessage();
        }
    }

    function getQADescr($qa)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT description FROM qa_descriptions WHERE qa_value=:qa_value");
        $stmt->bindParam(':qa_value', $qa, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
        //qa_descriptions
    }

    function calcFA($array=array(),$sql="",$val1="",$arrz=array())
    {
        global $conn;

        for($i=0;$i<count($array);$i++)
        {
            $username=$array[$i];
            $usercv=array();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username11', $username, PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
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

            array_push($arrz,$realarr);
        }
        return $arrz;
    }
    function getClient()
    {
        global $conn;
        $arr=[];
        $stmt=$conn->prepare("SELECT DISTINCT client_name FROM `clients` WHERE (reporting_status=1 OR client_name=\"Individual\")");
        $stmt->execute();
        foreach($stmt->fetchAll() as $row)
        {
            array_push($arr,$row[0]);
        }
        return $arr;
    }
    function getUser()
    {
        global $conn;
        $arr=[];
        $stmt=$conn->prepare("SELECT username FROM `users_information` where status=1");
        $stmt->execute();
        foreach($stmt->fetchAll() as $row)
        {
            array_push($arr,$row[0]);
        }
        return $arr;
    }
    function qaPercentages($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1")
    {
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        else
        {
            $start_date=date("Y-m-01");
            $end_date=date("Y-m-d");
        }
        $arr=array();
        $op1=" a.quality=2 AND y.qa_signed=1 AND y.cs_signed=1 AND a.username=:usernamex";
        $dat=!empty($start_date)?" AND y.date_entered >='".$start_date."' AND y.date_entered<'".$end_date."' ":" AND 1";
        $op=" INNER JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";
        $users_array=array_map('strval', explode(',', $users));
        $users_array=strlen($users_array[0])>1?$users_array:$this->getUser();
        //$users_em = implode("','",$users_array);
        //$vol=" a.username IN ('".$users_em."')";
        $all=$op1.$dat." AND ".$condition;
        $sql="";
        if($val=="users")
        {
            $sql="SELECT COUNT(assessment_score) as Percentage FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all;
            $sqly="SELECT SUM(assessment_score) as Percentage FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all;
        }
        //echo $sql;
        try {
            global $conn;
            for($i=0;$i<count($users_array);$i++)
            {
                $username=$users_array[$i];
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
                $stmt->bindParam(':usernamex', $username, PDO::PARAM_STR);
                $stmt->execute();
                $totalmark=$stmt->fetchColumn();
                $passedmark=$this->getPassed($sqly,$username,$val1);
                //echo $sql;
                $perc=$totalmark>0?round($passedmark/$totalmark):0;
                $tt="$passedmark/$totalmark";
                $inarr=array("client_name"=>$username,"0"=>$username,"total"=>$perc,"1"=>$perc,"","tt"=>$tt);
                array_push($arr,$inarr);
            }
            return $arr;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function getPassed($sql,$username,$val1)
    {
        global $conn;
        $sqlx=$sql.' AND y.position="Passed"';
        //echo "-----".$sqlx;
        $stmt = $conn->prepare($sqlx);
        $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
        $stmt->bindParam(':usernamex', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();

    }

    function getIncentive($val="",$clients="",$users="",$start_date="",$end_date="",$typ="open",$condition=":username",$val1="1")
    {
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        else
        {
            $start_date=date("Y-m-01");
            $end_date=date("Y-m-d");
        }
        $arr=array();
        $dat=!empty($start_date)?" a.date_closed >='".$start_date."' AND a.date_closed<'".$end_date."' ":" AND 1";
        $dat2=!empty($start_date)?" date_entered >='".$start_date."' AND date_entered<'".$end_date."' ":" AND 1";

        $sql="SELECT SUM(savings_discount) as discount, SUM(savings_scheme) as scheme,SUM(savings_discount + savings_scheme) as total,
COUNT(claim_id) as total_claim FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE $dat AND username=:usernamex AND Open=0";
//echo $sql;
        $users_array=array_map('strval', explode(',', $users));
        $users_array=strlen($users_array[0])>1?$users_array:$this->getUser();
        try {
            global $conn;
            for($i=0;$i<count($users_array);$i++)
            {
                $username=$users_array[$i];
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':usernamex', $username, PDO::PARAM_STR);
                $stmt->execute();
                $row=$stmt->fetch();
                $savings=$row[2];
                $total_closed=$row[3];
                $claim_value=$this->claimValue2($dat2,$username);
                $perc=$claim_value>0?round(($savings/$claim_value)*100):0;
                $qa=$this->indivQA($val,$clients,$users,$start_date,$end_date,$username,$val1);
                $inarr=array("username"=>$username,"savings"=>$perc,"closed_claims"=>$total_closed,"qa"=>$qa);
                array_push($arr,$inarr);
            }
            return $arr;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }

    }

    function indivQA($val="",$clients="",$users="",$start_date="",$end_date="",$idivuser="",$condition=":username",$val1="1")
    {
        $condition=":username";
        if(!empty($start_date))
        {
            $date = new DateTime($end_date);
            $date->modify('+1 day');
            $end_date=$date->format('Y-m-d');
        }
        else
        {
            $start_date=date("Y-m-01");
            $end_date=date("Y-m-d");
        }

        $op1=" a.quality=2 AND y.qa_signed=1 AND y.cs_signed=1 AND a.username=:usernamex";
        $dat=!empty($start_date)?" AND y.date_entered >='".$start_date."' AND y.date_entered<'".$end_date."' ":" AND 1";
        $op=" INNER JOIN (SELECT *FROM (SELECT *FROM quality_assurance ORDER BY id DESC) AS x GROUP BY claim_id) as y ON a.claim_id=y.claim_id";

        $all=$op1.$dat." AND ".$condition;

        $sql="SELECT COUNT(assessment_score) as Percentage FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all;
        $sqly="SELECT SUM(assessment_score) as Percentage FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id
INNER JOIN clients as c ON b.client_id=c.client_id $op WHERE ".$all;
//echo $sql;

        try {
            global $conn;
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $val1, PDO::PARAM_STR);
            $stmt->bindParam(':usernamex', $idivuser, PDO::PARAM_STR);
            $stmt->execute();
            $totalmark=$stmt->fetchColumn();

            $passedmark=$this->getPassed($sqly,$idivuser,$val1);

            $perc=$totalmark>0?round($passedmark/$totalmark):0;

            return $perc;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            return (int)$e->getMessage();
        }
    }
    function claimValue2($date,$val="1")
    {
        try {
            global $conn;
            $sql="SELECT SUM(charged_amnt-scheme_paid) FROM `claim` WHERE $date AND Open<>2 AND username=:username";
            //echo $sql;
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $val, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function reformat($number)
    {
        return number_format($number, 2, '.', ',');
    }
}
