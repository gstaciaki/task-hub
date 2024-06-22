<?php

use App\Controllers\TasksController;
use Core\Router\Route;

//Create
Route::post('/tasks', [TasksController::class, 'create'])->name('tasks.create');

// Retrieve
Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.paginate');
Route::get('/tasks/{id}', [TasksController::class, 'show'])->name('tasks.show');

// Update
Route::put('/tasks/{id}', [TasksController::class, 'update'])->name('tasks.update');

// Delete
Route::delete('/tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.destroy');
