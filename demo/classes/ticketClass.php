<?php
$conn=connection("mca","MCA_admin");
$conn1=connection("doc","doctors");
class ticketClass
{
    private $issue_id;
    public $sql1;
    public $val;
    public $st;

 function sendMail($transferedto,$transferedfrom,$issue_number,$subject,$content,$trcker,$status,$sender=""){
        global $mail;
        // Passing `true` enables exceptions
        try {


            //Server settings
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'mcahelpdesk@medclaimassist.co.za';                 // SMTP username
            $mail->Password = 'P@ssw0rd!';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('mcahelpdesk@medclaimassist.co.za', 'MCA Helpdesk');
          $mail->addAddress($this->checkEmail($transferedto), $transferedto);     // Add a recipient
            $mail->AddCC($this->checkEmail($transferedfrom));
            $mail->isHTML(true);
            $sub="[Case Number #$issue_number][$trcker][$status] -: $subject";
            $content=nl2br($content);
            $body="Hi $transferedto<br><br><br>Case Number #$issue_number </b>was transfered to you from $sender<br><br><u><b>Description</u><br>$content</b><br><br>MCA Helpdesk";
                // Set email format to HTML
            $mail->Subject = $sub;
            $mail->Body = $body;
            //$mail->AddAttachment('documents/' . getConsentName($scheme));
            //$mail->send();

            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Doneeee";
            }
        }

        catch (Exception $e)
        {
echo "Thereis an email error ";
        }
    }

    function insertTicket($tracker, $status, $priority, $subject, $assignee, $descr, $role,$initiator,$environment,$related_to)
    {
        $try = false;
        try {
            global $conn;
            $stmt = $conn->prepare("INSERT INTO issues(tracker, status, priority, subject, assignee,descr,current_level,initiator,environment,related_to) VALUES (:tracker, :status, :priority, :subject, :assignee,:descr,:role,:initiator,:environment,:related_to)");
            $stmt->bindParam(':tracker', $tracker, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
            $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
            $stmt->bindParam(':assignee', $assignee, PDO::PARAM_STR);
            $stmt->bindParam(':descr', $descr, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':initiator', $initiator, PDO::PARAM_STR);
$stmt->bindParam(':environment', $environment, PDO::PARAM_STR);
$stmt->bindParam(':related_to', $related_to, PDO::PARAM_STR);
            $rr = $stmt->execute();
            if ($rr == 1) {
                $try = true;
                if (isset($_FILES["file"]) && is_file($_FILES['file']['tmp_name'])) {
                    $selectlast = $conn->prepare("SELECT max(id) FROM issues WHERE assignee=:assignee");
                    $selectlast->bindParam(':assignee', $assignee, PDO::PARAM_STR);
                    $selectlast->execute();
                    $this->issue_id = $selectlast->fetchColumn();
                    $this->uploadDoc();
                }
            }
        } catch (Exception $e) {

            $try = false;
        }
        return $try;
    }

    function selectRecords($role, $username, $status,$start_from,$myst)
    {
        $rrv = "";
        $val = "";
        $st = "";
        try {
            global $conn;
            $stt = "";
            $stt2 = "0";

$closed="Closed";
            $limit=10;
            $this->sql1 = "SELECT id, tracker, status, priority, subject, assignee, date_entered, last_updated,initiator FROM issues WHERE status<>:closed";

            $sql = "SELECT id, tracker, status, priority, subject, assignee, date_entered, last_updated,initiator FROM issues WHERE status<>:closed ORDER BY id DESC LIMIT $start_from, $limit";
            if($myst=="Closed")
            {
                $this->sql1 = "SELECT id, tracker, status, priority, subject, assignee, date_entered, last_updated,initiator FROM issues WHERE status=:closed";

                $sql = "SELECT id, tracker, status, priority, subject, assignee, date_entered, last_updated,initiator FROM issues WHERE status=:closed ORDER BY id DESC LIMIT $start_from, $limit";

            }
            $stmt = $conn->prepare($sql);
       $stmt->bindParam(':closed', $closed, PDO::PARAM_STR);
            //$stmt->bindParam(':st', $this->st, PDO::PARAM_STR);
            $stmt->execute();
            $rrv = $stmt->fetchAll(PDO::FETCH_ASSOC);


        } catch (Exception $e) {

        }

        return $rrv;
    }

   function DBaddfiles($description, $size, $type, $rand,$log_id=0)
    {
       // $username = $_SESSION['user_id'];
  if($log_id!=0)
        {
            $this->issue_id=0;
        }
        $username = "Fuma";
        global $conn;
        $sql = $conn->prepare('INSERT INTO tickets(id,doc_description,doc_size,doc_type,randomNum,uploaded_by,log_id) VALUES(:id,:description,:size,:type,:rand,:uploaded_by,:log_id)');
        $sql->bindParam(':id', $this->issue_id, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->bindParam(':size', $size, PDO::PARAM_STR);
        $sql->bindParam(':type', $type, PDO::PARAM_STR);
        $sql->bindParam(':rand', $rand, PDO::PARAM_STR);
        $sql->bindParam(':uploaded_by', $username, PDO::PARAM_STR);
        $sql->bindParam(':log_id', $log_id, PDO::PARAM_STR);
        $sql->execute();

    }

    function uploadDoc()
    {
        if (isset($_FILES["file"]) && is_file($_FILES['file']['tmp_name'])) {

            $allowedExts = ['jpeg', 'jpg', 'png', "pdf", "doc", "docx", "xlsx", "xls", "txt", "PDF", "PNG", "msg", "MSG", "JPG","JPEG"];
            $fileExtensions = ['jpeg', 'jpg', 'png', "pdf", "vnd.openxmlformats-officedocument.spreadsheetml.sheet", "vnd.openxmlformats-officedocument.wordprocessingml.document", "vnd.ms-excel", "msword", "vnd.oasis.opendocument.text", "application/pdf", "PDF", "PNG", "msg", "MSG", "octet-stream"];
            $temp = explode(".", $_FILES["file"]["name"]);
            $presentExtention = end($temp);
            $type = basename($_FILES['file']['type']);
            $nname = basename($_FILES['file']['name']);
            $fileSize = $_FILES['file']['size'];
            $fileExtension = basename($_FILES['file']['type']);
            $nux = substr_count($nname, '.');
            if (in_array($presentExtention, $allowedExts) && strlen($nname) < 100 && $nux == 1 && $fileSize > 0) {
                if (in_array($fileExtension, $fileExtensions) && ($fileSize < 20000000)) {
                    $ra = rand(0, 1000);
                    $target = "../../mca/tickets/";
                    $target = $target . $ra . basename($_FILES['file']['name']);
                    $ok = 1;
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                        $redirect = "success";
                        $size = basename($_FILES['file']['size']);
                        $nname = filter_var($nname, FILTER_SANITIZE_STRING);
                       $this->DBaddfiles($nname, $size, $type, $ra);
                        echo "<span class=\"notice\" style=\"color: green\">Your file has been uploaded.</span>";


                    } else {
                        echo "<span class=\"notice\" style=\"color: red\">Sorry, Failed to upload.</span>";
                    }


                } else {
                    echo "<span class=\"notice\" style=\"color: red\">Sorry, incorrect file, failed to upload</span>";
                }
            } else {
                echo "<span class=\"notice\" style=\"color: red\">Sorry, incorrect file, failed to upload_$nname</span>";
            }

        }
    }

function getFirst($id)
{
    $rrv="";
    try {
        global $conn;

        $sql = "SELECT id, tracker, status, priority, subject, assignee, date_entered, last_updated,initiator,descr,environment,related_to FROM issues WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        //$stmt->bindParam(':st', $this->st, PDO::PARAM_STR);
        $stmt->execute();
        $rrv = $stmt->fetchAll(PDO::FETCH_ASSOC);


    } catch (Exception $e) {

    }
return $rrv;
}

    function getEntries($issue_id)
    {
        $rrv="";
        try {
            global $conn;
            $sql = "SELECT id, issue_id,descr,assignee,assigned_by, date_entered FROM issues_log WHERE issue_id=:id ORDER BY id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $issue_id, PDO::PARAM_STR);
            //$stmt->bindParam(':st', $this->st, PDO::PARAM_STR);
            $stmt->execute();
            $rrv = $stmt->fetchAll(PDO::FETCH_ASSOC);


        } catch (Exception $e) {

        }
        return $rrv;
    }

    function getFiles($issue_id)
    {
        $rrv="";
        try {
            global $conn;
            $sql = "SELECT id, issue_id,tracker, status, priority, assignee,assigned_by, date_entered FROM issues_log WHERE issue_id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $issue_id, PDO::PARAM_STR);
            //$stmt->bindParam(':st', $this->st, PDO::PARAM_STR);
            $stmt->execute();
            $rrv = $stmt->fetchAll(PDO::FETCH_ASSOC);


        } catch (Exception $e) {

        }
        return $rrv;
    }

    function getallFiles($issue_id)
    {
        $rrv="";
        try {
            global $conn;
            $sql = "SELECT randomNum,doc_description FROM tickets WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $issue_id, PDO::PARAM_STR);
            $stmt->execute();
            $rrv = $stmt->fetchAll(PDO::FETCH_ASSOC);


        } catch (Exception $e) {

        }
        return $rrv;
    }

    function addLog($issue_id,$assi,$cu,$descr,$assto,$po)
    {
        $try=false;
        try {
            global $conn;
            $stmt = $conn->prepare("INSERT INTO issues_log(issue_id, assignee,current_level, descr, assigned_by,state) VALUES (:issue_id, :assignee,:current_level, :descr, :assigned_to,:state)");
            $stmt->bindParam(':issue_id', $issue_id, PDO::PARAM_STR);
            $stmt->bindParam(':assignee', $assi, PDO::PARAM_STR);
            $stmt->bindParam(':current_level', $cu, PDO::PARAM_STR);
            $stmt->bindParam(':descr', $descr, PDO::PARAM_STR);
            $stmt->bindParam(':assigned_to', $assto, PDO::PARAM_STR);
            $stmt->bindParam(':state', $po, PDO::PARAM_STR);
            $nu = $stmt->execute();
            if($nu==1)
            {
                $this->open($issue_id,$po);
                $try=true;
            }
            else
            {
                $try=false;
            }
        }
        catch (Exception $e)
        {
            $try=false;
        }
return $try;
    }

    function  open($issue_id,$st)
    {
        global $conn;
        $stmt = $conn->prepare("UPDATE issues SET state=:st WHERE id=:id");
        $stmt->bindParam(':id', $issue_id, PDO::PARAM_STR);
        $stmt->bindParam(':st', $st, PDO::PARAM_STR);
        $stmt->execute();
    }
   function getallFiles1($log_id)
    {
        $rrv="";
        try {
            global $conn;
            $sql = "SELECT randomNum,doc_description FROM tickets WHERE log_id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $log_id, PDO::PARAM_STR);
            $stmt->execute();
            $rrv = $stmt->fetchAll(PDO::FETCH_ASSOC);


        } catch (Exception $e) {

        }
        return $rrv;
    }
 function getLog($myid)
    {
        global $conn;
        $selectlast = $conn->prepare("SELECT max(id) FROM issues_log WHERE issue_id=:id");
        $selectlast->bindParam(':id', $myid, PDO::PARAM_STR);
        $selectlast->execute();
       return $selectlast->fetchColumn();
    }
  function lastEntry()
    {
        global $conn;
        $selectlast = $conn->prepare("SELECT max(id) FROM issues");
        $selectlast->execute();
        return $selectlast->fetchColumn();
    }
  function updateIssue($myid,$status,$assignee)
    {

        try {
            global $conn;
            $stmt = $conn->prepare("UPDATE issues SET status=:st,assignee=:ass WHERE id=:num");
            $stmt->bindParam(':num', $myid, PDO::PARAM_STR);
            $stmt->bindParam(':st', $status, PDO::PARAM_STR);
 $stmt->bindParam(':ass', $assignee, PDO::PARAM_STR);
            $stmt->execute();

        }
        catch (Exception $e)
        {

        }

    }
    function checkEmail($username)
    {
        global $conn;
        $user="tendai@medclaimassist.co.za";
        $selectlast = $conn->prepare("SELECT email FROM users_information WHERE username=:user1");
        $selectlast->bindParam(':user1', $username, PDO::PARAM_STR);
        $selectlast->execute();
        $cc=$selectlast->rowCount();
        if($cc>0)
        {
            $user=$selectlast->fetchColumn();
        }

return $user;
    }
}
//$nn=new ticketClass();
//print_r($nn->getallFiles(15));