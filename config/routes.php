<?php

use App\Controllers\TasksController;
use Core\Router\Route;

Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');

Route::post('/tasks', [TasksController::class, 'create'])->name('tasks.create');
