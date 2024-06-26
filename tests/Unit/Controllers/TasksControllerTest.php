<?php

namespace Tests\Unit\Controllers;

use App\Models\Task;
use App\Models\TaskOwnership;
use App\Models\User;

class TasksControllerTest extends ControllerTestCase
{
    public function test_list_all_tasks(): void
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

        foreach ($tasks as $task) {
            $task->save();
            $taskOwnership = new TaskOwnership(['task_id' => $task->id, 'user_id' => $user->id]);
            $taskOwnership->save();
        }

        $response = $this->get(action: 'index', controller: 'App\Controllers\TasksController');

        foreach ($tasks as $task) {
            $this->assertMatchesRegularExpression("/{$task->title}/", $response);
        }
    }
}
