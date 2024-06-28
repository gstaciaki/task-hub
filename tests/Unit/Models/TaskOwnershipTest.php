<?php

namespace Tests\Unit\Models;

use App\Models\TaskOwnership;
use Core\Database\ActiveRecord\BelongsTo;
use Tests\TestCase;

class TaskOwnershipTest extends TestCase
{
    public function test_should_create_new_task_ownership(): void
    {
        $taskOwnership = new TaskOwnership(['task_id' => 1, 'user_id' => 1]);

        $this->assertTrue($taskOwnership->save());
        $this->assertNotNull($taskOwnership->id);
    }

    public function test_should_find_task_ownership_by_id(): void
    {
        $taskOwnership = new TaskOwnership(['task_id' => 1, 'user_id' => 1]);
        $taskOwnership->save();

        $foundTaskOwnership = TaskOwnership::findById($taskOwnership->id);

        $this->assertEquals($taskOwnership->task_id, $foundTaskOwnership->task_id);
        $this->assertEquals($taskOwnership->user_id, $foundTaskOwnership->user_id);
    }

    public function test_should_delete_task_ownership(): void
    {
        $taskOwnership = new TaskOwnership(['task_id' => 1, 'user_id' => 1]);
        $taskOwnership->save();
        $taskOwnership->destroy();

        $this->assertNull(TaskOwnership::findById($taskOwnership->id));
    }

    public function test_relationship_with_user(): void
    {
        $taskOwnership = new TaskOwnership(['task_id' => 1, 'user_id' => 1]);

        $this->assertInstanceOf(BelongsTo::class, $taskOwnership->user());
    }

    public function test_relationship_with_task(): void
    {
        $taskOwnership = new TaskOwnership(['task_id' => 1, 'user_id' => 1]);

        $this->assertInstanceOf(BelongsTo::class, $taskOwnership->task());
    }

    public function test_validates_should_return_true_with_valid_data(): void
    {
        $taskOwnership = new TaskOwnership(['task_id' => 1, 'user_id' => 1]);

        $this->assertTrue($taskOwnership->isValid());
    }

    public function test_validates_should_return_false_with_invalid_data(): void
    {
        $taskOwnership = new TaskOwnership(['task_id' => null, 'user_id' => 1]);

        $this->assertFalse($taskOwnership->isValid());
    }
}
