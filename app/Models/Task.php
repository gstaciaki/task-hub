<?php

namespace App\Models;

use Core\Database\Database;
use Lib\Paginator;

class Task
{
    /** @var array<string, string> */
    private array $errors = [];

    public function __construct(
        private int $id = -1,
        private string $title = '',
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function save(): bool
    {

        if (!$this->isValid()) {
            return false;
        }

        $pdo = Database::getDatabaseConn();
        if ($this->newRecord()) {
            $sql = 'INSERT INTO tasks (title) VALUES (:title);';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':title', $this->title);

            $stmt->execute();

            $this->id = (int) $pdo->lastInsertId();
        } else {
            $sql = "UPDATE tasks SET title = :title WHERE id =:id;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':id', $this->id);

            $stmt->execute();
        }

        return true;
    }


    public function destroy(): bool
    {
        $pdo = Database::getDatabaseConn();

        $sql = 'DELETE FROM tasks WHERE id = :id;';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        return ($stmt->rowCount() !== 0);
    }

    public function isValid(): bool
    {

        $this->errors = [];

        if (empty($this->title)) {
            $this->errors['title'] = 'não pode ser vazio!';
        }

        return empty($this->errors);
    }

    public function hasErrors(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return array<string, string>
     */
    public function errors(string $index = ''): string | array
    {
        if (isset($this->errors[$index])) {
            return $this->errors[$index];
        }
        return $this->errors;
    }

    /**
     * @return array<int, Task>
     */
    public static function all(): array
    {
        $tasks = [];

        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query('SELECT id, title FROM tasks;');

        foreach ($resp as $row) {
            $tasks[] = new Task(id: $row['id'], title: $row['title']);
        }

        return $tasks;
    }

    public function newRecord(): bool
    {
        return $this->id === -1;
    }

    public static function findById(int $id): Task|null
    {
        $pdo = Database::getDatabaseConn();
        $sql = 'SELECT id, title FROM tasks WHERE id = :id;';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('id', $id);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $row = $stmt->fetch();

        return new Task(id: $row['id'], title: $row['title']);
    }

    public static function paginate(int $page = 1, int $per_page = 10): Paginator
    {
        return new Paginator(
            class: Task::class,
            page: $page,
            per_page: $per_page,
            table: 'tasks',
            attributes: ['title']
        );
    }
}
