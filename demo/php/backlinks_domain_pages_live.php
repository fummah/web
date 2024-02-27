<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');
$post_array = array();
// simple way to get a result
$post_array[] = array(
   "target" => "forbes.com",
   "filters" => [
      ["page_summary.backlinks", ">", 5],
      "and",
      ["page", "like", "%sites%"]
   ],
   "limit" => 5
);
try {
   // POST /v3/backlinks/domain_pages/live
   // the full list of possible parameters is available in documentation
   $result = $client->post('/v3/backlinks/domain_pages/live', $post_array);
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