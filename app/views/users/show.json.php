<?php

if (isset($response['error'])) {
    $json = ['error' => $response['error']];
} else {
    $user = $response['user'];

    $json = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $_ENV['API_HOST'] . $user->avatar()->path(),
        'created_at' => $user->created_at,
        'admin' => $user->isAdmin(),
    ];
}
