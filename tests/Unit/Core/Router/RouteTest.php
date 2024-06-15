<?php

namespace Tests\Unit\Core\Router;

use Core\Constants\Constants;
use Core\Http\Request;
use Core\Router\Route;
use Core\Router\Router;
use Tests\TestCase;

class RouteTest extends TestCase
{
    public function test_should_create_route_using_constructor(): void
    {
        $route = new Route(method: 'GET', uri: '/', controllerName: MockController::class, actionName: 'action');

        $this->assertEquals('GET', $route->getMethod());
        $this->assertEquals('/', $route->getURI());
        $this->assertEquals(MockController::class, $route->getControllerName());
        $this->assertEquals('action', $route->getActionName());
    }

    public function test_should_add_route_to_the_router_method_get(): void
    {
        $routerReflection = new \ReflectionClass(Router::class);
        $instanceProperty = $routerReflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        // Store the original instance
        $originalInstance = $instanceProperty->getValue();

        $routerMock = $this->createMock(Router::class);
        $routerMock->expects($this->once())
            ->method('addRoute')
            ->with($this->callback(function ($route) {
                return $route instanceof Route
                    && $route->getMethod() === 'GET'
                    && $route->getUri() === '/test'
                    && $route->getControllerName() === 'TestController'
                    && $route->getActionName() === 'test';
            }));
        $instanceProperty->setValue(null, $routerMock);

        Route::get('/test', ['TestController', 'test']);

        // Restore the original instance
        $instanceProperty->setValue(null, $originalInstance);
    }

    public function test_should_add_route_to_the_router_method_post(): void
    {
        $routerReflection = new \ReflectionClass(Router::class);
        $instanceProperty = $routerReflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        // Store the original instance
        $originalInstance = $instanceProperty->getValue();

        $routerMock = $this->createMock(Router::class);
        $routerMock->expects($this->once())
            ->method('addRoute')
            ->with($this->callback(function ($route) {
                return $route instanceof Route
                    && $route->getMethod() === 'POST'
                    && $route->getUri() === '/test'
                    && $route->getControllerName() === 'TestController'
                    && $route->getActionName() === 'test';
            }));
        $instanceProperty->setValue(null, $routerMock);

        Route::post('/test', ['TestController', 'test']);

        // Restore the original instance
        $instanceProperty->setValue(null, $originalInstance);
    }

    public function test_match_should_return_true_if_method_and_uri_match(): void
    {
        $route = new Route(method: 'GET', uri: '/', controllerName: 'MockController', actionName: 'index');

        $this->assertTrue($route->match($this->request('GET', '/')));
    }

    public function test_match_should_return_false_if_method_and_uri_do_not_match(): void
    {
        $route = new Route(method: 'GET', uri: '/', controllerName: 'MockController', actionName: 'index');

        $this->assertFalse($route->match($this->request('POST', '/')));
        $this->assertFalse($route->match($this->request('GET', '/test')));
    }

    public function test_name_should_set_the_name_of_the_route(): void
    {
        $route = new Route(method: 'GET', uri: '/', controllerName: 'MockController', actionName: 'index');
        $route->name('root');

        $this->assertEquals('root', $route->getName());
    }

    private function request(string $method, string $uri): Request
    {
        require_once Constants::rootPath()->join('tests/Unit/Core/Http/header_mock.php');

        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        $_REQUEST = [];
        return new Request();
    }
}
