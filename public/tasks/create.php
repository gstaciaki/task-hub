<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    exit;
}

$errors = [];
$task = trim(file_get_contents('php://input'));


if (empty($task))
    $errors['task'] = 'task nao pode ser vazia';


if (empty($errors)) {
    define('DB_PATH', '../../database/tasks.txt');
    file_put_contents(DB_PATH, $task . "\n", FILE_APPEND);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["task" => $task]);    
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($errors);    
}
?>
