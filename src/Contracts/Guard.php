<?php

namespace Lunzi\TopAuth\Contracts;

interface Guard
{
    /**
     * 检查当前用户是否经过身份验证
     *
     * @return bool
     */
    public function check();

    /**
     * 检查当前用户是否游客
     *
     * @return bool
     */
    public function guest();

    /**
     * 获取当前用户
     *
     * @return Authenticatable|null
     */
    public function user();

    /**
     * 获取当前用户 ID.
     *
     * @return int|string|null
     */
    public function id();

    /**
     * 验证用户证书
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = []);

    /**
     * 设置当前用户
     *
     * @param  Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user);
}