<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');
$post_array = array();
// simple way to set a task
$post_array[] = array(
  "task_id" => "06211235-0696-0139-1000-36727fbd3c90",
  "prompt" => "give a short description of 100 characters",
  "include_links" => true,
  "fetch_content" => true,
  "suport_extra" => true
);
try {
   // POST /v3/serp/ai_summary
   $result = $client->post('/v3/serp/ai_summary', $post_array);
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
