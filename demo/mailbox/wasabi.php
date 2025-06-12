<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Configuration for Wasabi
$bucketName = 'mcafiles';
$region = 'us-east-1'; // Your Wasabi region
$accessKey = @'A622UIYRH3E7O3RPZ2P4';
$secretKey = @'w7g3myju72oCm2EzeqeYK34Kmsrk0IKQjCC2G6ea';

// Instantiate the S3 client using Wasabi configuration
$s3Client = new S3Client([
    'version' => 'latest',
    'region' => $region,
    'endpoint' => 'https://s3.wasabisys.com',
    'credentials' => [
        'key' => $accessKey,
        'secret' => $secretKey,
    ],    
    'http' => [
        'verify' => false, // Disable SSL verification
    ],
]);

// Path to the file you want to upload
$filePath = 'temp_upload/Project Milestones.docx';
$keyName = basename($filePath); // The name of the file in the bucket

try {
    // Upload data
    $result = $s3Client->putObject([
        'Bucket' => $bucketName,
        'Key' => $keyName,
        'SourceFile' => $filePath,
    ]);

    // Print the URL to the object.
    echo "File uploaded successfully. File URL: " . $result['ObjectURL'] . "\n";
} catch (AwsException $e) {
    // Output error message if fails
    echo "Error uploading file: " . $e->getMessage() . "\n";
}
