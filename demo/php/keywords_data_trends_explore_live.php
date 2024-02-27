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
	"date_from" => "2019-01-01",
	"date_to" => "2020-01-01",
	"type" => "youtube",
	"category_code" => 3,
	"keywords" => array(
		"seo api",
		"rank api"
	)
);
try {
	// POST /v3/keywords_data/google_trends/explore/live
	$result = $client->post('/v3/keywords_data/google_trends/explore/live', $post_array);
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
