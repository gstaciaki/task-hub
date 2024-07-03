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
        $encodedHeader = $jwtComponents[0];
        $encodedPayload = $jwtComponents[1];

        $header = str_replace(['-', '_', ''], ['+', '/', '='], base64_decode($encodedHeader));
        $payload = str_replace(['-', '_', ''], ['+', '/', '='], base64_decode($encodedPayload));

        return ['header' => json_decode($header, true), 'payload' => json_decode($payload, true)];
    }
}
