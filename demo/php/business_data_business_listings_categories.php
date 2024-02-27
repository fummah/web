<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip?202197
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');
try {
   // GET /v3/business_data/business_listings/categories
   $result = $client->get('/v3/business_data/business_listings/categories');
   print_r($result);
   // do something with post result
} catch (RestClientException $e) {
   echo "n";
   print "HTTP code: {$e->getHttpCode()}n";
   print "Error code: {$e->getCode()}n";
   print "Message: {$e->getMessage()}n";
   print  $e->getTraceAsString();
   echo "n";
}
$client = null;
?>