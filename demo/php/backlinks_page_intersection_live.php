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
      "1" => "football.com",
      "2" => "fifa.com"
   ],
   "exclude_targets" => [
      "skysports.com"
   ],
   "limit" => 10,
   "order_by" => [
      "1.rank,desc"
   ],
   "filters" => [
      [
         "2.domain_from_rank",
         ">",
         400
      ],
      "and",
      [
         "1.dofollow",
         "=",
         true
      ]
   ]
);
try {
   // POST /v3/backlinks/page_intersection/live
   $result = $client->post('/v3/backlinks/page_intersection/live', $post_array);
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