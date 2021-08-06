<?php

namespace Lunzi\TopAuth;

trait Authenticatable
{
    /**
     * "记住我" token 的字段名
     *
     * @var string
     */
    protected $rememberTokenName = 'remember_token';

    /**
     * 获取用户ID名称
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
//        模型主键
        return $this->getPk();
    }

    /**
     * 获取用户ID
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * 获取用户密码
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * 获取 “记住我” token
     *
     * @return string
     */
    public function getRememberToken()
    {
        if (! empty($this->getRememberTokenName()))
        {
            return (string) $this->{$this->getRememberTokenName()};
        }
    }

    /**
     * 设置 “记住我” token 的值
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        if (! empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * 获取 “记住我” token 名称
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }
}