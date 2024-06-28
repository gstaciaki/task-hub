<?php

namespace Tests\Api\Tasks;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class AuthenticatedTest extends TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client([
            'base_uri' => 'http://web',
            'http_errors' => false,
        ]);
    }

    public function test_should_return_unauthorized_if_not_provided_credentials(): void
    {
        $response = $this->client->request('GET', 'tasks');

        $this->assertEquals(401, $response->getStatusCode());

        $response = $this->client->request('POST', 'tasks');

        $this->assertEquals(401, $response->getStatusCode());

        $response = $this->client->request('PUT', 'tasks/');

        $this->assertEquals(401, $response->getStatusCode());

        $response = $this->client->request('DELETE', 'tasks/');

        $this->assertEquals(401, $response->getStatusCode());
    }
}
