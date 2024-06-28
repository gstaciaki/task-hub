<?php

namespace Tests\Api\Middlewares;

use App\Models\User;
use Tests\TestCase;

abstract class MiddlewareTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $data = [
            'name' => 'root',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => true
        ];

        $user = new User($data);
        $user->save();

        $data = [
            'name' => 'user',
            'email' => 'fulano2@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => false
        ];

        $user = new User($data);
        $user->save();
    }
}
