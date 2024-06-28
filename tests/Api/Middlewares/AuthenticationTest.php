<?php

namespace Tests\Api\Middlewares;

use GuzzleHttp\Client;

class AuthenticationTest extends MiddlewareTestCase
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

    public function test_should_return_user_id_if_provided_valid_credentials(): void
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

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_should_return_unauthorized_if_provided_invalid_credentials(): void
    {
        $body = [
            'user' => [
                'email' => 'wrong@example.com',
                'password' => 'vadvdad',
            ]
        ];

        $response = $this->client->request('POST', 'login', [
            'json' => $body,
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
