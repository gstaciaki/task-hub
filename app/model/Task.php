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

    public function save(): bool {

        if(!$this->isValid()) return false;

        $this->id = count(file(self::DB_PATH));
        file_put_contents(self::DB_PATH, $this->title . PHP_EOL, FILE_APPEND);
        return true;
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
}