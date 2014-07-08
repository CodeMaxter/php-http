<?php
require_once '../Input.php';
echo '<pre>';

$data = [];

file_put_contents('server.txt', print_r($_SERVER, true));
file_put_contents('server.txt', print_r(getallheaders(), true), FILE_APPEND);
file_put_contents('server.txt', print_r(Input::get(), true), FILE_APPEND);

//if ('PUT' === $_SERVER['REQUEST_METHOD'] || 'DELETE' === $_SERVER['REQUEST_METHOD']) {
//    parse_str(file_get_contents('php://input'), $input);
//    var_dump($input);
////    die;
//}

echo json_encode([
    'error' => false,
    'message' => 'request received succesfuly',
    'method' => $_SERVER['REQUEST_METHOD'],
    'data' => Input::get(),
]);

die;