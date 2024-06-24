<?php

namespace App\Models;

use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $title
 */

class Task extends Model
{
    protected static string $table = 'tasks';
    protected static array $columns = ['title'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'task_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('title', $this);
    }
}
