<?php

namespace Core\Http\Controllers;

use App\Models\User;
use Core\Constants\Constants;
use Core\Http\Request;
use Lib\Authentication\Auth;

class Controller
{
    private ?User $currentUser = null;

    public function currentUser(Request $request): ?User
    {
        if ($this->currentUser === null) {
            $this->currentUser = Auth::user($request);
        }

        return $this->currentUser;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = [], int $responseCode = 200): void
    {
        extract($data);

        $view = Constants::rootPath()->join('app/views/' . $view . '.json.php');
        $json = [];

        header('Content-Type: application/json; chartset=utf-8');
        http_response_code($responseCode);
        require $view;
        echo json_encode($json);
        return;
    }
}
