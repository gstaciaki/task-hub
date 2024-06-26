<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\CommentsPopulate;
use Database\Populate\TaskOwnershipPopulate;
use Database\Populate\TasksPopulate;
use Database\Populate\UsersPopulate;

Database::migrate();

UsersPopulate::populate();
TasksPopulate::populate();
CommentsPopulate::populate();
TaskOwnershipPopulate::populate();
