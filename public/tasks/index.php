<?php
require '/var/www/app/models/Task.php';

$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json; charset=utf-8');

if ($method !== 'GET') {
    http_response_code(405);
    exit;
}

$tasks = Task::all();

echo json_encode(array_map(function ($task) {
    return ['id' => $task->getId(), 'title' => $task->getTitle()];
}, $tasks));
