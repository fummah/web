<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip?202197
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');
$post_array = array();
// simple way to get a result
$post_array[] = array(
   "categories" => ["pizza_restaurant"],
   "description" => "pizza",
   "title" => "pizza",
   "is_claimed" => true,
   "location_coordinate" => "53.476225,-2.243572,10",
   "initial_dataset_filters" => [
      [ "rating.value",">",3 ]
   ],
   "internal_list_limit" => 10,
   "limit" => 3
);
try {
   // POST /v3/business_data/business_listings/categories_aggregation/live
   // the full list of possible parameters is available in documentation
   $result = $client->post('/v3/business_data/business_listings/categories_aggregation/live', $post_array);
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