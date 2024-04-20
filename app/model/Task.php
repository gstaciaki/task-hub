<?php

class Task {

    const DB_PATH = '/var/www/database/tasks.txt';
    private $errors = [];

    public function __construct(
        private string $title = '',
        private int $id = -1
    ) {

    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->$id;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function save() {

        file_put_contents(self::DB_PATH, $this->title . PHP_EOL, FILE_APPEND);
        return true;
    }
}