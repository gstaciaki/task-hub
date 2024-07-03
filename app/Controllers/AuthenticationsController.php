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
            $this->responseCode = 400;
            exit;
        }

        if ($user && $user->authenticate($params['password'])) {
            $response = Auth::login($user);

            $this->render('authentications/login', compact('response'));
        } else {
            $this->responseCode = 401;
            exit;
        }
    }

    public function destroy(Request $request): void
    {
        $token = str_replace('Bearer ', '', $request->getHeader('Authorization'));
        Auth::logout($token);
        $this->render('authentications/logout');
    }
}
