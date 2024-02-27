<style type="text/css">
    #notifications{
        float: right;
        width: 20%;
        right: 20px;
        position: fixed;
        z-index: 1;
    }
</style>

<?php
include_once "dbconn.php";
function getDetails($username)
{
    $DBconn = connection("doc", "doctors");
    $sql1 = $DBconn->prepare("SELECT email,phone,fullName FROM staff_users WHERE username=:num LIMIT 1");
    $sql1->bindParam(':num', $username, PDO::PARAM_STR);
    $sql1->execute();
    $nu1 = $sql1->rowCount();
    if ($nu1 > 0) {
        foreach ($sql1->fetchAll() as $row1) {

            echo"<div id=\"notifications\" class=\"uk-alert-primary alert-dismissible col-md-3 hidden-xs w3-panel w3-leftbar w3-border-blue\"><h5><b><u style=\"color: red\">Owner Information</u></b></h5>";
            echo "<u>";
            echo "<li>$row1[2]</li>";
            $_SESSION['getEmail']=$row1[0];
            echo"<li><a href=\"mailto:$row1[0]\" target=\"_top\">$row1[0]</a></li>";
            echo"<li>$row1[1]</li>";
            echo"</u>";
            //echo"<span id=\"hid\" title=\"Hide\" style=\"cursor:pointer; color: red\" class=\"glyphicon glyphicon-remove\"></span>";
 echo"<span  id=\"hid\"  class=\"w3-button w3-red w3-display-topright\">&times;</span></span>";
            echo"</div>";
        }
    }
}


function feedback()
{
    $username=$_SESSION['user_id'];
    $claim_id=$_SESSION['currentClaimid'];
    $fbConn = connection("mca", "MCA_admin");
    $fbStmt = $fbConn->prepare("SELECT description,date_entered,owner FROM feedback WHERE claim_id=:num");
    $fbStmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
    $fbStmt->execute();
    $nu1 = $fbStmt->rowCount();

    if ($nu1 > 0) {
        foreach ($fbStmt->fetchAll() as $row1) {

            $desc=htmlspecialchars_decode($row1[0]);

            $dateE=htmlspecialchars($row1[1]);
            $owner=htmlspecialchars($row1[2]);
            $color="";
            if($owner==$username)
            {
                $owner="You";
                $color="#3e8f3e";
            }
            $desc=$desc;
            echo"<h4 class=\"feedbackHeader\"> <b style='color: $color'>$owner</b> posted on <i style=\"color: #0d92e1\">$dateE</i>";
            echo"</h4>";
            echo "<p class='feedbackParagraph'> $desc </p>";

        }
    }
}

function sumFeedback()
{
    $claim_id=$_SESSION['currentClaimid'];
    $fbConn = connection("mca", "MCA_admin");
    $fbStmt = $fbConn->prepare("SELECT description,date_entered,owner FROM feedback WHERE claim_id=:num");
    $fbStmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
    $fbStmt->execute();
    $nu1 = htmlspecialchars($fbStmt->rowCount());
    return $nu1;
}
function clients($r)
{
    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT client_id FROM clients WHERE client_name = :name");
    $stmt->bindParam(':name', $r, PDO::PARAM_STR);
    $stmt->execute();
    $clientNameID = htmlspecialchars($stmt->fetchColumn());
    return $clientNameID;

}


function feedbackAdd()
{
    $claim_id = $_SESSION['currentClaimid'];
    $fbConn = connection("mca", "MCA_admin");
    $fbStmt = $fbConn->prepare("SELECT description,date_entered,owner FROM feedback WHERE claim_id=:num");
    $fbStmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
    $fbStmt->execute();
    $nu1 = $fbStmt->rowCount();

    if ($nu1 > 0) {
        foreach ($fbStmt->fetchAll() as $row1) {

            $desc = htmlspecialchars_decode($row1[0]);
            $desc=nl2br($desc);
            $dateE = htmlspecialchars($row1[1]);
            $owner = htmlspecialchars($row1[2]);
            echo "<tr class='alert-info'>";
            echo "<td>";
            echo $dateE;
            echo "</td>";
            echo "<td class='w3-card'>";
            echo $desc;
            echo "</td>";
            echo "<td align='center'>";
            date_default_timezone_set('Africa/Johannesburg');
            $now = date("Y-m-d h:i:sa");
            $datetime_1=strtotime($dateE);
            $datetime_2=strtotime($now);
            $secs=$datetime_2 - $datetime_1;
            $days=$secs / 86400;
            $days=round($days);
            echo $days;
            echo "</td>";
        }

    }
}

function getClientName($id)
{
    $dbh = connection("mca", "MCA_admin");
    $stmt = $dbh->prepare("SELECT client_name FROM clients WHERE client_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $clientName = htmlspecialchars($stmt->fetchColumn());
    return $clientName;

}

function files($record_index)
{

    $connD = connection("mca", "MCA_admin");
    $sqlDoc = $connD->prepare('SELECT *FROM documents WHERE claim_id=:claim');
    $sqlDoc->bindParam(':claim', $record_index, PDO::PARAM_STR);
    $sqlDoc->execute();
    $nu8 = $sqlDoc->rowCount();

    if ($nu8 > 0) {
         echo " <div class=\"uk-inline\" id='vv1'><button class=\"uk-button uk-button-default\" type=\"button\"><span uk-icon=\"cloud-download\"></span> Files</button>";
        echo "<div uk-dropdown>";
        foreach ($sqlDoc->fetchAll() as $row1) {
            $id = htmlspecialchars($row1[0]);
            $ra=htmlspecialchars($row1[6]);
            $nname = htmlspecialchars($row1[2]);
            $desc = "../../mca/documents/" . $ra.$nname;
            $type = htmlspecialchars($row1[3]);
            $size = round($row1[4] / 1024);
            $dd=(int)$row1[9];
            $ffj="";
            if($dd==1)
            {
                $ffj="style='color: red'";
            }
            //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
            echo "<form action='test5.php' method='post' target=\"print_popup\" onsubmit=\"window.open('view_doc.php','print_popup','width=1000,height=800');\"/>
<input type=\"hidden\" name=\"my_id\" value=\"$id\" />
<input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"submit\" class=\"linkbutton\" $ffj name=\"doc\" value=\"$nname\">
</form>";
        }
        echo "</div>";
        echo "</div>";
    }
}


function jvLinked($record_index)
{

    global $conn;
    $sqlDoc = $conn->prepare('SELECT claim_id,claim_number FROM claim WHERE claim_number1=:claim AND claim_number1 is not null');
    $sqlDoc->bindParam(':claim', $record_index, PDO::PARAM_STR);
    $sqlDoc->execute();
    $nu8 = $sqlDoc->rowCount();

    if ($nu8 > 0) {
        echo "<div class=\"dropdown\" id='vv1' title='Linked Claims'>";
        echo "<button class=\"dropbtn w3-border w3-border-red w3-blue\"><span class=\"glyphicon glyphicon-paperclip w3-spin\"></span> </button>";
        echo "<div class=\"dropdown-content\">";
        foreach ($sqlDoc->fetchAll() as $row1) {
            $id = (int)htmlspecialchars($row1[0]);
            $ra=htmlspecialchars($row1[1]);

            //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
            echo "<form action='case_detail.php' method='post' target=\"print_popup\" onsubmit=\"window.open('case_detail.php','print_popup','width=1000,height=800');\"/>
<input type=\"hidden\" name=\"claim_id\" value=\"$id\" />
<input type=\"submit\" class=\"linkbutton\" name=\"btn\" value=\"$ra\">
</form>";
        }
        echo "</div>";
        echo "</div>";
    }
}

function checkMofifier($claim_id,$practice_number,$practicetype)
{
    $check = false;
    if ($practicetype != "010" || $practicetype != "10") {
        $check = true;
        $count = 0;

        try {

            global $conn;

            $sqlDoc = $conn->prepare('SELECT id,mca_claim_id,practice_number,tariff_code FROM claim_line WHERE mca_claim_id=:claim AND practice_number=:prac');
            $sqlDoc->bindParam(':claim', $claim_id, PDO::PARAM_STR);
            $sqlDoc->bindParam(':prac', $practice_number, PDO::PARAM_STR);
            $sqlDoc->execute();

            foreach ($sqlDoc->fetchAll() as $row) {
                $modifier = $row[3];
                $arr = ["0109", "0192", "01970", "301", "302", "11041", "300", "303", "304", "305", "306", "307", "308", "400", "401", "402", "403", "404", "405", "406", "407", "408", "409", "410", "411", "490", "001", "0173", "130", "131", "132", "133", "134", "10010", "10020", "10090", "1100", "1101", "1102", "1103", "1110", "0174", "0175", "1031", "1037", "1039", "1071", "1188", "0145", "0146", "0147", "1221", "0148", "0149", "0153", "0161", "0162", "0163", "0164", "0017", "0166", "0167", "0168", "5783", "0169", "0190", "2392", "2616", "3009", "3010", "3035", "3117", "3251", "3252", "3280", "0191", "5793", "4587", "108", "109", "003", "004", "005", "006", "002", "014", "020", "025", "030", "230", "234", "238", "450", "708", "016", "018", "021", "023", "031", "044", "200", "201", "202", "203", "204", "205", "206", "207", "208", "209", "210", "211", "290", "309", "310", "311", "1010", "1011", "1012", "1013", "1015", "1020", "1021", "1022", "1023", "9429", "901", "903", "905", "01070"];

                if (!empty($modifier)) {
                    if (!in_array($modifier, $arr)) {
                        $count++;
                    }

                }
                $modifier5 = "0005";
                //$sub = (int)substr($modifier, 0, 1);

                if ($modifier5 == $modifier) {
                    $count = -100;
                }
            }

            if ($count < 2) {
                $check = false;
            }
        } catch (Exception $d) {

        }
    }
    return $check;
}

function checkCPT4($cpt4,$claim_id="",$practice_number="",$descipline_code="")
{
    $tr="TRCP";
    $descipline_code_array=["56","57","58","59","056","057","058","059"];
    $check="";
    $count=0;
    $count1=0;
    $count2=0;

    if(!in_array($descipline_code,$descipline_code_array) ) {

        try {
            global $conn2;
            global $conn;
            $mycp=checkAllDoctors($claim_id,$descipline_code_array);
            if(strlen($mycp)>1) {
                $stmt = $conn2->prepare('SELECT *FROM ClinicalXref WHERE clinical_xref=:xref AND xref_type=:typ');
                $stmt->bindParam(':xref', $mycp, PDO::PARAM_STR);
                $stmt->bindParam(':typ', $tr, PDO::PARAM_STR);
                $stmt->execute();
                $nu = $stmt->rowCount();

                if ($nu > 0) {
                    $count1++;
                    foreach ($stmt->fetchAll() as $row) {
                        $clinical_code = $row[0];

                        $stmt1 = $conn->prepare('SELECT id FROM claim_line WHERE mca_claim_id=:mca AND practice_number=:prac AND tariff_code=:code');
                        $stmt1->bindParam(':mca', $claim_id, PDO::PARAM_STR);
                        $stmt1->bindParam(':prac', $practice_number, PDO::PARAM_STR);
                        $stmt1->bindParam(':code', $clinical_code, PDO::PARAM_STR);
                        $stmt1->execute();
                        $nu1 = $stmt1->rowCount();
                        if ($nu1 > 0) {
                            $count++;
                        }

                    }
                } else {
                    $count1 = 0;
                    $count2 = 2;
                }
            }


        } catch (Exception $r) {
            $check = "There is an error : " . $r->getMessage();
        }
    }
    if($count1==0 || $count>0)
    {
        $check="";
        if(in_array($descipline_code,$descipline_code_array) && strlen($cpt4)<2)
        {
            $check="<span style='color: darkred;font-weight: bolder;font-style: italic'> <br>Please check corresponding procedure codes for CPT4 on invoice or call provider if needed</span>";
        }
        if($count2 == 2)
        {
            $check="<span style='color: darkred;font-weight: bolder;font-style: italic'> <br>Please check corresponding procedure codes for CPT4 on invoice or call provider if needed</span>";
        }
    }
    else{
        $check="<span style='color: darkred;font-weight: bolder;font-style: italic'> <br>Please check corresponding procedure codes for CPT4 on invoice or call provider if needed</span>";
    }

    return $check;
}

function checkAllDoctors($claim_id,$desipline_codes)
{
    global $conn;
    global $conn1;
    $mycp="";
    $stmt=$conn->prepare('SELECT claim_id,practice_number,cpt_code FROM doctors WHERE claim_id=:id');
    $stmt->bindParam(':id',$claim_id,PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll() as $row)
    {
        $practice_number=$row[1];

        $stmt1=$conn1->prepare('SELECT disciplinecode FROM person WHERE practiceno like :num UNION 
SELECT disciplinecode FROM organisation WHERE practiceno like :num');
        $stmt1->bindParam(':num',$practice_number,PDO::PARAM_STR);
        $stmt1->execute();
        $mydescpline=$stmt1->fetchColumn();

        if (in_array($mydescpline,$desipline_codes))
        {

            $mycp=$row[2];
        }


    }
    return $mycp;
}


function getPMBStatus($code)
{
    global $conn2;
    $pmbf= "N";
    try {

        $stmt = $conn2->prepare('SELECT `diag_code`,`pmb_code`,`shortdesc` FROM `Diagnosis` WHERE diag_code=:num UNION SELECT `ICD10_Code`,`pmb`,`ICD10_3_Code_Desc` FROM `diagonisis1`  WHERE ICD10_Code=:num');
        $stmt->bindParam(':num', $code, PDO::PARAM_STR);
        $stmt->execute();
        $nu = $stmt->rowCount();
        if ($nu > 0) {

            $row=$stmt->fetch();
            $pmbCode = $row[1];
            $valu = strlen($pmbCode);
            if ($valu > 1) {

                $pmbf= "Y";
            }

        } else {
           
        }
    } catch (Exception $e) {

    }

    return $pmbf;
}
function calcDoctor($practice_number,$invoice_date,$member_portion,$gap)
{
    if(!empty($invoice_date)) {
        $amnt=$gap>$member_portion?$member_portion:$gap;
        global $conn;
        date_default_timezone_set('Africa/Johannesburg');
        $from_date = date('Y-m-d', strtotime($invoice_date));
        $today=date('Y-m-d');
        $datetime1 = strtotime($from_date);
        $datetime2 = strtotime($today);
        $secs = $datetime2 - $datetime1;// == <seconds between the two times>
        $days = $secs / 86400;
        $days=(int)round($days);
        $stmt = $conn->prepare("SELECT dr_value,discount_perc,discount_value,days_number FROM discount_details WHERE practice_number=:prac AND status=1");
        $stmt->bindParam(':prac', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            $dr_value=$row[0];$discount_perc=$row[1];$discount_value=$row[2];$days_number=$row[3];
            $min=0;$max=0;
            if($days_number=="< 30")
            {
                $min=0;$max=30;
            }
            else
            {
                $rr=explode("-",$days_number);
                $min=(int)$rr[0];
                $max=(int)$rr[1];
            }

            $vak=(int)filter_var(
                $days,
                FILTER_VALIDATE_INT,
                array(
                    'options' => array(
                        'min_range' => $min,
                        'max_range' => $max
                    )
                )
            );
            if($days==$vak)
            {

              $amnt=$dr_value=="P"?($discount_perc/100)*$amnt:$amnt-$discount_value;
              return $amnt;
              break;
            }
        }

    }
    return 0;
}
function getEmail($name)
{
    global $conn1;
    $email= "";
    try {

        $stmt = $conn1->prepare('SELECT email FROM `staff_users` WHERE (fullname=:num OR other_name=:num) AND length(fullname)>1' );
        $stmt->bindParam(':num', $name, PDO::PARAM_STR);
        $stmt->execute();
        $mymail=$stmt->fetchColumn();
        $email = $stmt->rowCount()>0?"<a href='mailto:$mymail'>($mymail)</a>":"";

    } catch (Exception $e) {
        $email= "";
    }

    return $email;
}
function myreopen($claim_id)
{
         global $conn;

        $stmt = $conn->prepare('SELECT date_entered FROM `claim_line` WHERE mca_claim_id=:claim_id ORDER BY id DESC LIMIT 1' );
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        $date_entered=$stmt->fetchColumn();
        return $date_entered;
}
function clinicalNotes()
{
    $username=$_SESSION['user_id'];
    $claim_id=$_SESSION['currentClaimid'];
    $fbConn = connection("mca", "MCA_admin");
    $fbStmt = $fbConn->prepare("SELECT description,date_entered,owner FROM clinical_notes WHERE claim_id=:num");
    $fbStmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
    $fbStmt->execute();
    $nu1 = $fbStmt->rowCount();

    if ($nu1 > 0) {
        foreach ($fbStmt->fetchAll() as $row1) {

            $desc=htmlspecialchars_decode($row1[0]);

            $dateE=htmlspecialchars($row1[1]);
            $owner=htmlspecialchars($row1[2]);
            $color="";
            if($owner==$username)
            {
                $owner="You";
                $color="#3e8f3e";
            }

            echo"<h4 class=\"feedbackHeader\"> <b style='color: $color'>$owner</b> posted on <i style=\"color: #0d92e1\">$dateE</i>";
            echo"</h4>";
            echo "<p class='feedbackParagraph'>$desc</p>";

        }
    }
}

function getTarrifDesc($tariff)
{
    global $conn2;
    $fbStmt = $conn2->prepare("SELECT Description FROM `TariffMaster` WHERE `Tariff_Code`=:tarr");
    $fbStmt->bindParam(':tarr', $tariff, PDO::PARAM_STR);
    $fbStmt->execute();
    if($fbStmt->rowCount()>0)
    {
        return $fbStmt->fetchColumn();
    }
    else{
        return "";
    }
}
function getIcd10Desc($icd10)
{
    global $conn;
    $fbStmt = $conn->prepare("SELECT shortdesc FROM `diagnosis` WHERE `diag_code`=:icd");
    $fbStmt->bindParam(':icd', $icd10, PDO::PARAM_STR);
    $fbStmt->execute();
    if($fbStmt->rowCount()>0)
    {
        return $fbStmt->fetchColumn();
    }
    else{
        return "";
    }
}
function getSubscription($email)
{
    global $conn;
    $broker_name="";
 
    $fbStmt = $conn->prepare("SELECT broker_id FROM `web_clients` where email=:email");
    $fbStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $fbStmt->execute();
    if($fbStmt->rowCount()>0)
    {
        $broker_id=(int)$fbStmt->fetchColumn();
    
        $fbStmt1 = $conn->prepare('SELECT CONCAT(name," ",surname) as fullname FROM `web_clients` where client_id=:broker_id');
        $fbStmt1->bindParam(':broker_id', $broker_id, PDO::PARAM_STR);
        $fbStmt1->execute();
        if($fbStmt1->rowCount()>0)
        {
            $broker_name=$fbStmt1->fetchColumn();
        }
        else{
            $broker_name="No Broker";
        }
    }

return $broker_name;
}
?>                        