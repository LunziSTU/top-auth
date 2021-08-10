<?php

namespace Lunzi\TopAuth;

use Firebase\JWT\JWT as FirebaseJWT;

class JWT
{
    protected $key;

    public function __construct()
    {
        $this->key = config('topauth.jwt_key') ?? base64_encode(__FILE__);
    }

    public function check($token)
    {
        try {
            return FirebaseJWT::decode($token,$this->key, array('HS256'));
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function createToken($id)
    {
        $payload = array(
            "sub" => $id
        );
        return FirebaseJWT::encode($payload, $this->key);
    }
}