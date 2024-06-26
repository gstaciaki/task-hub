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
        $response = ['comments' => $comments];

        $this->render('comments/index', compact('response'));
    }

    public function show(Request $request): void
    {
        $taskId = $request->getParam("task_id");
        $id = $request->getParam("id");

        $comment = Comment::findBy(['id' => $id, 'task_id' => $taskId]);
        $response = ['comment' => $comment];

        $this->render('comments/show', compact('response'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $comment = new Comment($params);

        if ($comment->save()) {
            $this->responseCode = 201;
            $response = ['comment' => $comment];
        } else {
            $response = ['error' => $comment->errors()];
            $this->responseCode = 422;
        }

        $this->render('comments/show', compact('response'));
    }

    public function update(Request $request): void
    {
        $taskId = $request->getParam("task_id");
        $id = $request->getParam("id");

        $comment = Comment::findBy(['id' => $id, 'task_id' => $taskId]);
        $comment->description = $request->getParam('description');

        if ($comment->save()) {
            $this->responseCode = 201;
            $response = ['comment' => $comment];
        } else {
            $errors = $comment->errors();
            $response = ['error' => $comment->errors()];
            $this->responseCode = 422;
        }

        $this->render('comments/show', compact('response'));
    }

    public function destroy(Request $request): void
    {
        $taskId = $request->getParam("task_id");
        $id = $request->getParam("id");

        $comment = Comment::findBy(['id' => $id, 'task_id' => $taskId]);
        $comment->destroy();
        $response = ['comment' => $comment];

        $this->render('comments/show', compact('response'));
    }
}
