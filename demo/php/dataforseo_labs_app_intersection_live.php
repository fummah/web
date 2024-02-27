<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// simple way to set a task
$post_array[] = array(
	"app_ids" => [
		"1" => "org.telegram.messenger",
		"2" => "com.zhiliaoapp.musically"
	],
	"language_code" => "en",
	"location_code" => 2840,
	"filters" => [
		"keyword_data.keyword_info.search_volume",
		">",
		10000
	],
	"order_by" => [
		"keyword_data.keyword_info.search_volume,desc"
	],
	"limit" => 10,
);
try {
	// POST /v3/dataforseo_labs/google/app_intersection/live
	// POST /v3/dataforseo_labs/apple/app_intersection/live
	$result = $client->post('/v3/dataforseo_labs/google/app_intersection/live', $post_array);
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
