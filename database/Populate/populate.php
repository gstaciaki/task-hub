<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\TasksPopulate;

Database::migrate();

TasksPopulate::populate();
