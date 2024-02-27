<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// simple way to get a result
$post_array[] = array(
	"url" => "https://dataforseo.com",
	"for_mobile" => false,
    "categories" => array(
        "seo",
        "performance",
        "pwa"
    ),
    "audits" => array(
        "is-on-https"
    ),
	"tag" => "some_string_123"
);
try {
	// POST /v3/on_page/lighthouse/live/json
	$result = $client->post('/v3/on_page/lighthouse/live/json', $post_array);
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