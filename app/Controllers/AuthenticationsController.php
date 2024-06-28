<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

class AuthenticationsController extends Controller
{
    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        if (isset($params['email'])) {
            $user = User::findByEmail($params['email']);
        } else {
            http_response_code(400);
            exit;
        }

        if ($user && $user->authenticate($params['password'])) {
            $id = Auth::login($user);

            $this->render('authentications/login', compact('id'));
        } else {
            http_response_code(401);
            exit;
        }
    }

    public function destroy(Request $request): void
    {
        $user = Auth::user($request);

        Auth::logout($user);
        $this->render('authentications/logout');
    }
}
