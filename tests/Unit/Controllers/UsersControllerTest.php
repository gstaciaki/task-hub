<?php

namespace Tests\Unit\Controllers;

use App\Models\User;

class UsersControllerTest extends ControllerTestCase
{
    public function test_list_users_paginated(): void
    {
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

        $numberOfUsers = 5;

        for ($i = 1; $i < $numberOfUsers; $i++) {
            $data = [
                'name' => 'Fulano ' . $i,
                'email' => 'fulano' . $i . '@example.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'created_at' => date_create()->format('Y-m-d H:i:s'),
                'is_admin' => false
            ];

            $user = new User($data);
            $user->save();
        }

        $users = User::paginate(1, 10)->registers();

        $response = $this->get(action: 'index', controller: 'App\Controllers\UsersController');

        foreach ($users as $user) {
            $this->assertMatchesRegularExpression("/{$user->name}/", $response);
        }
    }

    public function test_show_user(): void
    {
        $data = [
            'name' => 'root',
            'email' => 'fulano@example.com',
            'password' => 'root',
            'password_confirmation' => 'root',
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => true
        ];

        $user = new User($data);
        $user->save();

        $response = $this->get(action: 'show', controller: 'App\Controllers\UsersController', params: ['id' => 1]);

        $this->assertMatchesRegularExpression("/{$user->name}/", $response);
    }

    public function test_should_create_new_user(): void
    {
        $this->assertCount(0, User::all());

        $data = [
            'name' => 'root',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'admin' => true
        ];

        $this->get(action: 'create', controller: 'App\Controllers\UsersController', params: $data);

        $this->assertCount(1, User::all());
    }

    public function test_should_edit_an_existing_user(): void
    {
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

        $editData = [
            'id' => $user->id,
            'name' => 'new name',
            'admin' => 'false'
        ];

        $this->get(action: 'update', controller: 'App\Controllers\UsersController', params: $editData);

        $user = User::findById($user->id);

        $this->assertEquals('new name', $user->name);
        $this->assertEquals('false', $user->is_admin);
    }

    public function test_should_delete_an_existing_user(): void
    {
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

        $this->assertCount(1, User::all());

        $this->get(action: 'destroy', controller: 'App\Controllers\UsersController', params: ['id' => 1]);

        $this->assertCount(0, User::all());
    }
}
