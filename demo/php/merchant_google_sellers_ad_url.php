<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'login', 'password');

try {
	// get ad_url by the ad_aclk
	// GET /v3/merchant/google/sellers/ad_url/$ad_aclk
	// use the ad_aclk that you received upon a sellers task
	$ad_aclk = "DChcSEwiSl5TKpbPoAhVFmdUKHfa_B_wYABADGgJ3cw&sig";
	$result = $client->get('/v3/merchant/google/sellers/ad_url/' . $ad_aclk);
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
