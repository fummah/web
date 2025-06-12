<?php
session_start();

if(isset($_SESSION['logxged']) && !empty($_SESSION['logxged'])) {
    //require_once('../dbconn1.php');
    $identity = (int)$_GET['identity'];    
    include_once ("email.php");
    $control=new controls();
    $obj=new email();
    $conn = connection("mca", "MCA_admin");
    $conn1 = connection("doc", "doctors");
    $conn2 = connection("cod", "Coding");
    if($identity==1)
    {
        
        $source="Internal";
        $typm = "Member";
        $claim_id=(int)$_GET['claim_id'];
        $claim_number=getMemberEmail($claim_id)[0][1];
        $client_name=getMemberEmail($claim_id)[0][2];
        echo "<form method=\"post\" action=\"mailbox/compose_email.php?sess=$claim_id\" target=\"print_popup\" onsubmit=\"window.open('#','print_popup','width=1000,height=800');\"><div class=\"row\" style=\"width: 60%; margin-left: auto;margin-right: auto;position: center\">";
        echo " <div class=\"col-md-4\">Select Recipient : <select class=\"uk-select\" name='coresp' id='coresp'><option value=\"\">[Select]</option>";
        echo "<optgroup label=\"Member\">";
        foreach (getMemberEmail($claim_id) as $row)
        {
            $email=$row[0];
            if(strlen($email)>2)
            {
                $typm = "Member";
                echo "<option value=\"$email|$typm\">$email</option>";
            }

        }
        echo "</optgroup>";
        echo "<optgroup label=\"Gap Client\">";
        foreach (getClientEmail($claim_id) as $row)
        {
            $email=$row[0];
            if(strlen($email)>2)
            {
                $typm = "Gap Provider";
                echo "<option value=\"$email|$typm\">$email</option>";
            }
        }
        echo "</optgroup>";
        echo "<optgroup label=\"Providers\">";
        foreach (getProviderEmail($claim_id) as $row)
        {
            $email=$row[0];
            if(strlen($email)>2)
            {
                $typm = "Practice";
                echo "<option value=\"$email|$typm\">$email</option>";
            }
        }
        echo "</optgroup>";
        echo "<optgroup label=\"Medical Scheme\">";
        foreach (getSchemeEmail($claim_id) as $row)
        {
            $email=$row[0];
            if(strlen($email)>2)
            {
                $typm = "Medical Scheme";
                echo "<option value=\"$email|$typm\">$email</option>";
            }
        }
        echo "</optgroup>";
        echo "</optgroup>";
        echo "<optgroup label=\"Gap Provider\">";       
            $email="fummah3@outlook.com";           
                $typm = "Gap Provider";
                echo "<option value=\"$email|$typm\">$email</option>";           
        echo "</optgroup>";
        echo "</select></div><div class=\"col-md-4\">Select Template : <select class=\"uk-select\" name='seltyp' id='template_id'><option value=\"0\">[Select Template]</option>";
        $areaar = getEmailTemplates($client_name);
        foreach ($areaar as $row)
        {
            $template = $row[0];
            $id = $row[1];
                echo "<option value=\"$template|$id\">$template</option>";            
        }
        
        echo"</select></div>";
        echo "<textarea id='areaarr' style='display: none'>".json_encode($areaar)."</textarea>";

        echo "<div class=\"col-md-4\"><br><input type=\"hidden\" name=\"myclaim_id\" id='xclaim_id' value=\"$claim_id\"><input type=\"hidden\" name=\"myclaim_number\" value=\"$claim_number\">
<button class=\"uk-button uk-button-primary empty\" id='compose'>Compose Email</button>
<button class='uk-button uk-button-primary uk-margin-small-right openEmailCompose' style='display: none' type='button' uk-toggle='target: #modal-example1'>Compose Email</button> 
<span class=\"uk-button uk-button-primary\" id='send_consent' style='display: none' onclick='activateConsend()'>Send Consent</span></div></div>
<hr class=\"uk-divider-icon\"> </form>";

        echo"<div class='xemail'>";

        //$_SESSION['email_claim_id']=$claim_id;
$arr=$obj->getAllMails($claim_id);
                                    $count=count($arr);
                                    If($count>0) {
                                        echo "<table class=\"uk-table uk-table-striped\">
                                        <thead><tr><th>Origin</th><th>Email</th><th>Subject</th><th></th><th>Date</th><th></th></tr></thead>
                                        <tbody>";
                                        foreach ($arr as $row) {
                                            $email_to=$row[0];
                                            $email_from=$row[1];
                                            $subject=$row[2];
                                            $body=$row[3];
                                            $statusx=(int)$row["status"]>0?"<span style='background-color:red;color:white'>New</span>":"";
                                            $email_source=$row[4];
                                            $id=$row[5];
                                            $date1 = new DateTime($row[6]);
                                            $date=$date1->format('d M Y, H:i:s');
                                            $ttx=$obj->checkFiles($id)?"<span uk-icon=\"tag\"></span>":"";
                                            $emltxt="uk-text-muted";
                                            $emltval="sentitems";
                                            $inicon="history";
                                            $label = "uk-label-warning";
                                            $badge1 = "Sent Item";
                                            if($email_source=="External")
                                            {
                                                $email_to=$email_from;
                                                $emltxt="uk-text-success";
                                                $emltval="inbox";
                                                $inicon="mail";
                                                $label = "uk-label-success";
                                                $badge1 = "Received";
                                            }

                                          //$grp=$obj->checkGroup($email_to,$claim_id);

                                            echo "<tr class='$emltxt'>
                                        <td>
                                            <div class='uk-label $label'>
                                              $statusx <span> $badge1</span>
                                            </div>
                                        </td>
                                        <td> <form action='mailbox/read_mail.php?sess=$claim_id' method='post' target=\"print_popup\" onsubmit=\"window.open('#','print_popup','width=1000,height=800');\">
               <input type=\"hidden\" name=\"mail_id\" value=\"$id\" />
               <input type=\"hidden\" name=\"email_from\" value=\"$email_to\" />
               <input type=\"hidden\" name=\"subject\" value=\"$subject\" />              
               <input type=\"submit\" class=\"linkbutton\" name=\"$emltval\" value=\"$email_to\">
                </form></td>
                                        
                                 
                                        <td class=\"mailbox-subject\">$subject
                                        </td>
                                        <td class=\"mailbox-attachment\"></td>
                                        <td class=\"mailbox-date\">$date</td>
                                        <td class=\"\">$ttx</td>
                                    </tr>";
                                        }
                                        echo "</tbody></table>";
                                    }
                                    else{
                                        echo "<p class='text-danger' align='center'>No emails</p>";
                                    }
                                    ?>
                                   </div>
<?php
    }
 elseif ($identity==2)
    {

        $claim_id=(int)$_GET['claim_id'];
        if(getNewmail($claim_id))
        {
            echo 1;
        }
        else{
            echo 0;
        }
    }
    elseif ($identity==3)
    {
        $claim_id=(int)$_GET['claim_id'];
        if(updateMail($claim_id))
        {
            echo 1;
        }
        else{
            echo 0;
        }
    }
    else if($identity==4)
{
    try {
      
        if(strlen($_GET["keyword"])>0) {
            $keyword=$_GET["keyword"];
            
            $xarr=getSearchedClaim($keyword);
            $ccount=count($xarr);
            $msg="";
            if($ccount>0)
            {
                $msg="<ul id=\"country-list\" class=\"uk-card uk-card-body uk-card-default\">";
                foreach ($xarr as $row)
                {
                    $claim_id=$row["claim_id"];
                    $first_name=$row["first_name"];
                    $surname=$row["surname"];
                    $claim_number=$row["claim_number"];
                    $full=$claim_number." (".$first_name." ".$surname.")";
                    $msg.="<li style=\"color: gray;\" class='cop' onClick=\"selectSearchedClaim('$claim_id','$claim_number')\"><span class=\"uk-margin-small-right\" uk-icon=\"check\"></span> $full</li>";
                }
                $msg.="</ul>";
            }
            echo $msg;
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
else if($identity==5)
{
    try {
        $claim_id=(int)$_GET["claim_id"];
        $email_id=(int)$_GET["email_id"];
      
        if(strlen($claim_id)>0 && strlen($email_id)>0) {  
           echo updateDocument($email_id,$claim_id);
        }
        else{
            echo "Failed";
        }
    }
    catch (Exception $e)
    {
        echo "There is an error ".$e;
    }
}
else if($identity==6)
{
    try {
        $txttemp = $_GET["template_id"];
        $claim_id = (int)$_GET["claim_id"];
        $temparr = explode('|',$txttemp);
        $id=(int)$temparr[1];
        $tags = $obj->getEmailTemplate($id);
        $claim_data = $control->viewSingleClaim($claim_id);
        $doctor_data = $control->viewClaimDoctor($claim_id);      

        $array = json_decode($tags["tags"], true);        
        $possible_questions = $array["possible_questions"];
        $fields = array_column($possible_questions, 'field');
        $field_string = implode(', ', $fields);
        $output ="<input type='hidden' name='template_id' id='template_id' value='$txttemp'>";
        //controls
foreach($possible_questions as $row)
{
    $field = $row["field"];
    $question = $row["question"];
    $answers = $row["answers"];
    $source = $row["source"];
    $field_type = $row["field_type"];
    $state = $row["state"];
    $value = $obj->getInputValue($field,$claim_data,$doctor_data);
    $lq = "<div class='uk-form-label'><b>$question</b></div>";
    $output .= "<div class='uk-margin'>$lq<div class='uk-form-controls'>";
    $output .= $obj->createPlainInput($field_type,$field,$answers,$value,$state,$claim_id);
    $output .= "</div'></div>";
}    
    }
    catch (Exception $e)
    {
        $output .= "There is an error ".$e->getMessage();
    }
    finally{
        echo $output;
    }
}
else if($identity==7)
{

    try {
        $ret = 0;
        $email_id=(int)$_GET["email_id"];
      if($control->callUpdateEmailStatus($email_id,$control->LoggedAs()))  
      {
        $ret = 1;
      }   
      echo $ret;   
    }
    catch (Exception $e)
    {
        echo "There is an error ".$e;
    }
}
}
else
{
    echo "<b style='color: red'>Invalid Access</b>";
}
function getSearchedClaim($keyword)
{
    global $conn;
    $keyword="%".$keyword."%";
    $stmt = $conn->prepare('SELECT a.claim_id,a.claim_number,b.first_name,b.surname FROM `claim` as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_number LIKE :keyword OR b.first_name LIKE :keyword OR surname LIKE :keyword LIMIT 5');
    $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
    //$stmt->bindParam(':group_id', $this->group_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getMemberEmail($claim_id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT b.email,a.claim_number,c.client_name FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.claim_id=:claim_id');
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getProviderEmail($claim_id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT b.email,a.practice_number FROM doctors as a INNER JOIN doctor_details as b ON a.practice_number=b.practice_number WHERE a.claim_id=:claim_id');
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getSchemeEmail($claim_id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT s.email FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN `schemes` as s ON b.medical_scheme=s.name WHERE a.claim_id=:claim_id');
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getClientEmail($claim_id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT c.client_email FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.reporting_client_id WHERE a.claim_id=:claim_id');
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}
function getNewmail($claim_id)
{
    global $conn;
    $stmt = $conn->prepare('SELECT DISTINCT  b.claim_number,b.claim_id FROM `emails` as a INNER JOIN claim as b ON a.claim_id=b.claim_id WHERE a.status=1 AND a.claim_id=:claim_id');
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount()>0)
    {
        return true;
    }
    else{
        return false;
    }
}
function updateMail($claim_id)
{
    global $conn;
    $emsource="External";
    $stmt = $conn->prepare('UPDATE `emails` SET status=0 WHERE claim_id=:claim_id AND email_source=:emsource');
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':emsource', $emsource, PDO::PARAM_STR);
    $nu=$stmt->execute();
    if($nu>0)
    {
        return true;
    }
    else{
        return false;
    }
}

function getEmailTemplates($client = "Individual")
{
       
    global $conn;
    $client = $client == "Individual"?"Individual":"Gap Client";
    $stmt = $conn->prepare("SELECT template,id,recipient FROM `email_templates` WHERE client = :client AND template <>'Auto response'");
    $stmt->bindParam(':client', $client, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}
function updateEmail($email_id,$claim_id)
{
     global $conn;
    global $control;
    $date_modified = date("Y-m-d H:i:s");
    $archived_by = $control->loggedAs();
    $stmt = $conn->prepare("UPDATE `emails` SET claim_id=:claim_id, archived_by=:archived_by, date_modified = :date_modified WHERE id = :email_id");
    $stmt->bindParam(':email_id', $email_id, PDO::PARAM_STR);
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    $stmt->bindParam(':archived_by', $archived_by, PDO::PARAM_STR);
    $stmt->bindParam(':date_modified', $date_modified, PDO::PARAM_STR);
    return $stmt->execute();
}
function updateDocument($email_id,$claim_id)
{
    global $conn;
    updateEmail($email_id,$claim_id);
    $stmt = $conn->prepare("UPDATE `documents` SET claim_id=:claim_id WHERE email_id = :email_id");
    $stmt->bindParam(':email_id', $email_id, PDO::PARAM_STR);
    $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
    return $stmt->execute();
}

?>