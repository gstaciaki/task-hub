<?php

$json = empty($errors) ? ['id' => $task->id, 'title' => $task->title] : ['errors' => $errors];
