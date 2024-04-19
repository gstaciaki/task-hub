<?php

$method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'];

if ($method !== 'GET') {
    http_response_code(405);
    exit;
}

define('DB_PATH', '/var/www/database/tasks.txt');

$tasks = file(DB_PATH, FILE_IGNORE_NEW_LINES);

echo json_encode($tasks[$id])
?>