<?php
//error_reporting(0);
session_start();
include ("../classes/reportsClass.php");
$holidays=array("01-01","03-21","04-19","04-27","05-01","06-17","08-09","09-24","12-16","12-25","12-26");

$role=$_SESSION['level'];
$mcausername=$_SESSION['user_id'];
$condition=$role=="admin" || $role=="controller"?":username":"username=:username";
$valx=$role=="admin" || $role=="controller"?"1":$mcausername;
$results=new reportsClass();
$identity = (int)validateXss($_GET['identityNum']);
//$identity=20;
//$identity =11;
if($identity==1)
{
    $r=json_encode($results->usersCase($condition,$valx),true);
    echo $r;
}
elseif($identity==2)
{
    $r=json_encode($results->clientsCase($condition,$valx),true);
    echo $r;
}
elseif ($identity==3)
{

    $r=json_encode($results->trend_claims($condition,$valx),true);
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
    $r=json_encode($results->savingsTrend(7,$condition,$valx),true);
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

    $header="<table border='1'><thead><tr><th>Year-Month</th><th>Claims Closed</th><th>Savings by Dr Discount</th><th>Savings by Scheme Paid</th><th>Total Savings</th><th>Value of Claims Referred</th>
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
        echo "<tr $vv><td>$row[month]</td><td>$row[claims]</td><td>$discount</td><td>$scheme</td><td>$total_savings</td><td>$charged</td><td>$row[percentage]</td><td>$row[average]</td><td>$row[total_referred]</td></tr>";


    }
    echo "</tbody><caption><form method='post' action='../admin_main/download_summary_report.php'><input type='hidden' name='ctxt' value='$client'><input type='hidden' name='txt' value='$full_array'><button class='btn btn-info'><i class=\"fas fa-download\"></i> Download Excel</button></form></caption></table>";

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
                $number_closed=$results->closedthisClaims($mydate,"username=:username",$myuser);
                $number_entered=$results->enteredthisClaims($mydate,"username=:username",$myuser);
                $number_workedon=$results->casesWorkedOn($mydate,"owner=:username",$myuser);
                $average_closed=round($number_closed/$days);
                $average_worked=round($number_workedon/$days);
                $claims_value=$results->claimValue($mydate,"username=:username",$myuser);
                $savings=$results->savingsMain($mydate,"username=:username",$myuser);
                $myaar=$results->reopenedCases("",$mydate,$myuser);
                $numclosed=$results->closedDate("username=:username",$myuser,$mydate);
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
    $users_web=$_GET["users"];
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
    $clients_web=$_GET["clients"];
    $users_web=$_GET["users"];
    $clients=!empty($clients_web)?implode(",",$clients_web):"";
    $users=!empty($users_web)?implode(",",$users_web):"";
    $start_date=$_GET["start_date"];
    $end_date=$_GET["end_date"];
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
        $vari=(double)$row["vari"];
        $actualsavings = $savings-$firstsavings+$vari;
        $vatexcl = round($actualsavings*(100/115),2);
        $ssw = $results->switchProd($id,$today) + $results->switchSeamless($id,$today);
        $ccochf= (int)$results->getSwitchCHF($client,0,$today);
        $switch += $switch;
        $variance = $vatexcl - $threshold;
        $subtract = 0.0;
        $cl = "text-secondary";
        $caret = "fas fa-caret-left";
        if ($savings < $actualsavings) {
            $cl = "text-success";
            $caret = "fas fa-arrow-up";
            $subtract = $savings - $actualsavings;
        } elseif ($savings > $actualsavings) {
            $cl = "text-danger";
            $caret = "fas fa-arrow-down";
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
            "vatexcl"=>$vatexcl,"base_fee"=>$base_fee,"variance"=>$variance,"variance1"=>$variance1,"perc25"=>$perc25,"perc30"=>$perc30,"threshold"=>$threshold,"threshold1"=>$threshold1,"switch_number"=>$ssw,
            "client_id"=>$id,"total_switch"=>$switch,"chf"=>$ccochf
        ];
        array_push($allarr,$arr);
    }
    echo json_encode($allarr);
}
?>