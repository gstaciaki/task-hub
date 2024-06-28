<?php

namespace Core\Http\Controllers;

use App\Models\User;
use Core\Constants\Constants;
use Core\Http\Request;
use Lib\Authentication\Auth;

class Controller
{
    protected string $layout = 'application';
    protected ?User $current_user = null;
    protected int $responseCode = 200;
    private Request $request;

    public function __construct(Request $request = new Request())
    {
        $this->request = $request;
        $this->current_user = Auth::user($this->request);
    }

    public function currentUser(): ?User
    {
        if ($this->current_user === null) {
            $this->current_user = Auth::user($this->request);
        }
        return $this->current_user;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $view = Constants::rootPath()->join('app/views/' . $view . '.json.php');
        $json = [];

        header('Content-Type: application/json; chartset=utf-8');
        http_response_code($this->responseCode);
        require $view;
        echo json_encode($json);
        return;
    }
}
