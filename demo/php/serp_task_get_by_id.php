<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

try {
	// get the task results by id
	// GET /v3/serp/google/organic/task_get/advanced/$id
	// in addition to 'advanced' you can also set other type parameters
	// the full list of possible parameters is available in documentation
	// use the task identifier that you recieved upon setting a task
	$id = "12021127-0696-0087-0000-e7b313697ffc";
	$result = $client->get('/v3/serp/google/organic/task_get/advanced/' . $id);
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
