<?php

namespace Lib\Authentication;

use App\Models\User;
use Core\Http\Request;

class Auth
{
    public static function login($user): int
    {
        return $user->id;
    }

    public static function user(Request $request): ?User
    {
        if ($request->getHeader('Authorization')) {
            $id = $request->getHeader('Authorization');
            return User::findById($id);
        }

        return null;
    }

    public static function check(Request $request): bool
    {
        return $request->getHeader('Authorization') && self::user($request) !== null;
    }

    public static function logout(User $user): void
    {
    }
}
