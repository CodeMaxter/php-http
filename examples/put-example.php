<?php
require_once './../Http.php';

$client = new Http();

$url = 'http://localhost:8000/server.php';

$data = [
    'username' => 'john@domain.com',
    'password' => 'pasword12345',
];

$options = [
//    'header' => [
//        'Content-Type: application/form-data',
//    ],
];

echo '<pre>';
$response = $client->put($url, $data, $options);
echo $response;