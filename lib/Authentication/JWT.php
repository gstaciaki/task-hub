<?php

namespace Lib\Authentication;

use Core\Constants\Constants;

class JWT
{
    private static $SECRET_KEY;

    public static function initialize(): void
    {
        $secretKeyPath = Constants::rootPath()->join('secret_key');
        if (file_exists($secretKeyPath)) {
            self::$SECRET_KEY = trim(file_get_contents($secretKeyPath));
        } else {
            throw new \Exception('Secret key file not found.');
        }
    }

    public static function encode(array $header, array $payload): string
    {
        if (self::$SECRET_KEY === null) {
            self::initialize();
        }

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$SECRET_KEY, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function decode(string $jwt): array
    {
        if (self::$SECRET_KEY === null) {
            self::initialize();
        }

        $jwtComponents = explode(".", $jwt);
        if (count($jwtComponents) !== 3) {
            throw new \Exception('Invalid JWT structure');
        }

        list($encodedHeader, $encodedPayload, $encodedSignature) = $jwtComponents;

        $header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $encodedHeader)), true);
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $encodedPayload)), true);
        $signatureProvided = base64_decode(str_replace(['-', '_'], ['+', '/'], $encodedSignature));

        $signature = hash_hmac('sha256', $encodedHeader . "." . $encodedPayload, self::$SECRET_KEY, true);

        if (!hash_equals($signature, $signatureProvided)) {
            throw new \Exception('Invalid JWT signature');
        }

        return ['header' => $header, 'payload' => $payload];
    }
}
