<?php

namespace App\Controllers;

use App\Models\Comment;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class CommentsController extends Controller
{
    public function index(Request $request): void
    {
        $taskId = $request->getParam("task_id");

        $comments = Comment::where(['task_id' => $taskId]);

        $this->render('comments/index', compact('comments'));
    }

    public function show(Request $request): void
    {
        $taskId = $request->getParam("task_id");
        $id = $request->getParam("id");

        $comment = Comment::findBy(['id' => $id, 'task_id' => $taskId]);

        $this->render('comments/show', compact('comment'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $comment = new Comment($params);

        if ($comment->save()) {
            $this->render('comments/show', compact('comment'), 201);
        } else {
            $errors = $comment->errors();
            $this->render('comments/show', compact('errors'), 422);
        }
    }

    public function update(Request $request): void
    {
        $taskId = $request->getParam("task_id");
        $id = $request->getParam("id");

        $comment = Comment::findBy(['id' => $id, 'task_id' => $taskId]);
        $comment->description = $request->getParam('description');

        if ($comment->save()) {
            $this->render('comments/show', compact('comment'), 201);
        } else {
            $errors = $comment->errors();
            $this->render('comments/show', compact('errors'), 422);
        }
    }

    public function destroy(Request $request): void
    {
        $taskId = $request->getParam("task_id");
        $id = $request->getParam("id");

        $comment = Comment::findBy(['id' => $id, 'task_id' => $taskId]);
        $comment->destroy();
        $this->render('comments/show', compact('comment'));
    }
}
