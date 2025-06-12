<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

define("access",true);
require 'vendor/autoload.php';
require '../classes/controls.php';

$control = new controls();
$conf = $control->viewEmailCredentils();
$accessToken = json_decode($conf["newcase_oauth"],true);

$client = new Google_Client();
$client->setClientId('554203116355-pr6cbedkb684o3i3do6n1uld8c08suet.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/web/testing/emails/oauth.php');
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

$client->setAccessToken($accessToken);

// Check if token has expired
if ($client->isAccessTokenExpired()) {
    // Automatically refresh the token using the refresh token
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    $accessToken = $client->getAccessToken();
    $control->updateEmailCredentils(json_encode($accessToken));
}

$token = $accessToken["access_token"];
print_r($accessToken);
echo json_encode($token);

$mail = new PHPMailer(true);
try {
    // OAuth settings with the custom Guzzle client
    $provider = new Google([
        'clientId'     => '554203116355-pr6cbedkb684o3i3do6n1uld8c08suet.apps.googleusercontent.com',
        'clientSecret' => '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',
    ]);

    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->AuthType   = 'XOAUTH2';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Set OAuth token
    $mail->setOAuth(new OAuth([
        'provider'       => $provider,
        'clientId'       => '554203116355-pr6cbedkb684o3i3do6n1uld8c08suet.apps.googleusercontent.com',
        'clientSecret'   => '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',
        'refreshToken'   => $token,
        'userName'       => 'fummah4@gmail.com',
    ]));

    // Recipients
    $mail->setFrom('fummah4@gmail.com', 'Your Name');
    $mail->addAddress('tendai@medclaimassist.co.za', 'Dziva Tendai');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent via Gmail SMTP using OAuth. Test at 9:31';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>