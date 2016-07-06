<?php

use GuzzleHttp\Client;

require __DIR__.'/vendor/autoload.php';

$client = new Client([
    'base_uri' => 'http://localhost:8000',
    'http_errors' => false
]);

$name = 'Category'.rand(0, 999);
$data = array(
    'name' => $name,
);
// 1) POST to create category
$response = $client->post('/api/categories', [
    'body'  => json_encode($data)
]);

echo $response->getBody();
echo "\n";
echo $response->getStatusCode();
echo "\n";
echo $response->getHeaderLine('Content-Type');
echo "\n";
echo $response->getHeaderLine('Location');
echo "\n";

$categoryUrl = $response->getHeaderLine('Location');

// 2) GET to get view category
$response = $client->get($categoryUrl);

echo $response->getBody();
echo "\n\n";
echo $response->getStatusCode();
echo "\n\n";
echo $response->getHeaderLine('Content-Type');
echo "\n\n";
echo $response->getHeaderLine('Location');
echo "\n\n";

// 3) GET a collection of categories
$response = $client->get('/api/categories');

echo $response->getBody();
echo "\n\n";
echo $response->getStatusCode();
echo "\n\n";
echo $response->getHeaderLine('Content-Type');
echo "\n\n";
echo $response->getHeaderLine('Location');
echo "\n\n";