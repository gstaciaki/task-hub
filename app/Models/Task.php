<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $title
 * @property User[] $owners
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

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user_owners', 'task_id', 'user_id');
    }

    public function removeOwners(): void
    {
        $owners = $this->owners()->get();

        foreach ($owners as $owner) {
            $taskOwner = TaskOwnership::findBy(['user_id' => $owner->id, 'task_id' => $this->id]);
            $taskOwner->destroy();
        }
    }

    public function isTaskOwner(int $userId): bool
    {
        $owners = $this->owners()->get();
        $ownersId = array_map(function ($owner) {
            return $owner->id;
        }, $owners);
        if (in_array($userId, $ownersId)) {
            return true;
        }

        return false;
    }
}
