<?php
session_start();
define("access",true);
include("dbconn.php");
$conn = connection("mca", "MCA_admin");
function tt($claim_id)
{
    global $conn;
$stmtx = $conn->prepare('Select claim_id,date_entered FROM intervention WHERE claim_id=:claim_id');
$stmtx->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
$stmtx->execute();
return $stmtx->fetchAll();
}
function holidays()
{
    return array("2024-01-01","2024-03-21","2024-03-29","2024-04-01","2024-04-27","2024-05-01","2024-06-17","2024-08-09","2024-09-24","2024-12-16","2024-12-25","2024-12-26");
}
function getWorkingHours($startDate, $endDate) {
    $startWorkingHour = 8;
    $endWorkingHour = 16;
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
    $workingDays = 0;
    $currentDate = $startDate;
    while ($currentDate <= $endDate+86400) {
        $dayOfWeek = date('N', $currentDate);
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
            $currentDateFormatted = date('Y-m-d', $currentDate);
            if (!in_array($currentDateFormatted, holidays())) {           
                $startTime = strtotime(date('Y-m-d', $currentDate) . " $startWorkingHour:00:00");
                $endTime = strtotime(date('Y-m-d', $currentDate) . " $endWorkingHour:00:00");
                $startTime = $startTime<$startDate? $startDate : $startTime;
                if((int)date('H', $startTime)<$endWorkingHour){
                $endTime = $endTime>$endDate? $endDate : $endTime;
                if($startTime<$endTime){
                  //$workingHours = floor(($endTime - $startTime) / 3600); 
                  $workingHours = ($endTime - $startTime) / 3600; 
                  $workingDays += $workingHours;
                }              
             }
            }          
        }      
        $currentDate = strtotime('+1 day', $currentDate);
    }
    return $workingDays;
  }
  function myClaims($start_date,$end_date)
  {
    global $conn;
    $stmt = $conn->prepare('Select a.claim_id,a.claim_number,a.date_entered,a.username,c.client_name FROM 
    claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.date_entered>:dat1 
    AND a.date_entered < :dat2 AND b.client_id IN (3,16)');
    $stmt->bindParam(':dat1', $start_date, PDO::PARAM_STR);
    $stmt->bindParam(':dat2', $end_date, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
  }
  $rows = myClaims("2024-04-01","2024-05-01");
$tot = count($rows);
echo "<h2>Total : $tot</h2>";
echo"<table border='1'><tr><th>Claim Number</th><th>Username</th><th>Client</th><th>Date Entered</th><th>SLA Date</th><th>Number of Days for SLA</th></tr>";
foreach($rows as $row)
{
    $claim_id = $row['claim_id'];
    $claim_number = $row['claim_number'];
    $date_entered = $row['date_entered'];    
    $username = $row['username'];  
    $client_name = $row['client_name'];  
    $tlines = tt($claim_id);
    $sal_date = "---";
    $hours = "--";
    if(count($tlines)<1)
    {
        $sal_date = date('Y-m-d H:i:s');
        $hours = getWorkingHours($sal_date,$date_entered);
    }
echo "<tr><td>$claim_number</td><td>$username</td><td>$client_name</td><td>$date_entered</td><td>$sal_date</td><td> $hours</td></tr>";
$sal_date = $date_entered;
foreach ($tlines as $t)
{
    $interv_date = $t["date_entered"];
    $hours = getWorkingHours($sal_date,$interv_date);
    echo "<tr style='background-color:green;color:white'><td>$claim_number</td><td>$username</td><td>$username</td><td>$interv_date</td><td>$sal_date</td><td> $hours</td></tr>"; 
    $sal_date = $interv_date;
}
}
echo "</table>";
?>