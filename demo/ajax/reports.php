<?php
error_reporting(0);
define("access",true);
session_start();
include ("../classes/reportsClass.php");
$results=new reportsClass();
$holidays=$results->getHolidays();
$role=$results->myRole();
$mcausername=$results->loggedAs();
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$valx=$role=="admin" || $role=="controller"?"1":$mcausername;
$identity = (int)validateXss($_GET['identityNum']);
if($identity==1)
{
    $type=$_GET["type"];
    if($type=="cs")
    {
    $r=json_encode($results->usersCase($condition,$valx),true);
}
else
{   
    $r=json_encode($results->clientsCase($condition,$valx),true);
}
 echo $r;
}
elseif($identity==2)
{
    $r=json_encode($results->clientsCase($condition,$valx),true);
    echo $r;
}
elseif ($identity==3)
{
    $type=$_GET["type"];
    $r=json_encode($results->trend_claims($condition,$valx,$type),true);
    echo $r;
}
elseif ($identity==4)
{

    $r=json_encode($results->clients_trend_claims($condition,$valx),true);
    echo $r;

}
elseif ($identity==5)
{

    $r=json_encode($results->users_trend_claims($condition,$valx),true);
    echo $r;

}
elseif ($identity==6)
{
    $r=json_encode($results->getrealDoctors(7),true);
    echo $r;

}
elseif ($identity==7)
{
    //$type=$_GET["type"];
    $r=json_encode($results->savingsTrend(7,$condition,$valx,),true);
    echo $r;

}
elseif ($identity==8)
{
    $r=json_encode($results->savingspercentageTrend(7,$condition,$valx),true);
    echo $r;

}
elseif ($identity==9)
{
    $r=json_encode($results->trend_savings($condition,$valx),true);
    echo $r;

}
elseif ($identity==10)
{
    $r=json_encode($results->trend_users($condition,$valx),true);
    echo $r;

}
elseif ($identity==11)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];

    $r=json_encode($results->analysisCase($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx),true);
    // $r=$results->analysisCase($val,$clients,$users);
    echo $r;

}
elseif ($identity==12)
{
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $val=$_GET["val"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];

    $r=json_encode($results->analysisSavings($val,$clients,$users,$start_date,$end_date,$condition,$valx),true);
    // $r=$results->analysisCase($val,$clients,$users);
    echo $r;
}

elseif ($identity==13)
{

    $client=$_GET["client"];
    $number=isset($_GET["month"])?$_GET["month"]:12;
    $doc=isset($_GET["current"])?$_GET["current"]:0;

    $header="<table width='100%' class='table table-striped table-valign-middle'><thead><tr><th>Year-Month</th><th>Claims Closed</th><th>Savings by Dr Discount</th><th>Savings by Scheme Paid</th><th>Total Savings</th><th>Value of Claims Referred</th>
<th>Percentage saved (of total referred)</th><th>Average time to close (days)</th><th>Claims Referred</th></tr></thead><tbody>";
    echo $header;

    $full_array=json_encode(array_reverse($results->reports($number,$client,$doc)),true);
    foreach (array_reverse($results->reports($number,$client,$doc)) as $row)
    {
        $vv="";
        if($row["month"]=="")
        {
            $vv="style='font-weight:bolder'";
        }

        $discount=$results->format($row["discount"]);
        $scheme=$results->format($row["scheme"]);
        $total_savings=$results->format($row["total_savings"]);
        $charged=$results->format($row["charged"]);
        echo "<tr $vv><td style='cursor:pointer; color:green'><a onclick=\"window.open('cs_analysis.php?month=$row[month]&client=$client','popup','width=1000,height=600'); return false;\">$row[month]</a></td><td>$row[claims]</td><td>$discount</td><td>$scheme</td><td>$total_savings</td><td>$charged</td><td>$row[percentage]</td><td>$row[average]</td><td>$row[total_referred]</td></tr>";


    }
    echo "</tbody><caption><form method='post' action='download_summary_report.php'><input type='hidden' name='ctxt' value='$client'><input type='hidden' name='txt' value='$full_array'><button class='btn btn-info'><i class=\"fas fa-download\"></i> Download Excel</button></form></caption></table>";

}

elseif ($identity==14)
{
    $target=(double)$_GET["target"];
    $closed_target=(int)$_GET["closed_target"];
    $entered_target=(int)$_GET["entered_target"];

    if($target>10000)
    {
        $month=date("F");
        $entered_by=$_SESSION["user_id"];
        $output=$results->insertTarget($month,$target,$entered_by,$closed_target,$entered_target);
        $str=$output==1?"Target successfully updated.":"Failed to update target.";
    }
    else
    {
        $str="Invalid Amount";
    }
    echo"<span class=\"alert alert-secondary\"> $str</span>";
}
elseif ($identity==15)
{
    $frmdate=$_GET["from_client"]."-01";
    $todate=$_GET["to_client"]."-31";
    $header="<table border='1' class='table table-bordered'><thead><tr><th>Dat.Closed</th><th>User</th><th>Tot.Closed</th><th>Total.Received</th><th>Avg closed/day</th><th>Avg worked/month</th>
<th>Avg worked/day</th><th>Wrkng Days</th><th>Avg Days</th><th>Claims Value</th><th>Savings</th><th>Perc.%</th></tr></thead><tbody>";
    echo $header;

    //print_r($results->selectDates($frmdate,$todate));
    try {
        foreach ($results->selectDates($frmdate,$todate) as $row)
        {
            $mydate=$row[0];
            $strdate=$mydate."-01";
            $startDate=$strdate;

            $d = new DateTime( $strdate );
            $endDate=$d->format( 'Y-m-t' );
            if($mydate==date("Y-m"))
            {
                $startDate=date("Y-m")."-01";
                $endDate=date("Y-m-d");
            }
            $days=$results->getWorkingDaysx($startDate,$endDate,$holidays);
            foreach ($results->getSpecialists($role,$mcausername) as $row1)
            {
                $myuser=$row1[0];
                $data=$results->getAverageList($mydate,"username=:username",$myuser);               
                $number_closed=$data["closedthisClaims"];
                $number_entered=$data["enteredthisClaims"];
                $number_workedon=$results->casesWorkedOn($mydate,"owner=:username",$myuser);
                $average_closed=round($number_closed/$days);
                $average_worked=round($number_workedon/$days);
                $claims_value=$data["claimValue"];
                $savings=$data["savingsMain"];
                $myaar=$results->reopenedCases("",$mydate,$myuser);
                $numclosed=$results->closedDate("username=:username",$myuser,$mydate);
                //$numclosed=3;
                $average_days=$number_closed>0?round($numclosed/$number_closed):0;
                $average_days=(int)$average_days;
                $firstsavings=(double)$myaar["total1"];
                $savings-=$firstsavings;
                $perc=$claims_value>0?round(($savings/$claims_value)*100):0;
                $claims_value=number_format($claims_value,2,'.',' ');
                $savings=number_format($savings,2,'.',' ');
                echo "<tr><td>$mydate</td><td>$myuser</td><td>$number_closed</td><td>$number_entered</td><td>$average_closed</td><td>$number_workedon</td><td>$average_worked</td><td>$days</td><td>$average_days</td><td>$claims_value</td><td>$savings</td><td>$perc</td></tr>";

            }


        }

    } catch (Exception $rr) {
        echo $rr->getMessage();

    }
    echo "</tbody></table>";

}
elseif ($identity==16)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];

    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";

    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];

    $r=json_encode($results->analysisQA($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx),true);
    //$r=$results->analysisCase($val,$clients,$users);
    echo $r;

}

elseif ($identity==17)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];

    $r=json_encode($results->marksQA($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx),true);
    // $r=$results->analysisCase($val,$clients,$users);
    echo $r;

}
elseif ($identity==18)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];

    $r=json_encode($results->marksQATrend($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx),true);
    // $r=$results->analysisCase($val,$clients,$users);
    echo $r;

}
elseif ($identity==19)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $user=$_GET["user"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];

    $r=$results->getPercTotals($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx,$user);
    // $r=$results->analysisCase($val,$clients,$users);
    echo $r;

}
elseif ($identity==20)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $user=$_GET["user"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];
//echo "".$val."----".$val1."=======".$clients."pppppp".$users."++++++".$start_date."===".$end_date;
    $r=$results->aiQA($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx,$user);
    echo $r;

}
elseif ($identity==21)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $clients_web=$_GET["clients"];
    $users_web=json_decode($_GET["users"]);
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    if($role=="admin" || $role=="controller")
    {
        $users=!empty($users_web)?implode(",",$users_web):"";
    }
    else{
        $users=!empty($users_web)?implode(",",$users_web):$mcausername;
    }

    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];
//echo $val."====".$val1."-------".$clients."****".$users."&&&&&".$start_date."----".$end_date;
    $r=json_encode($results->qaPercentages($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx),true);
    // $r=$results->analysisCase($val,$clients,$users);
    echo $r;

}
elseif ($identity==22)
{
    $val=$_GET["val"];
    $val1=$_GET["val1"];
    $clients_web=json_decode($_GET["clients"]);
    $users_web=json_decode($_GET["users"]);
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];
    $user=$results->loggedAs();
    $r=$results->getIncentive($val,$clients,$users,$start_date,$end_date,$val1,$condition,$valx,$user);
    foreach ($r as $row)
    {
        $username=$row["username"];
        $qa=(int)$row["qa"];
        $savings=(double)$row["savings"];
        $closed_claims=(int)$row["closed_claims"];
        $descr_savings="-";
        $descr_claims="-";
        $descr_qa="-";

        $savings1="-";
        $pers=0;
        if($savings<10){ $savings1="5";$pers = 0;$descr_savings="Less than 10 perc";}
        elseif ($savings>=10 && $savings<=12){$savings1="4";$pers = 6.25;$descr_savings="10 to 12 perc";}
        elseif ($savings>12 && $savings<=14){$savings1="3";$pers = 8.33;$descr_savings="13 to 14 perc";}
        elseif ($savings>14 && $savings<=16){$savings1="2";$pers = 12.50;$descr_savings="15 to 16 perc";}
        elseif ($savings>16){$savings1="1";$pers = 25;$descr_savings="17 and Above";}

        // QA
        $qa1="-";
        $perq=0;
        if($qa<80){ $qa1="5";$perq = 0;$descr_qa="Less than 80";}
        elseif ($qa>=80 && $qa<=84){$qa1="4";$perq = 12.50;$descr_qa="80 to 84";}
        elseif ($qa>84 && $qa<=89){$qa1="3";$perq = 16.67;$descr_qa="85 to 90";}
        elseif ($qa>89 && $qa<=94){$qa1="2";$perq = 25;$descr_qa="90 to 94";}
        elseif ($qa>94){$qa1="1";$perq = 50;$descr_qa="95 and Above";}

        //Closed claims
        $closed_claims1="-";
        $perc=0;
        if($closed_claims<180){ $closed_claims1="5";$perc = 0;$descr_claims="Less than 180";}
        elseif ($closed_claims>=180 && $closed_claims<=184){$closed_claims1="4";$perc = 6.25;$descr_claims="180";}
        elseif ($closed_claims>184 && $closed_claims<=189){$closed_claims1="3";$perc = 8.33;$descr_qa="-";$descr_claims="185";}
        elseif ($closed_claims==190){$closed_claims1="2";$perc = 12.50;$descr_claims="190";}
        elseif ($closed_claims>190){$closed_claims1="1";$perc = 25;$descr_claims="190 and Above";}

        $overal=$pers+$perq+$perc;
        $overal = number_format($overal, 2, '.', ',');
        $pers = number_format($pers, 2, '.', ',');
        $perc = number_format($perc, 2, '.', ',');
        $perq = number_format($perq, 2, '.', ',');

        echo "<h3>$username</h3><table class=\"table table-striped\">
              <tr><td></td><td>Target</td><td>Weighting</td><td>Overall%</td></tr>
              <tr><td>Savings</td><td>$descr_savings</td><td>$savings1</td><td>$pers%</td></tr>
              <tr><td>Closed</td><td>$descr_claims</td><td>$closed_claims1</td><td>$perc%</td></tr>
              <tr><td>QA</td><td>$descr_qa</td><td>$qa1</td><td>$perq%</td></tr>
              <tr><td colspan=\"3\"></td><td>$overal%</td></tr></table>";

        echo "<hr>";

    }

}
elseif ($identity==23)
{
    $switch=0;
    $today=$_GET["month"];
    $allarr=array();
    foreach ($results->getClientsWithSavings($today) as $row) {
        $client = $row["client_name"];
        $id = $row["client_id"];
        $base_fee = (double)$row["base_fee"];
        $threshold = (double)$row["threshold"];
        $threshold1 = (double)$row["threshold1"];
        $savings = (double)$row["savings"];
        $myaar=$results->reopenedCases($client,$today);
        $firstsavings=(double)$myaar["total1"];
        $vari=(double)$myaar["vari"];
        $vari=0;
        $actualsavings = $savings-$firstsavings+$vari;
        $vatexcl1 = round($actualsavings*(100/115),2);
        $vatexcl=$actualsavings;
        $ssw = $results->switchProd($id,$today) + $results->switchSeamless($id,$today);
        $ccochf= $client=="Kaelo" || $client=="Western" || $client=="Sanlam"?(int)$results->getSwitchCHF($client,0,$today):0;
        //$ssw = 0;
        //$ccochf=0;
        $switch += $switch;
        $variance = $vatexcl - $threshold;
        $subtract = 0.0;
        $cl = "text-info";
        $caret = "ti-angle-double-left";
        if ($savings < $actualsavings) {
            $cl = "text-success";
            $caret = "ti-angle-double-up";
            $subtract = $savings - $actualsavings;
        } elseif ($savings > $actualsavings) {
            $cl = "text-danger";
            $caret = "ti-angle-double-down";
            $subtract = $savings - $actualsavings;
        }
        $perc25 = 0.00;
        $perc30 = 0.00;
        $variance1=0.00;
        if ($threshold > 0 && $vatexcl > $threshold1) {
            $perc25=$vatexcl<200000?$vatexcl-$threshold1:$threshold1;

            $perc25 = $perc25 * 0.25;
        }
        if($threshold1>0)
        {
            $variance1=$vatexcl<200000?$vatexcl-$threshold1:$threshold1;
        }
        if ($variance > 0 && $threshold > 0) {
            $perc30 = $variance * 0.30;
        } else {
            if ($threshold < 1) {
                $variance = 0.00;
            }
        }


        $client_arr=["Sanlam","Western","Kaelo"];
        if($client=="Sanlam")
        {
            $perc30=$vatexcl*0.30;
        }
        if($client=="Netcareplus")
        {
            $perc30=round($actualsavings*(1/3),2);
        }
        if($client=="Turnberry")
        {
            $variance1=$actualsavings-$threshold1;
            $perc25=$actualsavings>$threshold1?($variance1)*0.25:0.00;
        }
        if($client=="Gaprisk_administrators")
        {
            $perc30=$actualsavings*0.33;
        }

        $savings = $results->reformat($savings);
        $vatexcl = $results->reformat($vatexcl);
        $actualsavings = $results->reformat($actualsavings);
        $base_fee = $results->reformat($base_fee);
        $threshold = $results->reformat($threshold);
        $variance = $results->reformat($variance);
        $variance1 = $results->reformat($variance1);
        $subtract = $results->reformat($subtract);
        $perc25 = $results->reformat($perc25);
        $perc30 = $results->reformat($perc30);
        $threshold1 = $results->reformat($threshold1);
        if(!in_array($client,$client_arr))
        {
            $vatexcl="--";
        }
        $arr=[
            "client"=>$client,"savings"=>$savings,"cl"=>$cl,"caret"=>$caret,"actualsavings"=>$actualsavings,
            "vatexcl"=>$vatexcl1,"base_fee"=>$base_fee,"variance"=>$variance,"variance1"=>$variance1,"perc25"=>$perc25,"perc30"=>$perc30,"threshold"=>$threshold,"threshold1"=>$threshold1,"switch_number"=>$ssw,
            "client_id"=>$id,"total_switch"=>$switch,"chf"=>$ccochf
        ];
        array_push($allarr,$arr);
    }
    echo json_encode($allarr);
}

elseif($identity==24)
{
$today=date("Y-m");
$open_claims=$results->openClaims($condition,$valx);
$closed_claims=$results->closedthisClaims($today,$condition,$valx);
$new_claims=$results->newClaims($condition,$valx); 
$arr=array("open_claims"=>$open_claims,"closed_claims"=>$closed_claims,"new_claims"=>$new_claims);
echo json_encode($arr,true);
}
elseif($identity==25)
{
$this_month=date("Y-m");
$last_month= date("Y-m", strtotime("-1 months"));
$average=(int)$results->getAveragethisClaims($this_month,$condition,$valx);
$ratio=$results->getMyRecentClaims($last_month,$this_month,$condition,$valx);
//print_r($ratio);
$claims1=(int)$ratio["last_month"];
$claims2=(int)$ratio["this_month"];
$perc=($claims2/$claims1)*100;
if($perc>=100)
{
    $claims_per=$perc-100;
    $last_perc="Down";
    $this_perc="Up";  
    $arrow1="ti-arrow-up"; 
    $arrow2="ti-arrow-down";  
}
else
{
    $claims_per=100-$perc;
    $last_perc="Up";
    $this_perc="Down";  
     $arrow1="ti-arrow-down"; 
    $arrow2="ti-arrow-up";    
}
$reopened=$results->getReopened($condition,$valx);
$arr=array("average"=>$average,"claims_per"=>round($claims_per,2),"last_perc"=>$last_perc,"this_perc"=>$this_perc,"arrow1"=>$arrow1,"arrow2"=>$arrow2,"claims1"=>$claims1,"claims2"=>$claims2,"reopened"=>$reopened);
echo json_encode($arr,true);
}
elseif($identity==26)
{
    $today=date("Y-m");
    $hhed="";
    $condition1=$role=="admin" || $role=="controller"?":username":"a.username=:username";
$tyt=["badge bg-success","badge bg-danger","badge bg-warning","badge bg-info"];

                      foreach ($results->selectAllusers($condition1,$valx) as $names)
                      {
                        $name=$names[0];
                        echo "<tr><td>$name</td>";
                        for($j=0;$j<4;$j++)
                        {
                          $today = date("Y-m-d", strtotime( '-1 days' ) );
                          $results->sql1="SELECT b.claim_id,b.claim_number,b.Open, intervention_id,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";
                          $results->sql2="SELECT DISTINCT a.claim_id,b.claim_number,b.Open,b.date_entered,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";
                          $results->sql3="SELECT claim_id,claim_number,Open,date_entered,username,date_closed,recent_date_time FROM `claim` WHERE `recent_date_time` >= :dat AND username=:username AND Open<>2";
                          $results->sql4="SELECT DISTINCT a.claim_id,a.claim_number,b.Open,b.date_entered,owner,b.date_closed,a.date FROM `logs` as a INNER JOIN claim as b ON a.claim_id=b.claim_id WHERE a.date >= :dat AND a.owner=:username";

                          $totalClaims="SELECT DISTINCT a.claim_id,b.claim_number,b.Open FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered >= :dat AND b.username=:username AND b.Open<>2";

                          if($j==1) {

                            $today = date("Y-m-d");
                          }
                          elseif ($j==2)
                          {

                            $today = date("Y-m-d", strtotime('monday this week'));
                          }
                          elseif ($j==3)
                          {
                            $today = date("Y-m-01");
                          }
                          else
                          {
                            $results->sql1="SELECT b.claim_id,b.claim_number,b.Open, intervention_id,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered LIKE :dat AND b.username=:username AND b.Open<>2";
                            $results->sql2="SELECT DISTINCT a.claim_id,b.claim_number,b.Open,b.date_entered,owner,b.date_closed,a.date_entered FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered LIKE :dat AND b.username=:username AND b.Open<>2";
                            $results->sql3="SELECT claim_id,claim_number,Open,date_entered,username,date_closed,recent_date_time FROM `claim` WHERE `recent_date_time` LIKE :dat AND username=:username AND Open<>2";
                            $results->sql4="SELECT DISTINCT a.claim_id,a.claim_number, b.Open,b.date_entered,owner,b.date_closed,a.date FROM `logs` as a INNER JOIN claim as b ON a.claim_id = b.claim_id WHERE `date` LIKE :dat AND a.owner=:username";
                            $totalClaims="SELECT DISTINCT a.claim_id,b.claim_number,b.Open FROM `intervention` as a INNER JOIN claim as b ON a.claim_id=b.claim_id where a.date_entered LIKE :dat AND b.username=:username AND b.Open<>2";
                            $today=$today."%";
                          }
                          $d = $results->selectuser($name, $today, $results->sql1);
                          $d2 = $results->selectuser($name, $today, $totalClaims);
                          $d1 = $results->selectuser($name, $today, $results->sql3);
                          $d3 = $results->selectuser($name, $today, $results->sql4);
                          $mesg1="showclaim.php?sql=".$results->sql2."&dat=".$today."&name=".$name;
                          $mesg2="showclaim.php?sql=".$results->sql3."&dat=".$today."&name=".$name;
                          $mesg3="showclaim.php?sql=".$results->sql4."&dat=".$today."&name=".$name;

                          $d = "<span class='$tyt[1]' style='cursor: pointer' onclick=\"window.open('$mesg1','popup','width=800,height=600'); return false;\">$d/$d2</span>";
                          $d1 = "<span class='$tyt[2]' style='cursor: pointer' onclick=\"window.open('$mesg2','popup','width=800,height=600'); return false;\">$d1</span>";
                          $d2 = "<span class='$tyt[3]' style='cursor: pointer' onclick=\"window.open('$mesg3','popup','width=800,height=600'); return false;\">$d2</span>";
                          $hhed = "<div style='color: #0c85d0;' class='row'><div class='col-4 border-right'>$d</div><div class='col-4  border-right'>$d1</div><div class='col-4' style='cursor: pointer' onclick=\"window.open('$mesg3','popup','width=800,height=600'); return false;\">$d3</div></div>";
                          echo "<td>$hhed</td>";

                        }
                        echo "<tr>";
                      }
    }

    elseif($identity==27)
    {
        $date=date("Y-m");
        $tr="";
        foreach($results->getSchemeSummary($date,$condition,$valx) as $row)
        {
            $medical_scheme=$row["val_name"];
            $total=$row["total"];
            $scheme_option_arr=$results->getSchemeOption($medical_scheme,$date,$condition,$valx);
            $scheme_option=$scheme_option_arr[0];
            $tr.="<tr><td><div class='payment_gatway'>";
            $tr.="<h5 class='byer_name  f_s_16 f_w_600 color_theme2'><span class='badge bg-info'>$total</span> $medical_scheme</h5>";
            $tr.="<p class='color_gray f_s_12 f_w_700'>$scheme_option</p></div></td>";
            $tr.="</tr>";            
        }
        echo $tr;
    }
    elseif($identity==28)
    {
        $date=date("Y-m");
        $tr="";
        foreach($results->getTariffSummary($date,$condition,$valx) as $row)
        {
            $tariff_code=$row["tariff_code"];
            $total=$row["total"];
            $description=$row["Description"];
            $tr.="<div class='single_update_news'>";
            $tr.="<h5 class='byer_name  f_s_16 f_w_600 color_theme2'><span class='badge bg-warning'>$total</span>  $tariff_code</h5>";
            $tr.="<p class='color_gray f_s_12 f_w_700'>$description</p>";
            $tr.="</div>";               
        }
        echo $tr;
    }
    elseif($identity==29)
    {
        $date=date("Y-m");
        $tr="";
        //print_r($results->getICD10Summary($date,$condition,$valx));
        foreach($results->getICD10Summary($date,$condition,$valx) as $row)
        {
            $primaryICDCode=$row["primaryICDCode"];
            $total=$row["total"];
            $description=$row["shortdesc"];
            $tr.="<div class='single_update_news'>";
            $tr.="<h5 class='byer_name  f_s_16 f_w_600 color_theme2'><span class='badge bg-success'>$total</span>  $primaryICDCode</h5>";
            $tr.="<p class='color_gray f_s_12 f_w_700'>$description</p>";
            $tr.="</div>";               
        }
        echo $tr;
    }
    elseif($identity==30)
    {
        $date=date("Y-m");
        $row=$results->pmbPerc($date,$condition,$valx);
        $pmb=(int)$row["pmb"];
        $non_pmb=(int)$row["non_pmb"];
        $total=$pmb+$non_pmb;
         $pmb_perc=round(($pmb/$total)*100);
        $non_pmb_perc=round(($non_pmb/$total)*100);
        $tr="<div id='bar1' class='barfiller' title='PMB Percentage'>";
        $tr.="<div class='tipWrap' style='display: inline;'>";
        $tr.="<span class='tip' style='left: $pmb_perc%;; transition: left 2s ease-in-out 0s;'>$pmb_perc%</span></div>";
        $tr.="<span class='fill' data-percentage='$pmb_perc' style='background: rgb(255, 191, 67); width: $pmb_perc%; transition: width 2s ease-in-out 0s;'></span></div>";
               $tr.="<div id='bar2' class='barfiller' title='Non-PMB Percentage'>";
        $tr.="<div class='tipWrap' style='display: inline;'>";
        $tr.="<span class='tip' style='left: $pmb_perc%;; transition: left 2.1s ease-in-out 0s;'>$non_pmb_perc%</span></div>";
        $tr.="<span class='fill' id='non_pmb_perc' data-percentage='$non_pmb_perc' style='background: rgb(80, 143, 244); width: $non_pmb_perc%; transition: width 2.1s ease-in-out 0s;''></span></div>";

        echo $tr;

    }
        elseif($identity==31)
    {
        $dd=date("Y-m-01");
$d = new DateTime( $dd );
$d->modify( '-1 month' );
$date= $d->format( 'Y-m' );
$today=date("Y-m");
$dat="%".$today."%";
$dat1="%".$date."%";
$current_savings=$results->currentSavings($dat,"",$condition,$valx);
$last_month_savings=$results->currentSavings($dat1,"",$condition,$valx);
$subtract=0;
$cl="text-warning";
$caret="ti-arrow-left";
if($current_savings>$last_month_savings)
{
  $cl="text-success";
  $caret="ti-arrow-up";
  $subtract=$current_savings-$last_month_savings;

}
elseif ($current_savings<$last_month_savings)
{
  $cl="text-danger";
  $caret="ti-arrow-down";
  $subtract=$current_savings-$last_month_savings;

}
$tot_amnt=number_format($current_savings,2,'.',',');
$subtract=number_format($subtract,2,'.',',');
//Rercentage here
$subtract1=0;
$perCurrent=$results->calcPerc($dat,"",$condition,$valx);
$perLast=$results->calcPerc($dat1,"",$condition,$valx);
$cl1="text-warning";
$caret1="ti-arrow-left";

if($perCurrent>$perLast)
{
  $cl1="text-success";
  $caret1="ti-arrow-up";
  $subtract1=$perCurrent-$perLast;

}
elseif ($perCurrent<$perLast)
{
  $cl1="text-danger";
  $caret1="ti-arrow-down";
  $subtract1=$perCurrent-$perLast;

}
$subtract1=round($subtract1);
$perCurrent=(int)round($perCurrent);      
      foreach ($results->client_savings() as $row) {
                    $client = $row["client_name"];
                    $savings = (double)$row["savings"];
                    $target = (double)$row["target"];
                    $variance=$savings-$target;
                    $id = $row["client_id"];
                    $lastsavings = (double)$results->savingsChange($id, $date);
                    $subtract = 0.0;
                    $cl = "text-warning";
                    $caret = "ti-arrow-left";
                    if ($savings > $lastsavings) {
                      $cl = "text-success";
                      $caret = "ti-arrow-up";
                      $subtract = $savings - $lastsavings;

                    } elseif ($savings < $lastsavings) {
                      $cl = "text-danger";
                      $caret = "ti-arrow-down";
                      $subtract = $savings - $lastsavings;

                    }
                    $savings = number_format($savings, 2, '.', ',');
                    $target1 = number_format($target, 2, '.', ',');
                    $variance1 = number_format($variance, 2, '.', ',');
                    $subtract = number_format($subtract, 2, '.', ',');
                    echo "<tr><td>$client</td>";
                    echo "<td>R $savings </td><td><span class=\"$cl mr-1\"><i class=\"$caret\"></i>";
                    echo " $subtract</span></td><td style='background-color:lightyellow'>R $target1 </td><td>R $variance1 </td><td><form method='post' action='download_summary_report.php'><input type='hidden' name='xxc' value='$id'> <button class=\"btn btn-primary\"><i class=\"ti-angle-double-down\"></i></button></form></td><td colspan='2'><button client='$client' class=\"btn btn-primary btn-sm bg-danger toastsDefaultMaroon\" value='$client'><i class=\"ti-arrow-circle-up\"></i> View Report</button></td></tr>";

                  }
                  if (count($results->client_savings()) < 1) {
                    echo "<caption><p align='center' class='text-muted'>No Information</p></caption>";
                  }
                    //echo $tr;

    }
    elseif($identity==32)
{
$this_month=date("Y-m");
$last_month= date("Y-m", strtotime("-1 months"));
$ratio=$results->getMyRecentSavings($last_month,$this_month,$condition,$valx);
$claims1=(double)$ratio["last_month"];
$claims2=(double)$ratio["this_month"];
$perc=($claims2/$claims1)*100;
if($perc>=100)
{
    $claims_per=$perc-100;
    $last_perc="Down";
    $this_perc="Up";  
    $arrow1="ti-arrow-up"; 
    $arrow2="ti-arrow-down";  
}
else
{
    $claims_per=100-$perc;
    $last_perc="Up";
    $this_perc="Down";  
     $arrow1="ti-arrow-down"; 
    $arrow2="ti-arrow-up";    
}

$arr=array("claims_per"=>round($claims_per,2),"last_perc"=>$last_perc,"this_perc"=>$this_perc,"arrow1"=>$arrow1,"arrow2"=>$arrow2,"claims1"=>$claims1,"claims2"=>$claims2);
echo json_encode($arr,true);
}
elseif($identity==33)
{
    echo $results->trend_clients();
}
elseif($identity==34)
{
    echo $results->trend1_clients();
}
elseif($identity==35)
{
    $date=$_GET["month_date"];
    $pmb=$_GET["pmb"];
    echo json_encode($results->pmbNClaims($date,$condition,$valx,$pmb));
}
elseif($identity==36)
{
    $month_date=$_GET["month_date"];
    $client_name=$_GET["client_name"];
    echo json_encode($results->csAnalysis($month_date,$client_name));
}
elseif($identity==37)
{
    $month_date=$_GET["month_date"];
    $client_name=$_GET["client_name"];
    $username=$_GET["username"];
    echo json_encode($results->csClaims($month_date,$client_name,$username));
}
elseif($identity==38)
{
    $month_date=$_GET["month_date"];
    $section_name=$_GET["section_name"];
    $open=$_GET["open"];
    if($section_name=="schemes")
    {
        echo json_encode($results->getSchemeSummary($month_date,$condition,$valx,2000,$open));
    }
    elseif($section_name=="ICD10_codes")
    {
        echo json_encode($results->getICD10Summary($month_date,$condition,$valx,2000,$open)); 
    }
    elseif($section_name=="tariff_codes")
    {
        echo json_encode($results->getTariffSummary($month_date,$condition,$valx,2000,$open)); 
    }
    
}
elseif($identity==39)
{
    $month_date=$_GET["month_date"];
    $val=$_GET["val"];
    $open=$_GET["open"];
    $section_name=$_GET["section_name"];
    echo json_encode($results->getIndvClaims($month_date,$val,$open,$section_name));
}
elseif($identity==40)
{    
    $val1=$_GET["year1"];
    $val2=$_GET["year2"];
    $from_month=$_GET["from_month"];
    $to_month=$_GET["to_month"];
    $type=$_GET["type"];
    $r3=$_GET["r3"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";

    $compare1=$results->compareR($results->evalCompareDates($val1,$from_month,$to_month),$type,$condition,$valx,$r3,$users,$clients);
    $compare2=$results->compareR($results->evalCompareDates($val2,$from_month,$to_month),$type,$condition,$valx,$r3,$users,$clients);
   $arr = array('compare1' => $compare1, 'compare2'=>$compare2);

    echo json_encode($arr,true);
}
elseif($identity==41)
{    
    $val1=$_GET["year1"];
    $val2=$_GET["year2"];
    $from_month=$_GET["from_month"];
    $to_month=$_GET["to_month"];
     $r3=$_GET["r3"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $date1_start=$val1."-".$from_month;
    $date1_end=$val1."-".$to_month;
    $date2_start=$val2."-".$from_month;
    $date2_end=$val2."-".$to_month;
    //echo $date1_start."--".$date1_end."-".$date2_start."->".$date2_end;
    $compare1=$results->modelClaimValue($date1_start,$date1_end,$users,$clients);
    $compare2=$results->modelClaimValue($date2_start,$date2_end,$users,$clients);
    $arr = array('compare1' => $compare1, 'compare2'=>$compare2);
    echo json_encode($arr,true);
}
elseif($identity==42)
{    
    $val1=$_GET["year1"];
    $val2=$_GET["year2"];
    $from_month=$_GET["from_month"];
    $to_month=$_GET["to_month"];
    $type=$_GET["type"];
     $r3=$_GET["r3"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $compare1=$results->getBestDays($results->evalCompareDates($val1,$from_month,$to_month),$r3,$users,$clients);
    $compare2=$results->getBestDays($results->evalCompareDates($val2,$from_month,$to_month),$r3,$users,$clients);
   $arr = array('compare1' => $compare1, 'compare2'=>$compare2);

    echo json_encode($arr,true);
}
elseif($identity==43)
{    
    $val1=$_GET["year1"];
    $val2=$_GET["year2"];
    $from_month=$_GET["from_month"];
    $to_month=$_GET["to_month"];   
     $r3=$_GET["r3"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $date1_start=$val1."-".$from_month;
    $date1_end=$val1."-".$to_month;
    $date2_start=$val2."-".$from_month;
    $date2_end=$val2."-".$to_month;
    //echo $date1_start."--".$date1_end."-".$date2_start."->".$date2_end;
    $compare1=$results->mlPercVal($date1_start,$date1_end,$users,$clients);
    $compare2=$results->mlPercVal($date2_start,$date2_end,$users,$clients,$emergency);
   $arr = array('compare1' => $compare1, 'compare2'=>$compare2);
    echo json_encode($arr,true);
}

elseif($identity==44)
{    
    $val1=$_GET["year1"];
    $val2=$_GET["year2"];
    $from_month=$_GET["from_month"];
    $to_month=$_GET["to_month"];
    $type=$_GET["type"];
     $r3=$_GET["r3"];
      $emergency=$_GET["emergency"];
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $compare1=$results->mlTScoring($results->evalCompareDates($val1,$from_month,$to_month),$users,$clients,$emergency);
    $compare2=$results->mlTScoring($results->evalCompareDates($val2,$from_month,$to_month),$users,$clients,$emergency);
   $arr = array('compare1' => $compare1, 'compare2'=>$compare2);

    echo json_encode($arr,true);
}
?>
