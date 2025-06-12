<?php
session_start();
define("access",true);
$_SESSION['start_db']=true;
$_SESSION["admin_main"]=true;
require 'vendor/autoload.php';
require '../classes/controls.php';

use Google\Client;
use Google\Service\Gmail;
$control = new controls();
$config = $control->viewEmailCredentils();
function getGmailMessage($query, $accessToken)
{
    global $control;
    global $config;
    $aouth_details = json_decode($config["correspondence_details"],true);
    $access_details = json_decode($config["correspondence_oauth"],true);

// Set up Google Client
$client = new Google_Client();
$client->setClientId($aouth_details["clientId"]);
$client->setClientSecret($aouth_details["clientSecret"]);
$client->setRedirectUri($aouth_details["redirectUrl"]);
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
$client->setPrompt('consent');

    $client->setAccessToken($accessToken);

    // Check if the token has expired
     // If token is expired, refresh it
     if ($client->isAccessTokenExpired()) {
        $refreshToken = $access_details["refresh_token"]; // Fetch from DB

        if (!$refreshToken) {
            die("❌ No refresh token available. Please reauthorize the app.");
        }

        $client->fetchAccessTokenWithRefreshToken($refreshToken);
        $newAccessToken = $client->getAccessToken();
        $newAccessToken['refresh_token'] = $refreshToken; // Keep the refresh token
        
        // Store the new access token
        $control->updateEmailCredentils(json_encode($newAccessToken), "correspondence_oauth");

        // Set new token
        $client->setAccessToken($newAccessToken);
    }

    $service = new Google_Service_Gmail($client);
    $user = 'me';

    // Search email using query
    $messages = $service->users_messages->listUsersMessages($user, ['q' => $query]);
    
    if (empty($messages->getMessages())) {
        die("❌ No email found with query: $query\n");
    }

    $messageId = $messages->getMessages()[0]->getId();

    // Fetch the email message
    $message = $service->users_messages->get($user, $messageId, ['format' => 'full']);
    $payload = $message->getPayload();
    
    $body = '';
    $isHtml = false;
    $attachments = [];

    // Function to decode base64url encoded data
    function decodeBody($encodedBody) {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $encodedBody));
    }

    // Recursive function to process email parts
    function processParts($parts, &$body, &$isHtml, &$attachments, $service, $user, $messageId)
    {
        foreach ($parts as $part) {
            $mimeType = $part->getMimeType();
            $bodyData = $part->getBody()->getData();

            if ($mimeType === 'text/plain' && !$isHtml) {
                $body = decodeBody($bodyData);
            } elseif ($mimeType === 'text/html') {
                $body = decodeBody($bodyData);
                $isHtml = true;
            } elseif (strpos($mimeType, 'multipart/') === 0) {
                processParts($part->getParts(), $body, $isHtml, $attachments, $service, $user, $messageId);
            } elseif (!empty($part->getFilename()) && !empty($part->getBody()->getAttachmentId())) {
                // Process attachments
                $attachmentId = $part->getBody()->getAttachmentId();
                $attachment = $service->users_messages_attachments->get($user, $messageId, $attachmentId);
                $attachments[] = [
                    'filename' => $part->getFilename(),
                    'mimeType' => $mimeType,
                    'data' => decodeBody($attachment->getData()),
                ];
            }
        }
    }

    // Process the main payload
    if ($payload->getParts()) {
        processParts($payload->getParts(), $body, $isHtml, $attachments, $service, $user, $messageId);
    } elseif ($payload->getBody()->getData()) {
        $body = decodeBody($payload->getBody()->getData());
    }

    return [
        'body' => $body,
        'is_html' => $isHtml,
        'attachments' => $attachments
    ];
}
function deleteFolderContents($folder) {
    if (!is_dir($folder)) {
        return;
    }
    
    $files = array_diff(scandir($folder), ['.', '..']);
    
    foreach ($files as $file) {
        $filePath = $folder . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteFolderContents($filePath);
            rmdir($filePath);
        } else {
            unlink($filePath);
        }
    }
}

if(isset($_POST["message_id"]))
{
    
    
    
    //$aouth_details = json_decode($config["correspondence_details"],true);
$username = $control->loggedAs();
$folder = "".$username;
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
} else {
    deleteFolderContents($folder);
}
// Example usage
$xid = $_POST["message_id"];
$accessToken = $config["correspondence_oauth"];
$mID = str_replace('<','',$xid);
$mID = str_replace('>','',$mID);

$query = 'rfc822msgid:"' . $mID . '"';

$emailData = getGmailMessage($query, $accessToken);
//print_r($emailData);
echo  $emailData['body'] . "<hr><ul>";
foreach ($emailData['attachments'] as $attachment) {
    $path = $folder."/".$attachment['filename'];
    file_put_contents($path, $attachment['data']); // Save attachment
    echo "<li><a target='_blank' href='$path'>" . $attachment['filename'] . "</li>";
}
echo "</ul>";
}
?>