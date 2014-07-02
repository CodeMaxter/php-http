<?php
require_once './../Http.php';

$client = new Http();

$url = 'http://localhost:8000/server.php';

$data = [
    'username' => 'john@domain.com',
    'password' => 'pasword12345',
];

$options = [
    'header' => [
        'Accept: application/json',
    ],
];

$response = $client->post($url, $data, $options);
echo $response;