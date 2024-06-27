<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property string $finished_at
 * @property string $priority
 * @property string $status
 * @property User[] $owners
 */

class Task extends Model
{
    protected static string $table = 'tasks';
    protected static array $columns = ['title', 'created_at', 'finished_at', 'priority', 'status'];

    /**
     * @param array<string, mixed> $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);

        if ($this->newRecord()) {
            $this->status = 'open';
            $this->created_at = date('Y-m-d H:i:s');
        }
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'task_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('title', $this);
    }

    public function validateProp(string $key): void
    {
        Validations::notEmpty($key, $this);
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

    public function finish(): void
    {
        $this->status = 'closed';
        $this->finished_at = date('Y-m-d H:i:s');
    }

    public function reOpen(): void
    {
        $this->status = 'open';
        $this->finished_at = '';
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
