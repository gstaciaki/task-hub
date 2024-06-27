<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_should_create_new_task(): void
    {
        $task = new Task(['title' => 'Task 1']);

        $this->assertTrue($task->save());
        $this->assertCount(1, Task::all());
    }

    public function test_all_should_return_all_tasks(): void
    {
        $tasks[] = new Task(['title' => 'Task 1']);
        $tasks[] = new Task(['title' => 'Task 2']);

        foreach ($tasks as $task) {
            $task->save();
        }

        $all = Task::all();
        $this->assertCount(2, $all);
        $this->assertEquals($tasks, $all);
    }

    public function test_destroy_should_remove_the_task(): void
    {
        $task1 = new Task(['title' => 'Task 1']);
        $task2 = new Task(['title' => 'Task 2']);

        $task1->save();
        $task2->save();
        $task2->destroy();

        $this->assertCount(1, Task::all());
    }

    public function test_set_title(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $this->assertEquals('Task 1', $task->title);
    }

    public function test_set_id(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $task->id = 7;

        $this->assertEquals(7, $task->id);
    }

    public function test_set_priority(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $task->priority = 'urgent';

        $this->assertEquals('urgent', $task->priority);
    }

    public function test_should_change_status_when_finished(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $task->save();
        $task = Task::findById($task->id);

        $this->assertEquals('open', $task->status);

        $task->finish();

        $this->assertEquals('closed', $task->status);
    }

    public function test_should_set_created_at_when_created_new_task(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $this->assertEquals(date('Y-m-d H:i:s'), $task->created_at);
    }

    public function test_set_finished_at_when_finished_task(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $task->save();
        $task->finish();

        $this->assertEquals(date('Y-m-d H:i:s'), $task->finished_at);
    }

    public function test_errors_should_return_title_error(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $task->title = '';

        $this->assertFalse($task->isValid());
        $this->assertFalse($task->save());
        $this->assertFalse($task->hasErrors());

        $this->assertEquals('não pode ser vazio!', $task->errors('title'));
    }

    public function test_find_by_id_should_return_the_task(): void
    {
        $task2 = new Task(['title' => 'Task 1']);
        $task1 = new Task(['title' => 'Task 2']);
        $task3 = new Task(['title' => 'Task 3']);

        $task1->save();
        $task2->save();
        $task3->save();

        $this->assertEquals($task1, Task::findById($task1->id));
    }

    public function test_find_by_id_should_return_null(): void
    {
        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $this->assertNull(Task::findById(7));
    }
}
