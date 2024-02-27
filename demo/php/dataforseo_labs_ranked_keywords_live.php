<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// simple way to set a task
$post_array[] = array(
	"target" => "dataforseo.com",
	"language_name" => "English",
	"location_code" => 2840,
	"filters" => [
		["keyword_data.keyword_info.search_volume", ">", 10],
		"and",
		[
			["ranked_serp_element.serp_item.type", "<>", "paid"],
			"or",
			["ranked_serp_element.serp_item.is_paid", "=", false]
		]
	],
	"limit" => 3
);
try {
	// POST /v3/dataforseo_labs/google/ranked_keywords/live
	// POST /v3/dataforseo_labs/bing/ranked_keywords/live
	// POST /v3/dataforseo_labs/amazon/ranked_keywords/live
	// for amazon use 'asin' instead of 'target'
	$result = $client->post('/v3/dataforseo_labs/google/ranked_keywords/live', $post_array);
	print_r($result);
	// do something with post result
} catch (RestClientException $e) {
	echo "\n";
	print "HTTP code: {$e->getHttpCode()}\n";
	print "Error code: {$e->getCode()}\n";
	print "Message: {$e->getMessage()}\n";
	print  $e->getTraceAsString();
	echo "\n";
}
$client = null;
?>
