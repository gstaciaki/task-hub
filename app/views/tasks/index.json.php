<?php

$tasksToJson = [];
$tasks = $response['tasks'];

foreach ($tasks as $task) {
    $arrayOwners = array_map(function ($user) {
        return ['id' => $user->id, 'name' => $user->name, 'admin'=> $user->isAdmin()];
    }, $task->owners()->get());

    $tasksToJson[] = [
        'id' => $task->id,
        'title' => $task->title,
        'priority' => $task->priority,
        'status' => $task->status,
        'created_at' => $task->created_at,
        'finished_at' => $task->finished_at,
        'owners' => $arrayOwners
    ];
}

$json = $tasksToJson;
