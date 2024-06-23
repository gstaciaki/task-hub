<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_should_create_new_task(): void
    {
        $task = new Task(title: 'Task 1');

        $this->assertTrue($task->save());
        $this->assertCount(1, Task::all());
    }

    public function test_all_should_return_all_tasks(): void
    {
        $tasks[] = new Task(title: 'Task 1');
        $tasks[] = new Task(title: 'Task 2');

        foreach ($tasks as $task) {
            $task->save();
        }

        $all = Task::all();
        $this->assertCount(2, $all);
        $this->assertEquals($tasks, $all);
    }

    public function test_destroy_should_remove_the_task(): void
    {
        $task1 = new Task(title: 'Task 1');
        $task2 = new Task(title: 'Task 2');

        $task1->save();
        $task2->save();
        $task2->destroy();

        $this->assertCount(1, Task::all());
    }

    public function test_set_title(): void
    {
        $task = new Task(title: 'Task 1');
        $this->assertEquals('Task 1', $task->getTitle());
    }

    public function test_set_id(): void
    {
        $task = new Task(title: 'Task Test');
        $task->setId(7);

        $this->assertEquals(7, $task->getId());
    }

    public function test_errors_should_return_title_error(): void
    {
        $task = new Task(title: 'Task 1');
        $task->setTitle('');

        $this->assertFalse($task->isValid());
        $this->assertFalse($task->save());
        $this->assertFalse($task->hasErrors());

        $this->assertEquals('não pode ser vazio!', $task->errors('title'));
    }

    public function test_find_by_id_should_return_the_task(): void
    {
        $task2 = new Task(title: 'Task 2');
        $task1 = new Task(title: 'Task 1');
        $task3 = new Task(title: 'Task 3');

        $task1->save();
        $task2->save();
        $task3->save();

        $this->assertEquals($task1, Task::findById($task1->getId()));
    }

    public function test_find_by_id_should_return_null(): void
    {
        $task = new Task(title: 'Task 1');
        $task->save();

        $this->assertNull(Task::findById(7));
    }
}
