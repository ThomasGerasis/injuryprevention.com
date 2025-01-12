<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtTokenHandler
{
    private string $privateKey;
    private string $publickey;

    public function __construct()
    {
        $this->privateKey = file_get_contents(ROOTPATH . 'certificate/localhost-key.pem');
        $this->publickey = file_get_contents(ROOTPATH . 'certificate/localhost.pem');
    }
    
    public function generateToken($profile): string
    {
        $now = time();
        $expirationTime = $now + MONTH * 2;
        $token = array(
            "iat" => $now,
            "exp" => $expirationTime,
            'username' => $profile['firstname'],
            'email' => $profile['email'],
        );
        return JWT::encode($token, $this->privateKey, 'RS256');
    }

    public function verifyToken($token): \stdClass
    {
        return JWT::decode($token, new Key($this->publickey, 'RS256'));
    }

}