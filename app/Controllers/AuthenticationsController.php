<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Request;
use Lib\Authentication\Auth;

class AuthenticationsController
{
    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = User::findByEmail($params['email']);

        if ($user && $user->authenticate($params['password'])) {
            $id = Auth::login($user);

            $this->render('index', compact('id'));
        } else {
            exit;
        }
    }

    public function destroy(): void
    {
        Auth::logout();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function render(string $view, array $data = [], int $responseCode = 200): void
    {
        extract($data);

        $view = '/var/www/app/views/authentications/' . $view . '.json.php';
        $json = [];

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($responseCode);
        require $view;
        echo json_encode($json);
    }
}
