<?php

namespace Tests\Unit\Lib\Authentication;

use Core\Constants\Constants;
use Core\Http\Request;
use Lib\Authentication\Auth;
use App\Models\User;
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
        $this->request->addHeaders(['Authorization' => $this->user->id]);
    }

    public function tearDown(): void
    {
        parent::setUp();
        $_SESSION = [];
    }
    public function test_login(): void
    {
        $id = Auth::login($this->user);
        $this->assertEquals(1, $id);
    }
    public function test_user(): void
    {
        Auth::login($this->user);

        $userFromSession = Auth::user($this->request);

        $this->assertEquals($this->user->id, $userFromSession->id);
    }

    public function test_check(): void
    {
        Auth::login($this->user);
        $this->assertTrue(Auth::check($this->request));
    }
}
