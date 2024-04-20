<?php

require '/var/www/app/models/Task.php';

$method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];

if ($method !== 'DELETE') {
    http_response_code(405);
    exit;
}   

$queryParams = $_SERVER['QUERY_STRING'];
preg_match_all('/id=(\d+)/', $queryParams, $matches);
$id = $matches[1][0];

$task = Task::findById($id);
$task->destroy();

