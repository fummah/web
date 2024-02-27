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
		"org.telegram.messenger",
		"com.zhiliaoapp.musically",
		"com.whatsapp",
		"com.facebook.katana"
	],
	"location_code" => 2840,
	"language_code" => "en"
);
try {
	// POST /v3/dataforseo_labs/google/bulk_app_metrics/live
	// POST /v3/dataforseo_labs/apple/bulk_app_metrics/live
	$result = $client->post('/v3/dataforseo_labs/google/bulk_app_metrics/live', $post_array);
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
