<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

$post_array = array();
// example #1 - a simple way to set a task
$post_array[] = array(
	"url" => "https://dataforseo.com"
);
// example #2 - a way to set a task with additional parameters
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
	"tag" => "some_string_123",
	"pingback_url" => 'https://your-server.com/pingscript?id=$id&tag=$tag'
);

// this example has a 2 elements, but in the case of large number of tasks - send up to 100 elements per POST request
if (count($post_array) > 0) {
	try {
		// POST /v3/on_page/lighthouse/task_post
		// the full list of possible parameters is available in documentation
		$result = $client->post('/v3/on_page/lighthouse/task_post', $post_array);
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
}
$client = null;
?>