<?php
// You can download this file from here https://api.dataforseo.com/v3/_examples/php/_php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

try {
	$result = array();
	// using this method you can get summary for task
	// GET /v3/on_page/summary/$id
	$id = "07281559-0695-0216-0000-c269be8b7592";
	$result[] = $client->get('/v3/on_page/summary/' . $id);
	print_r($result);
	// do something with result
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
