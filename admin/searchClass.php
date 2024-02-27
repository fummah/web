<link rel="stylesheet" href="bootstrap3/css/bootstrap.min.css">
<script src="jquery/jquery.min.js"></script>
<script src="bootstrap3/js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet">

<style>
    .vv {
        font-size: 15px;
        line-height: 1.5;
        font-family: arial,sans-serif;
        color: dimgrey;


    }
    .ddel{
        text-decoration: line-through;
    }
</style>
<?php
error_reporting(0);
require_once('classes/functionsClass.php');
require_once('dbconn.php');
$conn=connection("mca","MCA_admin");
Class Search{
    public $totalNumber;
    function getnoteId($claim_id)
    {
        global $conn;
        $stmupd=$conn->prepare("UPDATE claim SET sla=2 WHERE sla<>1 AND claim_id=:claim_id");
        $stmupd->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmupd->execute();
    }
    public function quickSearch()
    {
        global $conn;
        if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id'])) {
            die("Access Dinied");

        }
        $username=$_SESSION['user_id'];

        if ($_SESSION['level'] == "claims_specialist") {
            $condition = "a.username = :num";
            $spxy1 = $conn->prepare("SELECT COUNT(*) FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id WHERE qa_signed=1 AND cs_signed=0 AND quality=2 AND username=:num");
            $spxy1->bindParam(':num', $username, PDO::PARAM_STR);
            $spxy1->execute();
            $quality=$spxy1->fetchColumn();
        } else if ($_SESSION['level'] == "gap_cover") {

            $myClient=clients($username);
            $condition = "b.client_id = :num";
            if($username=="Kaelo")
            {
                $condition = "(b.client_id=15 OR b.client_id=27 OR b.client_id = :num)";
            }
            if($username=="Gaprisk_administrators")
            {
                $condition = "(b.client_id=32 OR b.client_id=21 OR b.client_id = :num)";
            }
            if($username=="Western")
            {
                $condition = "(b.client_id=27 OR b.client_id = :num)";
            }
            $username=$myClient;
        }
        else if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller")
        {
            $condition="a.username<>'Faghry' AND :num";
            $username=1;
            $spxy1 = $conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE quality=1");
            //$spxy1->bindParam(':num', $username, PDO::PARAM_STR);
            $spxy1->execute();
            $quality=$spxy1->fetchColumn();
        }
        else
        {
            die("Access Denied");
        }
    try{
        if ($_SESSION['level'] != "gap_cover")
        {
            $sp = $conn->prepare("SELECT COUNT(*) FROM lead as a WHERE a.status=0 AND $condition");
            $sp->bindParam(':num', $username, PDO::PARAM_STR);
            $sp->execute();
            $ccn=$sp->fetchColumn();

            $spx = $conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE Open=4 AND $condition");
            $spx->bindParam(':num', $username, PDO::PARAM_STR);
            $spx->execute();
            $clinc=$spx->fetchColumn();
        }
       

        $selectDetails = $conn->prepare("SELECT a.claim_id,a.pmb,b.first_name, b.surname, b.policy_number, a.claim_number, b.medical_scheme, a.icd10, b.client_id, a.date_entered, 
a.new,a.username,a.date_reopened,a.date_closed,a.open_reason FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.Open=1 AND $condition");
        $selectDetails->bindParam(':num', $username, PDO::PARAM_STR);
        $selectDetails->execute();

        echo "<p class=\"tab1\" >";
        if ($_SESSION['level'] != "gap_cover") {
            echo "<span class=\"xx\"><a href='consent_forms.php'> <button type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" id=\"btn\" name=\"btn\"> <b style=\"color:mediumseagreen\">View Consent Forms</b></button></a></span>".$_SESSION['role'] ;
            echo "<span class=\"xx\"><a href='leads.php'> <button type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" id=\"btn\" name=\"btn\"><span class=\"badge w3-red\"> $ccn</span> <b style=\"color:mediumseagreen\">Leads</b></button></a></span>";


            echo "<span class=\"xx\"><a href='clinical_review.php'> <button type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" id=\"btn\" name=\"btn\"><span class=\"badge w3-red\"> $clinc</span> <b style=\"color:mediumseagreen\">Clinical Review</b></button></a></span>";
            echo "<span class=\"xx\"><a href='view_quality.php'> <button type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" id=\"btn\" name=\"btn\"><span class=\"badge w3-red\"> $quality</span> <b style=\"color:mediumseagreen\">QA</b></button></a></span>";
            if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller" || $_SESSION["gap_admin"]=="assessor") {

                if($_SESSION["gap_admin"]=="assessor")
                {
                    $spx1 = $conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE Open=5 AND (username=:num OR preassessor=:num)");
                    $spx1->bindParam(':num', $username, PDO::PARAM_STR);
                    $spx1->execute();
                    $preassessed=$spx1->fetchColumn();
                }
                else{
                    $spx1 = $conn->prepare("SELECT COUNT(*) FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE Open=5");
                    $spx1->execute();
                    $preassessed=$spx1->fetchColumn();
                }
                echo "<span class=\"xx\"><a href='preassessed.php'> <button type=\"submit\" class=\"uk-input uk-form-success uk-form-width-medium\" id=\"btn\" name=\"btn\"><span class=\"badge w3-red\"> $preassessed</span> <b style=\"color:mediumseagreen\">Pre-Assessment</b></button></a></span>";
            }

        }
        if ($_SESSION['level'] == "controller") {
            echo "<span class=\"xx\"><a href='admin/controller_reports.php'> <button type=\"submit\" class=\"w3-btn w3-white w3-border w3-border-blue w3-round-large\" id=\"btn\" name=\"btn\"><span class=\"glyphicon glyphicon-list-alt\" style=\"color:mediumseagreen\"> </span> <b style=\"color:mediumseagreen\">Client Reports</b></button></a></span>";

            echo "<span class=\"xx\"><a href='reports2.php'> <button type=\"submit\" class=\"w3-btn w3-white w3-border w3-border-blue w3-round-large\" id=\"btn\" name=\"btn\"><span class=\"glyphicon glyphicon-list-alt\" style=\"color:mediumseagreen\"> </span> <b style=\"color:mediumseagreen\">Admed/Zestlife</b></button></a></span>";


        }

        echo "<br>";
        echo "<p class=\"tab1\">";
        echo "<span class=\"xx\" style='color: black;'><b><u>All your open cases are listed below. Click on the claim number to review the case, see your previous case notes, and to submit further notes.</u></b></span>";

        //echo "<br>";
        echo "<br>";
        echo "</p>";
        ?>
        <div id="tb" >
            <?php
            echo "<div class=\"w3-container uk-card uk-card-default uk-card-body\" style='padding: 15px'>";
            echo "<table id=\"example\" class=\"table table-condensed vv\" cellspacing=\"0\" width=\"100%\">";

            echo "<thead style='color: black; border-color: red'>";
            echo "<tr align='center'>";
            echo "<th>";
            echo "Full Name";
            echo "</th>";

            echo "<th>";
            echo "Claim Number";
            echo "</th>";
            echo "<th>";
            echo "Note Days";
            echo "</th>";
            echo "<th>";
            echo "Date/Notes";
            echo "</th>";
            echo "<th>";
            echo "Days Open";
            echo "</th>";
            echo "<th>";
            echo "Medical Scheme";
            echo "</th>";
            echo "<th>";
            echo "Client";
            echo "</th>";
            echo "<th>";
            echo "Owner";
            echo "</th>";
            if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                echo "<th>";
                echo "PMB?";
                echo "</th>";
                echo "<th>";
                echo "";
                echo "</th>";
            }
            if ($_SESSION['level'] == "gap_cover") {
                echo "<th></th>";
            }
            echo "</tr>";
            echo "</thead>";

            echo "<tfoot>";
            echo "<tr align='center'>";

            echo "<th>";
            echo "Name and Surname";
            echo "</th>";

            echo "<th>";
            echo "Claim Number";
            echo "</th>";
            echo "<th>";
            echo "Note Days";
            echo "</th>";
            echo "<th>";
            echo "Date/Notes";
            echo "</th>";
            echo "<th>";
            echo "Days Open";
            echo "</th>";
            echo "<th>";
            echo "Medical Scheme";
            echo "</th>";
            echo "<th>";
            echo "Client";
            echo "</th>";
            echo "<th>";
            echo "Owner";
            echo "</th>";
            if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                echo "<th>";
                echo "PMB?";
                echo "</th>";
                echo "<th>";
                echo "";
                echo "</th>";
            }
            if ($_SESSION['level'] == "gap_cover") {
                echo "<th></th>";
            }
            echo "</tr>";
            echo "</tfoot>";
            echo "</tbody>";
            $tot_red=0;
            $tot_orange=0;
            $tot_purple=0;
            $arr1=array();
            $arr2=array();
            $arr3=array();


            $arr11=array();
            $arr21=array();
            $arr31=array();
            $counter = 0;
            foreach ( $selectDetails->fetchAll() as $result) {
                $claim_id = htmlspecialchars($result[0]);
                $pp = htmlspecialchars($result[1]);
                $first_name = htmlspecialchars(strtoupper($result[2]));
                $surname = htmlspecialchars(strtoupper($result[3]));
                $policy_number = htmlspecialchars(strtoupper($result[4]));
                $claim_number = htmlspecialchars(strtoupper($result[5]));
                $medicalScheme = htmlspecialchars($result[6]);
                $icd10 = htmlspecialchars($result[7]);
                $client_id = htmlspecialchars($result[8]);
                $stmt = $conn->prepare("SELECT client_name FROM clients WHERE client_id = :id");
                $stmt->bindParam(':id', $client_id, PDO::PARAM_STR);
                $stmt->execute();
                $clientName = $stmt->fetchColumn();
                $date_entered = htmlspecialchars($result[9]);
                $new = htmlspecialchars($result[10]);
                $username = htmlspecialchars($result[11]);
                $date_reopened = htmlspecialchars($result[12]);
                $date_closed = htmlspecialchars($result[13]);
                $open_reason = htmlspecialchars($result[14]);
                $record_index = $claim_id;
                date_default_timezone_set('Africa/Johannesburg');
                //$holidays=array("01-01","03-21","04-27","05-02","06-16","08-09","09-24","11-01","12-16","12-25","12-27");
                $holidays=array("01-01","03-21","04-15","04-18","04-27","05-02","06-16","08-09","09-24","12-16","12-25","12-26");
                $today = date('Y-m-d');


                //if($open_reason=="Client Request")
                //{

                if(strlen($date_reopened)<2 && strlen($date_closed)>10)
                {
                    $stmts = $conn->prepare('SELECT date_entered FROM `claim_line` WHERE mca_claim_id=:claim_id ORDER BY id DESC LIMIT 1');
                    $stmts->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                    $stmts->execute();
                    $ytd = $stmts->fetchColumn();
                    $date_reopened=$date_reopened>$ytd?$date_reopened:$ytd;

                }
                $date_entered = strlen($date_reopened)>10 && strlen($date_closed)>10?$date_reopened:$date_entered;
                //}

                $from_date1 = date('Y-m-d', strtotime($date_entered));

                /*
                  $datetime_1 = strtotime($from_date1);
                  $datetime_2 = strtotime($today);
                  $secs = $datetime_2 - $datetime_1;
                  $days = $secs / 86400;
                */
                $days=$this->getWorkingDays($from_date1,$today,$holidays);

                $nnotes = "No Notes";
                $sla=0;
                $cc = $claim_id;
                $date1 = "";
                $date_number = 0;
                $stmt = $conn->prepare('SELECT date_entered,intervention_desc FROM intervention WHERE claim_id=:num OR claim_id1=:num ORDER BY intervention_id DESC LIMIT 1');
                $stmt->bindParam(':num', $cc, PDO::PARAM_STR);
                $stmt->execute();
                foreach ($stmt->fetchAll() as $row) {
                    $date1 = htmlspecialchars($row[0]);
                    $nnotes = htmlspecialchars($row[1]);

                    $from_date = date('Y-m-d', strtotime($date1));

                    /*
                                        $datetime1 = strtotime($from_date);
                                        $datetime2 = strtotime($today);
                                        $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                                        $date_number = $secs / 86400;
                    */
                    $date_number=$this->getWorkingDays($from_date,$today,$holidays);
                }



                if (strlen($date_number) < 2) {
                    $date_number = "0" . $date_number;
                }
                $mess = "<b>" . $date_number."</b>";

                if ($date1 == 0) {
                    $mess = "<b style='color:red'>(No Notes)</b>";
                }
                $star = "";
                if ($new == 0) {
                    $star = "<b style='color:red'></b>";
                }
                $coll="w3-text-green";
                if($days>2 && $nnotes == "No Notes")
                {
                    $coll="w3-text-purple";
                    $tot_purple+=1;
                    $star.="~";
                    array_push($arr1,$clientName);
                    array_push($arr11,$username);
                    $sla=1;
                }
                elseif($date_number>2)
                {
                    $coll="w3-text-red";
                    $tot_red+=1;
                    $star.="^";
                    array_push($arr2,$clientName);
                    array_push($arr21,$username);
                    $sla=1;
                }
                elseif($date_number==0 && $days==2)
                {
                    $coll="w3-text-red";
                    $tot_red+=1;
                    $star.="^";
                    array_push($arr2,$clientName);
                    array_push($arr21,$username);
                    $sla=1;
                }
                elseif ($date_number==2)
                {
                    $coll="w3-text-orange";
                    $tot_orange+=1;
                    $star.="*";
                    array_push($arr3,$clientName);
                    array_push($arr31,$username);
                }
                else
                {
                    $star.="#";
                }
                $pV = "No";
                $pC = "alert-danger";

                if (strlen($icd10) < 1) {
                    $pV = "Not Confirmed";
                }

                if ($pp == 1) {
                    $pV = "Yes";
                    $pC = "alert-success";
                }
                if($sla==1)
                {
                    $this->getnoteId($record_index);
                }

//    echo "<p class=\"tab\">";
                echo "<tr class='w3-hover-white $coll' title='$username'>";

                echo "<td> ";
                echo $first_name;
                echo "  ";
                echo $surname;
                echo "</td>";

                echo "<td>";
                $path=(int)$client_id==31?"view_aspen.php":"case_detail.php";
                echo "<form action='$path' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                echo "<input type=\"hidden\" name=\"user\" value=\"$username\" />";
                echo "<input type=\"hidden\" name=\"sla\" value=\"$sla\" />";
                echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claim_number\">";
                echo "(" . $claim_number . ")";
                echo "</form>";
//    echo $claim_number;
                echo "</td>";
//    $interim_days=$days / 7;
//    $interim_working_days=$interim_days * 5;
//    $working_days=round($interim_working_days);
                echo "<td align='center'> ";


                echo $star.$mess;
                echo "</td>";
                echo "<td>";
                echo $date1." <span data-toggle=\"tooltip\" data-placement=\"top\" title='$nnotes'><span uk-icon=\"info\"></span></span>";
                echo "</td>";
                echo "<td align='center'>";
                echo $days;
                echo "</td>";

                echo "<td>";
                echo $medicalScheme;
                echo "</td>";
                echo "<td>";

                echo $clientName;
                echo "</td>";
                echo "<td>";

                echo $username;
                echo "</td>";
                if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {
                    echo "<td class='$pC' style='font-weight:bolder;'>";
                    echo $pV;
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='edit_case.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    // echo "<input type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"Edit\">";
                    echo "<button class=\"w3-btn w3-white w3-border w3-border-blue w3-round-large\" title='Edit Case' name=\"btn\"><span class=\"glyphicon glyphicon-pencil\"></span></button>";
                    echo "</form>";
//    echo $claim_number;
                    echo "</td>";
                }
                if ($_SESSION['level'] == "gap_cover") {
                    echo "<td><form action='add_documents.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                    echo "<button type=\"submit\" class=\"btn btn-info\" name=\"btn\" value=\"\" ><span class=\"glyphicon glyphicon-pencil\"></span></button>";
                    echo "</form></td>";
                }
                echo "</tr>";

                $counter++;
            }


            $xall=array(ccheck($arr1));
            array_push($xall,ccheck($arr2));
            array_push($xall,ccheck($arr3));

            $xall1=array(ccheck($arr11));
            array_push($xall1,ccheck($arr21));
            array_push($xall1,ccheck($arr31));


            $da=json_encode($xall,true);
            $da1=json_encode($xall1,true);
            $_SESSION["mypurple"]=$tot_purple;
            $_SESSION["myred"]=$tot_red;
            $_SESSION["myorange"]=$tot_orange;
            echo "<input type='hidden' id='purple1' value='$tot_purple'><input type='hidden' id='red1' value='$tot_red'><input type='hidden' id='orange1' value='$tot_orange'><textarea style='display:none' id='arrall'>$da</textarea><textarea style='display:none' id='arrall1'>$da1</textarea></tbody>";
            echo "</table></div>";
            }
            catch (Exception $e)
            {
                die("There is an error ".$e->getMessage());
            }
            ?>
        </div>

        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script src="js/responsive.bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#example').DataTable({"order": [[ 2, 'desc' ],[ 3, 'asc' ]]});

            } );
        </script>
        <?php
    }

    //The function returns the no. of business days between two dates and it skips the holidays
    public function getWorkingDays($startDate,$endDate,$holidays){
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
}

function ccheck($arr)

{
    $uni=array_unique($arr);
    $all=array();
    foreach($uni as $uu)
    {
        $num=count(array_keys($arr, $uu));
        $inn=array("a"=>$uu,"num"=>$num);
        array_push($all,$inn);
    }
    return $all;
}
?>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
</body></html>