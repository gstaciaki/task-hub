<?php

namespace Core\Errors;

class ErrorsHandler
{
    public static function init()
    {
        new self();
    }

    private function __constructor()
    {
        ob_start();
        set_exception_handler($this->exceptionHandler());
        set_error_handler($this->errorHandler());
    }

    private static function exceptionHandler()
    {
        return function ($e) {
            ob_end_clean();

            header('HTTP/1.1 500 Internal Server Error');

            echo <<<HTML
                <h1>{$e->getMessage()}</h1>
                Uncaught exception class: {get_class($e)}<br>
                Message: <strong>{$e->getMessage()}</strong><br>
                File: {$e->getFile()}<br>
                Line: {$e->getLine()}<br>
                <br>
                Stack Trace: <br>
                <pre>
                    {$e->getTraceAsString()}
                </pre>
                HTML;
        };
    }

    private static function errorHandler()
    {
        return function ($errorNumber, $errorStr, $file, $line) {
            ob_end_clean();

            header('HTTP/1.1 500 Internal Server Error');

            switch ($errorNumber) {
                case E_USER_ERROR:
                    echo <<<HTML
                        <b>ERROR</b> [$errorNumber] $errorStr<br>
                        Fatal error on line $line in file $file<br>
                        PHP {PHP_VERSION} ({PHP_OS})<br>
                        Aborting...<br>
                        HTML;
                    exit(1);
                case E_USER_WARNING:
                    echo "<b>WARNING</b> [$errorStr] $errorStr<br>";
                    break;
                case E_USER_NOTICE:
                    echo "<b>NOTICE</b> [$errorNumber] $errorStr<br>";
                    break;
            }

            echo <<<HTML
                <h1>$errorStr</h1>
                File: $file <br>
                Line: $line <br>
                <br>
                Stack Trace: <br>
                HTML;

            echo '<pre>';
            debug_print_backtrace();
            echo '</pre>';

            exit();
        };
    }
}
