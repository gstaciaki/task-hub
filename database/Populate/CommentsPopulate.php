<?php

namespace Database\Populate;

use App\Models\Comment;
use App\Models\Task;

class CommentsPopulate
{
    public static function populate()
    {
        $tasks = Task::all();

        $numberOfCommentsPerTask = 5;

        foreach ($tasks as $task) {
            for ($j = 0; $j < $numberOfCommentsPerTask; $j++) {
                $comment = new Comment(['description' => 'comment ' . $j . ' of ' . $task->title, 'task_id' => $task->id]);
                $comment->save();
            }
        }

        echo "Comments populated with $numberOfCommentsPerTask registers per Task\n";
    }
}
