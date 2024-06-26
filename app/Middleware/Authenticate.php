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
            http_response_code(401);
            exit;
        }
    }
}
