<?php

$json = [];

foreach ($users as $user) {
    $json[] = [
        'id' => $user->id,
        'name' => $user->name,
    ];
}
