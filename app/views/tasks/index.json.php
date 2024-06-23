<?php

foreach ($tasks as $task) {
    $tasksToJson[] = ['id' => $task->id, 'title' => $task->title];
}

$meta = [
    'currentPage' => $paginator->getPage(),
    'pages' => $paginator->totalOfPages(),
    'total' => $paginator->totalOfRegisters()
];
$json = ['meta' => $meta, 'tasks' => $tasksToJson];
