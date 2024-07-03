<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class AdminAccess implements Middleware
{
    public function handle(Request $request): void
    {
        $user = Auth::user($request);

        if ($user == null || !$user->isAdmin()) {
            http_response_code(403);
            exit;
        }
    }
}
