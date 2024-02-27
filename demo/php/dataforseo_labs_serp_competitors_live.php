<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// simple way to set a task
$post_array[] = array(
	"keywords" => [
		"phone",
		"watch"
	],
	"language_name" => "English",
	"location_code" => 2840,
	"filters" => [
		["relevant_serp_items", ">", 0],
		"or",
		["median_position", "in", [ 1, 10 ]]
	]
);
try {
	// POST /v3/dataforseo_labs/google/serp_competitors/live
	// POST /v3/dataforseo_labs/bing/serp_competitors/live
	$result = $client->post('/v3/dataforseo_labs/google/serp_competitors/live', $post_array);
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
