<?php

namespace Lunzi\TopAuth\Guards;

use Lunzi\TopAuth\Contracts\Authenticatable;
use Lunzi\TopAuth\Contracts\Authenticatable as AuthenticatableContract;
use Lunzi\TopAuth\Contracts\Guard;
use Lunzi\TopAuth\Contracts\UserProvider;
use Lunzi\TopAuth\GuardHelpers;
use Lunzi\TopAuth\JWT;


class JWTGuard implements Guard
{
    use GuardHelpers;

    /**
     * JWT 实例
     * @var JWT
     */
    protected $jwt;

    public function __construct(JWT $jwt, UserProvider $provider)
    {
        $this->jwt = $jwt;
        $this->provider = $provider;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $token = $this->getToken();
        if ($token && $payload = $this->jwt->check($token)) {
            return $this->user = $this->provider->retrieveById($payload['sub']);
        }
    }

    public function login(AuthenticatableContract $user)
    {
        return $this->jwt->createToken($user->getAuthIdentifier());
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function setUser(Authenticatable $user)
    {
        // TODO: Implement setUser() method.
    }

    /**
     * 从 header 或者参数中获取 token
     * @return array|mixed|string
     */
    private function getToken()
    {
        $token = request()->header('Authorization');
        return $token ?? input('access_token');
    }
}