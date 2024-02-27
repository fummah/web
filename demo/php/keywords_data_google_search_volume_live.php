<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// simple way to set a task
$post_array[] = array(
	"location_name" => "United States",
	"language_name" => "English",
	"keywords" => array(
		"average page rpm adsense",
		"adsense blank ads how long",
		"leads and prospects"
	)
);
try {
	// POST /v3/keywords_data/google_ads/search_volume/live
	// the full list of possible parameters is available in documentation
	$result = $client->post('/v3/keywords_data/google_ads/search_volume/live', $post_array);
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
