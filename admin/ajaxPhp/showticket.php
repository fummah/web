<?php
session_start();
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);
$_SESSION['start_db']=true;
require_once ("../dbconn1.php");
require_once ("../classes/ticketClass.php");
$new=new ticketClass();
$identity=(int)validateXss($_POST['identity']);
if($identity==1)
{
    $id=(int)validateXss($_POST['id']);
    $det=$new->getFirst($id);
    $_SESSION["my_idx"]=$id;
    $tracker=$det[0]["tracker"];
    $status=$det[0]["status"];
    $priority=$det[0]["priority"];
    $assignee=$det[0]["assignee"];
    $date_entered=$det[0]["date_entered"];
    $subject=$det[0]["subject"];
    $initiator=$det[0]["initiator"];
    $descr=$det[0]["descr"];
$descr=htmlspecialchars_decode($descr);

    $environment=$det[0]["environment"];
$related_to=$det[0]["related_to"];
   $username=$_SESSION["user_id"];
    $docs=$new->getallFiles($id);
    $numx=count($docs);
    $alldocs="";
    $f="";
    for($i=0;$i<$numx;$i++)
    {
        $rnd=$docs[$i]["randomNum"];
        $doc=$docs[$i]["doc_description"];
        $desc = "../../mca/tickets/" .$rnd.$doc;
        //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
        $alldocs.= "<form action='view_ticket.php' method='post' target=\"print_popup\" onsubmit=\"window.open('view_ticket.php','print_popup','width=1000,height=800');\"><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$doc\">
</form>";

    }

    $date= date('F j, Y h:i:s a ', strtotime($date_entered));
    if($_SESSION['level'] != "claims_specialist" || $username==$initiator) {
        echo "<span style='float: right; cursor: pointer' class='glyphicon glyphicon-pencil' onclick='showHide()'> Update</span>";
        echo "<span class='edit w3-animate-top' hidden> <form method=\"post\" enctype=\"multipart/form-data\">Status :  <br>  <select name=\"mystatus\" id=\"mystatus\" style='color: green' required>
                    <option value=\"Investigate\">Investigate</option>
                    <option value=\"Feedback\">Feedback</option>
                    <option value=\"Testing\">Testing</option>
                     <option value=\"Develop\">Develop</option>
                   <option value=\"Closed\">Closed</option>
                </select><br>";
    echo "Description : <textarea  cols=\"100\"  style=\"width: 100%\" id=\"issue_description\" name=\"issue_description\" rows=\"10\"></textarea><br>";

    echo "Assignee :  <br>  <select name=\"assignee\" id=\"assignee\" style='color: green' required>
 <option value=\"\">[select username]</option>
 <option value=\"Shakila\">Shakila</option>
<option value=\"Mandy\">Mandy</option>
                    <option value=\"Faghry\">Faghry</option>                  
                    <option value=\"Tendai\">Tendai</option>
<option value=\"$initiator\">$initiator</option>
                </select><br> Files  <input type=\"file\" name=\"files[]\" multiple />";
    echo"<span style='color: #00b3ee; display: none; font-weight: bolder'>Close? <input type=\"radio\" id=\"open\" name=\"Open\" value=\"1\" checked> No                     
                                <input type=\"radio\" id=\"close\" name=\"Open\" value=\"0\"\"> Yes </span>";
    echo"<br><button class='w3-btn w3-white w3-border w3-border-blue' onclick='save(\"$id\")'>Save</button><span id='suc'></span><span style='color: red' id='inf'></span></form><hr></span>";
}

    echo "<u>#$id</u> Logged by <i style='color: #00b3ee;font-weight: bolder'>".$initiator."</i> to <i style='color: #00b3ee;font-weight: bolder'>".$assignee."</i> on <b style='color: green'>".$date."</b>";
    echo "<br>Subject : <span style='color: green;font-weight: bolder'>".$subject."</span>";
    echo "<br><u>Description</u><br> <span style='color: darkslategrey;font-weight: bolder'>".nl2br($descr)."</span>";
    echo "<br><u><b>Files</b></u> <br>".$alldocs;
    echo "<br><u>Environment : </u><b style='color: green'>".$environment." ($related_to)</b></span>";
    echo "<hr>";

    $other=$new->getEntries($id);
    $xnum=count($other);
    for($f=0;$f<$xnum;$f++)
    {

        $iid=$other[$f]["id"];
        $assii=$other[$f]["assignee"];
        $assigto=$other[$f]["assigned_by"];
        $descr=$other[$f]["descr"];

$descr=htmlspecialchars_decode($descr);
        $date_entered=$other[$f]["date_entered"];
        $date= date('F j, Y h:i:s a ', strtotime($date_entered));
        echo "Transfered from  <i style='color: #00b3ee;font-weight: bolder'>".$assigto."</i> to <i style='color: #00b3ee;font-weight: bolder'>".$assii."</i> on <b style='color: green'>".$date."</b>";

        echo "<br><u>Details</u><br> <span style='color: dimgrey;font-weight: bolder'>".nl2br($descr)."</span>";

        $docs1=$new->getallFiles1($iid);
        $numx1=count($docs1);
        $alldocs1="";
        for($u=0;$u<$numx1;$u++)
        {
            $rnd1=$docs1[$u]["randomNum"];
            $doc1=$docs1[$u]["doc_description"];
            $desc = "../../mca/tickets/" .$rnd1.$doc1;
            //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";
            $alldocs1.= "<form action='view_ticket.php' method='post' target=\"print_popup\" onsubmit=\"window.open('view_ticket.php','print_popup','width=1000,height=800');\"><input type=\"hidden\" name=\"my_doc\" value=\"$desc\" />
<input type=\"submit\" class=\"linkbutton\" name=\"doc\" value=\"$doc1\">
</form>";


        }

        echo "<br><u><b>Files</b></u> <br>".$alldocs1."</span>";
        echo "<hr>";
    }
}
elseif($identity==2) {

    $issue_id = (int)validateXss($_POST['id']);
 
 $descr=filter_var($_POST['descr'], FILTER_SANITIZE_STRING);
    $assi = validateXss($_POST['assi']);
  if(empty($assi))
    {
        die ("<span class='alert alert-danger'>Select Assignee</span>");  
    }
    $op = validateXss($_POST['op']);
    $status1 = validateXss($_POST['st']);
    $cu=2;
    $assi1=$_SESSION["user_id"];
    if($new->addLog($issue_id,$assi,$cu,$descr, $assi1,$op))
    {
        $new->updateIssue($issue_id,$status1,$assi);
      $det=$new->getFirst($issue_id);
        $subject=$det[0]["subject"];
        $initiator=$det[0]["initiator"];
    $tracker=$det[0]["tracker"];
        $new->sendMail($assi,$initiator,$issue_id,$subject,$descr,$tracker,$status1,$assi1);
        echo "<span class='alert alert-success'>Successfully Saved</span>";
    }
    else{
        echo "<span class='alert alert-danger'>Failed</span>";
    }
}
?>
