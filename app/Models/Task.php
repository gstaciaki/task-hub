<?php

namespace App\Models;

use Core\Constants\Constants;

class Task
{
    /** @var array<string, string> */
    private array $errors = [];

    public function __construct(
        private string $title = '',
        private int $id = -1
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

        if ($this->newRecord()) {
            $this->id = file_exists(self::dbPath()) ? count(file(self::dbPath())) : 0;
            file_put_contents(self::dbPath(), $this->title . PHP_EOL, FILE_APPEND);
        } else {
            $tasks = file(self::dbPath(), FILE_IGNORE_NEW_LINES);
            $tasks[$this->id] = $this->title;

            $data = implode(PHP_EOL, $tasks);
            file_put_contents(self::dbPath(), $data . PHP_EOL);
        }

        return true;
    }


    public function destroy(): void
    {
        $tasks = file(self::dbPath(), FILE_IGNORE_NEW_LINES);
        unset($tasks[$this->id]);

        $data = implode(PHP_EOL, $tasks);
        file_put_contents(self::dbPath(), $data . PHP_EOL);
    }

    public function isValid(): bool
    {

        $this->errors = [];

        if (empty($this->title)) {
            $this->errors['title'] = 'nao pode ser vazio';
        }

        return empty($this->errors);
    }

    public function hasErros(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return array<string, string>
     */
    public function errors(): array | null
    {
        return $this->errors;
    }

    /**
     * @return array<int, Task>
     */
    public static function all(): array
    {
        $tasks = file(self::dbPath(), FILE_IGNORE_NEW_LINES);

        return array_map(function ($line, $title) {
            return new Task(id: $line, title: $title);
        }, array_keys($tasks), $tasks);
    }

    public static function findById(int $id): Task|null
    {
        $tasks = self::all();

        foreach ($tasks as $task) {
            if ($task->getId() === $id) {
                return $task;
            }
        }

        return null;
    }

    public function newRecord(): bool
    {
        return $this->id === -1;
    }

    private static function dbPath(): string
    {
        return Constants::databasePath() . $_ENV['DB_NAME'];
    }
}
