<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require 'vendor/autoload.php';
define("access",true);
include ("../classes/controls.php");
$mail = new PHPMailer(true);

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Google\Service\CloudSupport\EscalateCaseRequest;
class email
{
    public $main_email_id;
    public $arrpracticename = ["practice_name","hospital_practice_name","treating_practice_name"];
    public $arrpracticenumber = ["practice_number","hospital_practice_number","treating_practice_number"];
    function getUserEmail($username)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT email,username,surname FROM users_information WHERE username=:username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();

        }
        catch (Exception $e)
        {

        }

    }
    function getEmailTemplate($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT template_content,tags,attachment,template,client,subject FROM `email_templates` WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch();
}
    function insertEmail($email_to,$email_from,$subject,$body,$email_source,$claim_id,$message_id="",$typm="",$status=0)
    {
        $body = $this->removeContentTypeSection($body);
        $body = $this->replaceMultipleBrWithSingle($body);
        global $conn;
        try {
            $stmt=$conn->prepare("INSERT INTO emails(email_to,email_from,subject,body,email_source,claim_id,message_id,typm,status) VALUES (:email_to,:email_from,:subject,:body,:email_source,:claim_id,:message_id,:typm,:status)");
            $stmt->bindParam(':email_to', $email_to, PDO::PARAM_STR);
            $stmt->bindParam(':email_from', $email_from, PDO::PARAM_STR);
            $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
            $stmt->bindParam(':body', $body, PDO::PARAM_STR);
            $stmt->bindParam(':email_source', $email_source, PDO::PARAM_STR);
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':message_id', $message_id, PDO::PARAM_STR);
            $stmt->bindParam(':typm', $typm, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            return $stmt->execute();

        }
        catch(Exception $e)
        {
 echo "<script>alert(\"Errro\".$e)</script>";
            return 0;
        }

    }
    
    function getMails($source,$claim_id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT email_to,email_from,subject,body,email_source,id,date_entered FROM emails WHERE email_source=:email_source AND claim_id=:claim_id ORDER BY id DESC");
            $stmt->bindParam(':email_source', $source, PDO::PARAM_STR);
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return[];
        }
    }

    function checkClaimId($claim_id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT claim_id FROM claim WHERE claim_id=:claim_id AND claim_id > 20000 LIMIT 1");
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    function updateEmail($claim_id,$subject)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("UPDATE emails SET status=0 WHERE claim_id=:claim_id AND subject=:subject");
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
            return $stmt->execute();
        }
        catch (Exception $e)
        {
            return 0;
        }
    }
    function getInbox($source,$claim_id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT e.*FROM emails e INNER JOIN (SELECT claim_id, subject, MAX(id) as max_id FROM emails
    WHERE email_source=:email_source AND claim_id=:claim_id GROUP BY claim_id, subject ORDER BY max_id DESC) grouped_emails ON e.id = grouped_emails.max_id");
            $stmt->bindParam(':email_source', $source, PDO::PARAM_STR);
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (Exception $e)
        {
            return[];
        }
    }
    function getAllMails($claim_id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT email_to,email_from,subject,body,email_source,id,date_entered,status FROM emails WHERE claim_id=:claim_id ORDER BY id DESC");
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

        }
        catch (Exception $e)
        {
            return[];
        }
    }
    function getEmailDetail($id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT email_to,email_from,subject,body,email_source,id,date_entered FROM emails WHERE id=:id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

        }
        catch (Exception $e)
        {
return[];
        }
    }

    function moveFile($original_filename,$claim_id,$email_id)
    {
        /* Store the path of source file */
        $filePath = 'temp_upload/'.$original_filename;
        $size=filesize($filePath);
        $type=filetype("$filePath");
        $random_number=rand(1000,9999);
        if(file_exists($filePath))
        {
$new_name ='temp_upload/'.$random_number.$original_filename;
if (rename($filePath, $new_name)) {
    $this->uploadWasabi($random_number.$original_filename);
    $this->DBaddfiles($original_filename,$size,$type,$random_number,$claim_id,$email_id);
    if (unlink($new_name)) {
        //echo "File deleted successfully.";
    }
}            
        }
        else{

        }
    }
    function getEmailId($email)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT MAX(id) FROM emails WHERE email_from=:email_from");
            $stmt->bindParam(':email_from', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();

        }
        catch (Exception $e)
        {
            return[];
        }
    }
    function formatDate($dateString)
    {
$date = new DateTime($dateString);
$formattedDate = $date->format('d F Y H:i:s');
return $formattedDate;
    }
    function getEmailTrail($claim_id,$subject)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT *FROM emails WHERE claim_id=:claim_id AND subject=:subject ORDER BY id DESC");
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

        }
        catch (Exception $e)
        {
            return[];
        }
    }
    function getSingelEmail($id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT *FROM emails WHERE id=:id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

        }
        catch (Exception $e)
        {
            return[];
        }
    }

    function getMessageId($claim_id,$subject)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT message_id FROM emails WHERE claim_id=:claim_id AND subject=:subject AND email_source='External' ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount()>0?$stmt->fetchColumn():"";
           

        }
        catch (Exception $e)
        {
            return[];
        }
    }

    function DBaddfiles($description,$size,$type,$rand,$claim_id,$email_id,$additional_doc=0)
    {
        global $conn;
        $username="System";
        try {
            $sql = $conn->prepare('INSERT INTO documents(claim_id,doc_description,doc_size,doc_type,randomNum,uploaded_by,additional_doc,email_id) VALUES(:claim,:description,:size,:type,:rand,:uploaded_by,:additional_doc,:email_id)');
            $sql->bindParam(':claim', $claim_id, PDO::PARAM_STR);
            $sql->bindParam(':description', $description, PDO::PARAM_STR);
            $sql->bindParam(':size', $size, PDO::PARAM_STR);
            $sql->bindParam(':type', $type, PDO::PARAM_STR);
            $sql->bindParam(':rand', $rand, PDO::PARAM_STR);
            $sql->bindParam(':uploaded_by', $username, PDO::PARAM_STR);
            $sql->bindParam(':additional_doc', $additional_doc, PDO::PARAM_STR);
            $sql->bindParam(':email_id', $email_id, PDO::PARAM_STR);
            $sql->execute();
        }
        catch (Exception $ex)
        {
        }

    }
    function  checkFiles($email_id)
    {
        global $conn;
        $stmt=$conn->prepare("SELECT email_id FROM documents WHERE email_id=:email_id");
        $stmt->bindParam(':email_id', $email_id, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            return true;
        }
        else{
            return false;
        }
    }
    function  autoResponse($subto,$subject)
    {
        global $mail;
        try{
        $subject = "Acknowledgement of email (".$subject.")";
      
         $temmm = $this->getEmailTemplate(1);
        $body = nl2br($temmm["template_content"]);
       
        if ($this->sendMail($subto,$subject,$body,[],"","MCA System"))
        {
            echo "Auto Response Sent";
        }
        else{
            echo "Failed";
        }
    }
    catch(Exception $e)
    {
        echo "There is an error";
    }
    }
    function getEmailDocuments($email_id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT doc_description,doc_size,randomNum,doc_id FROM documents WHERE email_id=:email_id AND email_id<>0");
            $stmt->bindParam(':email_id', $email_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

        }
        catch (Exception $e)
        {
            return[];
        }
    }
    function getFirstDoctor($claim_id)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT d.practice_number,p.name_initials,p.surname,d.claimedline_id FROM `doctors` as d INNER JOIN doctor_details as p
             ON d.practice_number=p.practice_number WHERE d.claim_id=:claim_id ORDER BY d.date_entered ASC;");
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();

        }
        catch (Exception $e)
        {
            return[];
        }
    }
    function getDoctorByEmail($email)
    {
        try {
            global $conn;
            $stmt=$conn->prepare("SELECT d.practice_number,p.name_initials,p.surname,d.claimedline_id FROM `doctors` as d INNER JOIN doctor_details as p
             ON d.practice_number=p.practice_number WHERE p.email=:email LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();

        }
        catch (Exception $e)
        {
            return[];
        }
    }
    function getEncrpass()
    {
        global  $conn;
        $stmt = $conn->prepare("SELECT email,password,correspondence_email,correspondence_password FROM email_configs");
        $stmt->execute();
        return $stmt->fetch();
    }

    function sendMail($email,$subject,$body,$attachment=[],$message_id="",$fullname="")
    {
        global $mail;
        $succ=true;
        $data=$this->getEncrpass();
        try {

$emaiarr=explode(";",$email);
$hostname='smtp.gmail.com';
$username = $data[2];
$password= $data[3];
$ffot = strpos($subject,"Acknowledgement of email")>-1?"":"<p style='color:red; font-size:8px'>Please reply to this email. Do not change the subject line or send a new email as it will affect the progress/traction of the claim.</p><br/>";
$bottommsg = "<br><br>Kind Regards,<br><span style='color:gray;'>".$fullname." on behalf of MedClaim Assist</span>";
$body = $ffot.$body;
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $hostname;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $username;                 // SMTP username
            $mail->Password = $password;                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($username, 'Med Claim Assist');
            for($i=0;$i<count($emaiarr);$i++)
            {
               $mail->addAddress($emaiarr[$i], 'Med Claim Assist');
            }
            if(!empty($message_id))
{
      // Headers
      $mail->addCustomHeader('In-Reply-To', $message_id);
      $mail->addCustomHeader('References', $message_id);  
}

            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body.$bottommsg;
            for($i=1;$i<count($attachment);$i++)
            {
                $file="temp_upload/".$attachment[$i];
                $mail->AddAttachment($file);

            }

            //$mail->send();

            if (!$mail->send()) {
                $succ = false;
            }
        }
        catch (Exception $e)
        {
            $succ = false;
        }
            return $succ;
    }
    function getMemberEmail($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT b.email,a.claim_number FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_id=:claim_id');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertNotes($claim_id,$intervention_desc,$username,$reminder_time="0000-00-00 00:00:00",$reminder_status=0,$claim_id1=0,$current_practice_number="",$doc_name="",$consent_dest="",$status=1)
    {
        global $conn;
$intervention_desc = $this->replaceMultipleBrWithSingle($intervention_desc);
        $clamm="--";
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
    function insertAPILog($claim_id, $current_practice_number, $notes)
    {
        $control=new controls();
        $data=$control->viewSingleClaim($claim_id);        
        $senderId = (int)$data["senderId"];
        if((int)$senderId>0)
    {
        

        $claim_status = (int)$data["Open"];        
        $api_data = $control->viewAPIURL($senderId);
        if($api_data)
        {
        $url = $api_data["api_url"];
        $auth_key = $api_data["auth_key"];
        $doctor_data=$control->viewSpecific($claim_id,$current_practice_number);
        $claimid=$doctor_data["claimedline_id"];
        $pmb_criteria=$data["savings_catergory"];
        $pmb = (int)$data["pmb"]>0?"yes":"no";
        $emergency = (int)$data["emergency"]>0?"yes":"no";
        $claim_number = $data["claim_number"];
        $date_entered = date("Y-m-d H:i:s");
        $current_savings_scheme = (double)$doctor_data["savings_scheme"];
        $current_savings_discount = (double)$doctor_data["savings_discount"];
        $pay = "no";
        $api=$control->sendOwlAPI($claim_number,$claim_status,$date_entered,$notes,$current_savings_scheme,$current_savings_discount,$pay,$current_practice_number,$claimid,$url,$auth_key,$pmb_criteria,$pmb,$emergency,$senderId);
        echo $api;
        }
    }   

    }
    public function getIcdTariff($claim_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT DISTINCT primaryICDCode, tariff_code FROM claim_line WHERE mca_claim_id=:claim_id");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount()>0)
        {
// Extract distinct primaryICDCode values
$icdCodes = array_column($results, 'primaryICDCode');
$icdCodesString = implode(',', array_unique($icdCodes));
$tariffCodes = array_column($results, 'tariff_code');
$tariffCodesString = implode(',', array_unique($tariffCodes));

        }
        else{
            $tariffCodes="";
            $tariffCodesString="";
        }
         
        return [
            'primaryICDCode' => $icdCodesString,
            'tariff_codes' => $tariffCodesString // Keeps the individual rows with both fields intact
        ];
    }
    public function getTreatmentDates($claim_id,$practice_number)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT DISTINCT treatmentDate FROM claim_line WHERE mca_claim_id=:claim_id AND practice_number=:practice_number");
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':practice_number', $practice_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
 function checkGroup($email,$claim_id)
    {
        global $conn;
        $group="";
        $stmt = $conn->prepare('SELECT b.email FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id WHERE a.claim_id=:claim_id AND b.email=:email');
        $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()>0)
        {
            $group="Member";
        }
        else
        {
            $stmt = $conn->prepare('SELECT c.client_email FROM claim as a INNER JOIN member as b ON a.member_id=b.member_id INNER JOIN clients as c ON b.client_id=c.reporting_client_id WHERE a.claim_id=:claim_id AND c.client_email=:email');
            $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount()>0)
            {
                $group="Client";
            }
            else{
                $stmt = $conn->prepare('SELECT b.email FROM doctors as a INNER JOIN doctor_details as b ON a.practice_number=b.practice_number WHERE a.claim_id=:claim_id AND b.email=:email');
                $stmt->bindParam(':claim_id', $claim_id, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                if($stmt->rowCount()>0)
                {
                    $group="Doctor";
                }

            }
        }
return $group;
    }

    function getSplitEmail($text)
{
    $delimiter = "_-_-_-_-_-_-_-";
    $parts = explode($delimiter, $text);
    if(strpos($text,$delimiter)>-1)
    {       
        $parts = array_slice($parts, 0, -1);
    }    
return $parts;
}
// Function to read emails and reply
function extractEmailAddress($str) {
    $pattern = '/<([^>]+)>/';
    if (preg_match($pattern, $str, $matches)) {
        return $matches[1];
    } else {
        return false;
    }
}

function validateEmailAddress($email) {
    // Step 1: Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false; // Invalid format
    }

    // Step 2: Check if the domain exists (optional)
    $domain = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
        return false; // Domain does not exist
    }

    return true; // Email is valid
}

function extractEmailAddressCheck($email) {
    $pattern = '/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (preg_match($pattern, $email)) {

        // Validate the extracted email
        return $this->validateEmailAddress($email) ? $email : false;
    }

    return false; // No valid email found
}

function containsWRefNo($statement) {
    return strpos($statement, '-MailRef#') !== false;
}

function cleanEmailBody($body) {
    $body = preg_replace('/^(Content-Type:.*|charset=.*|\s*\-\-\d+\w+\-\-\s*)$/mi', '', $body);
    $body = preg_replace('/^On\s.+\swrote:.*$/msU', '_-_-_-_-_-_-_-', $body);
    return $body;
}

function getNewEmailContent($emailBody) {
    // Define common separators used in email threads
    $separators = [
        '/^On .* wrote:/mi',              // Matches "On [date], [person] wrote:"
        '/^----[-\s]*Original Message[-\s]*----/mi', // Matches "----Original Message----"
        '/^From: .*/mi',                  // Matches lines starting with "From:"
        '/^\>+ .*/m',                     // Matches ">" quoted lines
    ];

    // Iterate over separators to find the first occurrence
    foreach ($separators as $separator) {
        if (preg_match($separator, $emailBody, $matches, PREG_OFFSET_CAPTURE)) {
            // Extract everything before the separator
            $position = $matches[0][1];
            $newContent = substr($emailBody, 0, $position);
            return trim($newContent); // Trim to clean up any whitespace
        }
    }

    // If no separator found, return the full email body
    return trim($emailBody);
}

function cleanHtmlEmail($htmlContent) {
    // Strip unwanted HTML tags using DOMDocument (keeping basic tags like <p>, <a>, etc.)
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($htmlContent);
    libxml_clear_errors();
    
    // Extract the body content (remove unwanted tags or elements)
    $cleanedHtml = $dom->saveHTML();
    
    // Optionally remove specific tags (e.g., <span>, <div>, etc.)
    $cleanedHtml = preg_replace('/<span[^>]*>/', '', $cleanedHtml);
    $cleanedHtml = preg_replace('/<\/span>/', '', $cleanedHtml);
    $cleanedHtml = preg_replace('/<div[^>]*>/', '', $cleanedHtml);
    $cleanedHtml = preg_replace('/<\/div>/', '', $cleanedHtml);
    
    return $cleanedHtml;
}

function cleanPlainTextEmail($plainTextContent) {
    // Remove email signatures, common disclaimers (e.g., lines starting with '--')
    $cleanedText = preg_replace('/^--.*$/m', '', $plainTextContent); // Remove signature lines
    $cleanedText = preg_replace('/\n{2,}/', "\n", $cleanedText); // Remove excessive line breaks

    return $cleanedText;
}

function checkBase64Encoded($string, $minLength = 75) {
    $pattern = '/^[A-Za-z0-9+\/=]{' . $minLength . ',}/';

    if (preg_match($pattern, $string)) {
        return true; // The string has a long word at the beginning
    }
    return false; 
    }

function getEmailBody($inbox, $email_number) {
    $structure = imap_fetchstructure($inbox, $email_number);

    function getPart($structure, $email_number, $inbox, $mimeType) {
        if ($structure->type == 0 && $structure->subtype == strtoupper($mimeType)) {
            $body = imap_fetchbody($inbox, $email_number, 1); // Fetch body part

            // Check encoding and decode if necessary
            if (isset($structure->encoding)) {
                switch ($structure->encoding) {
                    case 3: // Base64
                        preg_match_all(    '/Content-Transfer-Encoding: base64\s+([\s\S]*?)\s+----/i',    $body,  $matches);

                        if (!empty($matches[1])) {
                            foreach ($matches[1] as $encodedContent) {
                                $body = preg_replace('/\s+/', '', $encodedContent);
                                $body = base64_decode($body);
                            }
                        }
                        else{
                            $body = base64_decode($body);
                        }
                        break;
                    case 4: // Quoted-Printable
                        $body = quoted_printable_decode($body);
                        break;
                }
            }
            return $body;
        }

        if (isset($structure->parts) && is_array($structure->parts)) {
            foreach ($structure->parts as $index => $part) {
                $partNumber = $index + 1;
                $result = getPart($part, $email_number, $inbox, $mimeType);
                if (!empty($result)) {
                    return $result;
                }
            }
        }

        return "No email body";
    }

    // Try to fetch plain text first, then HTML as fallback
    $body = getPart($structure, $email_number, $inbox, 'PLAIN');
    if (empty($body)) {
        $body = getPart($structure, $email_number, $inbox, 'HTML');
    }

    return $body ?: 'No email body';
}


function readAndReply($hostname, $username, $password) {
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
    $emails = imap_search($inbox, 'UNSEEN');
    if ($emails) {
        rsort($emails);
        foreach ($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0);            
            $message_id = $overview[0]->message_id;                 
            
        $headers = imap_fetchheader($inbox, $email_number);
        
        preg_match('/X-GM-MSGID: (\d+)/', $headers, $matches);
        $gmail_message_id  = $matches[1] ?? '--';
        //$gmail_message_id  = $headers;

			$from = $overview[0]->from;
            $from = strpos($from, '>') !== false ? $this->extractEmailAddress($from) : $from;

            $subject = $overview[0]->subject;

            $claim_id = $this->extractClaimId($subject);
            $claim_id = $this->convertToInt($claim_id);
            $claim_id = $this->checkClaimId($claim_id)?$claim_id:0;
         
           $body = $this->getEmailBody($inbox, $email_number);
            $structure = imap_fetchstructure($inbox, $email_number);
            if($body == "No email body")
            {     
			$body = imap_fetchbody($inbox, $email_number, 1);
			
			
		
			if ($structure->subtype == 'PLAIN') {
				$body = imap_fetchbody($inbox, $email_number, 1);
			} elseif ($structure->subtype == 'HTML') {
				$body = imap_fetchbody($inbox, $email_number, 1.2);
				if (empty($body)) {
					$body = imap_fetchbody($inbox, $email_number, 1);
				}
			}
            }
            
                if ($this->checkBase64Encoded($body)) {
        $body = base64_decode($body);
       }
       $htmlStart = strpos($body, '<html');

       if ($htmlStart !== false) {
           $body = substr($body, $htmlStart);
       }
            
			$body = htmlentities($body);
            $dbbody = $this->getNewEmailContent($body);
            $dbbody = $this->replaceUnwanted($dbbody);
            $subject = str_replace("Re: ","",$subject);
            $db_email_id=0;

            
            foreach($this->getSplitEmail($dbbody) as $b)
            {
                
                $b = $this->cleanHtmlEmail($b);
                $b = $this->cleanPlainTextEmail($b);
                $b = str_replace("--=20","",$b);
                $b = str_replace("Content-Transfer-Encoding: quoted-printable","",$b);

                $b = quoted_printable_decode($b);
                 
    $b = html_entity_decode($b, ENT_QUOTES | ENT_HTML5);
    $pattern = '/[A-Za-z0-9\/+]{50,}=*/';
    $b = preg_replace($pattern, '', $b);
    $b = preg_replace('/[\r\n]+/', "\n", $b);
    $b = trim($b); 

                
                $this->insertEmail($username,$from,$subject,$b,"External",$claim_id,$message_id,'',1);
                $db_email_id=$this->getEmailId($from);
            }            
            if ($structure->subtype != 'PLAIN' && $structure->subtype != 'HTML')
            {
                
               $attach = $this->getEmailDoc($structure,$inbox,$email_number,$claim_id,$db_email_id);
               
            }
            
            $ivemail = ["pmb@bestmed.co.za","no-reply@gems.gov.za","noreply@medihelp.co.za","claims@sirago.co.za","claims@sirago.co.za","yourclaim@stratumbenefits.co.za","no-reply-servicedesk@complaints.gems.gov.za", "hcppmbqueries@medscheme.co.za","noreply@feedback.ppsha.co.za", "claims_assist@discovery.co.za", "no-reply@discovery.co.za", "noreply@momentumhealth.co.za","info@profmed.co.za", "no-reply@medscheme.co.za","Admedclaims@guardrisk.co.za","Admed@guardrisk.co.za","specialist@medscheme.co.za"];
            
            // Mark the email as seen
            imap_setflag_full($inbox, $email_number, "\\Seen");
            if(!$this->containsKeywords($subject))
            {
            if(!in_array($from,$ivemail)){
              $this->autoResponse($from,$subject);
              }
            }        
        }
       
        }
    

    // Close the connection
    imap_close($inbox);
}

function extractActualContent($emailContent) {
    // Remove disclaimers
    $emailContent = preg_replace('/Email Disclaimer:.*$/is', '', $emailContent);
    
    // Remove reply chains (e.g., "On [date], [name] wrote:")
    $emailContent = preg_replace('/On .* wrote:.*$/is', '', $emailContent);

    // Remove signatures (e.g., starts with "--")
    $emailContent = preg_replace('/--\s*\n.*/s', '', $emailContent);

    return trim($emailContent); // Clean up extra whitespace
}
function getEmailDoc($structure,$inbox,$email_number,$claim_id,$email_id)
{
    $attachments = [];

            if (isset($structure->parts) && count($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); $i++) {
                    $attachment = $structure->parts[$i];
                    if ($attachment->ifdparameters) {
                        $attachments = $this->fileLoop($attachment,$inbox,$email_number,$i,$claim_id,$email_id);
                       }
                       else
                       {
                           if(isset($attachment->parts) && count($attachment->parts))
                           {
                          
                               for ($j = 0; $j < count($attachment->parts); $j++) {
                                   $attachment_in = $attachment->parts[$j];
                                   if ($attachment_in->ifdparameters) {
                                       $attachments = $this->fileLoop($attachment_in,$inbox,$email_number,$i,$claim_id,$email_id);
                                      }
                               }
                           }
                       }
                }
            }
            return $attachments;
}

function fileLoop($attachment,$inbox,$email_number,$i,$claim_id,$email_id)
{
    $attachments = [];
    foreach ($attachment->dparameters as $object) {
        if (strtolower($object->attribute) == 'filename') {
            $filename = $object->value;
            $attachmentBody = imap_fetchbody($inbox, $email_number, $i + 1);
            if ($attachment->encoding == 3) { // 3 = BASE64
                $attachmentBody = base64_decode($attachmentBody);
            } elseif ($attachment->encoding == 4) { // 4 = QUOTED-PRINTABLE
                $attachmentBody = quoted_printable_decode($attachmentBody);
            }
            file_put_contents('temp_upload/' . $filename, $attachmentBody);
            $this->moveFile($filename,$claim_id,$email_id);
            $attachments[] = $filename;
        }
    }
    return $attachments;
}
function extractClaimId($subject)
{
$parts = explode("-", $subject);
$inid = 0;
if ($this->containsWRefNo($subject)) {
    $inid = $this->extractNumbers($subject);
}
else
{
    $number_parts = array_slice($parts, -1);
    $inid = $number_parts[0]; 
}
$number_parts = array_slice($parts, -1);
return $inid;
}
function replaceUnwanted($htmlContent) {
    // Define replacements for specific tags
    $replacements = [
        'Content-Type: text/plain; charset=us-ascii' => '', 
        'Content-Transfer-Encoding: Quoted-printable' => ''
    ];

    // Replace specific tags
    $htmlContent = strtr($htmlContent, $replacements);
    return $htmlContent;
}

function cleanHTMLContent1($htmlContent) {
    // Define replacements for specific tags
    $replacements = [
        '<p>' => '', 
        '</p>' => ' \n',
        '<div>' => '', 
        '</div>' => ' \n',
        '<br>' => ' \n',
        '&nbsp;' => ' '
    ];

    $doubr = ['\n \n \n' => ' \n\n'];

    // Replace specific tags
    $htmlContent = strtr($htmlContent, $replacements);
    

    // Remove all other HTML tags
    $htmlContent = strip_tags($htmlContent);

    // Decode special HTML entities and clean up unwanted characters
    $htmlContent = html_entity_decode($htmlContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $htmlContent = preg_replace('/[^\x20-\x7E]/', '', $htmlContent); // Remove non-ASCII characters
    $htmlContent = strtr($htmlContent, $doubr);
    // Trim whitespace from start and end
    return trim($htmlContent);
}
function cleanHtmlContent($html) {
    // Load the HTML content
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Suppress warnings for invalid HTML
    $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    // Remove unnecessary style attributes
    $xpath = new DOMXPath($dom);
    foreach ($xpath->query("//*[@style]") as $node) {
        $node->removeAttribute("style");
    }

    // Remove unwanted characters like ï»¿
    $cleanedHtml = $dom->saveHTML();
    $cleanedHtml = preg_replace('/ï»¿/', '', $cleanedHtml);
    $cleanedHtml =  $this->cleanHtmlContent1( $cleanedHtml);
    return $cleanedHtml;
}

function extractNumbers($statement) {
    $pattern = '/-\s(\d{6})\s-/'; // Matches six-digit numbers surrounded by " - <number> - "
    $number = 0;
        if (preg_match($pattern, $statement, $matches)) {
            $number = $matches[1]; // Extract the matched number
        }   

    return $number;
}
function convertToInt($value) {
    if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
        return (int) $value;
    }
    return 0;
}

function removeContentTypeSection($emailContent) {
    // Define the pattern to match the boundary and Content-Type sections
    $pattern = '/^--[a-zA-Z0-9]+[\s\S]*?Content-Type:.*?\n\n/s';

    // Remove the matched pattern from the email content
    $cleanedEmail = preg_replace($pattern, '', $emailContent);
    $pattern1 = '/--00[a-zA-Z0-9]*\r?\n/';
    $cleanedEmail = preg_replace($pattern1, '', $cleanedEmail);

    return $cleanedEmail;
}

function uploadWasabi($file_name)
{
$bucketName = 'mcafiles';
$region = 'us-east-1';
$accessKey = @'A622UIYRH3E7O3RPZ2P4';
$secretKey = @'w7g3myju72oCm2EzeqeYK34Kmsrk0IKQjCC2G6ea';

$s3Client = new S3Client([
    'version' => 'latest',
    'region' => $region,
    'endpoint' => 'https://s3.wasabisys.com',
    'credentials' => [
        'key' => $accessKey,
        'secret' => $secretKey,
    ],    
    'http' => [
        'verify' => false,
    ],
]);

$filePath = 'temp_upload/'.$file_name;
$keyName = basename($filePath); // The name of the file in the bucket

try {
    // Upload data
    $result = $s3Client->putObject([
        'Bucket' => $bucketName,
        'Key' => $keyName,
        'SourceFile' => $filePath,
    ]);

} catch (AwsException $e) {
    // Output error message if fails
    //echo "Error uploading file: " . $e->getMessage() . "\n";
}
}

function createPlainInput($field_type,$field,$answers,$value,$state,$claim_id)
{
    $input = "";
    $farr = ["radio","checkbox"];   
    if(in_array($field_type,$farr))
    {
        $inclass = "uk-".$field_type;
        $ine = $field_type=="checkbox"?$field."[]":"$field_type";
        foreach($answers as $answer)
        {
            $input .= "<label style='padding-bottom: 50px !important;'><input class='$inclass' type='$field_type' name='$ine' value='$answer'><span>$answer</span></label><br>";
        }
    }
    else
    {
       
        if(in_array($field,$this->arrpracticename) || in_array($field,$this->arrpracticenumber))
        {
        $listname = $field."1";
        $input = "<input type='$field_type' list='$listname' class='uk-input' placeholder='$field' name='$field' REQUIRED>";
        $input .= " <datalist id='$listname'>";
        foreach($value as $val)
        {
            $in = in_array($field,$this->arrpracticenumber)?$val["practice_number"]:$val["name_initials"]." ".$val["surname"];
            $input .= " <option value='$in'>";         

        }
        $input .= "</datalist>";
        }
        elseif($field=="practice_tag")
        {
             $input .= "<textarea id='fdr' name='fdr' hidden REQUIRED></textarea><div class='row'><div class='col-md-6'>Provider(s)</div><div class='col-md-6'>Service Date(s)</div></div>";
            foreach($value as $val)
        {
            
            $input .= "<fieldset><label style='width:100%'><input type='checkbox' class='group-check'><span style='width:100%'>"; 
            $service_dates = $this->getTreatmentDates($claim_id,$val["practice_number"]);
            $input .="<div class='row'><div class='col-md-6'><select name='practice-doc' class='practice-doc uk-select'>";
            $in = $val["practice_number"]."-".$val["name_initials"]." ".$val["surname"];
            $input .= " <option value='$in'>$in</option>"; 
            $input .= "</select></div>"; 
            
            $input .= "<div class='col-md-6'><select name='service-date-doc' class='service-date-doc uk-select'>";
            foreach($service_dates as $service_date)
            {
                $ddat = $service_date["treatmentDate"];
                $input .= " <option value='$ddat'>$ddat</option>"; 
            }
            $input .= "</select></div></div>";
            $input .="</span></label></fieldset>";
        }        
   
        }

        elseif($field=="practice_tag1")
        {
             $input .= "<textarea id='fdr1' name='fdr1' hidden REQUIRED></textarea><div class='row'><div class='col-md-6'>Provider(s)</div><div class='col-md-6'>Service Date(s)</div></div>";
            foreach($value as $val)
        {
            
            $input .= "<fieldset><label style='width:100%'><input type='checkbox' class='group-check1'><span style='width:100%'>"; 
            $service_dates = $this->getTreatmentDates($claim_id,$val["practice_number"]);
            $input .="<div class='row'><div class='col-md-6'><select name='practice-doc' class='practice-doc uk-select'>";
            $in = $val["practice_number"]."-".$val["name_initials"]." ".$val["surname"];
            $input .= " <option value='$in'>$in</option>"; 
            $input .= "</select></div>"; 
            
            $input .= "<div class='col-md-6'><select name='service-date-doc' class='service-date-doc uk-select'>";
            foreach($service_dates as $service_date)
            {
                $ddat = $service_date["treatmentDate"];
                $input .= " <option value='$ddat'>$ddat</option>"; 
            }
            $input .= "</select></div></div>";
            $input .="</span></label></fieldset>";
        }        
   
        }
        
        else
        {
        if($field=="tariff_codes")
        {
            $ictarif = $this->getIcdTariff($claim_id);
            $value = $ictarif["tariff_codes"];
        }
        elseif($field=="icd_codes")
        {
            $ictarif = $this->getIcdTariff($claim_id);
            $value = $ictarif["primaryICDCode"];
        }
            $input = "<input type='$field_type' class='uk-input' placeholder='$field' name='$field' value='$value' $state REQUIRED>"; 
        }
              
    }
    return $input;
}

function getInputValue($field,$claim_data,$doctor_data)
{
    $value = "";
    if($field == "gap_name")
    {
        $value = $claim_data["client_name"]=="Individual"?$claim_data["indiv_gap"]:$claim_data["client_name"];
    }
    elseif($field == "scheme_name")
    {
        $value = $claim_data["medical_scheme"];
    }
    elseif($field == "member_number")
    {
        $value = $claim_data["scheme_number"];
    }
    elseif($field == "policy_number")
    {
        $value = $claim_data["policy_number"];
    }
    elseif($field == "claim_number")
    {
        $value = $claim_data["claim_number"];
    }
    elseif($field == "patient_name")
    {
        $value = $claim_data["patient_name"];
    }
    elseif($field == "dependent_name")
    {
        $value = $claim_data["patient_name"];
    }
    elseif($field == "service_date")
    {
        $value = $claim_data["Service_Date"];
    }
    elseif($field == "account_number")
    {
        $value = count($doctor_data)>0?$doctor_data[0]['provider_invoicenumber']:"";
    }
    elseif($field == "practice_tag" || $field == "practice_tag1" || in_array($field,$this->arrpracticename) || in_array($field,$this->arrpracticenumber))
    {
        $value = $doctor_data;
    }

    return $value;
}
function replaceMultipleBrWithSingle($input) {
    // Use regular expression to find multiple <br> or <br /> and replace with a single <br>
    $output = preg_replace('/(<br\s*\/?>\s*)+/', '<br/>', $input);
    $output = preg_replace('/(Good Day,)(<br\s*\/?>)/i', "Good Day,<br /><br />", $output);
    return $output;
}
function containsKeywords($string)
{
    $keywords = ['Ticket', 'MailRef#', 'We confirm receipt', 'AutoResponse:', 'Automatic reply:', 'Receipt Of Enquiry', 'Acknowledgement of email','Automailer:','Out of office', 'Auto Response'];
    foreach ($keywords as $keyword) {
        if (stripos($string, $keyword) !== false) {
            return true;
        }
    }
}
}