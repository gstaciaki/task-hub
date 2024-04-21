<?php

foreach ($tasks as $task) {
    $json[] = ['id' => $task->getId(), 'title' => $task->getTitle()];
}

