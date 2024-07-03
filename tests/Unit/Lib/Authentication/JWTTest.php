<?php

namespace Tests\Unit\Lib\Authentication;

use App\Models\User;
use Lib\Authentication\JWT;
use Tests\TestCase;

class JWTTest extends TestCase
{
    public function test_should_return_valid_jwt(): void
    {
        $data = [
            'name' => 'root',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => true
        ];

        $user = new User($data);
        $user->save();


        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];
        $payload = [
            "sub" => $user->id,
            "iat" => 1719888411,
            "exp" => 1730688411,
            "admin" => $user->isAdmin()
        ];

        $mockJwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9'
            . '.eyJzdWIiOjEsImlhdCI6MTcxOTg4ODQxMSwiZXhwIjoxNzMwNjg4NDExLCJhZG1pbiI6dHJ1ZX0'
            . '.H247QIE0RSFyuEZXF2P-Fj_vj5QnB6bINqh8gAu52-o';
        $this->assertEquals($mockJwt, JWT::encode($header, $payload));
    }

    public function test_should_return_valid_payload_and_header_on_decode(): void
    {
        $data = [
            'name' => 'root',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => true
        ];

        $user = new User($data);
        $user->save();


        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];
        $payload = [
            "sub" => $user->id,
            "iat" => 1719888411,
            "exp" => 1730688411,
            "admin" => $user->isAdmin()
        ];

        $mockJwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9'
            . '.eyJzdWIiOjEsImlhdCI6MTcxOTg4ODQxMSwiZXhwIjoxNzMwNjg4NDExLCJhZG1pbiI6dHJ1ZX0'
            . '.H247QIE0RSFyuEZXF2P-Fj_vj5QnB6bINqh8gAu52-o';

        $decodedJwt = JWT::decode($mockJwt);

        $this->assertEquals($decodedJwt['payload'], $payload);
        $this->assertEquals($decodedJwt['header'], $header);
    }

    public function test_should_throw_a_exception_on_decode_with_signature(): void
    {
        $data = [
            'name' => 'root',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => true
        ];

        $user = new User($data);
        $user->save();


        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];
        $payload = [
            "sub" => $user->id,
            "iat" => 1719888411,
            "exp" => 1730688411,
            "admin" => $user->isAdmin()
        ];

        $invalidJwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9'
            . '.eyJzdWIiOjEsImlhdCI6MTcxOTg4ODQxMSwiZXhwIjoxNzMwNjg4NDExLCJhZG1pbiI6dHJ1ZX0'
            . '.H247QIE0RSFyuEZXF2P-Fj_vj5QnB6bINqh8gAu52-1';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JWT signature');

        $decodedJwt = JWT::decode($invalidJwt);
    }
}
