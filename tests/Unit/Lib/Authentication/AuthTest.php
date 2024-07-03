<?php

namespace Tests\Unit\Lib\Authentication;

use Core\Http\Request;
use Lib\Authentication\Auth;
use App\Models\User;
use Lib\Authentication\JWT;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private User $user;
    private Request $request;
    public function setUp(): void
    {
        parent::setUp();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';
        $this->request = new Request();
        $this->user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $this->user->save();
    }

    public function test_login(): void
    {
        $auth = Auth::login($this->user);

        $THREE_HOURS_IN_TIMESTAMP = 1000 * 60 * 60 * 3;
        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];
        $payload = [
            "sub" => $this->user->id,
            "iat" => time(),
            "exp" => time() + $THREE_HOURS_IN_TIMESTAMP,
            "admin" => $this->user->isAdmin()
        ];

        $this->assertEquals(JWT::encode($header, $payload), $auth['accessToken']);
    }

    public function test_check(): void
    {
        $this->request->addHeaders(['Authorization' => 'Bearer ' . Auth::login($this->user)['accessToken']]);
        $this->assertTrue(Auth::check($this->request));
    }

    public function test_user(): void
    {
        $this->request->addHeaders(['Authorization' => 'Bearer ' . Auth::login($this->user)['accessToken']]);

        $userFromSession = Auth::user($this->request);

        $this->assertEquals($this->user->id, $userFromSession->id);
    }

    public function test_logout(): void
    {
        $token = Auth::login($this->user)['accessToken'];
        $this->request->addHeaders(['Authorization' => 'Bearer ' . $token]);
        $this->assertTrue(Auth::check($this->request));

        Auth::logout($token);
        $this->assertFalse(Auth::check($this->request));
    }
}
