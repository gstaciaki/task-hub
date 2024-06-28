<?php

namespace Tests\Api\Middlewares;

use GuzzleHttp\Client;

class AdminAuthenticationTest extends MiddlewareTestCase
{
    protected Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client([
            'base_uri' => 'http://web',
            'http_errors' => false,
        ]);
    }

    public function test_should_return_forbidden_if_provided_invalid_non_admin_credentials(): void
    {
        $body = [
            'user' => [
                'email' => 'fulano2@example.com',
                'password' => '123456',
            ]
        ];

        $response = $this->client->request('POST', 'login', [
            'json' => $body,
        ]);

        $body = $response->getBody()->getContents();

        $data = json_decode($body, true);
        
        $headers = [
            'Authorization' => $data["userId"],
            'Content-Type' => 'application/json',
        ];

        $response = $this->client->request('GET', 'users', [
            'headers' => $headers,
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_should_return_status_ok_if_provided_admin_credentials(): void
    {
        $body = [
            'user' => [
                'email' => 'fulano@example.com',
                'password' => '123456',
            ]
        ];

        $response = $this->client->request('POST', 'login', [
            'json' => $body,
        ]);

        $body = $response->getBody()->getContents();

        $data = json_decode($body, true);

        $headers = [
            'Authorization' => $data["userId"],
            'Content-Type' => 'application/json',
        ];

        $response = $this->client->request('GET', 'users', [
            'headers' => $headers,
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
