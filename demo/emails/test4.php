<?php
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Gmail;

function getGmailMessage($messageId, $accessToken)
{
    $client = new Client();
    $client->setAccessToken($accessToken);

    $service = new Gmail($client);
    $user = 'me';

    // Fetch the email message
    $message = $service->users_messages->get($user, $messageId, ['format' => 'full']);
    $payload = $message->getPayload();
    $headers = $payload->getHeaders();
    $parts = $payload->getParts();

    // Extract email body
    $body = '';
    if ($parts) {
        foreach ($parts as $part) {
            if ($part->getMimeType() == 'text/plain') {
                $body = base64_decode(str_replace(['-', '_'], ['+', '/'], $part->getBody()->getData()));
            }
        }
    }

    // Extract attachments
    $attachments = [];
    foreach ($parts as $part) {
        if ($part->getFilename()) {
            $attachmentId = $part->getBody()->getAttachmentId();
            $attachment = $service->users_messages_attachments->get($user, $messageId, $attachmentId);
            $data = base64_decode(str_replace(['-', '_'], ['+', '/'], $attachment->getData()));

            $attachments[] = [
                'filename' => $part->getFilename(),
                'mimeType' => $part->getMimeType(),
                'data' => $data
            ];
        }
    }

    return [
        'body' => $body,
        'attachments' => $attachments
    ];
}

// Example usage
$accessToken = '{"access_token":"ya29.a0AeXRPp6kqUlPxZmtAAz4LOaTJIcSzJrkOomD2FqM4n-v9lQXTk8prdr2OnJtqowu4aoSFk_qdlj4EIsBM5SLBI_W5Z_CwxLiRDg7KQQE8HHa881XD3uxUw-nP41O8e5yHQbw0zMUlMw4EqpQ-PbNriqPLH9sqDC5z6-wNIPXaCgYKAQwSARASFQHGX2Mi2mFZ9g-jirff5sTAAZ6YiQ0175","expires_in":3599,"refresh_token":"1\/\/03ggyf9Yr4WfGCgYIARAAGAMSNwF-L9IroB7voilH06qmk5xvzlfYZ5FYjy5qzty5-hOjEVNq_bk0UF73ML9A2DSyYPWVYRPGs6I","scope":"https:\/\/mail.google.com\/","token_type":"Bearer","created":1741496263}'; // Get this from OAuth authentication
$messageId = '<CAOBVvjZ7qxUYV-AKhKEA5B7UcZ1x9D=w-8mOf6u091fLi1Jw_g@mail.gmail.com>';

$emailData = getGmailMessage($messageId, $accessToken);
echo "Email Body:\n" . $emailData['body'] . "\n";
foreach ($emailData['attachments'] as $attachment) {
    file_put_contents($attachment['filename'], $attachment['data']); // Save attachment
    echo "Saved attachment: " . $attachment['filename'] . "\n";
}
echo "Done";
?>