<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// simple way to get a result
$post_array[] = array(
	"id" => "07281559-0695-0216-0000-c269be8b7592",
	"url" => "https://dataforseo.com/wp-content/plugins/wp-video-lightbox/wp-video-lightbox.css?ver=4.7.18"
);
try {
	// POST /v3/on_page/pages_by_resource
	// the full list of possible parameters is available in documentation
	$result = $client->post('/v3/on_page/pages_by_resource', $post_array);
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
