<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
error_reporting(0);
include ("../../mca/link2.php");
$conn=connection("mca","MCA_admin");
$mail = new PHPMailer(true);
class exc
{
    public $mess;
    function getUser()
    {
        global $conn;
        $sql = $conn->prepare('SELECT username FROM users_information WHERE status=1 ORDER BY datetime ASC LIMIT 1');
        $sql->execute();
        return $sql->fetchColumn();
    }
    function updateUser($username)
    {
        global $conn;
        $dat=date('Y-m-d H:i:s');
        $sql = $conn->prepare('UPDATE users_information SET datetime=:dat WHERE username=:user1');
        $sql->bindParam(':user1', $username, PDO::PARAM_STR);
        $sql->bindParam(':dat', $dat, PDO::PARAM_STR);
        $sql->execute();

    }
    function addLead($fist_name,$last_name,$email,$contact_number,$medical_name,$scheme_number,$amount,$descrip)
    {
        global $conn;
        $username=$this->getUser();
        try {
            $stmt = $conn->prepare('INSERT INTO `lead`(first_name,last_name,email,contact_number,medical_scheme,scheme_number,amount_claimed,description,username) VALUES(:first_name,:last_name,:email,:contact_number,:medical_scheme,:scheme_number,:amount_claimed,:description,:username)');
            $stmt->bindParam(':first_name', $fist_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
            $stmt->bindParam(':medical_scheme', $medical_name, PDO::PARAM_STR);
            $stmt->bindParam(':scheme_number', $scheme_number, PDO::PARAM_STR);
            $stmt->bindParam(':amount_claimed', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':description', $descrip, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $this->updateUser($username);
        }
        catch (Exception $e)
        {
            echo "There is an error : ".$e->getMessage();
        }
    }

    function getLead($email)
    {
        global $conn;
        try {
            $stmt = $conn->prepare('SELECT lead_id FROM lead WHERE email=:email ORDER BY lead_id DESC');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        }
        catch (Exception $e)
        {
            return 0;
        }
    }

    function addFiles($lead_id,$file_type,$file_size,$file_name,$random)
    {
        global $conn;
        try {
            $stmt = $conn->prepare('INSERT INTO `leads_file`(lead_id,file_type,file_name,file_size,random) VALUES(:lead_id,:file_type,:file_name,:file_size,:random)');
            $stmt->bindParam(':lead_id', $lead_id, PDO::PARAM_STR);
            $stmt->bindParam(':file_type', $file_type, PDO::PARAM_STR);
            $stmt->bindParam(':file_name', $file_name, PDO::PARAM_STR);
            $stmt->bindParam(':file_size', $file_size, PDO::PARAM_STR);
            $stmt->bindParam(':random', $random, PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            echo "There is an error : ".$e->getMessage();
        }
    }
    function sendMail($email,$subject,$body)
    {
        $mail = new PHPMailer(true);
        $from="info@medclaimassist.co.za";
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
            $mail->setFrom($from, 'Medclaim Assist');
            $mail->addAddress($email, 'MCA User');
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body."<br>The Med ClaimAssist Team";
            //$mail->AddAttachment('../../mca/schemes/' . getConsentDetails($scheme, $spName));
            //$mail->send();

            if (!$mail->send()) {

                $this->mess=false;
            }
        }
    }
    function getEncrpass()
    {
        global  $conn;
        $stmt = $conn->prepare("SELECT notification_email,notification_password FROM email_configs");
        $stmt->execute();
        return $stmt->fetch();
    }

    function my_utf8_decode($string)
    {
        return strtr($string,
            "???????",
            "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
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
}
$obj=new exc();
$obj->mess=true;
$data = json_decode(file_get_contents("php://input"));
/*
$json = file_get_contents("php://input");
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, $json);
fclose($myfile);

if (empty($json)) {
    echo "No data payload";
    die;
}
$contact = json_decode($json,true);
if ($contact == null && json_last_error() !== JSON_ERROR_NONE) {
    die ("Error reading JSON: " . json_last_error());
}
$json_enco=$contact;

$fist_name=isset($json_enco['Name']['First'])?$json_enco['Name']['First']:'';
$last_name=isset($json_enco['Name']['Last'])?$json_enco['Name']['Last']:'';
$email=isset($json_enco['Email'])?$json_enco['Email']:'';
$contact_number=isset($json_enco['ContactNumber'])?$json_enco['ContactNumber']:'';
$medical_name=isset($json_enco['MedicalSchemeName'])?$json_enco['MedicalSchemeName']:'';
$scheme_number=isset($json_enco['MedicalAidNumber'])?$json_enco['MedicalAidNumber']:'';
$amount=(double)isset($json_enco['AmountToBeClaimed'])?$json_enco['AmountToBeClaimed']:'';
$descrip=isset($json_enco['DetailsYouWouldLikeToAdd'])?$json_enco['DetailsYouWouldLikeToAdd']:'';
*/
$fist_name=ucfirst($obj->validateXss($data->first_name));
$last_name=ucfirst($obj->validateXss($data->last_name));

$contact_number=$data->contact_number;
$email=$data->email;
$medical_name=$obj->validateXss($data->scheme);
$scheme_number=$data->scheme_number;
$amount=(double)$data->amount;
$descrip=$obj->validateXss($data->txt);
$remd=$amount-575;

$files = explode(";",$data->files);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address");
}


$obj->addLead($fist_name,$last_name,$email,$contact_number,$medical_name,$scheme_number,$amount,$descrip);
$lead_id=$obj->getLead($email);

for ($i = 0; $i < count($files); $i++) {

    $original_filename = $files[$i];
    if (strlen($original_filename)>2)
    {
        $randrom=rand(1000,9999);
        $filePath = '../temp_upload/'.$original_filename;
        $size=filesize($filePath);
        $type=filetype($filePath);
        if(file_exists($filePath))
        {
            /* Store the path of destination file */
            $destinationFilePath = '../../mca/leads/'.$randrom.$original_filename;

            /* Move File from images to copyImages folder */
            if( !rename($filePath, $destinationFilePath) ) {

            }
            else {

                $obj->addFiles($lead_id,$type,$size,$original_filename,$randrom);
            }
        }
    }


}
$amount=number_format($amount,2,'.',' ');
$remd=number_format($remd,2,'.',' ');
$sub1="You've contacted Med ClaimAssist";
$sub2="Contact Us Web Form - $fist_name $last_name";

$ttb="<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"85%\" class=\"x_wrapper\" style=\"margin-top:20px; margin-bottom:0; border-collapse:collapse!important\">
    <tbody>
    <tr>
        <td valign=\"top\" style=\"\">
            <table width=\"100%\">
                <tbody>
                <tr>
                    <td style=\"padding-bottom:20px\">
                        <h2 class=\"x_header-2\" style=\"color:#333333; display:block; font-family:helvetica,arial,sans-serif; font-size:1.7em; font-style:normal; font-weight:bold; line-height:100%; letter-spacing:normal; text-align:left; margin-bottom:0\">Entry Details</h2>
                    </td>
                </tr>
                </tbody>
            </table>
            <table width=\"100%\" cellpadding=\"12\" class=\"x_wrapper\" style=\"border-collapse:collapse!important\">
                <tbody>
                <tr>
                    <td class=\"x_real-table-container\" style=\"background:white;\">
                        <table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" style=\"border-collapse:collapse!important\">
                            <tbody>
                            <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">Name</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">$fist_name $last_name</td>
                            </tr>
                            <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">Contact number</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">$contact_number</td>
                            </tr>
                            <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">Email</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">$email
                                </td>
                            </tr>
                            <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">Medical Aid Scheme</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">$medical_name</td>
                            </tr>
                            <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">Medical Aid Number</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">$numeme_number</td>
                            </tr>
   <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">AMOUNT TO BE CLAIMED</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">R $amount</td>
                            </tr>
                            <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">Amount you'll get out, if we are able to assist you</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">R $remd</td>
                            </tr>
                            <tr>
                                <th valign=\"top\" style=\"width:40%; font-size:12px; text-align:left; text-transform:uppercase; color:#777777; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">Details you would like to add?</th>
                                <td valign=\"top\" style=\"text-align:left; padding-top:15px; padding-bottom:15px; border-bottom:1px solid #eeeeee; font-family:Helvetica,Arial,sans-serif\">$descrip</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>";
$body1="<p>Thank you for contacting Med ClaimAssist. One of our team will contact you within 2-4 days.</p>".$ttb;
$body2="<p>New Lead received, you may login to check the details.</p>".$ttb;

$obj->sendMail($email,$sub1,$body1);
$obj->sendMail("info@medclaimassist.co.za",$sub2,$body2);

if($obj->mess)
{
    echo "Success";
}
else{
    echo "Failed";
}
?>