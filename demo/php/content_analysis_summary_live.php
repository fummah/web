<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');
$post_array = array();
// simple way to set a task
$post_array[] = array(
   "keyword" => "logitech",
   "search_mode" => "as_is",
   "page_type" => array(
      "ecommerce",
      "news",
      "blogs",
      "message-boards",
      "organization"
   ),
   "filters" => array(
      "main_domain",
      "=",
      "reviewfinder.ca"
   ),
   "internal_list_limit" => 3,
   "positive_connotation_threshold" => 0.5,
   "order_by" => array(
      "content_info.sentiment_connotations.anger,desc"
   )
);
try {
   // POST /v3/content_analysis/summary/live
   $result = $client->post('/v3/content_analysis/summary/live', $post_array);
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