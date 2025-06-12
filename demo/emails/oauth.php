<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

define("access",true);
$_SESSION['logxged'] = true;
$_SESSION['start_db']=true;
$_SESSION["admin_main"]=true;

require 'vendor/autoload.php';
require '../classes/controls.php';
$_SESSION["admin_main"]=true;
$_SESSION['level'] = "claims_specialist";
$control = new controls();
$config = $control->viewEmailCredentils();

$aouth_details = json_decode($config["correspondence_details"],true);
//$accessToken = json_decode($conf["oauth"],true);

// Set up Google Client
$client = new Google_Client();
$client->setClientId($aouth_details["clientId"]);
$client->setClientSecret($aouth_details["clientSecret"]);
$client->setRedirectUri($aouth_details["redirectUrl"]);
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
$client->setPrompt('consent');

// If there is no code, redirect to Google's OAuth 2.0 server
if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
}

// Exchange authorization code for an access token
if (isset($_GET['code'])) {
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $accessToken;
    echo json_encode( $accessToken);
    //header('Location: ' . filter_var('oauth.php', FILTER_SANITIZE_URL));
    exit;
}

// Set access token if available
if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
}

// If token expired, refresh it
if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    $_SESSION['access_token'] = $client->getAccessToken();
}
if (isset($_SESSION['access_token'])) {
    echo "<h1>Your Auth Code is successfully updated</h1>";
    $accessToken = $_SESSION['access_token'];
    print_r($accessToken);
}
echo json_encode( $accessToken);
?>