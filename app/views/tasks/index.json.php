<?php

$tasksToJson = [];
$tasks = $response['tasks'];

foreach ($tasks as $task) {
    $arrayOwners = array_map(function ($user) {
        return ['id' => $user->id, 'name' => $user->name];
    }, $task->owners()->get());

    $tasksToJson[] = ['id' => $task->id, 'title' => $task->title, 'owners' => $arrayOwners];
}

$json = $tasksToJson;
