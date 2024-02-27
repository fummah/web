<?php
// You can download this file from here https://cdn.dataforseo.com/v3/examples/php/php_RestClient.zip
require('RestClient.php');
$api_url = 'https://api.dataforseo.com/';
// Instead of 'login' and 'password' use your credentials from https://app.dataforseo.com/api-dashboard
$client = new RestClient($api_url, null, 'fummah3@gmail.com', 'c74740b4130998c6');

$post_array = array();
// simple way to get a result
$post_array[] = array(
   "id" => "09071413-6616-0216-0000-c8b7eb43293b",
   "filters" => [
      ["resource_type", "=", "html"],
      "and",
      ["meta.scripts_count", ">", 40]
   ],
   "order_by" => ["meta.content.plain_text_word_count,desc"],
   "limit" => 10
);
try {
   // POST /v3/on_page/pages
   // the full list of possible parameters is available in documentation
   $result = $client->post('/v3/on_page/pages', $post_array);
   echo json_encode($result);
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
