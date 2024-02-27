<?php
session_start();
error_reporting(0);
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$_SESSION['start_db']=true;
require_once('../dbconn1.php');
$time = date('r');
$conn = connection("mca", "MCA_admin");
$identity=validateXss($_GET['id']);
//$identity=5;
$from = date('Y-m').'%';

//include("../admin/validateAdmin.php");
if(isset($_COOKIE["myMCA"])) {
    if ($identity == 1) {
        $stmt = $conn->prepare('SELECT b.client_name, SUM(a.savings_scheme) as scheme,SUM(a.savings_discount) as discount  
FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id WHERE a.Open=0 AND a.date_closed LIKE :dat AND a.recordType IS NULL GROUP BY b.client_name HAVING SUM(a.savings_scheme + a.savings_discount)> 0');
        $stmt->bindParam(':dat', $from, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll();
        $myArray = json_encode($data, JSON_NUMERIC_CHECK);
        // echo $myArray;

        echo("data:{$myArray}\n\n");
    } else if ($identity == 2) {
        $stmt = $conn->prepare('SELECT b.client_name, COUNT(Open) as total FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id 
WHERE a.date_entered LIKE :dat AND a.recordType IS NULL GROUP BY b.client_name');
        $stmt->bindParam(':dat', $from, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll();
        $myArray = json_encode($data, JSON_NUMERIC_CHECK);
        // echo $myArray;

        echo("data:{$myArray}\n\n");
    } else if ($identity == 3) {
        $myArray = array();
        $stmt = $conn->prepare('SELECT b.client_name as name, COUNT(a.Open) as y FROM claim as a INNER JOIN member as c ON a.member_id=c.member_id INNER JOIN clients as b ON c.client_id=b.client_id 
WHERE a.Open=1 AND a.recordType IS NULL GROUP BY b.client_name');
        $stmt->bindParam(':dat', $from, PDO::PARAM_STR);

        $stmt->execute();
        /* foreach ($stmt->fetchAll() as $row) {
     
             $name = $row[0];
             $total = $row[1];
     
             $myArray[] = filter_var("{name:=" . $name . "=,y:".$total."}", FILTER_SANITIZE_STRING);
         }
         $myJson = json_encode($myArray, JSON_NUMERIC_CHECK);
     */

        // echo $myArray;
        $data = $stmt->fetchAll();
        $myArray = json_encode($data, JSON_NUMERIC_CHECK);
        echo("data:{$myArray}\n\n");
    } else if ($identity == 4) {
        $yy = date("Y");
        $mm = date("n");

//echo $yy."===".$mm."\n";
        $all = list_week_days($yy, $mm);
//echo $all;
        $arr = explode("==", $all);
        $arr1 = explode(',', $all);
        $count = count($arr);
        $ff = str_replace("==", ',', $arr1[0]);
        $data = Array();
        for ($i = 0; $i < $count - 1; $i++) {
            $stmt = $conn->prepare('SELECT SUM(savings_scheme+savings_discount) as total FROM claim WHERE Open=0 AND date_closed >=:dat1 AND date_closed<=:dat2 AND recordType IS NULL');
            if ($i == 0) {
                $d1 = explode(',', $ff);
                $datetime1 = strtotime($d1[0]);
                $datetime2 = strtotime($d1[1]);
                $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                $days = $secs / 86400;
                if ($days > 2) {
                    $stmt = $conn->prepare('SELECT SUM(savings_scheme+savings_discount) as total FROM claim WHERE Open=0 AND date_closed >=:dat1 AND date_closed<:dat2 AND recordType IS NULL');
                    $stmt->bindParam(':dat1', $d1[0], PDO::PARAM_STR);
                    $stmt->bindParam(':dat2', $d1[1], PDO::PARAM_STR);

                    $stmt->execute();
                    $amt = $stmt->fetchColumn();
                    array_push($data, $amt);
                }

            } else {
                $d1 = explode(',', $arr[$i]);
                $stmt->bindParam(':dat1', $d1[0], PDO::PARAM_STR);
                $stmt->bindParam(':dat2', $d1[1], PDO::PARAM_STR);
                $stmt->execute();
                $amt = $stmt->fetchColumn();
                array_push($data, $amt);

            }

        }
        $myArray = json_encode($data, JSON_NUMERIC_CHECK);
        echo("data:{$myArray}\n\n");
    }

    else if ($identity == 5)
    {
        $se = "%" . date("Y-m") . "%";
        $stmt = $conn->prepare('SELECT username,SUM(savings_scheme + savings_discount) as total FROM claim WHERE date_closed LIKE :dd GROUP BY username');
        $stmt->bindParam(':dd', $se, PDO::PARAM_STR);
        $stmt->execute();
        $myArray=json_encode($stmt->fetchAll(),true);
        echo("data:{$myArray}\n\n");
    }
       else if ($identity == 6)
    {
        $stmt = $conn->prepare('SELECT purple,red,yellow FROM email_configs WHERE 1 LIMIT 1');
        //$stmt->bindParam(':dd', $se, PDO::PARAM_STR);
        $stmt->execute();
        $rrow=$stmt->fetch();
        echo("data:".(int)$rrow[0]."--".(int)$rrow[1]."--".(int)$rrow[2]."\n\n");

    }

    else if ($identity == 7)
    {
        $stmt = $conn->prepare('SELECT COUNT(claim_id) FROM claim WHERE Open=1');
        //$stmt->bindParam(':dd', $se, PDO::PARAM_STR);
        $stmt->execute();
        echo("data:".$stmt->fetchColumn()."\n\n");
    }
}
else
{
    exit("There is an error");
}
function getSaturdays($y, $m)
{
    return new DatePeriod(
        new DateTime("first monday of $y-$m"),
        DateInterval::createFromDateString('next monday'),
        new DateTime("last day of $y-$m")
    );
}


function list_week_days($year, $month) {
    $first_month_day =  new DateTime("first day of $year-$month") ;
    $myAll= $first_month_day->format("Y-m-d==");
    foreach (getSaturdays($year, $month) as $saturday) {
        $myAll.= $saturday->format("Y-m-d");
        $myAll.= ',';
        $sunday  = $saturday->modify('next Sunday');
        $myAll.= $sunday->format("Y-m-d==");

    }
    $last_month_day =  new DateTime("last day of $year-$month");
    $myAll.= $last_month_day->format("Y-m-d\n");
    return $myAll;
}
flush();
?>