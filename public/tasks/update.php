<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'PUT') {
    http_response_code(405);
    exit;
}   

$errors = [];
$task = trim(file_get_contents('php://input'));

$queryParams = $_SERVER['QUERY_STRING'];
preg_match_all('/id=(\d+)/', $queryParams, $matches);
$id = $matches[1][0];

if (empty($task))
    $errors['task'] = 'task nao pode ser vazia';


if (empty($errors)) {
    define('DB_PATH', '/var/www/database/tasks.txt');

    $tasks = file(DB_PATH, FILE_IGNORE_NEW_LINES);
    $tasks[$id] = $task;

    $data = implode(PHP_EOL, $tasks);
    file_put_contents(DB_PATH, $data . PHP_EOL);


    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["task" => $task]);    
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($errors);    
}
?>
