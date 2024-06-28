<?php

namespace Tests\Unit\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;

class CommentsControllerTest extends ControllerTestCase
{
    public function test_list_comments_for_task(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $comment1 = new Comment(['task_id' => $task->id, 'description' => 'Comment 1']);
        $comment1->save();

        $comment2 = new Comment(['task_id' => $task->id, 'description' => 'Comment 2']);
        $comment2->save();

        $response = $this->get(
            action: 'index',
            controller: 'App\Controllers\CommentsController',
            params: ["task_id" => $task->id],
            headers: ['Authorization' => 1]
        );

        $this->assertMatchesRegularExpression("/{$comment1->description}/", $response);
        $this->assertMatchesRegularExpression("/{$comment2->description}/", $response);
    }

    public function test_show_comment(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $comment = new Comment(['task_id' => $task->id, 'description' => 'Test comment']);
        $comment->save();

        $response = $this->get(
            action: 'show',
            controller: 'App\Controllers\CommentsController',
            params: ["id" => $comment->id, "task_id" => $task->id],
            headers: ['Authorization' => 1]
        );

        $this->assertMatchesRegularExpression("/{$comment->description}/", $response);
    }

    public function test_create_comment(): void
    {
        $this->assertCount(0, Comment::all());

        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $this->get(
            action: 'create',
            controller: 'App\Controllers\CommentsController',
            params: ["task_id" => $task->id, "description" => 'New comment'],
            headers: ['Authorization' => 1]
        );

        $this->assertCount(1, Comment::all());
    }

    public function test_update_comment(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $comment = new Comment(['task_id' => $task->id, 'description' => 'Initial comment']);
        $comment->save();

        $data = [
            "id" => $comment->id,
            "task_id" => $task->id,
            "description" => "Updated comment"
        ];

        $this->get(
            action: 'update',
            controller: 'App\Controllers\CommentsController',
            params: $data,
            headers: ['Authorization' => 1]
        );

        $updatedComment = Comment::findById($comment->id);

        $this->assertEquals("Updated comment", $updatedComment->description);
    }

    public function test_delete_comment(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $task = new Task(['title' => 'Task 1']);
        $task->save();

        $comment = new Comment(['task_id' => $task->id, 'description' => 'To be deleted']);
        $comment->save();

        $this->assertCount(1, Comment::all());

        $this->get(
            action: 'destroy',
            controller: 'App\Controllers\CommentsController',
            params: ["id" => $comment->id, "task_id" => $task->id],
            headers: ['Authorization' => 1]
        );

        $this->assertCount(0, Comment::all());
    }
}
