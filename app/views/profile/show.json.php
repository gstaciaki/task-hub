<?php

$json = [
    "id" => $user->id,
    "name" => $user->name,
    "email" => $user->email,
    "avatar" => $_ENV['API_HOST'] . $user->avatar()->path(),
];
