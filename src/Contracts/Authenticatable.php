<?php

namespace Lunzi\TopAuth\Contracts;

interface Authenticatable
{
    /**
     * 获取用户ID名称
     *
     * @return string
     */
    public function getAuthIdentifierName();

    /**
     * 获取用户ID
     *
     * @return mixed
     */
    public function getAuthIdentifier();

    /**
     * 获取用户密码
     *
     * @return string
     */
    public function getAuthPassword();

    /**
     * 获取 “记住我” token
     *
     * @return string
     */
    public function getRememberToken();

    /**
     * 设置 “记住我” token 的值
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value);

    /**
     * 获取 “记住我” token 名称
     *
     * @return string
     */
    public function getRememberTokenName();
}