<?php
require_once './../Http.php';

$client = new Http();

//$url = 'https://api.github.com/users/CodeMaxter/repos';
$url = 'http://localhost:8000/server.php';

$data = [
    'type' => 'owner',
    'otherData' => 'more data',
];

$options = [
    'timeout' => 30,
];

$response = $client->options($url, $data, $options);
var_dump($response);