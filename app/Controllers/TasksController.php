<?php

namespace App\Controllers;

use App\Models\Task;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class TasksController extends Controller
{
    public function index(Request $request): void
    {
        $paginator = Task::paginate($request->getParam('page', 1), $request->getParam('per_page', 10));
        $tasks = $paginator->registers();

        $this->render('tasks/index', compact('paginator', 'tasks'));
        $this->render('tasks/index', compact('paginator', 'tasks'));
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $task = Task::findById($params['id']);

        $this->render('tasks/show', compact('task'));
        $this->render('tasks/show', compact('task'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $task = new Task($params);
        $task = new Task($params);

        if ($task->save()) {
            $this->render('tasks/show', compact('task'), 201);
            $this->render('tasks/show', compact('task'), 201);
        } else {
            $errors = $task->errors();
            $this->render('tasks/show', compact('errors'), 422);
            $this->render('tasks/show', compact('errors'), 422);
        }
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();
        $id = $params['id'];
        $id = $params['id'];

        $task = Task::findById($id);
        $task->title = $params['title'];
        $task = Task::findById($id);
        $task->title = $params['title'];

        if ($task->save()) {
            $this->render('tasks/show', compact('task'));
            $this->render('tasks/show', compact('task'));
        } else {
            $errors = $task->errors();
            $this->render('tasks/show', compact('errors'), 422);
            $this->render('tasks/show', compact('errors'), 422);
        }
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');
        $id = $request->getParam('id');

        $task = Task::findById($id);
        $task = Task::findById($id);
        $task->destroy();
        $this->render('tasks/show', compact('task'));
    }
}
