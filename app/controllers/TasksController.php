<?php

require '/var/www/app/models/Task.php';

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

    public function render($view, $data = []) {
        extract($data);

        $view = '/var/www/app/views/tasks/' . $view . '.json.php';
        $json = [];

        header('Content-Type: application/json; charset=utf-8');
        require $view;
        echo json_encode($json);
        return;
    }
}