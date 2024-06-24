<?php

foreach ($comments as $comment) {
    $json[] = ['id' => $comment->id, 'description' => $comment->description];
}
