<?php
require_once './../Http.php';

$client = new Http();

//$url = 'https://api.github.com/users/CodeMaxter/repos';
$url = 'http://localhost:8000/server.php';

$data = ['type' => 'owner'];

$options = [
    'timeout' => 30,
];

$response = $client->get($url, $data, $options);
echo $response;