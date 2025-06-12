<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;
require 'vendor/autoload.php';
$mysql_hostname = 'sql2.cpt1.host-h.net';
$mysql_username = 'greenwhc_8';
$mysql_password = 'CpN4WKc0UBmm0PrgN7Zx';
$mysql_dbname="web_clients";
$conn = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getEmailCredentils()
{
    global $conn;
    $stmt = $conn->prepare("SELECT notification_email,notification_password,cc,cc1,newcase_oauth,newcase_details,notifications_oauth,notifications_details FROM email_configs");
    $stmt->execute();
    return $stmt->fetch();
}
  function updateEmailCredentils($oauth)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE email_configs SET newcase_oauth=:oauth");
    $stmt->bindParam(':oauth', $oauth, PDO::PARAM_STR);
    return $stmt->execute();
}


$conf = getEmailCredentils();
$accessToken = json_decode($conf["newcase_oauth"],true);
$aouth_details = json_decode($conf["newcase_details"],true);

$client = new Google_Client();
$client->setClientId($aouth_details["clientId"]);
$client->setClientSecret($aouth_details["clientSecret"]);
$client->setRedirectUri($aouth_details["redirectUrl"]);
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

$client->setAccessToken($accessToken);

// Check if token has expired
if ($client->isAccessTokenExpired()) {
    // Automatically refresh the token using the refresh token
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    $accessToken = $client->getAccessToken();
    updateEmailCredentils(json_encode($accessToken));
}

$token = $accessToken["access_token"];
print_r($accessToken);
echo json_encode($token);

?>