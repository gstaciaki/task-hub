<?php

namespace App\Controllers;

use App\Models\Task;

class TasksController {

    public function index() {
        $tasks = Task::all();

        $this->render('index', compact('tasks'));
    }

    public function show() {
        $id = $_GET['id'];

        $task = Task::findById($id);

        $this->render('show', compact('task'));
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('errors', [], 405);
            exit;
        }

        $params = json_decode(file_get_contents('php://input'), true);

        $task = new Task(title: $params['title']);

        if($task->save()) {
            $this->render('show', compact('task'), 201);
        } else {
            $errors = $task->errors();
            $this->render('errors', compact('errors'), 400);
        }

    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->render('errors', [], 405);
            exit;
        }   

        $queryParams = $_SERVER['QUERY_STRING'];
        preg_match_all('/id=(\d+)/', $queryParams, $matches);
        $id = $matches[1][0];

        $params = json_decode(file_get_contents('php://input'), true);

        $task = Task::findById($id);
        $task->setTitle($params['title']);

        if($task->save()) {
            $this->render('show', compact('task'));
        } else {
            $errors = $task->errors();
            $this->render('errors', compact('errors'), 400);
        }
    }

    public function destroy() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->render('errors', [], 405);
            exit;
        }   

        $queryParams = $_SERVER['QUERY_STRING'];
        preg_match_all('/id=(\d+)/', $queryParams, $matches);
        $id = $matches[1][0];

        $task = Task::findById($id);
        $task->destroy();
        $this->render('show', compact('task'));
    }

    public function render($view, $data = [], $responseCode = 200) {
        extract($data);

        $view = '/var/www/app/views/tasks/' . $view . '.json.php';
        $json = [];

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($responseCode);
        require $view;
        echo json_encode($json);
        return;
    }
}