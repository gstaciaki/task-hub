<?php

namespace App\Controllers;

use App\Models\TaskOwnership;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class OwnerTasksController extends Controller
{
    public function addOwner(Request $request): void
    {
        $task_id = $request->getParam('task_id');
        $user_id = $request->getParam('user_id');

        $taskOwner = new TaskOwnership(
            ['task_id' => $task_id, 'user_id' => $user_id]
        );

        if ($taskOwner->save()) {
            $response = $taskOwner;
        } else {
            $response = $taskOwner->errors('user_id');
        }

        $this->render('owner_tasks/add', compact('response'));
    }

    public function removeOwner(Request $request): void
    {
        $task_id = $request->getParam('id');
        $user_id = $request->getParam('user_id');

        $taskOwner = TaskOwnership::findBy(
            ['user_id' => $user_id, 'task_id' => $task_id]
        );

        if ($taskOwner == null) {
            $response = ['error' => 'Task Owner Not Found'];
        } else {
            $taskOwner->destroy();
            $response = ['message' => 'Task Owner Removed'];
        }

        $this->render('owner_tasks/remove', compact('response'));
    }
}
