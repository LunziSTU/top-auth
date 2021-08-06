<?php

namespace Lunzi\TopAuth\Contracts;

use Lunzi\TopAuth\Contracts\Authenticatable;

interface UserProvider
{
    /**
     * 通过id检索用户
     *
     * @param  mixed  $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier);

    /**
     * 通过token检索用户
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return Authenticatable|null
     */
    public function retrieveByToken($identifier, $token);

    /**
     * 更新 “记住我” token
     *
     * @param  Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token);

    /**
     * 通过 凭证 检索用户
     *
     * @param  array  $credentials
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials);

    /**
     * 根据给定的凭据验证用户
     *
     * @param  Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials);
}