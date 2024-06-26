<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 */
class TaskOwnership extends Model
{
    protected static string $table = 'task_user_owners';
    protected static array $columns = ['task_id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('task_id', $this);
        Validations::notEmpty('user_id', $this);
        Validations::uniqueness(['task_id', 'user_id'], $this);
    }
}
