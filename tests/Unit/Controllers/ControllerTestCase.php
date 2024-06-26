<?php

namespace Tests\Unit\Controllers;

use Core\Constants\Constants;
use Core\Http\Request;
use Tests\TestCase;

abstract class ControllerTestCase extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        require Constants::rootPath()->join('config/routes.php');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $this->request = new Request();
    }

    public function tearDown(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
        unset($_SERVER['REQUEST_URI']);
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed> $headers
     */
    public function get(string $action, string $controller, array $params = [], array $headers = []): string
    {
        $this->request->addParams($params);
        $this->request->addHeaders($headers);
        $controller = new $controller($this->request);

        ob_start();
        try {
            $controller->$action($this->request);
            return ob_get_contents();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            ob_end_clean();
        }
    }
}
