<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class Authenticate implements Middleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check($request)) {
            $errors = ['missing credentials'];

            $this->render('show', compact('errors'), 403);
            exit;
        }
    }

    /**
     * @param array<string, mixed> $data
     */

    public function render(string $view, array $data = [], int $responseCode = 200): void
    {
        extract($data);

        $view = '/var/www/app/views/tasks/' . $view . '.json.php';
        $json = [];

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($responseCode);
        require $view;
        echo json_encode($json);
    }
}
