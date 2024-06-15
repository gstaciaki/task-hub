<?php

use App\Controllers\TasksController;
use Core\Router\Route;

Route::get('/tasks', [TasksController::class, 'index']);
