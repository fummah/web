<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');
$post_array = array();
// simple way to set a task
$post_array[] = array(
  "targets" => [
    "1" => "adidas.com",
    "2" => "nike.com"
  ],
  "exclude_targets" => [
    "puma.com"
  ],
  "limit" => 5,
  "include_subdomains" => false,
  "exclude_internal_backlinks" => true,
  "order_by" => [
    "1.backlinks,desc"
  ]
);
try {
   // POST /v3/backlinks/domain_intersection/live
   $result = $client->post('/v3/backlinks/domain_intersection/live', $post_array);
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