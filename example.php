<?php
require_once './Http.php';

$client = new Http();

$url = 'https://api.github.com/users/CodeMaxter/repos';
//$url = 'http://www.bing.com';
$query = ['type' => 'owner'];
//$query = [];
$options = [
//    'auth' => ['username' => 'johndoe@email.com', 'password' => '123456'],
//    'timeout' => 10,
];
echo '<pre>';
$response = $client->get($url, $query, $options);
//echo $response;
print_r(json_decode($response));

$url = 'http://localhost/server.php';
$response = $client->post($url, $query, $options);
echo $response;

$client->close();