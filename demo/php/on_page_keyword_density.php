<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// simple way to get a result
$post_array[] = array(
	"id" => "09091234-2692-0216-1000-811e2cf91d64",
	"keyword_length" => 2,
	"filters" => ["frequency", ">", 5],
	"order_by" => ["frequency,desc"],
	"limit" => 10
);
try {
	// POST /v3/on_page/keyword_density
	// the full list of possible parameters is available in documentation
	$result = $client->post('/v3/on_page/keyword_density', $post_array);
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
