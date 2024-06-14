<?php

use App\Controllers\TasksController;
use Core\Router\Route;

Route::get('/', [TasksController::class, 'index']);

Route::get('/tasks', [TasksController::class,'index']);