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
            foreach ($users as $user) {
                $taskOwnership = new TaskOwnership(
                    ['task_id' => $task->id, 'user_id' => $user->id]
                );
                $taskOwnership->save();
            }
        }

        echo "Tasks Ownership populated with $numberOfOwners users per Task\n";
    }
}
