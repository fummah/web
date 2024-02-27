<?php
session_start();
$role=$_SESSION['level'];
$username=$_SESSION['user_id'];
$_SESSION["admin_main"]=true;
$_SESSION['start_db']=true;

$limit = 10;
$page = 1;
if (isset($_GET["page"])) {
  $page = (int)$_GET["page"];
} else {
  $page = 1;
};
$start_from = ($page - 1) * $limit;
$sql=$_GET['sql']. " LIMIT $start_from, $limit";
$sql1=$_GET['sql'];
$username=$_GET['name'];
$dat=$_GET['dat'];
$holidays=array("01-01","03-21","04-15","04-18","04-27","05-01","06-16","08-09","09-24","12-16","12-25","12-26");
$today = date('Y-m-d H:i:s');
echo $username."<hr>";
require_once "../dbconn1.php";
$conn=connection("mca","MCA_admin");
?>
<html>
<head>

  <title>MCA : Claims</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../bootstrap3/css/bootstrap.min.css">
  <script src="../jquery/jquery.min.js"></script>
  <script src="../bootstrap3/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../js/simplePagination.css" />
  <script src="../js/jquery.simplePagination.js"></script>
  <link href="../w3/w3.css" rel="stylesheet" />
  <link rel="stylesheet" href="../uikit/css/uikit.min.css" />
  <script src="../uikit/js/uikit.min.js"></script>
  <script src="../uikit/js/uikit-icons.min.js"></script>
</head>
<table class="uk-table uk-table-striped" width="50%">
  <thead>
  <tr>
    <th>Claim Number</th>
    <th>Username</th>
    <th>Date and Time</th>
    <th>SLA Started On</th>
    <th>Days</th>
    <th></th>
  </tr>
  </thead>
  <tbody>
  <?php
  try {
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt1->bindParam(':dat', $dat, PDO::PARAM_STR);
    $stmt1->execute();
    $total_records=$stmt1->rowCount();

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':dat', $dat, PDO::PARAM_STR);
    $stmt->execute();

    $total_records1=$stmt->rowCount();
    if($total_records1>0) {
      foreach ($stmt->fetchAll() as $row) {

        $claim_id=$row[0];
        $from_date=$row[3];
        $pre_date=$row[6];
        $present="no";
        $stmtp = $conn->prepare('SELECT date_entered FROM intervention WHERE date_entered<:ddat AND (claim_id=:num OR claim_id1=:num) ORDER BY intervention_id DESC LIMIT 1');
        $stmtp->bindParam(':num', $claim_id, PDO::PARAM_STR);
        $stmtp->bindParam(':ddat', $pre_date, PDO::PARAM_STR);
        $stmtp->execute();
        if($stmtp->rowCount()>0)
        {
          $from_date=$stmtp->fetchColumn();
          $present="yes";
        }
        $claim_number=$row[1];
        $open=(int)$row[2];
        $user=$row[4];
        $starx="";
        if($open==0){
          $starx="(Closed)";

        }
        $date_number=getWorkingDays($from_date,$pre_date,$holidays);

        $star="";
        if($date_number<2){$star="<span style='color: darkseagreen'>*</span>";}
        elseif($date_number>=2 && $present=="no"){$star="<span style='color: purple'>*</span>";}
        elseif($date_number==2){$star="<span style='color: orange'>*</span>";}
        elseif($date_number>2){$star="<span style='color: red'>*</span>";}
        echo "<td>$claim_number $star $starx</td>";
        echo "<td>$user</td>";
        echo "<td>$pre_date</td>";
        echo "<td>$from_date</td>";
        echo "<td>$date_number</td>";
        echo "<td>";
        echo "<form action='../case_detail.php' method='post' target='_blank' />";
        echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
        echo "<input type=\"hidden\" name=\"sla\" value=\"\" />";
        echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"View\">";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
      }
    }
    else{
      echo "<tr style='background-color: white'><td colspan='7' class='uk-text-light'>No records</td></tr>";
    }

  } catch (Exception $e) {
    return "There is an error.";
  }

  ?>
  </tbody>
</table>
<?php

$total_pages = ceil($total_records / $limit);
$pagLink = "<nav><ul class='pagination'>";
for ($i=1; $i<=$total_pages; $i++) {
  $pagLink .= "<li><a href='showclaim.php?sql=".$sql1."&name=".$username."&dat=".$dat."&page=".$i."'>".$i."</a></li>";
};
echo $pagLink . "</ul></nav>";
?>

</div>

</body>
</html>

<?php
function getWorkingDays($initialDate,$finalDate,$holiday)
{
  $hours=getWorkingDaysWorking($initialDate,$finalDate,$holiday);
  $mydays=$hours/8;
  $daysx=ceil($mydays);
  $date = new DateTime($initialDate);
  $datformat=$date->format('Y-m-d');
  $todaydate=date("Y-m-d");
  $days=$datformat==$todaydate?1:$daysx;
  return $days;
}
function getWorkingDaysWorking($initialDate,$finalDate,$holiday)
{
  $noofholiday = sizeof($holiday);     //no of total holidays

//create all required date time objects
  $firstdate = DateTime::createFromFormat('Y-m-d H:i:s', $initialDate);
  $lastdate = DateTime::createFromFormat('Y-m-d H:i:s', $finalDate);
  if ($lastdate > $firstdate) {
    $first = $firstdate->format('Y-m-d');
    $first = DateTime::createFromFormat('Y-m-d H:i:s', $first . " 00:00:00");
    $last = $lastdate->format('Y-m-d');
    $last = DateTime::createFromFormat('Y-m-d H:i:s', $last . " 23:59:59");
    $workhours = 0;   //working hours

    for ($i = $first; $i <= $last; $i->modify('+1 day')) {
      $holiday = false;
      for ($k = 0; $k < $noofholiday; $k++)   //excluding holidays
      {
        $myholiday=date("Y")."-";
        if ($i == $myholiday.$holiday[$k]) {
          $holiday = true;
          break;
        }
      }
      $day = $i->format('l');
      if ($day === 'Saturday' || $day === 'Sunday')  //excluding saturday, sunday
        $holiday = true;

      if (!$holiday) {
        $ii = $i->format('Y-m-d');
        $f = $firstdate->format('Y-m-d');
        $l = $lastdate->format('Y-m-d');
        if ($l == $f)
          $workhours += sameday($firstdate, $lastdate);
        else if ($ii === $f)
          $workhours += firstday($firstdate);
        else if ($l === $ii)
          $workhours += lastday($lastdate);
        else
          $workhours += 8;
      }
    }

    return $workhours;   //echo the hours
  } else
    return "lastdate less than first date";
}
function sameday($firstdate,$lastdate)
{
  $fmin = $firstdate->format('i');
  $fhour = $firstdate->format('H');
  $lmin = $lastdate->format('i');
  $lhour = $lastdate->format('H');
  if($fhour >=12 && $fhour <14)
    $fhour = 14;
  if($fhour <8)
    $fhour =8;
  if($fhour >=18)
    $fhour =18;
  if($lhour<8)
    $lhour=8;
  if($lhour>=12 && $lhour<14)
    $lhour = 14;
  if($lhour>=18)
    $lhour = 18;
  if($lmin == 0)
    $min = ((60-$fmin)/60)-1;
  else
    $min = ($lmin-$fmin)/60;
  return $lhour-$fhour + $min;
}

function firstday($firstdate)   //calculation of hours of first day
{
  $stmin = $firstdate->format('i');
  $sthour = $firstdate->format('H');
  if($sthour<8)   //time before morning 8
    $lochour = 8;
  else if($sthour>18)
    $lochour = 0;
  else if($sthour >=12 && $sthour<14)
    $lochour = 4;
  else
  {
    $lochour = 18-$sthour;
    if($sthour<=14)
      $lochour-=2;
    if($stmin == 0)
      $locmin =0;
    else
      $locmin = 1-( (60-$stmin)/60);   //in hours
    $lochour -= $locmin;
  }
  return $lochour;
}

function lastday($lastdate)   //calculation of hours of last day
{
  $stmin = $lastdate->format('i');
  $sthour = $lastdate->format('H');
  if($sthour>=18)   //time after 18
    $lochour = 8;
  else if($sthour<8)   //time before morning 8
    $lochour = 0;
  else if($sthour >=12 && $sthour<14)
    $lochour = 4;
  else
  {
    $lochour = $sthour - 8;
    $locmin = $stmin/60;   //in hours
    if($sthour>14)
      $lochour-=2;
    $lochour += $locmin;
  }
  return $lochour;
}
?>
<script type="text/javascript">
  $(document).ready(function(){
    $('.pagination').pagination({
      items: <?php echo $total_records;?>,
      itemsOnPage: <?php echo $limit;?>,
      cssStyle: 'light-theme',
      currentPage : <?php echo $page;?>,
      hrefTextPrefix : 'showclaim.php?sql=<?php echo $sql1;?>&name=<?php echo $username;?>&dat=<?php echo $dat;?>&page='
    });
  });
</script>

