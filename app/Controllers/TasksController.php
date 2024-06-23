<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\User;
use Core\Http\Request;
use Lib\Authentication\Auth;

class TasksController
{
    private ?User $currentUser = null;

    public function currentUser(Request $request): ?User
    {
        if ($this->currentUser === null) {
            $this->currentUser = Auth::user($request);
        }

        return $this->currentUser;
    }

    public function index(Request $request): void
    {
        $paginator = Task::paginate($request->getParam('page', 1), $request->getParam('per_page', 10));
        $tasks = $paginator->registers();

        $this->render('index', compact('paginator', 'tasks'));
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $task = Task::findById($params['id']);

        $this->render('show', compact('task'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $task = new Task(title: $params['title']);

        if ($task->save()) {
            $this->render('show', compact('task'), 201);
        } else {
            $errors = $task->errors();
            $this->render('show', compact('errors'), 422);
        }
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();

        $task = Task::findById($params['id']);
        $task->setTitle($params['title']);

        if ($task->save()) {
            $this->render('show', compact('task'));
        } else {
            $errors = $task->errors();
            $this->render('show', compact('errors'), 422);
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $task = Task::findById($params['id']);
        $task->destroy();
        $this->render('show', compact('task'));
    }

    /**
     * @param array<string, mixed> $data
     */

    public function render(string $view, array $data = [], int $responseCode = 200): void
    {
        extract($data);

        $view = '/var/www/app/views/tasks/' . $view . '.json.php';
        $json = [];

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($responseCode);
        require $view;
        echo json_encode($json);
    }
}
