<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $task_id
 * @property string $description
 */

class Comment extends Model
{
    protected static string $table = 'task_comments';
    protected static array $columns = ['task_id', 'description'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('task_id', $this);
        Validations::notEmpty('description', $this);
    }
}
