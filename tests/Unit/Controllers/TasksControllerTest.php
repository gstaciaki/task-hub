<?php

namespace Tests\Unit\Controllers;

use App\Models\Task;
use App\Models\TaskOwnership;
use App\Models\User;

class TasksControllerTest extends ControllerTestCase
{
    public function test_list_user_all_tasks(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $tasks[] = new Task(['title' => 'Task 1']);
        $tasks[] = new Task(['title' => 'Task 2']);

        $taskNotOwned = new Task(['title' => 'Task 3']);
        $taskNotOwned->save();

        foreach ($tasks as $task) {
            $task->save();
            $taskOwnership = new TaskOwnership(['task_id' => $task->id, 'user_id' => $user->id]);
            $taskOwnership->save();
        }

        $response = $this->get(action: 'index', controller: 'App\Controllers\TasksController');

        foreach ($tasks as $task) {
            $this->assertMatchesRegularExpression("/{$task->title}/", $response);
        }

        $this->assertDoesNotMatchRegularExpression("/{$taskNotOwned->title}/", $response);
    }

    public function test_show_task(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $taskOwnership = new TaskOwnership(['task_id' => $task->id, 'user_id' => $user->id]);
        $taskOwnership->save();

        $response = $this->get(
            action: 'show',
            controller: 'App\Controllers\TasksController',
            params: ["id" => $task->id]
        );

        $this->assertMatchesRegularExpression("/{$task->title}/", $response);
    }

    public function test_should_return_an_error_if_task_id_is_not_found(): void
    {

        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $response = $this->get(action: 'show', controller: 'App\Controllers\TasksController', params: ["id" => 8545]);

        $this->assertMatchesRegularExpression("/not found/", $response);

        $response = $this->get(
            action: 'update',
            controller: 'App\Controllers\TasksController',
            params: ["id" => 8545]
        );

        $this->assertMatchesRegularExpression("/not found/", $response);

        $response = $this->get(
            action: 'destroy',
            controller: 'App\Controllers\TasksController',
            params: ["id" => 8545]
        );

        $this->assertMatchesRegularExpression("/not found/", $response);
    }

    public function test_create_task(): void
    {
        $this->assertCount(0, Task::all());

        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $this->get(action: 'create', controller: 'App\Controllers\TasksController', params: ["title" => 'new task']);


        $this->assertCount(1, Task::all());
    }

    public function test_should_return_an_error_if_propertie_is_invalid(): void
    {
        $this->assertCount(0, Task::all());

        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();
        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $taskOwnership = new TaskOwnership(['task_id' => $task->id, 'user_id' => $user->id]);
        $taskOwnership->save();

        $response = $this->get(
            action: 'create',
            controller: 'App\Controllers\TasksController',
            params: ["title" => '']
        );

        $this->assertMatchesRegularExpression("/error/", $response);

        $response = $this->get(
            action: 'update',
            controller: 'App\Controllers\TasksController',
            params: ["title" => '', 'id' => 1, 'owners' => [1]]
        );

        $this->assertMatchesRegularExpression("/error/", $response);
    }

    public function test_update_task(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $taskOwnership = new TaskOwnership(['task_id' => $task->id, 'user_id' => $user->id]);
        $taskOwnership->save();

        $response = $this->get(
            action: 'show',
            controller: 'App\Controllers\TasksController',
            params: ["id" => $task->id]
        );

        $this->assertMatchesRegularExpression("/{$task->title}/", $response);

        $data = [
            "id" => $task->id,
            "title" => "new title",
            "owners" => [1]
        ];

        $response = $this->get(action: 'update', controller: 'App\Controllers\TasksController', params: $data);

        $this->assertMatchesRegularExpression("/new title/", $response);
    }

    public function test_should_ignore_unknow_properties_on_update_task(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $taskOwnership = new TaskOwnership(['task_id' => $task->id, 'user_id' => $user->id]);
        $taskOwnership->save();

        $response = $this->get(
            action: 'show',
            controller: 'App\Controllers\TasksController',
            params: ["id" => $task->id]
        );

        $this->assertMatchesRegularExpression("/{$task->title}/", $response);

        $data = [
            "id" => $task->id,
            "title" => "new title",
            "owners" => [1],
            "unknowPropertie" => "unknow"
        ];

        $response = $this->get(action: 'update', controller: 'App\Controllers\TasksController', params: $data);

        $this->assertMatchesRegularExpression("/new title/", $response);
    }

    public function test_delete_task(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $taskOwnership = new TaskOwnership(['task_id' => $task->id, 'user_id' => $user->id]);
        $taskOwnership->save();

        $this->assertCount(1, Task::all());

        $this->get(action: 'destroy', controller: 'App\Controllers\TasksController', params: ["id" => $task->id]);

        $this->assertCount(0, Task::all());
    }
}
