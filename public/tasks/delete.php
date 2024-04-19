<?php

$method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];

if ($method !== 'DELETE') {
    http_response_code(405);
    exit;
}   

$errors = [];

$queryParams = $_SERVER['QUERY_STRING'];
preg_match_all('/id=(\d+)/', $queryParams, $matches);
$id = $matches[1][0];


if (empty($errors)) {
    define('DB_PATH', '/var/www/database/tasks.txt');

    $tasks = file(DB_PATH, FILE_IGNORE_NEW_LINES);
    $task = $tasks[$id];
    unset($tasks[$id]);

    $data = implode(PHP_EOL, $tasks);
    file_put_contents(DB_PATH, $data . PHP_EOL);


    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["task" => $task]);    
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($errors);    
}
?>
