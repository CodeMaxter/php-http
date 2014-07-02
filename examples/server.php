<?php

echo '<pre>';

$data = [];

file_put_contents('server.txt', print_r($_SERVER, true));
file_put_contents('server.txt', print_r(getallheaders(), true), FILE_APPEND);

echo json_encode([
    'error' => false,
    'message' => 'request received succesfuly',
    'method' => $_SERVER['REQUEST_METHOD'],
]);

die;