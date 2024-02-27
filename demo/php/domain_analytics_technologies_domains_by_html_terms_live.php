<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// You can set only one task at a time
$post_array[] = array(
	"search_terms" => [
		"data-attrid"
	],
	"filters" => [
			"country_iso_code",
			"=",
			"US"
	],
	"order_by" => ["last_visited,desc"],
	"limit" => 10
);
try {
	// POST /v3/domain_analytics/technologies/domains_by_html_terms/live
	$result = $client->post('/v3/domain_analytics/technologies/domains_by_html_terms/live', $post_array);
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
