<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Task;
use Tests\TestCase;

class CommentTest extends TestCase
{
    private Comment $comment;
    private Task $task;

    public function setUp(): void
    {
        parent::setUp();
        $this->task = new Task([
            'title' => 'Task 1'
        ]);
        $this->task->save();

        $this->comment = new Comment(['description' => 'Comment 1', 'task_id' => $this->task->id]);
        $this->comment->save();
    }

    public function test_should_create_new_comment(): void
    {
        $this->assertTrue($this->comment->save());
        $this->assertCount(1, Comment::all());
    }

    public function test_all_should_return_all_comments(): void
    {
        $comments[] = $this->comment;
        $comments[] = $this->task->comments()->new(['description' => 'Comment 2']);
        $comments[1]->save();

        $all = Comment::all();
        $this->assertCount(2, $all);
        $this->assertEquals($comments, $all);
    }

    public function test_destroy_should_remove_the_comment(): void
    {
        $comment2 = $this->task->comments()->new(['description' => 'Comment 2']);

        $comment2->save();
        $comment2->destroy();

        $this->assertCount(1, Comment::all());
    }

    public function test_set_title(): void
    {
        $comment = $this->task->comments()->new(['description' => 'Comment 2']);
        $this->assertEquals('Comment 2', $comment->description);
    }

    public function test_set_id(): void
    {
        $comment = $this->task->comments()->new(['description' => 'Comment 2']);
        $comment->id = 7;

        $this->assertEquals(7, $comment->id);
    }

    public function test_errors_should_return_title_error(): void
    {
        $comment = $this->task->comments()->new(['description' => 'Comment 2']);
        $comment->description = '';

        $this->assertFalse($comment->isValid());
        $this->assertFalse($comment->save());
        $this->assertFalse($comment->hasErrors());
        $this->assertEquals('não pode ser vazio!', $comment->errors('description'));
    }

    public function test_find_by_id_should_return_the_comment(): void
    {
        $comment2 = $this->task->comments()->new(['description' => 'Comment 2']);
        $comment1 = $this->task->comments()->new(['description' => 'Comment 1']);
        $comment3 = $this->task->comments()->new(['description' => 'Comment 3']);

        $comment1->save();
        $comment2->save();
        $comment3->save();
        $this->assertEquals($comment1, Comment::findById($comment1->id));
    }

    public function test_find_by_id_should_return_null(): void
    {
        $comment = $this->task->comments()->new(['description' => 'Comment 2']);
        $comment->save();

        $this->assertNull(Comment::findById(7));
    }
}
