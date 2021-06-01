<?php

declare(strict_types=1);

namespace App\Security;

use Firebase\JWT\JWT;

class JwtToken
{
    public static function encode(
        array $data,
        string $secret,
        int $secondsToExpire
    ): string {
        $issuedAt = time();
        $expireAt = $issuedAt + $secondsToExpire;

        $data['iat'] = $issuedAt;
        $data['exp'] = $expireAt;

        return JWT::encode($data, $secret);
    }

    public static function decode(string $jwtToken, string $secret): object
    {
        return JWT::decode($jwtToken, $secret, ['HS256']);
    }
}
