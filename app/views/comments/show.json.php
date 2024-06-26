<?php

if (isset($response['error'])) {
    $json = ['error' => $response['error']];
} else {
    $comment = $response['comment'];
    $json = ['id' => $comment->id, 'taskId' => $comment->task_id, 'description' => $comment->description];
}
