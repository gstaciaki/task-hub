<?php

require '/var/www/app/models/Task.php';

$method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'];
header('Content-Type: application/json; charset=utf-8');

if ($method !== 'GET') {
    http_response_code(405);
    exit;
}

$task = Task::findById($id);

echo json_encode(['id' => $task->getId(), 'title' => $task->getTitle()]);
?>