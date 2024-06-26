<?php

if (isset($response['error'])) {
    $json = ['error' => $response['error']];
} else {
    $task = $response['task'];
    $owners = $task->owners()->get();

    $arrayOwners = array_map(function ($user) {
        return ['id' => $user->id, 'name' => $user->name];
    }, $owners);

    $json = ['id' => $task->id, 'title' => $task->title, 'owners' => $arrayOwners];
}
