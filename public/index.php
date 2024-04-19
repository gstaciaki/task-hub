<?php
define('DB_PATH', '../database/task.txt');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $task = file_get_contents('php://input');
    if($task) file_put_contents(DB_PATH, $task . "\n", FILE_APPEND);
}

$tasks = file(DB_PATH, FILE_IGNORE_NEW_LINES);
echo json_encode($tasks);
?>
