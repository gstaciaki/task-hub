<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request): void
    {
        $paginator = User::paginate($request->getParam("id", 1), $request->getParam("per_page", 10));
        $response = ['users' => $paginator->registers(), 'paginator' => $paginator];

        $this->render('users/index', compact('response'));
    }

    public function show(Request $request): void
    {
        $id = $request->getParam('id');
        $user = User::findById($id);

        if ($user !== null) {
            $response = ['user' => $user];
        } else {
            $response = ['error' => 'User not found'];
            $this->responseCode = 404;
        }

        $this->render('users/show', compact('response'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $data = [
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => $params['password'],
            'password_confirmation' => $params['password_confirmation'],
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => $params['admin']
        ];
        $user = new User($data);

        if ($user->save()) {
            $response = ['user' => $user];
        } else {
            $response = ['error' => $user->errors()];
            $this->responseCode = 422;
        }

        $this->render('users/show', compact('response'));
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();
        $id = $params['id'];

        $user = User::findById($id);

        if ($user !== null) {
            $data = [];

            foreach ($params as $key => $param) {
                switch ($key) {
                    case 'name':
                        $user->name = $param;
                        $data['name'] = $param;
                        break;
                    case 'email':
                        $user->email = $param;
                        $data['email'] = $param;
                        break;
                    case 'password':
                        $user->encrypted_password = $param;
                        $data['encrypted_password'] = password_hash($param, PASSWORD_DEFAULT);
                        $key = 'encrypted_password';
                        break;
                    case 'admin':
                        $user->is_admin = $param;
                        $data['is_admin'] = $param;
                        $key = 'is_admin';
                        break;
                    default:
                        break;
                }
                $user->validateProp($key);
            }

            if ($user->hasErrors()) {
                $user->update($data);
                $response = ['user' => User::findById($id)];
            } else {
                $response = ['error' => $user->errors()];
                $this->responseCode = 422;
            }
        } else {
            $response = ['error' => 'User not found'];
            $this->responseCode = 404;
        }

        $this->render('users/show', compact('response'));
    }

    public function destroy(Request $request): void
    {
        $user = User::findById($request->getParam('id'));

        if ($user !== null) {
            $response = ['user' => $user];
            $user->destroy();
        } else {
            $response = ["error" => "user not found"];
            $this->responseCode = 404;
        }

        $this->render('users/show', compact('response'));
    }
}
