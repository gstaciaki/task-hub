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
        $tasks = $this->current_user->tasks()->get();
        $response = ['tasks' => $tasks];

        $this->render('tasks/index', compact('response'));
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $task = $this->current_user->tasks()->find($params['id']);

        if ($task !== null) {
            $response = ['task' => $task];
        } else {
            $response = ["error" => "task not found"];
            $this->responseCode = 404;
        }

        $this->render('tasks/show', compact('response'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $task = new Task(['title' => $params['title']]);

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

        /** @var ?Task $task */
        $task = $this->current_user->tasks()->find($params['id']);

        if ($task !== null) {
            $task->removeOwners();

            $owners = $request->getParam('owners');

            foreach ($owners as $owner) {
                $taskOwner = new TaskOwnership(['task_id' => $task->id, 'user_id' => $owner]);
                $taskOwner->save();
            }

            $data = [];
            foreach ($params as $key => $param) {
                switch ($key) {
                    case 'title':
                        $task->title = $param;
                        $data['title'] = $param;
                        $task->validateProp($key);
                        break;
                    case 'priority':
                        $task->priority = $param;
                        $data['priority'] = $param;
                        $task->validateProp($key);
                        break;
                    case 'status':
                        $task->validateProp($key);
                        if ($param == 'closed') {
                            $task->finish();
                        } else {
                            $task->reOpen();
                        }
                        $data['status'] = $task->status;
                        $data['finished_at'] = $task->finished_at;
                        break;
                    default:
                        break;
                }
            }

            $owners = $task->owners()->get();
            if ($task->hasErrors()) {
                $task->update($data);
                $response = ['task' => Task::findById($id)];
            } else {
                $response = ['error' => $task->errors()];
                $this->responseCode = 422;
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

        $task = $this->current_user->tasks()->find($id);

        if ($task !== null) {
            $response = ['task' => $task];
            $task->destroy();
        } else {
            $response = ["error" => "task not found"];
            $this->responseCode = 404;
        }
        $this->render('tasks/show', compact('response'));
    }
}
