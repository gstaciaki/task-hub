<?php

foreach ($tasks as $task) {
    $tasksToJson[] = ['id' => $task->getId(), 'title' => $task->getTitle()];
}

$meta = [
    'currentPage' => $paginator->getPage(),
    'pages' => $paginator->totalOfPages(),
    'total' => $paginator->totalOfRegisters()
];
$json = ['meta' => $meta, 'tasks' => $tasksToJson];
