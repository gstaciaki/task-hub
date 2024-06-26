<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\TaskOwnership;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class TasksController extends Controller
{
    public function index(Request $request): void
    {
        $tasks = $this->current_user->ownedTasks()->get();
        $response = ['tasks' => $tasks];

        $this->render('tasks/index', compact('response'));
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $task = Task::findById($params['id']);

        if ($task !== null) {
            if ($task->isTaskOwner($this->current_user->id)) {
                $response = ['task' => $task];
            } else {
                $response = ["error" => "not authorized"];
                $this->responseCode = 403;
            }
        } else {
            $response = ["error" => "task not found"];
            $this->responseCode = 404;
        }

        $this->render('tasks/show', compact('response'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $task = new Task($params);

        if ($task->save()) {
            $taskOwner = new TaskOwnership(['task_id' => $task->id, 'user_id' => $this->current_user->id]);
            $taskOwner->save();
            $this->responseCode = 201;
            $response = ['task' => $task];
        } else {
            $response = ['error' => $task->errors()];
            $this->responseCode = 422;
        }

        $this->render('tasks/show', compact('response'));
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();
        $id = $params['id'];

        $task = Task::findById($id);

        if ($task !== null) {
            if ($task->isTaskOwner($this->current_user->id)) {
                $task->removeOwners();

                $owners = $request->getParam('owners');

                foreach ($owners as $owner) {
                    $taskOwner = new TaskOwnership(['task_id' => $task->id, 'user_id' => $owner]);
                    $taskOwner->save();
                }

                $task->title = $params['title'];
                $owners = $task->owners()->get();

                if ($task->save()) {
                    $response = ['task' => $task];
                } else {
                    $response = ['error' => $task->errors()];
                    $this->responseCode = 422;
                }
            } else {
                $response = ["error" => "not authorized"];
                $this->responseCode = 403;
            }
        } else {
            $response = ["error" => "task not found"];
            $this->responseCode = 404;
        }

        $this->render('tasks/show', compact('response'));
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');

        $task = Task::findById($id);

        if ($task !== null) {
            if ($task->isTaskOwner($this->current_user->id)) {
                $response = ['task' => $task];
                $task->destroy();
            } else {
                $response = ["error" => "not authorized"];
                $this->responseCode = 403;
            }
        } else {
            $response = ["error" => "task not found"];
            $this->responseCode = 404;
        }
        $this->render('tasks/show', compact('response'));
    }
}
