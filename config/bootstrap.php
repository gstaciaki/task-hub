<?php

require __DIR__ . '/../vendor/autoload.php';

use Core\Errors\ErrorsHandler;
use Core\Env\EnvLoader;

ErrorsHandler::init();
EnvLoader::init();
