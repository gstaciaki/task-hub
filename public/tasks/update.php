<?php

require '/var/www/app/models/Task.php';

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

$params = json_decode(file_get_contents('php://input'), true);

$task = Task::findById($id);
$task->setTitle($params['title']);

if($task->save()) {
    echo json_encode($task);
} else {
    
}

?>
