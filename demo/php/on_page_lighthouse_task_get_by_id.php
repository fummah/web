<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

try {
	// get the task results by id
	// GET /v3/on_page/lighthouse/task_get/json/$id
	// use the task identifier that you recieved upon setting a task
	$id = "07161117-2692-0317-1000-75f3a73975f8";
	$result = $client->get('/v3/on_page/lighthouse/task_get/json/' . $id);
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
