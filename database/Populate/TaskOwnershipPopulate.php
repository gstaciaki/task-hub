<?php

namespace Database\Populate;

use App\Models\Task;
use App\Models\TaskOwnership;
use App\Models\User;

class TaskOwnershipPopulate
{
    public static function populate()
    {
        $tasks = Task::all();

        $users = User::all();

        $numberOfOwners = count($users);

        foreach ($tasks as $task) {
            $numOwners = rand(1, count($users));

            for ($i = 0; $i < $numOwners; $i++) {
                $randomUserId = rand(1, count($users) - 1);
                $taskOwnership = new TaskOwnership(
                    ['task_id' => $task->id, 'user_id' => $randomUserId]
                );
                $taskOwnership->save();
            }
        }

        echo "Tasks Ownership populated with $numberOfOwners users per Task\n";
    }
}
