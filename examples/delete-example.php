<?php
require_once './../Http.php';

$client = new Http();

$url = 'http://localhost:8000/server.php';

$data = [
    'id' => 1,
];

$options = [
//    'header' => [
//        'Content-Type: application/form-data',
//    ],
];

echo '<pre>';
$response = $client->delete($url, $data, $options);
echo $response;