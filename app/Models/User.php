<?php

namespace App\Models;

use App\Services\ProfileAvatar;
use Core\Database\ActiveRecord\BelongsToMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property boolean $is_admin
 * @property string $created_at
 * @property Task[] $owned_tasks
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'encrypted_password', 'avatar_name', 'is_admin', 'created_at'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function ownedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_user_owners', 'user_id', 'task_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);

        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function validateProp(string $key): void
    {
        Validations::notEmpty($key, $this);
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByEmail(string $email): User|null
    {
        return User::findBy(['email' => $email]);
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    public function avatar(): ProfileAvatar
    {
        return new ProfileAvatar($this);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin == 1;
    }
}
