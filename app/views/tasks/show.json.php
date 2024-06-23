<?php

$json = empty($errors) ? ['id' => $task->getId(), 'title' => $task->getTitle()] : ['errors' => $errors];
