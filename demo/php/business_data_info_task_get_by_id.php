<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

try {
	// get the task results by id
	// GET /v3/business_data/google/my_business_info/task_get/$id
	// use the task identifier that you recieved upon setting a task
	$id = "09161101-0696-0242-0000-22d3e5db7c45";
	$result = $client->get('/v3/business_data/google/my_business_info/task_get/' . $id);
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
