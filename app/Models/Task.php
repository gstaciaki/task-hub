<?php

namespace App\Models;

class Task {

    private array $errors = [];

    public function __construct(
        private string $title = '',
        private int $id = -1
    ) {}

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function save(): bool {

        if(!$this->isValid()) return false;

        if($this->newRecord()) {
            $this->id = file_exists(self::DB_PATH()) ? count(file(self::DB_PATH())) : 0;
            file_put_contents(self::DB_PATH(), $this->title . PHP_EOL, FILE_APPEND);
        } else {
            $tasks = file(self::DB_PATH(), FILE_IGNORE_NEW_LINES);
            $tasks[$this->id] = $this->title;

            $data = implode(PHP_EOL, $tasks);
            file_put_contents(self::DB_PATH(), $data . PHP_EOL);
        }

        return true;
    }


    public function destroy() {
        $tasks = file(self::DB_PATH(), FILE_IGNORE_NEW_LINES);
        unset($tasks[$this->id]);

        $data = implode(PHP_EOL, $tasks);
        file_put_contents(self::DB_PATH(), $data . PHP_EOL);

    }

    public function isValid(): bool {

        $this->errors = [];

        if (empty($this->title)) $this->errors['title'] = 'nao pode ser vazio';

        return empty($this->errors);
    }

    public function hasErros(): bool {
        return empty($this->errors);
    }

    public function errors() {
        return $this->errors;
    }

    public static function all(): array {
        $tasks = file(self::DB_PATH(), FILE_IGNORE_NEW_LINES);

        return array_map(function ($line, $title) {
            return new Task(id: $line, title: $title);
        }, array_keys($tasks), $tasks);
    }

    public static function findById(int $id): Task|null {
        $tasks = self::all();

        foreach($tasks as $task) {
            if($task->getId() === $id) return $task;
        }

        return null;
    }

    public function newRecord(): bool {
        return $this->id === -1;
    }

    private static function DB_PATH() {
        return DATABASE_PATH . $_ENV['DB_NAME'];
    }
}