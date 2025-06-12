<?php
// Include the Composer autoload file
require $_SERVER['DOCUMENT_ROOT'] . '/web/testing/emails/vendor/autoload.php';

class Controls
{
    private $client;

    public function __construct()
    {
        echo $_SERVER['DOCUMENT_ROOT'] . '/emails/vendor/autoload.php';
        // Create a new Google_Client instance
        $this->client = new Google_Client();
        
        // Set application name (optional)
        $this->client->setApplicationName('Your App Name');
    }
    public function getClient()
    {
        return $this->client;
    }
}

$controls = new Controls();
$client = $controls->getClient();  // Now you have access to the Google_Client instance
echo "Done";
?>
