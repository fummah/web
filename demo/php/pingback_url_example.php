<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';

function _in_logit_GET($id_message, $data) {
	@file_put_contents(__DIR__ . "/pingback_url_example.log", PHP_EOL . date("Y-m-d H:i:s") . ": " . $id_message . PHP_EOL . "---------" . PHP_EOL . print_r($data, true) . PHP_EOL . "---------", FILE_APPEND);
}

if (!empty($_GET["id"])) {
	// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
	$client = new RestClient($api_url, null, 'login', 'password');
	
	try {
		$serp_result = $client->get('/v3/serp/google/organic/task_get/advanced/' . $_GET["id"]);
	} catch (RestClientException $e) {
		echo "\n";
		print "HTTP code: {$e->getHttpCode()}\n";
		print "Error code: {$e->getCode()}\n";
		print "Message: {$e->getMessage()}\n";
		print  $e->getTraceAsString();
		echo "\n";
		exit();
	}
	// you can find the full list of the response codes here https://docs.dataforseo.com/v3/appendix/errors
	if (isset($serp_result['status_code']) AND $serp_result['status_code'] === 20000) {
		_in_logit_GET('ready', $serp_result);
		// do something with results	
		echo "ok";
	} else {
		echo "error";
	}
} else {
	echo "empty GET";
}
?>
