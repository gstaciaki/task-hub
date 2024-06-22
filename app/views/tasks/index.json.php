<?php

foreach ($tasks as $task) {
    $tasksView[] = ['id' => $task->getId(), 'title' => $task->getTitle()];
}

$meta = [
    'currentPage' => $paginator->getCurrentPage(),
    'pages' => $paginator->totalOfPages(),
    'total' => $paginator->totalOfRegisters()
];
$json = ['meta' => $meta, 'tasks' => $tasksView];
