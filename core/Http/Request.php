<?php

namespace Core\Http;

class Request
{
    private string $method;
    private string $uri;

    /** @var array<string, string> */
    private array|null $params = [];

    /** @var array<string, string> */
    private array $headers = [];

    public function __construct()
    {
        $this->method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->params = array_merge($_REQUEST, json_decode(file_get_contents('php://input'), true) ?? []);
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /** @return mixed[] */
    public function getParams(): array
    {
        return $this->params;
    }

    /** @return array<string, string>*/
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /** @param mixed[] $params*/
    public function addParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }

    public function acceptJson(): bool
    {
        return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === 'application/json');
    }
}
