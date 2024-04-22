<?php

namespace Tests\Unit\Controllers;

use App\Models\Task;

class TasksControllerTest extends ControllerTestCase
{
    public function test_list_all_tasks()
    {
        $tasks[] = new Task(title: 'Task 1');
        $tasks[] = new Task(title: 'Task 2');

        foreach ($tasks as $task) {
            $task->save();
        }

        $response = $this->get(action: 'index', controller: 'App\Controllers\TasksController');

        foreach ($tasks as $task) {
            $this->assertMatchesRegularExpression("/{$task->getTitle()}/", $response);
        }
    }
}