<?php

use App\Controllers\AuthenticationsController;
use App\Controllers\CommentsController;
use App\Controllers\ProfileController;
use App\Controllers\TasksController;
use App\Controllers\UsersController;
use Core\Router\Route;

// Authentication
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');

Route::middleware('auth')->group(function () {
    //Create
    Route::post('/tasks', [TasksController::class, 'create'])->name('tasks.create');
    Route::post('/tasks/{task_id}/comments', [CommentsController::class, 'create'])->name('comments.create');

    // Retrieve
    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.paginate');
    Route::get('/tasks/{id}', [TasksController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task_id}/comments', [CommentsController::class, 'index'])->name('comments.index');
    Route::get('/tasks/{task_id}/comments/{id}', [CommentsController::class, 'show'])->name('comments.show');

    // Update
    Route::put('/tasks/{id}', [TasksController::class, 'update'])->name('tasks.update');
    Route::put('/tasks/{task_id}/comments/{id}', [CommentsController::class, 'update'])->name('comments.update');

    // Delete
    Route::delete('/tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.destroy');
    Route::delete('/tasks/{task_id}/comments/{id}', [CommentsController::class, 'destroy'])->name('comments.destroy');


    Route::post('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Admin Routes
    Route::middleware('admin')->group(function () {
        // Users
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');

        Route::post('/users', [UsersController::class, 'create'])->name('users.create');

        Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');

        Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
    });
});
