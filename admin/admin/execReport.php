<?php
session_start();
$_SESSION['start_db']=true;
require_once "../dbconn1.php";
error_reporting(0);

class execReport
{

    /*** $message = a message saying we have connected ***/
    function openCases()
    {
        try {

            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('SElECT username,COUNT(Open) as total FROM claim WHERE Open=1 GROUP BY username');
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $username = $row[0];
                $total = $row[1];
                $myArray[] = filter_var("{~y~:" . $total . ",~indexLabel~:~" . $username . "($total)~}", FILTER_SANITIZE_STRING);
            }
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;


        } catch (Exception $e) {
            echo("There is an error.");
        }
    }

    function schemeSavings()
    {
        try {
            $from = date('Y-m').'%';
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('SElECT username,SUM(savings_scheme) as total FROM claim WHERE date_closed LIKE :dat AND Open=0 AND recordType is null GROUP BY username');
            $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $username = $row[0];
                $total = $row[1];
                $myArray[] = filter_var("{~y~:" . $total . ",~label~:~" . $username . "~}", FILTER_SANITIZE_STRING);
            }
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }

    function discountSavings()
    {
        try {
            $from = date('Y-m').'%';
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('SElECT username,SUM(savings_discount) as total FROM claim WHERE date_closed LIKE :dat AND Open=0 AND recordType is null GROUP BY username');
            $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $username = $row[0];
                $total = $row[1];
                $myArray[] = filter_var("{~y~:" . $total . ",~label~:~" . $username . "~}", FILTER_SANITIZE_STRING);
            }
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
//Clients savings
    function schemeSavings1()
    {
        try {
            $from = date('Y-m').'%';
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('SELECT DISTINCT c.client_name,SUM(a.savings_scheme) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_closed LIKE :dat AND a.Open=0 AND a.recordType is null GROUP BY c.client_name');
            $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $username = $row[0];
                $total = $row[1];

                $myArray[] = filter_var("{~y~:" . $total . ",~label~:~" . $username . "~}", FILTER_SANITIZE_STRING);
            }
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }

    function discountSavings1()
    {
        try {
            $from = date('Y-m').'%';
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('SELECT DISTINCT c.client_name,SUM(a.savings_discount) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_closed LIKE :dat AND a.Open=0 AND a.senderId is null GROUP BY c.client_name');
            $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $username = $row[0];
                $total = $row[1];

                $myArray[] = filter_var("{~y~:" . $total . ",~label~:~" . $username . "~}", FILTER_SANITIZE_STRING);
            }
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }

    function myCases()
    {
        try {
            $from = date('Y-m').'%';
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('SElECT username,Count(Open) as total FROM claim WHERE date_entered LIKE :dat GROUP BY username');
            $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $username = $row[0];
                $total = $row[1];
                $myArray[] = filter_var("{~y~:" . $total . ",~label~:~" . $username . "~,~indexLabel~: ~" . $total . "~}", FILTER_SANITIZE_STRING);
            }
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }

    function clients()
    {
        try {
            $from = date('Y-m').'%';
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('SElECT DISTINCT c.client_name,Count(a.Open) as total FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_entered LIKE :dat AND a.Open<>2 AND a.recordType is null GROUP BY c.client_name');
            $stmt->bindParam(':dat', $from, PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll() as $row) {
                $username = $row[0];
                $total = $row[1];

                $myArray[] = filter_var("{~y~:" . $total . ",~label~:~" . $username . "~,~indexLabel~: ~" . $total . "~}", FILTER_SANITIZE_STRING);
            }
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function trend()
    {
        try {
            $from = date('Y-m').'%';
            $first_day=date('Y-m-01');
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('select DATE_FORMAT(date_entered, \'%Y-%m\') AS claim_date, count(*) cc FROM claim WHERE date_entered < :first_day AND Open<>2 AND recordType is NULL GROUP BY  DATE_FORMAT(date_entered, \'%Y - %m\')
ORDER BY claim_date DESC LIMIT 7');
            $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
            $stmt->execute();
            $myArray=$stmt->fetchAll();
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function trend1()
    {
        try {
            $from = date('Y-m').'%';
            $first_day=date('Y-m-01');
            /*** set the error mode to excptions ***/
            $myArray = array();
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('select DATE_FORMAT(date_closed, \'%Y-%m\') AS savings_date, SUM(savings_scheme+savings_discount) savings FROM claim WHERE date_closed < :first_day AND Open<>2 AND recordType is NULL GROUP BY  DATE_FORMAT(date_closed, \'%Y - %m\')
ORDER BY savings_date DESC LIMIT 7');
            $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
            $stmt->execute();
            $myArray=$stmt->fetchAll();
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function trend_clients()
    {
        $client_id=$_SESSION['user_id'];
        $client_id1=$_SESSION['user_id'];
        if($client_id=="Gaprisk_administrators")
        {
            $client_id1="Insuremed";
        }
        try {
            $from = date('Y-m').'%';
            $first_day=date('Y-m-01');
            /*** set the error mode to excptions ***/
            $myArray = array(
                array("claim_date"=>"2019-01","cc"=>0),
                array("claim_date"=>"2019-02","cc"=>0),
                array("claim_date"=>"2019-03","cc"=>0),
                array("claim_date"=>"2019-04","cc"=>0),
                array("claim_date"=>"2019-05","cc"=>0),
                array("claim_date"=>"2019-06","cc"=>0),
                array("claim_date"=>"2019-07","cc"=>0),
                array("claim_date"=>"2019-08","cc"=>0),
                array("claim_date"=>"2019-09","cc"=>0),

            );

            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('select DATE_FORMAT(a.date_entered, \'%Y-%m\') AS claim_date, count(*) cc FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE a.date_entered < :first_day AND Open<>2 AND (b.client_name=:name1 OR b.client_name=:name2) GROUP BY  DATE_FORMAT(a.date_entered, \'%Y - %m\')
ORDER BY claim_date DESC LIMIT 9');
            $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
            $stmt->bindParam(':name1', $client_id, PDO::PARAM_STR);
            $stmt->bindParam(':name2', $client_id1, PDO::PARAM_STR);
            $stmt->execute();
            //array_push($myArray,$stmt->fetchAll());
            $myArray=$stmt->fetchAll();
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function trend1_clients()
    {
        $client_id=$_SESSION['user_id'];
        if($client_id=="Gaprisk_administrators")
        {
            $client_id1="Insuremed";
        }
        try {
            $from = date('Y-m').'%';
            $first_day=date('Y-m-01');
            /*** set the error mode to excptions ***/
            $myArray = array(
                array("savings_date"=>"2019-01","cc"=>0),
                array("savings_date"=>"2019-02","cc"=>0),
                array("savings_date"=>"2019-03","cc"=>0),
                array("savings_date"=>"2019-04","cc"=>0),
                array("savings_date"=>"2019-05","cc"=>0),
                array("savings_date"=>"2019-06","cc"=>0),
                array("savings_date"=>"2019-07","cc"=>0),
                array("savings_date"=>"2019-08","cc"=>0),
                array("savings_date"=>"2019-09","cc"=>0),

            );
            $this->con()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt =  $this->con()->prepare('select DATE_FORMAT(a.date_closed, \'%Y-%m\') AS savings_date, SUM(a.savings_scheme+a.savings_discount) savings FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE a.date_closed < :first_day AND (b.client_name=:name1 OR b.client_name=:name2) AND Open=0 GROUP BY  DATE_FORMAT(a.date_closed, \'%Y - %m\')
ORDER BY savings_date DESC LIMIT 9');
            $stmt->bindParam(':first_day', $first_day, PDO::PARAM_STR);
            $stmt->bindParam(':name1', $client_id, PDO::PARAM_STR);
            $stmt->bindParam(':name2', $client_id1, PDO::PARAM_STR);
            $stmt->execute();
            //array_push($myArray,$stmt->fetchAll());
            $myArray=$stmt->fetchAll();
            $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
            echo $myJson;
        } catch (Exception $e) {
            echo("There is an error.");
        }
    }
    function con()
    {
        $conn = connection("mca","MCA_admin");

        return $conn;
    }
}
$myOpenCase=new execReport();
$id=validateXss($_GET['id']);
//$id=10;
if($_SESSION['level'] == "admin") {
    if($id==1)
    {
        $myOpenCase->openCases();
    }
    if($id==2)
    {
        $myOpenCase->schemeSavings();
    }
    if($id==3)
    {
        $myOpenCase->discountSavings();
    }
    if($id==4)
    {
        $myOpenCase->myCases();
    }
    if($id==5)
    {
        $myOpenCase->clients();
    }
    if ($id==6)
    {
        $myOpenCase->schemeSavings1();
    }
    if($id==7) {
        $myOpenCase->discountSavings1();
    }
}
if($id==8)
{
    $myOpenCase->trend();
}
if($id==9)
{
    $myOpenCase->trend1();
}
if($id==10)
{
    $myOpenCase->trend_clients();
}
if($id==11)
{
    $myOpenCase->trend1_clients();
}

?>