<?php

require '/var/www/config/bootstrap.php';

use App\Controllers\TasksController;

$controller = new TasksController();
$controller->update();
