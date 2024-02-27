<?php
$_SESSION['start_db']=true;

class leadClass
{
    public $mcclaim_id;
    public $calls_total=10;
    public $emails_total=9;

    function fetchLeads($start_from, $limit,$status=0,$role="admin",$username="")
    {
        try {
            global $conn;
            if($role=="claims_specialist")
            {
                $condition = "username = :num";
            }
            else{
                $condition=":num";
                $username=1;
            }
            $stmt = $conn->prepare("SELECT lead_id,first_name,last_name,email,contact_number,medical_scheme,scheme_number,amount_claimed,description,date_entered,username,status FROM lead WHERE status=:status AND $condition ORDER BY date_entered DESC LIMIT $start_from, $limit");
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(':num', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function fetchAllLeads($status=0,$role="admin",$username="")
    {
        try {
            global $conn;
            if($role=="claims_specialist")
            {
                $condition = "username = :num";
            }
            else{
                $condition=":num";
                $username=1;
            }
            $stmt = $conn->prepare("SELECT lead_id,first_name,last_name,email,contact_number,medical_scheme,scheme_number,amount_claimed,description,date_entered,username,status FROM lead WHERE status=:status AND $condition ORDER BY date_entered DESC");
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(':num', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getPreassessed($role,$username)
    {
        global $conn;
        if($role=="claims_specialist")
        {
            $condition = "(username = :num OR preassessor=:num)";
        }
        else{
            $condition=":num";
            $username=1;
        }
        $stmt1 = $conn->prepare("SELECT claim_id,b.first_name,b.surname,a.claim_number,a.username,a.preassessor FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE Open=5 AND $condition");
        $stmt1->bindParam(':num', $username, PDO::PARAM_STR);
        $stmt1->execute();
        return $stmt1->fetchAll();

    }


    function fetchSearch($val,$start_from, $limit,$status=0,$role="admin",$username="")
    {
        try {
            global $conn;
            if($role=="claims_specialist")
            {
                $condition = "username = :num";
            }
            else{
                $condition=":num";
                $username=1;
            }
            $val = "%" . $val . "%";
            $stmt = $conn->prepare("SELECT lead_id,first_name,last_name,email,contact_number,medical_scheme,scheme_number,amount_claimed,description,date_entered,username,status FROM lead WHERE status=:status AND (first_name LIKE :val OR last_name LIKE :val OR email LIKE :val OR scheme_number LIKE :val) AND $condition ORDER BY date_entered DESC LIMIT $start_from, $limit");
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":val",$val,PDO::PARAM_STR);
            $stmt->bindParam(':num', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function fetchAllSearch($val,$status=0,$role="admin",$username="")
    {
        try {
            global $conn;
            if($role=="claims_specialist")
            {
                $condition = "username = :num";
            }
            else{
                $condition=":num";
                $username=1;
            }
            $val = "%" . $val . "%";
            $stmt = $conn->prepare("SELECT lead_id,first_name,last_name,email,contact_number,medical_scheme,scheme_number,amount_claimed,description,date_entered,username,status FROM lead WHERE status=:status AND (first_name LIKE :val OR last_name LIKE :val OR email LIKE :val OR scheme_number LIKE :val) AND $condition ORDER BY date_entered DESC");
            $stmt->bindParam(":status",$status,PDO::PARAM_STR);
            $stmt->bindParam(":val",$val,PDO::PARAM_STR);
            $stmt->bindParam(':num', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function notes($lead_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT description,date_entered,entered_by FROM lead_notes WHERE lead_id=:lead_id');
            $st->bindParam(':lead_id', $lead_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getDetails($lead_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT lead_id,first_name,last_name,email,contact_number,medical_scheme,scheme_number,amount_claimed,description,date_entered,username,status,claim_id FROM lead WHERE lead_id=:lead_id');
            $st->bindParam(':lead_id', $lead_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetch();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getFiles($lead_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT *FROM leads_file WHERE lead_id=:lead_id');
            $st->bindParam(':lead_id', $lead_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getMember($email)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT member_id FROM member WHERE email=:email AND client_id=4 AND (email is not null OR email<>"")');
            $st->bindParam(':email', $email, PDO::PARAM_STR);
            $st->execute();
            return (int)$st->fetchColumn();
        }
        catch (Exception $e)
        {
            return (int)$e->getMessage();
        }
    }

    function addMember($first_name,$last_name,$email,$contact_number="",$scheme_name = "Unknown",$scheme_number="",$client_id = 4,$scheme_option="",$telephone="",$id_number="")
    {

        global $conn;
        $tempmember=$this->getMember($email);
        //$tempmember=0;
        if($tempmember<1) {
            $entered_by = "System";
            $policy_number = "-----";


            try {
                $sql = $conn->prepare('INSERT INTO `member`(`client_id`, `policy_number`,`first_name`,`surname`,`email`,`cell`,`scheme_number`,`medical_scheme`,`entered_by`,`scheme_option`,`telephone`,`id_number`) VALUES (:client_id,:policy_number,:first_name,:surname,:email,:cell,:scheme_number,:medical_scheme,:entered_by,:scheme_option,:telephone,:id_number)');
                $sql->bindParam(':client_id', $client_id, PDO::PARAM_STR);
                $sql->bindParam(':policy_number', $policy_number, PDO::PARAM_STR);
                $sql->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $sql->bindParam(':surname', $last_name, PDO::PARAM_STR);
                $sql->bindParam(':email', $email, PDO::PARAM_STR);
                $sql->bindParam(':cell', $contact_number, PDO::PARAM_STR);
                $sql->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
                $sql->bindParam(':medical_scheme', $scheme_name, PDO::PARAM_STR);
                $sql->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
                $sql->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
                $sql->bindParam(':telephone', $telephone, PDO::PARAM_STR);
                $sql->bindParam(':id_number', $id_number, PDO::PARAM_STR);
                $result = $sql->execute();

                if ($result == 1) {

                    $checkClaim = $conn->prepare('SELECT MAX(member_id) FROM member WHERE client_id=:client_id');
                    $checkClaim->bindParam(':client_id', $client_id, PDO::PARAM_STR);
                    $checkClaim->execute();
                    $tempmember = $checkClaim->fetchColumn();
                }
            } catch (Exception $r) {
                echo "Error ; " . $r->getMessage();
            }
        }
        return $tempmember;
    }
    function addClaim($first_name,$last_name,$email,$contact_number,$scheme_name,$scheme_number,$username,$charged_amnt,$lead_id)
    {

        global $conn;
        $tr=false;
        try {

            $member_id = $this->addMember($first_name,$last_name,$email,$contact_number,$scheme_name,$scheme_number);

            $claim_number=$this->createNumber();

            $sql = $conn->prepare('INSERT INTO `claim`(`member_id`, `claim_number`,`username`,`createdBy`,charged_amnt) VALUES (:member_id,:claim_number,:username,:createdBy,:charged_amnt)');
            $sql->bindParam(':member_id', $member_id, PDO::PARAM_STR);
            $sql->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
            $sql->bindParam(':username', $username, PDO::PARAM_STR);
            $sql->bindParam(':createdBy', $username, PDO::PARAM_STR);
            $sql->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
            $tr1=$sql->execute();
            if($tr1==1)
            {
                $checkClaim = $conn->prepare('SELECT MAX(claim_id) FROM claim WHERE username=:username');
                $checkClaim->bindParam(':username', $username, PDO::PARAM_STR);
                $checkClaim->execute();
                $claim_id = $checkClaim->fetchColumn();

                $this->mcclaim_id=$claim_id;
                $this->getaFiles($lead_id,$claim_id,$username);
                $this->updateLead($lead_id,1,$claim_id);
                $tr=true;
            }


        }
        catch (Exception $ex)
        {

        }
        return $tr;
    }

    function createNumber($client=4)
    {
        global $conn;
        $claim_number="";
        if($client==31)
        {
            $stmt = $conn->prepare('SElECT a.claim_number FROM claim as a inner join member as b on a.member_id=b.member_id WHERE claim_number LIKE "%ASPEN%" ORDER BY claim_number DESC LIMIT 1');

            $stmt->execute();
            if($stmt->rowCount()<1)
            {
                die("ASPEN0001");
            }
            $row=$stmt->fetch();
            $newClaim = $row[0];
            $str = "1" . substr($newClaim, 5);

            $str = $str + 1;
            $finalG = substr($str, 1);
            $claim_number = "ASPEN" . $finalG;
        }
        else {
            $stmt1 = $conn->prepare('SElECT a.claim_number FROM claim as a inner join member as b on a.member_id=b.member_id WHERE b.client_id=4 OR b.client_id=26 ORDER BY a.claim_number DESC LIMIT 1');
            $stmt1->execute();
            $row1 = $stmt1->fetch();
            $newClaim = $row1[0];
            $str = "1" . substr($newClaim, 3);
            $str = $str + 1;
            $finalG = substr($str, 1);
            $claim_number = "MCA" . $finalG;
        }
        return $claim_number;
    }
    function updateLead($lead_id,$val,$claim_id=0)
    {

        global $conn;
        try {

            $sql = $conn->prepare('UPDATE lead SET status=:status,claim_id=:claim_id WHERE lead_id=:lead_id');
            $sql->bindParam(':lead_id', $lead_id, PDO::PARAM_STR);
            $sql->bindParam(':status', $val, PDO::PARAM_STR);
            $sql->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $sql->execute();
        }
        catch (Exception $ex)
        {

        }

    }
    function getaFiles($lead_id,$claim_id,$uploaded_by)
    {
        global $conn;
        $stmt1 = $conn->prepare('SELECT `file_type`, `file_name`, `file_size`, `lead_id`,`random` FROM `leads_file` WHERE lead_id=:lead_id');
        $stmt1->bindParam(':lead_id', $lead_id, PDO::PARAM_STR);
        $stmt1->execute();
        foreach ($stmt1->fetchAll() as $row)
        {
            $filePath = '../../mca/leads/'.$row[4].$row[1];
            $size=filesize($filePath);
            $type=filetype($filePath);
            if(file_exists($filePath))
            {
                /* Store the path of destination file */
                $destinationFilePath = '../../mca/documents/'.$row[4].$row[1];

                /* Move File from images to copyImages folder */
                if( !rename($filePath, $destinationFilePath) ) {

                }
                else {

                    $this->insertFiles($claim_id,$row[1],$row[0],$row[2],$row[4],$uploaded_by);
                }
            }

        }

    }
    function insertFiles($claim_id,$doc_description,$doc_type,$doc_size,$randomNum,$uploaded_by)
    {
        global $conn;
        $stmt1 = $conn->prepare('INSERT INTO `documents`(`claim_id`,`doc_description`,`doc_type`,`doc_size`,`randomNum`,`uploaded_by`) VALUES (:claim_id,:doc_description,:doc_type,:doc_size,:randomNum,:uploaded_by)');
        $stmt1->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt1->bindParam(':doc_description', $doc_description, PDO::PARAM_STR);
        $stmt1->bindParam(':doc_size', $doc_size, PDO::PARAM_STR);
        $stmt1->bindParam(':doc_type', $doc_type, PDO::PARAM_STR);
        $stmt1->bindParam(':randomNum', $randomNum, PDO::PARAM_STR);
        $stmt1->bindParam(':uploaded_by', $uploaded_by, PDO::PARAM_STR);
        $stmt1->execute();

    }
    function getclinical()
    {
        global $conn;
        $dd="This claim was sent for clinical review.";
        $stmt1 = $conn->prepare('SELECT a.claim_id,b.first_name,b.surname,a.claim_number,a.username,k.date_entered FROM claim as a INNER JOIN intervention as k ON a.claim_id=k.claim_id INNER JOIN member as b ON a.member_id=b.member_id WHERE Open=4 AND k.intervention_desc=:nnot ORDER BY k.date_entered ASC');
        $stmt1->bindParam(':nnot', $dd, PDO::PARAM_STR);
        $stmt1->execute();
        return $stmt1->fetchAll();

    }
//This claim was sent for clinical review.
    function sendMail($email,$first_name,$lead_id)
    {
        global $mail;
        $data=$this->getEncrpass();
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //Server settings
            //$mail->SMTPDebug = 2;
            $body="Dear $first_name<br><br>Med ClaimAssist has evaluated your request and it's possible that we may be able to address the relevant stakeholders in order to get to a positive outcome.If you would like us to proceed with your request, you may subscribe to any one of our services.<br><br><a href='https://medclaimassist.co.za/select_subscription'>Med ClaimAssist Subscription.</a><br><br>Yours Sincerely,<br>The Med ClaimAssist Team";
            $subject="Medclaim Assist subscription request";
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $data[0];                 // SMTP username
            $mail->Password = $data[1];                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($data[0], 'Medclaim Assist');
            $mail->addAddress($email, $first_name);
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            //$mail->AddAttachment('../../mca/schemes/' . getConsentDetails($scheme, $spName));
            //$mail->send();

            if (!$mail->send()) {


            }
            $this->updateLead($lead_id,3);
        }
    }
    function getEncrpass()
    {
        global  $conn;
        $stmt = $conn->prepare("SELECT notification_email,notification_password FROM email_configs");
        $stmt->execute();
        return $stmt->fetch();
    }
    function getLead($email)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT lead_id,username,amount_claimed FROM lead WHERE email=:email AND status=3');
            $st->bindParam(':email', $email, PDO::PARAM_STR);
            $st->execute();
            return $st->fetch();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }


    function getNewQuality($status,$role="admin",$username="")
    {
        global $conn;
        if($role=="claims_specialist")
        {
            $condition = "username = :num";
        }
        else{
            $condition="1";
        }

        $sql="SELECT DISTINCT claim_id,b.first_name,b.surname,a.claim_number,b.policy_number,a.username,c.client_name FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE quality=1 AND $condition";
        if($status==1)
        {
            $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE qa_signed=1 AND cs_signed=1 AND $condition";
        }
        elseif ($status==2)
        {
            $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE qa_signed=1 AND cs_signed=0 AND quality=2  AND $condition";
        }
        $stmt1 = $conn->prepare($sql);
        $stmt1->bindParam(':num', $username, PDO::PARAM_STR);
        $stmt1->execute();
        return $stmt1->fetchAll();

    }
    function getNewAllQuality($start_from, $limit,$status,$role="admin",$username="")
    {
        global $conn;
        if($role=="claims_specialist")
        {
            $condition = "username = :num";
        }
        else{
            $condition="1";
        }
        $sql="SELECT DISTINCT claim_id,b.first_name,b.surname,a.claim_number,b.policy_number,a.username,c.client_name,a.sla FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE quality=1 AND $condition ORDER BY a.date_closed DESC LIMIT $start_from, $limit";
        if($status==1)
        {
            $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name,b.sla FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE qa_signed=1 AND cs_signed=1 AND $condition ORDER BY a.id DESC LIMIT $start_from, $limit";
        }
        elseif($status==2)
        {
            $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name,b.sla FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE qa_signed=1 AND cs_signed=0 AND quality=2 AND $condition ORDER BY a.id DESC LIMIT $start_from, $limit";
        }
        $stmt1 = $conn->prepare($sql);
        $stmt1->bindParam(':num', $username, PDO::PARAM_STR);
        $stmt1->execute();
        return $stmt1->fetchAll();

    }
    function getSearchQuality($val,$start_from, $limit,$status=0,$role="admin",$username="")
    {
        try {
            global $conn;
            if($role=="claims_specialist")
            {
                $condition = "username = :num";
            }
            else{
                $condition="1";
            }
            $val = "%" . $val . "%";
            $sql="SELECT DISTINCT claim_id,b.first_name,b.surname,a.claim_number,b.policy_number,a.username,c.client_name,a.sla FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE quality=1 AND (first_name LIKE :val OR surname LIKE :val OR client_name LIKE :val OR username LIKE :val OR b.claim_number LIKE :val OR c.policy_number LIKE :val) AND $condition ORDER BY a.date_closed DESC LIMIT $start_from, $limit";
            if($status==1)
            {
                $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name,b.sla FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE (first_name LIKE :val OR surname LIKE :val OR client_name LIKE :val OR username LIKE :val OR b.claim_number LIKE :val OR c.policy_number LIKE :val) AND qa_signed=1 AND cs_signed=1 AND $condition ORDER BY a.date_entered DESC LIMIT $start_from, $limit";
            }
            elseif($status==2)
            {
                $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name,b.sla FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE (first_name LIKE :val OR surname LIKE :val OR client_name LIKE :val OR username LIKE :val OR b.claim_number LIKE :val OR c.policy_number LIKE :val) AND qa_signed=1 AND cs_signed=0 AND quality=2 AND $condition ORDER BY a.date_entered DESC LIMIT $start_from, $limit";
            }
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":val",$val,PDO::PARAM_STR);
            $stmt->bindParam(':num', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getSearchAllQuality($val,$status=0,$role="admin",$username="")
    {
        try {
            global $conn;
            if($role=="claims_specialist")
            {
                $condition = "username = :num";
            }
            else{
                $condition="1";
            }
            $val = "%" . $val . "%";
            $sql="SELECT DISTINCT claim_id,b.first_name,b.surname,a.claim_number,b.policy_number,a.username,c.client_name FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE quality=1 AND (first_name LIKE :val OR surname LIKE :val OR client_name LIKE :val OR username LIKE :val OR b.claim_number LIKE :val OR c.policy_number LIKE :val) AND $condition ORDER BY a.date_closed DESC";
            if($status==1)
            {
                $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE (first_name LIKE :val OR surname LIKE :val OR client_name LIKE :val OR username LIKE :val OR b.claim_number LIKE :val OR c.policy_number LIKE :val) AND qa_signed=1 AND cs_signed=1 AND $condition ORDER BY a.date_entered DESC";
            }
            elseif($status==2)
            {
                $sql="SELECT DISTINCT a.claim_id,c.first_name,c.surname,b.claim_number,c.policy_number,b.username,d.client_name FROM quality_assurance as a INNER JOIN claim as b ON a.claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id WHERE (first_name LIKE :val OR surname LIKE :val OR client_name LIKE :val OR username LIKE :val OR b.claim_number LIKE :val OR c.policy_number LIKE :val) AND qa_signed=1 AND cs_signed=0 AND quality=2 AND $condition ORDER BY a.date_entered DESC";
            }
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":val",$val,PDO::PARAM_STR);
            $stmt->bindParam(':num', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getQualityDetails($claim_id)
    {
        try {
            global $conn;
            $stmt = $conn->prepare("SELECT *FROM quality_assurance WHERE claim_id=:claim_id ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(":claim_id",$claim_id,PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount()>0)
            {
                return $stmt->fetch();
            }
            else{
                return [];
            }

        }
        catch (Exception $e)
        {
            return [];
        }
    }
    function getmemberDetails($claim_id)
    {
        try {
            global $conn;
            $stmt = $conn->prepare("SELECT b.first_name,b.surname,a.claim_number,b.policy_number,a.username,a.quality FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE claim_id=:claim_id");
            $stmt->bindParam(":claim_id",$claim_id,PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount()>0)
            {
                return $stmt->fetch();
            }
            else{
                return [];
            }

        }
        catch (Exception $e)
        {
            return [];
        }
    }

    function insertQuality($claim_id,$entered_by,$asssessment_score,$data1,$data2,$data3,$data4,$data5,$sla17,$data7,$sla19,$data9,$sla16,$auto2,$auto3,$sla18,$auto5,$sla20,$sla21,$sla22,$auto9,$auto10,$calls1,$calls2,$calls3,$calls4,$calls5,$calls6,$calls7,$calls8,$calls9,$calls10,$calls11,$sla1,$sla2,$sla3,$sla4,$sla5,$sla6,$sla7,$sla8,$sla9,$sla10,$sla11,$sla12,$sla13,$sla14,$sla15,$emails1,$emails2,$emails3,$emails4,$emails5,$emails6,$emails7,$emails8,$emails9,$emails10)
    {
        try {
            global $conn;
            $stmt1 = $conn->prepare('INSERT INTO quality_assurance(claim_id,entered_by,assessment_score, data1, data2, data3, data4, data5, sla17, 
data7, sla19, data9, sla16, auto2, auto3, sla18, auto5, sla20, sla21, sla22, auto9, auto10, calls1, calls2, calls3, calls4, calls5, calls6, calls7, calls8, calls9, 
calls10, calls11,sla1, sla2, sla3, sla4, sla5, sla6, sla7, sla8, sla9,sla10, sla11, sla12, sla13, sla14, sla15,emails1, emails2, emails3, emails4, emails5, emails6, emails7, emails8, emails9,emails10) VALUES (:claim_id,:entered_by,:asssessment_score, :data1, :data2, :data3, :data4, :data5, :sla17, 
:data7, :sla19, :data9, :sla16, :auto2, :auto3, :sla18, :auto5, :sla20, :sla21, :sla22, :auto9, :auto10, :calls1, :calls2, :calls3, :calls4, :calls5, :calls6, :calls7, :calls8, :calls9, 
:calls10, :calls11,:sla1,:sla2,:sla3,:sla4,:sla5,:sla6,:sla7,:sla8,:sla9,:sla10,:sla11,:sla12,:sla13,:sla14,:sla15,:emails1,:emails2,:emails3,:emails4,:emails5,:emails6,:emails7,:emails8,:emails9,:emails10)');
            $stmt1->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt1->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $stmt1->bindParam(':asssessment_score', $asssessment_score, PDO::PARAM_STR);
            $stmt1->bindParam(':data1', $data1, PDO::PARAM_STR);
            $stmt1->bindParam(':data2', $data2, PDO::PARAM_STR);
            $stmt1->bindParam(':data3', $data3, PDO::PARAM_STR);
            $stmt1->bindParam(':data4', $data4, PDO::PARAM_STR);
            $stmt1->bindParam(':data5', $data5, PDO::PARAM_STR);
            $stmt1->bindParam(':sla17', $sla17, PDO::PARAM_STR);
            $stmt1->bindParam(':data7', $data7, PDO::PARAM_STR);
            $stmt1->bindParam(':sla19', $sla19, PDO::PARAM_STR);
            $stmt1->bindParam(':data9', $data9, PDO::PARAM_STR);
            $stmt1->bindParam(':sla16', $sla16, PDO::PARAM_STR);
            $stmt1->bindParam(':auto2', $auto2, PDO::PARAM_STR);
            $stmt1->bindParam(':auto3', $auto3, PDO::PARAM_STR);
            $stmt1->bindParam(':sla18', $sla18, PDO::PARAM_STR);
            $stmt1->bindParam(':auto5', $auto5, PDO::PARAM_STR);
            $stmt1->bindParam(':sla20', $sla20, PDO::PARAM_STR);
            $stmt1->bindParam(':sla21', $sla21, PDO::PARAM_STR);
            $stmt1->bindParam(':sla22', $sla22, PDO::PARAM_STR);
            $stmt1->bindParam(':auto9', $auto9, PDO::PARAM_STR);
            $stmt1->bindParam(':auto10', $auto10, PDO::PARAM_STR);
            $stmt1->bindParam(':calls1', $calls1, PDO::PARAM_STR);
            $stmt1->bindParam(':calls2', $calls2, PDO::PARAM_STR);
            $stmt1->bindParam(':calls3', $calls3, PDO::PARAM_STR);
            $stmt1->bindParam(':calls4', $calls4, PDO::PARAM_STR);
            $stmt1->bindParam(':calls5', $calls5, PDO::PARAM_STR);
            $stmt1->bindParam(':calls6', $calls6, PDO::PARAM_STR);
            $stmt1->bindParam(':calls7', $calls7, PDO::PARAM_STR);
            $stmt1->bindParam(':calls8', $calls8, PDO::PARAM_STR);
            $stmt1->bindParam(':calls9', $calls9, PDO::PARAM_STR);
            $stmt1->bindParam(':calls10', $calls10, PDO::PARAM_STR);
            $stmt1->bindParam(':calls11', $calls11, PDO::PARAM_STR);
            $stmt1->bindParam(':sla1', $sla1, PDO::PARAM_STR);
            $stmt1->bindParam(':sla2', $sla2, PDO::PARAM_STR);
            $stmt1->bindParam(':sla3', $sla3, PDO::PARAM_STR);
            $stmt1->bindParam(':sla4', $sla4, PDO::PARAM_STR);
            $stmt1->bindParam(':sla5', $sla5, PDO::PARAM_STR);
            $stmt1->bindParam(':sla6', $sla6, PDO::PARAM_STR);
            $stmt1->bindParam(':sla7', $sla7, PDO::PARAM_STR);
            $stmt1->bindParam(':sla8', $sla8, PDO::PARAM_STR);
            $stmt1->bindParam(':sla9', $sla9, PDO::PARAM_STR);
            $stmt1->bindParam(':sla10', $sla10, PDO::PARAM_STR);
            $stmt1->bindParam(':sla11', $sla11, PDO::PARAM_STR);
            $stmt1->bindParam(':sla12', $sla12, PDO::PARAM_STR);
            $stmt1->bindParam(':sla13', $sla13, PDO::PARAM_STR);
            $stmt1->bindParam(':sla14', $sla14, PDO::PARAM_STR);
            $stmt1->bindParam(':sla15', $sla15, PDO::PARAM_STR);
            $stmt1->bindParam(':emails1', $emails1, PDO::PARAM_STR);
            $stmt1->bindParam(':emails2', $emails2, PDO::PARAM_STR);
            $stmt1->bindParam(':emails3', $emails3, PDO::PARAM_STR);
            $stmt1->bindParam(':emails4', $emails4, PDO::PARAM_STR);
            $stmt1->bindParam(':emails5', $emails5, PDO::PARAM_STR);
            $stmt1->bindParam(':emails6', $emails6, PDO::PARAM_STR);
            $stmt1->bindParam(':emails7', $emails7, PDO::PARAM_STR);
            $stmt1->bindParam(':emails8', $emails8, PDO::PARAM_STR);
            $stmt1->bindParam(':emails9', $emails9, PDO::PARAM_STR);
            $stmt1->bindParam(':emails10', $emails10, PDO::PARAM_STR);

            return $stmt1->execute();
        }
        catch (Exception $e)
        {
            echo "There is an Error ".$e;
        }
    }


    function updateQuality($claim_id,$st=2)
    {

        global $conn;
        try {

            $sql = $conn->prepare('UPDATE claim SET quality=:st WHERE claim_id=:claim_id');
            $sql->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $sql->bindParam(':st', $st, PDO::PARAM_STR);
            $sql->execute();
        }
        catch (Exception $ex)
        {

        }

    }

    function getQAnotes($claim_id)
    {
        try {
            global $conn;
            $stmt = $conn->prepare("SELECT * FROM qa_notes WHERE claim_id=:claim_id");
            $stmt->bindParam(":claim_id",$claim_id,PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount()>0)
            {
                return $stmt->fetchAll();
            }
            else{
                return [];
            }

        }
        catch (Exception $e)
        {
            return [];
        }
    }
    function sendMail1($email,$subject,$body)
    {
        global $mail;
        $data=$this->getEncrpass();
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //Server settings
            //$mail->SMTPDebug = 2;

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $data[0];                 // SMTP username
            $mail->Password = $data[1];                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($data[0], 'Medclaim Assist');
            $mail->addAddress($email, "User");
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            //$mail->AddAttachment('../../mca/schemes/' . getConsentDetails($scheme, $spName));
            //$mail->send();

            if (!$mail->send()) {


            }

        }
    }
    function getClaimNumber($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT claim_number FROM claim WHERE claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getUserEmail($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT email FROM claim as a INNER JOIN users_information as b ON a.username=b.username WHERE a.claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    function getClaim($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT a.claim_id,b.member_id,b.first_name, b.surname,a.claim_number, b.medical_scheme, b.scheme_option, a.date_closed, b.client_id, 
a.Open, id_number, a.username, b.scheme_number,b.email,b.cell,b.telephone,a.pmb,a.icd10,a.Service_Date,a.emergency,a.end_date,a.senderId,a.date_entered,b.consent_descr,
       a.cpt_code,b.broker,a.createdBy,a.date_reopened,a.medication_value,patient_dob,a.fusion_done,a.code_description,a.modifier,a.reason_code,c.client_name,a.contact_person_email,a.patient_gender,a.patient_idnumber
 FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function getclaimFiles($claim_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT *FROM documents WHERE claim_id=:claim_id');
            $st->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getProviders($claim_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT DISTINCT a.practice_number,b.name_initials,b.surname,a.prescription,a.administering FROM doctors as a LEFT JOIN doctor_details as b ON a.practice_number=b.practice_number WHERE claim_id=:claim_id AND display is null');
            $st->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function notesClaim($claim_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT intervention_desc,date_entered,owner,intervention_id FROM intervention WHERE claim_id=:claim_id');
            $st->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function getPatient($claim_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT patient_name FROM patient WHERE claim_id=:claim_id');
            $st->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $st->execute();
            return $st->rowCount()>0?$st->fetchColumn():"";
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function getAspen($claim_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT *FROM aspen_checklist WHERE claim_id=:claim_id');
            $st->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetch();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function calcVal($str,$val,$arr)
    {
        $tot=0;
        for($i=1;$i<$val+1;$i++)
        {
            $mystr=$str.$i;
            $indv=(int)$arr[$mystr];
            //echo "$i ++++ $indv ----";
            $indv=($str=="data" || $str=="sla") && $indv==2 ? 1 : $indv;

            if($str=="emails" && $indv==101)
            {
                $this->emails_total-=1;
            }
            if($str=="calls" && $indv==101)
            {
                $this->calls_total-=1;
            }
            $indv=$indv==101?0 : $indv;
            //echo "$indv <br>";
            $tot=$tot+$indv;
        }
        //echo "<hr>";
        return $tot;
    }
    function getDraft($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT a.claim_id FROM claim as a INNER JOIN quality_assurance as b ON a.claim_id=b.claim_id WHERE a.claim_id=:claim_id AND a.quality=1");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount()>0?true:false;
    }
    function addClaimMain($member_id,$claim_number,$Service_Date,$icd10,$pmb,$charged_amnt,$scheme_paid,$gap,$username,$emergency,$entered_by,$end_date,$client_gap,$category_type,$medication_value,$patient_dob,$fusion_done,$code_description,$modifier,$reason_code,$person_email,$patient_gender,$patient_idnumber)
    {
        global $conn;

        $ret=false;
        $insert = $conn->prepare('INSERT INTO `claim`(`member_id`,`claim_number`, `Service_Date`, `icd10`, `pmb`, `charged_amnt`,`scheme_paid`, `gap`,`username` ,`emergency`, `createdBy`,`end_date`,`client_gap`,`category_type`,medication_value,patient_dob,fusion_done,code_description,modifier,reason_code,contact_person_email,patient_gender,patient_idnumber) 
VALUES (:member_id,:claim_number, :Service_Date,:icd10,:pmb, :charged_amnt, :scheme_paid, :gap,:username, :emergency, :entered_by,:end_date,:client_gap,:category_type,:medication_value,:patient_dob,:fusion_done,:code_description,:modifier,:reason_code,:contact_person_email,:patient_gender,:patient_idnumber)');
        $insert->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $insert->bindParam(':claim_number', $claim_number, PDO::PARAM_STR);
        $insert->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
        $insert->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $insert->bindParam(':pmb', $pmb, PDO::PARAM_STR);
        $insert->bindParam(':charged_amnt', $charged_amnt, PDO::PARAM_STR);
        $insert->bindParam(':scheme_paid', $scheme_paid, PDO::PARAM_STR);
        $insert->bindParam(':gap', $gap, PDO::PARAM_STR);
        $insert->bindParam(':username', $username, PDO::PARAM_STR);
        $insert->bindParam(':emergency', $emergency, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $insert->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $insert->bindParam(':client_gap', $client_gap, PDO::PARAM_STR);
        $insert->bindParam(':category_type', $category_type, PDO::PARAM_STR);
        $insert->bindParam(':medication_value', $medication_value, PDO::PARAM_STR);
        $insert->bindParam(':patient_dob', $patient_dob, PDO::PARAM_STR);
        $insert->bindParam(':fusion_done', $fusion_done, PDO::PARAM_STR);
        $insert->bindParam(':code_description', $code_description, PDO::PARAM_STR);
        $insert->bindParam(':modifier', $modifier, PDO::PARAM_STR);
        $insert->bindParam(':reason_code', $reason_code, PDO::PARAM_STR);
        $insert->bindParam(':contact_person_email', $person_email, PDO::PARAM_STR);
        $insert->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
        $insert->bindParam(':patient_idnumber', $patient_idnumber, PDO::PARAM_STR);
        $success = $insert->execute();
        if($success==1)
        {
            $checkClaim=$conn->prepare('SELECT MAX(claim_id) FROM claim WHERE createdBy=:entered_by');
            $checkClaim->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
            $checkClaim->execute();
            $this->mcclaim_id=$checkClaim->fetchColumn();
            $ret=true;

        }
        else{
            $ret=false;
        }
        return $ret;
    }
    function addAspen($claim_id,$iron_used,$period_oral,$iron_reasons,$signature,$delivery_required,$alt_name,$alt_telephone,$alt_relationship)
    {
        global $conn;
        $insert = $conn->prepare('  INSERT INTO `aspen_checklist`(`claim_id`,`iron_used`,`period_oral`,`iron_reasons`,`signature`,`delivery_required`,`alt_name`,`alt_telephone`,`alt_relationship`) VALUES (:claim_id,:iron_used,:period_oral,:iron_reasons,:signature,:delivery_required,:alt_name,:alt_telephone,:alt_relationship)');
        $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insert->bindParam(':iron_used', $iron_used, PDO::PARAM_STR);
        $insert->bindParam(':period_oral', $period_oral, PDO::PARAM_STR);
        $insert->bindParam(':iron_reasons', $iron_reasons, PDO::PARAM_STR);
        $insert->bindParam(':signature', $signature, PDO::PARAM_STR);
        $insert->bindParam(':delivery_required', $delivery_required, PDO::PARAM_STR);
        $insert->bindParam(':alt_name', $alt_name, PDO::PARAM_STR);
        $insert->bindParam(':alt_telephone', $alt_telephone, PDO::PARAM_STR);
        $insert->bindParam(':alt_relationship', $alt_relationship, PDO::PARAM_STR);
        $insert->execute();
    }
    function addDoctor($practice_number,$claim_id,$username)
    {
        global $conn;
        $ret=false;
        $practice_number=trim($practice_number,' ');
        $practice_number =str_pad( $practice_number, 7, '0', STR_PAD_LEFT);
        $insert = $conn->prepare('  INSERT INTO `doctors`(`claim_id`, `practice_number`,`entered_by`) VALUES (:claim_id,:practice_number,:entered_by)');
        $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insert->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $username, PDO::PARAM_STR);
        $success = $insert->execute();
        if($success==1)
        {
            $ret=true;

        }
        else{
            $ret=false;
        }
        return $ret;

    }
    function getDoctorByemail($email)
    {
        global $conn;
        $checkPrac=$conn->prepare('SELECT practice_number FROM `doctor_details` WHERE email=:email');
        $checkPrac->bindParam(':email', $email, PDO::PARAM_STR);
        $checkPrac->execute();
        return $checkPrac->rowCount()>0?$checkPrac->fetchColumn():"0000000";

    }
    function addPatient($claim_id,$patient_name,$username)
    {
        global $conn;
        $ret=false;
        $insert = $conn->prepare('  INSERT INTO `patient`(`claim_id`, `patient_name`,`entered_by`) VALUES (:claim_id,:patient_name,:entered_by)');
        $insert->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $insert->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $username, PDO::PARAM_STR);
        $success = $insert->execute();
        if($success==1)
        {
            $ret=true;

        }
        else{
            $ret=false;
        }
        return $ret;

    }
    function DBaddfiles($description,$size,$type,$rand,$claim_id,$username)
    {
        global $conn;
        $sql = $conn->prepare('INSERT INTO documents(claim_id,doc_description,doc_size,doc_type,randomNum,uploaded_by) VALUES(:claim,:description,:size,:type,:rand,:uploaded_by)');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->bindParam(':size', $size, PDO::PARAM_STR);
        $sql->bindParam(':type', $type, PDO::PARAM_STR);
        $sql->bindParam(':rand', $rand, PDO::PARAM_STR);
        $sql->bindParam(':uploaded_by', $username, PDO::PARAM_STR);
        $sql->execute();

    }
    function DBaddfilesDraft($description,$size,$type,$rand,$claim_id,$username)
    {
        global $conn;
        $sql = $conn->prepare('INSERT INTO draftDocs(claim_id,doc_description,doc_size,doc_type,randomNum,uploaded_by) VALUES(:claim,:description,:size,:type,:rand,:uploaded_by)');
        $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->bindParam(':size', $size, PDO::PARAM_STR);
        $sql->bindParam(':type', $type, PDO::PARAM_STR);
        $sql->bindParam(':rand', $rand, PDO::PARAM_STR);
        $sql->bindParam(':uploaded_by', $username, PDO::PARAM_STR);
        $sql->execute();

    }
    function getDiag($icd10)
    {
        global $conn;
        $sql = $conn->prepare('SELECT shortdesc FROM diagnosis WHERE diag_code=:diag_code');
        $sql->bindParam(':diag_code', $icd10, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchColumn();

    }
    function addFiles($fileMain,$claim_id,$username,$val=0)
    {
        if(isset($fileMain))
        {

            $allowedExts= ['jpeg','jpg','png',"pdf","doc","docx","xlsx","xls","txt","PDF","PNG","msg","MSG","eml","EML","zip","ZIP","JPEG"];
            $fileExtensions = ['jpeg','jpg','png',"pdf","PDF","DOC","DOCX","XLSX","XLS","image/jpeg","image/PNG","application/pdf","vnd.openxmlformats-officedocument.spreadsheetml.sheet","image/png","vnd.ms-excel","msword","vnd.oasis.opendocument.text","vnd.openxmlformats-officedocument.spreadsheetml.sheet","image/png","application/vnd.openxmlformats-officedocument.wordprocessingml.document","vnd.openxmlformats-officedocument.wordprocessingml.document","vnd.ms-excel","msword","vnd.oasis.opendocument.text","application/pdf","PDF","PNG","msg","MSG","octet-stream","eml","EML","application/octet-stream","message/rfc822","rfc822","x-zip-compressed"];

            $name_array=$fileMain["name"];
            // $name_array=filter_var($name_array, FILTER_SANITIZE_STRING);
            $type_array=$fileMain["type"];
            $temp_array=$fileMain["tmp_name"];
            $size_array=$fileMain["size"];
            //$error_array=$_FILES['file_array']["error"];

            for($i=0;$i<count($temp_array);$i++) {
                if ($i < 5) {
                    if (!empty($temp_array[$i])) {
                        $rand = rand(10, 10000);
                        $name_arr = $name_array[$i];
                        $temp = explode(".", $name_arr);
                        $presentExtention = end($temp);
                        $fileExtension = $type_array[$i];
                        $nux = substr_count($name_arr, '.');
                        if (in_array($presentExtention, $allowedExts) && strlen($name_arr) < 100 && $nux == 1) {
                            if (in_array($fileExtension, $fileExtensions) && $size_array[$i] < 50000000 && $size_array[$i]>0) {

                                if (move_uploaded_file($temp_array[$i], "../../mca/documents/" . $rand . $name_array[$i])) {

                                    $description = filter_var($name_array[$i], FILTER_SANITIZE_STRING);
                                    $size = $size_array[$i];
                                    $type = $type_array[$i];
                                    $desc = "../mca/test/" . $rand . $description;
                                    if($val==0)
                                    {
                                        $this->DBaddfiles($description, $size, $type, $rand, $claim_id, $username);
                                    }
                                    else{
                                        $this->DBaddfilesDraft($description, $size, $type, $rand, $claim_id, $username);
                                    }

                                    //echo "<a href='$desc' onclick=\"window.open('$desc','popup','width=800,height=600'); return false;\" title='Click to view'>$nname</a>";


                                } else {

                                }
                            } else {

                            }
                        } else {

                        }
                    }

                }
                else
                {
                    echo "You have exceed the number of files required";
                    break;
                }
            }
        }
    }

    function getPracticesignature($claim_id)
    {
        global $conn;
        $sql = $conn->prepare('SELECT b.practice_number,c.name,c.surname,c.physical_address1,c.contact_number FROM `claim` as a INNER JOIN doctors as b ON a.claim_id=b.claim_id INNER JOIN web_clients as c ON b.practice_number=c.broker_id WHERE a.claim_id=:claim_id');
        $sql->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch();

    }
    function updateScore($claim_id,$assessment_score,$position)
    {
        global $conn;
        $sql = $conn->prepare('UPDATE quality_assurance SET assessment_score=:assessment_score,position=:position WHERE claim_id=:claim_id');
        $sql->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $sql->bindParam(':assessment_score', $assessment_score, PDO::PARAM_STR);
        $sql->bindParam(':position', $position, PDO::PARAM_STR);
        $sql->execute();

    }
    function insertProfile($name,$surname,$id_number,$email,$contact_number,$physical_address1,$role,$broker_id,$password)
    {
        global $conn;
        $sql = $conn->prepare('INSERT INTO web_clients(name,surname,id_number,email,contact_number,physical_address1,role,broker_id,password) VALUES (:name,:surname,:id_number,:email,:contact_number,:physical_address1,:role,:broker_id,:password)');
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':surname', $surname, PDO::PARAM_STR);
        $sql->bindParam(':id_number', $id_number, PDO::PARAM_STR);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
        $sql->bindParam(':physical_address1', $physical_address1, PDO::PARAM_STR);
        $sql->bindParam(':role', $role, PDO::PARAM_STR);
        $sql->bindParam(':broker_id', $broker_id, PDO::PARAM_STR);
        $sql->bindParam(':password', $password, PDO::PARAM_STR);
        $sql->execute();

    }
    function insertDoctor($name_initials,$surname,$telephone,$discipline,$practice_number,$physad1,$disciplinecode,$sub_disciplinecode,$sub_disciplinecode_description,$email,$contact_person)
    {
        global $conn;
        $sql = $conn->prepare('INSERT INTO doctor_details(name_initials,surname,telephone,discipline,practice_number,physad1,disciplinecode,sub_disciplinecode,sub_disciplinecode_description,email,admin_name) VALUES (:name_initials,:surname,:telephone,:discipline,:practice_number,:physad1,:disciplinecode,:sub_disciplinecode,:sub_disciplinecode_description,:email,:admin_name)');
        $sql->bindParam(':name_initials', $name_initials, PDO::PARAM_STR);
        $sql->bindParam(':surname', $surname, PDO::PARAM_STR);
        $sql->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':discipline', $discipline, PDO::PARAM_STR);
        $sql->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $sql->bindParam(':physad1', $physad1, PDO::PARAM_STR);
        $sql->bindParam(':disciplinecode', $disciplinecode, PDO::PARAM_STR);
        $sql->bindParam(':sub_disciplinecode', $sub_disciplinecode, PDO::PARAM_STR);
        $sql->bindParam(':sub_disciplinecode_description', $sub_disciplinecode_description, PDO::PARAM_STR);
        $sql->bindParam(':admin_name', $contact_person, PDO::PARAM_STR);
        $sql->execute();

    }
    function checkProfile($email)
    {
        global $conn;
        $sql = $conn->prepare('SELECT *FROM web_clients WHERE email=:email');
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        return $sql->rowCount()>0?true:false;

    }
    function generatePassword()
    {
        $arr1 = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
        $arr2 = ['R','S', 'B', '4', 'T', 'N'];
        $random = rand(0, 25);
        $random1 = rand(0, 5);
        $random2 = rand(0, 9);
        $password = $random . ucfirst($arr1[$random]) . $random1 . $arr2[$random1] . $random2;
        return $password;
    }
    function sendMailMain($email,$subject,$body)
    {
        global $mail;
        $data=$this->getEncrpass();
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $data[0];                 // SMTP username
            $mail->Password = $data[1];                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($data[0], 'Medclaim Assist');
            $mail->addAddress($email, "MCA User");
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            if (!$mail->send()) {


            }
        }
    }
    function my_utf8_decode($string)
    {
        return strtr($string,"???????ÃÂÃÂÃÂ¿","SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
    }
// sanitize a string in prep for passing a single argument to system() (or similar)
    function sanitize_system_string($string, $min='', $max='')
    {
        $pattern = '/(;|\||`|>|<|&|%|=|^|"|'."\n|\r|'".'|{|}|[|]|\)|\()/i'; // no piping, passing possible environment variables ($),
        // seperate commands, nested execution, file redirection,
        // background processing, special commandss (backspace, etc.), quotes
        // newlines, or some other special characters
        $string = preg_replace($pattern, '', $string);
        $string = preg_replace('/\$/', '\\\$', $string); //make sure this is only interpretted as ONE argument
        $len = strlen($string);
        if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
            return FALSE;
        return $string;
    }
    function validateXss($string)
    {
        $newstr = filter_var($string, FILTER_SANITIZE_STRING);
        $newstr=$this->sanitize_system_string($newstr, $min='', $max='');
        $newstr=htmlspecialchars($newstr);
        $newstr=$this->my_utf8_decode($newstr);
        $newstr=trim($newstr);
        return $newstr;

    }

    function addAspenDraft($member_id,$Service_Date,$icd10,$end_date,$reason_code,$code_description,$modifier,$entered_by,$createdBy,$medication_value,$patient_idnumber,$fusion_done,$contact_person_email,$patient_gender,$iron_used,$period_oral,$iron_reasons,$signature,$delivery_required,$alt_name,$alt_telephone,$patient_name,$patient_surname,$alt_relationship,$hospital_practice)
    {
        global $conn;
        $insert = $conn->prepare('INSERT INTO `pre_capture`(`member_id`,`Service_Date`,`icd10`,`end_date`,`reason_code`,`code_description`,`modifier`,`entered_by`,`createdBy`,`medication_value`,`patient_idnumber`,`fusion_done`,`contact_person_email`,`patient_gender`,`iron_used`, `period_oral`,`iron_reasons`,`signature`,`delivery_required`,`alt_name`,`alt_telephone`,`patient_name`,`patient_surname`,`alt_relationship`,`hospital_practice`) VALUES (:member_id,:Service_Date,:icd10,:end_date,:reason_code,:code_description,:modifier,:entered_by,:createdBy,:medication_value,:patient_idnumber,:fusion_done,:contact_person_email,:patient_gender,:iron_used,:period_oral,:iron_reasons,:signature,:delivery_required,:alt_name,:alt_telephone,:patient_name,:patient_surname,:alt_relationship,:hospital_practice)');
        $insert->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $insert->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
        $insert->bindParam(':icd10', $icd10, PDO::PARAM_STR);
        $insert->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $insert->bindParam(':reason_code', $reason_code, PDO::PARAM_STR);
        $insert->bindParam(':code_description', $code_description, PDO::PARAM_STR);
        $insert->bindParam(':modifier', $modifier, PDO::PARAM_STR);
        $insert->bindParam(':entered_by', $entered_by, PDO::PARAM_STR);
        $insert->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
        $insert->bindParam(':medication_value', $medication_value, PDO::PARAM_STR);
        $insert->bindParam(':patient_idnumber', $patient_idnumber, PDO::PARAM_STR);
        $insert->bindParam(':fusion_done', $fusion_done, PDO::PARAM_STR);
        $insert->bindParam(':contact_person_email', $contact_person_email, PDO::PARAM_STR);
        $insert->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
        $insert->bindParam(':iron_used', $iron_used, PDO::PARAM_STR);
        $insert->bindParam(':period_oral', $period_oral, PDO::PARAM_STR);
        $insert->bindParam(':iron_reasons', $iron_reasons, PDO::PARAM_STR);
        $insert->bindParam(':signature', $signature, PDO::PARAM_STR);
        $insert->bindParam(':delivery_required', $delivery_required, PDO::PARAM_STR);
        $insert->bindParam(':alt_name', $alt_name, PDO::PARAM_STR);
        $insert->bindParam(':alt_telephone', $alt_telephone, PDO::PARAM_STR);
        $insert->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
        $insert->bindParam(':patient_surname', $patient_surname, PDO::PARAM_STR);
        $insert->bindParam(':alt_relationship', $alt_relationship, PDO::PARAM_STR);
        $insert->bindParam(':hospital_practice', $hospital_practice, PDO::PARAM_STR);

        $success = $insert->execute();
        if($success==1)
        {
            $checkClaim=$conn->prepare('SELECT MAX(claim_id) FROM pre_capture WHERE createdBy=:entered_by');
            $checkClaim->bindParam(':entered_by', $createdBy, PDO::PARAM_STR);
            $checkClaim->execute();
            $this->mcclaim_id=$checkClaim->fetchColumn();
            $ret=true;

        }
        else{
            $ret=false;
        }
        return $ret;
    }

    function gedoctortDraft($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT a.claim_id,b.member_id,b.first_name, b.surname,b.medical_scheme, b.scheme_option,b.client_id, 
a.Open,id_number,b.scheme_number,b.email,b.cell,b.telephone,a.pmb,a.icd10,a.Service_Date,a.end_date,a.date_entered,a.reason_code,iron_used, period_oral,a.iron_reasons,a.signature,a.delivery_required,a.alt_name,a.alt_telephone,a.patient_name,a.patient_surname,a.alt_relationship,
       a.createdBy,a.medication_value,a.entered_by,a.fusion_done,a.code_description,a.modifier,a.reason_code,c.client_name,a.contact_person_email,a.patient_gender,a.patient_idnumber,a.hospital_practice
 FROM pre_capture as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.client_id WHERE a.claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    function getdraftFiles($claim_id)
    {
        try {
            global $conn;
            $st=$conn->prepare('SELECT *FROM draftDocs WHERE claim_id=:claim_id');
            $st->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function updateMember($member_id,$medical_scheme,$scheme_number,$id_number,$member_name,$member_surname,$memb_telephone,$memb_cell,$memb_email,$scheme_option)
    {
        try {
            global $conn;
            $stmt=$conn->prepare('Update member SET medical_scheme=:medical_scheme,scheme_number=:scheme_number,id_number=:id_number,
    first_name=:member_name,surname=:member_surname,telephone=:memb_telephone,cell=:memb_cell,email=:memb_email,scheme_option=:scheme_option WHERE member_id=:num');
            $stmt->bindParam(':num', $member_id, PDO::PARAM_STR);
            $stmt->bindParam(':medical_scheme', $medical_scheme, PDO::PARAM_STR);
            $stmt->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
            $stmt->bindParam(':id_number', $id_number, PDO::PARAM_STR);
            $stmt->bindParam(':member_name', $member_name, PDO::PARAM_STR);
            $stmt->bindParam(':member_surname', $member_surname, PDO::PARAM_STR);
            $stmt->bindParam(':memb_telephone', $memb_telephone, PDO::PARAM_STR);
            $stmt->bindParam(':memb_cell', $memb_cell, PDO::PARAM_STR);
            $stmt->bindParam(':memb_email', $memb_email, PDO::PARAM_STR);
            $stmt->bindParam(':scheme_option', $scheme_option, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function updateDraft1($claim_id,$Service_Date,$end_date,$icd10,$medication_value,$fusion_done,$dosage,$codes,$nappi,$person_email,$patient_gender,$patient_idnumber,$period_oral,$iron_reasons,$signature,$delivery_required,$alt_name,$alt_telephone,$patient_name,$patient_surname,$alt_relationship,$hospital_practice,$iron_used)
    {
        try {
            global $conn;
            $stmt=$conn->prepare('Update pre_capture SET Service_Date=:Service_Date,icd10=:icd10,end_date=:end_date,medication_value=:medication_value,fusion_done=:fusion_done,code_description=:code_description,modifier=:modifier,reason_code=:reason_code,contact_person_email=:contact_person_email,patient_gender=:patient_gender,patient_idnumber=:patient_idnumber,iron_used=:iron_used,period_oral=:period_oral,iron_reasons=:iron_reasons,signature=:signature,delivery_required=:delivery_required,alt_name=:alt_name,alt_telephone=:alt_telephone,patient_name=:patient_name,patient_surname=:patient_surname,alt_relationship=:alt_relationship,hospital_practice=:hospital_practice WHERE claim_id=:num');
            $stmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':Service_Date', $Service_Date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':icd10', $icd10, PDO::PARAM_STR);
            $stmt->bindParam(':medication_value', $medication_value, PDO::PARAM_STR);
            $stmt->bindParam(':fusion_done', $fusion_done, PDO::PARAM_STR);
            $stmt->bindParam(':code_description', $dosage, PDO::PARAM_STR);
            $stmt->bindParam(':modifier', $codes, PDO::PARAM_STR);
            $stmt->bindParam(':reason_code', $nappi, PDO::PARAM_STR);
            $stmt->bindParam(':contact_person_email', $person_email, PDO::PARAM_STR);
            $stmt->bindParam(':patient_gender', $patient_gender, PDO::PARAM_STR);
            $stmt->bindParam(':patient_idnumber', $patient_idnumber, PDO::PARAM_STR);
            $stmt->bindParam(':period_oral', $period_oral, PDO::PARAM_STR);
            $stmt->bindParam(':iron_reasons', $iron_reasons, PDO::PARAM_STR);
            $stmt->bindParam(':iron_used', $iron_used, PDO::PARAM_STR);
            $stmt->bindParam(':signature', $signature, PDO::PARAM_STR);
            $stmt->bindParam(':delivery_required', $delivery_required, PDO::PARAM_STR);
            $stmt->bindParam(':alt_name', $alt_name, PDO::PARAM_STR);
            $stmt->bindParam(':alt_telephone', $alt_telephone, PDO::PARAM_STR);
            $stmt->bindParam(':patient_name', $patient_name, PDO::PARAM_STR);
            $stmt->bindParam(':patient_surname', $patient_surname, PDO::PARAM_STR);
            $stmt->bindParam(':alt_relationship', $alt_relationship, PDO::PARAM_STR);
            $stmt->bindParam(':hospital_practice', $hospital_practice, PDO::PARAM_STR);
            return $stmt->execute();

        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }
    function updateStatusDraft($claim_id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare('Update pre_capture SET Open=2 WHERE claim_id=:num');
            $stmt->bindParam(':num', $claim_id, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    function getPracticeSecond($email)
    {
        global $conn;
        $sql = $conn->prepare('SELECT broker_id,name,surname,physical_address1,contact_number FROM `web_clients` WHERE email=:email');
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch();

    }
    function getDocName($client_id)
    {
        global $conn;
        $sql = $conn->prepare('SELECT name FROM `web_clients` WHERE client_id=:client_id');
        $sql->bindParam(':client_id', $client_id, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchColumn();

    }
    function zeroAmounts($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT DISTINCT claim_number,mca_claim_id,username,b.date_entered,d.client_name FROM `claim_line` as a INNER JOIN claim as b ON a.mca_claim_id=b.claim_id INNER JOIN member as c ON b.member_id=c.member_id INNER JOIN clients as d ON c.client_id=d.client_id
where (clmnline_charged_amnt="0.00" OR clmline_scheme_paid_amnt="0.00") AND (LENGTH(msg_code)<3  OR msg_code is null) AND b.claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function members($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT a.claim_number,a.claim_id,a.date_entered,a.member_contacted,NOW() as time,TIMESTAMPDIFF(day,a.date_entered,NOW()) as period,a.username,a.claim_id 
FROM claim as a INNER JOIN  member as b ON a.member_id=b.member_id WHERE a.Open=1 GROUP BY a.claim_id having period>=6 AND (a.member_contacted<>1 OR a.member_contacted IS NULL) AND a.claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function updatedDocs($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT b.claim_number,a.claim_id FROM `documents` as a inner join claim as b on a.claim_id=b.claim_id where additional_doc=1 AND a.claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getQAUsers($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT username FROM claim where claim_id=:claim_id UNION SELECT owner FROM logs where claim_id=:claim_id UNION SELECT createdBy FROM claim_line where mca_claim_id=:claim_id UNION SELECT uploaded_by FROM documents where claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    function insertProvider($name,$surname,$email,$contact_number,$practice_number,$physical_address1,$role,$password,$discipline,$disciplinecode,$sub_disciplinecode,$sub_disciplinecode_description)
    {
        global $conn;
        $sql = $conn->prepare('INSERT INTO web_providers(name,surname,email,contact_number,practice_number,physical_address1,role,password,discipline,disciplinecode,sub_disciplinecode,sub_disciplinecode_description) VALUES (:name,:surname,:email,:contact_number,:practice_number,:physical_address1,:role,:password,:discipline,:disciplinecode,:sub_disciplinecode,:sub_disciplinecode_description)');
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':surname', $surname, PDO::PARAM_STR);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
        $sql->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $sql->bindParam(':physical_address1', $physical_address1, PDO::PARAM_STR);
        $sql->bindParam(':role', $role, PDO::PARAM_STR);
        $sql->bindParam(':password', $password, PDO::PARAM_STR);
        $sql->bindParam(':discipline', $discipline, PDO::PARAM_STR);
        $sql->bindParam(':disciplinecode', $disciplinecode, PDO::PARAM_STR);
        $sql->bindParam(':sub_disciplinecode', $sub_disciplinecode, PDO::PARAM_STR);
        $sql->bindParam(':sub_disciplinecode_description', $sub_disciplinecode_description, PDO::PARAM_STR);
        $sql->execute();

    }
    public function insertNotes($claim_id,$intervention_desc,$username,$reminder_time,$reminder_status,$claim_id1,$current_practice_number,$doc_name,$consent_dest,$status=1)
    {
        $clamm="--";
        global $conn;
        $stmt = $conn->prepare('INSERT INTO intervention(claim_id,intervention_desc,owner,reminder_time,reminder_status,claim_id1,practice_number,doc_name,consent_destination,claim_number,status) VALUES(:claim,:notes,:owner,:reminder_time,:reminder_status,:claim_id1,:practice_number,:doc_name,:consent_destination,:claim_number,:status)');
        $stmt->bindParam(':claim', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':notes', $intervention_desc, PDO::PARAM_STR);
        $stmt->bindParam(':owner', $username, PDO::PARAM_STR);
        $stmt->bindParam(':reminder_time', $reminder_time, PDO::PARAM_STR);
        $stmt->bindParam(':reminder_status', $reminder_status, PDO::PARAM_STR);
        $stmt->bindParam(':claim_id1', $claim_id1, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $current_practice_number, PDO::PARAM_STR);
        $stmt->bindParam(':doc_name', $doc_name, PDO::PARAM_STR);
        $stmt->bindParam(':consent_destination', $consent_dest, PDO::PARAM_STR);
        $stmt->bindParam(':claim_number', $clamm, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        return (int)$stmt->execute();
    }
    function checkProfile1($email)
    {
        global $conn;
        $sql = $conn->prepare('SELECT *FROM web_providers WHERE email=:email');
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        return $sql->rowCount()>0?true:false;

    }
}