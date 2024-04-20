<?php

require '/var/www/app/models/Task.php';

$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json; charset=utf-8');

if ($method !== 'POST') {
    http_response_code(405);
    exit;
}

$params = json_decode(file_get_contents('php://input'), true);

$task = new Task(title: $params['title']);

if($task->save()) {
    http_response_code(201);
    echo json_encode($task);
} else {
    http_response_code(400);
    echo json_encode($task->errors());
}
