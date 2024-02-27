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
   "keyword_fields" => array(
      "snippet" => "logitech"
   ),
   "page_type" => array(
      "ecommerce",
      "news",
      "blogs",
      "message-boards",
      "organization"
   ),
   "search_mode" => "as_is",
   "filters" => array(
      "main_domain",
      "=",
      "reviewfinder.ca"
   ),
   "internal_list_limit" => 8,
   "order_by" => array(
      "content_info.sentiment_connotations.anger,desc"
   ),
   "limit" => 10
);
try {
   // POST /v3/content_analysis/search/live
   $result = $client->post('/v3/content_analysis/search/live', $post_array);
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