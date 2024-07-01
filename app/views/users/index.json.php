<?php

$users = $response['users'];
$paginator = $response['paginator'];

foreach ($users as $user) {
    $usersToJson[] = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $_ENV['API_HOST'] . $user->avatar()->path(),
        'created_at' => $user->created_at,
        'admin' => $user->isAdmin(),
    ];
}

$meta = [
    'currentPage' => $paginator->getPage(),
    'pages' => $paginator->totalOfPages(),
    'total' => $paginator->totalOfRegisters()
];

$json = ['meta' => $meta, 'users' => $usersToJson];
