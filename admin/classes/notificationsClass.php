<?php
error_reporting(0);
require_once('dbconn.php');

abstract class main
{
    abstract function totalFunction();
    abstract function newFunction();
    abstract function schemeSavings();
    abstract function discountSavings();
    abstract function closedThisMonth();
    abstract function totalThisMonth();
    abstract function closedDate();
    abstract function enteredAndClosed();
    abstract function enteredByMe();
    abstract function updatedDocs();
    abstract function members();
    abstract function zeroAmounts();
    abstract function zeroAmountsClients();
    abstract function zeroAmountsUpdate();
    abstract function leads();

    public $username="cc";
    public $username2="cc";
    public $conn="";
    public $from = "";
    public $open=1;
    public $new=0;
    public $username1="";

    public function __construct()
    {
        $this->from = date('Y-m').'%';
        $this->username=$_SESSION['user_id'];
        $this->conn=connection("mca","MCA_admin");
        $this->username1=clients($this->username);
        $this->username2=$this->username1;
        if($this->username1==32 || $this->username1==21)
        {
            $this->username2=21;
        }
    }
    function searchForId($id, $array) {
        foreach ($array as $key => $val) {
            if ($val['uid'] === $id) {
                return $key;
            }
        }
        return null;
    }
    function getSla($email)
    {
        $stmt = $this->conn->prepare('SELECT a.date_entered FROM `lead_notes` as a INNER JOIN lead as b on a.lead_id=b.lead_id WHERE b.email=:email ORDER BY id DESC LIMIT 1');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            return $stmt->fetchColumn();
        }
        else{
            $stmt = $this->conn->prepare('SELECT date_entered FROM lead WHERE email=:email ORDER BY lead_id DESC LIMIT 1');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }


    }
    public function all()
    {
        $dv=$this->totalThisMonth();

        if($dv==0)
        {
            $dv=1;
        }
$entclos=$this->closedThisMonth();
        $closed=$dv>0?($this->enteredAndClosed()/$dv)*100:0;
        $st="progress-bar-success";
        if($closed<50)
        {
            $st="progress-bar-danger";
        }
        if($closed>=50 && $closed<70)
        {
            $st="progress-bar-warning";
        }

        $totalSavings=$this->discountSavings()+$this->schemeSavings();

        $average=$entclos>0?round($this->closedDate()/$entclos):0;
        $date=date('F');


        if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist" || $_SESSION['level'] == "controller") {

            $count=count($this->updatedDocs());
            echo "<h4 class='uk-text-muted'>Priority List</h4>";
            echo "<div class=\"uk-placeholder\" style='background-color: floralwhite'>";
            echo "<div class='uk-inline'><span id='purple_on' style='cursor: pointer' title='No Notes for more than 2 days' class=\"badge w3-purple w3-display-container\"><span id='purple'></span> / <span id='purple_open'>".$this->totalFunction()."</span></span><div uk-dropdown><ul uk-accordion='collapsible: false'><li><a class='uk-accordion-title' href='#'>Clients</a><div class='uk-accordion-content'><span id='bas3'></span></div></li><li><a class='uk-accordion-title' href='#'>User(s)</a><div class='uk-accordion-content'><span id='bas31'></span></div></li></ul></div></div>";
            echo " | <div class='uk-inline'><span id='red_on' style='cursor: pointer' title='No additional notes for more than 2 days' class=\"badge w3-red w3-display-container\"><span id='red'></span> / <span id='red_open'>".$this->totalFunction()."</span></span><div uk-dropdown><ul uk-accordion='collapsible: false'><li><a class='uk-accordion-title' href='#'>Clients</a><div class='uk-accordion-content'><span id='bas2'></span></div></li><li><a class='uk-accordion-title' href='#'>User(s)</a><div class='uk-accordion-content'><span id='bas21'></span></div></li></ul></div></div>";
            ///////////////////////////////////////////////////4 Days Claims
            $count11=count($this->members());
            echo " | <div class=\"uk-inline\">";
            echo "<span class=\"uk-badge\" style='cursor: pointer' title=\"$count11 Member(s) with more than 4 days without being contacted\">$count11 Member(s)?</span>";
            echo " <div uk-dropdown=\"mode: click\">";
            foreach($this->members() as $row)
            {
                $claim_number=$row[0];
                $claim_id=$row[1];
                echo "<form action='case_detail.php' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claim_number\">";
                echo "</form>";
            }
            echo "</div></div>| <div style='color:#00b3ee;cursor:pointer' id='clearf' title='refresh' uk-icon='refresh'></div><hr>";
            //End
            echo "<div class=\"uk-inline\">";
            echo "<span class=\"uk-badge\" style='cursor: pointer' title=\"$count New Documents\">$count New Files</span>";
            echo " <div uk-dropdown=\"mode: click\">";
            foreach($this->updatedDocs() as $row)
            {
                $claim_number=$row[0];
                $claim_id=$row[1];
                echo "<form action='case_detail.php' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claim_number\">";
                echo "</form>";
            }
            echo "</div></div>";
//Leads here
            $arrLeads=$this->leads();
            $countleads=count($arrLeads);
            $leadcoarr=[];$xcc="w3-green";
            echo " | <div class=\"uk-inline\">";
            echo "<span class=\"uk-badge w3-green\" id='leed' style='cursor: pointer' title=\"$countleads Lead(s)\">$countleads Lead(s)</span>";
            echo "<div uk-dropdown><ul uk-accordion='collapsible: false'>";
            foreach($this->leads() as $row)
            {
                $lead_id=$row[0];
                $leadname=$row[1]." ".$row[2];
                $leademail=$row[3];
                $leaddate=$this->getSla($leademail);
                $d1 = strtotime($leaddate);
                $d2 = strtotime(date("Y-m-d H:i:s"));
                $totalSecondsDiff = abs($d1-$d2); //42600225
                $totalDaysDiff    = round($totalSecondsDiff/60/60/24);
                $ccolor="w3-green";
                if($totalDaysDiff>=3)
                {
                    $ccolor="w3-red";
                }
                array_push($leadcoarr,$ccolor);
                echo "<li class='$ccolor' style='color: #fff'><form action='view_lead.php' method='post'><input type='hidden' name='lead_id' value='$lead_id'/><input type=\"submit\" class=\"linkbutton\" name=\"lead_btn\" style='color: #fff; text-decoration: none' value=\"$leadname\"></form><div class='uk-accordion-content'>";

                echo "</div></li>";

            }
            if(in_array("w3-red",$leadcoarr)){$xcc="w3-red";}

            echo "<input type='hidden' id='gettco' value='$xcc'></ul></div></div>";
            //End
/////////////////////////Zero Amounts

            $arrZero=$this->zeroAmounts();
            $count22=count($arrZero);
            echo " | <div class=\"uk-inline\">";
            echo "<span class=\"uk-badge\" style='cursor: pointer' title=\"$count22 Claim(s) with Zero Amounts\">$count22 Zero Amnt(s)</span>";
            echo "<div uk-dropdown><ul uk-accordion='collapsible: false'>";
            foreach($this->zeroAmountsClients() as $row)
            {

                $client_name=$row[0];
                $keys = array_keys(array_column($arrZero, 'client_name'), $client_name);
                $ccountclient=count($keys);
                $cvc=$ccountclient<8?$ccountclient:8;
                echo "<li><a class='uk-accordion-title' href='#'><span class='badge badge-primary'>$ccountclient</span> $client_name</a><div class='uk-accordion-content'>";
                for($i=0;$i<$cvc;$i++)
                {
                    $claim_number= $arrZero[$keys[$i]]["claim_number"];
                    $claim_id= $arrZero[$keys[$i]]["mca_claim_id"];
                    echo "<span><form action='case_detail.php' method='post' />";
                    echo "<input type=\"hidden\" name=\"claim_id\" value=\"$claim_id\" />";
                    echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claim_number\">";
                    echo "</form></span>";
                }
                echo "</div></li>";

            }
            echo "</ul></div></div>";
            ////////////////////////////////////// End
            echo " | <div class='uk-inline'><span id='orange_on' style='cursor: pointer' title='No additional notes for the second day' class=\"badge w3-orange w3-display-container\"><span id='orange'></span> / <span id='orange_open'>".$this->totalFunction()."</span></span><div uk-dropdown><ul uk-accordion='collapsible: false'><li><a class='uk-accordion-title' href='#'>Clients</a><div class='uk-accordion-content'><span id='bas1'></span></div></li><li><a class='uk-accordion-title' href='#'>User(s)</a><div class='uk-accordion-content'><span id='bas11'></span></div></li></ul></div></div>";

            echo "</div>";


        }
        elseif ($_SESSION['level'] == "gap_cover") {
            $ccis=(int)clients($this->username);
            echo "<form method='post' action='admin_main/download_summary_report.php'><input type='hidden' name='xxc' value='$ccis'><button title='weekly claims referred report' uk-icon='download'></button></form>";
        }
        echo"<p>Open Cases : <span class=\"badge\">".$this->totalFunction()."</span></p>";
        echo"<p>New Cases : <span class=\"badge w3-red\">".$this->newFunction()."</span></p>";
        echo "<p style='color: #0d92e1'><u><i>$date</i></u></p>";
        echo "<p >Scheme Savings : <span class=\"badge badge-warning\">R ".number_format($this->schemeSavings(),0,'',',')."</span></p>";
        echo "<p >Discount Savings : <span class=\"badge badge-warning\">R ".number_format($this->discountSavings(),0,'',',')."</span></p>";
        echo "<p ><u>Total Savings : <span class=\"badge badge-warning\">R ".number_format($totalSavings,0,'',',')."</u></span></p>";
        echo "<p >Average Days to Close : <span class=\"badge badge-warning\">".round($average,2)."</span></p>";
        echo "<p >Closed Cases : <span class=\"badge\">".$this->closedThisMonth()."</span></p>";
        if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "claims_specialist") {
            echo "<p >Entered By Me <span class=\"badge badge-warning\">" . $this->enteredByMe() . "</span></p>";
        }
        echo "<p >Entered and Closed this Month <span class=\"badge badge-warning\">".$this->enteredAndClosed()."</span></p>";
        echo "<p >Total : <span class=\"badge badge-warning\">".$this->totalThisMonth()."</span></p>";

        echo"<div class=\"progress\">";
        echo"<div class=\"progress-bar $st progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"$closed\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: $closed%\">";
        echo round($closed)."%";
        echo "</div>";
        echo "</div>";

    }

}


class specialistsNotifications extends main
{
    public function _constructor()
    {
        parent::_constructor(); // TODO: Change the autogenerated stub
    }


    function totalFunction()
    {

        $stmt = $this->conn->prepare('SELECT claim_id FROM claim WHERE username = :num AND Open=:open');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':open', $this->open, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
    function closedDate()
    {



        $fbStmt = $this->conn->prepare("SELECT date_entered,date_closed FROM claim WHERE username = :num AND date_closed LIKE :dat AND Open=0");
        $fbStmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $fbStmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $fbStmt->execute();
        $nu1 = $fbStmt->rowCount();
        $totDays=0;
        if ($nu1 > 0) {
            foreach ($fbStmt->fetchAll() as $row1) {
                $d1=$row1[0];
                $d2=$row1[1];
                $datetime1 = strtotime($d1);
                $datetime2 = strtotime($d2);
                $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                $days = $secs / 86400;
                $totDays+=$days;
            }
        }
        return $totDays;
    }

    function newFunction()
    {

        $stmt = $this->conn->prepare('SELECT claim_id FROM claim WHERE username = :num AND Open=:open AND new=:new');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':open', $this->open, PDO::PARAM_STR);
        $stmt->bindParam(':new', $this->new, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->rowCount();
    }
    function schemeSavings()
    {
        $stmt = $this->conn->prepare('SELECT SUM(savings_scheme) as a FROM claim WHERE date_closed LIKE :dat AND username=:num AND Open=0 AND recordType IS NULL');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function discountSavings()
    {

        $stmt = $this->conn->prepare('SELECT SUM(savings_discount) as a FROM claim WHERE date_closed LIKE :dat AND username=:num AND Open=0 AND recordType IS NULL');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function closedThisMonth()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(claim_id) as a FROM claim WHERE date_closed LIKE :dat AND Open=0 AND username=:num');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function enteredAndClosed()
    {
        $stmt = $this->conn->prepare('SELECT COUNT(claim_id) as a FROM claim WHERE date_entered LIKE :dat AND Open=0 AND username=:num');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function enteredByMe()
    {
        $stmt = $this->conn->prepare('SELECT COUNT(claim_id) as a FROM claim WHERE date_entered LIKE :dat AND entered_by=:num');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function totalThisMonth()
    {
        $stmt = $this->conn->prepare('SELECT COUNT(claim_id) as a FROM claim WHERE date_entered LIKE :dat AND Open<>2 AND username=:num');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function updatedDocs()
    {
        $stmt = $this->conn->prepare('SELECT b.claim_number,a.claim_id FROM `documents` as a inner join claim as b on a.claim_id=b.claim_id where additional_doc=1 AND b.username=:num');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function members()
    {
        $stmt = $this->conn->prepare('SELECT a.claim_number,a.claim_id,a.date_entered,a.member_contacted,NOW() as time,TIMESTAMPDIFF(day,a.date_entered,NOW()) as period,a.username,a.claim_id 
FROM claim as a INNER JOIN  member as b ON a.member_id=b.member_id WHERE a.Open=1 GROUP BY a.claim_id having period>=8 AND (a.member_contacted<>1 OR a.member_contacted IS NULL) AND a.username=:usex');
        $stmt->bindParam(':usex', $this->username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function zeroAmounts()
    {
        $stmt = $this->conn->prepare('SELECT DISTINCT claim_number,mca_claim_id,username,b.date_entered,d.client_name FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id
where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId is null AND a.date_entered>"2020-05-28" AND b.username=:usex');
        $stmt->bindParam(':usex', $this->username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function zeroAmountsClients()
    {
        $this->zeroAmountsUpdate();
        $stmt = $this->conn->prepare('SELECT DISTINCT d.client_name FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id
where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId is null AND a.date_entered>"2020-05-28" AND b.username=:usex');
        $stmt->bindParam(':usex', $this->username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function zeroAmountsUpdate()
    {

        $stmt1=$this->conn->prepare('SELECT a.id FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c 
ON b.member_id=c.member_id where clmline_scheme_paid_amnt="0.00" AND clmnline_charged_amnt <>"0.00" 
AND (LENGTH(msg_code)<3 OR msg_code is null) AND b.senderId is null AND a.date_entered>"2020-05-28" AND c.client_id=1 AND b.username=:usex');
        $stmt1->bindParam(':usex', $this->username, PDO::PARAM_STR);
        $stmt1->execute();
        foreach ($stmt1->fetchAll() as $row)
        {
            $id=$row[0];
            $stmt2=$this->conn->prepare('UPDATE claim_line SET msg_code="003 - Benefit exclusion" WHERE id=:id');
            $stmt2->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt2->execute();

        }


    }
    function leads()
    {
        $stmt = $this->conn->prepare("SELECT lead_id,first_name,last_name,email FROM lead WHERE status=0 AND username=:usex");
        $stmt->bindParam(':usex', $this->username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

class adminNotifications extends main
{
    public function _constructor()
    {
        parent::_constructor(); // TODO: Change the autogenerated stub
    }
    function totalFunction()
    {
        $conn=connection("mca","MCA_admin");
        $stmt = $conn->prepare('SELECT COUNT(Open) as a FROM claim WHERE OPEN=1 AND recordType is null');
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    function closedDate()
    {
        $fbStmt = $this->conn->prepare("SELECT date_entered,date_closed FROM claim WHERE date_closed LIKE :dat AND Open=0");
        $fbStmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $fbStmt->execute();
        $nu1 = $fbStmt->rowCount();
        $totDays=0;
        if ($nu1 > 0) {
            foreach ($fbStmt->fetchAll() as $row1) {
                $d1 = $row1[0];
                $d2 = $row1[1];
                $datetime1 = strtotime($d1);
                $datetime2 = strtotime($d2);
                $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                $days = $secs / 86400;
                $totDays += $days;
            }
        }
        return $totDays;
    }
    function newFunction()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(new) as a FROM claim WHERE Open=1 AND new=0 AND recordType is null');
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function schemeSavings()
    {

        $stmt = $this->conn->prepare('SELECT SUM(savings_scheme) as a FROM claim WHERE date_closed LIKE :dat AND Open=0 AND recordType IS NULL');
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function discountSavings()
    {

        $stmt = $this->conn->prepare('SELECT SUM(savings_discount) as a FROM claim WHERE date_closed LIKE :dat AND Open=0 AND recordType IS NULL');
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function closedThisMonth()
    {


        $stmt = $this->conn->prepare('SELECT COUNT(Open) as a FROM claim WHERE date_closed LIKE :dat AND Open=0');
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function enteredAndClosed()
    {
        $stmt = $this->conn->prepare('SELECT COUNT(claim_id) as a FROM claim WHERE date_entered LIKE :dat AND Open=0');
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function enteredByMe()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(claim_id) as a FROM claim WHERE date_entered LIKE :dat AND entered_by=:num');
        $stmt->bindParam(':num', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function totalThisMonth()
    {
        $stmt = $this->conn->prepare('SELECT COUNT(claim_id) as a FROM claim WHERE date_entered LIKE :dat AND Open<>2 AND recordType is null');
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    function updatedDocs()
    {
        $stmt = $this->conn->prepare('SELECT b.claim_number,a.claim_id FROM `documents` as a inner join claim as b on a.claim_id=b.claim_id where additional_doc=1');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function members()
    {
        $stmt = $this->conn->prepare('SELECT a.claim_number,a.claim_id,a.date_entered,a.member_contacted,NOW() as time,TIMESTAMPDIFF(day,a.date_entered,NOW()) as period,a.username,a.claim_id 
FROM claim as a INNER JOIN  member as b ON a.member_id=b.member_id WHERE a.Open=1 GROUP BY a.claim_id having period>=8 AND (a.member_contacted<>1 OR a.member_contacted IS NULL)');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function zeroAmounts()
    {
        $stmt = $this->conn->prepare('SELECT DISTINCT claim_number,mca_claim_id,username,b.date_entered,d.client_name FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id
where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId  is null AND a.date_entered>"2020-05-28"');

        $stmt->execute();
        return $stmt->fetchAll();
    }
    function zeroAmountsClients()
    {
        $this->zeroAmountsUpdate();
        $stmt = $this->conn->prepare('SELECT DISTINCT d.client_name FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id
where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.senderId  is null AND a.date_entered>"2020-05-28"');

        $stmt->execute();
        return $stmt->fetchAll();
    }
    function zeroAmountsUpdate()
    {

        $stmt1=$this->conn->prepare('SELECT a.id FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c 
ON b.member_id=c.member_id where clmline_scheme_paid_amnt="0.00" AND clmnline_charged_amnt <>"0.00" 
AND (LENGTH(msg_code)<3 OR msg_code is null) AND b.senderId  is null AND a.date_entered>"2020-05-28" AND c.client_id=1');

        $stmt1->execute();
        foreach ($stmt1->fetchAll() as $row)
        {
            $id=$row[0];
            $stmt2=$this->conn->prepare('UPDATE claim_line SET msg_code="003 - Benefit exclusion" WHERE id=:id');
            $stmt2->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt2->execute();

        }


    }
    function leads()
    {
        $stmt = $this->conn->prepare("SELECT lead_id,first_name,last_name,email FROM lead WHERE status=0");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

class gapNotifications extends main
{
    public $username1="";
    public $username2="";

    public function _constructor()
    {
        parent::_constructor(); // TODO: Change the autogenerated stub
        $this->username1=clients($this->username);

    }

    function totalFunction()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(a.Open) as ab FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.OPEN =1 AND (b.client_id=:num OR b.client_id=:num1)');
        $stmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $stmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function closedDate()
    {

        $fbStmt = $this->conn->prepare("SELECT a.date_entered,a.date_closed FROM claim as a inner join member as b on a.member_id=b.member_id WHERE (b.client_id=:num OR b.client_id=:num1) AND a.date_closed LIKE :dat AND a.Open=0");
        $fbStmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $fbStmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $fbStmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $fbStmt->execute();
        $nu1 = $fbStmt->rowCount();
        $totDays=0;
        if ($nu1 > 0) {
            foreach ($fbStmt->fetchAll() as $row1) {
                $d1=$row1[0];
                $d2=$row1[1];
                $datetime1 = strtotime($d1);
                $datetime2 = strtotime($d2);
                $secs = $datetime2 - $datetime1;// == <seconds between the two times>
                $days = $secs / 86400;
                $totDays+=$days;
            }}
        return $totDays;
    }
    function newFunction()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(a.new) as ab FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.new=0 AND a.Open=1 AND (b.client_id=:num OR b.client_id=:num1)');
        $stmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $stmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function schemeSavings()
    {


        $stmt = $this->conn->prepare('SELECT SUM(a.savings_scheme) as ab FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.date_closed LIKE :dat AND (b.client_id=:num OR b.client_id=:num1) AND a.Open=0 AND a.recordType IS NULL');
        $stmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();

    }
    function discountSavings()
    {

        $stmt = $this->conn->prepare('SELECT SUM(a.savings_discount) as ab FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.date_closed LIKE :dat AND (b.client_id=:num OR b.client_id=:num1) AND a.Open=0 AND a.recordType IS NULL');
        $stmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function enteredByMe()
    {
        return 0;
    }
    function totalThisMonth()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(a.claim_id) as ab FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.date_entered LIKE :dat AND Open<>2 AND (b.client_id=:num OR b.client_id=:num1)');
        $stmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function closedThisMonth()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(a.claim_id) as ab FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.date_closed LIKE :dat AND Open=0 AND (b.client_id=:num OR b.client_id=:num1)');
        $stmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function enteredAndClosed()
    {

        $stmt = $this->conn->prepare('SELECT COUNT(a.claim_id) as ab FROM claim as a inner join member as b on a.member_id=b.member_id WHERE a.date_entered LIKE :dat AND a.Open=0 AND (b.client_id=:num OR b.client_id=:num1)');
        $stmt->bindParam(':num', $this->username1, PDO::PARAM_STR);
        $stmt->bindParam(':dat', $this->from, PDO::PARAM_STR);
        $stmt->bindParam(':num1', $this->username2, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function updatedDocs()
    {
        return 0;
    }
    function members()
    {
        return 0;
    }
    function zeroAmounts()
    {
        return 0;
    }
    function zeroAmountsClients()
    {
        return 0;
    }
    function zeroAmountsUpdate()
    {
        return 0;
    }
    function leads()
    {
        return 0;
    }
}

class displayClass
{
    public function levelsDisplay()
    {

        if ($_SESSION['level'] == "claims_specialist") {
            $myDisplay=new specialistsNotifications();
            $myDisplay->all();
        } else if ($_SESSION['level'] == "admin" || $_SESSION['level'] == "controller") {
            $myDisplay=new adminNotifications();
            $myDisplay->all();

        }
        else if ($_SESSION['level'] == "gap_cover")
        {
            $myDisplay=new gapNotifications();
            $myDisplay->all();
        }
        else
        {
            die("Access Denied");
        }
    }
}

class Feedback
{
    public function flashNow()
    {
        $username=$_SESSION['user_id'];
        $fbConn = connection("mca", "MCA_admin");
        $fbStmt = $fbConn->prepare("SELECT DISTINCT a.claim_id,b.claim_number FROM intervention as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id WHERE b.username=:num AND reminder_status=1 AND a.reminder_time<= now()");
        $fbStmt->bindParam(':num', $username, PDO::PARAM_STR);
        $fbStmt->execute();
        $nu1 = $fbStmt->rowCount();

        if ($nu1 > 0) {
            echo"<div class=\"w3-container\">";
            echo"<div class=\"w3-dropdown-hover\">";
            echo"<button id=\"blink\" class=\"w3-button w3-black\">Reminder</button>";
            echo "<div class=\"w3-dropdown-content w3-bar-block w3-card-2 w3-light-grey\" id=\"myDIV\">";
            echo"<input class=\"w3-input w3-padding\" type=\"text\" placeholder=\"Search..\" id=\"myInput\" onkeyup=\"myFunction()\">";

            foreach ($fbStmt->fetchAll() as $row1) {
                $record_index=$row1[0];
                $claimN=$row1[1];
                echo "<form action='case_detail.php' method='post' />";
                echo "<input type=\"hidden\" name=\"claim_id\" value=\"$record_index\" />";
                echo "<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$claimN\">";
                echo "</form>";

            }

            echo "</div>";
            echo " </div>";
            echo"</div>";
        }

    }

}
?>