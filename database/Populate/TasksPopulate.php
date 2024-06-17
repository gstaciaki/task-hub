<?php

namespace Database\Populate;

use App\Models\Task;

class TasksPopulate
{
    public static function populate()
    {
        $numberOfTasks = 100;

        for ($i = 0; $i < $numberOfTasks; $i++) {
            $task = new Task(title: 'Task ' . $i);
            $task->save();
        }

        echo "Tasks populated with $numberOfTasks registers\n";
    }
}
