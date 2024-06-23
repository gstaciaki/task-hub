<?php

namespace App\Models;

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

    public function validates(): void
    {
        Validations::notEmpty('title', $this);
    }
}
