<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// You can set only one task at a time
$post_array[] = array(
	"technologies" => [
		"Nginx"
	],
	"keywords" => [
		"WordPress"
	],
	"filters" => [
		[
			"country_iso_code",
			"=",
			"US"
		],
		"and",
		[
			"domain_rank",
			">",
			800
		]
	]
);
try {
	// POST /v3/domain_analytics/technologies/technologies_summary/live
	$result = $client->post('/v3/domain_analytics/technologies/technologies_summary/live', $post_array);
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
