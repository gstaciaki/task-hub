<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_can_set_title(): void
    {
        $task = new Task(title: 'Task 1');

        $this->assertEquals('Task 1', $task->getTitle());
    }

    public function test_should_create_new_task(): void
    {
        $task = new Task(title: 'Task 1');

        $this->assertTrue($task->save());
        $this->assertCount(1, Task::all());
    }
}
