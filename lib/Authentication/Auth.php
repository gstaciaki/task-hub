<?php

namespace Lib\Authentication;

use App\Models\User;
use Core\Database\Database;
use Core\Http\Request;

class Auth
{
    public static function login($user): array
    {
        $THREE_HOURS_IN_TIMESTAMP = 1000 * 60 * 60 * 3;

        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];
        $payload = [
            "sub" => $user->id,
            "iat" => time(),
            "exp" => time() + $THREE_HOURS_IN_TIMESTAMP,
            "admin" => $user->isAdmin()
        ];

        $jwt = JWT::encode($header, $payload);
        self::store($jwt, $user->id);

        return [
            "accessToken" => $jwt,
            "type" => "Bearer",
            "expires" => $payload['exp']
        ];
    }

    public static function user(Request $request): ?User
    {
        if (Auth::check($request)) {
            $token = str_replace('Bearer ', '', $request->getHeader('Authorization'));
            $authInfo = JWT::decode($token);

            return User::findById($authInfo['payload']['sub']);
        }

        return null;
    }

    public static function check(Request $request): bool
    {
        $accessToken = $request->getHeader('Authorization');
        if (empty($accessToken)) {
            return false;
        }

        $jwt = str_replace('Bearer ', '', $request->getHeader('Authorization'));

        if ($jwt) {
            $authInfo = JWT::decode($jwt);
            return $authInfo['payload']['exp'] > time() && self::exists($jwt);
        }

        return false;
    }

    public static function logout(string $jwt): void
    {

        $pdo = Database::getDatabaseConn();

        $stmt = $pdo->prepare("
            DELETE FROM users_sessions
            WHERE jwt = :jwt
        ");

        $stmt->execute([
            ":jwt" => $jwt,
        ]);
    }

    private static function store(string $jwt, int $user_id): void
    {
        $pdo = Database::getDatabaseConn();

        $stmt = $pdo->prepare("
            DELETE FROM users_sessions
            WHERE user_id = :user_id
        ");

        $stmt->execute([
            ":user_id" => $user_id,
        ]);

        $stmt = $pdo->prepare("
            INSERT INTO users_sessions (user_id, jwt, created_at)
            VALUES (:user_id, :jwt, NOW())
        ");

        $stmt->execute([
            ':user_id' => $user_id,
            ':jwt' => $jwt
        ]);
    }

    private static function exists(string $jwt): bool
    {
        $pdo = Database::getDatabaseConn();

        $stmt = $pdo->prepare("
            SELECT jwt FROM users_sessions
            WHERE jwt = :jwt
        ");

        $stmt->execute([
            ":jwt" => $jwt,
        ]);

        return $stmt->rowCount() > 0;
    }
}
