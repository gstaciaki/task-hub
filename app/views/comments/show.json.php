<?php

$json = empty($errors) ?
    ['id' => $comment->id, 'taskId' => $comment->task_id, 'description' => $comment->description]
    : ['errors' => $errors];
