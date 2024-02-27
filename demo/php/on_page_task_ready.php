<?php
// You can download this file from here https://api.dataforseo.com/v3/_examples/php/_php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

try {
	$result = array();
	// #1 - using this method you can get a list of completed tasks
	// GET /v3/on_page/tasks_ready
	$tasks_ready = $client->get('/v3/on_page/tasks_ready');
	// you can find the full list of the response codes here https://docs.dataforseo.com/v3/appendix/errors
	if (isset($tasks_ready['status_code']) AND $tasks_ready['status_code'] === 20000) {
		foreach ($tasks_ready['tasks'] as $task) {
			if (isset($task['result'])) {
				foreach ($task['result'] as $task_ready) {
					// #2 - using this method you can get results of each completed task
					// GET /v3/on_page/summary/$id
					if (isset($task_ready['id'])) {
						$result[] = $client->get('/v3/on_page/summary/' . $task_ready['id']);
					}
				}
			}
		}
	}
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
