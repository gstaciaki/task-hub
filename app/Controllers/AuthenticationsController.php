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
        $user = User::findByEmail($params['email']);

        if ($user && $user->authenticate($params['password'])) {
            $id = Auth::login($user);

            $this->render('authentications/login', compact('id'));
        } else {
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
