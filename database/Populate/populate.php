<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\TasksPopulate;
use Database\Populate\UsersPopulate;

Database::migrate();

TasksPopulate::populate();
UsersPopulate::populate();